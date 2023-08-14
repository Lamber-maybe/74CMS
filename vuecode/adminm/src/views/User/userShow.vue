<template>
  <div class="body">
    <Head>{{ title }}</Head>
    <van-skeleton title :row="3" v-if="info.info.username===undefined" />
    <van-cell-group v-else>
      <van-cell>
        <template #title>
          <span class="cus_title">用户名：</span><span class="cus_content">{{info.info.username}}</span>
          <div class="cus_btn absolute_for_y" @click="openResetUsername">修改</div>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">会员密码：</span><span class="cus_content">******</span>
          <div class="cus_btn absolute_for_y" @click="openResetPassword">修改</div>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">手机号：</span><span class="cus_content">{{info.info.mobile}}</span>
          <div class="cus_btn absolute_for_y" @click="openResetMobile">修改</div>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">UID：</span><span class="cus_content">{{info.info.uid}}</span>
        </template>
      </van-cell>
      <van-cell class="substring" v-if="type === 1">
        <template #title>
          <span class="cus_title">企业名称：</span><span class="cus_content">{{info.company===null?'未知企业名称':info.company.companyname}}</span>
        </template>
      </van-cell>
      <van-cell class="substring" v-if="type === 2">
        <template #title>
          <span class="cus_title">简历：</span><span class="cus_content">{{info.resume===null?'未知姓名':info.resume.fullname}} (完整度：{{info.resume===null?'0':info.resume.complete_percent}}%)</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">最近登录：</span><span class="cus_content">{{info.info.last_login_time|timeFilter}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">注册时间：</span><span class="cus_content">{{info.info.reg_time|timeFilter}} ({{info.info.platform_cn}})</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">账号状态：</span><span class="cus_content">{{info.info.status==1?'正常':'已锁定'}}</span>
          <div class="cus_btn absolute_for_y" @click="handleLock">{{info.info.status==1?'锁定':'解锁'}}</div>
        </template>
      </van-cell>
    </van-cell-group>
    <van-popup
        :lazy-render="false"
        v-model="showResetUsername"
        position="right"
        :overlay="false"
        style="width:100%;height:100%"
    >
      <reset-user-name @closePopout="closeResetUsername" @onSubmit="saveUsername"></reset-user-name>
    </van-popup>
    <van-popup
        :lazy-render="false"
        v-model="showResetPassword"
        position="right"
        :overlay="false"
        style="width:100%;height:100%"
    >
      <reset-password @closePopout="closeResetPassword" @onSubmit="savePassword" :nocheck="true"></reset-password>
    </van-popup>
    <van-popup
        :lazy-render="false"
        v-model="showResetMobile"
        position="right"
        :overlay="false"
        style="width:100%;height:100%"
    >
      <reset-mobile @closePopout="closeResetMobile" @onSubmit="saveMobile"></reset-mobile>
    </van-popup>
    <div class="bottom clear">
      <div class="l_btn" @click="toInfo">{{ type === 1 ? '查看企业资料' : '查看简历信息' }}</div>
      <div class="r_btn" @click="deleteInfo">删除</div>
    </div>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import resetUserName from '../../components/config/resetUserName'
  import resetPassword from '../../components/config/resetPassword'
  import resetMobile from '../../components/config/resetMobile'
  import { formatTime } from '@/utils/index'
  export default {
    name: "userShow",
    components: {
      resetUserName,
      resetPassword,
      resetMobile
    },
    filters: {
      timeFilter (timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    data () {
      return {
        info:{
          info:{},
          company:null,
          resume:null
        },
        title: '',
        uid:'',
        type: 1, // 1->企业会员 2->个人会员
        showResetUsername: false,
        showResetPassword: false,
        showResetMobile: false
      }
    },
    created() {
      this.type = parseInt(this.$route.params.type)
      this.uid = parseInt(this.$route.params.uid)
      this.title = this.type === 1 ? '企业会员详情' : '个人会员详情'
      this.fetchData()
    },
    methods: {
      fetchData () {
        http
          .get(api.memberDetail, {uid:this.uid})
          .then(res => {
            this.info = res.data
          })
          .catch(() => {})
      },
      saveUsername (data) {
        let that = this
        data.uid = that.uid
        http
          .post(api.memberUsername, data)
          .then(res => {
            that.$toast.success(res.message)
            that.closeResetUsername(data.username)
          })
          .catch(() => {})
      },
      savePassword (data) {
        let that = this
        data.uid = that.uid
        http
          .post(api.memberPassword, data)
          .then(res => {
            that.$toast.success(res.message)
            that.closeResetPassword()
          })
          .catch(() => {})
      },
      saveMobile (data) {
        let that = this
        data.uid = that.uid
        http
          .post(api.memberMobile, data)
          .then(res => {
            that.$toast.success(res.message)
            that.closeResetMobile(data.mobile)
          })
          .catch(() => {})
      },
      openResetUsername () {
        this.showResetUsername = true
      },
      closeResetUsername (username) {
        this.showResetUsername = false
        if(username!==undefined){
          this.info.info.username = username
        }
      },
      openResetPassword () {
        this.showResetPassword = true
      },
      closeResetPassword () {
        this.showResetPassword = false
      },
      openResetMobile () {
        this.showResetMobile = true
      },
      closeResetMobile (mobile) {
        this.showResetMobile = false
        if(mobile!==undefined){
          this.info.info.mobile = mobile
        }
      },
      handleLock () {
        let status = this.info.info.status
        let message = status==1?'当前账号是开启状态，锁定账号后将不能登录网站，是否确认锁定？':'当前账号是锁定状态，是否确认解除锁定？'
        let data = {}
        data.uid = this.uid
        data.status = status==1?0:1
        this.$dialog
          .confirm({
            title: '系统提示',
            message: message
          })
          .then(() => {
            http
            .post(api.memberLock, data)
            .then(res => {
              this.$toast.success(res.message)
              this.info.info.status = data.status
            })
            .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      toInfo () {
        if(this.info.company!==null){
          location.href = this.info.company.mobile_link
        }else{
          location.href = this.info.resume.mobile_link
        }
      },
      deleteInfo () {
        this.$dialog
          .confirm({
            title: '系统提示',
            message: '此操作将永久删除该会员, 是否继续?'
          })
          .then(() => {
            http
            .post(api.memberDelete, {uid:[this.uid]})
            .then(res => {
              this.$toast.success(res.message)
              this.$router.go(-1)
            })
            .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      }
    }
  }
</script>

<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .cus_title { font-size: 28px;color: #999999;width: 155px;display: inline-block; }
  .cus_content { font-size: 28px;color: #333333; }
  .van-hairline--top-bottom::after, .van-hairline-unset--top-bottom::after { border-top: 0; }
  .bottom {
    position: absolute;left: 0;bottom: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px;
    .l_btn {
      float: left;width: 490px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #1787fb;
    }
    .r_btn {
      float: right;width: 180px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #f56c6c;
    }
  }
  .cus_btn {
    position: absolute;right: 35px;top: 50%;width: 120px;border: 1PX solid #1787fb;font-size: 24px;
    color: #1787fb;border-radius: 999px;text-align: center;padding: 12px 34px;
  }
</style>
