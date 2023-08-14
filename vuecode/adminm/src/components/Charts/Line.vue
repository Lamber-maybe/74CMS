<template>
  <div class="chartbox" ref="chart"></div>
</template>

<script>
  import * as echarts from 'echarts'
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
  export default {
    name: "chart_line",
    props:['dataset'],
    mounted() {
      this.$nextTick(function() {
        this.drawChart()
      })
    },
    data () {
      return {
        charts:{}
      }
    },
    methods:{
      drawChart() {
        let that = this
        const series_data = [...that.dataset.series]
        const series_arr = []
        for (const key in series_data) {
          const index = key % colorList.length
          const obj = {
            name: that.dataset.legend[key],
            type: 'line',
            data: series_data[key],
            lineStyle: {
              color: colorList[index]
            },
            itemStyle: {
              color: colorList[index]
            }
          }
          series_arr.push(obj)
        }


        that.charts = echarts.init(that.$refs.chart)
        that.charts.setOption({
          tooltip: {
            trigger: 'axis'
          },
          legend: {
            y:'bottom',
            data: that.dataset.legend
          },
          grid: {
            top:'10%',
            left: '6%',
            right: '8%',
            bottom: '25%',
            containLabel: true
          },
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: that.dataset.xAxis
          },
          yAxis: {
            type: 'value'
          },
          series: series_arr
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
.chartbox{
  height:480px;
}
</style>