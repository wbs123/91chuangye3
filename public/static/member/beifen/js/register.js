$(function(){
	//省市区
	$("#province").change(function(){
		if($(this).val() == '' || $(this).val() == '省/市')
		{			
			$("#address").attr('disabled',false);
			changeElementStyle('province','','error','请选择公司所在地');
		}else{
			$("#address").attr('disabled',false);
			changeElementStyle('province','','ok','');
		}
	});
	//注册日期获取焦点
	$("#buildtime").attr('readonly',true);
	$("#calendarSpan").click(function(){
		$("#buildtime").focus();
	});
	$("#buildtime").focus(function(){
		changeInputClass('buildtime','inp4 selected');
		changeSpanClass('calendarSpan','default');
//		changeElementStyle('calendarSpan','inp4 selected','tipsBox default',"");
	});
	$("#buildtime").blur(function(){
		if($("#buildtime").val() == '')
		{
			changeInputClass('buildtime','inp4');
			changeSpanClass('calendarSpan','error');
//			changeElementStyle('calendarSpan','','tipsBox error',"");
		}
		else
		{
			changeSpanClass('calendarSpan','ok');
			changeInputClass('buildtime','inp4');
		}
	});
	//企业类型
	$("#enterprise_type").change(function(){
		var submit_result;
		if($(this).val() == '0')
		{
			changeElementStyle('enterprise_type','','error','请选择企业类型');
			submit_result = false;
		}else{
			changeElementStyle('enterprise_type','','ok','');
			submit_result = true;
		}
		return submit_result;
	});
	
	
	$("#email").emailMatch({wrapLayer:"#infoBox"});
	
	
	$("#industry_id").change(function(){
	
		if($(this).val() == '')
		{
			changeElementStyle('industry_id','','error','请选择所属行业');
		}else{
			changeElementStyle('industry_id','','ok','');
		}
	});
	$("#brand_suminvest").change(function(){
		if($(this).val() == '')
		{
			changeElementStyle('brand_suminvest','','error','请选择公司所在地');
		}else{
			changeElementStyle('brand_suminvest','','ok','');
		}
	});
	
	
	$(".jinying_ms").click(function(){
		var checked = $('.jinying_ms:checked');
		var bool = false;
		$(checked).each(function(){
			bool = true;
		});
		if(!bool)
		{
			changeElementStyle('jinying_mode_label','','tipsBox error',"请选择发展模式模式");
		}
		else
		{
			changeElementStyle('jinying_mode_label','','tipsBox ok',"");
		}
	});
	
	
	$(".fazhan_ms").click(function(){
		var checked = $('.fazhan_ms:checked');
		var bool = false;
		$(checked).each(function(){
			bool = true;
		});
		if(!bool)
		{
			changeElementStyle('fazhan_mode_label','','tipsBox error',"请选择发展模式模式");
		}
		else
		{
			changeElementStyle('fazhan_mode_label','','tipsBox ok',"");
		}
	});

    layui.use(['upload', 'jquery'], function() {
        var $ = layui.jquery,layer = layui.layer, upload = layui.upload;

        //营业执照图片上传
        var uploadInst = upload.render({
            elem: '#btn_zhizhao'
            ,url: '/register!uploadImgUpdate.action'
            ,accept: 'images' //图片
            ,exts: 'jpg|bmp|png|jpeg' //只允许上传jpg、bmp、png、jpeg格式图片
            ,size: '3072'  //3M,限制文件上传大小
            ,field: 'img1'
            , before: function (obj) {
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#img_zhizhao').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //如果上传失败
                if (res.code > 0) {
                    return alert('上传失败');
                }
                //上传成功
                $('#companyCountry').val(res.data);
            }
            , error: function () {
                //实现重传
                var demoText = $('#re_btn_zhizhao');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function () {
                    uploadInst.upload();
                });
            }
        });

        //品牌授权书上传
        var uploadShouquan = upload.render({
            elem: '#btn_shouquan'
            ,url: '/register!uploadImgUpdate.action'
            ,accept: 'images' //图片
            ,exts: 'jpg|bmp|png|jpeg' //只允许上传jpg、bmp、png、jpeg格式图片
            ,size: '3072'  //3M,限制文件上传大小
            ,field: 'img1'
            , before: function (obj) {
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#img_shouquan').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //如果上传失败
                if (res.code > 0) {
                    return alert('上传失败');
                }
                //上传成功
                $('#companyCertificate').val(res.data);
            }
            , error: function () {
                //实现重传
                var demoText = $('#re_btn_shouquan');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function () {
                    uploadShouquan.upload();
                });
            }
        });

        //申请人身份证上传
        var uploadShenfenzheng = upload.render({
            elem: '#btn_shenfenzheng'
            ,url: '/register!uploadImgUpdate.action'
            ,accept: 'images' //图片
            ,exts: 'jpg|bmp|png|jpeg' //只允许上传jpg、bmp、png、jpeg格式图片
            ,size: '3072'  //3M,限制文件上传大小
            ,field: 'img1'
            , before: function (obj) {
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#img_shenfenzheng').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //如果上传失败
                if (res.code > 0) {
                    return alert('上传失败');
                }
                //上传成功
                $('#companyProposeridentity').val(res.data);
            }
            , error: function () {
                //实现重传
                var demoText = $('#re_btn_shenfenzheng');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function () {
                    uploadShenfenzheng.upload();
                });
            }
        });
    });

});
	//表单JS
	//全为空格
	var pspace = eval(/^\s+$/);
	//验证用户名正则      '/[\x{4e00}-\x{9fa5}！￥…（）—【】、；‘：“，。、《》？]/u'
