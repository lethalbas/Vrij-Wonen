// delete staff member
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

// archive staff member
function archiveUser(id){
    $.confirm({
        title: 'Medewerker archiveren',
        content: 'Weet u zeker dat u deze medewerker wilt archiveren? De medewerker verliest alle toegangsrechten.',
        buttons: {
            archiveren: function () {
                $.redirect('/beheerder/medewerkers-overzicht', {
                    "archive_id": id
                });
            },
            annuleren: function () {
                $.alert('Het archiveren van de medewerker is geannuleerd.');
            }
        }
    });
}

// unarchive staff member
function unarchiveUser(id){
    $.confirm({
        title: 'Medewerker uit archief halen',
        content: 'Weet u zeker dat u deze medewerker uit het archief wilt halen? U moet daarna rollen toewijzen.',
        buttons: {
            'Uit archief halen': function () {
                $.redirect('/beheerder/medewerkers-overzicht', {
                    "unarchive_id": id
                });
            },
            annuleren: function () {
                $.alert('Het uit het archief halen van de medewerker is geannuleerd.');
            }
        }
    });
}