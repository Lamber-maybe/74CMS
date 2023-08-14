<template>
  <div>
    <el-card class="box-card">
      <div id="setmeal_bar" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { businessSetmeal } from '@/api/stat'
import echarts from 'echarts'

export default {
  props: ['height'],
  data() {
    return {
      charts: ''
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('setmeal_bar')
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
      businessSetmeal({}).then(response => {
        this.charts.setOption({
          dataset: {
            dimensions: response.data.dimensions,
            source: response.data.source
          }
        })
        this.charts.hideLoading()
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.setOption({
        title: {
          text: '企业套餐会员分析',
          left: '0'
        },
        legend: {},
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        grid: {
          left: '3%'
        },
        dataset: {
          dimensions: [],
          source: []
        },
        xAxis: {
          type: 'category',
          axisTick: {
            alignWithLabel: true
          }
        },
        yAxis: {},
        series: [
          {
            type: 'bar',
            barWidth: '10%',
            lineStyle: {
              color: '#3aa1ff'
            },
            itemStyle: {
              color: '#3aa1ff'
            }
          },
          {
            type: 'bar',
            barWidth: '10%',
            lineStyle: {
              color: '#f2637b'
            },
            itemStyle: {
              color: '#f2637b'
            }
          }
        ]
      })
    }
  }
}
</script>
