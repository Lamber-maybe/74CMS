/*轮播*/


function hover(str,showStr){
    $("."+str).hover(function () {
        $(this).find('.'+showStr).addClass("hover");
    },
    function () {
        $(this).find('.'+showStr).removeClass("hover");
    })
}
$(function () {
    //导航
    $(".navItems").click(function () {
        $(this).siblings().removeClass("navActive")
        $(this).addClass("navActive")
    })

    // 更多服务 下拉框
    hover('services','servicesList')
    hover('tag','tagList')
    hover('fixed','fixedOther')

    // 广告栏的显示
    $(".width1").hover(function () {
        $(this).find('.innerCard').addClass("hover");
    },
    function () {
        $(this).find('.innerCard').removeClass("hover");
    })

    $(".typeHover").hover(function () {
        $(this).find('.occupationItemList').addClass("hover");
        $(this).removeClass('iconRight').addClass("iconRightColor");
    },
    function () {
        $(this).find('.occupationItemList').removeClass("hover");
        $(this).removeClass('iconRightColor').addClass("iconRight");
    })


    /*tab切换*/
    $(".tab span").click(function () {
        $(this).siblings().removeClass("tabActive")
        $(this).addClass("tabActive")
        var dataType = $(this).attr("data-type")
        $(".dataType"+dataType).siblings().hide()
        $(".dataType"+dataType).show()
    })
    $(".bannerTab span").click(function () {
        $(this).siblings().removeClass("loginActive")
        $(this).addClass("loginActive")
    })

    $(".tabs").click(function () {
        $(this).siblings().removeClass("noticeActive")
        $(this).addClass("noticeActive")
    })

    $('li[isLoaded != 1]').each(function() {
        var oT = $(this).offset().top;
        var sT = $(window).scrollTop();
        var cH = $(window).height();
        if (sT + cH >= oT) {
            var s = $(this).find('img').attr('Imgsrc');
            $(this).find('img').attr('src', s);
            $(this).attr('isLoaded', 1);
        }

    })
})



//右侧返回顶部
$(document).ready(function () {

    var width = $(window).width();
    var height = window.innerHeight;
    var document_height = $(document).height();
    var footer_height = $('.footerOut').height();
    var oTop1 = $(document).scrollTop();

    $(window).scroll(function(){
        var oTop2 = $(document).scrollTop();
        var st = $(this).scrollTop();
        oTop1 = $(document).scrollTop();
        backTop(oTop2);
    });

    function backTop(oTop2){
        if(oTop2 >= 100){
            $('#tops').css('display',"block");
            if(oTop2 + height >= document_height-footer_height){
                $('#back-top').css({'position':'fixed','top':'250px','right':'80px','bottom':'auto'});
            }else{
                $('#back-top').css({'position':'fixed','top':'250px','right':'80px','bottom':'auto'});
            }
        }else{
            $('#tops').css('display',"none");
        }
    }

    $("#tops").click(function(){
        $('body,html').animate({scrollTop:0},300);
    });
});

