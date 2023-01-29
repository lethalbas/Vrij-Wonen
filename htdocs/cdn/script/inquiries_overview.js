// delete object
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

function view_details(msg, fullname, email){
    $.alert({
        title: fullname + ", " + email,
        content: msg
    });
}