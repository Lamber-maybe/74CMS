<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item
        label="标题"
        prop="title"
      >
        <el-input
          v-model="form.title"
          class="middle"
        />
      </el-form-item>
      <el-form-item
        label="企业数基数"
        prop="total_company"
      >
        <el-input
          v-model="form.total_company"
          class="middle"
          @keyup.native="keyup()"
        />
      </el-form-item>
      <el-form-item
        label="岗位数基数"
        prop="total_job"
      >
        <el-input
          v-model="form.total_job"
          class="middle"
          @keyup.native="keyup()"
        />
      </el-form-item>
      <el-form-item
        label="岗位需求数基数"
        prop="total_job_amount"
      >
        <el-input
          v-model="form.total_job_amount"
          class="middle"
          @keyup.native="keyup()"
        />
      </el-form-item>
      <el-form-item
        label="简历数基数"
        prop="total_resume"
      >
        <el-input
          v-model="form.total_resume"
          class="middle"
          @keyup.native="keyup()"
        />
      </el-form-item>
      <el-form-item
        label="预览地址"
      >
        {{ previewUrl }} <el-button type="text" @click="onReset">[重置]</el-button>
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <el-button
          @click="onPreview()"
        >
          预览
        </el-button>
        <el-button
          type="warning"
          @click="onCopy()"
        >
          复制链接
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
function randomString(e) {
  e = e || 32
  var t = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'
  var a = t.length
  var n = ''
  for (let i = 0; i < e; i++) n += t.charAt(Math.floor(Math.random() * a))
  return n
}
export default {
  data() {
    return {
      infoLoading: true,
      form: {
        title: '',
        total_company: 0,
        total_job: 0,
        total_job_amount: 0,
        total_resume: 0
      },
      screen_token: '',
      sitedomain: '',
      sitedir: '',
      linkurl: '',
      rules: {
        title: [
          { required: true, message: '请输入标题', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ]
      }
    }
  },
  computed: {
    // 计算属性的 getter
    previewUrl: function () {
      // `this` 指向 vm 实例
      return this.sitedomain + this.sitedir + 'index/screen/index?token=' + this.screen_token
    }
  },
  mounted() {},
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            sitedomain,
            sitedir,
            screen_token,
            screen_base,
            screen_title
          } = { ...response.data }
          const screen_base_arr = screen_base.split(',')
          this.sitedomain = sitedomain
          this.sitedir = sitedir
          this.screen_token = screen_token
          this.form = {
            title: screen_title,
            total_company: screen_base_arr[0],
            total_job: screen_base_arr[1],
            total_job_amount: screen_base_arr[2],
            total_resume: screen_base_arr[3]
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onReset(){
      this.$confirm('重置后之前的预览地址都将失效，确定重置吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.screen_token = randomString(16)
        setConfig({ screen_token: this.screen_token })
          .then(response => {
            this.$store.dispatch('config/getConfigInfo')
            this.$message.success('重置成功')
          })
          .catch(() => {})
      }).catch(() => {
      })
    },
    onPreview(){
      window.open(this.previewUrl)
    },
    onCopy(){
      const that = this
      const copyMessage = this.previewUrl
      this.$copyText(copyMessage).then(function (e) {
        that.$message.success('链接已复制到剪切板！')
      }, function (e) {
        that.$message.error('抱歉，复制失败！')
      })
    },
    onSubmit(formName) {
      const insertData = {
        screen_title: this.form.title,
        screen_base: [this.form.total_company == '' ? 0 : this.form.total_company, this.form.total_job == '' ? 0 : this.form.total_job, this.form.total_job_amount == '' ? 0 : this.form.total_job_amount, this.form.total_resume == '' ? 0 : this.form.total_resume].join(',')
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    keyup(){
      if (this.form.total_company == ''){
        this.form.total_company = 0
      }
      if (this.form.total_job == ''){
        this.form.total_job = 0
      }
      if (this.form.total_job_amount == ''){
        this.form.total_job_amount = 0
      }
      if (this.form.total_resume == ''){
        this.form.total_resume = 0
      }
    }
  }
}
</script>

