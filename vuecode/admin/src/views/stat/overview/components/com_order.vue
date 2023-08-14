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
      <div id="com_order" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { overviewOrder } from '@/api/stat'
import echarts from 'echarts'

export default {
  props: ['height'],
  data() {
    return {
      daterange: [],
      charts: ''
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('com_order')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const params = { utype: 1, daterange: this.daterange }
      overviewOrder(params).then(response => {
        this.charts.setOption({
          legend: {
            data: response.data.label
          },
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
          text: '企业已完成订单（实收金额）',
          left: '0'
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} 元 ({d}%)'
        },
        legend: {
          orient: 'vertical',
          x: 'left',
          y: 'center',
          data: []
        },
        series: [
          {
            name: '订单类别',
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
