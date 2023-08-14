$(function () {
  // ie下替换class 类名
  var isIE = IEVersion();
  var title_1 = $(".font");
  var font_1 = [];
  if (isIE <= 11 && isIE > 5) {
    for (var index = 0; index < title_1.length; index++) {
      var item = title_1[index];
      font_1.push(item.className);
      for (var k = 0; k < font_1.length; k++) {
        var item2 = font_1[k];
        var item2_arr = item2.split(" ");
        if (item2_arr[0] == "font") {
          item.className = item2_arr[1];
        } else {
          item.className = item2_arr[0];
        }
      }
    }
    $(".tip").show();
    $(".tip .icon").click(function (event) {
      var e = e || event;
      e.stopPropagation();
      $(".tip").hide();
    });
  } else {
    for (var index = 0; index < title_1.length; index++) {
      var item = title_1[index];
      font_1.push(item.className);
      for (var k = 0; k < font_1.length; k++) {
        var item2 = font_1[k];
        item.className = item2 + " text_style";
      }
    }
  }
  var data = {};
  getData();
  function getData() {
    $.ajax({
      url: apiUrl,
      type: "get",
      async: false,
      success: function (res) {
        data = JSON.parse(res);
      },
    });
  }
  setInterval(function () {
    getData();
  }, 60000);
  // 标题
  var title = data.data.title;
  $(".header .title").text(title);
  // 时间
  var time_1 = "";
  var time_2 = "";
  getTime();
  function getTime() {
    var date = new Date();
    var y = date.getFullYear(); // 年
    var MM = date.getMonth() + 1; // 月
    MM = MM < 10 ? "0" + MM : MM;
    var d = date.getDate(); // 日
    d = d < 10 ? "0" + d : d;
    var h = date.getHours(); // 时
    h = h < 10 ? "0" + h : h;
    var m = date.getMinutes(); // 分
    m = m < 10 ? "0" + m : m;
    var s = date.getSeconds(); // 秒
    s = s < 10 ? "0" + s : s;
    var day = date.getDay();
    var weeks = new Array(
      "星期日",
      "星期一",
      "星期二",
      "星期三",
      "星期四",
      "星期五",
      "星期六"
    );
    var week = weeks[day];
    time_1 = h + ":" + m + ":" + s;
    time_2 = y + "-" + MM + "-" + d + "/" + week;
  }
  $(".header .date .text_1").text(time_1);
  $(".header .date .text_2").text(time_2);
  setInterval(function () {
    getTime();
    $(".header .date .text_1").text(time_1);
    $(".header .date .text_2").text(time_2);
  }, 1000);

  chart1();
  chart2();
  chart3();
  chart4();
  chart5();
  chart6();
  chart7();
  chart8();
  chart9();
  function chart1() {
    var chartDom = document.getElementById("chart1");
    var myChart = echarts.init(chartDom);
    var option;
    var sex = data.data.sex.dataset;
    var legendData = [];
    for (var k = 0; k < sex.length; k++) {
      legendData.push(sex[k].name);
    }
    option = {
      legend: {
        orient: "vertical",
        right: 15,
        top: "center",
        data: legendData,
        itemWidth: 10,
        itemHeight: 10,
        textStyle: {
          color: "#fff",
        },
        orient: "vertical",
      },
      series: [
        {
          type: "pie",
          radius: ["30%", "50%"],
          center: ["35%", "50%"],
          label: {
            show: false,
            position: "center",
          },
          labelLine: {
            show: false,
          },
          color: ["#4777f5", "#fad752"],
          data: sex,
        },
      ],
    };
    option && myChart.setOption(option);
    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  function chart2() {
    var chartDom = document.getElementById("chart2");
    var myChart = echarts.init(chartDom);
    var option;
    var legendData = [];
    var age = data.data.age.dataset;
    for (var k = 0; k < age.length; k++) {
      legendData.push(age[k].name);
    }
    option = {
      legend: {
        orient: "vertical",
        right: 15,
        top: 'center',
        data: legendData,
        itemWidth: 10,
        itemHeight: 10,
        textStyle: {
          color: "#fff",
        },
        orient: "vertical",
      },
      series: [
        {
          type: "pie",
          radius: ["30%", "50%"],
          center: ["30%", "50%"],
          label: {
            show: false,
            position: "center",
          },
          labelLine: {
            show: false,
          },
          color: ["#0075c2", "#19af5c", "#ff00dd", "#fad752", "#fb741f"],
          data: age,
        },
      ],
    };
    option && myChart.setOption(option);
    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  function chart3() {
    var chartDom = document.getElementById("chart3");
    var myChart = echarts.init(chartDom);
    var option;
    var edu = data.data.edu.dataset;
    var legendData = [];
    for (var k = 0; k < edu.length; k++) {
      legendData.push(edu[k].name);
    }
    option = {
      legend: {
        orient: "vertical",
        right: 15,
        top: 'center',
        data: ["本科", "大专", "中专", "中技", "高中", "其他"],
        itemWidth: 10,
        itemHeight: 10,
        textStyle: {
          color: "#fff",
        },
        orient: "vertical",
      },
      series: [
        {
          name: "访问来源",
          type: "pie",
          radius: ["30%", "50%"],
          center: ["35%", "50%"],
          label: {
            show: false,
            position: "center",
          },
          labelLine: {
            show: false,
          },
          color: [
            "#0075c2",
            "#19af5c",
            "#ff00dd",
            "#fad752",
            "#fb741f",
            "#1acbfb",
          ],
          data: edu,
        },
      ],
    };
    option && myChart.setOption(option);
    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  function chart4() {
    var chartDom = document.getElementById("chart4");
    var myChart = echarts.init(chartDom);
    var option;
    var exp = data.data.exp.dataset;
    var legendData = [];
    for (var k = 0; k < exp.length; k++) {
      legendData.push(exp[k].name);
    }
    option = {
      legend: {
        orient: "vertical",
        right: 15,
        top: 'center',
        data: legendData,
        itemWidth: 10,
        itemHeight: 10,
        textStyle: {
          color: "#fff",
        },
        orient: "vertical",
      },
      series: [
        {
          type: "pie",
          radius: ["30%", "50%"],
          center: ["30%", "50%"],
          label: {
            show: false,
            position: "center",
          },
          labelLine: {
            show: false,
          },
          color: [
            "#0075c2",
            "#19af5c",
            "#ff00dd",
            "#fad752",
            "#fb741f",
            "#1acbfb",
            "#ff00dd",
          ],
          data: exp,
        },
      ],
    };
    option && myChart.setOption(option);
    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  function chart5() {
    var chartDom = document.getElementById("chart5");
    var myChart = echarts.init(chartDom);
    var option;
    var intention = data.data.intention.dataset;
    var legendData = [];
    for (var k = 0; k < intention.length; k++) {
      legendData.push(intention[k].name);
    }

    option = {
      // backgroundColor: "rgba(0,0,0,0)",
      tooltip: {
        trigger: "item",
        formatter: "{b}: <br/>{c} ({d}%)",
      },
      color: [
        "#0075c2",
        "#19af5c",
        "#ff00dd",
        "#fad752",
        "#fb741f",
        "#1acbfb",
        "#975FE5",
        "#36CBCB",
        "#4ECB73",
      ],
      legend: {
        //图例组件，颜色和名字
        x: "60%",
        y: "center",
        orient: "vertical",
        itemGap: 12, //图例每项之间的间隔
        itemWidth: 10,
        itemHeight: 10,
        icon: "rect",
        data: legendData,
        textStyle: {
          color: [],
          fontStyle: "normal",
          fontFamily: "微软雅黑",
          fontSize: 12,
        },
      },
      series: [
        {
          name: "行业占比",
          type: "pie",
          clockwise: false, //饼图的扇区是否是顺时针排布
          minAngle: 20, //最小的扇区角度（0 ~ 360）
          center: ["30%", "50%"], //饼图的中心（圆心）坐标
          radius: [50, 75], //饼图的半径
          avoidLabelOverlap: true, ////是否启用防止标签重叠
          itemStyle: {
            //图形样式
            normal: {
              borderColor: "#1e2239",
              borderWidth: 2,
            },
          },
          label: {
            //标签的位置
            normal: {
              show: false,
              position: "inside", //标签的位置
              // formatter: "{d}%",
              textStyle: {
                color: "#fff",
              },
            },
            emphasis: {
              show: false,
              textStyle: {
                fontWeight: "bold",
              },
            },
          },
          data: intention,
        },
        // {
        //   name: "",
        //   type: "pie",
        //   clockwise: false,
        //   silent: true,
        //   minAngle: 20, //最小的扇区角度（0 ~ 360）
        //   center: ["30%", "50%"], //饼图的中心（圆心）坐标
        //   radius: [0, 40], //饼图的半径
        //   itemStyle: {
        //     //图形样式
        //     normal: {
        //       borderColor: "#1e2239",
        //       borderWidth: 1.5,
        //       opacity: 0.41,
        //     },
        //   },
        //   label: {
        //     //标签的位置
        //     normal: {
        //       show: false,
        //     },
        //   },
        //   data: intention,
        // }
      ],
    };
    myChart.setOption(option);

    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  // 入驻企业 招聘岗位  岗位需求 求职者数
  var total_1 = data.data.total.company;
  var total_2 = data.data.total.job;
  var total_3 = data.data.total.job_amount;
  var total_4 = data.data.total.resume;
  $(".center_box .box1 .item .color_1 .sum_text").text(total_1);
  $(".center_box .box1 .item .color_2 .sum_text").text(total_2);
  $(".center_box .box1 .item .color_3 .sum_text").text(total_3);
  $(".center_box .box1 .item .color_4 .sum_text").text(total_4);

  function chart6() {
    var chartDom = document.getElementById("chart6");
    var myChart = echarts.init(chartDom);
    var option;
    var activeLegend = data.data.active.legend;
    var activeSeries = data.data.active.series;
    var activeXAxis = data.data.active.xAxis;
    var dataAry = [];
    for (var k = 0; k < activeSeries.length; k++) {
      dataAry.push({
        name: activeLegend[k],
        type: "line",
        smooth: true,
        data: activeSeries[k],
      });
    }
    option = {
      color: ["#00f0ff", "#c3ac47", "#ff4343", "#32fbb0", "#f69846"],
      legend: {
        icon: "circle",
        data: activeLegend,
        textStyle: {
          color: "#fff",
        },
      },
      xAxis: {
        type: "category",
        boundaryGap: false,
        data: activeXAxis,
        axisLine: {
          lineStyle: {
            color: "#fff",
          },
        },
      },
      yAxis: {
        type: "value",
        axisLine: {
          lineStyle: {
            color: "#fff",
          },
        },
        // min: "0",
        // max: "1000",
      },
      series: dataAry,
    };

    option && myChart.setOption(option);
    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  // 求职者实时数据统计 招聘企业实时数据统计
  var jobList = data.data.personal_event;
  var comList = data.data.company_event;
  var job_text = "";
  var com_text = "";
  $.each(jobList, function (index, value) {
    switch (value.type) {
      case "resume_refresh":
        job_text = "刷新了简历";
        break;
      case "jobadd":
        job_text = "添加了";
        break;
      case "jobrefresh":
        job_text = "刷新了";
        break;
      case "jobapply":
        job_text = "投递了";
        break;
      default:
        break;
    }
    var html =
      '<li class="item substring"><div class="text"><span class="text_1">' +
      value.fullname +
      '</span><span class="text_2">' +
      job_text +
      '</span><span class="text_3">' +
      value.jobname +
      "</span></div></li>";
    $(".statistics_box .job_list .center_list").append(html);
  });
  $.each(comList, function (index, value) {
    switch (value.type) {
      case "resume_refresh":
        com_text = "刷新了简历";
        break;
      case "jobadd":
        com_text = "发布了";
        break;
      case "jobrefresh":
        com_text = "刷新了";
        break;
      case "jobapply":
        com_text = "投递了";
        break;
      default:
        break;
    }
    var html =
      '<li class="item substring"><div class="text"><span class="text_1">' +
      value.companyname +
      '</span><span class="text_2">' +
      com_text +
      '</span><span class="text_3">' +
      value.jobname +
      "</span></div></li>";
    $(".statistics_box .com_list .center_list").append(html);
  });
  function chart7() {
    var chartDom = document.getElementById("chart7");
    var myChart = echarts.init(chartDom);
    var option;
    var nature = data.data.nature.dataset;
    var legendData = [];
    $.each(nature, function (index, value) {
      legendData.push(value.name);
    });
    option = {
      legend: {
        orient: "vertical",
        right: 15,
        top: 'center',
        data: legendData,
        itemWidth: 10,
        itemHeight: 10,
        textStyle: {
          color: "#fff",
        },
        orient: "vertical",
      },
      series: [
        {
          name: "访问来源",
          type: "pie",
          radius: ["30%", "50%"],
          center: ["30%", "50%"],
          label: {
            show: false,
            position: "center",
          },
          labelLine: {
            show: false,
          },
          color: [
            "#0075c2",
            "#19af5c",
            "#ff00dd",
            "#fad752",
            "#fb741f",
            "#1acbfb",
          ],
          data: nature,
        },
      ],
    };
    option && myChart.setOption(option);
    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  function chart8() {
    var chartDom = document.getElementById("chart8");
    var myChart = echarts.init(chartDom);
    var option;
    var scale = data.data.scale.dataset;
    var legendData = [];
    $.each(scale, function (index, value) {
      legendData.push(value.name);
    });
    option = {
      legend: {
        orient: "vertical",
        right: 15,
        top: 'center',
        data: legendData,
        itemWidth: 10,
        itemHeight: 10,
        textStyle: {
          color: "#fff",
        },
        orient: "vertical",
      },
      series: [
        {
          name: "访问来源",
          type: "pie",
          radius: ["30%", "50%"],
          center: ["30%", "50%"],
          label: {
            show: false,
            position: "center",
          },
          labelLine: {
            show: false,
          },
          color: [
            "#0075c2",
            "#19af5c",
            "#ff00dd",
            "#fad752",
            "#fb741f",
            "#1acbfb",
          ],
          data: scale,
        },
      ],
    };
    option && myChart.setOption(option);
    window.addEventListener("resize", function () {
      myChart.resize();
    });
    tools.loopShowTooltip(myChart, option, { loopSeries: true });
  }

  var hotList = data.data.hotjob.dataset;
  $.each(hotList, function (index, value) {
    var html =
      '<li class="clearfix item"><div class="idx">' +
      (index * 1 + 1) +
      '</div><div class="job substring">' +
      value.jobname +
      '</div><div class="com substring">' +
      value.companyname +
      '</div><div class="sum substring">被投递:' +
      value.total +
      "次</div></li>";
    $(".right_box .hot_list .hot_job_list").append(html);
    $(".right_box .hot_list .hot_job_list .idx")
      .eq(0)
      .attr("class", "idx idx_1");
    $(".right_box .hot_list .hot_job_list .idx")
      .eq(1)
      .attr("class", "idx idx_2");
    $(".right_box .hot_list .hot_job_list .idx")
      .eq(2)
      .attr("class", "idx idx_3");
  });

  function chart9() {
    var chartDom = document.getElementById("chart9");
    var myChart = echarts.init(chartDom);
    var option;
    var district = data.data.district.dataset;
    var xAxisData = [];
    $.each(district, function (index, value) {
      xAxisData.push(value.name);
    });
    option = {
      xAxis: {
        type: "category",
        data: xAxisData,
        axisLine: {
          lineStyle: {
            color: "#fff",
          },
        },
        axisLabel: {
          interval: 0,
        },
      },
      yAxis: {
        type: "value",
        axisLine: {
          lineStyle: {
            color: "#fff",
          },
        },
        // min: "0",
        // max: "600",
      },
      grid: {
        x: 50,
        y: 80,
        borderWidth: 1,
      },
      series: [
        {
          barWidth: 30,
          itemStyle: {
            normal: {
              barBorderRadius: 8,
              //每个柱子的颜色即为colorList数组里的每一项，如果柱子数目多于colorList的长度，则柱子颜色循环使用该数组
              color: function (params) {
                var colorList = [
                  ["#ff8563", "#fe6e9d"],
                  ["#ffac19", "#ff880c"],
                  ["#b0ef92", "#7fd952"],
                  ["#3ad0bf", "#139ed1"],
                  ["#3fb3fc", "#7f7cf1"],
                  ["#8664f8", "#cf6ced"],
                ];
                var index = params.dataIndex;
                if (params.dataIndex >= colorList.length) {
                  index = params.dataIndex - colorList.length;
                }
                return new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                  {
                    offset: 0,
                    color: colorList[index][0],
                  },
                  {
                    offset: 1,
                    color: colorList[index][1],
                  },
                ]);
              },
            },
          },
          data: district,
          type: "bar",
        },
      ],
    };
    option && myChart.setOption(option);
  }

  $("#center_swiper_1").vTicker({
    speed: 700,
    pause: 2000,
    animation: "fade",
    mousePause: true,
    showItems: 6,
  });

  $.each(jobList, function (index, value) {
    if (index % 2 == 0) {
      $(".job_list .center_list li")
        .eq(index)
        .attr("class", "item substring odd");
    } else {
      $(".job_list .center_list li").eq(index).attr("class", "item substring");
    }
  });

  $.each(comList, function (index, value) {
    if (index % 2 == 0) {
      $(".com_list .center_list li")
        .eq(index)
        .attr("class", "item substring odd");
    } else {
      $(".com_list .center_list li").eq(index).attr("class", "item substring");
    }
  });

  $("#center_swiper_2").vTicker({
    speed: 700,
    pause: 2000,
    animation: "fade",
    mousePause: true,
    showItems: 6,
  });

  $("#right_swiper_1").vTicker({
    speed: 700,
    pause: 2000,
    animation: "fade",
    mousePause: true,
    showItems: 5,
  });

  window.onresize = function () {
    window.location.reload();
  };

  function IEVersion() {
    var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
    var isIE =
      userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1; //判断是否IE<11浏览器
    var isEdge = userAgent.indexOf("Edg") > -1 && !isIE; //判断是否IE的Edge浏览器
    var isIE11 =
      userAgent.indexOf("Trident") > -1 && userAgent.indexOf("rv:11.0") > -1;
    if (isIE) {
      var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
      reIE.test(userAgent);
      var fIEVersion = parseFloat(RegExp["$1"]);
      if (fIEVersion == 7) {
        return 7;
      } else if (fIEVersion == 8) {
        return 8;
      } else if (fIEVersion == 9) {
        return 9;
      } else if (fIEVersion == 10) {
        return 10;
      } else {
        return 6; //IE版本<=7
      }
    } else if (isEdge) {
      return "edge"; //edge
    } else if (isIE11) {
      return 11; //IE11
    } else {
      return -1; //不是ie浏览器
    }
  }
});
