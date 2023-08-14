<template>
  <div>
    <el-card class="box-card">
      <div id="sex_pie" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { resumeOverviewSex } from '@/api/stat'
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
      this.drawChart('sex_pie')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      resumeOverviewSex({}).then(response => {
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
          text: '性别分布',
          left: 'center'
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a}<br/>{b}：{c}人 ({d}%)'
        },
        series: [
          {
            name: '性别',
            type: 'pie',
            radius: ['40%', '60%'],
            center: ['50%', '60%'],
            data: [],
            itemStyle: {
              normal: {
                color: function(params) {
                  var colorList = [
                    '#3aa1ff',
                    '#f2637b'
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
