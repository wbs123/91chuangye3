/**
 * @description util.js
 * @创建人： 陈德昭
 * @修改人： 刘新来
 * @date 2011-03-08
 * @chanageDate 2011-08-21
 */
var Browser = new Object();

Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument != 'undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox") != - 1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != - 1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera") != - 1);

var Utils = new Object();

Utils.htmlEncode = function(text)
{
  return text.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

Utils.isEmpty = function( val )
{
  switch (typeof(val))
  {
    case 'string':
      return Utils.trim(val).length == 0 ? true : false;
      break;
    case 'number':
      return val == 0;
      break;
    case 'object':
      return val == null;
      break;
    case 'array':
      return val.length == 0;
      break;
    default:
      return true;
  }
}

Utils.isNumber = function(val)
{
  var reg = /^[\d|\.|,]+$/;
  return reg.test(val);
}

Utils.isInt = function(val)
{
  if (val == "")
  {
    return false;
  }
  var reg = /\D+/;
  return !reg.test(val);
}

Utils.x = function(e)
{ //当前鼠标X坐标
    return Browser.isIE?event.x + document.documentElement.scrollLeft - 2:e.pageX;
}

Utils.y = function(e)
{ //当前鼠标Y坐标
    return Browser.isIE?event.y + document.documentElement.scrollTop - 2:e.pageY;
}

Utils.request = function(url, item)
{
    var sValue=url.match(new RegExp("[\?\&]"+item+"=([^\&]*)(\&?)","i"));
    return sValue?sValue[1]:sValue;
}

Utils.$ = function(name)
{
    return document.getElementById(name);
}

function rowindex(tr)
{
  if (Browser.isIE)
  {
    return tr.rowIndex;
  }
  else
  {
    table = tr.parentNode.parentNode;
    for (i = 0; i < table.rows.length; i ++ )
    {
      if (table.rows[i] == tr)
      {
        return i;
      }
    }
  }
}

function getPosition(o)
{
    var t = o.offsetTop;
    var l = o.offsetLeft;
    while(o = o.offsetParent)
    {
        t += o.offsetTop;
        l += o.offsetLeft;
    }
    var pos = {top:t,left:l};
    return pos;
}

function cleanWhitespace(element)
{
  var element = element;
  for (var i = 0; i < element.childNodes.length; i++) {
   var node = element.childNodes[i];
   if (node.nodeType == 3 && !/\S/.test(node.nodeValue))
     element.removeChild(node);
   }
}

/**
 * 去除字符串空格
 */
Utils.trim = function(text) {
    return text.replace(/(^\s*)|(\s*$)/g, "");
}

/**
 * 去除字符串左空格
 */
Utils.ltrim = function(text) {
    return text.replace(/(^\s*)/g, "");
}

/**
 * 去除字符串右空格
 */
Utils.rtrim = function(text) {
    return text.replace(/(\s*$)/g, "");
}

/**
 * 返回字符串的实际长度, 一个汉字算2个长度
 */
Utils.len = function(text) {
    return text.replace(/[^\x00-\xff]/g, "**").length;
}

/**
* 是否有效用户名
*/
Utils.isUsername = function(str) {
	var reg = /^[\w\.\-\u4e00-\u9fa5]{2,16}$/;
	return reg.test(str);
}

/**
 * 是否是有效密码
 */
Utils.isPassword = function(str) {
	var pattern = /^[\w!@#$%^&*().]{6,16}$/;
    return pattern.test(str);
}

/**
* 是否有效验证码
*/
/*
Utils.isCheckcode = function(str) {
	var reg = /^[\u4e00-\u9fa5]{2}$/;
	return reg.test(str);
}
*/
Utils.isCheckcode = function(str) {
	var reg = /^[a-zA-Z0-9]{4}$/;
	return reg.test(str);
}

/**
 * 是否是有效邮箱
 */
Utils.isEmail = function(email) {
//    var email_format = /^(\d|[a-zA-Z])+(-|\.|\w)*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    // var email_format = /^\w(\w|_|-|\.)*@\w(\w|_|-|\.)*\.\w{2,3}$/;//(sunlian)
    var email_format = /^\w(\w|_|-|\.)*@\w(\w|_|-|\.)*\.(com|edu|gov|int|mil|net|org|biz|info|pro|name|museum|coop|aero|xxx|idv|cn|hk|tw)$/
    return email_format.test(email);
}



/**
 * 是否是中文字符
 */
Utils.isChinese = function(str) {
    var regexp = /^[\u4e00-\u9fa5]*$/g;
    return regexp.test(str);
}

/**
 * 是否是数字
 */
Utils.isDigit = function(str) {
    var regexp = /^[0-9]+$/;
    return regexp.test(str);
}

/**
 * 是否是数字（可为空）
 */
Utils.isDigitSpace = function(str) {
    var regexp = /^[0-9]*$/;
    return regexp.test(str);
}

/**
 * 是否是整数
 */
Utils.isInteger = function(str) {
    var regexp = /^(-|\+)?\d+$/;
    return regexp.test(str);
}

/**
 * 是否是小数
 */
Utils.isFloat = function(str) {
    var regexp = /^(-|\+)?\d+\.{0,1}\d+$/;
    return regexp.test(str);
}

/**
 * 是否是有效邮编
 */
Utils.isPostalCode = function(str) {
    var regexp = /(^[0-9]{6}$)/;
    return regexp.test(str);
}

/**
 * 验证qq或msn
 */
Utils.isQqMsn = function(str) {
//    var regexp = /^[a-zA-Z0-9]{1}[\w@.]{3,40}$/;
	var regexp = /^\d{4,14}$/;
    return regexp.test(str);
}

/**
 * 验证企业中文名称
 */
Utils.isCompanyName = function(str) {
   var regexp = /^[\u4e00-\u9fa5+\·?]*[\u4e00-\u9fa5]{1,20}$/;
   return regexp.test(str);
}

/**
 * 验证品牌名称
 */
Utils.isBrandName = function(str) {
   var regexp = /^[a-zA-Z0-9\u4e00-\u9fa5]{0,10}$/;
   return regexp.test(str);
}

/**
 * 验证企业英文名称
 */
Utils.isEnglishName = function(str) {
   var regexp = /^([A-Za-z]+\s?|\.?)*[A-Za-z]$/;
   return regexp.test(str);
}

/**
 * 验证企业类型
 */
Utils.isCompanyClass = function(str) {
   var regexp = /^[1-9]$/;
   return regexp.test(str);
}

/**
 * 验证企业法人
 */
Utils.isCompanyLegal = function(str)
{
  var regexp = /^([A-Za-z\u4e00-\u9fa5]+\s?)*[A-Za-z\u4e00-\u9fa5]{0,20}$/;
  return regexp.test(str);
}

/**
 * 验证企业注册资金
 */
Utils.isCompanyMoney = function(str)
{
  var regexp = /^[0-9\u4e00-\u9fa5]{1}[\d\u4e00-\u9fa5]{0,19}$/;
  return regexp.test(str);
}

/**
 * 验证企业营业执照
 */
Utils.isCompanyCommercial = function(str)
{
  var regexp = /^\d{0,18}$/;
  return regexp.test(str);
}

/**
 * 验证企业税务登记
 */
Utils.isCompanyTax = function(str)
{
  var regexp = /^\d{0,20}$/;
  return regexp.test(str);
}

/**
 * 验证企业组织机构
 */
Utils.isCompanyOrganizational = function(str)
{
  var regexp = /^\d{0,9}$/;
  return regexp.test(str);
}

/**
 * 验证企业传真
 */
Utils.isCompanyFax = function(str)
{
//	var regexp = /^([0-9]{3,4}\-|\/{0,1})?\d{7,8}(\-|\/{0,1}[0-9]{1,8})*$/;
  var regexp = /^([0-9]+[\/\-]?)*[0-9]+$/; //(wangshuo)
  return regexp.test(str);
}

/**
 * 验证企业qq
 */
Utils.isQQ = function(str) {
  var regexp = /^\d{0,15}$/;//(sunlian)
    return regexp.test(str);
}

/**
 * 验证项目title
 */
 Utils.isTitle = function(str) {
  var regexp = /^[A-Za-z\u4e00-\u9fa5\d\,，]{1,100}$/;//(sunlian)
    return regexp.test(str);
}


/**
 * 是否是有效座机号
 */
Utils.isPhone = function(str) {
	var regexp = /^(\d{3,4}-{0,1}){0,1}\d{7,8}$/;//(sunlian)
    return regexp.test(str);
}

/**
 * 是否是有效手机号
 */
Utils.isMobile = function(str) {
    var regexp = /^(1)[0-9]{10}$/;//(sunlian)
    return regexp.test(str);
}

/**
 * 是否是多个手机号
 */
Utils.isMobileNumbers = function(str) {
    var regexp = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9])[0-9]{8}(\,(13[0-9]|14[0-9]|15[0-9]|18[0-9])[0-9]{8})*$/;//(sunlian)
    return regexp.test(str);
}

