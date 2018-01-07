$('document').ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $("form button.live_save").hide();
    $('form .commitment').blur(function(e) {
        debugger;
        alert(this.attr('id'));
        e.preventDefault();

        if (this.val() > 0) {
        }
            // var name = $("#name").val();
            // var last_name = $("#last_name").val();
            // var dataString = 'name='+name+'&last_name='+last_name;
            // $.ajax({
            //     type:'POST',
            //     data:dataString,
            //     url:'insert.php',
            //     success:function(data) {
            //         alert(data);
            //     }
            // });
    });
});
