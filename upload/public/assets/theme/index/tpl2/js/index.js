$(".j_notice_lsit").vTicker({
  speed: 700,
  pause: 2000,
  animation: "fade",
  mousePause: true,
  showItems: 2,
});
$(window).scroll(function () {
  var scolTop = $(document).scrollTop();
  if (scolTop >= 200) {
    $(".nav_box").attr("class", "nav_box nav_active");
  } else {
    $(".nav_box").attr("class", "nav_box");
  }
});

// 头部下拉广告位
var showPopup = false
var current = new Date()
var expire_time = localStorage.getItem('index_popup_expire_time')
if (expire_time === undefined || !expire_time || current.getTime() > new Date(expire_time)) {
  showPopup = true
  expire_time = new Date(new Date(new Date().toLocaleDateString()).getTime() + 24 * 60 * 60 * 1000 - 1)
  localStorage.setItem('index_popup_expire_time', expire_time)
}
var header_source_time = 6;
var header_time = null;
var distinguish_source = true;

if ($(".down_source")[0]) {
  down_source();
} else {
  if ($(".popup_source")[0] && showPopup === true) {
    $('<div>').attr({ 'class': 'mantle_box' }).appendTo('body')
    $(".popup_source").show();
    popup_source();
  }
}

function down_source() {
  $(".source_box .img_link").animate({ height: "500px" });
  $(".source_box").animate({ height: "500px" });
  $(".down_source .source_btn").html(header_source_time);
  header_time = setInterval(function () {
    header_source_time--;
    $(".down_source .source_btn").html(header_source_time);
    if (header_source_time == 0) {
      $(".source_box .img_link").animate({ height: "0px" });
      $(".source_box").animate({ height: "0px" });
      $(".down_source .source_btn").html("重播");
      header_source_time = 6;
      $(".source_btn").attr({ disabled: false });
      clearInterval(header_time);
      if ($(".popup_source")[0] && showPopup === true) {
        if (distinguish_source) {
          $('<div>').attr({ 'class': 'mantle_box' }).appendTo('body')
          $(".popup_source").show();
          $(".source_btn").attr({ disabled: false });
          popup_source();
        }
      }
    }
  }, 1000);
}
// 重播点击事件
$(".down_source .source_btn").click(function () {
  distinguish_source = false;
  $(this).attr({ disabled: true });
  down_source();
});
// 关闭按钮
$(".down_source .source_box .img_link .close").click(function (event) {
  var e = window.event || event;
  e.preventDefault();
  distinguish_source = false;
  $(".source_box .img_link").animate({ height: "0px" });
  $(".source_box").animate({ height: "0px" });
  header_source_time = 6;
  $(".down_source .source_btn").html("重播");
  clearInterval(header_time);
  $(".source_btn").attr({ disabled: false });
  if (distinguish_source && showPopup) {
    $(".popup_source").show();
    popup_source();
  }
});

// 弹窗广告位
var popup_time = null;

function popup_source() {
  popup_time = setTimeout(function () {
    $('.mantle_box').hide()
    $(".popup_source").hide();
  }, 6000);
}
// 关闭
$(".popup_close").click(function () {
  $(".popup_source").hide();
  $('.mantle_box').hide()
  clearTimeout(popup_time);
});
