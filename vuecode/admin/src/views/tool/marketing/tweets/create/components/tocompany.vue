<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      label-width="100px"
    >
      <el-form-item label="指定企业" prop="company_id">
        <el-select
          v-model="company_id"
          filterable
          remote
          reserve-keyword
          placeholder="请输入企业ID/企业名称"
          :remote-method="companySearch"
          :loading="search_loading"
          class="large"
        >
          <el-option
            v-for="item in options_companylist"
            :key="item.id"
            :value="item"
            :disabled="item.has_job==1?false:true"
          >
            <span style="float: left">
              【企业名称：{{ item.companyname }}】 / 【ID：{{ item.id }}】<font color="red">【职位数：{{ item.has_job_num }}】</font>
            </span>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" style="margin-bottom:20px;">
        <el-tag
          v-for="(item,index) in submitTag"
          :key="index"
          closable
          :disable-transitions="false"
          style="margin:5px;float:left;"
          @close="delJobs(item)"
        >
          {{ item.companyname }}
        </el-tag>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { tweetsSearchCompany } from '@/api/tweets_template'
export default {
  data() {
    return {
      options_companylist: [],
      submitTag: [],
      company_id: '',
      search_loading: false,
      rules: {}
    }
  },
  watch: {
    company_id: function (newVal, oldVal) {
      if (newVal !== undefined){
        this.addJobs()
      }
    }
  },
  created() {
  },
  methods: {
    addJobs() {
      if (this.submitTag.length > 0){
        this.$message.error('仅可选择一个企业')
      } else {
        if (this.company_id){
          const index = this.submitTag.indexOf(this.company_id)
          if (index == -1){
            this.submitTag.push(this.company_id)
            this.options_companylist = []
          } else {
            this.$message.success('已存在指定企业列表中')
          }
        } else {
          this.$message.error('请选择企业')
        }
      }
    },
    delJobs(item) {
      const index = this.submitTag.indexOf(item)
      if (index != -1){
        this.submitTag.splice(index, 1)
      }
    },
    companySearch(query) {
      if (query !== '') {
        this.search_loading = true
        tweetsSearchCompany({ keyword: query })
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
  width: 308px;
}
</style>
