(function(){
	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + "; " + expires+";domain=.kmway.com;path=/";
	}
	//获取cookie
	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
	    }
	    return "";
	}
	function checkAndDel(){
		var cs = getCookie("TRACK_PROJECT");
		var rts = "";
		var m=1;
		if(cs!=""){
			var css = cs.split("|");
			for(var i = 0; m<=9&&i <css.length;i++){
				if(css[i]==""||css[i].indexOf($("#trackpid").val())>=0){
					continue;
				}
				rts +=css[i]+"|";
				m++;
			}
		}
		return rts;
	}
	if($("#trackpid").val()){
		var rts = checkAndDel();
		setCookie("TRACK_PROJECT",$("#trackpid").val()+"_"+new Date().getTime()+"|"+rts,30);
	}
	
})();
