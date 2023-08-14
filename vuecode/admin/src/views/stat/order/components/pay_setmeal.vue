<template>
  <div>
    <el-card class="box-card" style="position:relative;">
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
        style="position:absolute;right:18px;top:18px;z-index:999;width:240px;"
        @change="fetchData"
      />
      <el-select
        v-model="platform"
        size="mini"
        placeholder="请选择渠道"
        style="position:absolute;right:268px;top:18px;z-index:999;width:120px;"
        @change="fetchData"
      >
        <el-option label="不限渠道" value="" />
        <el-option
          v-for="(item, index) in platformOptions"
          :key="index"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <div id="pay_setmeal" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { orderPaySetmeal } from '@/api/stat'
import echarts from 'echarts'
var colorList = [
  '#3aa1ff',
  '#f2637b',
  '#4ecb73',
  '#fbd437',
  '#36cbcb',
  '#975fe5',
  '#f263d9',
  '#435188',
  '#8bf263',
  '#5254cf'
]
export default {
  props: ['height', 'platformOptions'],
  data() {
    return {
      platform: '',
      daterange: [],
      charts: ''
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('pay_setmeal')
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
        daterange: this.daterange,
        platform: this.platform
      }
      orderPaySetmeal(params).then(response => {
        const series_data = [...response.data.series]
        const series_arr = []
        for (const key in series_data) {
          const index = key % colorList.length
          const obj = {
            name: response.data.legend[key],
            type: 'line',
            data: series_data[key],
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
            data: response.data.legend
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
          text: '企业新开通套餐趋势分析'
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
