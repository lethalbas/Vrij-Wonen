// create cookie
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";               

    document.cookie = name + "=" + value + expires + "; path=/";
}

// read cookie
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length).replaceAll("%20", " ");
    }
    return null;
}

// delete cookie
function eraseCookie(name) {
    createCookie(name, "", -1);
}

// onload check for notifications
$( document ).ready(function() {
    const notification_cookie = readCookie("notification");
    const notification_title = readCookie("notification_title");
    if(notification_cookie && notification_title){
        $.toast({
            title: 'Info!',
            subtitle: notification_title,
            content: notification_cookie,
            type: 'toast',
            delay: 2000,
            dismissible: false
        });
        eraseCookie("notification_title");
        eraseCookie("notification");
    }
});