<template>
  <div>
    <el-card class="box-card">
      <div id="service_bar" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { businessService } from '@/api/stat'
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
      this.drawChart('service_bar')
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
      businessService({}).then(response => {
        this.charts.setOption({
          xAxis: {
            data: response.data.label
          },
          series: [
            {
              data: response.data.dataset
            }
          ]
        })
        this.charts.hideLoading()
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.setOption({
        title: {
          text: '职位增值服务分析',
          left: '0'
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        grid: {
          left: '6%'
        },
        xAxis: {
          type: 'category',
          axisTick: {
            alignWithLabel: true
          },
          data: []
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name: '职位数',
            type: 'bar',
            barWidth: '10%',
            itemStyle: {
              normal: {
                color: function(params) {
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
                  const index = params.dataIndex % colorList.length
                  return colorList[index]
                }
              }
            },
            data: []
          }
        ]
      })
    }
  }
}
</script>
