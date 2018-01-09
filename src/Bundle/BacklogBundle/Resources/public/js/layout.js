$('document').ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $("form.commitment button.live_save").hide();
    $('form.commitment .man_days').blur(function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        if ($(this).val() > 0) {
            form.submit();
        }
    });
});
