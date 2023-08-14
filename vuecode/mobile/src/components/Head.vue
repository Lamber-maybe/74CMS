<template>
  <div class="public_head" v-if="$store.state.isHeadShow">
    <div :class="classname" :style="bgColor!==undefined?'background-color:'+bgColor:''">
      <div class="head_back" v-if="showBackArrow" @click="goBack"></div>
      <div class="substring"><slot></slot></div>
      <div
        class="head_more"
        v-if="showRight"
        @click="showMore = !showMore"
      ></div>
      <router-link class="head_user" v-if="showRight" :to="user_link">
        <div class="sub_block" v-if="$store.state.LoginOrNot">
          <div class="block_con"></div>
        </div>
      </router-link>
    </div>
    <van-popup v-model="showMore" position="top">
      <div class="more_wrapper">
        <div class="head_wrapper">
          <div class="head_back" @click="showMore = !showMore"></div>
          <div><slot></slot></div>
          <div class="head_more" @click="showMore = !showMore"></div>
          <router-link class="head_user" :to="user_link">
            <div class="sub_block" v-if="$store.state.LoginOrNot">
              <div class="block_con"></div>
            </div>
          </router-link>
        </div>
        <div class="item_wrapper">
          <div
            class="item_block"
            v-for="(item, index) in itemList"
            :key="index"
            @click="handleHeadClick(item)"
          >
            <img :src="item.src" :alt="item.name" class="ico" />
            <div class="ico_txt">{{ item.name }}</div>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </van-popup>
  </div>
</template>

