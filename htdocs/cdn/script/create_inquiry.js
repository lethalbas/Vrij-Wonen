let cont = $("#form-container-responsive");

// remove the 50% class off the form container and replace with 100% when screen is too narrow
$( window ).on("load", function() {
    if (screen.availWidth < 960){
        cont = $("#form-container-responsive");
        cont.removeClass("w-50");
        cont.addClass("w-100");
    }
});