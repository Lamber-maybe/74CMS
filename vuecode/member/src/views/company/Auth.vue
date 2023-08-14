<template>
  <div>
    <AuthForm :authinfo="authinfo" v-if="authinfo.audit == 0" @reload="fetchData"></AuthForm>
    <AuthResult v-else :authinfo="authinfo" @reload="fetchData"></AuthResult>
    
  </div>
</template>


<script>
import AuthForm from '@/components/company/auth/AuthForm'
import AuthResult from '@/components/company/auth/AuthResult'
import http from '@/utils/http'
import api from '@/api'
export default {
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
