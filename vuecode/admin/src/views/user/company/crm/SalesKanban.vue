<template>
  <div class="dashboard-container">
    <el-row :gutter="20">
      <el-col :span="24">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span style="font-size: 18px;font-weight: bold">【刘伟】的销售数据表</span>
              <span style="margin-left: 20px;">累计 2022-07-01 ~ 2022-07-14</span>
            </div>
            <el-row :gutter="20">
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">16</div>
                    <div class="tit1">累计订单</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">8100</div>
                    <div class="tit1">累计金额</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">6</div>
                    <div class="tit1">新开通套餐客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">6500</div>
                    <div class="tit1">新开通套餐金额</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">3</div>
                    <div class="tit1">续费客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">1600</div>
                    <div class="tit1">续费金额</div>
                  </div>
                </el-card>
              </el-col>
            </el-row>
          </el-card>
        </el-row>
      </el-col>
      <el-col :span="24" style="margin-top: 30px;">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span style="font-size: 18px;font-weight: bold">【刘伟】的客户等级</span>
            </div>
            <el-row :gutter="20">
              <el-col :span="3">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">15</div>
                    <div class="tit1">新客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="3">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">3</div>
                    <div class="tit1">生命周期2</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="3">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">3</div>
                    <div class="tit1">活跃有意向</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="3">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">3</div>
                    <div class="tit1">试用期</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="3">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">3</div>
                    <div class="tit1">成交客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="3">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">3</div>
                    <div class="tit1">流失客户</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="3">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info">
                    <div class="num">3</div>
                    <div class="tit1">到期未续费</div>
                  </div>
                </el-card>
              </el-col>
            </el-row>
          </el-card>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: "SalesKanban"
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
