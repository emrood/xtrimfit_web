$(function() {
    $(window).keydown(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });

    setTimeout(function() {
        $(".alert").hide();
    }, 5000);
});
