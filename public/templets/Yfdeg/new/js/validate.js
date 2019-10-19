
      $(function() {
        initProvince("msgProvince1");
        // $("#ProjectID1").val($("#form_contact_us_ProjectID").val());
        // $("#PageURL1").val(window.location.href);
        // $("#PageURLTitle1").val(document.title);
      });

      function showSmalla(value) {
        onProvinceChange(value, 'msgCity1', 'msgXian1', resetAddressa);
      }

      function showXiana(value) {
        onCityChange(value, 'msgXian1', resetAddressa);
      }

      function resetAddressa() {
        var p = document.getElementById("msgProvince1");
        var c = document.getElementById("msgCity1");
        var d = document.getElementById("msgXian1");
        var text = p.options[p.selectedIndex].text;
        text += $(c).val() == 0 ? '' : c.options[c.selectedIndex].text;
        text += $(d).val() == 0 ? '' : d.options[d.selectedIndex].text;
        $("#Address1").val(text);
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

      function quickmsgsubmit1() {
        //判断是否选中
        if($('#msgform1 #checkbox1').prop('checked') === false) {
          alert("请选择我同意将我的联系方式推荐给商家！");
          return false;
        }
        //是否含有中文（也包含日文和韩文）
        var reName = /^[a-zA-Z\u4e00-\u9fa5\uF900-\uFA2D ]{1,20}$/;
        if(reName.test($("#Name1").val()) === false) {
          alert("请输入正确的姓名！");
          $("#Name1").focus().select();
          return false;
        }
        //支持手机号码，3-4位区号，7-8位直播号码，1－4位分机号
        var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
        if(reTel.test($("#Tel1").val()) === false) {
          alert("请输入正确的电话号码！");
          $("#Tel1").focus().select();
          return false;
        }
        $("#imgBtnUp1").val("正在提交...");
        var data = $("#msgform1").serializeObject();
        var im = document.getElementById("InvestMoney1");
        data.InvestMoney = im.options[im.selectedIndex].text;
        data.Gender = data.radiobutton == 0 ? '男' : '女';
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
          url: prefix + "jm/msgsubmit?" + queryString + "&MessageSource=XQ005-001",
          dataType: "jsonp", //数据类型为jsonp
          jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
          jsonpCallback: "jsonpCallback",
          success: function(data) {
            alert(data.msg);
            $("#imgBtnUp1").val("提交留言");
          },
          error: function() {
            alert('fail2');
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
          $("#msgform1").find("input,button,textarea,select").attr("disabled", true);
        }
        var Catalog = $("#form400").val();
        if(Catalog == "Y") {
          $(".nav-400, .form-400").removeClass("dn")
        };

        //提交电话号码
        $("#Free_phone_btn_1").click(function() {
          var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
          if(reTel.test($("#Free_phone_text_1").val()) === false) {
            alert("请输入正确的电话号码！");
            $("#Free_phone_text_1").focus().select();
            return false;
          }
          var mobilephone = $("#Free_phone_text_1").val();
          $.ajax({
            type: "get",
            async: false,
            url: prefix + "jm/msgsubmit?ProjectID=" + ProjectID + "&URL=" + window.location
              .href + "&URLTitle=" + document.title + "&Tel=" + mobilephone +
              "&MessageSource=XQ005-002",
            dataType: "jsonp", //数据类型为jsonp
            jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
            jsonpCallback: "jsonpCallback",
            success: function(data) {
              alert("呼叫成功,请等候来电");
            },
            error: function() {
              alert('fail3');
            }
          });
        });
        //提交电话号码2 （详情页左立即联系我）
        $("#Free_phone_btn1").click(function() {
          var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
          if(reTel.test($("#Free_phone_text1").val()) === false) {
            alert("请输入正确的电话号码！");
            $("#Free_phone_text1").focus().select();
            return false;
          }
          var mobilephone = $("#Free_phone_text1").val();
          $.ajax({
            type: "get",
            async: false,
            url: prefix + "jm/msgsubmit?ProjectID=" + ProjectID + "&URL=" + window.location
              .href + "&URLTitle=" + document.title + "&Tel=" + mobilephone +
              "&MessageSource=XQ003-003",
            dataType: "jsonp", //数据类型为jsonp
            jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
            jsonpCallback: "jsonpCallback",
            success: function(data) {
              alert("呼叫成功,请等候来电");
            },
            error: function() {
              alert('fail4');
            }
          });

        });

        //快捷留言
        $(".quickMessage1 li").click(function() {          
          var lit = $(this).find("p").text();
            $(".Message1").val(lit);          
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
          success: function() {},
          error: function() {}
        });
      }


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

      function quickmsgsubmit3() {
        //判断是否选中
        if($('#msgform #checkbox').prop('checked') === false) {
          alert("请选择我同意将我的联系方式推荐给商家！");
          return false;
        }
        //是否含有中文（也包含日文和韩文）
        var reName = /^[a-zA-Z\u4e00-\u9fa5\uF900-\uFA2D ]{1,20}$/;
        if(reName.test($("#Name").val()) === false) {
          alert("请输入正确的姓名！");
          $("#Name").focus().select();
          return false;
        }
        //支持手机号码，3-4位区号，7-8位直播号码，1－4位分机号
        var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
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
          url: prefix + "jm/msgsubmit?" + queryString + "&MessageSource=XQ005-001",
          dataType: "jsonp", //数据类型为jsonp
          jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
          jsonpCallback: "jsonpCallback",
          success: function(data) {
            alert(data.msg);
            $("#imgBtnUp").val("提交留言");
          },
          error: function() {
            alert('提交失败');
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
        var Catalog = $("#form400").val();
        if(Catalog == "Y") {
          $(".nav-400, .form-400").removeClass("dn")
        };
        //提交电话号码
        $("#Free_phone_btnleft").click(function() {
          var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
          if(reTel.test($("#Free_phone_text").val()) === false) {
            alert("请输入正确的电话号码！");
            $("#Free_phone_text").focus().select();
            return false;
          }
          var mobilephone = $("#Free_phone_text").val();
          $.ajax({
            type: "get",
            async: false,
            url: prefix+"jm/msgsubmit?ProjectID=" + ProjectID + "&URL=" + window.location
              .href + "&URLTitle=" + document.title + "&Tel=" + mobilephone +
              "&MessageSource=XQ005-002",
            dataType: "jsonp", //数据类型为jsonp
            jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
            jsonpCallback: "jsonpCallback",
            success: function(data) {
              alert("呼叫成功,请等候来电");
            },
            error: function() {
              alert('fail1');
            }
          });
        });
        //快捷留言
        $(".quickMessage li").click(function() {
          var lit = $(this).find("p").text()
          $(".Message").val(lit)
        });
      });


$("#msgform1").submit(function(){
  var username=$("#Name1").val(); 
  var tel=$("#Tel1").val();  
  var msg=$("#msg1").val();
  var cn=/^[\u0391-\uFFE5]+$/;   
  var http=/^http[s]?:\/\/([\w-]+\.)+[\w-]+([\w-./?%&=]*)?$/; 
  var mobile = /^1[3|5|8]\d{9}$/ , phone = /^0\d{2,3}-?\d{7,8}$/; 
  if(username=="" || username=="请输入姓名" || (username != "" && !cn.test(username))) 
      { 
        alert("请输入您的中文姓名"); 
        $("#Name1").focus(); 
        return false; 
      }      
      if(username.length>5){
          alert("请控制在5个字以内！");
          $("#Name1").focus(); 
          return false; 
      }                            
      if (tel=="" || (tel != "" && !mobile.test(tel) && !phone.test(tel))) {
          alert("请输入正确有效的手机号码"); 
          $("#Tel1").focus(); 
          return false; 
      }
      if((msg != "" && http.test(msg))){ 
            alert("请输入中文问题！"); 
            $("#msg1").focus(); 
            return false; 
        } 

});


$("#msgform2").submit(function(){
  var username=$("#Name2").val(); 
  var tel=$("#Tel2").val(); 
  var msg=$("#msg2").val();               
  var cn=/^[\u0391-\uFFE5]+$/; 
  var http=/^http[s]?:\/\/([\w-]+\.)+[\w-]+([\w-./?%&=]*)?$/;
  var mobile = /^1[3|5|8]\d{9}$/ , phone = /^0\d{2,3}-?\d{7,8}$/; 
  if(username=="" || username=="请输入姓名" || (username != "" && !cn.test(username))) 
      { 
        alert("请输入您的中文姓名"); 
        $("#Name2").focus(); 
        return false; 
      }      
      if(username.length>5){
          alert("请控制在5个字以内！");
          $("#Name2").focus(); 
          return false; 
      }                       
      if (tel=="" || (tel != "" && !mobile.test(tel) && !phone.test(tel))) { 
          alert("请输入正确有效的手机号码"); 
          $("#Tel2").focus(); 
          return false; 
      }   
       if((msg != "" && http.test(msg))){ 
            alert("请输入中文问题！"); 
            $("#msg2").focus(); 
            return false; 
        }

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
    