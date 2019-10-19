$(function () {
    // 瞄点滚动到指定位置
    (function () {
        // 获取offsetTop
        function getOffsetTop(val) {
            return parseInt(val.offset().top);
        }
        // 获取高度
        function getDomHeight(val) {
            return parseInt(val.outerHeight(true));
        }
        // offsetTop与height相加
        function getSummation(val) {
            var pint = parseInt(getOffsetTop(val) + getDomHeight(val));
            return reduceHeight(pint);
        }
        // 减去定位指定DOM的高度
        function reduceHeight(val) {
            // 减去固定导航高度
            // val = val - getDomHeight($('.cata-log .list'));
            // 减去固定头部高度
            // val = val - getDomHeight($('.item_jminfo .tabs-tit.active'));
            val = val - 52 /* 52为头部固定高度 */
            return val;
        }

        // 给指定dom添加active
        function addNavActive(number) {
            if ($('.item_jminfo .tabs-tit li').length <= 4) {
                number = number - 1
            }
            return $('.item_jminfo .tabs-tit li').eq(number).addClass('on').siblings().removeClass('on');
        }

        // 获取页面高度区间
        function getSection() {
            // 获取总高度
            var domponNumber = 1;
            return function (val, number) {
                var arr = [];
                if (number == 1) {
                    domponNumber = getSummation(val);
                    var oneHeight = getOffsetTop(val);
                    arr = [oneHeight - 100, domponNumber];
                    return arr;
                } else if (number == undefined) {
                    arr = [domponNumber];
                    domponNumber = getSummation(val);
                    arr.push(domponNumber);
                    return arr;
                }
            };
        }

        setTimeout(function () {

            $(window).scroll(function () {
                if ($('.item_jminfo').is(':hidden')) {
                    return false
                }
                var closure = getSection();
                var gsArray = closure($("#js_join_1"), 1);
                var elArray = closure($("#js_join_2"));
                var scArray = closure($("#js_join_3"));
                var xsArray = closure($("#js_join_4"));
                var scrollTop = $(this).scrollTop() + 10;
                if (scrollTop > gsArray[0] && scrollTop <= gsArray[1]) {
                    addNavActive(1);
                } else if (scrollTop > elArray[0] && scrollTop <= elArray[1]) {
                    addNavActive(2);
                } else if (scrollTop > scArray[0] && scrollTop <= scArray[1]) {
                    addNavActive(3);
                } else if (scrollTop > xsArray[0] && scrollTop <= xsArray[1]) {
                    addNavActive(4);
                }
            });

        }, 20)

        $('.item_jminfo .tabs-tit li').click(function () {
            var index = $(this).index();
            if ($('.item_jminfo .tabs-tit li').length <= 4) {
                index = index + 1
            }
            var topnumber = 0;

            if (index != 0) {
                if (index == 1) {
                    topnumber = getOffsetTop($("#js_join_1"));
                } else if (index == 2) {
                    topnumber = getOffsetTop($('#js_join_2'));
                } else if (index == 3) {
                    topnumber = getOffsetTop($('#js_join_3'));
                } else if (index == 4) {
                    topnumber = getOffsetTop($('#js_join_4'));
                }
                topnumber = topnumber - 52 - 20; /* 52为固定头部高度, 20为留个间距,可随意调整 */
                $("html,body").stop().animate({ scrollTop: topnumber + 1 }, 500);
                return false
            }

        });
    })();
})

$(function () {
    if (sessionStorage.getItem('ProjectDetailsSecondaryNavigationIndex91')) {
        var ProjectDetailsSecondaryNavigationIndex91;
        ProjectDetailsSecondaryNavigationIndex91 = sessionStorage.getItem('ProjectDetailsSecondaryNavigationIndex91')
        console.log(ProjectDetailsSecondaryNavigationIndex91)
        $('.item_jminfo .tabs-tit li').eq(ProjectDetailsSecondaryNavigationIndex91).click()
        sessionStorage.removeItem('ProjectDetailsSecondaryNavigationIndex91')
    }
})