// delete object
function trash(id){
    $.confirm({
        title: 'Onomkeerbare actie!',
        content: 'Weet u zeker dat u de medewerker wilt verwijderen?',
        buttons: {
            verwijderen: function () {
                $.redirect('/beheerder/medewerkers-overzicht', {
                    "delete_id": id
                });
            },
            annuleren: function () {
                $.alert('Het verwijderen van de medewerker is geannuleerd.');
            }
        }
    });
}
