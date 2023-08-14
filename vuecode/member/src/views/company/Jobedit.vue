<template>
	<div>
		<JobForm ref="child" type="edit" @submit="submit"></JobForm>
	</div>
</template>

<script>
import JobForm from '@/components/company/JobForm'
import http from '@/utils/http'
import api from '@/api'
export default {
  components: {
    JobForm
  },
  data () {
    return {
	}
  },
  created () {
    this.fetchData(this.$route.params.id)
  },
  methods: {
    fetchData (id) {
		const loading = this.$loading({
          lock: true,
          text: 'Loading',
        });
      http
        .get(api.company_jobedit_pre, { id })
        .then(res => {
			this.$refs.child.field_rule = res.data.field_rule
			let basicInfo = res.data.basic
			let contactInfo = res.data.contact


			basicInfo.citycategory_arr = []
			basicInfo.jobcategory_arr = []

			if (basicInfo.category1 != 0) {
			basicInfo.jobcategory_arr.push(basicInfo.category1)
			}
			if (basicInfo.category2 != 0) {
			basicInfo.jobcategory_arr.push(basicInfo.category2)
			}
			if (basicInfo.category3 != 0) {
			basicInfo.jobcategory_arr.push(basicInfo.category3)
			}
			if (basicInfo.district1 != 0) {
			basicInfo.citycategory_arr.push(basicInfo.district1)
			}
			if (basicInfo.district2 != 0) {
			basicInfo.citycategory_arr.push(basicInfo.district2)
			}
			if (basicInfo.district3 != 0) {
			basicInfo.citycategory_arr.push(basicInfo.district3)
			}
			let tagIds = basicInfo.tag.toString()
			let arrData = tagIds.split(',')
			for (var i = 0; i < arrData.length; i++) {
			if (!isNaN(arrData[i])) {
				arrData[i] = parseInt(arrData[i])
			}
			}
			if (basicInfo.minwage) {
				this.$refs.child.handleMaxwageChange(basicInfo.minwage)
			}
			if (basicInfo.minage) {
				this.$refs.child.handleMaxageChange(basicInfo.minage)
			}
			if (this.$refs.child.field_rule.basic.negotiable === false) {
				basicInfo.negotiable = false
			} else {
				basicInfo.negotiable = basicInfo.negotiable == 1 ? true : false
			}
			basicInfo.age_na = basicInfo.age_na == 1 ? true : false

			basicInfo.tag = arrData
			contactInfo.mobile_show =
			contactInfo.mobile_show == 1 ? true : false
			contactInfo.telephone_show =
			contactInfo.telephone_show == 1 ? true : false


			this.$refs.child.form.basic = basicInfo
			this.$refs.child.form.contact = contactInfo
			this.$refs.child.contactHidden = contactInfo.is_display==1?false:true
			this.$refs.child.weixin_sync_mobile = contactInfo.weixin === contactInfo.mobile
			loading.close();

        })
        .catch(() => {})
    },
    submit (values) {
      http
        .post(api.company_jobedit_save, values)
        .then(res => {
          this.$message({ type: 'success', message: res.message })
          this.$router.push('/company/joblist')
        })
        .catch(() => {})
    }
  }
}
</script>


<style lang="scss" scoped>
</style>
