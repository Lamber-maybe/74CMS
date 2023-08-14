<template>
  <div>
    <Head :show-refresh="true" :show-back="false" :show-admin="true" @dorefresh="fetchData">{{globalConfig.sitename}}</Head>
    <van-overlay z-index="10" :show="fetchDataFinish===false"><van-loading color="#1989fa" class="loading" >数据加载中...</van-loading></van-overlay>
    <div class="b1">
      <div class="public_item_title">
        <p class="title">今日信息统计</p>
        <p class="link" @click="$router.push('/user')">会员管理<van-icon class="absolute_for_y" name="arrow" size="14px" color="#999999" /></p>
      </div>
      <div class="b_content">
        <div class="bc_line">
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.reg_personal_today>today_data.reg_personal_yesterday?'add':(today_data.reg_personal_today<today_data.reg_personal_yesterday?'cut':'flat')">{{today_data.reg_personal_today}}</p></div><p class="t2">昨日：{{today_data.reg_personal_yesterday}}</p><p class="t3">新增个人</p>
          </div>
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.resume_add_today>today_data.resume_add_yesterday?'add':(today_data.resume_add_today<today_data.resume_add_yesterday?'cut':'flat')">{{today_data.resume_add_today}}</p></div><p class="t2">昨日：{{today_data.resume_add_yesterday}}</p><p class="t3">新增简历</p>
          </div>
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.resume_refresh_today>today_data.resume_refresh_yesterday?'add':(today_data.resume_refresh_today<today_data.resume_refresh_yesterday?'cut':'flat')">{{today_data.resume_refresh_today}}</p></div><p class="t2">昨日：{{today_data.resume_refresh_yesterday}}</p><p class="t3">简历刷新</p>
          </div>
        </div>
        <div class="bc_line">
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.reg_company_today>today_data.reg_company_yesterday?'add':(today_data.reg_company_today<today_data.reg_company_yesterday?'cut':'flat')">{{today_data.reg_company_today}}</p></div><p class="t2">昨日：{{today_data.reg_company_yesterday}}</p><p class="t3">新增企业</p>
          </div>
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.job_add_today>today_data.job_add_yesterday?'add':(today_data.job_add_today<today_data.job_add_yesterday?'cut':'flat')">{{today_data.job_add_today}}</p></div><p class="t2">昨日：{{today_data.job_add_yesterday}}</p><p class="t3">新增职位</p>
          </div>
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.job_refresh_today>today_data.job_refresh_yesterday?'add':(today_data.job_refresh_today<today_data.job_refresh_yesterday?'cut':'flat')">{{today_data.job_refresh_today}}</p></div><p class="t2">昨日：{{today_data.job_refresh_yesterday}}</p><p class="t3">职位刷新</p>
          </div>
        </div>
        <div class="bc_line">
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.job_apply_today>today_data.job_apply_yesterday?'add':(today_data.job_apply_today<today_data.job_apply_yesterday?'cut':'flat')">{{today_data.job_apply_today}}</p></div><p class="t2">昨日：{{today_data.job_apply_yesterday}}</p><p class="t3">简历投递</p>
          </div>
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.down_resume_today>today_data.down_resume_yesterday?'add':(today_data.down_resume_today<today_data.down_resume_yesterday?'cut':'flat')">{{today_data.down_resume_today}}</p></div><p class="t2">昨日：{{today_data.down_resume_yesterday}}</p><p class="t3">下载简历</p>
          </div>
          <div class="bc_cell">
            <div><p class="t1" :class="today_data.orderpay_today>today_data.orderpay_yesterday?'add':(today_data.orderpay_today<today_data.orderpay_yesterday?'cut':'flat')">{{today_data.orderpay_today}}</p></div><p class="t2">昨日：{{today_data.orderpay_yesterday}}</p><p class="t3">完成订单</p>
          </div>
        </div>
      </div>
    </div>
    <div class="b2">
      <div class="public_item_title">
        <p class="title">今日待办</p>
        <p class="link">* 统计全部待完成事项</p>
      </div>
      <div class="b_content">
        <div class="bc_line">
          <div class="bc_cell" @click="$router.push('/company_list/0')">
            <p class="t1">{{pending_data[0]===undefined?0:pending_data[0].num}}</p><p class="t2">{{pending_data[0]===undefined?'':pending_data[0].title}}</p>
          </div>
          <div class="bc_cell" @click="$router.push('/job_list/0')">
            <p class="t1">{{pending_data[1]===undefined?0:pending_data[1].num}}</p><p class="t2">{{pending_data[1]===undefined?'':pending_data[1].title}}</p>
          </div>
          <div class="bc_cell" @click="$router.push('/resume_list/0')">
            <p class="t1">{{pending_data[2]===undefined?0:pending_data[2].num}}</p><p class="t2">{{pending_data[2]===undefined?'':pending_data[2].title}}</p>
          </div>
        </div>
        <div class="bc_line">
          <div class="bc_cell" @click="$router.push('/cancellation')">
            <p class="t1">{{pending_data[3]===undefined?0:pending_data[3].num}}</p><p class="t2">{{pending_data[3]===undefined?'':pending_data[3].title}}</p>
          </div>
          <div class="bc_cell" @click="$router.push('/tipoff')">
            <p class="t1">{{pending_data[4]===undefined?0:pending_data[4].num}}</p><p class="t2">{{pending_data[4]===undefined?'':pending_data[4].title}}</p>
          </div>
          <div class="bc_cell" @click="$router.push('/feedback')">
            <p class="t1">{{pending_data[5]===undefined?0:pending_data[5].num}}</p><p class="t2">{{pending_data[5]===undefined?'':pending_data[5].title}}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="b2">
      <div class="public_item_title">
        <p class="title">今日收入</p>
      </div>
      <div class="b_content">
        <div class="bc_line">
          <div class="bc_cell">
            <p class="t1 c_3">{{income_data.personalTodayNewOrderNum}}</p><p class="t2">个人新增订单</p>
          </div>
          <div class="bc_cell">
            <p class="t1 c_3">{{income_data.personalTodayPayOrderNum}}</p><p class="t2">个人完成订单</p>
          </div>
          <div class="bc_cell">
            <p class="t1 c_3">{{income_data.personalTodayPayOrderAmount}}</p><p class="t2">实收金额合计</p>
          </div>
        </div>
        <div class="bc_line">
          <div class="bc_cell">
            <p class="t1 c_3">{{income_data.companyTodayNewOrderNum}}</p><p class="t2">企业新增订单</p>
          </div>
          <div class="bc_cell">
            <p class="t1 c_3">{{income_data.companyTodayPayOrderNum}}</p><p class="t2">企业完成订单</p>
          </div>
          <div class="bc_cell">
            <p class="t1 c_3">{{income_data.companyTodayPayOrderAmount}}</p><p class="t2">实收金额合计</p>
          </div>
        </div>
      </div>
    </div>
    <div class="b3">
      <div class="public_item_title">
        <p class="title">个人/企业注册趋势</p>
        <p class="link ic">近7天</p>
      </div>
      <div class="b_content">
        <chartComponents v-if="reg_line!==null" :dataset="reg_line"></chartComponents>
      </div>
    </div>
    <div class="b3">
      <div class="public_item_title">
        <p class="title">下载/投递简历</p>
        <p class="link ic">近7天</p>
      </div>
      <div class="b_content">
        <chartComponents v-if="down_apply_line!==null" :dataset="down_apply_line"></chartComponents>
      </div>
    </div>
    <BottomNav></BottomNav>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import chartComponents from '@/components/Charts/Line'
  export default {
    name: "Index",
    components:{
      chartComponents
    },
    data () {
      return {
        fetchDataFinish:false,
        globalConfig:{},
        today_data:{},
        income_data:{},
        pending_data:[],
        down_apply_line:null,
        reg_line:null
      }
    },
    created () {
      this.config()
      this.fetchData()
    },
    methods:{
      config () {
        let that = this
        http
          .get(api.config, {})
          .then(res => {
            that.globalConfig = res.data
          })
          .catch(() => {})
      },
      fetchData () {
        let that = this
        that.fetchDataFinish = false
        setTimeout(() => {
            that.fetchDataFinish = true
        }, 500);
        http
          .get(api.dashboard, {})
          .then(res => {
            const {today_data,income_data,down_apply_line,pending_data,reg_line} = {...res.data}
            that.today_data = today_data
            that.income_data = income_data
            that.pending_data = pending_data
            that.reg_line = reg_line
            that.down_apply_line = down_apply_line

          })
          .catch(() => {})
      },
    }
  }
