<template>
  <div style="position:relative;">
    <el-radio-group v-model="labelPosition" size="small" @change="radioChange" style="position:absolute;right:245px;top:18px;z-index:1;width:240px;">
      <el-radio-button label="7">近7天</el-radio-button>
      <el-radio-button label="30">近30天</el-radio-button>
      <el-radio-button label="auto">自定义</el-radio-button>
    </el-radio-group>
    <el-date-picker
      v-model="daterange"
      size="mini"
      type="daterange"
      range-separator="至"
      start-placeholder="开始日期"
      end-placeholder="结束日期"
      format="yyyy-MM-dd"
      value-format="yyyy-MM-dd"
      :editable="false"
      :disabled="disabled"
      style="position:absolute;right:18px;top:18px;z-index:1;width:240px;"
      @change="fetchData"
    />
    <div id="reg_line" style="height: 300px;" />
  </div>
</template>

<script>
import { newDataStatisticsChart } from '@/api/dashboard';
import echarts from 'echarts';
var colorList = ['#3aa1ff', '#f2637b', '#4ecb73', '#fbd437', '#36cbcb', '#975fe5', '#f263d9', '#435188', '#8bf263', '#5254cf'];
export default {
  props: ['daterange', 'type'],
  data() {
    return {
      charts: '',
      labelPosition: '7',
      disabled: true,
      type:'down_resume_line'
    };
  },
  watch:{
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('down_resume_line');
    });
  },
  created() {
    this.fetchData(null, true);
  },
  methods: {
    radioChange(e) {
      if (e == 'auto') {
        this.disabled = false;
      } else {
        this.disabled = true;
      }
    },
    fetchData(e, init = false) {
      if (init === false) {
        this.charts.showLoading();
      }
      const params = {
        type: this.type
      };
      newDataStatisticsChart(params).then(response => {
        const series_data = [...response.data.series];
        const legendList = ['注册趋势', '收入趋势', '企业活跃度', '个人活跃度'];
        const series_arr = [];
        for (const key in series_data) {
          const index = key % colorList.length;
          const obj = {
            name: legendList[key],
            type: 'line',
            data: series_data[key],
            lineStyle: {
              color: colorList[index]
            },
            itemStyle: {
              color: colorList[index]
            }
          };
          series_arr.push(obj);
        }
        this.charts.setOption({
          legend: {
            data: response.data.legend,
            bottom: 0
          },
          grid: {
            x: '2%',
            y: '25%',
            x2: '2%',
            y2: '15%'
          },
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: response.data.xAxis
          },
          series: series_arr
        });
        this.charts.hideLoading();
      });
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id));
      this.charts.showLoading();
      this.charts.setOption({
        title: {
          text: ''
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: []
        },
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: []
        },
        yAxis: {
          type: 'value'
        },
        series: []
      });
    }
  }
};
</script>
