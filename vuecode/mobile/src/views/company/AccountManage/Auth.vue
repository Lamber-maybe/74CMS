<template>
  <div id="app">
    <AuthForm v-if="authinfo.audit == 0" :authinfo="authinfo" @reload="fetchData"></AuthForm>
    <AuthResult v-else :authinfo="authinfo" @reload="fetchData"></AuthResult>
  </div>
</template>

<script>
import AuthForm from '@/components/account/company_auth/AuthForm'
import AuthResult from '@/components/account/company_auth/AuthResult'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'CompanyAuth',
  components: {
    AuthForm,
    AuthResult
  },
  data () {
    return {
      authinfo: {}
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      http
        .get(api.company_authinfo, {})
        .then(res => {
          this.authinfo = { ...res.data }
        })
        .catch(() => {})
    }
  }
}
</script>
