<template>
  <div class="public_head" v-if="$store.state.isHeadShow">
    <div :class="classname" :style="bgColor!==undefined?'background-color:'+bgColor:''">
      <router-link class="head_substation" v-if="showSubstationName" to="/subsitelist">
        {{ $store.state.config.subsite_info.district_text }}
      </router-link>
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
        <div class="item_wrapper " v-if="itemList.length == 1">
          <div
            class="item_block item_block_pad"
            v-for="(item, index) in itemList[0]"
            :key="index"
            @click="handleHeadClick(item)"
          >
            <img :src="item.src" :alt="item.name" class="ico" />
            <div class="ico_txt">{{ item.name }}</div>
          </div>
          <div class="clear"></div>
        </div>
        <div class="item_wrapper" v-else>
          <swiper :options="swiperOption" class="item_wrapper_pd" v-if="$store.state.swiperLoaded">
            <swiper-slide v-for="(item,index) in itemList" :key="index" >
              <div v-for="(item2,index2) in item" :key="index2" class="item_block"  @click="handleHeadClick(item2)">
                <img :src="item2.src" :alt="item2.name" class="ico" />
                <div class="ico_txt">{{ item2.name }}</div>
              </div>
            </swiper-slide>
            <div class="swiper-pagination" slot="pagination"></div>
          </swiper>
        </div>
      </div>
    </van-popup>
  </div>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex'
import { isWeiXin } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Head',
  props: ['goback_custom', 'bg', 'show_right', 'show_back', 'bgColor', 'show_sub'],
  data () {
    return {
      showSubstationName: false,
      substationName: '总站',
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
        { name: '网络招聘会', src: `member`, url: '/jobfairol', imgName: 'jobfairol' },
        { name: '职场资讯', src: `member`, url: '/newslist', imgName: 'news' },
        { name: '视频招聘', src: `member`, url: '/shortvideo/companylist', imgName: 'shortvideo' },
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
        { name: '网络招聘会', src: `member`, url: '/jobfairol', imgName: 'jobfairol' },
        { name: '视频招聘', src: `member`, url: '/shortvideo/companylist', imgName: 'shortvideo' },
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
        { name: '网络招聘会', src: `member`, url: '/jobfairol', imgName: 'jobfairol' },
        { name: '视频招聘', src: `member`, url: '/shortvideo/companylist', imgName: 'shortvideo' },
        { name: '我的', src: `company`, url: '/member/login', imgName: 'user' }
      ],
      itemList: [],
      classname: 'head_content',
      swiperOption: {
        pagination: {
          el: '.swiper-pagination',
          renderBullet: function (index, className) {
            return (
              '<span class="' +
              className +
              ' index-app-four-swiper-span"></span>'
            )
          },
          bulletActiveClass: 'index-app-four-swiper-bullet-active'
        },
        initialSlide: 0,
        speed: 800
      },
      timer: null
    }
  },
  mounted () {
    window.addEventListener('scroll', this.scrollToTop)
    this.showRight = this.show_right !== 'false'
    this.showBackArrow = this.show_back !== 'false'
    this.showSubstationName = this.show_sub === 'true'
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
        this.itemList = this.group(this.companyMoreList, 12)
      } else {
        // 个人
        this.user_link = '/member/personal/index'
        this.itemList = this.group(this.personalMoreList, 12)
      }
    } else {
      // 未登录
      this.user_link = '/member/login'
      this.itemList = this.group(this.memberMoreList, 12)
    }
    // 更多
    this.itemList = this.itemList.map(function (item, index) {
      return item.map(function (item2, index) {
        let imgUrl = `${item2.src}/${item2.imgName}`
        return {
          name: item2.name,
          src: require('../assets/images/head_more/' + imgUrl + '.png'),
          url: item2.url
        }
      })
    })
    // if (this.LoginType != 0) {
    //   // alert(222)
    //   // 0 - 表示连接尚未建立，1 - 表示连接已建立，可以进行通信，2 - 表示连接正在进行关闭，3 - 表示连接已经关闭或者连接不能打开
    //   console.log(this.ws.readyState, 'head')
    //   if (this.ws.readyState != 1) {
    //     if (this.imToken == '') {
    //       // 获取聊天token
    //       this.getImToken()
    //     }
    //   }
    // }
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken,
      LoginType: state => state.LoginType,
      ws: state => state.ws
    }),
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
    ...mapMutations(['setImToken']),
    ...mapActions(['initWebSocket', 'webSocket_send']),
    /**
     * 获取imToken
     */
    getImToken () {
      http.get(api.imToken).then((res) => {
        this.setImToken(res.data)
        // 初始化WebSocket
        // setTimeout(() => {
        this.initWebSocket(res.data)
        // }, 500)
      })
    },
    /**
     * 全局检测聊天是否开启
     */
    imWindowGlobal () {
      http.post(api.im_window_global).then((res) => {
        if (res.data.next == '') {
          // 获取聊天token
          this.getImToken()
        }
      })
    },
    group (array, subGroupLength) {
      let index = 0
      let newArray = []
      while (index < array.length) {
        newArray.push(array.slice(index, index += subGroupLength))
      }
      return newArray
    },
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
  .item_wrapper_pd{
    padding-bottom: 15px;
  }
  .item_wrapper {
    .swiper-pagination{
      bottom: 0px;
    }
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
    .item_block_width{
      width: 100% !important;
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
      .head_substation {
        color: #ffffff;
        &::after {
          border-top: 1px solid #ffffff;
          border-left: 1px solid #ffffff;
        }
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
    .head_substation {
      &::after {
        content: "";
        position: absolute;
        right: 1px;
        top: 21px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-left: 1px solid #666666;
        transform: rotate(-135deg);
      }
      position: absolute;
      left: 0;
      top: 0;
      max-width: 105px;
      height: 100%;
      font-size: 14px;
      font-weight: normal;
      padding: 16px 14px;
      overflow: hidden;
      white-space: nowrap;
      display: block;
      color: #666666;
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
