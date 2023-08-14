<template>
  <div id="income_line" style="height:300px;" />
</template>

<script>
import { dashboardChart } from '@/api/dashboard'
import echarts from 'echarts'

export default {
  data() {
    return {
      charts: ''
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('income_line')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const params = {
        type: '3'
      }
      dashboardChart(params).then(response => {
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: response.data.xAxis
          },
          series: [
            {
              type: 'line',
              data: response.data.series
            }
          ]
        })
        this.charts.hideLoading()
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.showLoading()
      this.charts.setOption({
        tooltip: {
          trigger: 'axis'
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
        series: [
          {
            type: 'line',
            data: [],
            lineStyle: {
              color: '#3aa1ff'
            },
            itemStyle: {
              color: '#3aa1ff'
            }
          }
        ]
      })
    }
  }
}
</script>
