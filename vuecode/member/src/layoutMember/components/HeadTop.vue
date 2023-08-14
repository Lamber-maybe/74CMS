<template>
  <div class="header">
    <div class="header_left">
      <a class="item home" :href="link_url_web.index">网站首页</a>
      <a class="item" href="javascript:;" @click="handlerFav">设为收藏</a>
      <a
        class="item"
        :href="config.sitedomain + config.sitedir + 'index/shortcut'"
        >保存桌面</a
      >
      <a class="item" :href="link_url_web.joblist">搜职位</a>
      <div class="clear"></div>
    </div>
    <div class="header_right">
      <el-dropdown class="drop" v-if="!isLogin" @command="handlerLogin">
        <a class="item" href="javascript:;"
          >会员登录<i class="el-icon-arrow-down el-icon--right"></i
        ></a>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="2">求职者登录</el-dropdown-item>
          <el-dropdown-item command="1">招聘登录</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <el-dropdown class="drop" v-if="!isLogin" @command="handlerReg">
        <a class="item" href="javascript:;"
          >会员注册<i class="el-icon-arrow-down el-icon--right"></i
        ></a>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="2">求职者注册</el-dropdown-item>
          <el-dropdown-item command="1">招聘注册</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <div class="login_name" v-if="isLogin">
        欢迎
        <router-link :to="memberUrl" class="name">{{
          show_username
        }}</router-link>
        登录，<span class="out" @click="handlerLogout">退出</span>
      </div>
      <el-dropdown class="drop" @command="handleManageClick" v-if="utype != 0">
        <a class="item" href="javascript:;"
          >管理中心<i class="el-icon-arrow-down el-icon--right"></i
        ></a>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item v-if="utype == 2" command="handlerRefreshResume"
            >刷新简历</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 2" command="handlerResumeIndex"
            >我的简历</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 2" command="handlerResumePreview"
            >简历预览</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 2" command="handlerPersonalRecommend"
            >智能推荐</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 2" command="handlerPersonalService"
            >增值服务</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 2" command="handlerPersonalPwd"
            >修改密码</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 1" command="handlerRefreshJob"
            >刷新职位</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 1" command="handlerAddJob"
            >发布职位</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 1" command="handlerCompanyPreview"
            >预览主页</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 1" command="handlerJobApplyList"
            >收到简历</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 1" command="handlerCompanyRecommend"
            >智能推荐</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 1" command="handlerCompanyService"
            >会员服务</el-dropdown-item
          >
          <el-dropdown-item v-if="utype == 1" command="handlerCompanyPwd"
            >修改密码</el-dropdown-item
          >
        </el-dropdown-menu>
      </el-dropdown>
      <el-popover class="drop_over" placement="bottom-end" trigger="hover">
        <span class="top-qrcode-item">
          <span class="img"><img :src="mobileQrcode" /></span>
          <span class="txt">触屏版</span>
        </span>
        <span class="top-qrcode-item" v-if="wechatQrcode">
          <span class="img"><img :src="wechatQrcode" /></span>
          <span class="txt">微信公众号</span>
        </span>
        <span class="top-qrcode-item" v-if="wechatMiniprogramQrcode">
          <span class="img"><img :src="wechatMiniprogramQrcode" /></span>
          <span class="txt">微信小程序</span>
        </span>
        <span class="top-qrcode-item" v-if="androidQrcode">
          <span class="img"><img :src="androidQrcode" /></span>
          <span class="txt">App(安卓)</span>
        </span>
        <span class="top-qrcode-item" v-if="iosQrcode">
          <span class="img"><img :src="iosQrcode" /></span>
          <span class="txt">App(苹果)</span>
        </span>
        <a class="item_phone" href="javascript:;" slot="reference"
          >手机端<i class="el-icon-arrow-down el-icon--right"></i
        ></a>
      </el-popover>
      <a class="item" target="_blank" :href="link_url_web.helplist">使用帮助</a>
      <el-popover class="drop_over" placement="bottom-end" trigger="hover">
        <a class="item_nav" href="javascript:;" slot="reference"
          >网站导航<i class="el-icon-arrow-down el-icon--right"></i
        ></a>
        <div class="nav_drop">
          <div class="dr_g">
            <div class="g_t">求职找工作</div>
            <div class="g_teg">
              <router-link to="/personal/resume" class="g_it"
                >登记简历</router-link
              >
              <a :href="link_url_web.joblist" class="g_it">找工作</a>
              <a :href="link_url_web.companylist" class="g_it">搜企业</a>
              <a :href="link_url_web.map" class="g_it">地图找工作</a>
              <a :href="link_url_web.famous_joblist" class="g_it">名企直聘</a>
              <a :href="link_url_web.joblist" class="g_it">最新职位</a>
              <a :href="link_url_web.emergency_joblist" class="g_it"
                >紧急招聘</a
              >
              <router-link to="/personal/recommend" class="g_it"
                >智能匹配</router-link
              >
              <div class="clear"></div>
            </div>
          </div>
          <div class="dr_g">
            <div class="g_t">企业招人才</div>
            <div class="g_teg">
              <router-link to="/company" class="g_it">企业入驻</router-link>
              <a href="javascript:;" class="g_it" @click="handlerAddJob"
                >发布职位</a
              >
              <a :href="link_url_web.resumelist" class="g_it">搜索简历</a>
              <router-link to="/company/joblist" class="g_it"
                >职位管理</router-link
              >
              <a
                href="javascript:;"
                class="g_it"
                @click="handlerCompanyRecommend"
                >人才推荐</a
              >
              <a href="javascript:;" class="g_it" @click="handlerCompanyService"
                >会员服务</a
              >
              <div class="clear"></div>
            </div>
          </div>
          <div class="dr_g">
            <div class="g_t">信息专栏</div>
            <div class="g_teg">
              <a :href="link_url_web.jobfairollist" class="g_it">网络招聘会</a>
              <a :href="link_url_web.noticelist" class="g_it">网站公告</a>
              <a :href="link_url_web.hrtoollist" class="g_it">HR工具箱</a>
              <a :href="link_url_web.articlelist" class="g_it">职场资讯</a>
              <div class="clear"></div>
            </div>
          </div>
          <div class="dr_g">
            <div class="g_t">自助工具</div>
            <div class="g_teg">
              <router-link to="/login/personal" class="g_it"
                >求职会员登录</router-link
              >
              <router-link to="/login/company" class="g_it"
                >招聘会员登录</router-link
              >
              <router-link to="/forget/1" class="g_it">忘记密码</router-link>
              <router-link to="/appeal" class="g_it">账号申诉</router-link>
              <a :href="link_url_web.helplist" class="g_it">帮助中心</a>
              <div class="clear"></div>
            </div>
          </div>
        </div>
      </el-popover>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'HeadTop',
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  data() {
    return {
      link_url_web: {},
      isLogin: false,
      utype: 0,
      show_username: '',
      memberUrl: '',
      preview_id: 0,
      mobileQrcode: '',
      wechatQrcode: '',
      wechatMiniprogramQrcode: '',
      androidQrcode: '',
      iosQrcode: ''
    }
  },
  watch: {
    $route() {
      this.fetchUserinfo()
    }
  },
  mounted() {
    this.link_url_web = this.$store.state.config.link_url_web
    this.mobileQrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + this.$store.state.config.mobile_domain
    this.wechatQrcode = this.$store.state.config.wechat_qrcode
    if (this.$store.state.config.wechat_miniprogram_qrcode !== undefined && this.$store.state.config.wechat_miniprogram_qrcode != '') {
      this.wechatMiniprogramQrcode = this.$store.state.config.wechat_miniprogram_qrcode
    }
    if (this.$store.state.config.app_android_download_url !== undefined && this.$store.state.config.app_android_download_url != '') {
      this.androidQrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + this.$store.state.config.app_android_download_url
    }
    if (this.$store.state.config.app_ios_download_url !== undefined && this.$store.state.config.app_ios_download_url != '') {
      this.iosQrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + this.$store.state.config.app_ios_download_url
    }

    this.fetchUserinfo()
    this.runCron()
  },
  methods: {
    runCron() {
      http.get(api.cron_run).then(() => {
      }).catch(() => { })
    },
    handlerFav() {
      var sURL = window.location
      var sTitle = document.title
      try {
        window.external.addFavorite(sURL, sTitle);
      } catch (e) {
        try {
          window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
          this.$confirm('加入收藏失败，请使用Ctrl+D进行添加', '提示', {
            type: 'warning',
            showCancelButton: false
          }).then(() => {
          }).catch(() => { });
        }
      }
    },
    fetchUserinfo() {
      http.get(api.userinfo).then(res => {
        this.isLogin = res.data.login
        if (this.isLogin) {
          this.utype = res.data.userinfo.utype
          if (res.data.userinfo.utype == 1) {
            this.memberUrl = '/company'
          } else {
            this.memberUrl = '/personal'
          }
          this.show_username = res.data.show_username
          this.preview_id = res.data.preview_id
        }
      }).catch(() => { })
    },
    handlerLogin(utype) {
      if (utype == 1) {
        this.$router.push('/login/company')
      } else {
        this.$router.push('/login/personal')
      }
    },
    handlerReg(utype) {
      if (utype == 1) {
        this.$router.push('/reg/company')
      } else {
        this.$router.push('/reg/personal')
      }
    },
    handlerLogout() {
      this.$confirm('确定退出当前账号吗?', '提示', {
        type: 'warning'
      }).then(() => {
        this.$store.commit('setLoginState', {
          whether: false,
          utype: 0,
          token: '',
          mobile: '',
          userIminfo: {}
        })
        this.$router.push('/login')
      }).catch(() => {

      });
    },
    handleManageClick(func) {
      this[func]()
    },
    handlerRefreshResume() {
      http
        .post(api.resume_refresh)
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$message({ type: 'success', message: res.message })
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlerResumeIndex() {
      this.$router.push('/personal/resume')
    },
    handlerResumePreview() {
      let url = this.link_url_web.resumeshow
      url = url.replace('_id_', this.preview_id)
      window.open(url)
    },
    handlerPersonalRecommend() {
      this.$router.push('/personal/recommend')
    },
    handlerPersonalService() {
      this.$router.push('/personal/service/stick')
    },
    handlerPersonalPwd() {
      this.$router.push('/personal/account/safety')
    },
    handlerRefreshJob() {
      this.$confirm('确定刷新所有发布中的职位吗？', '系统提示', {
        type: 'warning'
      })
        .then(() => {
          http
            .post(api.company_job_refresh_batch, {})
            .then(res => {
              if (res.data.done === 0) {
                this.$confirm(res.message, '系统提示', { type: 'warning' })
                  .then(() => {
                    this.$router.push('/company/joblist')
                  })
                  .catch(() => {
                    // on cancel
                  })
                return false
              } else {
                this.$message({ type: 'success', message: res.message })
              }
            })
            .catch(() => { })
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerAddJob() {
      http.get(api.company_check_jobadd_num, {}).then(res => {
        if (res.data.enable_addjob_num <= 0) {
          this.$confirm('您当前是' +
            res.data.setmeal_name +
            '，当前可发布职位数为0，建议您立即升级套餐或将暂时不招聘的职位设为关闭！', '系统提示', {
            confirmButtonText: '升级套餐',
            type: 'warning'
          })
            .then(() => {
              this.$router.push('/company/service/setmeal')
            })
            .catch(() => {
              // on cancel
            })
          return false
        } else {
          this.$router.push('/company/jobadd')
        }
      }).catch(() => { })
    },
    handlerCompanyPreview() {
      let url = this.link_url_web.companyshow
      url = url.replace('_id_', this.preview_id)
      window.open(url)
    },
    handlerJobApplyList() {
      this.$router.push('/company/resume/jobapply')
    },
    handlerCompanyRecommend() {
      this.$router.push('/company/recommend')
    },
    handlerCompanyService() {
      this.$router.push('/company/service/setmeal')
    },
    handlerCompanyPwd() {
      this.$router.push('/company/account/safety')
    }
  }
}
</script>

<style lang="scss" scoped>
.nav_drop {
  width: 1200px;
  display: flex;
  .dr_g {
    flex: 1;
    position: relative;
    padding: 20px 45px 10px 45px;
    &:not(:last-child)::after {
      content: "";
      position: absolute;
      right: 0;
      top: 10px;
      height: 120px;
      border-right: 1px solid #f0f0f0;
    }
    .g_t {
      font-size: 14px;
      font-weight: bold;
      color: #333;
    }
    .g_teg {
      padding-top: 16px;
      .g_it {
        margin: 0 16px 10px 0;
        font-size: 12px;
        color: #333;
        display: block;
        float: left;
      }
    }
  }
}
.header {
  width: 100%;
  padding: 11px 0;

  .header_left {
    float: left;
  }

  .header_right {
    float: right;
  }

  .not_login {
    font-size: 12px;
  }

  .login_name {
    float: left;
    font-size: 12px;
    .name {
      color: #1787fb;
    }
    .out {
      color: #999;
      cursor: pointer;
    }
  }

  .drop_over {
    float: left;
  }

  .item {
    display: block;
    float: left;
    font-size: 12px;
    position: relative;

    &:not(:first-child) {
      margin-left: 20px;

      &::before {
        content: "";
        position: absolute;
        left: -10px;
        top: 1px;
        width: 1px;
        height: 13px;
        background-color: #dddddd;
      }
    }

    &.home {
      background: url("../../assets/images/header/header_home_ico.png") 0 0
        no-repeat;
      background-size: 13px;
      padding-left: 18px;
    }
  }

  .item_phone {
    background: url("../../assets/images/header/header_phone_ico.png") 0 1px
      no-repeat;
    background-size: 13px;
    padding-left: 18px;
    margin-left: 20px;
    display: block;
    float: left;
    font-size: 12px;
    position: relative;
    &::before {
      content: "";
      position: absolute;
      left: -10px;
      top: 1px;
      width: 1px;
      height: 13px;
      background-color: #dddddd;
    }
  }
  .item_nav {
    margin-left: 20px;
    display: block;
    float: left;
    font-size: 12px;
    position: relative;
    &::before {
      content: "";
      position: absolute;
      left: -10px;
      top: 1px;
      width: 1px;
      height: 13px;
      background-color: #dddddd;
    }
  }

  .co9 {
    color: #999;
  }

  .blue {
    color: #409eff;
  }

  .drop {
    float: left;

    &:not(:first-child) {
      margin-left: 20px;

      &::before {
        content: "";
        position: absolute;
        left: -10px;
        top: 1px;
        width: 1px;
        height: 13px;
        background-color: #dddddd;
      }
    }
  }
}
.top-qrcode-item {
  display: inline-block;
  width: 100px;
  margin: 0 10px;
  span {
    display: block;
  }
  .img img {
    width: 100px;
    height: 100px;
  }
  .txt {
    text-align: center;
  }
}
</style>
