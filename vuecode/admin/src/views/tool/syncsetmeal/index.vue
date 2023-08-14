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
          将所选套餐的权限内容同步到站内相同套餐的所有企业。
        </el-form-item>
        <el-form-item
          label="选择要同步的套餐"
          prop="setmeal_id"
        >
          <el-radio-group v-model.number="form.setmeal_id" @change="changeSetmeal">
            <el-radio v-for="it in setMealList" v-if="it.is_display>0" :key="it.id" :label="it.id"><span v-text="it.name" /></el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="form.setmeal_id>0"
          label="选择要同步的项目"
        >
          <el-checkbox-group v-model="form.sync_item">
            <el-row>
              <el-col :span="8">
                <el-checkbox label="download_resume_point">简历下载点数: <span class="val" v-html="detail.download_resume_point" /></el-checkbox>
              </el-col>
              <el-col :span="8">
                <el-checkbox label="gift_point">积分点数: <span class="val" v-html="detail.gift_point" /></el-checkbox>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="8">
                <el-checkbox label="im_total">职聊次数: <span class="val" v-html="detail.im_total" /></el-checkbox>
              </el-col>
              <el-col :span="8">
                <el-checkbox label="refresh_jobs_free_perday">免费刷新职位: <span class="val" v-html="detail.refresh_jobs_free_perday" /> 次/天</el-checkbox>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="8">
                <el-checkbox label="jobs_meanwhile">同时在招职位数: <span class="val" v-html="detail.jobs_meanwhile" /></el-checkbox>
              </el-col>
              <el-col :span="8">
                <el-checkbox label="im_max_perday">允许发起聊天: <span class="val" v-html="detail.im_max_perday" /> 次/天</el-checkbox>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="8">
                <el-checkbox label="enable_video_interview">使用视频面试: <span v-if="enable_video_interview" class="val">允许</span><span v-else class="val">不允许</span></el-checkbox>
              </el-col>
              <el-col :span="8">
                <el-checkbox v-if="detail.show_apply_contact === 0" label="enable_video_interview">
                  收到简历免费查看:
                  <span class="val">不允许</span>
                </el-checkbox>
                <el-checkbox v-else-if="detail.resume_view_num === 0" label="im_max_perday">收到简历免费查看: <span class="val">不限</span></el-checkbox>
                <el-checkbox v-else label="im_max_perday">收到简历免费查看: <span class="val" v-html="detail.resume_view_num" /> 份/天</el-checkbox>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="8">
                <el-checkbox label="download_resume_max_perday">下载简历上限: <span class="val" v-html="detail.download_resume_max_perday" /> 份/天</el-checkbox>
              </el-col>
              <el-col :span="8">
                <el-checkbox label="service_added_discount">套餐增值包折扣: <span class="val" v-html="detail.service_added_discount" /> 折</el-checkbox>
              </el-col>
            </el-row>
          </el-checkbox-group>
          <!--          <el-card>-->
          <!--            <el-row>-->
          <!--              <el-col :span="24">-->
          <!--                职位并发数: <span class="val" v-html="detail.jobs_meanwhile" />-->
          <!--              </el-col>-->
          <!--            </el-row>-->
          <!--            <el-row>-->
          <!--              <el-col :span="8">-->
          <!--                免费刷新职位: <span class="val" v-html="detail.refresh_jobs_free_perday" /> 次/天-->
          <!--              </el-col>-->
          <!--              <el-col :span="8">-->
          <!--                下载简历上限: <span class="val" v-html="detail.download_resume_max_perday" /> 份/天-->
          <!--              </el-col>-->

          <!--              <el-col :span="8">-->
          <!--                套餐增值包折扣: <span class="val" v-html="detail.service_added_discount" /> 折-->
          <!--              </el-col>-->
          <!--            </el-row>-->
          <!--            <el-row>-->
          <!--              <el-col :span="8">-->
          <!--                使用微海报: <span v-if="enable_poster" class="val">允许</span><span v-else class="val">不允许</span>-->
          <!--              </el-col>-->
          <!--              <el-col :span="8">-->
          <!--                收到简历免费查看: <span v-if="show_apply_contact" class="val">允许</span><span v-else class="val">不允许</span>-->
          <!--              </el-col>-->
          <!--              <el-col :span="8">-->
          <!--                使用视频面试: <span v-if="enable_video_interview" class="val">允许</span><span v-else class="val">不允许</span>-->
          <!--              </el-col>-->
          <!--            </el-row>-->
          <!--          </el-card>-->
        </el-form-item>
        <el-form-item
          label="注意事项"
        >
          1.操作执行成功后数据不可恢复,请提前备份好数据;<br>
          2.套餐权限指您的套餐配置信息，如需修改请在[企业业务配置-->套餐管理]处修改对应套餐;

        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            @click="dialogVisiblefun"
          >
            提交同步
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
    <el-dialog
      :visible.sync="dialogVisible"
      width="45%"
    >
      <div class="title">
        操作提示
      </div>
      <p class="text">1.修改前请注意原始数据备份，此操作不可逆，操作完成后无法还原!</p>
      <p class="text">2.同步指把当前套餐的内容项目同步到当前套餐的企业会员(已过期企业除外)，同步操作不会变更企业的套餐有效期。</p>
      <p class="text">3.请确认您要同步的套餐类型及项目，勾选表示要同步当前套餐的此项目;如果勾选项目<b> 简历下载点、积分、聊天次数</b>，同步操作将清空企业原有的简历下载点、积分、聊天次数，并把套餐配置好的资源重新分配给企业。</p>
      <p class="text">4.使用同步功能仅限超级管理员(角色)使用，同步需输入对应账号密码验证。</p>
      <p class="text">5.如非必要场景请勿使用本工具，操作不当会导致严重后果。如无备份文件我们无法协助您恢复。</p>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" :disabled="startTime" @click="onSubmitPre">我已同意免责声明，开始下一步{{ secondText }}</el-button>
      </div>
    </el-dialog>
    <el-dialog title="验证密码" :visible.sync="enterPwdShow" width="350px">
      <el-row>
        <el-col :span="18"> <el-input v-model="form.pwd" type="password" placeholder="请输入当前管理员密码" /> </el-col>
        <el-col :span="6"> <el-button type="primary" @click="onSubmit">确定</el-button></el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import { sysToolSyncSetmeal } from '@/api/systool'
