// logout user
function log_out(){
    $.confirm({
        title: 'Uitloggen',
        content: 'Weet u zeker dat u wilt uitloggen?',
        buttons: {
            uitloggen: function () {
                $.redirect('/log-in', {'logout': 'true'});
            },
            annuleren: function () {
                $.alert('Het uitloggen is geannuleerd.');
            }
        }
    });
}