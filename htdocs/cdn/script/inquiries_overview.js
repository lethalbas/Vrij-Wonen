// delete inquiry
function trash(id){
    $.confirm({
        title: 'Onomkeerbare actie!',
        content: 'Weet u zeker dat u de contactaanvraag wilt voltooien?',
        buttons: {
            voltooien: function () {
                $.redirect('/beheerder/contact-aanvragen-overzicht', {
                    "complete_id": id
                });
            },
            annuleren: function () {
                $.alert('Het voltooien van de aanvraag is geannuleerd.');
            }
        }
    });
}

// view inquiry details
function view_details(msg, fullname, email){
    $.alert({
        title: fullname + ", " + email,
        content: msg
    });
}

// view inquiry object details
function object_details(id){
    $.redirect('/object-details', {
        "id": id
    }, "POST", "_blank");
}