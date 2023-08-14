<template>
  <div>
    <!-- <router-view></router-view> -->
    <keep-alive>
      <router-view v-if="$route.meta.keepAlive"></router-view>
    </keep-alive>
    <router-view
      v-if="$route.meta.keepAlive === undefined || !$route.meta.keepAlive"
    ></router-view>
  </div>
</template>

<script>
import { isWeiXin } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import { mapMutations, mapState, mapActions } from 'vuex'
export default {
  data () {
    return {
      timer: ''
    }
  },
  watch: {
    '$route' () {
      if (window._czc) {
        let location = window.location
        let contentUrl = location.pathname + location.hash
        let refererUrl = '/'
        window._czc.push(['_trackPageview', contentUrl, refererUrl])
      }
      if (window._hmt) {
        window._hmt.push(['_requirePlugin', 'UrlChangeTracker', {
          shouldTrackUrlChange: function (newPath, oldPath) {
            return newPath && oldPath
          }}
        ])
      }
    }
  },
  mounted () {
    document.addEventListener('visibilitychange', () => {
      if (!document.hidden) { // 页面呼出
      // 0 - 表示连接尚未建立，1 - 表示连接已建立，可以进行通信，2 - 表示连接正在进行关闭，3 - 表示连接已经关闭或者连接不能打开
        var u = navigator.userAgent
        var app = navigator.appVersion
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/) // ios终端
        if (isIOS) {
          if (this.LoginType != 0) {
            if (this.ws.readyState != 1) {
              this.imWindowGlobal()
            }
          }
        }
      }
    })
    if (this.LoginType != 0) {
      http.post(api.im_window_global).then((res) => {
        if (res.data.next == '') {
          this.timer = setInterval(this.ping, 30000)
        }
      })
    }
  },
  beforeDestroy () {
    clearInterval(this.timer)
  },
  created () {
    // 刷新页面时把config置空，保证系统配置信息的时效性
    window.addEventListener('beforeunload', () => {
      this.$store.state.config = ''
      this.$store.state.sendSmsBtnText = '获取验证码'
      this.$store.state.sendSmsBtnTextColor = '#1787fb'
      this.$store.state.sendSmsInterval = 60
      localStorage.setItem('vuex', JSON.stringify(this.$store.state))
    })
    this.$store.commit('setPlatform', isWeiXin() === true ? 'wechat' : 'mobile')
    if (this.$route.query.scene_uuid !== undefined) {
      localStorage.setItem('scene_uuid', this.$route.query.scene_uuid)
      http.post(api.scene_record, {scene_uuid: this.$route.query.scene_uuid}).then(() => {}).catch(() => {})
    }
    if (this.LoginType != 0) {
      // 获取聊天token
      // if (this.ws.readyState == undefined) {
      this.imWindowGlobal()
      // }
    }
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken,
      LoginType: state => state.LoginType,
      ws: state => state.ws
    })
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
        this.initWebSocket(res.data)
      })
    },
    /**
     * 全局检测聊天是否开启
     */
    imWindowGlobal () {
      http.post(api.im_window_global).then((res) => {
        if (res.data.next == '') {
          this.getImToken()
        }
      })
    },
    ping () {
      var msgObj = {
        controller: 'Ping',
        action: 'index',
        args: {}
      }
      // var msgStr = JSON.stringify(msgObj)
      this.webSocket_send(msgObj)
    }
  }
}
</script>

<style lang="scss"></style>
