<template>
  <div>
    <el-card class="box-card">
      <el-row style="margin-top:20px;">
        <el-col :span="14">
          <div id="intention_district" :style="'height: ' + height + ';'" />
        </el-col>
        <el-col :span="10">
          <el-table
            :data="dataset"
            border
            stripe
            size="mini"
            :height="height"
            style="width: 100%"
          >
            <el-table-column prop="number" label="排名" />
            <el-table-column prop="name" label="地区" />
            <el-table-column prop="value" label="人数" />
          </el-table>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { resumeOverviewIntentionDistrict } from '@/api/stat'
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
      this.drawChart('intention_district')
    })
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      resumeOverviewIntentionDistrict({}).then(response => {
        this.dataset = [...response.data.dataset]
        this.charts.setOption({
          xAxis: {
            data: response.data.label
          },
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
          text: '意向地区分布',
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
          axisLabel: {
            interval: 0,
            rotate: 40
          },
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
            name: '人数',
            type: 'bar',
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
