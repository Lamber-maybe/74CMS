<template>
  <div>
    <components :is="who" ref="child"></components>
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
      if (setShow === undefined || setShow === true) {
        this.$refs.child.show(callback)
      } else {
        callback()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
