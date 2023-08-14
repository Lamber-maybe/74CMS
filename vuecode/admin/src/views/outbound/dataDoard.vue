<template>
  <div class="dashboard-container">
    <div class="statistics">
      <el-card class="box-card">
        <div class="information-left">
          <div class="title">呼出总数</div>
          <div class="data red">{{ callRecordData.call_total }}</div>
        </div>
        <div class="information-right">
          <div class="img">
            <img src="../../assets/images/outbound/outgoing_general.png" alt="">
          </div>
        </div>
      </el-card>
      <el-card class="box-card">
        <div class="information-left">
          <div class="title">接通量</div>
          <div class="data green">{{ callRecordData.connect_total }}</div>
        </div>
        <div class="information-right">
          <div class="img">
            <img src="../../assets/images/outbound/on.png" alt="">
          </div>
        </div>
      </el-card>
      <el-card class="box-card">
        <div class="information-left">
          <div class="title">无人接听量</div>
          <div class="data blue">{{ callRecordData.not_connect_total }}</div>
        </div>
        <div class="information-right">
          <div class="img">
            <img src="../../assets/images/outbound/noanswer.png" alt="">
          </div>
        </div>
      </el-card>
      <el-card class="box-card">
        <div class="information-left">
          <div class="title">呼出总时长</div>
          <div class="data orange">{{ callRecordData.call_duration }}</div>
        </div>
        <div class="information-right">
          <div class="img">
            <img src="../../assets/images/outbound/exhale.png" alt="">
          </div>
        </div>
      </el-card>
      <el-card class="box-card-duration">
        <div class="information-left">
          <div class="title">通话时长包</div>
          <div class="data top">剩余分钟数：<span>{{ callRecordData.setmeal_surplus_duration }}</span>分钟</div>
          <div class="data tops">到期时间：
            <span v-if="callRecordData.setmeal_endtime == 0">-</span>
            <span v-else>{{ callRecordData.setmeal_endtime | timeFilter }}</span>
          </div>
        </div>
        <div class="information-right">
          <div class="btn" @click="callSign">云外呼</div>
        </div>
      </el-card>
    </div>
    <div class="seats">
      <el-card class="seats-box-card">
        <div class="title">
          坐席话务信息
          <div class="screens">
            <el-date-picker
              v-model="tableScreen"
              type="daterange"
              format="yyyy-MM-dd"
              value-format="yyyy-MM-dd"
              range-separator="-"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              :picker-options="pickerOptions"
              @change="getTable"
            />
          </div>
        </div>
        <div class="table">
          <template>
            <el-table
              v-loading="loading"
              :header-cell-style="{background:'#f9f9f9','padding-left':'20px;'}"
              :data="tableData"
              height="280"
              style="width: 100%"
              @sort-change="sortTable"
            >
              <el-table-column
                prop="seat_number"
                label="坐席编号"
              />
              <el-table-column
                prop="call_total"
                label="电话量"
                sortable
              />
              <el-table-column
                prop="connect_total"
                label="接通量"
                sortable
              />
              <el-table-column
                prop="call_duration_name"
                label="总通话时长"
                sortable
              />
              <el-table-column
                prop="avg_call_duration_name"
                label="平均通话时长"
                sortable
              />
              <el-table-column
                prop="connect_ratio"
                label="接通率"
                sortable
              />
              <el-table-column
                prop="not_connect_total"
                label="无人接听量"
                sortable
              />
            </el-table>
          </template>
        </div>
      </el-card>
    </div>
    <div class="data_statistics">
      <el-card class="statistics-box-card">
        <div class="title">
          数据统计
          <div class="screens">
            <el-date-picker
              v-model="statisticsScreen"
              type="daterange"
              format="yyyy-MM-dd"
              value-format="yyyy-MM-dd"
              range-separator="-"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              :picker-options="pickerOptionsStatistics"
              @change="getStatistics"
            />
          </div>
        </div>
        <div id="overview" class="broken" style="height:438px;" />
      </el-card>
    </div>
    <el-dialog
      title="外呼拨号"
      :visible.sync="dialogVisible"
      width="21%"
      :before-close="handleClose"
    >
      <el-form ref="form" :model="formDial" label-width="80px">
        <el-form-item label="呼叫号码">
          <el-input v-model="formDial.phone" style="width: 60%" />
          <el-button type="primary" style="margin-left: 2%" @click="clickDial()">拨号</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <div class="call">
      <el-dialog
        :visible.sync="callDialogVisible"
        width="30%"
        :before-close="callHandleClose"
      >
        <div class="box-content">
          <div class="content">
            <div class="title1">您还尚未开通云呼叫服务</div>
            <div class="title2">扫码添加客服</div>
            <div class="title3">快速开通呼叫业务</div>
          </div>
          <div class="code">
            <img src="../../assets/images/outbound/renew.png" alt="">
          </div>
          <div style="clear:both" />
          <div class="slogan">一键发起云呼叫，自动录音，助力提升沟通效率！</div>
          <div class="advantage">
            <div class="advantage-box">
              <div class="img"><img src="../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">免硬件设备</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">录音清晰无杂音</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">外显销售手机号</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">招聘行业专用线路</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">稳定性更高</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">防封强、接通率高</div>
            </div>
          </div>
        </div>
      </el-dialog>
    </div>
    <div class="meet">
      <el-dialog
        :visible.sync="meetDialogVisible"
        width="30%"
        :before-close="meetHandleClose"
      >
        <div class="box-content">
          <div id="animation" class="img">
            <!--            <img src="../../assets/images/outbound/meet.png" alt="">-->
          </div>
          <div class="hu">正在呼叫客户</div>
          <div class="telephone">{{ dialPhone }}</div>
          <div class="company">{{ dialName }}</div>
          <div class="tips">请您留意手机来电接听</div>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import echarts from 'echarts'