//	var puName2 = /[\\u4E00-\\u9FA5\\uF900-\\uFA2D！￥…（）—【】、；‘：“，。、《》？]/;
	var puName = /\s+/;
	var puName1 =/^[\u4e00-\u9fa50-9a-z]{1,20}$/;
	//邮箱验证正则   
	var pemail = /^(\w(\w|_|-|\.)*@\w(\w|_|-|\.)*\.(com|edu|gov|int|mil|net|org|biz|info|pro|name|museum|coop|aero|xxx|idv|cn|hk|tw)){1,50}$/;//^[a-zA-Z0-9_]+@[a-zA-Z0-9]+[(\.com)(\.edu)(\.gov)(\.int)(\.mil)(\.net)(\.org)(\.biz)(\.info)(\.pro)(\.name)(\.museum)(\.coop)(\.aero)(\.xxx)(\.idv)(\.cn)(\.hk)(\.tw)]+$/i;
	//密码验证正则
//	var puPwd = /^[a-zA-Z0-9\`\~\!\@\#\$\%\\^\&\*\(\)\_\+\-\=\|\\\[\]\{\}\;\'\:\"\,\.\/\<\>\?]{6,20}$/;
	var puPwd = /^\d+[a-zA-Z][a-zA-Z\d]*|[a-zA-Z]+\d[a-zA-Z\d]*$/;
