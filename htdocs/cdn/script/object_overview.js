$(function () {
    $('#js-citie-single').select2({
        placeholder: "Selecteer stad",
        allowClear: true
    });
    $('#js-properties-multiple').select2({
        placeholder: "Selecteer eigenschappen"
    });
    $("#add_btn").click(function (event) {
        event.preventDefault();
        $.redirect('/beheerder/object-aanmaken');
    });
});

let cont = $("#form-container-responsive");

// remove the 50% class off the form container and replace with 100% when screen is too narrow
$(window).on("load", function () {
    if (screen.availWidth < 960) {
        cont = $("#form-container-responsive");
        cont.removeClass("w-50");
        cont.addClass("w-100");
    }
});

// edit object
function edit(id) {
    $.redirect('/beheerder/object-aanmaken', {
        "object_id": id
    });
}

// open details page
function open_details(id){
    $.redirect('/object-details', {
        "id": id
    });
}
