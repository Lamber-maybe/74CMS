<template>
  <div class="invitaion_box">
    <el-form ref="form" :model="form" label-width="80px" :rules="rules" :show-message="false">
        <el-form-item label="面试形式">
            <el-radio-group v-model="form.type">
              <el-radio :label="1">面试邀请</el-radio>
              <el-radio :label="2" :disabled="mySetmeal.enable_video_interview===0?true:false">视频面试</el-radio>
            </el-radio-group>
        </el-form-item>

        <el-form-item label="面试人">
          {{apply_fullname}}
        </el-form-item>
        <el-form-item label="面试职位" v-if="from != 'apply'" prop="jobid">
          <el-select v-model="form.jobid"  placeholder="请选择职位">
            <el-option
              v-for="(item,index) in options_jobs"
              :key="index"
              :label="item.jobname"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="面试日期" prop="interview_date">
          <el-date-picker
            v-model="form.interview_date"
            type="date"
            value-format="yyyy-MM-dd"
            placeholder="请选择面试日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="面试时间" prop="interview_time">
          <el-time-select
            v-model="form.interview_time"
            :picker-options="{
              start: '08:00',
              step: '00:10',
              end: '20:00'
            }"
            placeholder="选择时间">
          </el-time-select>
        </el-form-item>
        <el-form-item v-if="form.type==1" label="面试地点" prop="address">
          <el-input v-model="form.address" placeholder="请填写面试地点"></el-input>
        </el-form-item>
        <el-form-item label="联系人" prop="contact">
          <el-input v-model="form.contact" placeholder="请填写联系人"></el-input>
        </el-form-item>
        <el-form-item label="联系电话" prop="tel">
          <el-input v-model="form.tel" type="number" placeholder="请填写联系电话"></el-input>
        </el-form-item>
        <el-form-item label="备注" prop="note">
          <el-input  v-model="form.note" placeholder="请填写备注"></el-input>
        </el-form-item>
    </el-form>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
  export default{
    props:[
      'from',
      'apply_id',
      'apply_fullname',
      'resume_id'
    ],
    data(){
      return{
        options_jobs:[],
        form: {
          type: 1,
          jobid: '',
          interview_date: '',
          interview_time: '',
          address: '',
          contact: '',
          tel: '',
          note: ''
        },
        mySetmeal: {},
        rules: {
          jobid: [
            { required: true, message: '请选择面试职位', trigger: 'change' },
          ],
          interview_date: [
            { required: true, message: '请选择面试日期', trigger: 'change' }
          ],
          interview_time: [
            {required: true, message: '请选择面试时间', trigger: 'change' }
          ],
          address: [
            {required: true, message: '请输入面试地址', trigger: 'blur' }
          ],
          contact: [
            { required: true, message: '请输入联系人', trigger: 'blur' }
          ],
          tel: [
            { required: true, message: '请输入联系电话', trigger: 'blur' }
          ]
        }
      }
    },
    methods:{
      initCB(){
        http
          .get(api.company_published_jobslist, {})
          .then(res => {
            this.options_jobs = []
            res.data.forEach(element => {
              this.options_jobs.push(element)
            })
          })
          .catch(() => {})
        this.fetchSetmeal()
      },
      fetchSetmeal () {
        http
          .get(api.member_setmeal, {})
          .then(res => {
            this.mySetmeal = res.data.info
          })
          .catch(() => {})
      }
    }
  }
</script>

<style scoped lang="scss">
  .invitaion_box{
    padding: 0 74px 0 42px;
  }
  .el-form-item {
    margin-bottom: 11px !important;
  }
  .el-select{
    width: 100%;
  }
</style>
