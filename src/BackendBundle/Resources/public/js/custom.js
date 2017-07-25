//init component
$(function(){
    //enable flash back
    $.getJSON(window.container.flash_bag).done(function (logs) {
        $.each(logs, function (key, val) {
            $.each(val, function (k, v) {
                if (key == "error")
                    alertify.error(v);
                else
                    alertify.success(v);
            })
        });
    });
    //enable delete form
    $('[delete]').click(deleteConfirm);


    //enable enabled form
    $('[enabled]').click(enabledConfirm);

    //enable iCheck form
    $('input[type=checkbox],input[type=radio]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });

});

function deleteConfirm(e) {
    var message = '<h3 class="text-danger text-center">' + $(e.currentTarget).attr('delete') + '</h3>';
    var okLabel = $(e.currentTarget).attr('ok') ;
    var cancelLabel =$(e.currentTarget).attr('cancel') ;

    alertify.confirm("", message, function () {
        e.currentTarget.form.submit();
    }, function () {
    }).setting('labels', {'ok': okLabel, 'cancel': cancelLabel});
    e.preventDefault();
}

function enabledConfirm(e) {
    var message = '<h3 class="text-danger text-center">' + $(e.currentTarget).attr('enabled') + '</h3>';
    var okLabel = $(e.currentTarget).attr('ok') ;
    var cancelLabel =$(e.currentTarget).attr('cancel') ;

    alertify.confirm("", message, function () {
        e.currentTarget.form.submit();
    }, function () {
    }).setting('labels', {'ok': okLabel, 'cancel': cancelLabel});
    e.preventDefault();
}