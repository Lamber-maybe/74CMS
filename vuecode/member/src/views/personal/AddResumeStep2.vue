<template>
    <div class="add_group">
      <div v-if="parseInt(hasWork) === 1">
        <div class="g_title">工作经历</div>
        <div class="fill_group">
          <div class="fill_line">
            <div class="f_item">
              <div class="i_label"><span class="req">* </span>公司名称</div>
              <div class="i_input"><el-input v-model="work.companyname" placeholder="请输入公司名称"></el-input></div>
              <div class="clear"></div>
            </div>
            <div class="f_item">
              <div class="i_label"><span class="req">* </span>职位名称</div>
              <div class="i_input"><el-input v-model="work.jobname" placeholder="请输入职位名称"></el-input></div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="fill_line">
            <div class="f_item">
              <div class="i_label"><span class="req">* </span>入职时间</div>
              <div class="i_input">
                <el-date-picker
                  :style="{'width': '250px'}"
                  v-model="work.starttime"
                  type="month"
                  format="yyyy-MM"
                  value-format="yyyy-MM"
                  :picker-options="datePickerRange"
                  placeholder="请选择入职时间">
                </el-date-picker>
              </div>
              <div class="clear"></div>
            </div>
            <div class="f_item">
              <div class="i_label"><span class="req">* </span>离职时间</div>
              <div class="i_input">
                <el-date-picker
                  :style="{'width': '250px'}"
                  v-model="work.endtime"
                  :disabled="work.todate"
                  type="month"
                  format="yyyy-MM"
                  value-format="yyyy-MM"
                  :picker-options="datePickerRange"
                  placeholder="请选择离职时间">
                </el-date-picker>
                <el-checkbox class="for_after" v-model="work.todate">至今</el-checkbox>
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="fill_line">
            <div class="f_item">
              <div class="i_label"><span class="req">* </span>工作职责</div>
              <div class="i_input">
                <el-input class="w766 h190" type="textarea" v-model="work.duty" placeholder="请简要描述您此段工作期间的工作职责"></el-input>
              </div>
              <div class="clear"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="g_title">教育经历</div>
      <div class="fill_group">
        <div class="fill_line">
          <div class="f_item">
            <div class="i_label"><span class="req">* </span>学校名称</div>
            <div class="i_input"><el-input v-model="education.school" placeholder="请填写就读学校名称"></el-input></div>
            <div class="clear"></div>
          </div>
          <div class="f_item">
            <div class="i_label"><span class="req">* </span>专业名称</div>
            <div class="i_input"><el-input v-model="education.major" placeholder="请填写就读专业"></el-input></div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="fill_line">
          <div class="f_item">
            <div class="i_label"><span class="req">* </span>入学时间</div>
            <div class="i_input">
              <el-date-picker
                :style="{'width': '250px'}"
                v-model="education.starttime"
                type="month"
                format="yyyy-MM"
                value-format="yyyy-MM"
                :picker-options="datePickerRange"
                placeholder="请选择入学时间">
              </el-date-picker>
            </div>
            <div class="clear"></div>
          </div>
          <div class="f_item">
            <div class="i_label"><span class="req">* </span>毕业时间</div>
            <div class="i_input">
              <el-date-picker
                :style="{'width': '250px'}"
                v-model="education.endtime"
                :disabled="education.todate"
                type="month"
                format="yyyy-MM"
                value-format="yyyy-MM"
                :picker-options="datePickerRange"
                placeholder="请选择毕业时间">
              </el-date-picker>
              <el-checkbox class="for_after" v-model="education.todate">至今</el-checkbox>
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="fill_line">
          <div class="f_item">
            <div class="i_label"><span class="req">* </span>取得学历</div>
            <div class="i_input">
              <el-select v-model="education.education" placeholder="请选择取得学历">
                <el-option v-for="(item, index) in optionEducation"
                           :key="index"
                           :label="item.text"
                           :value="item.id"></el-option>
              </el-select>
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
      <div class="g_title">自我描述</div>
      <div class="fill_group">
        <div class="fill_line">
          <div class="f_item">
            <div class="i_label"></div>
            <div class="i_input">
              <el-input class="w766 h190" type="textarea" v-model="specialty" placeholder="你可以描述你擅长的东西，也可以简单的介绍一下，说说你的求职目标。"></el-input>
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
      <div class="fill_btn"><el-button class="w150" type="primary" @click="handleSubmit">完成</el-button></div>
    </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'

  export default {
    name: 'AddResumeStep2',
    data () {
      return {
        datePickerRange: this.dateRange(),
        hasWork: 0,
        work: {
          companyname: '',
          jobname: '',
          starttime: '',
          endtime: '',
          todate: false,
          duty: ''
        },
        education: {
          school: '',
          major: '',
          starttime: '',
          endtime: '',
          todate: false,
          education: ''
        },
        specialty: '',
        optionEducation: []
      }
    },
    mounted () {
      // 学历分类
      this.$store.dispatch('getClassify', 'education').then(() => {
        this.optionEducation = JSON.parse(JSON.stringify(this.$store.state.classifyEdu))
      })
      // 接受工作经历参数
      this.hasWork = this.$route.params.work
    },
    methods: {
      dateRange(){
        return {
          disabledDate(time){
              return time.getTime() > new Date().getTime();
          }
        }
      },
      // 提交前验证
      handleSubmit () {
        if (parseInt(this.hasWork) === 1) {
          if (!this.work.companyname) {
            this.$message({ message: '请填写公司名称', type: 'warning' })
            return false
          }
          if (!this.work.jobname) {
            this.$message({ message: '请填写职位名称', type: 'warning' })
            return false
          }
          if (!this.work.starttime) {
            this.$message({ message: '请选择入职时间', type: 'warning' })
            return false
          }
          if (!this.work.todate) {
            if (!this.work.endtime) {
              this.$message({ message: '请选择离职时间', type: 'warning' })
              return false
            }
          }
          if (!this.work.duty) {
            this.$message({ message: '请填写工作职责', type: 'warning' })
            return false
          }
        }
        if (!this.education.school) {
          this.$message({ message: '请填写学校名称', type: 'warning' })
          return false
        }
        if (!this.education.major) {
          this.$message({ message: '请填写专业名称', type: 'warning' })
          return false
        }
        if (!this.education.starttime) {
          this.$message({ message: '请选择入学时间', type: 'warning' })
          return false
        }
        if (!this.education.todate) {
          if (!this.education.endtime) {
            this.$message({ message: '请选择毕业时间', type: 'warning' })
            return false
          }
        }
        if (!this.education.education) {
          this.$message({ message: '请选择学历', type: 'warning' })
          return false
        }
        if (!this.specialty) {
          this.$message({ message: '请填写自我描述', type: 'warning' })
          return false
        }
        let post_data = {
          "education":{
            "school": this.education.school,
            "major": this.education.major,
            "starttime": this.education.starttime,
            "endtime": this.education.endtime,
            "todate": this.education.todate === true?1:0,
            "education": this.education.education
          },
          "specialty": this.specialty
        }
        if (parseInt(this.hasWork) === 1) {
            post_data.work = {
                "companyname": this.work.companyname,
                "jobname": this.work.jobname,
                "starttime": this.work.starttime,
                "endtime": this.work.endtime,
                "todate": this.work.todate === true?1:0,
                "duty": this.work.duty
              }
        }
        http.post(api.reg_resume_form_step2, post_data).then(res => {
          if (parseInt(res.code) === 200) {
            this.$message({message: res.message, type: 'success'})
            this.$router.push({path: '/personal/resume/add_resume_step3'})
          }
        }).catch(() => {
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
  .add_group {
    .el-input, .el-select { width: 250px; }
    width: 1200px; background-color: #fff; padding: 0 50px; margin-top: 15px;
    .g_title { font-size: 18px; color: #333; font-weight: 700; padding: 33px 0 12px; border-bottom: 1px solid #f3f3f3; }
    .fill_group {
      padding-top: 8px;
      .fill_line {
        display: flex; margin-top: 22px;
        .f_item {
          flex: 1;
          .i_label {
            float: left; padding: 11px 10px 11px 0; text-align: right; width: 142px;
            .req { color: #ff0000 }
          }
          .i_input {
            float: left; position: relative;
            .g_type {
              float: left; width: 76px; color: #999; padding: 11px 0 11px 39px; margin-right: 15px; border-radius: 40px;
              cursor: pointer;
              &.male {
                background: #f8f8f8 url("../../assets/images/member/4.png") 16px center no-repeat; background-size: 13px;
              }
              &.female {
                background: #f8f8f8 url("../../assets/images/member/6.png") 16px center no-repeat; background-size: 13px;
              }
              &.active {
                color: #1787fb;
                &.male {
                  background: #ebf5ff url("../../assets/images/member/5.png") 16px center no-repeat; background-size: 13px;
                }
                &.female {
                  background: #ebf5ff url("../../assets/images/member/7.png") 16px center no-repeat; background-size: 13px;
                }
              }
            }
            .for_after { margin-left: 15px; }
          }
        }
      }
    }
    .fill_btn {
      padding: 70px 0 90px; text-align: center;
    }
  }
</style>
