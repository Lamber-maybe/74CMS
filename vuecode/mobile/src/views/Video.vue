<template>
  <div id="app">
    <Head bgColor="#fff">音视频</Head>
    <div class="vi_not" v-if="room_status!=='opened'">
      <div class="not_ico"></div>
      <div class="not_tit" v-if="room_status==='overtime'">本次面试已结束，感谢您的参与！</div>
      <div class="not_tit" v-if="room_status==='nostart'">本次面试未开始，敬请期待！</div>
      <div class="not_tit" v-if="room_status==='opened'">当前浏览器不支持流畅的视频面试</div>
      <div class="not_info font12">
        <div class="in_line">
          <div class="i_left">候选人:</div>
          <div class="i_right">{{fullname}}</div>
          <div class="clear"></div>
        </div>
        <div class="in_line">
          <div class="i_left">面试时间:</div>
          <div class="i_right">
            {{interview_time}}
          </div>
          <div class="clear"></div>
        </div>
        <div class="in_line">
          <div class="i_left">面试职位:</div>
          <div class="i_right">{{jobname}}</div>
          <div class="clear"></div>
        </div>
        <div class="in_line">
          <div class="i_left">公司名称:</div>
          <div class="i_right">{{companyname}}</div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="not_rec font13">
        <div class="rec_txt">为了更好的面试体验</div>
        <div class="rec_txt last">请使用其它浏览器打开并使用</div>
        <van-button round block type="info" class="rec_copy" @click="copyUrl">
        复制面试链接(<span class="rec">推荐电脑打开</span>)
      </van-button>
      </div>
    </div>
    <Trtc
      ref="trtc"
      v-if="load_finish"
      :video="true"
      :audio="true"
      :userId="userId"
      :roomId="roomId"
      :appId="appId"
      :sig="sig"
      :jobname="jobname"
      :wage-text="wageText"
      :fullname="fullname"
      :age-text="ageText"
      :education-text="educationText"
      :experience-text="experienceText"
      :sex-text="sexText"
      :utype="$store.state.LoginType"
      @toNotice="toNotice"
    ></Trtc>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import Trtc from '@/components/Trtc.vue'
export default {
  name: 'Mapset',
  components: {
    Trtc
  },
  data () {
    return {
      interview_id: 0,
      load_finish: false,
      userId: '',
      roomId: '',
      appId: '',
      sig: '',
      fullname: '',
      interview_time: '',
      jobname: '',
      companyname: '',
      wageText: '',
      ageText: '',
      educationText: '',
      experienceText: '',
      sexText: '',
      room_status: 'opened'
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.trtc.leaveRoom()
    next()
  },
  created () {
    this.interview_id = this.$route.params.interview_id
    this.getConfig()
  },
  methods: {
    copyUrl () {
      var url = location.href
      var oInput = document.createElement('input')
      oInput.value = url
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$toast('复制成功')
    },
    getConfig () {
      http
        .post(api.rtc_config, {interview_id: this.interview_id})
        .then(res => {
          if (res.data.room_status === 'opened') {
            this.userId = res.data.userid
            this.roomId = res.data.roomid
            this.appId = res.data.appid
            this.sig = res.data.sig
            this.fullname = res.data.fullname
            this.interview_time = res.data.interview_time
            this.jobname = res.data.jobname
            this.companyname = res.data.companyname
            this.wageText = res.data.wage_text
            this.ageText = res.data.age_text
            this.educationText = res.data.education_text
            this.experienceText = res.data.experience_text
            this.sexText = res.data.sex_text
            this.load_finish = true
          }
          this.room_status = res.data.room_status
        })
        .catch(() => {})
    },
    // 发送提醒
    toNotice () {
      let noticeText = this.$store.state.LoginType === 1 ? '求职者' : '企业'
      let noticeUrl = this.$store.state.LoginType === 1 ? api.company_interview_video_notice : api.personal_manage_interview_video_notice
      this.$dialog
        .confirm({
          title: '系统提示',
          message:
            '确定提醒' + noticeText + '吗？'
        })
        .then(() => {
          http
            .post(noticeUrl, { id: this.interview_id })
            .then(() => {
              this.$toast('提醒成功')
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.vi_not { padding-top: 65px;
  .not_ico { width: 45px; height: 45px; margin: 0 auto 15px; background: url("../assets/images/vi/2.png") 0 no-repeat; background-size: 45px; }
  .not_tit { text-align: center; color: #333; margin-bottom: 35px; font-size:16px;}
  .not_info {font-size:14px; width: 320px; margin: 0 auto 20px; background: #fafafa; border-radius: 4px; padding: 15px 0;
    .in_line { margin-bottom: 10px;
      .i_left { float: left; line-height: 1.6; text-align: right; color: #999; width: 80px; }
      .i_right { float: left; line-height: 1.6; color: #333;  word-break: break-all; padding-left: 20px }
    }
  }
  .not_rec {font-size:14px; width: 320px; margin: 0 auto 20px; background: #f0f7ff; border-radius: 4px; text-align: center; padding: 19px 0 25px;
    .rec_txt { color: #333; margin-bottom: 15px;
      &.last { margin-bottom: 20px; }
    }
    .rec_copy {
      width:80%;margin:0 auto;
      .rec { color: #ffe16b; }
    }
  }

  .for_url { position: absolute; left: 0; top: 0; opacity: 0; }
}

</style>
