<template>
  <div>

  </div>
</template>

<script>
export default {
  name: 'CaptchaVaptcha',
  props:['mask'],
  data () {
    return {

    }
  },
  mounted () {
    if (window.vaptcha === undefined) {
      let script = document.createElement('script')
      let head = document.getElementsByTagName('head')[0]
      script.type = 'text/javascript'
      script.charset = 'UTF-8'
      script.src = 'https://v.vaptcha.com/v3.js'
      head.appendChild(script)
    }
  },
  created () {},
  computed: {},
  methods: {
    show (callback) {
      let that = this
      if (that.$store.state.config.captcha_vaptcha_vid == '') {
        this.$message.error('请正确配置手势验证vid')
        return false
      }
      window.vaptcha({
        vid: that.$store.state.config.captcha_vaptcha_vid, // 验证单元id
        type: 'invisible', // 显示类型 隐藏式
        scene: 0, // 场景值 默认0
        offline_server: '' // 离线模式服务端地址，若尚未配置离线模式，请填写任意地址即可。
        // 可选参数
        // lang: 'auto', // 语言 默认auto,可选值auto,zh-CN,en,zh-TW,jp
        // https: true, // 使用https 默认 true
      }).then(function (vaptchaObj) {
        // obj = vaptchaObj // 将VAPTCHA验证实例保存到局部变量中
        // 获取token的方式一：
        // vaptchaObj.renderTokenInput('.login-form')//以form的方式提交数据时，使用此函数向表单添加token值
        // 获取token的方式二：
        vaptchaObj.validate()
        vaptchaObj.listen('pass', function () {
          // 验证成功进行后续操作
          var data = {
            // 表单数据
            code: vaptchaObj.getToken()
          }
          callback(data)
          // $.post('login', data, function (r) {
          //   if (r.code !== 200) {
          //     console.log('登录失败')
          //     vaptchaObj.reset() // 重置验证码
          //   }
          // })
        })
        // 关闭验证弹窗时触发
        vaptchaObj.listen('close', function () {
          // 验证弹窗关闭触发
        })
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
