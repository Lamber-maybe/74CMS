<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="选择企业" prop="company_id">
        <el-select
          v-model="form.company_id"
          filterable
          remote
          reserve-keyword
          placeholder="请输入会员UID/手机号/企业名称"
          :remote-method="companySearch"
          :loading="search_loading"
          class="large"
        >
          <el-option
            v-for="item in options_companylist"
            :key="item.id"
            :label="
              '【企业名称：' +
                item.companyname +
                '】 / 【ID：' +
                item.id +
                '】'
            "
            :value="item.id"
            :disabled="item.has_job==1?false:true"
          >
            <span style="float: left">
              【企业名称：{{ item.companyname }}】 / 【ID：{{ item.id }}】<font v-if="item.has_job==0" color="red">无职位</font>
            </span>
          </el-option>
        </el-select>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { marketingWxoffiaccountSearchCompany } from '@/api/marketing_wxoffiaccount'
export default {
  data() {
    return {
      options_companylist: [],
      form: {
        company_id: ''
      },
      search_loading: false,
      rules: {}
    }
  },
  created() {
  },
  methods: {
    companySearch(query) {
      if (query !== '') {
        this.search_loading = true
        marketingWxoffiaccountSearchCompany({ keyword: query })
          .then(response => {
            this.options_companylist = response.data.items
            this.search_loading = false
          })
          .catch(() => {})
      } else {
        this.options = []
      }
    }
  }
}
</script>
<style scoped>
.large {
  width: 450px;
}
.el-checkbox-group {
  display: inline;
}
</style>
