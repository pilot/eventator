$(function() {
    $('body').on('click', '[id*="modal-confirm-"]', function(e) {
        e.preventDefault();
        $('#confirm-modal').modal('show');
        var url = $(this).attr('href');
        $('#modal-confirm-action').attr('href', url);
    });

    $('body').on('click', '#modal-confirm-action', function(e) {
        e.preventDefault();
        var $that = $(this),
            url = $that.attr('href');

        window.location.href = url;
    });
});
