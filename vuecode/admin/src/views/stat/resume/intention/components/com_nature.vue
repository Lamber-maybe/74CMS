<template>
  <div>
    <el-card class="box-card">
      <el-row :gutter="20">
        <el-col :span="12">
          <div id="com_nature" :style="'height: ' + height + ';'" />
        </el-col>
        <el-col :span="12">
          <el-table
            :data="dataset"
            border
            stripe
            size="mini"
            :height="height"
            style="width: 100%"
          >
            <el-table-column prop="number" label="排名" />
            <el-table-column prop="name" label="企业性质" />
            <el-table-column prop="value" label="人数" />
          </el-table>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { intentionComNature } from '@/api/stat'
import echarts from 'echarts'

export default {
  props: ['height'],
  data() {
    return {
      dataset: [],
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
      intentionComNature({}).then(response => {
        this.dataset = [...response.data.dataset]
        this.charts.setOption({
          series: [
            {
              data: this.dataset
            }
          ]
        })
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.setOption({
        title: {
          text: '求职者申请职位企业性质流向',
          left: 'left'
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a}<br/>{b}:{c} ({d}%)'
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
