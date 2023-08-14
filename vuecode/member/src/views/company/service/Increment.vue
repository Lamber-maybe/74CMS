<template>
  <el-card>
    <company-title>增值服务</company-title>
    <div class="increment_wrapper">
        <div class="increment_list">
          <div class="increment_list_top">
            <img src="../../../assets/images/personal/increment_icon1.png" />
            <p>简历增值包</p>
          </div>
          <div class="increment_list_bottom">
            <p>套餐简历点不够用？快来试试简历增值包，简历点打包购买更超值，指定套餐会员还可畅享专享折扣！</p>
            <div class="increment_list_btn">
              <el-button type="primary" @click="$router.push('/company/service/increment/add/resume_package')">立即购买</el-button>
            </div>
          </div>
        </div>
        <div class="increment_list">
          <div class="increment_list_top">
            <img src="../../../assets/images/personal/increment_icon2.png" />
            <p>职位置顶</p>
          </div>
          <div class="increment_list_bottom">
            <p>职位曝光量太少？开启职位置顶持续锁定C位，职位曝光量提升10倍，专享优先推荐特权，指定套餐会员还可畅享专享折扣！</p>
            <div class="increment_list_btn">
              <el-button type="primary" @click="$router.push('/company/service/increment/add/jobstick')">立即购买</el-button>
            </div>
          </div>
        </div>
        <div class="increment_list">
          <div class="increment_list_top">
            <img src="../../../assets/images/personal/increment_icon3.png" />
            <p>职位紧急</p>
          </div>
          <div class="increment_list_bottom">
            <p>想要快速招聘？开通职位紧急，专享优先推荐特权，专享紧急招聘图标显示，指定套餐会员还可畅享专享折扣！</p>
            <div class="increment_list_btn">
              <el-button type="primary" @click="$router.push('/company/service/increment/add/emergency')">立即购买 </el-button>
            </div>
          </div>
        </div>
        <div class="increment_list">
          <div class="increment_list_top">
            <img src="../../../assets/images/personal/increment_icon4.png" />
            <p>智能刷新</p>
          </div>
          <div class="increment_list_bottom">
            <p>职位能自动刷新？开通智能刷新，无需人工值守，系统自动定时刷新，指定套餐会员还可畅享专享折扣！</p>
            <div class="increment_list_btn">
              <el-button type="primary" @click="$router.push('/company/service/increment/add/job_refresh')">立即购买 </el-button>
            </div>
          </div>
        </div>
    </div>
    <p class="alert_text">* 增值优惠折扣以页面显示为准；购买过程中遇到任何问题请联系您的专享客服。</p>
  </el-card>
</template>
<script>
  export default{
    data(){
      return{}
    }
  }
</script>
<style lang="scss" scoped>
.increment_wrapper{
  display: flex;
}
.increment_list{
  flex: 1;
  height: 430px;
  background: #fafafa;
  border: 1px solid #eeeeee;
  margin-right: 20px;
  transition: linear all .2s;
  cursor: pointer;
  &:hover {
    -webkit-transform:translateY(-5px);transform:translateY(-5px);z-index:1;
  }
}
.increment_list_top{
  height: 210px;
  background: #fff;
  text-align: center;
}
.increment_list_top img{
  margin-top: 33px;
  margin-bottom: 14px;
}
.increment_list_top p{
  color:#333 ;
  font-size: 16px;
}
.increment_list_bottom{
  padding: 30px  24px 0 24px;
  font-size:13px ;
  color: #666666;
  line-height:30px;

}
.increment_list_btn{
  text-align: center;
  margin-top: 25px;
}

.increment_list_btn .el-button{
  width: 160px;
  height: 38px;
}
.alert_text{
  margin-top: 48px;
  font-size: 12px;
  color: #999;
}
</style>