import { setmealList } from '@/api/setmeal'
export default {
  data() {
    return {
      loading: true,
      setMealList: [],
      is_administrator: 0,
      enable_video_interview: false,
      show_apply_contact: false,
      enterPwdShow: false,
      dialogVisible: false,
      timer: '',
      second: 15,
      secondText: '（15s）',
      startTime: true,
      form: {
        setmeal_id: '',
        pwd: '',
        sync_item: []
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
  computed: {
    detail () {
      let r = {}
      this.setMealList.forEach((item, i) => {
        if (item.id == this.form.setmeal_id){
          r = item
        }
      })
      return r
    }
  },
  created() {
    setmealList({})
      .then(response => {
        this.setMealList = response.data.items
        this.loading = false
        this.is_administrator = response.data.is_administrator
      })
  },
  methods: {
    changeSetmeal () {
      this.enable_video_interview = !!parseInt(this.detail.enable_video_interview)
      this.show_apply_contact = !!parseInt(this.detail.show_apply_contact)
    },
    dialogVisiblefun(){
      // 判断不是超管时，不允许操作 chenyang 2022年3月9日18:50:15
      if (this.is_administrator != 1) {
        this.$message.error('只有超级管理员才有权限执行此操作')
        return false
      }
      if (!this.form.setmeal_id){
        this.$message.error('请选择要同步的套餐')
        return false
      }
      if (this.form.sync_item.length <= 0){
        this.$message.error('请选择要同步的项目')
        return false
      }
      this.dialogVisible = true
      this.startTime = true
      this.second = 15
      this.secondText = '（15s）'
      this.timer = setInterval(this.getSecond, 1000)
    },
    getSecond() {
      if (this.second > 1) {
        this.second--
        this.secondText = `（${this.second}s）`
      } else {
        this.secondText = ''
        this.startTime = false
        clearInterval(this.timer)
      }
    },
    onSubmitPre () {
      this.dialogVisible = false
      this.form.pwd = ''
      this.enterPwdShow = true
    },
    onSubmit() {
      if (this.form.pwd.length == 0){
        this.$message.error('密码不能为空')
        return false
      }

      const data = Object.assign({}, this.form)
      sysToolSyncSetmeal(data)
        .then(res => {
          this.enterPwdShow = false
          this.$message.success(res.message)
          return true
        })
        .catch(() => {})
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
  .title{
    text-align: center;
    line-height: 40px;
    font-size: 24px;
    font-weight: 400;
  }
  .text{
    font-size: 16px;
    line-height: 25px;
    margin: 10px 0;
    padding: 0;
  }
  .dialog-footer{
    text-align: center;
  }
</style>
