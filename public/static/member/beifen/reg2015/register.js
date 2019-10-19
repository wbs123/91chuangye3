var Check = new Object();
Check.enterSubmit = function(button){
	document.onkeydown = function(event){
		var theEvent = window.event || event;
		var theCode = theEvent.keyCode || theEvent.which;
		if(theCode == 13) button.click();
	}
}
var leftSeconds = 90;
var timeIntervalId;
function TimeDown(content) {
    if(leftSeconds<=1){
        clearInterval(timeIntervalId);
        $("#checkBtn").attr('disabled',false);
        leftSeconds = 90;
        return false;
    }
    leftSeconds--;
    // $("#codeSpan").html(content+leftSeconds+'秒后可再次获取！');
    // if(leftSeconds > 1)
   	// {
        // $("#checkBtn").attr('disabled',true);
   	// }else{
		// $("#code").attr('class','inp3');
		// $('#codeSpan').attr('class','tipsBox');
   		// $("#codeSpan").html('');
   		// $("#checkBtn").attr('value','再次获取验证码');
        // $("#checkBtn").attr('disabled',false);
   	// }
}
$(function(){
	var check_select = false;
	$("#brandname").focus(function(){
		$(this).attr('class','inp1 selected');
		$("#brandnameSpan").attr('class','tipsBox default');
		$("#brandnameSpan").html('请输入您的品牌名称');
		check_select = false;
	});

	$("#brandname").keyup(function(){
		$(this).val($.trim($(this).val()));
		if($(this).val() != '')
		{
			$(this).attr('class','inp1');
			$('#brandnameSpan').attr('class','tipsBox ok');
			$('#brandnameSpan').html('');
			check_select = true;
		}
	});

	$("#brandname").blur(function(){
		$(this).val($.trim($(this).val()));
		if($(this).val() == ''){
			$(this).attr('class','inp1 errorAlert');
			$("#brandnameSpan").attr('class','tipsBox error');
			$("#brandnameSpan").html('请输入您的品牌名称');
			check_select = false;
		}else{
			
			$(this).attr('class','inp1');
			$('#brandnameSpan').attr('class','tipsBox ok');
			$('#brandnameSpan').html('');
			check_select = true;
		}
	});
	
	$("#submitCheckBtn").click(function(){
		
		if(check_select==true){
			
			$('#result_p').html("<img style='margin-left:175px;' src=\"http://images.qj.com.cn/reg/reg2015/loading32.gif\"</img>");
					
			var brandcname = $("#brandname").val();
		
			$.ajax({
				url : '/search!getBrandList.action',
				type : 'post',
				dataType: "html",
				data : {
					'brandcname' : brandcname
				},
				success : function(data){
					var result = eval('('+data+')');
					if(result.success) {
					
						var records = result.records;
						if(records.length>0){
							$('#result_p').html("");
							$('#result_p').append("<span class=\"treatyLink\"><span class=\"tipsBox default\" style=\"font-size:18px;\">您查询品牌已经存在</span></span>");
							for(var i in records) {
								$('#result_p').append("<span class=\"treatyLink\"><a href=\"https://www.qj.com.cn/brand/"+records[i].companyDomain+"/index/\" target=\"_blank\">"+records[i].brandCname+"</a><a href=\"tencent://message/?uin=87923608&Site=前景加盟网&Menu=yes\" class=\"rl\">认领</a></span>");
							}
						}else{
							$('#result_p').html("");
							$('#result_p').append("<span class=\"treatyLink\"><span class=\"tipsBox ok\" style=\"font-size:18px;\">可以开始注册了</span></span>");
							$('#result_p').append("<span class=\"treatyLink\"><a href=\"/register!toRegister.action\" class=\"kszc\">开始注册</a></span>");
							
						}
					} else {
						alert(result.errorMsg);
					}
				}
			});
			
		}
		
		
		
	});
	
	
});
