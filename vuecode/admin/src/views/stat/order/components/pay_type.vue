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
      <div id="pay_type" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { orderPayType } from '@/api/stat'
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
      this.drawChart('pay_type')
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
      orderPayType(params).then(response => {
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: response.data.xAxis
          },
          series: [
            {
              name: '支付宝支付',
              type: 'line',
              data: response.data.series[0]
            },
            {
              name: '微信支付',
              type: 'line',
              data: response.data.series[1]
            },
            {
              name: '后台支付',
              type: 'line',
              data: response.data.series[2]
            },
            {
              name: '其他',
              type: 'line',
              data: response.data.series[3]
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
          text: '各订单支付方式结构'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['支付宝支付', '微信支付', '后台支付', '其他']
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
            name: '支付宝支付',
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
            name: '微信支付',
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
            name: '后台支付',
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
            name: '其他',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#fbd437'
            },
            itemStyle: {
              color: '#fbd437'
            }
          }
        ]
      })
    }
  }
}
</script>
