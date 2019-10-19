	//费用名称
	function feeNameFocus(){
		changeElementStyle('fee_name','inp4 selected','default','支持中英文、数字及其组合，最多10个字');
	}
	
	function feeNameBlur(){
		if($("#fee_name").val() == ''){
			changeElementStyle('fee_name','inp4 errorAlert','error','费用名称不能为空');
			return false;
		}else{
			if(!$("#fee_name").val().match("^[\\u4E00-\\u9FA5\\uF900-\\uFA2D\\w]+$")){
				changeElementStyle('fee_name','inp4 errorAlert','error','只能输入中文汉字、英文字符和半角数字');
				return false;
			}else{
				changeElementStyle('fee_name','inp4','ok','');
				return true;
			}
		}
	}
	
	//费用下限
	function lowerFocus(){
		changeElementStyle('fee_lower','inp3 selected','default','请输入费用的最低额度');
	}
	
	function lowerBlur(){
		if($("#fee_lower").val() == ''){
			changeElementStyle('fee_lower','inp3 errorAlert','error','费用下限不能为空');
			return false;
		}else{
			if(!$("#fee_lower").val().match(/^[0-9]{1}\d{0,7}$/)){
				changeElementStyle('fee_lower','inp3 errorAlert','error','只能输入半角数字');
				return false;
			}else{
				if($("#fee_upper").val()!='' && parseInt($("#fee_lower").val()) > parseInt($("#fee_upper").val())){
					changeElementStyle('fee_lower','inp3 errorAlert','error','您输入费用的下限额度大于上限额度');
					return false;
				}else if($("#fee_upper").val()=='' && $("#fee_lower").val()!=''){
					changeElementStyle('fee_lower','inp3','ok','');
				}else{
					changeElementStyle('fee_lower','inp3','ok','');
					changeElementStyle('fee_upper','inp3','ok','');
					return true;
				}
				changeElementStyle('fee_lower','inp3','ok','');
				return true;
			}
		}
	}
	
	//费用上限
	function upperFocus(){
		changeElementStyle('fee_upper','inp3 selected','default','请输入费用的上限额度');
	}
	
	function upperBlur(){
			if($("#fee_upper").val()!='' && !$("#fee_upper").val().match(/^[0-9]{1}\d{0,7}$/)){
				changeElementStyle('fee_upper','inp3 errorAlert','error','只能输入半角数字');
				return false;
			}else{
				if($("#fee_lower").val()!='' && parseInt($("#fee_upper").val()) < parseInt($("#fee_lower").val())){
					changeElementStyle('fee_upper','inp3 errorAlert','error','您输入费用的上限额度小于下限额度');
					return false;
				}else if($("#fee_lower").val()=='' && $("#fee_upper").val()!=''){
					changeElementStyle('fee_lower','inp3 errorAlert','error','费用下限不能为空');
					changeElementStyle('fee_upper','inp3','ok','');
					return false;
				}else{
					changeElementStyle('fee_upper','inp3','ok','');
					return true;
				}
			}
	}
	
	//费用说明
	function explainFocus(){
		changeElementStyle('fee_explain','inp1 selected','default','请输入费用说明，最多20个字');
	}
	
	function explainBlur(){
		if($("#fee_explain").val() == ''){
			changeElementStyle('fee_explain','inp1 errorAlert','error','费用说明不能为空');
			return false;
		}else{
			changeElementStyle('fee_explain','inp1','ok','');
			return true;
		}
	}

	
	
	
	
	
	
	
	
	//条件名称
	function asupportNameFocus(){
		changeElementStyle('conditionName','inp4 selected','default','支持中英文、数字及其组合，最多10个字');
	}
	
	function asupportNameBlur(){
		if($("#conditionName").val() == ''){
			changeElementStyle('conditionName','inp4 errorAlert','error','条件名称不能为空');
			return false;
		}else{
			if(!$("#conditionName").val().match("^[\\u4E00-\\u9FA5\\uF900-\\uFA2D\\w]+$")){
				changeElementStyle('conditionName','inp4 errorAlert','error','只能输入中文汉字、英文字符和半角数字');
				return false;
			}else{
				changeElementStyle('conditionName','inp4','ok','');
				return true;
			}
		}
	}
	
	
	
	//条件说明
	function conditionNameFocus(){
		changeElementStyle('conditionExplain','inp4 selected','default','支持中英文、数字及其组合，最多20个字');
	}
	
	function conditionNameBlur(){
		if($("#conditionExplain").val() == ''){
			changeElementStyle('conditionExplain','inp4 errorAlert','error','条件说明不能为空');
			return false;
		}else{
			if(!$("#conditionExplain").val().match("^[\\u4E00-\\u9FA5\\uF900-\\uFA2D\\w]+$")){
				changeElementStyle('conditionExplain','inp4 errorAlert','error','只能输入中文汉字、英文字符和半角数字');
				return false;
			}else{
				changeElementStyle('conditionExplain','inp4','ok','');
				return true;
			}
		}
	}
	
	
	
	




function getList(project_id)
{
	var timestamp =Date.parse(new Date()); 
	$.ajax({
		type:'post',
		url:host+'companyInfo/joinFeeList.php',
		data:'project_id='+project_id+'&timestamp='+timestamp,
		dataType:'json',
		success:function(data){
			$("#feeListTbody").html('');
			$(":text").parent().find('span').attr('class','tipsBox');
			$.each(eval(data),function(k,v){
				$('<tr><td>'+v.fee_name+'</td><td>'+v.fee_lower+
					'</td><td>'+v.fee_upper+'</td><td>'+v.fee_explain+
					'</td><td><a href="javascript:viod(0)" '+
					'onclick="delJoinFee('+v.id+
					');return false;">删除</a></td></tr>').appendTo("#feeListTbody");
			});
		}
	});
}



