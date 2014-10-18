if (!window.navigator.standalone) {
	document.addEventListener("DOMContentLoaded", adjustHeight, false);
} 
else {
	document.addEventListener("click", clickHandler, true);
}

function adjustHeight() {
	var html = document.documentElement;
	var size = window.innerHeight;

	html.style.height = (size + size) + "px";
	window.setTimeout(function() {
		if (window.pageYOffset == 0) {
			window.scrollTo(0, 0);
		}
		html.style.height = window.innerHeight + "px";
	}, 0);
}

/* Outgoing links */
function link(anchor) {
	window.location.href=anchor.href;
	return false;
}

/* Orientation LANDSCAPE/PORTRAIT */
function Orientation() {

	var contentType = "";
	switch(window.orientation) {

		case 0:
		contentType += "portrait";
		break;

		case -90:
		contentType += "landscape";
		break;

		case 90:
		contentType += "landscape";
		break;

		case 180:
		contentType += "portrait";
		break;
	
	}
	document.getElementById("wrapper").setAttribute("class", contentType);
}
