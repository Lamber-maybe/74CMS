<template>
  <div class="body">
    <Head>编辑套餐</Head>
    <van-field
      v-model="companyname"
      label="公司名称"
      placeholder="请输入"
      input-align="right"
      :colon="true"
      readonly
      class="reset_after"
    />
    <van-field
      v-model="deadline"
      label="服务时间"
      placeholder="服务时间"
      input-align="right"
      :colon="true"
      readonly
      class="reset_after"
    />
    <van-field
      readonly
      clickable
      :value="form.deadline"
      input-align="right"
      :colon="true"
      label="到期时间"
      placeholder="请选择"
      @click="showPickerEndDate = !showPickerEndDate"
      class="form_choose reset_after "
    />
    <van-popup v-model="showPickerEndDate" position="bottom">
      <van-datetime-picker
        v-model="currentEndDate"
        type="date"
        title=""
        :min-date="minDate"
        @confirm="onConfirmEndDate"
        @cancel="showPickerEndDate = false"
      />
    </van-popup>
    <van-field
      v-model.number="form.days"
      label="服务天数"
      placeholder="0不改变套餐时间,3延长3天"
      input-align="right"
      :colon="true"
      type="digit"
      class="reset_after "
    />
    <van-field
      v-model.number="form.jobs_meanwhile"
      label="同时在招职位数"
      placeholder="请输入"
      input-align="right"
      :colon="true"
      type="digit"
      class="reset_after big_left"
    />
    <van-field
      v-model.number="form.refresh_jobs_free_perday"
      label="免费职位刷新(次/天)"
      placeholder="请输入"
      input-align="right"
      :colon="true"
      type="digit"
      class="reset_after big_left"
    />
    <van-field
      v-model.number="form.download_resume_point"
      label="下载简历点数"
      placeholder="请输入"
      input-align="right"
      :colon="true"
      type="digit"
      class="reset_after big_left"
    />
    <van-field
      v-model.number="form.download_resume_max_perday"
      label="下载简历上限(份/天)"
      placeholder="请输入"
      input-align="right"
      :colon="true"
      type="digit"
      class="reset_after big_left"
    />
    <van-field
      v-model.number="form.im_total"
      label="职聊次数"
      placeholder="请输入"
      input-align="right"
      :colon="true"
      type="digit"
      class="reset_after big_left"
    />
    <van-field
      v-model.number="form.im_max_perday"
      label="允许发起聊天数(次/天)"
      placeholder="请输入"
      input-align="right"
      :colon="true"
      type="digit"
      class="reset_after big_300_left"
    />
    <van-field
      readonly
      clickable
      :value="show_apply_contact_cn"
      label="收到简历联系方式"
      placeholder="请选择"
      input-align="right"
      :colon="true"
      class="form_choose reset_after big_left"
      @click="showActions=true"
    />
    <van-field
        label="微海报"
        class="reset_after"
    />
    <div class="for_btn">
      <van-switch
          class="self_switch"
          v-model="form.enable_poster"
          size="18px"
          active-color="#0088fc"
      />
    </div>
    <van-field
        label="视频面试"
        class="reset_after"
    />
    <div class="for_btn">
      <van-switch
          class="self_switch"
          v-model="form.enable_video_interview"
          size="18px"
          active-color="#0088fc"
      />
    </div>
    <van-field
        label="是否收费"
        class="reset_after"
    />
    <div class="for_btn">
      <van-switch
          class="self_switch"
          v-model="form.is_charge"
          size="18px"
          active-color="#0088fc"
      />
    </div>
    <van-field
        v-if="form.is_charge===true"
        v-model.number="form.charge_val"
        label="收费金额"
        placeholder="请输入收费金额"
        input-align="right"
        :colon="true"
        class="reset_after"
    />
    <van-field
        readonly
        clickable
        label="其他说明"
        :colon="true"
        input-align="right"
        class="reset_after no_border"
    />
    <div class="box_1">
      <van-field
          style="background-color:#f9f9f9;"
          v-model="form.explain"
          rows="5"
          autosize
          label=""
          type="textarea"
          placeholder="请输入其他说明"
      />
    </div>
    <div class="bottom clear">
      <div class="l_btn" @click="onSubmit">修改套餐</div>
      <div class="r_btn" @click="$router.go(-1)">取消</div>
    </div>
    <van-action-sheet
      v-model="showActions"
      :actions="ActionsList"
      cancel-text="取消"
      close-on-click-action
      @cancel="showActions=false"
      @select="doAction"
    />
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  export default {
    name: "setmealEdit",
    data () {
      return {
        showActions:false,
        ActionsList: [{name:'免费查看',value:1},{name:'需下载',value:0}],
        uid:0,
        form:{},
        companyname: '江西航天云网科技有限公司',
        deadline:'',
        show_apply_contact_cn:'',
        points: 50,
        pointsCn: '增加',
        pointsNum: '',
        isPay: false,
        content: '',
        endtime: '',
        currentEndDate: '',
        showPickerEndDate: false,
        minDate: '',
        endtimeName: ''
      }
    },
    created() {
      this.uid = parseInt(this.$route.params.uid)
      this.companyname = this.$route.query.companyname
      this.fetchData()
    },
    methods: {
      fetchData () {
          http.get(api.companySetmealEdit, {uid:this.uid})
          .then(res => {
            this.form = {
              uid: this.uid,
              days: '',
              deadline: "",
              jobs_meanwhile: res.data.jobs_meanwhile,
              refresh_jobs_free_perday: res.data.refresh_jobs_free_perday,
              download_resume_point: res.data.download_resume_point,
              download_resume_max_perday: res.data.download_resume_max_perday,
              enable_video_interview: res.data.enable_video_interview==1?true:false,
              enable_poster: res.data.enable_poster==1?true:false,
              show_apply_contact:res.data.show_apply_contact,
              im_total:res.data.im_total,
              im_max_perday:res.data.im_max_perday,
              explain: "",
              is_charge: 0,
              charge_val: 0
            }
            this.show_apply_contact_cn = this.form.show_apply_contact==1?'免费查看':'需下载'
            this.deadline = res.data.deadline==''?'无限期':res.data.deadline
            this.initDate()
          })
          .catch(() => {})
      },
      onSubmit(){
        let data = JSON.parse(JSON.stringify(this.form))
        data.enable_video_interview = data.enable_video_interview===true?1:0
        data.enable_poster = data.enable_poster===true?1:0
        data.days = data.days===''?0:data.days

        http.post(api.companySetmealEdit, data)
          .then(res => {
              this.$toast.success(res.message)
              this.$router.go(-1)
          })
          .catch(() => {})
      },
      onConfirmEndDate (value) {
        this.endtime = this.dateFormat(value)
        this.endtimeName = this.dateFormat(value)
        this.showPickerEndDate = !this.showPickerEndDate
        this.form.deadline = this.dateFormat(value)
      },
      dateFormat (time) {
        let year = time.getFullYear()
        let month = time.getMonth() + 1
        let day = time.getDate()
        return `${year}-${month}-${day}`
      },
      initDate () {
        let nowDate = new Date()
        let nowYear = nowDate.getFullYear()
        this.minDate = new Date(nowYear, 0)
        this.currentEndDate = this.deadline=='无限期'?nowDate:(new Date(this.deadline+' 00:00:00'))

      },
      doAction(action){
        this.form.show_apply_contact = action.value
        this.show_apply_contact_cn = this.form.show_apply_contact==1?'免费查看':'需下载'
        this.showActions = false
      },
    }
  }
</script>

<style lang="scss" scoped>
  .body { background-color: #ffffff;position: absolute;top: 0;left: 0;overflow-y: auto;width: 100%;height: 100%; }
  .self_switch {
    position: absolute;
    right: 30px;
    top: -70px;
  }
  .box_1 {
    textarea {
      width: 100%;
      height: 260px;
      border: 0;
      background-color: #f9f9f9;
      font-size: 28px;
      resize: none;
      padding: 16px 30px;
      line-height: 1.8;
      &::placeholder {
        color: #c9c9c9;
      }
    }
    width: 100%;
    background-color: #ffffff;
    position: relative;
    padding: 0 40px 40px 40px;margin-bottom: 132px;
  }
  .bottom {
    position: fixed;left: 0;bottom: 0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 20px 30px;
    background-color: #ffffff;
    .l_btn {
      float: left;width: 490px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #1787fb;
    }
    .r_btn {
      float: right;width: 180px;height: 90px;line-height: 90px;text-align: center;border-radius: 8px;font-size: 30px;
      color: #ffffff;background-color: #f56c6c;
    }
  }
</style>
