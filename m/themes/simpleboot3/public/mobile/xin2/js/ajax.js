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
  /* data变量为假数据, 这里在接口出来后就可以删掉, 字段可以仿照这里 */
  // var data = [{
  //   // 图片
  //   img: 'https://uploads.qj.com.cn/images/25295/201401248706777.jpg',
  //   // 标题
  //   title: '黄焖鸡焖米饭',
  //   // 价格
  //   price: '1-5万',
  //   // 标签
  //   tab: [{
  //     title: '餐饮',
  //     href: 'http://www.baidu.com'
  //   }, {
  //     title: '上海市',
  //     href: 'http://www.baidu.com'
  //   }],
  //   // 描述
  //   desc: '生根餐饮管理(上海)系统经营',
  //   // 详情链接
  //   href: 'xm_info.html'
  // }];

  // for (var i = 0; i < 9; i++) {
  //   data.push(data[0]);
  // } // 传参添加dom列表函数


  // function addListDom(data) {
  //   var result = '';
  //   $.grep(data, function (item) {
  //     // 注意:这里可以改变传来的字段名称,这里的字段默认使用我定义的字段名称
  //     var img = item.litpic;
  //     var href = item.href;
  //     var title = item.title;
  //     var price = item.invested;
  //     var tab = [{
  //       title:item.category_name,
  //     },{
  //       title:item.company_address
  //     }]
  //     // console.log(tab)
  //     var desc = item.companyname;
  //     result += "\n            <li>\n            <div class=\"img\">\n                <a href=\"xm_info.html\">\n                    <img class=\"lazy\" src=\"".concat(img, "\" alt=\"\">\n                </a>\n            </div>\n            <div class=\"text\">\n                <div class=\"left\">\n                    <div class=\"title\">\n                        <h2>\n                            <a href=\"").concat(href, "\">").concat(title, "</a>\n                        </h2>\n                    </div>\n                    <div class=\"price\">\uFFE5").concat(price, "</div>\n                    <div class=\"smallTab\">\n                        <a href=\"").concat(tab[0].href, "\">").concat(tab[0].title, "</a>\n                        <a href=\"").concat(tab[1].href, "\">").concat(tab[1].title, "</a>\n                    </div>\n                    <div class=\"desc\">").concat(desc, "</div>\n                </div>\n                <div class=\"right\">\n                    <div class=\"join\"><a href=\"").concat(href, "\">\u54A8\u8BE2</a></div>\n                </div>\n            </div>\n        </li>\n            ");
  //   });
  //   $('.projectPage .contentList ul').append(result);
  // } // 点击加载更多点击事件

  var id = 0;
  $('.projectPage .contentList .more-btn').click(function () {
    // 全部分类
    var classification = $('.classification .content .right ul li.active a').attr('attr');

    if(classification == undefined){
      var classification = $('.contentTab').find('.swiper-wrapper').find('.swiper-slide.active').find('div').attr('attr');
    }
    // 投资金额
    var investment = $('.investment ul li a.active').html()
    // 地区
    var map = $('.region ul li a.active').html()
    var url = $('#url').val();
    id+=1;
    $.ajax({
      type: "POST",
      url: "/cate/listajax",
      data: {id:classification,num:investment,address:map,url:url,page:id},
      dataType: "json",
      success: function (msg) {
        if(msg.html){

          $('.asd').append(msg.html);
          $("img.lazy").lazyload({
            effect: "fadeIn",
            container:$('.projectPage .contentList ul'),
            threshold: 200,
            skip_invisible: false
          });
        }else{
          $(".projectPage .contentList .more-btn .down span").html("没有更多内容了");
        }

      }
    });

    // 点击完后添加dom
    // addListDom(data);
  });


});