<script>
import { isWeiXin } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Head',
  props: ['goback_custom', 'bg', 'show_right', 'show_back', 'bgColor'],
  data () {
    return {
      showBackArrow: true,
      name: '',
      user_link: '',
      showMore: false,
      showRight: true,
      memberMoreList: [
        { name: '首页', src: `member`, url: '/', imgName: 'home' },
        { name: '找工作', src: `member`, url: '/joblist', imgName: 'job' },
        { name: '搜企业', src: `member`, url: '/companylist', imgName: 'company' },
        { name: '找人才', src: `member`, url: '/resumelist', imgName: 'resume' },
        { name: '发布职位', src: `member`, url: '/member/company/index', imgName: 'add_job' },
        { name: '创建简历', src: `member`, url: '/member/personal/index', imgName: 'add_resume' },
        { name: '职场资讯', src: `member`, url: '/newslist', imgName: 'news' },
        { name: '我的', src: `member`, url: '/member/login', imgName: 'user' }
      ],
      personalMoreList: [
        { name: '首页', src: `personal`, url: '/', imgName: 'home' },
        { name: '找工作', src: `personal`, url: '/joblist', imgName: 'job' },
        { name: '搜企业', src: `personal`, url: '/companylist', imgName: 'company' },
        { name: '附近工作', src: `personal`, url: '/jobnearby', imgName: 'nearby' },
        { name: '编辑简历', src: `personal`, url: '/member/personal/resume', imgName: 'edit_resume' },
        { name: '刷新简历', src: `personal`, url: '/member/personal/index', imgName: 'refresh_resume' },
        { name: '智能匹配', src: `personal`, url: '/member/personal/recommend', imgName: 'recommend' },
        { name: '会员服务', src: `personal`, url: '/member/personal/service', imgName: 'service' },
        { name: '我的职聊', src: `personal`, url: '/im/imlist', imgName: 'im' },
        { name: '我的', src: `personal`, url: '/member/login', imgName: 'user' }
      ],
      companyMoreList: [
        { name: '首页', src: `company`, url: '/', imgName: 'home' },
        { name: '找人才', src: `company`, url: '/resumelist', imgName: 'resume' },
        { name: '发布职位', src: `company`, url: '/member/company/index', imgName: 'add_job' },
        { name: '一键刷新', src: `company`, url: '/member/company/index', imgName: 'refresh' },
        { name: '职位管理', src: `company`, url: '/member/company/joblist', imgName: 'joblist' },
        { name: '简历管理', src: `company`, url: '/member/company/jobapply', imgName: 'jobapply' },
        { name: '智能匹配', src: `company`, url: '/member/company/recommend', imgName: 'recommend' },
        { name: '会员服务', src: `company`, url: '/member/company/mysetmeal', imgName: 'setmeal' },
        { name: '我的职聊', src: `company`, url: '/im/imlist', imgName: 'im' },
        { name: '我的', src: `company`, url: '/member/login', imgName: 'user' }
      ],
      itemList: [],
      classname: 'head_content'
    }
  },
  mounted () {
    window.addEventListener('scroll', this.scrollToTop)
    this.showRight = this.show_right !== 'false'
    this.showBackArrow = this.show_back !== 'false'
  },
  created () {
    this.initWeixinPaymentOpenid()
    if (this.bg) {
      this.classname = `head_content ${this.bg}`
    }
    // 根据登录状态和会员类型设置对应的数据
    if (this.isLogin) {
      if (parseInt(this.loginType) === 1) {
        // 企业
        this.user_link = '/member/company/index'
        this.itemList = this.companyMoreList
      } else {
        // 个人
        this.user_link = '/member/personal/index'
        this.itemList = this.personalMoreList
      }
    } else {
      // 未登录
      this.user_link = '/member/login'
      this.itemList = this.memberMoreList
    }
    // 更多
    this.itemList = this.itemList.map(function (item, index) {
      let imgUrl = `${item.src}/${item.imgName}`
      return {
        name: item.name,
        src: require('../assets/images/head_more/' + imgUrl + '.png'),
        url: item.url
      }
    })
  },
  computed: {
    config () {
      return this.$store.state.config
    },
    isLogin () {
      return this.$store.state.LoginOrNot
    },
    loginType () {
      return this.$store.state.LoginType
    }
  },
  methods: {
    handleHeadClick (item) {
      if (item.name === '刷新简历') {
        this.refreshResume()
        this.showMore = false
      } else {
        this.$router.push(item.url)
      }
    },
    // 刷新简历
    refreshResume () {
      http
        .post(api.resume_refresh)
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    scrollToTop () {
      if (this.getScrollTop()) {
        this.classname = 'head_content wh'
      } else {
        if (this.bg) {
          this.classname = `head_content ${this.bg}`
        } else {
          this.classname = 'head_content'
        }
      }
    },
    // 当前距离顶部的滚动值
    getScrollTop () {
      let scrollTop = 0
      if (document.documentElement && document.documentElement.scrollTop) {
        scrollTop = document.documentElement.scrollTop
      } else if (document.body) {
        scrollTop = document.body.scrollTop
      }
      return scrollTop
    },
    // 返回上一页
    goBack () {
      if (this.goback_custom === true) {
        this.$emit('gobackCustomMethod')
      } else {
        window.history.length > 1
          ? this.$router.go(-1)
          : this.$router.push('/')
      }
    },
    initWeixinPaymentOpenid () {
      let weixinOpenid = localStorage.getItem('weixinOpenid')
      if (isWeiXin() === true && !weixinOpenid && this.config.payment_wechat_appid != '') {
        let code = ''
        let url = window.location.href
        let param_str = url.split('?')[1]
        if (param_str !== undefined) {
          let cs_arr = param_str.split('&')
          let cs = {}
          for (let i = 0; i < cs_arr.length; i++) {
            cs[cs_arr[i].split('=')[0]] = cs_arr[i].split('=')[1]
          }
          code = cs.code
        }
        if (code !== '') {
          http.post(api.get_weixin_openid, {code}).then(res => {
            localStorage.setItem('weixinOpenid', res.data)
          }).catch(() => {})
        } else {
          let appid = this.config.payment_wechat_appid
          let redirect_uri = encodeURI(location.href)
          let state = 'STATE'
          let url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + appid + '&redirect_uri=' + redirect_uri + '&response_type=code&scope=snsapi_base&state=' + state + '#wechat_redirect'
          location.href = url
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.more_wrapper {
  .item_wrapper {
    .item_block {
      .ico {
        display: block;
        margin: 0 auto 9px;
        width: 45px;
        height: 45px;
        border: 0;
        font-size: 10px;
      }
      .ico_txt {
        font-size: 13px;
        color: #333333;
      }
      float: left;
      display: block;
      width: 25%;
      text-align: center;
      padding: 15px 0 13px;
    }
    width: 100%;
  }
  .head_wrapper {
    * {
      line-height: normal;
    }
    width: 100%;
    height: 53px;
    padding: 14px 70px;
    text-align: center;
    font-size: 18px;
    color: #333333;
    font-weight: bolder;
    background-color: #ffffff;
    z-index: 9;
    .head_back {
      position: absolute;
      left: 0;
      top: 0;
      width: 40px;
      height: 100%;
      &::after {
        content: "";
        position: absolute;
        right: 10px;
        top: 21px;
        width: 11px;
        height: 11px;
        border-top: 1px solid #666666;
        border-left: 1px solid #666666;
        transform: rotate(-45deg);
      }
    }
    .head_more {
      position: absolute;
      right: 0;
      top: 0;
      width: 40px;
      height: 100%;
      background: url("../assets/images/head_more_ico.png") center no-repeat;
      background-size: 15px 14.5px;
    }
    .head_user {
      position: absolute;
      right: 40px;
      top: 0;
      width: 40px;
      height: 100%;
      display: block;
      background: url("../assets/images/head_user_ico.png") center no-repeat;
      background-size: 16px 17px;
      .sub_block {
        height: 10px;
        width: 10px;
        position: absolute;
        border-radius: 100%;
        background: #1787fb;
        right: 7px;
        bottom: 15px;
        display: block;
        .block_con {
          position: relative;
          height: 10px;
          width: 10px;
          border-radius: 100%;
          display: block;
          &::after,
          &::before {
            content: "";
            height: 5px;
            width: 1px;
            display: block;
            background: #fff;
            position: absolute;
            top: 3px;
            left: 5px;
            border-radius: 8px;
            transform: rotate(45deg);
          }
          &::before {
            height: 3px;
            transform: rotate(-45deg);
            top: 5px;
            left: 3px;
          }
        }
      }
    }
    position: relative;
  }
}
.public_head {
  width: 100%;
  height: 53px;
  .head_content {
    &.tran {
      color: #ffffff;
      .head_back {
        &::after {
          border-color: #ffffff;
        }
      }
      .head_user {
        background: url("../assets/images/head_user_ico_wh.png") center
          no-repeat;
        background-size: 16px 17px;
      }
      .head_more {
        background: url("../assets/images/head_more_ico_wh.png") center
          no-repeat;
        background-size: 15px 14.5px;
      }
    }
    &.wh {
      background-color: #ffffff;
      color: #333333;
      &::after {
        position: fixed;
        box-sizing: border-box;
        content: " ";
        pointer-events: none;
        right: 0;
        top: 53px;
        left: 0;
        border-bottom: 1px solid #ebebeb;
        -webkit-transform: scaleY(0.5);
        transform: scaleY(0.5);
        z-index: 10;
      }
      .head_back {
        &::after {
          border-color: #666666;
        }
      }
      .head_user {
        background: url("../assets/images/head_user_ico.png") center no-repeat;
        background-size: 16px 17px;
      }
      .head_more {
        background: url("../assets/images/head_more_ico.png") center no-repeat;
        background-size: 15px 14.5px;
      }
    }
    position: fixed;
    width: 100%;
    height: 53px;
    padding: 14px 70px;
    text-align: center;
    font-size: 18px;
    color: #333333;
    font-weight: bold;
    background-color: transparent;
    z-index: 2;
    * {
      line-height: normal;
    }
    &.bt {
      border-bottom: 1px solid #ebebeb;
    }
    .head_back {
      &::after {
        content: "";
        position: absolute;
        right: 10px;
        top: 21px;
        width: 11px;
        height: 11px;
        border-top: 1px solid #666666;
        border-left: 1px solid #666666;
        transform: rotate(-45deg);
      }
      position: absolute;
      left: 0;
      top: 0;
      width: 40px;
      height: 100%;
    }
    .head_more {
      position: absolute;
      right: 0;
      top: 0;
      width: 40px;
      height: 100%;
      background: url("../assets/images/head_more_ico.png") center no-repeat;
      background-size: 15px 14.5px;
    }
    .head_user {
      position: absolute;
      right: 40px;
      top: 0;
      width: 40px;
      height: 100%;
      display: block;
      background: url("../assets/images/head_user_ico.png") center no-repeat;
      background-size: 16px 17px;
      .sub_block {
        height: 10px;
        width: 10px;
        position: absolute;
        border-radius: 100%;
        background: #1787fb;
        right: 7px;
        bottom: 15px;
        display: block;
        .block_con {
          position: relative;
          height: 10px;
          width: 10px;
          border-radius: 100%;
          display: block;
          &::after,
          &::before {
            content: "";
            height: 5px;
            width: 1px;
            display: block;
            background: #fff;
            position: absolute;
            top: 3px;
            left: 5px;
            border-radius: 8px;
            transform: rotate(45deg);
          }
          &::before {
            height: 3px;
            transform: rotate(-45deg);
            top: 5px;
            left: 3px;
          }
        }
      }
    }
  }
}
</style>
