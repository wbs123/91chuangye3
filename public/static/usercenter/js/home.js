$(function() {
    //解析登录的ip
    $.ajax({
        type: "get",
        url: host + "v1/home/get_city_by_ip.php?ip=" + ip,
        dataType: "json",
        jsonp: "callback",
        success: function(json){
            var code = parseInt(json.code);
            if(code === 0)
            {
                $("#loginCity").html(json.data.city);
            }
        },
    });

    //1831认证
    $.ajax({
        url: host + 'v1/home/auth.php',
        type: 'post',
        dataType: 'html',
        success: function (html)
        {
            if (html === 'false')
            {
                window.location.reload();
            }else{
                $('.accountT_oneLeft').after(html);
            }
        }
    });

    //服务时间
    $.ajax({
        url: host + 'v1/home/service.php',
        type: 'post',
        data: {project_id: project_id},
        dataType: 'html',
        success: function (html)
        {
            if (html === 'false')
            {
                window.location.reload();
            }else {
                $('.icon_many').after(html);
            }
        }
    });

    //资料完善度
    $.ajax({
        url: host + 'v1/home/perfect.php',
        type: 'post',
        data: {project_id: project_id},
        dataType: "JSON",
        success: function (result) {
            if (result === 'false')
            {
                window.location.reload();
            }else {
                $('.circle_progress').html(result.info);
                $('.grayMark').after(result.layer);
            }
        }
    });

    //加盟意向
    $.ajax({
        url: host + 'v1/home/message.php',
        type: 'post',
        dataType: 'JSON',
        success: function (result) {
            if (result === 'false')
            {
                window.location.reload();
            }else {
                $('.liulan_times').find('.bottom_zhangHuTitle').after(result.pv);
                $('.guanZhu_people').find('.bottom_zhangHuTitle').after(result.fc);
                $('.jiaM_yiX').find('.bottom_zhangHuTitle').after(result.cv);
                $('.mine_keH').find('.my_clientTitle').after(result.chart);
            }
        }
    });

    //留言数据统计
    $.ajax({
        url: host + 'v1/home/statistics.php',
        type: 'post',
        data: {id: project_id},
        dataType: 'html',
        success: function (html) {
            if (html === 'false')
            {
                window.location.reload();
            }else {
                $('.management_fabu').before(html);
            }
        }
    });

    //调取加盟者动态
    $.ajax({
        url: host + 'v1/home/dynamic.php',
        type: 'post',
        data: {id: project_id},
        dataType: 'html',
        success: function (html){
            if (html === 'false')
            {
                window.location.reload();
            }else {
                $('.franchise').find('.title').after(html);
            }
        }
    });

    //展会活动
    $.ajax({
        url: host + 'v1/home/exhibition.php',
        type: 'post',
        data: {id: project_id},
        dataType: 'html',
        success: function (html){
            if (html === 'false')
            {
                window.location.reload();
            }else {
                $('.huoDongDl').html(html);
            }
        }
    });

    //公众号文章
    $.ajax({
        url: host + 'v1/home/we_chat.php',
        type: 'post',
        dataType: 'html',
        success: function (html){
            if (html === 'false')
            {
                window.location.reload();
            }else {
                $('.gongZhonghao .title').after(html);
            }
        }
    });

    //调取行业分析
    $.ajax({
        url: host + 'v1/home/article.php',
        type: 'post',
        dataType: 'html',
        success: function (html){
            if (html === 'false')
            {
                window.location.reload();
            }else {
                $('.industry_fenX .ul1').html(html);
            }
        }
    });
});

function firstLoad(user_id)
{
    var timestamp =Date.parse(new Date());
    $.ajax({
        url:host + 'v1/home/set_is_close.php',
        type:'post',
        data:{userid: user_id,timestamp: timestamp},
        success:function(result) {
            if (result === 'false')
            {
                window.location.reload();
            }else {
                result = parseInt(result);
                if (result === 1)
                {
                    $(".firstLoad").remove();
                }
            }
        }
    });
}

function show_layer()
{
    $('.grayMark').show();
    $('.inspectBox').show();
}

function close_layer()
{
    $('.grayMark').hide();
    $('.inspectBox').hide();
}

$('.click_button').click(function (event) {
    event.stopPropagation();

    $('.fabu_list').toggle();
    $('.management_fabu .fabu:last').toggleClass('on');
});

$('.management').live('mouseover',function () {
    $(this).addClass('active');
}).live('mouseout',function () {
    $(this).removeClass('active');
});

$('.fabuA').live('mouseover',function () {
    $(this).addClass('active');
}).live('mouseout',function () {
    $(this).removeClass('active');
});

$(".hide_ewm,.show_ewm").mouseover(function(){
    $(".show_ewm").stop().animate({left:"0"},300);
}).mouseout(function(){
    $(".show_ewm").stop().animate({left:"-150px"},300);
});

$(document).click(function () {
    $(".fabu_list").hide();
    $('.management_fabu .fabu').removeClass('on');
});