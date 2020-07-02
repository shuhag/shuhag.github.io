<!-- Original:  Martin Webb (martin@irt.org) -->

<!-- Begin
function right(e) {
if (navigator.appName == 'Netscape' && 
(e.which == 3 || e.which == 2))
return false;
else if (navigator.appName == 'Microsoft Internet Explorer' && 
(event.button == 2 || event.button == 3)) {
alert("WebPool - ©2000 Luigi Crespi")
return false;
}
return true;
}

document.onmousedown=right;
if (document.layers) window.captureEvents(Event.MOUSEDOWN);
window.onmousedown=right;
//  End -->
