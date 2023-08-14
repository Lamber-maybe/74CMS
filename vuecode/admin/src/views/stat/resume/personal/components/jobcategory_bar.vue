<template>
  <div>
    <el-card class="box-card">
      <div id="jobcategory_bar" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { personalJobcategory } from '@/api/stat'
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
      this.drawChart('jobcategory_bar')
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
      personalJobcategory({}).then(response => {
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
          text: '求职者职类性别分布TOP20',
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
          axisLabel: {
            interval: 0,
            rotate: 40
          },
          type: 'category',
          axisTick: {
            alignWithLabel: true
          }
        },
        yAxis: {},
        series: [
          {
            type: 'bar',
            barWidth: '30%',
            lineStyle: {
              color: '#3aa1ff'
            },
            itemStyle: {
              color: '#3aa1ff'
            }
          },
          {
            type: 'bar',
            barWidth: '30%',
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
