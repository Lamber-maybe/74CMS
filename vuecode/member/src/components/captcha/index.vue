<template>
  <div>
    <components :is="who" ref="child" :mask="mask"></components>
  </div>
</template>

<script>
import ctencent from './components/Tencent'
import cvaptcha from './components/Vaptcha'
import cpicture from './components/Picture'
export default {
  name: 'Captcha',
  props:['mask'],
  components: {
    ctencent,
    cvaptcha,
    cpicture
  },
  data () {
    return {
      who: ''
    }
  },
  mounted () {},
  created () {
    this.who = this.$store.state.config.captcha_type == '' ? 'picture' : this.$store.state.config.captcha_type
    this.who = 'c' + this.who
  },
  computed: {},
  methods: {
    show (callback, loginType, loginErrorNum) {
      if (this.$store.state.config.captcha_open == 1) {
        if (loginType === undefined && loginErrorNum === undefined) {
          this.$refs.child.show(callback)
        } else if (loginType == 'pwd' && parseInt(loginErrorNum) >= this.$store.state.config.captcha_show_by_pwd_error) {
          this.$refs.child.show(callback)
        } else if (loginType == 'code' && parseInt(loginErrorNum) >= this.$store.state.config.captcha_show_by_code_error) {
          this.$refs.child.show(callback)
        } else {
          callback()
        }
      } else {
        callback()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
