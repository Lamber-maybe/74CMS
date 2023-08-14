<template>
  <div>
    <el-card class="box-card">
      <div id="exp_pie" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { resumeOverviewExp } from '@/api/stat'
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
      this.drawChart('exp_pie')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      resumeOverviewExp({}).then(response => {
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
          text: '经验分布',
          left: 'center'
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a}<br/>{b}：{c}人 ({d}%)'
        },
        series: [
          {
            name: '工作经验',
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
