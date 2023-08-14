<template>
  <div>
    <el-card class="box-card">
      <div id="com_nature" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { companyOverviewComNature } from '@/api/stat'
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
      this.drawChart('com_nature')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      companyOverviewComNature({}).then(response => {
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
          text: '企业性质分布',
          left: 'center'
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a}<br/>{b}：{c} ({d}%)'
        },
        series: [
          {
            name: '企业性质',
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
