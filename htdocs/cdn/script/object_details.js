// open create inquiry page
function open_details(id){
    $.redirect('/contact-aanvraag-aanmaken', {
        "object": id
    });
}