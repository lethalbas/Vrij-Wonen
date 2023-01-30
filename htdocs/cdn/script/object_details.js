// open create inquiry page
function open_details(id){
    $.redirect('/contact-aanvraag-aanmaken', {
        "object": id
    });
}

// delete object
function trash(id) {
    $.confirm({
        title: 'Onomkeerbare actie!',
        content: 'Weet u zeker dat u het object wilt verwijderen? Dit verwijderd ook alle contactaanvragen omtrent dit object!',
        buttons: {
            verwijderen: function () {
                $.redirect('/beheerder/object_verwijderen', {
                    "delete_id": id
                });
            },
            annuleren: function () {
                $.alert('Het verwijderen van het object is geannuleerd.');
            }
        }
    });
}

// edit object
function edit(id) {
    $.redirect('/beheerder/object-bewerken', {
        "object_id": id
    });
}