/**
 * 验证留言内容
 */
Utils.isContent = function(str){
	var regexp = /^([a-zA-Z0-9]|[\u4e00-\u9fa5]|【){1}([a-zA-Z0-9]|\s|\n|-|,|\.|。|\！|!|，|\?|？|【|】|—|[\u4e00-\u9fa5]){2,199}$/;
	return regexp.test(str);
}

/**
 * 验证留言问题内容
 */
Utils.isQuestion = function(str){
	if(str==""){
		return false;
	} else {
		var regexp = /^[0-9a-zA-Z\n\u4e00-\u9fa5]{2,10}$/;
		return regexp.test(str);
	}
}

/**
 * 验证城市
 */
Utils.isCity = function(str){
	var regexp = /^[\u4e00-\u9fa5]{1,15}$/;
	return regexp.test(str);
}

/**
 * 验证地址
 */
Utils.isAdds = function(str){
  var regexp = /^([a-zA-z0-9]|[\u4e00-\u9fa5]|-){1,30}$/;
	return regexp.test(str);
}

/**
 * 验证姓名
 */
Utils.isName = function(str){
  var regexp = /^[0-9a-zA-Z\u4e00-\u9fa5]{0,19}$/;
	return regexp.test(str);
}

/**
 * Cookie操作
 */
