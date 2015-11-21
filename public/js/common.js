function alert(cls, msg, timeout) {
    $("#alert-pos").append(
        '<div class="fade in alert alert-'+cls+'" role="alert">'+
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
        '<span aria-hidden="true">&times;</span>'+
        '</button>' + msg + '</div>'
    );
    setTimeout(function(){
        $('.alert').alert('close');
    }, timeout === undefined ? 5000 : timeout);
}

var inAjax = false;

$(function() {

    $(".ajax-no").show();
    $(".ajax-yes").hide();

    $(document).ajaxStart(function () {
        $(".ajax-no").hide();
        $(".ajax-yes").show();
        inAjax = true;
    });

    $(document).ajaxComplete(function () {
        $(".ajax-no").show();
        $(".ajax-yes").hide();
        inAjax = false;
    });
});