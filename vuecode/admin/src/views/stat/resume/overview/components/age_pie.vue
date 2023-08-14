<template>
  <div>
    <el-card class="box-card">
      <div id="age_pie" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { resumeOverviewAge } from '@/api/stat'
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
      this.drawChart('age_pie')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      resumeOverviewAge({}).then(response => {
        this.charts.setOption({
          series: [
            {
              data: response.data.dataset
            }
          ]
        })
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.setOption({
        title: {
          text: '年龄分布',
          left: 'center'
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a}<br/>{b}：{c}人 ({d}%)'
        },
        series: [
          {
            name: '年龄',
            type: 'pie',
            radius: ['40%', '60%'],
            center: ['50%', '60%'],
            data: [],
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
            }
          }
        ]
      })
    }
  }
}
</script>
