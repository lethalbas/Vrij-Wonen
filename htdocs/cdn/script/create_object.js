// create select2 object
$(function() {
    $('#js-citie-single').select2({
        placeholder: "Selecteer stad",
        allowClear: true
    });
    $('#js-properties-multiple').select2({
        placeholder: "Selecteer eigenschappen"
    });
});

let cont = $("#form-container-responsive");

// remove the 50% class off the form container and replace with 100% when screen is too narrow
$( window ).on("load", function() {
    if (screen.availWidth < 960){
        cont = $("#form-container-responsive");
        cont.removeClass("w-50");
        cont.addClass("w-100");
    }
});

// preview image in edit mode
function preview(image){
    $.alert({
        title: 'Huidige afbeelding:',
        content: '<img src="' + image + '" alt="preview" />'
    });
}

// add values to the properties select box in edit mode
let select_values = [];

function push_select(value){
    select_values.push(value);
}

function commit_select(){
    $('#js-properties-multiple').val(select_values);
}