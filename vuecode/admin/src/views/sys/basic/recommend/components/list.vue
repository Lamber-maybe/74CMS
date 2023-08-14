<template>
  <div class="app-container">
    <el-form
      v-loading="infoLoading"
      class="common-form"
      label-width="0"
      :inline-message="true"
    >
      <el-form-item>
        <el-table border :data="list">
          <el-table-column
            label="权重项"
            align="center"
            prop="name"
          />
          <el-table-column align="center" label="权重值">
            <template slot-scope="scope">
              <el-form-item>
                <el-input
                  v-model="scope.row.value"
                  type="number"
                  style="width:120px"
                />
              </el-form-item>
            </template>
          </el-table-column>
        </el-table>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  props: ['listtype'],
  data() {
    return {
      page_tit: '',
      form_name: '',
      infoLoading: true,
      list: []
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true

      setConfig({}, 'get')
        .then(response => {
          if (this.listtype == 'job') {
            this.form_name = 'job_recommend_weight'
            this.page_tit = '职位'
            const { job_recommend_weight } = { ...response.data }
            for (const i in job_recommend_weight) {
              let name = ''
              switch (i) {
                case 'category':
                  name = '职位类别'
                  break
                case 'trade':
                  name = '行业'
                  break
                case 'wage':
                  name = '薪资'
                  break
                case 'district':
                  name = '工作地区'
                  break
                case 'nature':
                  name = '职位性质'
                  break
                case 'service_added':
                  name = '增值服务'
                  break
                case 'refreshtime':
                  name = '刷新时间'
                  break
                default:
                  name = ''
                  break
              }
              this.list.push({ name, alias: i, value: job_recommend_weight[i] })
            }
          } else {
            this.form_name = 'resume_recommend_weight'
            this.page_tit = '简历'
            const { resume_recommend_weight } = { ...response.data }
            for (const i in resume_recommend_weight) {
              let name = ''
              switch (i) {
                case 'category':
                  name = '职位类别'
                  break
                case 'trade':
                  name = '行业'
                  break
                case 'wage':
                  name = '薪资'
                  break
                case 'district':
                  name = '工作地区'
                  break
                case 'nature':
                  name = '职位性质'
                  break
                case 'service_added':
                  name = '增值服务'
                  break
                case 'refreshtime':
                  name = '刷新时间'
                  break
                case 'education':
                  name = '学历'
                  break
                case 'experience':
                  name = '工作经验'
                  break
                case 'birthyear':
                  name = '年龄要求'
                  break
                default:
                  name = ''
                  break
              }
              this.list.push({
                name,
                alias: i,
                value: resume_recommend_weight[i]
              })
            }
          }

          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const hasError = false
      const value_arr = {}
      this.list.forEach(function(val, index, arr) {
        value_arr[val.alias] = val.value
      })
      const insertData = {
        [this.form_name]: value_arr
      }
      if (hasError === true) {
        return false
      }
      setConfig(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>
