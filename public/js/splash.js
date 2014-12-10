var Configurely = {};

Configurely.Graph = (function () {

    var graph;
    //var menu;

    var w = $(window).width(),
        h = $(window).height() / 2 - $('body > .navbar').height(),
        x = d3.scale.linear().range([0, w]),
        y = d3.scale.linear().range([0, h]);
        
    var resize = function() {
        w = $(window).width();
        h = $(window).height() / 2 - $('body > .navbar').height();
        x = d3.scale.linear().range([0, w]);
        y = d3.scale.linear().range([0, h]);
        
        var g = graph
            .attr("width", w)
            .attr("height", h)
            .selectAll("g");
        
        g.attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; })
        g.selectAll("rect")
            .attr("width", d.dy * w)
            .attr("height", function(d) { return d.dx * h; });
            
        
        g.selectAll("text")
            .attr("transform", function(d) { return "translate(8," + (((d.dx * h) / 2) + 4) + ")"; })
            .style("opacity", function(d) { return d.dx * h > 10 ? 1 : 0; })
        
        /*
        g.selectAll("a")
            .attr("transform", function(d) { return  "translate(" + (d.dy * w - 16) + "," + (d.dx * h - 16) + ")"; })
        */
    };
  
    return {
        init: function () {

            d3.json("d3data", function(root) {
                
                // don't display graph if there are no applications
                if(root.children == null) {
                    return false;
                }
                
                // remove extra white space just below nav bar
                //$('body > .navbar').css('margin-bottom', '0');
                
                graph = d3.select("#graph")
                    .append("div")
                        .attr("class", "chart")
                    .append("svg:svg")
                        .attr("width", w)
                        .attr("height", h);
                        
                var partition = d3.layout.partition()
                    .value(function(d) { return d.size; });
                
                var g = graph.selectAll("g")
                    .data(partition.nodes(root))
                    .enter().append("svg:g")
                    .attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; })
                    .on("click", click);
                
                var kx = w / root.dx;
               
                g.append("svg:rect")
                    .attr("width", root.dy * kx)
                    .attr("height", function(d) { return d.dx * h; })
                    .attr("class", function(d) { return (d.type ? d.type : 'root') + ' ' + (d.children ? "parent" : "child"); })
                    .on("mouseover", function(d) { 
                        menu.transition()
                            .duration(500)
                            .attr("width", d.dy * w)
                            .attr("height", 16)
                            .attr("transform", "translate(" + d.y * w + "," + d.x * h + ")")
                            .style("opacity", function(d) { return 1; });
                    });
            
                g.append("svg:text")
                    .attr("transform", function(d) { return "translate(8," + (d.dx * h / 2 + 4) + ")"; })
                    .style("opacity", function(d) { return d.dx * h > 12 ? 1 : 0; })
                    .attr("class", function(d) { return (d.type ? d.type : 'root') + ' ' + (d.children ? "parent" : "child"); })
                    .text(function(d) { return d.name; });
                
                /*
                g.append("svg:a")
                    .attr("xlink:href", function(d){ return d.url ? d.url : '#'; })
                    .attr("transform", function(d) { return "translate(" + (d.dy * w - 16) + "," + (d.dx * h - 16) + ")" })
                    .append("svg:foreignObject")
                        .attr("width", 16)
                        .attr("height", 16)
                        .append("xhtml:span")
                            .attr("class", function(d) { return (d.type ? d.type : 'root') + ' link glyphicon glyphicon-new-window'; });
                */
 
                /*
                menu = graph.append("svg:rect")
                    .attr('class', 'menu')
                    .style("opacity", 0);
                */
                
                d3.select(window)
                    .on("click", function() { click(root); })
                        
                function click(d) {
                    if(d3.event) {
                        if(d3.event.shiftKey && d.id) {
                            var c = d;
                            var s = '';
                            while(c.parent) {
                                s = c.type + '/' + c.id + '/' + s;
                                c = c.parent;
                            }
                            window.location = '/api/v1/' + s;
                        }
                    }
                    
                    if (!d.children) return;

                    kx = (d.y ? w - 40 : w) / (1 - d.y);
                    var ky = h / d.dx;
                    x.domain([d.y, 1]).range([d.y ? 40 : 0, w]);
                    y.domain([d.x, d.x + d.dx]);
                    
                    var t = g.transition()
                        .duration(500)
                        .attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; });

                    t.select("rect")
                        .attr("width", d.dy * kx)
                        .attr("height", function(d) { return d.dx * ky; });

                    t.select("text")
                        .attr("transform", function(d) { return "translate(8," + (d.dx * ky / 2 + 4) + ")" })
                        .style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; });
                    
                    /*
                    t.select("a")
                        .attr("transform", function(d) { return "translate(" + (d.dy * kx - 16) + "," + (d.dx * ky - 16) + ")"; });
                    */
                    
                    if(d3.event) {
                        d3.event.stopPropagation();
                    }
                }
            });
        
            $(window).resize(function() {
                resize();
            });
        }
    };
})();

$(document).ready(function() {
    Configurely.Graph.init();
});