</script>

<style lang="scss" scoped>
.loading{
  text-align: center;
  top: 50%;
}
.van-overlay{
  background-color:rgba(255, 255, 255, 0.9)
}
  .b3 {
    width: 100%;background-color: #ffffff;margin-top: 20px;
    .b_content {
      width: 100%;height: 500px;
      .my_chart {
        width: 100%;height: 500px;
      }
    }
  }
  .b2 {
    width: 100%;background-color: #ffffff;margin-top: 20px;
    .b_content {
      padding: 0 36px 40px 36px;
      .bc_line {
        display: flex;border-top: 1PX solid #f2f2f2;
        &:nth-of-type(1) { border-top: 0; }
        .bc_cell {
          flex: 1;border-right: 1PX solid #f2f2f2;text-align: center;padding: 20px 0 30px 0;
          .t1 {
            font-size: 48px;color: #ff6600;font-weight: bold;margin-bottom: 10px;
            &.c_3 { color: #333333; }
          }
          .t2 { position: relative;font-size: 28px;color: #333333; }
          &:nth-of-type(3n) { border-right:0; }
        }
      }
    }
  }
  .b1 {
    width: 100%;background-color: #ffffff;margin-top: 20px;
    .b_content {
      padding: 0 36px 40px 36px;
      .bc_line {
        display: flex;border-top: 1PX solid #f2f2f2;
        &:nth-of-type(1) { border-top: 0; }
        .bc_cell {
          flex: 1;border-right: 1PX solid #f2f2f2;text-align: center;padding: 20px 0 30px 0;
          .t1 {
            position: relative;font-size: 48px;color: #333333;font-weight: bold;display: inline-block;
            &.add {
              &::after {
                content: '';position: absolute;right: -30px;bottom: 15px;width: 18px;height: 20px;
                background: url("../assets/images/1.png") 0 no-repeat;background-size: 18px 20px;
              }
            }
            &.cut {
              &::after {
                content: '';position: absolute;right: -30px;bottom: 15px;width: 18px;height: 20px;
                background: url("../assets/images/2.png") 0 no-repeat;background-size: 18px 20px;
              }
            }
            &.flat {
              &::after {
                content: '';position: absolute;right: -30px;bottom: 20px;width: 17px;height: 8px;
                background: url("../assets/images/3.png") 0 no-repeat;background-size: 17px 8px;
              }
            }
          }
          .t2 { position: relative;font-size: 26px;color: #999999; margin: 5px 0 10px; }
          .t3 { position: relative;font-size: 28px;color: #333333; }
          &:nth-of-type(3n) { border-right:0; }
        }
      }
    }
  }
  .public_item_title {
    width: 100%;height: 120px;line-height: 120px;position: relative;padding-left: 28px;
    .title { font-size: 32px;height: 120px;line-height: 120px;color: #333333;font-weight: bold; }
    .link {
      position: absolute;right: 0;top: 0;height: 120px;line-height: 120px;font-size: 28px;color: #999999;
      padding-right: 53px;
      .van-icon { position: absolute;right: 23px;top: 50%; }
      &.ic {
        padding-left: 40px;background: url("../assets/images/5.png") 0 center no-repeat;
        background-size: 32px;
      }
    }
  }
</style>
