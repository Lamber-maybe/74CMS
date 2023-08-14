<template>
  <div class="app-container">
    <el-card v-loading="loading">
      <div slot="header" class="clearfix">
        <span>套餐权限同步</span>
      </div>
      <div class="tip">
        <p>本工具操作不当会导致严重后果，使用前请注意数据备份，执行操作不可逆，请谨慎使用！</p>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="20%"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item
          label="功能使用场景说明"
        >
          将所选套餐的权限内容同步到站同相同套餐的所有企业。
        </el-form-item>
        <el-form-item
          label="选择要同步的套餐"
          prop="setmeal_id"
        >
          <el-radio-group v-model.number="form.setmeal_id" @change="changeSetmeal">
            <el-radio v-for="it in setMealList" v-if="it.is_display>0" :label="it.id" :key="it.id"><span v-text="it.name"></span></el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          label="套餐权限一览"
          v-if="form.setmeal_id>0"
        >
          <el-card>
            <el-row>
              <el-col :span="24">
                职位并发数: <span class="val" v-html="detail.jobs_meanwhile"></span>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="8">
                免费刷新职位: <span class="val" v-html="detail.refresh_jobs_free_perday"></span> 次/天
              </el-col>
              <el-col :span="8">
                下载简历上限: <span class="val" v-html="detail.download_resume_max_perday"></span> 份/天
              </el-col>

              <el-col :span="8">
                套餐增值包折扣: <span class="val" v-html="detail.service_added_discount"></span> 折
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="8">
                使用微海报: <span class="val" v-if="enable_poster">允许</span><span class="val" v-else>不允许</span>
              </el-col>
              <el-col :span="8">
                收到简历免费查看: <span class="val" v-if="show_apply_contact">允许</span><span class="val" v-else>不允许</span>
              </el-col>
              <el-col :span="8">
                使用视频面试: <span class="val" v-if="enable_video_interview">允许</span><span class="val" v-else>不允许</span>
              </el-col>
            </el-row>
          </el-card>
        </el-form-item>
        <el-form-item
          label="注意事项"
        >
           1.操作执行成功后数据不可恢复,请提前备份好数据;<br/>
            2.套餐权限指您的套餐配置信息，如需修改请在[企业业务配置-->套餐管理]处修改对应套餐;
            立即同步

        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            @click="onSubmitPre"
          >
            提交同步
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
    <el-dialog title="请输入密码确认" :visible.sync="enterPwdShow" width="350px">
      <el-row>
        <el-col :span="18"> <el-input v-model="form.pwd" type="password" placeholder="请输入密码"></el-input> </el-col>
        <el-col :span="6"> <el-button type="primary" @click="onSubmit">确定</el-button></el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import { setmealList } from '@/api/setmeal'
import request from '@/utils/request'
export default {
  data() {
    return {
      loading: true,
      setMealList: [],
      enable_video_interview: false,
      show_apply_contact: false,
      enable_poster: false,
      enterPwdShow: false,
      form: {
        setmeal_id: '',
        pwd: ''
      },
      rules: {
        setmeal_id: [
          {
            required: true,
            message: '请选择套餐',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {
    setmealList({})
      .then(response => {
        this.setMealList = response.data.items
        this.loading = false
      })
  },
  computed: {
    detail () {
      let r = {}
      this.setMealList.forEach((item, i) => {
        if(item.id == this.form.setmeal_id){
          r = item
        }
      })
      return r
    }
  },
  methods: {
    changeSetmeal () {
      this.enable_video_interview = !!parseInt(this.detail.enable_video_interview)
      this.show_apply_contact = !!parseInt(this.detail.show_apply_contact)
      this.enable_poster = !!parseInt(this.detail.enable_poster)
    },
    onSubmitPre () {
      if(!this.form.setmeal_id){
        this.$message.error('请选择要同步的套餐')
        return
      }
      this.form.pwd = ''
      this.enterPwdShow = true
    },
    onSubmit() {
      if(this.form.pwd.length == 0){
        this.$message.error('密码不能为空')
        return false
      }

      let data = Object.assign({}, this.form)
      request({url: '/member/syncSetmeal', method:'post', data}).then((res) => {
        this.enterPwdShow = false
        this.$message.success(res.message)
      })
    }
  }
}
</script>
<style lang="scss" scoped>
  .el-radio{
    margin-top: 10px;
  }
  .val{
    color: #e29417;
  }
</style>