Utils.cookie = new Object();
Utils.cookie.domain = 'jmw.com.cn';
Utils.cookie.path = '/';
Utils.cookie.hours = 24;//默认1天

/**
 * 设置cookie
 */
Utils.cookie.setCookie = function(name,value,hours,domain,path) {
    hours = hours ? hours : Utils.cookie.hours;
    domain = domain ? domain : Utils.cookie.domain;
    path = path ? path : Utils.cookie.path;
    var exp  = new Date();
    exp.setTime(exp.getTime() + hours*3600000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";domain=" + domain + ";path=" + path;
}

/**
 * 取得cookie
 */
Utils.cookie.getCookie = function(name) {
    var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}

/**
 * 删除cookie
 */
Utils.cookie.delCookie = function(name,domain,path) {
    domain = domain ? domain : Utils.cookie.domain;
    path = path ? path : Utils.cookie.path;
    var exp = new Date();
    exp.setTime(exp.getTime() - 3600000);
    document.cookie = name + "=0" + ";expires=" + exp.toGMTString() + ";domain=" + domain + ";path=" + path;
}

document.getCookie = function(sName)
{

  //cookies are separated by semicolons
  var aCookie = document.cookie.split("; ");
  for (var i=0; i < aCookie.length; i++)
  {
    // a name/value pair (a crumb) is separated by an equal sign
    var aCrumb = aCookie[i].split("=");
    if (sName == aCrumb[0])
    {
      //alert(decodeURIComponent(aCrumb[1]));
      return decodeURIComponent(aCrumb[1]);
    }
  }

  // a cookie with the requested name does not exist
  return null;
}

document.setCookie = function(sName, sValue, sExpires)
{
  var sCookie = sName + "=" + encodeURIComponent(sValue);
  if (sExpires != null)
  {
    sCookie += "; expires=" + sExpires;
  }

  document.cookie = sCookie;
}

document.removeCookie = function(sName,sValue)
{
  document.cookie = sName + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
}