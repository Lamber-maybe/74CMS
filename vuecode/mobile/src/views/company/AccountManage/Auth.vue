<template>
  <div id="app">
    <AuthType v-if="authinfo.audit == 0" @reload="fetchData"></AuthType>
    <AuthResult v-else :authinfo="authinfo" @reload="fetchData"></AuthResult>
  </div>
</template>

<script>
import AuthType from '@/components/account/company_auth/AuthType'
import AuthResult from '@/components/account/company_auth/AuthResult'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'CompanyAuth',
  components: {
    AuthType,
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
