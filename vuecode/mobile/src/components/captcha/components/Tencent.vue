<template>
  <div>

  </div>
</template>

<script>
export default {
  name: 'CaptchaTencent',
  data () {
    return {

    }
  },
  mounted () {
    if (window.TencentCaptcha === undefined) {
      let script = document.createElement('script')
      let head = document.getElementsByTagName('head')[0]
      script.type = 'text/javascript'
      script.charset = 'UTF-8'
      script.src = 'https://turing.captcha.qcloud.com/TCaptcha.js'
      head.appendChild(script)
    }
  },
  created () {},
  computed: {},
  methods: {
    show (callback) {
      let that = this
      if (that.$store.state.config.captcha_tencent_appid == '') {
        this.$notify('请正确配置腾讯防水墙appid')
        return false
      }
      var captcha = new window.TencentCaptcha(that.$store.state.config.captcha_tencent_appid, function (res) {
        if (res.ret === 0) {
          callback(res)
        }
      })
      captcha.show()
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
