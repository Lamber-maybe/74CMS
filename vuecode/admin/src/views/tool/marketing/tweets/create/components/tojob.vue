<template>
  <div>
    <el-form
      class="common-form"
      label-width="100px"
    >
      <el-form-item label="指定职位" prop="company_id">
        <el-select
          v-model="job_id"
          filterable
          remote
          reserve-keyword
          placeholder="请输入职位ID/职位名称"
          :remote-method="jobSearch"
          :loading="search_loading"
          class="large"
        >
          <el-option
            v-for="item in options_joblist"
            :key="item.id"
            :value="item"
          >
            <span style="float: left">
              【职位名称：{{ item.jobname }}】 / 【ID：{{ item.id }}】<font color="red">【所属企业：{{ item.companyname }}】</font>
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
          {{ item.jobname }}
        </el-tag>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { tweetsSearchJob } from '@/api/tweets_template'
export default {
  data() {
    return {
      options_joblist: [],
      submitTag: [],
      job_id: '',
      loading: true,
      search_loading: false,
      rules: {}
    }
  },
  watch: {
    job_id: function (newVal, oldVal) {
      if (newVal !== undefined){
        this.addJobs()
      }
    }
  },
  created() {
  },
  methods: {
    addJobs() {
      if (this.job_id){
        const index = this.submitTag.indexOf(this.job_id)
        if (index == -1){
          this.submitTag.push(this.job_id)
          this.job_id = ''
          this.options_joblist = []
        } else {
          this.$message.success('已存在指定职位列表中')
        }
      }
    },
    delJobs(item) {
      const index = this.submitTag.indexOf(item)
      if (index != -1){
        this.submitTag.splice(index, 1)
      }
    },
    jobSearch(query) {
      if (query !== '') {
        this.search_loading = true
        tweetsSearchJob({ keyword: query })
          .then(response => {
            this.options_joblist = response.data.items
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
