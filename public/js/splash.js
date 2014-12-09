var Configurely = {};

Configurely.Graph = (function () {

    var graph;

    var w = $(window).width(),
        h = $(window).height() - $('body > .navbar').height(),
        x = d3.scale.linear().range([0, w]),
        y = d3.scale.linear().range([0, h]);
        
    var resize = function() {
        w = $(window).width();
        h = $(window).height() - $('body > .navbar').height();
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
    };
  
    return {
        init: function () {

            d3.json("d3data", function(root) {
                
                // don't display graph if there are no applications
                if(root.children == null) {
                    return false;
                }
                
                // remove extra white space just below nav bar
                $('body > .navbar').css('margin-bottom', '0');
                
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
                    .attr("class", function(d) { return (d.type ? d.type : 'root') + ' ' + (d.children ? "parent" : "child"); });
            
                g.append("svg:text")
                    .attr("transform", function(d) { return "translate(8," + (d.dx * h / 2 + 4) + ")"; })
                    .style("opacity", function(d) { return d.dx * h > 12 ? 1 : 0; })
                    .text(function(d) { return d.name; });

                d3.select(window)
                    .on("click", function() { click(root); })
                        
                function click(d) {
                    if(d3.event.shiftKey && d.id) {
                    var c = d;
                    var s = '';
                    while(c.parent) {
                        s = c.type + '/' + c.id + '/' + s;
                        c = c.parent;
                    }
                    window.location = '/api/v1/' + s;
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
                    
                    d3.event.stopPropagation();
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

