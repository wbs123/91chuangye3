function rem() {
	var iWidth = document.documentElement.getBoundingClientRect().width;
	iWidth = iWidth > 640 ? 640 : iWidth;	
	document.getElementsByTagName("html")[0].style.fontSize = iWidth / 10 + "px";
};
rem();
window.onorientationchange = window.onresize = rem;
window.onload = function() { }; 
