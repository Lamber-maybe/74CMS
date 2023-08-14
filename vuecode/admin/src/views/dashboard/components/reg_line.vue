<template>
  <div id="reg_line" style="height: 300px;" />
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
      this.drawChart('reg_line')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const params = {
        type: '0'
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
              name: '个人数',
              type: 'line',
              data: response.data.series[0]
            },
            {
              name: '企业数',
              type: 'line',
              data: response.data.series[1]
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
        legend: {
          data: ['个人数', '企业数']
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
            name: '个人数',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#3aa1ff'
            },
            itemStyle: {
              color: '#3aa1ff'
            }
          },
          {
            name: '企业数',
            type: 'line',
            data: [],
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
