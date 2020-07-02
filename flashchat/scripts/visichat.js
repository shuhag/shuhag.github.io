// Variables
var ie=document.all;
var ns6=document.getElementById&&!document.all;
var loggedIn=false;
var isInternetExplorer = navigator.appName.indexOf("Microsoft") != -1;

// Visichat fscommand functions
function flashcontent_DoFSCommand(command, args) {
	var flashcontentObj = isInternetExplorer ? document.all.flashcontent : document.flashcontent;
	if(command == "login"){
		loggedIn=true;
	}
	if(command == "logout"){
		loggedIn=false;
	}
}
if (navigator.appName && navigator.appName.indexOf("Microsoft") != -1 && navigator.userAgent.indexOf("Windows") != -1 && navigator.userAgent.indexOf("Windows 3.1") == -1) {
	document.write('<script language=\"VBScript\"\>\n');
	document.write('On Error Resume Next\n');
	document.write('Sub flashcontent_FSCommand(ByVal command, ByVal args)\n');
	document.write('	Call flashcontent_DoFSCommand(command, args)\n');
	document.write('End Sub\n');
	document.write('</script\>\n');
}
// Events
window.onbeforeunload = confirmleave;
function confirmleave(){
	if(loggedIn){
		return "If you leave this page, you will be logged out!";
	}
}