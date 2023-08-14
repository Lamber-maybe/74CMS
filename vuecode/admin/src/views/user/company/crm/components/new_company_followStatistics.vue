<template>
  <div style="position:relative;">
    <!-- <el-card class="box-card" style="position:relative;"> -->
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
      style="position:absolute;right:18px;top:18px;z-index:1;width:240px;"
      @change="fetchData"
    />
    <el-select
      v-model="platform"
      size="mini"
      placeholder="请选择销售人员"
      style="position:absolute;right:268px;top:18px;z-index:1;width:120px;"
      @change="fetchData"
    >
      <el-option label="销售人员" value="" />
      <el-option v-for="(item, index) in platformOptions" :key="index" :label="item.name" :value="item.id" />
    </el-select>
    <div id="life_customer_statistics" :style="'height: ' + height + ';'" />
    <!-- </el-card> -->
  </div>
</template>

<script>
import {
  orderPaySetmeal
} from '@/api/stat'
import {
  newCompanyFollowStatistics
} from '@/api/company_crm'
import echarts from 'echarts'
var colorList = ['#3aa1ff', '#f2637b', '#4ecb73', '#fbd437', '#36cbcb', '#975fe5', '#f263d9', '#435188', '#8bf263',
  '#5254cf'
]
export default {
  props: ['height', 'platformOptions', 'daterange'],
  data() {
    return {
      platform: '',
      charts: ''
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('life_customer_statistics')
    })
  },
  created() {
    this.fetchData(null, true)
  },
  methods: {
    fetchData(e, init = false) {
      if (init === false) {
        this.charts.showLoading()
      }
      const params = {
        start_time: this.daterange[0] == undefined ? '' : this.daterange[0],
        end_time: this.daterange[1] == undefined ? '' : this.daterange[1],
        admin_id: this.platform
      }
      newCompanyFollowStatistics(params).then(response => {
        const series_data = [...response.data.series]
        const series_arr = []
        for (const key in series_data) {
          const index = key % colorList.length
          const obj = {
            name: response.data.legend[key],
            type: 'line',
            data: series_data[key],
            smooth: true,
            lineStyle: {
              color: colorList[index]
            },
            itemStyle: {
              color: colorList[index]
            }
          }
          series_arr.push(obj)
        }
        this.charts.setOption({
          legend: {
            data: response.data.legend,
            bottom: 0
          },
          tooltip: {
            trigger: 'axis',
            confine: true, // 解决浮窗被截断问题
            extraCssText: 'z-index:2'
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
        })
        this.charts.hideLoading()
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.showLoading()
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
      })
    }
  }
}
</script>
