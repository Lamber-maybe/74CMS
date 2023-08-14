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
      <div id="per_order" :style="'height: ' + height + ';'" />
    </el-card>
  </div>
</template>

<script>
import { orderPersonal } from '@/api/stat'
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
      this.drawChart('per_order')
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
      orderPersonal(params).then(response => {
        this.charts.setOption({
          dataset: {
            dimensions: response.data.dimensions,
            source: response.data.source
          }
        })
        this.charts.hideLoading()
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      this.charts.setOption({
        title: {
          text: '个人订单成交额',
          left: '0'
        },
        legend: {},
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        grid: {
          left: '3%'
        },
        dataset: {
          dimensions: [],
          source: []
        },
        xAxis: {
          type: 'category',
          axisTick: {
            alignWithLabel: true
          }
        },
        yAxis: {},
        series: [
          {
            type: 'bar',
            barWidth: '5%',
            lineStyle: {
              color: '#3aa1ff'
            },
            itemStyle: {
              color: '#3aa1ff'
            }
          },
          {
            type: 'bar',
            barWidth: '5%',
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
