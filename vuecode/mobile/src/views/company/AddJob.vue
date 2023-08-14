<template>
  <div id="app">
    <Head>发布职位</Head>

    <JobForm
      ref="child"
      v-if="showResult === false"
      type="add"
      :enable_addjob_num="enable_addjob_num"
      @submit="submit"
    ></JobForm>

    <div v-if="showResult === true">
      <div class="box_5">
        <div class="tx1">职位发布成功</div>
        <div class="tx2">推荐使用职位置顶，立即提升招聘效果</div>
        <div class="btn_box">
          <div class="btn_item blue" @click="addAgain">再发一条</div>
          <div class="btn_item" @click="$router.push('/member/company/index')">
            会员中心
          </div>
          <div
            class="btn_item"
            @click="$router.push('/member/company/joblist')"
          >
            管理职位
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="form_split_10" v-if="result_audit == 1"></div>
      <div class="box_6" v-if="result_audit == 1">
        <div class="head_txt">职位置顶<span>即刻提高18倍曝光量</span></div>
        <div
          class="item"
          v-for="(item, index) in result_service_list"
          :key="index"
        >
          {{ item.name }}
          <div class="price"><span>￥</span>{{ item.expense }}</div>
          <van-button
            round
            type="info"
            size="small"
            @click="
              $router.push(
                '/member/order/add/common?type=service&service_type=jobstick&jobid=' +
                  result_id
              )
            "
            >购买</van-button
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import JobForm from '@/components/JobForm'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AddJob',
  components: {
    JobForm
  },
  data () {
    return {
      showResult: false,
      result_id: 0,
      result_audit: 0,
      result_service_list: [],
      enable_addjob_num: 0
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    addAgain () {
      this.fetchData()
      this.showResult = !this.showResult
    },
    fetchData () {
      http
        .get(api.company_check_jobadd_num, {})
        .then(res => {
          this.enable_addjob_num = res.data.enable_addjob_num
        })
        .catch(() => {})
      http
        .get(api.company_jobadd_pre, {})
        .then(res => {
          this.$refs.child.field_rule = res.data.field_rule
        })
        .catch(() => {})
    },
    submit (values) {
      http
        .post(api.company_jobadd_save, values)
        .then(res => {
          this.result_id = res.data.id
          this.result_audit = [...res.data.audit]
          this.result_service_list = [...res.data.service_stick_list]
          this.showResult = true
        })
        .catch(() => {
          this.$refs.child.resetSubmit()
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_6 {
  .item {
    .van-button {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
    }
    .price {
      color: #ff6600;
      font-size: 15px;
      font-weight: bold;
      position: absolute;
      right: 106px;
      top: 50%;
      transform: translate(0, -50%);
      span {
        font-size: 9px;
      }
    }
    position: relative;
    width: 100%;
    padding: 20.5px 170px 20.5px 10px;
    font-size: 15px;
    color: #333333;
    font-weight: bold;
    border: 1px solid #e2e2e2;
    border-radius: 7px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    &:not(:last-child) {
      margin-bottom: 15px;
    }
  }
  .head_txt {
    span {
      font-size: 12px;
      color: #c9c9c9;
      margin-left: 12px;
    }
    font-size: 18px;
    color: #333333;
    font-weight: bold;
    padding: 27.5px 0 17.5px;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;
}
.box_5 {
  .btn_box {
    .btn_item {
      float: left;
      font-size: 14px;
      color: #1787fb;
      border: 1px solid #1787fb;
      padding: 5px 18px;
      background-color: #fff;
      border-radius: 30px;
      &.blue {
        background-color: #1787fb;
        color: #fff;
      }
      &:not(:first-child) {
        margin-left: 15px;
      }
    }
    margin: 0 auto;
    width: 313px;
    padding-bottom: 42px;
  }
  .tx2 {
    padding: 17.5px 0 32.5px;
    font-size: 15px;
    color: #999999;
  }
  .tx1 {
    font-size: 24px;
    color: #333333;
    display: inline-block;
    padding: 6.5px 0 6.5px 53px;
    background: url("../../assets/images/success_green.svg") 0 center no-repeat;
    background-size: 40px;
  }
  width: 100%;
  background-color: #ffffff;
  padding-top: 60px;
  text-align: center;
}
</style>
