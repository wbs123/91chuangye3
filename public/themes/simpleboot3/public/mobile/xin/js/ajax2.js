"use strict";

// 特别注意:
// 1.加载更多功能如果后台接口写完,可以把接口交给前端让前端来写.
// 2.如果自己也可以写,可以自己写着试试,遇到问题问前端
// 3.静态页面的加载更多功能是个假的,在js中为假数据(当前的data变量)
// 4.当前加载更多功能我只提供了一个'addListDom'的函数, 此函数为传递参数渲染数据
// 5.这里因为需要接口,所以有问题一定要及时沟通
// 6.js/ajax.js文件为es6转换为es5的代码,源码请从js/ajax.map.js文件中更改转为es5即可
// 7.如果不改拼接字符串也可以直接在js/ajax.js文件夹下改
$(function () {
  var id = 0;
  $('.projectPage .contentList .more-btn').click(function () {
    // 全部分类
    // var classification = $('.classification .content .right ul li.active a').attr('attr')
    // 投资金额
    // var investment = $('.investment ul li a.active').html()
    // 地区
    // var map = $('.region ul li a.active').html()
    // var url = $('#url').val();
    var keyword = $('#keyword').val();
    id+=1;
    $.ajax({
      type: "POST",
      url: "/plus/ajaxkeyword",
      data: {keyword:keyword,page:id},
      dataType: "json",
      success: function (msg) {
        if(msg.html){
          $('.asd').append(msg.html);
        }else{
          $(".projectPage .contentList .more-btn .down span").html("没有更多内容了");
        }
      }
    });

    // 点击完后添加dom
    // addListDom(data);
  });


});