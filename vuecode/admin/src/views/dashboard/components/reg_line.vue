<template>
  <div style="position:relative;">
    <el-radio-group v-model="labelPosition" class="radioGroup" size="mini" @change="radioChange">
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
      style="position:absolute;right:0;top:2px;z-index:1;width:240px;"
      @change="fetchData"
    />
    <div v-show="type == 'reg'" id="reg_line1" style="height: 320px;" />
    <div v-show="type == 'income'" id="reg_line2" style="height: 320px;" />
    <div v-show="type == 'personal'" id="reg_line3" style="height: 320px;" />
    <div v-show="type == 'company'" id="reg_line4" style="height: 320px;" />
  </div>
</template>

<script>
import { newDataStatisticsChart } from '@/api/dashboard'
import echarts from 'echarts'
var colorList = ['#3aa1ff', '#f2637b', '#4ecb73', '#fbd437', '#36cbcb', '#975fe5', '#f263d9', '#435188', '#8bf263', '#5254cf']
export default {
  props: ['date7', 'date30', 'tabVal'],
  data() {
    return {
      charts: '',
      labelPosition: '7',
      disabled: true,
      type: 'reg',
      daterange: []
    }
  },
  watch: {
    date7(val) {
      this.daterange = val
      this.fetchData(null, true)
    },
    tabVal(val) {
      if (parseInt(val) == 0) {
        this.type = 'reg'
      } else if (parseInt(val) == 1) {
        this.type = 'income'
      } else if (parseInt(val) == 2) {
        this.type = 'personal'
      } else if (parseInt(val) == 3) {
        this.type = 'company'
      }
      this.fetchData(null, true)
    }
  },
  mounted() {},
  created() {},
  methods: {
    radioChange(e) {
      if (e == 'auto') {
        this.disabled = false
        this.daterange = []
      } else if (e == '7') {
        this.daterange = this.date7
        this.disabled = true
      } else if (e == '30') {
        this.daterange = this.date30
        this.disabled = true
      }
      this.fetchData()
    },
    fetchData(e, init = false) {
      if (init === false) {
        this.charts.showLoading()
      }
      this.type = parseInt(this.tabVal) == 0 ? 'reg' : parseInt(this.tabVal) == 1 ? 'income' : parseInt(this.tabVal) == 2 ? 'personal' : 'company'
      const params = {
        type: this.type,
        start_time: this.daterange[0],
        end_time: this.daterange[1]
      }
      newDataStatisticsChart(params).then(response => {
        const res = response.data
        if ((this.type === 'reg')) {
          this.drawChart('reg_line1', res)
        } else if ((this.type === 'income')) {
          this.drawChart('reg_line2', res)
        } else if ((this.type === 'personal')) {
          this.drawChart('reg_line3', res)
        } else if ((this.type === 'company')) {
          this.drawChart('reg_line4', res)
        }
      })
    },
    drawChart(id, data) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.showLoading()
      this.charts.clear()
      var series_data = data.series
      const series_arr = []
      for (const key in series_data) {
        const index = key % colorList.length
        const obj = {
          name: data.legend[key],
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
        title: {
          text: ''
        },
        tooltip: {
          trigger: 'axis',
          extraCssText: 'z-index:2'
        },
        legend: {
          data: data.legend,
          top: '55'
        },
        grid: {
          x: '5%',
          y: '33%',
          x2: '2%',
          y2: '10%'
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: data.xAxis
        },
        yAxis: {
          type: 'value'
        },
        series: series_arr
      })
      this.charts.hideLoading()
    }
  }
}
</script>
<style lang="scss">
.radioGroup {
  position: absolute;
  right: 215px;
  top: 2px;
  z-index: 1;
  width: 240px;
}
</style>