// 删除
function delJoinFee(id){
	
	
	if(confirm('您确定要删除这条费用吗？')){
		$.ajax({
			type:'POST',
			url:'deleteJoinFee.php',
			data:'id='+id,
			success:function(html){
				
				if(html == 1){
					alert('操作成功！');
					//window.parent.parent.location.href="http://comp.jmw.com.cn/companyInfo/projectjoining.php";
					window.location.href='http://comp.jmw.com.cn/companyInfo/companyJoinFee.php';
				}else{
					alert('操作失败！');
					//window.parent.parent.location.href="http://comp.jmw.com.cn/companyInfo/projectPage.php";
					// window.location.href='http://comp.jmw.com.cn/companyInfo/companyJoinFee.php';
				}
				
				// getList(id);
				
			}
		});
	}else{
		return false;
	}
}

//置顶
function topExpense(id,update_time)
{
	$.ajax({
		url:'topJoinFee.php',
		data:'id='+id+'&update_time='+update_time,
		type:'POST',
		success:function(html)
		{
			if(html == 1)
			{
				alert('置顶成功！');
				//if(urlTag){
					//window.parent.parent.location.href="http://comp.jmw.com.cn/companyInfo/projectjoining.php";
					//window.location.href='http://comp.jmw.com.cn/companyInfo/expense.php?tag=1';
				//}else{
					//window.parent.parent.location.href="http://comp.jmw.com.cn/companyInfo/projectPage.php";
					//window.location.href='http://comp.jmw.com.cn/companyInfo/expense.php';
				//}
				window.location.href='http://comp.jmw.com.cn/companyInfo/companyJoinFee.php';
				
			}else{
				alert("置顶失败！");
			}
		}
	});
}


// 添加 加盟 费用
$(function(){
	$("#addJoiningFee").click(function(){
		
		if(!feeNameBlur()){
			return false;
		}
		if(!lowerBlur()){
			return false;
		}
		if(!upperBlur()){
			return false;
		}
		if(!explainBlur()){
			return false;
		}
		
		var fee_name    = $("#fee_name").val();
		var fee_lower   = $("#fee_lower").val();
		var fee_upper   = $("#fee_upper").val();
		var fee_explain = $("#fee_explain").val();
		var project_id  = $("#project_id").val();
		
		
		$(this).addClass('submiting');
		$.ajax({
			type:'POST',
			url:'http://comp.jmw.com.cn/companyInfo/AddJoinFee.php',
			data:'fee_name='+fee_name+'&fee_lower='+fee_lower+'&fee_upper='+fee_upper+'&fee_explain='+fee_explain,
			success:function(html){				
				if(html ==1 ){
					
					alert('操作成功！');
					window.location.href='http://comp.jmw.com.cn/companyInfo/companyJoinFee.php';
										
					$(":text").val('');
					$("#addJoiningFee").removeClass('submiting');
					
				}else if( html = "error"){
					alert('操作失败！');
					window.location.href='http://comp.jmw.com.cn/companyInfo/companyJoinFee.php';
					
				}else{
					$("#addJoiningFee").removeClass('submiting');
					alert("操作失败！");
				}
				
			}
		});
			
	});
});



// 添加  ||  修改 加盟 费用介绍
function updateContent()
{
	var content = $('#content').val();
	alert(content+"添加  ||  修改 加盟 费用介绍-");
	
}

	


// 添加 优势及支持
function addCondition()
{
	var name = $("#conditionName").val();
	var explain = $("#conditionExplain").val();
	
	if(asupportNameBlur() && conditionNameBlur()){
		$.ajax({
			type:'POST',
			url:'http://comp.jmw.com.cn/companyInfo/advantageSupport/addCondition.php',
			data:'name='+name+'&explain='+explain,
			dataType:'json',
			success:function(data){
				if(data == 1)
				{
					alert('添加成功 !');	
					window.location.href='http://comp.jmw.com.cn/companyInfo/companyAdvantageSupport.php';		
			
				}else if(data == "error"){
					alert('添加失败 !');					
					window.location.href='http://comp.jmw.com.cn/companyInfo/companyAdvantageSupport.php';

				}else{
					alert('添加失败 !');
				}
			}
		});	
	}
}


// 删除 优势及支持
function delCondition(id)
{
	
	if(confirm("您确定要删除这条优势及支持吗？")){
		
		$.ajax({
			type:'POST',
			url:'http://comp.jmw.com.cn/companyInfo/advantageSupport/delCondition.php',
			data:'id='+id,
			dataType:'json',
			success:function(data){
				if(data == 1)
				{
					alert('删除成功 !');
					window.location.href='http://comp.jmw.com.cn/companyInfo/companyAdvantageSupport.php';				
				}else{
					alert('添加失败 !');
				}
		}
	});	
	}else{
		return false;
	}
	
	
}




// 置顶 优势及支持
function topCondition(id)
{
	$.ajax({
		type:'POST',
		url:'http://comp.jmw.com.cn/companyInfo/advantageSupport/topCondition.php',
		data:'id='+id,
		dataType:'json',
		success:function(data){
			if(data == 1)
			{
				alert('置顶成功 !');				
				window.location.href='http://comp.jmw.com.cn/companyInfo/companyAdvantageSupport.php';	
				
			}else{
				alert('添加失败 !');
			}
		}
	});	
	
}










