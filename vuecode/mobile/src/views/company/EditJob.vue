<template>
  <div id="app">
    <Head>修改职位</Head>
    <JobForm ref="child" type="edit" @submit="submit"></JobForm>
  </div>
</template>

<script>
import JobForm from '@/components/JobForm'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditJob',
  components: {
    JobForm
  },
  data () {
    return {}
  },
  created () {
    this.fetchData(this.$route.params.id)
  },
  methods: {
    fetchData (id) {
      http
        .get(api.company_jobedit_pre, { id })
        .then(res => {
          this.$refs.child.basic = res.data.basic
          this.$refs.child.contact = res.data.contact
          this.$refs.child.field_rule = res.data.field_rule
          this.$refs.child.secrecyHidden = res.data.contact.is_secrecy == 1 ? false : true
          this.$refs.child.restoreCondition({
            basic: res.data.basic,
            contact: res.data.contact,
            field_rule: res.data.field_rule
          })
        })
        .catch(() => {})
    },
    submit (values) {
      http
        .post(api.company_jobedit_save, values)
        .then(res => {
          this.$notify({ type: 'success', message: res.message })
          this.$router.push('/member/company/joblist?operate_type=' + 'edit')
        })
        .catch(() => {})
    }
  }
}
</script>
