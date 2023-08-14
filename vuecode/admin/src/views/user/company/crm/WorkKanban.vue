<template>
  <div class="dashboard-container">
    <el-row :gutter="20">
      <el-col :span="18">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span>最新数据</span>
            </div>
            <el-row :gutter="20">
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">40</div>
                    <div class="tit1">线索总数</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">36985</div>
                    <div class="tit1">客户总数</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">653</div>
                    <div class="tit1">新客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">653</div>
                    <div class="tit1">联系中客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">200</div>
                    <div class="tit1">成交客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">200</div>
                    <div class="tit1">流失客户</div>
                  </div>
                </el-card>
              </el-col>
            </el-row>
            <el-row :gutter="20" style="margin-top:20px;">
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">2</div>
                    <div class="tit1">今日新增线索</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">200</div>
                    <div class="tit1">今日新增客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">40</div>
                    <div class="tit1">今日跟进客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">20</div>
                    <div class="tit1">今日跟进线索</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">26</div>
                    <div class="tit1">名企会员数</div>
                  </div>
                </el-card>
              </el-col>
            </el-row>
          </el-card>
        </el-row>
      </el-col>
      <el-col :span="6">
        <el-row>
          <el-card style="padding-bottom: 18px;">
            <div slot="header" class="clearfix1" />
            <el-col :span="10">
              <el-card shadow="hover" class="no-border">
                <div class="today-info">
                  <div class="num">3</div>
                  <div class="tit1">15天内到期</div>
                </div>
              </el-card>
            </el-col>
            <el-col :span="10">
              <el-card shadow="hover" class="no-border">
                <div class="today-info">
                  <div class="num">3</div>
                  <div class="tit1">到期未续费</div>
                </div>
              </el-card>
            </el-col>
            <el-col :span="10">
              <el-card shadow="hover" class="no-border" style="margin-top: 20px;">
                <div class="today-info">
                  <div class="num">29</div>
                  <div class="tit1">30天未跟进客户</div>
                </div>
              </el-card>
            </el-col>
            <el-col :span="10">
              <el-card shadow="hover" class="no-border" style="margin-top: 20px;">
                <div class="today-info">
                  <div class="num">12</div>
                  <div class="tit1">从未跟进客户数</div>
                </div>
              </el-card>
            </el-col>
          </el-card>
        </el-row>
      </el-col>
    </el-row>
    <el-row :gutter="24" style="margin-top: 30px;">
      <el-col :span="24">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span>销售一览表</span>
            </div>
            <el-table
              :data="tableData"
              style="width: 100%"
              height="250"
            >
              <el-table-column
                fixed
                prop="date"
                label="ID"
              />
              <el-table-column
                prop="name"
                label="员工账号"
              />
              <el-table-column
                prop="province"
                label="姓名"
              />
              <el-table-column
                prop="city"
                label="线索总数"
              />
              <el-table-column
                prop="address"
                label="客户总数"
              />
              <el-table-column
                prop="zip"
                label="今日新增客户"
              />
              <el-table-column
                prop="zip"
                label="今日新增线索"
              />
              <el-table-column
                prop="zip"
                label="联系中"
              />
              <el-table-column
                prop="zip"
                label="15天内到期"
              />
              <el-table-column
                prop="zip"
                label="已到期"
              />
              <el-table-column
                prop="zip"
                label="新客户"
              />
              <el-table-column
                prop="zip"
                label="联系中"
              />
              <el-table-column
                prop="zip"
                label="成交客户"
              />
              <el-table-column
                prop="zip"
                label="流失客户"
              />
              <el-table-column
                prop="zip"
                label="昨日跟进客户"
              />
              <el-table-column
                prop="zip"
                label="7天内跟进"
              />
              <el-table-column
                prop="zip"
                label="未发职位企业"
              />

            </el-table>
          </el-card>
        </el-row>
      </el-col>
    </el-row>
    <el-row :gutter="24" style="margin-top: 30px;">
      <el-col :span="24">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span>跟进一览表</span>
            </div>
          </el-card>
        </el-row>
      </el-col>
    </el-row>
    <el-row :gutter="24" style="margin-top: 30px;">
      <el-col :span="24">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span>客户数据分析</span>
            </div>
            <div id="reg_line" style="height: 300px;" />
          </el-card>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import echarts from 'echarts'
import { dashboardChart } from '@/api/dashboard'

export default {
  name: 'WorkKanban',
  data(){
    return {
      tongji: {
        new_customer_top: '',
        perm_top: ''
      },
      tableData: [],
      charts: ''
    }
  },
  created() {
    this.fetchData()
  },
  mounted() {
    this.$nextTick(function() {
      this.drawChart('reg_line')
    })
  },
  methods: {
    fetchData() {
      const params = {
        type: '0'
      }
      dashboardChart(params).then(response => {
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            data: response.data.xAxis
          },
          series: [
            {
              name: '个人数',
              type: 'line',
              data: response.data.series[0]
            },
            {
              name: '企业数',
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
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['个人数', '企业数']
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
            name: '个人数',
            type: 'line',
            data: [],
            lineStyle: {
              color: '#3aa1ff'
            },
            itemStyle: {
              color: '#3aa1ff'
            }
          }
        ]
      })
    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep .el-card__header{
  border: none;
}
.clearfix1{
  height: 20px;
}
.dashboard {
  &-container {
    margin: 20px;
  }
  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}
.no-border{
  border:0;
}
.today-info{
  cursor:pointer;
  .tit1{
    font-size:14px;
    color:#999;
    margin-top: 10px;
  }
  .num{
    font-size:24px;
    font-weight: bold;
    color:#222;
    margin-top:6px;
  }
  .tit2{
    font-size:12px;
    color:#999;
    margin-top:6px;
  }
}
.fright{
  float:right;
  position: absolute;
  right: 20px;
  top: 10px;
}
.fright2{
  float:right;
  margin-right:20px;
}
.transport1 .el-input__inner{
  border:0px solid #fff !important;
}
.bigBtn{
  width:100%;
  height:58px;
  color: #fff;
}
.bigBtn:hover{
  color: #fff;
}
.new_customer_index_1{
  color: #ff3c3c;
}
.new_customer_index_2{
  color: #ff7800;
}
.new_customer_index_3{
  color: #1cabcf;
}
.new_customer_index_4{
  color: #857d7d;
}
.btn_color_1 {
  background-image: linear-gradient(to right, #ff8760,#fe68aa);
}
.btn_color_2 {
  background-image: linear-gradient(to right, #1dbaf0,#4c92fe);
}
.btn_color_3 {
  background-image: linear-gradient(to right, #35d5c4,#139cd2);
}
.btn_color_4 {
  background-image: linear-gradient(to right, #6187ff,#8c48e8);
}
</style>
