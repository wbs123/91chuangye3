$(function() {
	initProvince("msgProvince");
	$("#ProjectID").val($("#form_contact_us_ProjectID").val());
	$("#PageURL").val(window.location.href);
	$("#PageURLTitle").val(document.title);
});

function showSmall(value) {
	onProvinceChange(value, 'msgCity', 'msgXian', resetAddress);
}

function showXian(value) {
	onCityChange(value, 'msgXian', resetAddress);
}

function resetAddress() {
	var p = document.getElementById("msgProvince");
	var c = document.getElementById("msgCity");
	var d = document.getElementById("msgXian");
	var text = p.options[p.selectedIndex].text;
	text += $(c).val() == 0 ? '' : c.options[c.selectedIndex].text;
	text += $(d).val() == 0 ? '' : d.options[d.selectedIndex].text;
	$("#Address").val(text);
}

$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if(o[this.name]) {
			if(!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};

function quickmsgsubmit() {
	//判断是否选中
	if($('#msgform #checkbox').prop('checked') === false) {
		alert("请选择我同意将我的联系方式推荐给商家！");
		return false;
	}
	//是否含有中文（也包含日文和韩文）
	var reName = /^[a-zA-Z\u4e00-\u9fa5\uF900-\uFA2D ]{1,20}$/;;
	if(reName.test($("#Name").val()) === false) {
		alert("请输入正确的姓名！");
		$("#Name").focus().select();
		return false;
	}
	//支持手机号码，3-4位区号，7-8位直播号码，1－4位分机号
	var reTel =
		/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/;
	if(reTel.test($("#Tel").val()) === false) {
		alert("请输入正确的电话号码！");
		$("#Tel").focus().select();
		return false;
	}
	$("#imgBtnUp").val("正在提交...");
	var data = $("#msgform").serializeObject();
	var im = document.getElementById("InvestMoney");
	data.InvestMoney = im.options[im.selectedIndex].text;
	data.Gender = data.radiobutton == 0 ? '男' : '女';
	/*$.post(prefix + "jm/msgsubmit", data, function(result) {
		var json = $.parseJSON(result);
		alert(json.msg);
		$("#imgBtnUp").val("提交留言");
		if(typeof $("#_msg").dialog("instance") !== "undefined"){
			$("#_msg").dialog("destroy");
		}
	});*/
	var queryString = "";
	for(var key in data) {
		if(queryString.length > 0) {
			queryString += "&";
		}
		queryString = queryString + key + "=" + encodeURIComponent(data[key]);
	}
	$.ajax({
		type: "get",
		async: false,
//		url: prefix + "jm/msgsubmit?" + queryString + "&MessageSource=XQ005-001",
		url: "",
		dataType: "jsonp", //数据类型为jsonp
		jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
		jsonpCallback: "jsonpCallback",
		success: function(data) {
			if(data.status == '1') {
				custFeedback();
			};
			alert(data.msg);
			$("#imgBtnUp").val("提交留言");
			if(typeof $("#_msg").dialog("instance") !== "undefined") {
				$("#_msg").dialog("destroy");
			}
		},
		error: function() {
			alert('fail');
		}
	});

	$.ajax({
		url: "/guestbook.html",
		type: "GET",
		data: "action=post&" + $("#guestbookfield").val(),
		dataType: "text"
	});
}

$(function() {
	var ProjectID = $("#form_contact_us_ProjectID").val();
	var arr = "540567,640944,540660,640883,540544,641525,544974,540573,540488,540463".split(",");
	if($.inArray(ProjectID, arr) > -1) { //禁用以上项目的留言功能
		$("#msgform").find("input,button,textarea,select").attr("disabled", true);
	}
	//验证需要添加400电话的行业
	//	var catalogID = $("#form400").val();
	//	var arr = "17090,17088,17510,17569,17570,17571,17288,17092,17451,17086,17091,17214,17089,17217,17215,17289,17216,17087,17446,17448,17449,17474,17476,17565,17490,17501,17502,17503,17504,17511,17155,17468,17176".split(",");
	//	if ($.inArray(catalogID, arr) > -1) {//以上类显示400电话
	//	    $("#form-400").removeClass("dn");
	//	    $("#nav-400").removeClass("dn");
	//	}
	var Catalog = $("#form400").val();
	if(Catalog == "Y") {
		$(".nav-400, .form-400").removeClass("dn")
	};

	//提交电话号码
	$("#Free_phone_btn").click(function() {
		var reTel =
			/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/;
		if(reTel.test($("#Free_phone_text").val()) === false) {
			alert("请输入正确的电话号码！");
			$("#Free_phone_text").focus().select();
			return false;
		}
		var mobilephone = $("#Free_phone_text").val();
		$.ajax({
			type: "get",
			async: false,
			url: "jm/msgsubmit?ProjectID=" + ProjectID + "&url=" + window.location
				.href + "&URLTitle=" + document.title + "&Tel=" + mobilephone +
				"&MessageSource=XQ005-002",
			dataType: "jsonp", //数据类型为jsonp
			jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
			jsonpCallback: "jsonpCallback",
			success: function(data) {
				if(data.status == '1') {
					custFeedback();
				};
				alert("呼叫成功,请等候来电");

			},
			error: function() {
				alert('fail');
			}
		});

	});

	//快捷留言
	$(".quickMessage li").click(function() {
		var lit = $(this).find("p").text()
		$(".Message").val(lit)
	});
});

//客户留言统计
function custFeedback() {
	var IsPC = function() {
		var userAgentInfo = navigator.userAgent;
		var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
		var flag = true;
		for(var v = 0; v < Agents.length; v++) {
			if(userAgentInfo.indexOf(Agents[v]) > 0) {
				flag = false;
				break;
			}
		}
		return flag;
	}
	var isPc = IsPC() ? "PC" : "H5";
	var getCata = function() {
		var reg = new RegExp("https?://.+\.com/([^/]+)/([^/]+)/([^/]+)(/[^/]+)*\.shtml", "i");
		var reg2 = new RegExp("https?://.+\.com/([^/]+)/([^/]+)/", "i");
		var r = window.location.href.match(reg);
		if(r != null) {
			var catalog = RegExp.$1 + "_" + RegExp.$2;
			return catalog;
		} else {
			r = window.location.href.match(reg2);
			if(r != null) {
				return RegExp.$1 + "_" + RegExp.$2;
			}
		}
		return "";
	}
	var catalog = getCata();
	$.ajax({
		type: "get",
		async: true,
		url: "/api/stat/Cust/Feedback?" + "&source=" + isPc + "&catalog=" + catalog,
		success: function() {
			// alert("cust-success");
		},
		error: function() {
			// alert("cust-fail");
		}
	});
	
	
}