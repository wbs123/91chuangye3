$(function () {
    var id = 0;
    // 更多点击事件
    $('.news .swiper-container .swiper-wrapper .swiper-slide .more-btn .down').click(function () {
        var url = $('#url').val();
        var data = '';
        id+=1;
        $.ajax({
            type: "POST",
            url: "/news/more",
            data: {url:url,page:id},
            dataType: "json",
        }).then(function(msg){
                data = msg.data;
                if(data.length<1){
                    $(".news .swiper-container .swiper-wrapper .swiper-slide .more-btn .down span").html("没有更多内容了");
                }
            for (var i = 0; i < data.length; i++) {
                AddListFun(data[i])
            }
        })
    })

    // 添加dom函数, data为一个数组数据
    function AddListFun(data) {
        var result = "\n        <li>\n            <a href=\"".concat(data.href, "\">\n                <div class=\"left\">\n                    <div class=\"title\">").concat(data.post_title, "</div>\n                    <div class=\"desc\">\n                    ").concat(data.post_excerpt, "\n                    </div>\n                    <div class=\"date\">\n                        <img src=\"").concat(data.timerImg, "\"\n                            alt=\"\">\n                        <span>").concat(data.timer, "</span>\n                    </div>\n                </div>\n                <div class=\"img\">\n                    <img class=\"swiper-lazy lazy\"\n                        data-original=\"").concat(data.img, "\"\n                        src=\"").concat(data.defaultImg, "\"\n                        alt=\"").concat(data.post_title, "\">\n                </div>\n            </a>\n        </li>\n        ");
        $('.news .swiper-container .swiper-wrapper .swiper-slide ul').append(result);
    }


})


$(function () {
    // 懒加载
    $("img.lazy").lazyload({
        skip_invisible: false,
        effect: "fadeIn",
        failure_limit: 999
    });
})