//	var puPwd = /\s+/;
	//联系人姓名正则
	var prealName = "^[\\u4E00-\\u9FA5\\uF900-\\uFA2Da-zA-Z]{1,20}$";
	//手机正则
	var pmobile = /^1[3458]{1}[0-9]{9}$/;
	//电话正则  
	var pphone = /^([0-9]{3,4}[\-\/]?)?\d{7,8}([\-\/][0-9]{1,8})*$/;
	//公司验证正则
	var pcompany_zh = /^\s+$/;
	
	//注册资金正则
	var preg_capital= "^[\\u4E00-\\u9FA5\\uF900-\\uFA2D\\d]+$";
	//品牌验证正则
	var brand_zh = /^[\-0-9a-zA-z\u4e00-\u9fa5]{0,10}$/;
	var brand_en = /^[a-zA-Z]$|^[a-zA-Z][a-zA-Z\s]{0,18}[a-zA-Z]$/;
	//品牌发源地正则(反向验证) 
	var pbrand_cradle = /^\s*$/;
	//品牌/项目投资额度、直销店、加盟店总数正则
	var plimit = /^\d+$/;
	//用户名验证
	function returnLength(str)
	{
		if(str.match(/[\u4e00-\u9fa5]/))
		{
			str = str.replace(/[\u4e00-\u9fa5]/,'');
			return returnLength(str);
		}
		else
		{
			if(str.length == 0) {return false;}
			else {return str.length;}
		}
	}
	function sub_str(str)
	{
		if(str.match(/[0-9a-zA-Z=\,\.\/\<\>\?\;\'\:\"\{\}\[\]\-\|\+\_\)\(\*\&\^\%\$\#\@\!\~\\]/))
		{
			str = str.replace(/[0-9a-zA-Z=\,\.\/\<\>\?\;\'\:\"\{\}\[\]\-\|\+\_\)\(\*\&\^\%\$\#\@\!\~\\]/,'');
			return sub_str(str);
		}
		else
		{
			if(str.length == 0) {return false;}
			else {return str.length;}
		}
	}
	function colationSpace(str)
	{
		str = $.trim(str);
		if(str.match(/\s/))
		{
			str = str.replace(/\s/,'');
			return colationSpace(str);
		}
		else
		{
			return str;
		}
	}
	function uNameblur()
	{
		$("#uName").val($.trim($("#uName").val()));
		var uName = $("#uName").val();
		var len1 = parseInt(uName.length);
		if(!returnLength(uName)){var len2 = 0;}
		else {var len2 = parseInt(returnLength(uName));}
		var len = (len1 - len2) * 2 + len2;
		var submit_result;
		if(uName == "" || uName.match(pspace))
		{		
			changeElementStyle('uName','inp1 errorAlert','tipsBox error','用户登录名不能为空');
			submit_result = false;
		}
		else if(!uName.match(puName1))
		{
			changeElementStyle('uName','inp1 errorAlert','tipsBox error','用户登录名不符合规则');
			submit_result = false;
		}
		else if(len > 20 || len <4)
		{		
			changeElementStyle('uName','inp1 errorAlert','tipsBox error','登录名的长度只能在4-20个字之间');
			submit_result = false;
		}
		else if($("#uName").val().match(puName))
		{
			changeElementStyle('uName','inp1 errorAlert','tipsBox error','登录名的长度只能在4-20个字之间');
			submit_result = false;
		}
		else
		{
			$.ajax({
				url:'/web.ajax/user/ckuser.jsp',
				type:'GET',
				async:false,
				data:"act=userck&username="+uName+"&rand"+Math.random(),
				success:function(data){
					if(data == 0){
						changeElementStyle('uName','inp1 errorAlert','tipsBox error',"该登录名已经被注册");
						submit_result = false;
					}
					else
					{
						changeElementStyle('uName','inp1','tipsBox ok','');
						submit_result = true;
					}
				}
			});
			
			
		}
		return submit_result;
	}
	
	
	
	function uNamefocus()
	{
		changeElementStyle('uName','inp1 selected','tipsBox default','用户名长度大于4位，小于20位，一旦注册成功，不可修改');
	}
	
	//密码验证
	function uPwdblur()
	{
		var uPwd = $("#uPwd").val();
		var submit_result;
		if(uPwd == "")
		{
			changeElementStyle('uPwd','inp1 errorAlert','error','密码不能为空。');
			submit_result = false;
		}
		else if($("#uName").val()!='' && $("#uName").val()==$("#uPwd").val())
		{
			changeElementStyle('uPwd','inp1 errorAlert','error','密码不能与用户登录名相同。');
			submit_result = false;
		}
		//else if(!$("#uPwd").val().match(puPwd))
		//{
			//changeElementStyle('uPwd','inp1 errorAlert','error','密码的长度只能在6-20个字之间，必须为字母与数字的组合');
			//submit_result = false;
		//}
		else
		{
			changeElementStyle('uPwd','inp1','ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function uPwdfocus()
	{
		changeElementStyle('uPwd','inp1 selected','tipsBox default','6~20个字符，不能与用户登录名相同。');
	}
	
	//验证二次密码
	function checkPwdblur()
	{
		var checkPwd = $("#checkPwd").val();
		var uPwd = $("#uPwd").val();
		var submit_result;
		if(checkPwd == "")
		{
			changeElementStyle('checkPwd','inp1 errorAlert','error','重复密码不能为空。');
			submit_result = false;
		}
		else if(checkPwd != uPwd)
		{
			changeElementStyle('checkPwd','inp1 errorAlert','error','两次密码输入不一致。');
			submit_result = false;
		}
		else
		{
			changeElementStyle('checkPwd','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	
	function checkPwdfocus()
	{
		changeElementStyle('checkPwd','inp1 selected','tipsBox default','请再输入一次您的密码。');
	}
	//企业资料:公司名称验证(中文)
	function company_zh_blur()
	{
		$("#company_zh").val($.trim($("#company_zh").val()));
		var submit_result;
		var comp = $("#company_zh").val();
		if(comp == ""  || comp.match(pspace))
		{
			changeElementStyle('company_zh','inp1 errorAlert','tipsBox error','企业中文名称不能为空');
			submit_result = false;
		}
		else if($("#company_zh").val().match(pcompany_zh))
		{
			changeElementStyle('company_zh','inp1 errorAlert','tipsBox error','企业中文名称不能为空');
			submit_result = false;
		}
		else
		{
			changeElementStyle('company_zh','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	
	function company_zh_focus()
	{
		changeElementStyle('company_zh','inp1 selected','tipsBox default','请输入公司中文名称，最多20个字');
	}
	
	//注册资金
	function register_capital_blur()
	{
		$("#register_capital").val($.trim($("#register_capital").val()));
		var capital = $("#register_capital").val();
		var submit_result;
		if(capital == "" || capital.match(pspace))
		{
			changeElementStyle('register_capital','inp4 errorAlert','tipsBox error','注册资金不能为空');
			submit_result = false;
		}
		else if(!$("#register_capital").val().match(preg_capital))
		{
			changeElementStyle('register_capital','inp4 errorAlert','tipsBox error','只能输入半角数字及中文');
			submit_result = false;
		}
		else
		{
			changeElementStyle('register_capital','inp4','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function register_capital_focus()
	{
		changeElementStyle('register_capital','inp4 selected','tipsBox default','请输入注册资金金额');
	}
	//详细地址
	function addressblur()
	{
		$("#address").val($.trim($("#address").val()));
		var address = $("#address").val();
		var submit_result;
		if(address == "" || address.match(pspace))
		{
			changeElementStyle('address','inp1 errorAlert','tipsBox error',"详细地址不能为空");
			submit_result = false;
		}
		else if($("#address").val().match(pbrand_cradle))
		{
			changeElementStyle('address','inp1 errorAlert','tipsBox error',"详细地址不能为空");
			submit_result = false;
		}
		else
		{
			changeElementStyle('address','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function addressfocus()
	{
		changeElementStyle('address','inp1 selected','tipsBox default','请输入公司的详细地址，最多30个字');
	}
	//联系人姓名验证
	function contactsblur()
	{
		$("#contacts").val($.trim($("#contacts").val()));
		var contacts = $("#contacts").val();
		var contacts_check = $("#contacts").val();
		var submit_result;
		if(contacts == "" || contacts.match(pspace))
		{
			changeElementStyle('contacts','inp1 errorAlert','tipsBox error','联系人不能为空');
			submit_result = false;
		}
		else 
		{
			if(!Utils.isCompanyLegal(contacts_check))
			{
				changeElementStyle('contacts','inp1 errorAlert','tipsBox error','只能输入中文及英文');
				submit_result = false;
			}
			else
			{
				changeElementStyle('contacts','inp1','tipsBox ok','');
				submit_result = true;
			}
		}
		return submit_result;
	}
	function contactsfocus()
	{
		changeElementStyle('contacts','inp1 selected','tipsBox default','请输入联系人姓名');
	}
	
	//成立年份
	function build_year_blur()
	{
		$("#build_year").val($.trim($("#build_year").val()));
		
		var build_year = $("#build_year").val();
		
		var submit_result;
		var len = build_year.length;
		
		
		if(build_year == "" || build_year.match(pspace))
		{
			changeElementStyle('build_year','inp1 errorAlert','tipsBox error',"请输入成立年份");
			submit_result = false;
		}
		else
		{
			if(!build_year.match(plimit))
			{
				changeElementStyle('build_year','inp1 errorAlert','tipsBox error','"只能输入数字');
				submit_result = false;
			}
			else
			{
				changeElementStyle('build_year','inp1','tipsBox ok','');
				submit_result = true;
			}
		}
		return submit_result;
	}
	
	function build_year_focus()
	{
		changeElementStyle('build_year','inp1 selected','tipsBox default','请输入成立年份');
	}
	
	//联系电话验证
	function contacts_phone_blur()
	{
		$("#contacts_phone").val($.trim($("#contacts_phone").val()));
		var contacts_phone = $("#contacts_phone").val();
		var submit_result;
		var len = contacts_phone.length;
		if(contacts_phone == "" || contacts_phone.match(pspace))
		{
			changeElementStyle('contacts_phone','inp1 errorAlert','tipsBox error',"联系电话不能为空");
			submit_result = false;
		}
		else 
		{
			if(!Utils.isCompanyFax(contacts_phone))
			{
				changeElementStyle('contacts_phone','inp1 errorAlert','tipsBox error','"只能输入半角数字和“-”“/”"');
				submit_result = false;
			}
			else
			{
				changeElementStyle('contacts_phone','inp1','tipsBox ok','');
				submit_result = true;
			}
		}
		return submit_result;
	}
	function contacts_phone_focus()
	{
		changeElementStyle('contacts_phone','inp1 selected','tipsBox default','请输入联系电话');
	}
	//联系人手机验证
	function contacts_mobile_blur()
	{
		$("#contacts_mobile").val($.trim($("#contacts_mobile").val()));
		var contacts_mobile = $("#contacts_mobile").val();
		var submit_result;
		if(contacts_mobile == "" || contacts_mobile.match(pspace))
		{
			changeElementStyle('contacts_mobile','inp1 errorAlert','tipsBox error',"联系手机不能为空");
			submit_result = true;
		}
		else{
			if(!Utils.isMobile(contacts_mobile))
			{
				changeElementStyle('contacts_mobile','inp1 errorAlert','tipsBox error',"您输入的号码有误，请重新输入");
				submit_result = false;
			}
			else
			{
				changeElementStyle('contacts_mobile','inp1','tipsBox ok','');
				submit_result = true;
			}
			
		}
		return submit_result;
	}
	function contacts_mobile_focus()
	{
		changeElementStyle('contacts_mobile','inp1 selected','tipsBox default','请输入您的手机号码');
	}
	//邮箱验证 是否被注册未做
	function emailblur()
	{
		var email = $("#email").val();
		var submit_result;
		if(email == "" || email.match(pspace))
		{
			changeElementStyle('email','inp1 errorAlert','tipsBox error',"电子邮箱不能为空");
			submit_result = false;
		}
		else //Utils.isEmail($("#"+id).val())
		{
			if(!Utils.isEmail(email))
			{
				changeElementStyle('email','inp1 errorAlert','tipsBox error',"您输入的邮箱格式有误，请检查一下");
				submit_result = false;
			}
			else
			{
				//$("#email").val($("#email").val().toLowerCase());
				
				//$.ajax({
				//	url:'/web.ajax/user/ckuser.jsp',
				//	type:'GET',
				//	async:false,
				//	data:"act=emailck&email="+email+"&rand"+Math.random(),
				//	success:function(data){
				//		if(data == 0){
				//			changeElementStyle('email','inp1 errorAlert','tipsBox error',"该邮箱已经被使用，请检查邮箱地址或换一个试试");
				//			submit_result = false;
				//		}
				//		else if(data == 1)
				//		{
				//			changeElementStyle('email','inp1','tipsBox ok','');
				//			submit_result = true;
				//		}
				//	}
				//});
				
				changeElementStyle('email','inp1','tipsBox ok','');
				submit_result = true;
			}
		}
		return submit_result;
	}
	function emailfocus()
	{
		changeElementStyle('email','inp1 selected','tipsBox default','请输入您的常用邮箱地址');
	}
	
	
	//QQ
	function contacts_qq_blur()
	{
		$("#contacts_qq").val($.trim($("#contacts_qq").val()));
		var contacts_qq = $("#contacts_qq").val();
		var submit_result;
		if(contacts_qq == "" || contacts_qq.match(pspace))
		{
			changeElementStyle('contacts_qq','inp1 errorAlert','tipsBox error',"请准确填写QQ号码");
			submit_result = false;
		}
		else
		{
			changeElementStyle('contacts_qq','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function contacts_qq_focus()
	{
		changeElementStyle('contacts_qq','inp1 selected','tipsBox default','请准确填写QQ号码');
	}


	//品牌名称验证(中文)是否被注册未做
	function brand_zh_blur()
	{
		$("#brand_zh").val($.trim($("#brand_zh").val()));
		var brand = $("#brand_zh").val();
		var submit_result;
		if(brand == "" || brand.match(pspace))
		{
			changeElementStyle('brand_zh','inp1 errorAlert','tipsBox error',"品牌的中文名称不能为空");
			submit_result = false;
		}
		//else if(!$("#brand_zh").val().match(brand_zh))
		//{
		//	changeElementStyle('brand_zh','inp1 errorAlert','tipsBox error',"只能输入中文汉字、半角数字以及大小写字母");
		//	submit_result = false;
		//}
		else
		{
			//$.ajax({
			//	url:'/web.ajax/user/ckbrand.jsp',
			//	type:'GET',
			//	async:false,
			//	data:"act=ckbrand&brandname="+brand+"&rand"+Math.random(),
			//	success:function(data){
			//		if(data == 0){
			//			changeElementStyle('brand_zh','inp1 errorAlert','tipsBox error',"品牌中文名称已经被使用,请<a class=\"rl\" href=\"tencent://message/?uin=87923608&amp;Site=前景加盟网&amp;Menu=yes\">认领</a>");
			//			submit_result = false;
			//		}
			//		else
			//		{
			//			changeElementStyle('brand_zh','inp1','tipsBox ok','');
			//			submit_result = true;
			//		}
			//	}
			//});

			changeElementStyle('brand_zh','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function brand_zh_focus()
	{
		changeElementStyle('brand_zh','inp1 selected','tipsBox default','请输入品牌中文名称，最多10个字');
	}
	//品牌名称验证(英文)是否被注册未做
	function brand_en_blur()
	{
		$("#brand_en").val($.trim($("#brand_en").val()));
		var brand = $("#brand_en").val();
		var len1 = parseInt(brand.length);
		if(!sub_str(brand)){var len2 = 0;}
		else {var len2 = parseInt(sub_str(brand));}
		var len = (len1 - len2) + len2 * 2;
		var submit_result;
		if(brand == "" || brand.match(pspace))
		{
			//changeElementStyle('brand_en','inp1','tipsBox ok','');
			submit_result = true;
		}
		else if(brand.match(/[\/\"\\]/))
		{
			changeElementStyle('brand_en','inp1 errorAlert','tipsBox error',"品牌英文名不能含有“/”，“\\”，“\"”。");
			submit_result = false;
		}
		//else if(!$("#brand_en").val().match(brand_en))
		else if(len > 50)
		{
//			changeElementStyle('brand_en','inp1 errorAlert','tipsBox error',"只能输入英文");
			//changeElementStyle('brand_en','inp1 errorAlert','tipsBox error',"品牌英文名称不能超过50个字");
			submit_result = false;
		}
		else
		{
		}
		return submit_result;
	}
	function brand_en_focus()
	{
		//changeElementStyle('brand_en','inp1 selected','tipsBox default','请输入品牌英文名称，最多50个字');
	}
	//公司网址
	function website_blur()
	{
		$("#website").val($.trim($("#website").val()));
		var website = $("#website").val();
		var submit_result;
		if(website == "" || website.match(pspace))
		{
			changeElementStyle('website','inp1 errorAlert','tipsBox error',"公司网址不能为空");
			submit_result = false;
		}
		else
		{
			changeElementStyle('website','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function website_focus()
	{
		changeElementStyle('website','inp1 selected','tipsBox default','请输入公司网址');
	}
	
	function brand_cradle_focus()
	{
		changeElementStyle('brand_cradle','inp1 selected','tipsBox default','请输入品牌发源地，最多20个字');
	}
	
	//品牌发源地
	function brand_cradle_blur()
	{
		$("#brand_cradle").val($.trim($("#brand_cradle").val()));
		var cradle = $("#brand_cradle").val();
		var submit_result;
		if(cradle == "" || cradle.match(pspace))
		{
			changeElementStyle('brand_cradle','inp1 errorAlert','tipsBox error',"品牌发源地不能为空");
			submit_result = false;
		}
		else if($("#brand_cradle").val().match(pbrand_cradle))
		{
			changeElementStyle('brand_cradle','inp1 errorAlert','tipsBox error',"品牌发源地不能为空");
			submit_result = false;
		}
		else
		{
			changeElementStyle('brand_cradle','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function brand_cradle_focus()
	{
		changeElementStyle('brand_cradle','inp1 selected','tipsBox default','请输入品牌发源地，最多20个字');
	}
	
	
	
	//品牌/项目投资额度
	function capital_limit_blur()
	{
		$("#capital_limit_1").val($.trim($("#capital_limit_1").val()));
		$("#capital_limit_2").val($.trim($("#capital_limit_2").val()));
		var limit_1 = $("#capital_limit_1").val();
		var limit_2 = $("#capital_limit_2").val();
		var submit_result;
		if((limit_1 == "" || limit_1.match(pspace)) || (limit_2 == "" || limit_2.match(pspace)))
		{
			changeElementStyle('capital_limit_1','inp2 errorAlert','tipsBox error',"");
			changeElementStyle('capital_limit_2','inp2 errorAlert','tipsBox error',"基本投资金额不能为空");
			submit_result = false;
		}
		else if(limit_1 == "" || limit_1.match(pspace))
		{
			changeElementStyle('capital_limit_1','inp2 errorAlert','tipsBox error',"");
			submit_result = false;
		}
		else if(limit_2 == "" || limit_2.match(pspace))
		{
			changeElementStyle('capital_limit_2','inp2 errorAlert','tipsBox error',"基本投资金额不能为空");
			submit_result = false;
		}
		else if(!limit_1.match(plimit))
		{
			changeElementStyle('capital_limit_1','inp2 errorAlert','tipsBox error',"您输入的金额格式有误，请重新输入");
			submit_result = false;
		}
		else if(!limit_2.match(plimit))
		{
			changeElementStyle('capital_limit_2','inp2 errorAlert','tipsBox error',"您输入的金额格式有误，请重新输入");
			submit_result = false;
		}
		else if(parseInt(limit_1)>parseInt(limit_2))
		{
			changeElementStyle('capital_limit_1','inp2 errorAlert','tipsBox error',"");
			changeElementStyle('capital_limit_2','inp2 errorAlert','tipsBox error',"您输入的最小金额必须小于最大金额，请重新输入");
			submit_result = false;
		}
		else
		{
			changeElementStyle('capital_limit_2','inp2','tipsBox ok','');
			changeElementStyle('capital_limit_1','inp2','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function capital_limit1_focus()
	{
		changeElementStyle('capital_limit_1','inp2 selected','tipsBox default','请输入基本投资金额');
	}
	function capital_limit2_focus()
	{
		changeElementStyle('capital_limit_2','inp2 selected','tipsBox default','请输入基本投资金额');
	}
	//招商模式 招商模式可以选择的选项有：加盟连锁、分销代理 可以同时勾选两项，但不能为空。 若用户未勾选，则在提交时，在右侧用红色文字提示用户：“请选择招商模式”。
	function business_mode_change()
	{
		var submit_result;
		if(!($("#business_mode_league").is(":checked")) && !($("#business_mode_agent").is(":checked")))
		{
			submit_result = false;
			changeElementStyle('business_mode_label','','tipsBox error',"请选择招商模式");
		}
		else
		{
			submit_result = true;
			changeElementStyle('business_mode_label','','tipsBox ok',"");
		}
		return submit_result;
	}
	//直营店总数
	function zhixiaodianblur()
	{
		$("#zhixiaodian").val($.trim($("#zhixiaodian").val()));
		var zhixiao = $("#zhixiaodian").val();
		var submit_result;
		if(zhixiao == "" || zhixiao.match(pspace))
		{
			changeElementStyle('zhixiaodian','inp1 errorAlert','tipsBox error',"直营店总数不能为空");
			submit_result = false;
		}
		else if(!$("#zhixiaodian").val().match(plimit))
		{
			changeElementStyle('zhixiaodian','inp1 errorAlert','tipsBox error',"只能输入半角数字");
			submit_result = false;
		}
		else
		{
			changeElementStyle('zhixiaodian','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function zhixiaodianfocus()
	{
		changeElementStyle('zhixiaodian','inp1 selected','tipsBox default','请输入直营店总数');
	}
	//加盟店总数
	function jiamengdianblur()
	{
		$("#jiamengdian").val($.trim($("#jiamengdian").val()));
		var jiameng = $("#jiamengdian").val();
		var submit_result;
		if(jiameng == "" || jiameng.match(pspace))
		{
			changeElementStyle('jiamengdian','inp1 errorAlert','tipsBox error',"加盟/代理店总数不能为空");
			submit_result = false;
		}
		else if(!$("#jiamengdian").val().match(plimit))
		{
			changeElementStyle('jiamengdian','inp1 errorAlert','tipsBox error',"只能输入半角数字");
			submit_result = false;
		}
		else
		{
			changeElementStyle('jiamengdian','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function jiamengdianfocus()
	{
		changeElementStyle('jiamengdian','inp1 selected','tipsBox default',"经营产品/提供服务不能为空");
	}
	//经营产品/提供服务  
	function productsblur()
	{
		$("#products").val($.trim($("#products").val()));
		var products = $("#products").val();
		var submit_result;
		if(products == "")
		{
			changeElementStyle('products','inp1 errorAlert','tipsBox error',"经营产品/提供服务不能为空");
			submit_result = false;
		}
		else if($("#products").val().match(pbrand_cradle))
		{
			changeElementStyle('products','inp1 errorAlert','tipsBox error',"经营产品/提供服务不能为空");
			submit_result = false;
		}
		else
		{
			changeElementStyle('products','inp1','tipsBox ok','');
			submit_result = true;
		}
		return submit_result;
	}
	function productsfocus()
	{
		changeElementStyle('products','inp1 selected','tipsBox default','请输入经营产品或提供的服务，严禁出现与产品项目无关的内容，以免造成法律纠纷！');
	}

	function checkElement(id)
	{
		var elements = true;
		switch(id)
		{
			case 'register_capital':
				elements = Utils.isCompanyMoney($("#"+id).val());
				break;			
			case 'combiner':
				elements = Utils.isCompanyLegal($("#"+id).val());
				break;
			case 'contacts_phone':
				elements = Utils.isCompanyFax($("#"+id).val());
				break;
			case 'contacts_mobile':
				elements = Utils.isMobile($("#"+id).val());
				break;
			case 'email':
				elements = Utils.isEmail($("#"+id).val());
				break; 
				
			return elements;
		} 
	}

	//验证码
	function checkcodeblur()
	{
		$("#checkcode").val($.trim($("#checkcode").val()));
		var code = $("#checkcode").val();
		var submit_result;
		if(code == "" || code.match(pspace))
		{
			changeElementStyle('checkcode','inp2 errorAlert','tipsBox error',"验证码不能为空");
			submit_result = false;
		}
		else
		{
			$.ajax({
				url:'/web.ajax/user/ckcertcode.jsp',
				type:'GET',
				async:false,
				data:"act=ckcode&certCode="+code+"&rand"+Math.random(),
				success:function(data){
					if(data == 0){
						changeElementStyle('checkcode','inp2 errorAlert','tipsBox error',"验证码错误");
						//return false;
						submit_result = false;
					}
					else
					{
						changeElementStyle('checkcode','inp2','tipsBox ok','');
						submit_result = true;
					}
				}
			});
		}
		return submit_result;
	}
	
	function checkcodefocus()
	{
		changeElementStyle('checkcode','inp2 selected','tipsBox default','请输入验证码');
	}
	//验证码更换
	function changecode()
	{
		$("#code1").attr("src","./identifying_code.php?id="+parseInt(Math.random()*99+1));
	} 
	function submitclick()
	{
		//alert('抱歉，系统升级中，暂不能注册！');return false;

        // if($("#companyCountry").val() == ''){ alert("请上传营业执照！"); return false;}

		//用户名
		if(!uNameblur()){return false;}
		//密码
		if(!uPwdblur()){return false;}
		//二次密码
		if(!checkPwdblur()){return false;}
		//公司名称中文
		if(!company_zh_blur()){return false;}
		
		//企业类型
		// if($("#enterprise_type").val() == '0'){changeElementStyle('enterprise_type','','error','请选择企业类型');return false;}
		
		//注册资金
		// if(!register_capital_blur()){return false;}
		//注册日期
		// if($("#buildtime").val() == ''){changeElementStyle('calendarSpan','','tipsBox error',"");return false;}
		
		//省市区
		// if($("#province").val() == '' || $("#province").val() == '省/市')
		// {
		// 	$("#province").change();
		// 	return false;
		// }
		//详细地址
		// if(!addressblur()){return false;}
		
		//网站
		// if(!website_blur()){return false;}
		
		//联系人
		if(!contactsblur()){return false;}
		//联系人电话
		if(!contacts_phone_blur()){return false;}
		//联系人手机
		// if(!contacts_mobile_blur()){return false;}
		
		//邮箱
		// if(!emailblur()){return false;}
		
		//联系QQ
		// if(!contacts_qq_blur()){return false;}
		
		//品牌中文
		if(!brand_zh_blur()){return false;}
		
		//所属行业一级
		if($("#industry_id").val() == '0'){
			changeElementStyle('industry_id','','error','请选择所属行业');
			return false;
		}
		
		//品牌发源地
		// if(!brand_cradle_blur()){return false;}
		
		// if($("#brand_suminvest").val() == ''){
		// 	changeElementStyle('brand_suminvest','','error','请选择投资金额');
		// 	return false;
		// }
		
		
		
		// var fazhan_bool = false;
		// $($('.fazhan_ms:checked')).each(function(){
		// 	fazhan_bool = true;
		// });
		// if(!fazhan_bool)
		// {
		// 	changeElementStyle('fazhan_mode_label','','tipsBox error',"请选择发展模式模式");
		// 	return false;
		// }
	
		// var jinying_bool = false;
		// $($('.jinying_ms:checked')).each(function(){
		// 	jinying_bool = true;
		// });
		// if(!jinying_bool)
		// {
		// 	changeElementStyle('jinying_mode_label','','tipsBox error',"请选择发展模式模式");
		// 	return false;
		// }
		
		// //直销店
		// if(!zhixiaodianblur()){return false;}
		// //加盟店
		// if(!jiamengdianblur()){return false;}
		// //经营产品/提供服务
		// if(!productsblur()){return false;}
		//验证码
		if(!checkcodeblur()){return false;}
		
	}

