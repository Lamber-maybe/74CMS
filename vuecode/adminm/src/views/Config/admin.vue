<template>
  <div class="body">
    <Head>账号信息</Head>
    <van-skeleton title :row="3" v-if="info.username===undefined" />
    <van-cell-group v-else>
      <van-cell>
        <template #title>
          <span class="cus_title">用户名：</span><span class="cus_content">{{info.username}}</span>
          <van-button round plain type="info" class="cus_btn absolute_for_y" @click="openResetUsername">修改</van-button>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">所属角色：</span><span class="cus_content">{{info.role_name}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">创建时间：</span><span class="cus_content">{{info.addtime|timeFilter}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">最近登录：</span><span class="cus_content">{{info.last_login_time|timeFilter}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">登录IP：</span><span class="cus_content">{{info.last_login_ip}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">最近登录：</span><span class="cus_content">{{info.last_login_ipaddress}}</span>
        </template>
      </van-cell>
      <van-cell>
        <template #title>
          <span class="cus_title">登录密码：</span><span class="cus_content">******</span>
          <van-button round plain type="info" class="cus_btn absolute_for_y" @click="openResetPassword">修改</van-button>
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
      <reset-password @closePopout="closeResetPassword" @onSubmit="savePassword"></reset-password>
    </van-popup>
    <div class="bottom"><van-button type="primary" block color="#1787fb" @click="logout">退出登录</van-button></div>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api' 
  import { formatTime } from '@/utils/index'
  import resetUserName from '@/components/config/resetUserName'
  import resetPassword from '@/components/config/resetPassword'
  export default {
    name: "configAdmin",
    components: {
      resetUserName,
      resetPassword
    },
    filters: {
      timeFilter (timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    data () {
      return {
        showResetUsername: false,
        showResetPassword: false,
        info:{}
      }
    },
    created(){
      this.fetchData()
    },
    methods: {
      fetchData () {
        let that = this
        http
          .get(api.adminDetail, {})
          .then(res => {
            that.info = res.data
          })
          .catch(() => {})
      },
      saveUsername (data) {
        let that = this
        http
          .post(api.adminUsername, data)
          .then(res => {
            that.$toast.success(res.message)
            that.closeResetUsername(data.username)
          })
          .catch(() => {})
      },
      savePassword (data) {
        let that = this
        http
          .post(api.adminPassword, data)
          .then(res => {
            that.$toast.success(res.message)
            that.closeResetPassword()
          })
          .catch(() => {})
      },
      openResetUsername () {
        this.showResetUsername = true
      },
      closeResetUsername (username) {
        this.showResetUsername = false
        if(username!==undefined){
          this.info.username = username
        }
      },
      openResetPassword () {
        this.showResetPassword = true
      },
      closeResetPassword () {
        this.showResetPassword = false
      },
      logout () {
        let that = this
        that.$dialog.confirm({
          title: '',
          message: '确定退出吗',
        })
          .then(() => {
            localStorage.removeItem('admintoken')
            that.$toast.success('退出成功');
            location.reload()
          })
          .catch(() => {
            // on cancel
          });
      }
    }
  }
</script>

<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .cus_title { font-size: 28px;color: #999999;width: 155px;display: inline-block; }
  .cus_content { font-size: 28px;color: #333333; }
  .van-hairline--top-bottom::after, .van-hairline-unset--top-bottom::after { border-top: 0; }
  .bottom { position: absolute;left: 0;bottom: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px; }
  .cus_btn {
    position: absolute;right: 35px;top: 50%;width: 120px;height: 54px;border: 1PX solid #1787fb;font-size: 24px;
    color: #1787fb;
  }
</style>
