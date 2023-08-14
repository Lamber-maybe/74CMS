<template>
  <div
    v-loading.fullscreen.lock="fullscreenLoading"
    class="app-container"
    :element-loading-text="fullscreenLoadingText"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>基本配置</span>
      </div>
      <div class="tip">
        <p>
          74IM是国内首家专业的即时通讯云服务提供商，专注为互联网、移动互联网开发者提供即时通讯基础能力和云端服务
          <a
            href="http://imcenter.74cms.com/"
            target="_blank"
            style="color: rgb(30, 136, 229);"
          >
            马上申请
          </a>
        </p>
        <p>如果您的会员无法正常使用即时聊天功能或您已更换app_key等信息，请使用下方同步数据功能</p>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="app_key">
          <el-input v-model="form.app_key" />
        </el-form-item>
        <el-form-item label="app_secret">
          <el-input v-model="form.app_secret" />
        </el-form-item>
        <el-form-item label="接口地址">
          <el-input v-model="im_server" />
        </el-form-item>
        <el-form-item label="websocket地址">
          <el-input v-model="im_websocket" />
        </el-form-item>
        <el-form-item label="">
          <el-button
            type="primary"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
          <el-button
            @click="confirmSync"
          >
            同步数据
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
import { syncImTokenBatch } from '@/api/im'

export default {
  data() {
    return {
      fullscreenLoading: false,
      fullscreenLoadingText: '正在同步数据...',
      infoLoading: false,
      form: {
        app_key: '',
        app_secret: ''
      },
      im_server: '',
      im_websocket: '',
      rules: {}
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const { account_im, im_server, im_websocket } = { ...response.data }
          this.form = account_im
          this.im_server = im_server
          this.im_websocket = im_websocket
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        account_im: { ...this.form },
        im_server: this.im_server,
        im_websocket: this.im_websocket
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    confirmSync(){
      this.$confirm('同步数据会先清空用户token数据，中途无法取消，请不要中途刷新页面，否则将引发不可预估的错误。<br>确定同步吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
        dangerouslyUseHTMLString: true
      }).then(() => {
        this.sync(1)
      }).catch(() => {

      })
    },
    sync(page){
      const that = this
      const param = {}
      param.page = page
      param.pagesize = 10
      that.fullscreenLoading = true
      that.fullscreenLoadingText = '正在同步数据...（已完成 ' + (page - 1) * param.pagesize + ' 条）'
      syncImTokenBatch(param)
        .then(response => {
          if (response.data.finish === 0){
            page = page + 1
            that.sync(page)
          } else {
            that.fullscreenLoading = false
            that.$message({ type: 'success', message: '同步成功' })
            return true
          }
        })
        .catch(() => {})
    }
  }
}
</script>
