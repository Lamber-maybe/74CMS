<template>
  <div>
    <components :is="who" ref="child" @setSubmitFun="setSubmitFun"></components>
  </div>
</template>

<script>
import ctencent from './components/Tencent'
import cvaptcha from './components/Vaptcha'
import cpicture from './components/Picture'
export default {
  name: 'Captcha',
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
    show (callback, setShow) {
      if (this.$store.state.config.captcha_open == 1 && (setShow === undefined || setShow === true)) {
        this.$refs.child.show(callback)
      } else {
        callback()
      }
    },
    setSubmitFun () {
      this.$emit('setSubmitFun')
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
