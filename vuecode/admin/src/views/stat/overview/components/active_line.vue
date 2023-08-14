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
      <el-select
        v-model="platform"
        size="mini"
        placeholder="请选择渠道"
        style="position:absolute;right:268px;top:18px;z-index:999;width:120px;"
        @change="fetchData"
      >
        <el-option label="不限渠道" value="" />
        <el-option
          v-for="(item, index) in platformOptions"
          :key="index"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <div id="active_line" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { overviewActive } from '@/api/stat'
import echarts from 'echarts'

export default {
  props: ['height', 'platformOptions'],
  data() {
    return {
      platform: '',
      daterange: [],
      charts: ''
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('active_line')
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
      const params = {
        utype: 1,
        daterange: this.daterange,
        platform: this.platform
      }
      overviewActive(params).then(response => {
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: response.data.xAxis
          },
          series: [
            {
              name: '刷新简历',
              type: 'line',
              data: response.data.series[0]
            },
            {
              name: '发布职位',
              type: 'line',
              data: response.data.series[1]
            },
            {
              name: '刷新职位',
              type: 'line',
              data: response.data.series[2]
            },
            {
              name: '投递简历',
              type: 'line',
              data: response.data.series[3]
            },
            {
              name: '下载简历',
              type: 'line',
              data: response.data.series[4]
            },
            {
              name: '会员登录',
              type: 'line',
              data: response.data.series[5]
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
        title: {
          text: '活跃度分析'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: [
            '刷新简历',
            '发布职位',
            '刷新职位',
            '投递简历',
            '下载简历',
            '会员登录'
          ]
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
            name: '刷新简历',
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
            name: '发布职位',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#f2637b'
            },
            itemStyle: {
              color: '#f2637b'
            }
          },
          {
            name: '刷新职位',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#4ecb73'
            },
            itemStyle: {
              color: '#4ecb73'
            }
          },
          {
            name: '投递简历',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#fbd437'
            },
            itemStyle: {
              color: '#fbd437'
            }
          },
          {
            name: '下载简历',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#36cbcb'
            },
            itemStyle: {
              color: '#36cbcb'
            }
          },
          {
            name: '会员登录',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#975fe5'
            },
            itemStyle: {
              color: '#975fe5'
            }
          }
        ]
      })
    }
  }
}
</script>
