<template>
  <div>
    <Head :show-back="false" :show-admin="true">数据统计</Head>
    <div class="public_item_title">网站数据总览</div>
    <div class="b1">
      <div class="bc_line">
        <div class="bc_cell"><p class="t1">{{dataset.company}}</p><p class="t2">入驻企业</p></div>
        <div class="bc_cell"><p class="t1">{{dataset.job}}</p><p class="t2">招聘岗位</p></div>
      </div>
      <div class="bc_line">
        <div class="bc_cell"><p class="t1">{{dataset.job_amount}}</p><p class="t2">岗位需求</p></div>
        <div class="bc_cell"><p class="t1">{{dataset.resume}}</p><p class="t2">求职者</p></div>
      </div>
    </div>
    <div class="b3">
      <div class="public_chart_title">
        <p class="title">注册量趋势</p>
        <p class="link ic">近7天</p>
      </div>
      <div class="b_content">
        <chartComponents v-if="dataset.reg_line!==undefined" :dataset="dataset.reg_line"></chartComponents>
      </div>
    </div>
    <div class="b3">
      <div class="public_chart_title">
        <p class="title">活跃度分析</p>
        <p class="link ic">近7天</p>
      </div>
      <div class="b_content">
        <chartComponents v-if="dataset.active_line!==undefined" :dataset="dataset.active_line"></chartComponents>
      </div>
    </div>
    <div class="b3">
      <div class="public_chart_title">
        <p class="title">企业新开套餐趋势分析</p>
        <p class="link ic">近7天</p>
      </div>
      <div class="b_content">
        <chartComponents v-if="dataset.setmeal_line!==undefined" :dataset="dataset.setmeal_line"></chartComponents>
      </div>
    </div>
    <div class="b_table">
      <div class="public_chart_title">
        <p class="title">企业套餐会员分布</p>
      </div>
      <div class="b_content">
        <div class="set_line">
          <div class="set_cell">套餐类型</div>
          <div class="set_cell">当前套餐数</div>
          <div class="set_cell">已过期</div>
        </div>
        <div class="set_line" v-for="(item,index) in dataset.setmeal_table" :key="index">
          <div class="set_cell">{{item.setmeal_name}}</div>
          <div class="set_cell">{{item.company_num}}</div>
          <div class="set_cell">{{item.overtime_num}}</div>
        </div>
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
    name: "statsIndex",
    components:{
      chartComponents
    },
    data () {
      return {
        dataset:{}
      }
    },
    created () {
      this.fetchData()
    },
    methods:{
      fetchData () {
        let that = this
        http
          .get(api.stat, {})
          .then(res => {
            that.dataset = res.data
          })
          .catch(() => {})
      },
    }
  }
</script>

<style lang="scss" scoped>
  .b_table {
    width: 100%;background-color: #ffffff;margin-top: 20px;
    .b_content {
      padding: 0 35px 35px 35px;
      .set_line {
        display: flex;
        &:nth-of-type(1) { border-top: 1PX solid #eff2f7; }
        .set_cell {
          flex: 1;padding: 15px 15px;font-size: 24px;color: #666666;
          border: 1PX solid #eff2f7;border-top: 0;
          &:nth-of-type(2n), &:nth-of-type(3n) { border-left: 0; }
        }
      }
    }
  }
  .b1 {
    width: 100%;background-color: #ffffff;
    .bc_line {
      display: flex;border-top: 1PX solid #f2f2f2;
      &:nth-of-type(1) { border-top: 0; }
      .bc_cell {
        flex: 1;border-right: 1PX solid #f2f2f2;height: 210px;padding: 50px 0 0 53px;
        .t1 { font-size: 58px;color: #3088ff;font-weight: bold;margin-bottom: 5px; }
        .t2 { font-size: 28px;color: #999999; }
        &:nth-of-type(2n) { border-right:0; }
      }
    }
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
  .public_item_title {
    width: 100%;height: 70px;line-height: 70px;padding-left: 28px;background-color: #f3f3f3;font-size: 26px;
    color: #999999;
  }
  .public_chart_title {
    width: 100%;height: 120px;line-height: 120px;position: relative;padding-left: 28px;
    .title { font-size: 32px;height: 120px;line-height: 120px;color: #333333;font-weight: bold; }
    .link {
      position: absolute;right: 0;top: 0;height: 120px;line-height: 120px;font-size: 28px;color: #999999;
      padding-right: 53px;
      .van-icon { position: absolute;right: 23px;top: 50%; }
      &.ic {
        padding-left: 40px;background: url("../../assets/images/5.png") 0 center no-repeat;
        background-size: 32px;
      }
    }
  }
</style>
