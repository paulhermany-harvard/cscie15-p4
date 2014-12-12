$(function() {
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('form').attr('action', $(e.relatedTarget).data('action'));
        $(this).find('input[name=_method]').val('DELETE');
        $(this).find('.title').text($(e.relatedTarget).data('title'));
    });
    
    $('input.date.utc').each(function() {
        var m = moment($(this).val());
        var s = m.format('LLLL');
        $(this).val(s);
    });
});