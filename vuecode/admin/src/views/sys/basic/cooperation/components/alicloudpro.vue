<template>
  <div class="app-container">
    <div class="tip">
      <p>
        本服务基于阿里云`号码隐私保护`服务，该服务为收费项目，具体收费标准以阿里云官方为准。[<a href="https://www.aliyun.com/product/pls?spm=5176.19720258.J_8058803260.44.e9392c4aZL2Yz4" style="color:#1E88E5" target="_blank">申请地址</a>]
        [<a href="http://doc.74cms.com/#/se/aliconceal_apply" style="color:#1E88E5" target="_blank">申请教程</a>]
      </p>
      <p>您可以自主选择使用AxB模式或AxN模式，AxB模式所需号码相对较少(拔号要求高)，AxN模式所需号码较多(体验较好)</p>
      <p>号码隐私保护开启后，全站个人简历联系将以虚拟号码呈现，同时您可以选择是否启用企业的虚拟保护。</p>
    </div>
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
    >
      <el-form-item label="开启保护">
        <el-switch v-model="form.alicloud_phone_protect_open" />
      </el-form-item>
      <el-form-item label="号码隐私模式">
        <el-radio-group v-model="form.alicloud_phone_protect_type" :disabled="!form.alicloud_phone_protect_open">
          <el-radio label="1">AxB</el-radio>
          <el-radio label="2">AxN</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="隐私保护主体">
        <el-checkbox-group v-model="form.alicloud_phone_protect_target" :disabled="!form.alicloud_phone_protect_open">
          <el-checkbox label="2" :disabled="true">个人会员</el-checkbox>
          <el-checkbox label="1">企业会员</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="号池key">
        <el-input v-if="form.alicloud_phone_protect_type==1" v-model="form.alicloud_pool_key" class="middle" :disabled="!form.alicloud_phone_protect_open" />
        <el-input v-if="form.alicloud_phone_protect_type==2" v-model="form.alicloud_pool_key_axn" class="middle" :disabled="!form.alicloud_phone_protect_open" />
      </el-form-item>
      <el-form-item label="AccessKeyId">
        <el-input v-model="form.alicloud_app_key" class="middle" :disabled="!form.alicloud_phone_protect_open" />
      </el-form-item>
      <el-form-item label="AccessKeySecret">
        <el-input v-model="form.alicloud_appsecret" class="middle" :disabled="!form.alicloud_phone_protect_open" />
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
  name: 'Alicloudpro',
  data(){
    return {
      infoLoading: true,
      form: {
        alicloud_phone_protect_open: 0,
        alicloud_phone_protect_type: '',
        alicloud_app_key: '',
        alicloud_appsecret: '',
        alicloud_pool_key: '',
        alicloud_pool_key_axn: '',
        alicloud_phone_protect_target: [2]
      }
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
          const { alicloud_phone_protect_open, alicloud_phone_protect_type, alicloud_app_key, alicloud_pool_key_axn, alicloud_appsecret, alicloud_pool_key, alicloud_phone_protect_target } = { ...response.data }
          const obj = { alicloud_phone_protect_open, alicloud_phone_protect_type, alicloud_app_key, alicloud_pool_key_axn, alicloud_appsecret, alicloud_pool_key, alicloud_phone_protect_target }
          obj.alicloud_phone_protect_target = obj.alicloud_phone_protect_target && obj.alicloud_phone_protect_target.split(',') || [2]
          obj.alicloud_phone_protect_open = !!parseInt(obj.alicloud_phone_protect_open)
          this.form = obj
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      insertData.alicloud_phone_protect_open = insertData.alicloud_phone_protect_open ? 1 : 0
      insertData.alicloud_phone_protect_target = insertData.alicloud_phone_protect_target.join(',')
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
    }
  }
}
</script>

<style scoped>

</style>
