<template>
  <div id="app">
    <Login
      :utype="utype"
      :redirect="redirect"
      :single_login="false"
      @afterLogin="redirectTo"
    ></Login>
  </div>
</template>

<script>
import Login from '@/components/Login'
export default {
  name: 'MemberLogin',
  components: {
    Login
  },
  data () {
    return {
      redirect: '',
      utype: 2
    }
  },
  created () {
    this.redirect =
      this.$route.query.redirect !== undefined
        ? this.$route.query.redirect
        : ''
  },
  watch: {
    $route: {
      immediate: true,
      handler (to, from) {
        this.utype = this.$route.params.utype_alias === 'company' ? 1 : 2
      }
    }
  },
  methods: {
    redirectTo () {
      if (this.redirect !== '') {
        this.$router.push(this.redirect)
      } else {
        if (this.utype === 1) {
          this.$router.push('/member/company/index')
        } else {
          this.$router.push('/member/personal/index')
        }
      }
    }
  }
}
</script>
