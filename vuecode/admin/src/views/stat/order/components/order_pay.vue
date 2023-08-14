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
      <div id="order_pay" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { orderPayTotal } from '@/api/stat'
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
      this.drawChart('order_pay')
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
        daterange: this.daterange,
        platform: this.platform
      }
      orderPayTotal(params).then(response => {
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: response.data.xAxis
          },
          series: [
            {
              name: '个人',
              type: 'line',
              data: response.data.series[0]
            },
            {
              name: '企业',
              type: 'line',
              data: response.data.series[1]
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
          text: '新增业务成交趋势'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['个人', '企业']
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
            name: '个人',
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
            name: '企业',
            type: 'line',
            data: [],
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
