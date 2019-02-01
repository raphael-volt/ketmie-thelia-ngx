$(document).ready(function () {
    $(".ctn-item").click(function (event) {
        if (event.target == this) {
            $(this).toggleClass("active")
            if (!$(this).hasClass("active")) {
                $(this).find(".active").removeClass("active")
            }
        }
    })
    $(document).click(function (event) {
        if (!$(event.target).parents("#menu").length == 1) {
            $("#menu").find(".active").removeClass("active")
        }
    })
})