import { dataBoardCallRecord, dataBoardcallRecordList, outboundCall, seatCallRecord } from '@/api/outbound'
import { parseTime } from '@/utils'
export default {
  name: 'DataDoard',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      pickerOptionsStatistics: {
        onPick: ({ maxDate, minDate }) => {
          this.choiceDate = minDate.getTime()
          if (maxDate) {
            this.choiceDate = ''
          }
        },
        disabledDate: (time) => {
          const self = this
          if (self.choiceDate) {
            const startDay = (new Date(self.choiceDate).getDate() - 1) * 24 * 3600 * 1000
            const endDay = (new Date(new Date(self.choiceDate).getFullYear(), new Date(self.choiceDate).getMonth() + 1, 0).getDate() - new Date(self.choiceDate).getDate()) * 24 * 3600 * 1000
            const minTime = self.choiceDate - startDay
            const maxTime = self.choiceDate + endDay
            return (time.getTime() < minTime || time.getTime() > maxTime) && time.getTime() > Date.now() - 8.64e6
          }
        }
      },
      pickerOptions: {
        onPick: ({ maxDate, minDate }) => {
          this.choiceDate = minDate.getTime()
          if (maxDate) {
            this.choiceDate = ''
          }
        },
        disabledDate: (time) => {
          const self = this
          if (self.choiceDate) {
            const startDay = (new Date(self.choiceDate).getDate() - 1) * 24 * 3600 * 1000
            const endDay = (new Date(new Date(self.choiceDate).getFullYear(), new Date(self.choiceDate).getMonth() + 1, 0).getDate() - new Date(self.choiceDate).getDate()) * 24 * 3600 * 1000
            const minTime = self.choiceDate - startDay
            const maxTime = self.choiceDate + endDay
            return (time.getTime() < minTime || time.getTime() > maxTime) && time.getTime() > Date.now() - 8.64e6
          }
        },
        shortcuts: [
          {
            text: '今天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '昨天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              end.setTime(start.getTime() - 3600 * 1000 * 24)
              start.setTime(start.getTime() - 3600 * 1000 * 24)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '本周',
            onClick(picker) {
              const now = new Date()
              var nowYear = now.getYear()
              const end = new Date()
              nowYear += nowYear < 2000 ? 1900 : 0
              const start = new Date(
                nowYear,
                now.getMonth(),
                now.getDate() - now.getDay() + 1
              )
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '本月',
            onClick(picker) {
              const now = new Date()
              var nowYear = now.getYear()
              const end = new Date()
              nowYear += nowYear < 2000 ? 1900 : 0
              const start = new Date(nowYear, now.getMonth(), 1)
              picker.$emit('pick', [start, end])
            }
          }]
      },
      formDial: {
        phone: ''
      },
      height: 500,
      screen: '',
      tableData: [],
      charts: '',
      callRecordData: '',
      statisticsScreen: '',
      statisticsStart: '',
      statisticsEnd: '',
      tableScreen: '',
      tableStart: '',
      tableEnd: '',
      dialogVisible: false,
      loading: false,
      callDialogVisible: false,
      meetDialogVisible: false,
      dialPhone: '',
      dialName: ''
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('overview')
    })
  },
  created() {
    this.fetchData(null, true)
    this.callRecord()
    this.getSeatCallRecord()
    var date = new Date()
    var year = date.getFullYear()
    var hours = date.getMonth() + 1
    var day = date.getDate()
    this.tableScreen = [year + '-' + hours + '-' + day, year + '-' + hours + '-' + day]
    this.statisticsScreen = [year + '-' + hours + '-' + day, year + '-' + hours + '-' + day]
  },
  methods: {
    sortTable({ column, order }){
      if (order == 'ascending'){
        this.order_by = 'asc'
      } else if (order == 'descending'){
        this.order_by = 'desc'
      } else {
        this.sortType = ''
      }
      if (column.property == 'call_total'){
        this.order_key = 1
      } else if (column.property == 'connect_total'){
        this.order_key = 2
      } else if (column.property == 'call_duration_name'){
        this.order_key = 3
      } else if (column.property == 'avg_call_duration_name'){
        this.order_key = 4
      } else if (column.property == 'connect_ratio'){
        this.order_key = 5
      } else if (column.property == 'not_connect_total'){
        this.order_key = 6
      } else {
        this.order_key = ''
      }
      this.getSeatCallRecord()
    },
    handleClose(){
      this.dialogVisible = false
    },
    callSign(){
      this.dialogVisible = true
    },
    getTable(){
      this.tableStart = this.tableScreen[0]
      this.tableEnd = this.tableScreen[1]
      this.getSeatCallRecord()
    },
    getStatistics(){
      this.statisticsStart = this.statisticsScreen[0]
      this.statisticsEnd = this.statisticsScreen[1]
      this.fetchData()
    },
    callRecord(){
      dataBoardCallRecord().then(res => {
        this.callRecordData = res.data
      })
    },
    getSeatCallRecord(){
      this.loading = true
      seatCallRecord({
        'call_start_time': this.tableStart,
        'call_end_time': this.tableEnd,
        'order_key': this.order_key,
        'order_by': this.order_by
      }).then(res => {
        this.tableData = res.data
        this.loading = false
      })
    },
    fetchData(e, init = false) {
      if (init === false) {
        this.charts.showLoading()
      }
      dataBoardcallRecordList({
        'call_start_time': this.statisticsStart,
        'call_end_time': this.statisticsEnd
      }).then(response => {
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: response.data.xAxis
          },
          series: [
            {
              name: '呼出总数',
              type: 'line',
              data: response.data.series[0]
            },
            {
              name: '接通总数',
              type: 'line',
              data: response.data.series[1]
            },
            {
              name: '未接通总数',
              type: 'line',
              data: response.data.series[2]
            },
            {
              name: '通话时长',
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
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['呼出总数', '接通总数', '未接通总数', '通话时长']
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
            name: '呼出总数',
            type: 'line',
            smooth: true,
            data: [],
            lineStyle: {
              color: '#3aa1ff'
            },
            itemStyle: {
              color: '#3aa1ff'
            }
          },
          {
            name: '接通总数',
            type: 'line',
            smooth: true,
            data: [],
            lineStyle: {
              color: '#f2637b'
            },
            itemStyle: {
              color: '#f2637b'
            }
          },
          {
            name: '未接通总数',
            type: 'line',
            smooth: true,
            data: [],
            lineStyle: {
              color: '#4ecb73'
            },
            itemStyle: {
              color: '#4ecb73'
            }
          },
          {
            name: '通话时长',
            type: 'line',
            smooth: true,
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
    },
    clickDial(){
      outboundCall(
        { 'mobile': this.formDial.phone }
      ).then(res => {
        if (res.code == 200){
          this.meetDialogVisible = true
          this.dialPhone = this.formDial.phone
          this.dialogVisible = false
          this.formDial.phone = ''
        }
        if (res.code == 4040){
          this.callDialogVisible = true
          this.formDial.phone = ''
        }
      }).catch((res) => {
      })
    },
    callHandleClose(){
      this.callDialogVisible = false
    },
    meetHandleClose(){
      this.meetDialogVisible = false
    }
  }
}
</script>

<style lang="scss" scoped>
.dial{
  position: relative;
  top:2px;
  width: 14px;
  height: 14px;
  display: inline-block;
  .disal_img{
    width: 14px;
    height: 14px;
    background: url('../../assets/images/outbound/dial.png') no-repeat center;
  }
}
.meet{
  .box-content{
    .img{
      width: 150px;
      height:150px;
      text-align: center;
      margin-top: 40px;
      margin:0 auto;
      background: url('../../assets/images/outbound/meet.png') no-repeat center;
    }
    .hu{
      text-align: center;
      margin-top: 30px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #949494;
    }
    .telephone{
      text-align: center;
      font-size: 30px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      margin-top: 26px;
    }
    .company{
      text-align: center;
      margin-top:25px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #666666;
    }
    .tips{
      text-align: center;
      height: 80px;
      line-height: 80px;
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #959595;
    }
  }
}
.meet{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
.call{
  .box-content{
    padding: 20px 30px 35px 30px;
    .advantage{
      .advantage-box{
        display: inline-block;
        width: 170px;
        .title{
          display: inline-block;
          float:left;
        }
        .img{
          display: inline-block;
          float:left;
          margin-right: 8px;
        }
      }
      margin-top: 25px;
    }
    .slogan{
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #FF550A;
      margin-top:24px;
    }
    .code{
      display: inline-block;
      margin-right: 30px;
      float: right;
      width: 117px;
      height: 117px;
      background: #FFFFFF;
      box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.14);
      border-radius: 8px;
      padding: 10px 10px;
      img{
        width: 100%;
        height: 100%;
      }
    }
    .content{
      display: inline-block;
      float: left;
      .title1{
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FF550A;
      }
      .title2{
        margin-top: 18px;
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #555555;
      }
      .title3{
        width: 133px;
        height: 24px;
        background: #FF6929;
        border-radius: 3px;
        text-align: center;
        line-height: 24px;
        font-size: 14px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FFFFFF;
        margin-top: 23px;
      }
    }
  }
}
.call{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
.data_statistics{
  padding: 0px 20px;
  .statistics-box-card{
    width: 100%;
    .broken{
      margin-top: 20px;
    }
    .title{
      margin-top: 10px;
      margin-left: 15px;
      font-size: 20px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      line-height: 24px;
      .screens{
        display: inline-block;
        float:right;
      }
    }
  }
}
.seats{
  margin-bottom:24px;
  padding: 0px 20px;
  .seats-box-card{
    width: 100%;
    .title{
      margin-top: 10px;
      margin-left: 15px;
      font-size: 20px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      line-height: 24px;
      .screens{
        display: inline-block;
        float:right;
      }
    }
    .table{
      margin-top: 20px;
      ::-webkit-scrollbar {
        width: 6px; // 横向滚动条
        height: 6px; // 纵向滚动条 必写
      }
      ::v-deep .cell{
        padding-left: 30px;
      }
      ::-webkit-scrollbar-thumb {
        background-color: #c1c7d0;
        border-radius: 2px;
      }
    }
  }
}
.statistics{
  width: 98%;
  margin: 0px 20px;
  margin-bottom: 24px;
}
.text {
  font-size: 14px;
}

.item {
  padding: 18px 0;
}
.box-card-duration{
  background: url('../../assets/images/outbound/a001.png') no-repeat right;
  background-position: 98% 28px;
  width: 26%;
  height: 120px;
  margin-top: 25px;
  display: inline-block;
  border-radius: 10px;
  .information-right{
    float: right;
    margin-right: 10px;
    .btn{
      cursor: pointer;
      width: 82px;
      height: 29px;
      text-align: center;
      line-height: 29px;
      color:#FFFFFF;
      font-size: 14px;
      background: linear-gradient(212deg, #FF5A00, #FF9600);
      border-radius: 14px;
    }
  }
  .information-left{
    display: inline-block;
    .title{
      font-size: 16px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      margin-top: 4px;
      color: #333333;
    }
    span{
      color:#FF5C2A;
    }
    .top{
      margin-top:10px;
    }
    .tops{
      margin-top: 10px;
    }
    .data{
      font-size: 14px;
    }
  }
}
.box-card {
  .information-right{
    margin-top: 6px;
    float: right;
  }
  .information-left{
    display: inline-block;
    .title{
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      margin-top: 10px;
      color: #666666;
    }
    .green{
      color:#01B62F;
    }
    .red{
      color: #FF5F36;
    }
    .blue{
      color: #0A84FF;
    }
    .orange{
      color:#FC486B;
    }
    .data{
      font-size: 32px;
      margin-top: 10px;
      font-family: Microsoft YaHei;
      font-weight: bold;
    }
  }
  width: 16.8%;
  height: 120px;
  margin-top: 25px;
  margin-right: 19px;
  display: inline-block;
  border-radius: 10px;
}
.screens{
  ::v-deep .el-range__icon{
    line-height: 35px;
  }
  ::v-deep .el-input__inner{
    width: 250px;
    height: 36px;
    padding: 0px 10px;
  }
  ::v-deep .el-range-separator{
    line-height: 32px;
  }
  ::v-deep .el-range-input{
    font-size: 14px;
  }
  ::v-deep .el-range__close-icon{
    display: none;
  }
}
#animation {
  animation:pulse 1s .2s ease both infinite;
}
@-webkit-keyframes pulse {
  0% {
    -webkit-transform:scale(1)
  }
  50% {
    -webkit-transform:scale(1.1)
  }
  100% {
    -webkit-transform:scale(1)
  }
}
@-moz-keyframes pulse {
  0% {
    -moz-transform:scale(1)
  }
  50% {
    -moz-transform:scale(1.1)
  }
  100% {
    -moz-transform:scale(1)
  }
}
</style>
