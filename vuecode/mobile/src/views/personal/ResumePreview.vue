<template>
  <div id="app">
    <Head>简历预览</Head>
    <div class="content_wrapper">
      <div class="box_2">
        <div class="name_group">
          <div class="name">
            <div class="text">{{ basic.fullname }}</div>
            <div
              class="qu_ico"
              v-if="parseInt(basic.high_quality) === 1"
            ></div>
            <div class="clear"></div>
          </div>
          <div class="tx1">{{ basic.current_text }}</div>
          <div class="info">
            {{ basic.age }}岁 · {{ basic.experience_text }} ·
            {{ basic.education_text }}
          </div>
          <div
            :class="parseInt(basic.sex) === 1 ? 'avatar_box' : 'avatar_box fe'"
          >
            <img :src="basic.photo_img_src" :alt="basic.fullname" />
          </div>
        </div>
        <div class="tx2">
          {{ dateFormatFull(basic.refreshtime, true) }} 更新
          <div class="right_txt" @click="showDetail = !showDetail">更多基本信息</div>
        </div>
      </div>
    </div>
    <div class="form_split_10"></div>
    <div class="content_wrapper">
      <!--求职意向-->
      <div class="box_3" v-if="intentionList.length">
        <div class="box_head"><div class="txt">求职意向</div></div>
        <div class="box_content">
          <div class="tx2" v-for="(item, index) in intentionList" :key="index">
            <div class="name">
              [{{ item.district_text }}] {{ item.category_text }}
            </div>
            <div class="intent">
              {{ item.wage_text }}，{{ item.nature_text }}
              {{ fieldStore.intention.trade.is_display === 1 && item.trade_text !== '' ? `，${item.trade_text}` : '' }}
            </div>
          </div>
        </div>
      </div>
      <!--联系方式-->
      <div class="box_cac">
        <div class="box_head"><div class="txt">联系方式</div></div>
        <div class="box_content">
          <div class="item phone" v-if="contact.mobile !== ''">
            手机：{{ contact.mobile }}
          </div>
          <div class="item wx" v-if="contact.weixin !== ''">
            微信：{{ contact.weixin }}
          </div>
          <div class="item email" v-if="contact.email !== ''">
            邮箱：{{ contact.email }}
          </div>
          <div class="item qq" v-if="contact.email !== ''">
            QQ：{{ contact.email }}
          </div>
        </div>
      </div>
      <!--自我描述-->
      <div class="box_5" v-if="basic.tag_text_arr.length || basic.specialty">
        <div class="box_head"><div class="txt">自我描述</div></div>
        <div class="box_content">
          <!--特长标签-->
          <div class="tag" v-if="basic.tag_text_arr.length">
            <div
              class="tag-item"
              v-for="(item, index) in basic.tag_text_arr"
              :key="index"
            >
              {{ item }}
            </div>
            <div class="clear"></div>
          </div>
          <div class="dec" v-if="basic.specialty">{{ basic.specialty }}</div>
        </div>
      </div>
      <!--教育经历-->
      <div class="box_6" v-if="educationList.length">
        <div class="box_head"><div class="txt">教育经历</div></div>
        <div class="box_content">
          <div class="tx1" v-for="(item, index) in educationList" :key="index">
            <div class="t1">{{ item.school }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : '至今'
              }}
            </div>
            <div class="t3" v-if="fieldStore.education.major.is_display === 1">{{ item.major }}</div>
          </div>
        </div>
      </div>
      <!--工作经历-->
      <div class="box_7" v-if="workList.length">
        <div class="box_head"><div class="txt">工作经历</div></div>
        <div class="box_content">
          <div class="tx1" v-for="(item, index) in workList" :key="index">
            <div class="t1">{{ item.companyname }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : '至今'
              }}
            </div>
            <div class="t3">{{ item.jobname }}</div>
            <div class="t4">{{ item.duty }}</div>
          </div>
        </div>
      </div>
      <!--培训经历-->
      <div class="box_8" v-if="trainingList.length">
        <div class="box_head"><div class="txt">培训经历</div></div>
        <div class="box_content">
          <div class="tx1" v-for="(item, index) in trainingList" :key="index">
            <div class="t1">{{ item.agency }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : '至今'
              }}
            </div>
            <div class="t3">{{ item.course }}</div>
            <div class="t4">{{ item.description }}</div>
          </div>
        </div>
      </div>
      <!--项目经历-->
      <div class="box_9" v-if="projectList.length">
        <div class="box_head"><div class="txt">项目经历</div></div>
        <div class="box_content">
          <div class="tx1" v-for="(item, index) in projectList" :key="index">
            <div class="t1">{{ item.projectname }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : '至今'
              }}
            </div>
            <div class="t3">{{ item.role }}</div>
            <div class="t4">{{ item.description }}</div>
          </div>
        </div>
      </div>
      <!--获得证书-->
      <div class="box_10" v-if="certificateList.length">
        <div class="box_head"><div class="txt">获得证书</div></div>
        <div class="box_content">
          <div
            class="tx1"
            v-for="(item, index) in certificateList"
            :key="index"
          >
            {{ item.name }}
            <div class="right_txt">{{ dateFormat(item.obtaintime) }}</div>
          </div>
        </div>
      </div>
      <!--语言能力-->
      <div class="box_11" v-if="languageList.length">
        <div class="box_head"><div class="txt">语言能力</div></div>
        <div class="box_content">
          <div class="tx1" v-for="(item, index) in languageList" :key="index">
            {{ item.language_text }}，{{ item.level_text }}
          </div>
        </div>
      </div>
      <!--我的作品-->
      <div class="box_12">
        <div class="box_head"><div class="txt">我的作品</div></div>
        <div class="box_content">
          <div class="img_item" v-for="(item, index) in imgList" :key="index">
            <img
              :src="item.img_src"
              alt="item.title"
              class="img"
              @click="previewImg(index)"
            />
          </div>
        </div>
      </div>
      <div class="box_13">
        <van-button round type="info" block native-type="button" @click="doShare">立即分享</van-button>
      </div>
    </div>
    <van-dialog v-model="showDetail" title="更多基本信息">
      <div class="details_pop_box">
        <div class="detail_line" v-if="fieldStore.basic.marriage.is_display">
          <div class="line_left">{{fieldStore.basic.marriage.field_cn}}：</div>
          <div class="line_right">{{basic.marriage_text || '未填写'}}</div><div class="clear"></div>
        </div>
        <div class="detail_line" v-if="fieldStore.basic.height.is_display">
          <div class="line_left">{{fieldStore.basic.height.field_cn}}：</div>
          <div class="line_right">{{basic.height || '未填写'}}</div><div class="clear"></div>
        </div>
        <div class="detail_line" v-if="fieldStore.basic.major.is_display">
          <div class="line_left">{{fieldStore.basic.major.field_cn}}：</div>
          <div class="line_right">{{basic.major_text || '未填写'}}</div><div class="clear"></div>
        </div>
        <div class="detail_line" v-if="fieldStore.basic.householdaddress.is_display">
          <div class="line_left">{{fieldStore.basic.householdaddress.field_cn}}：</div>
          <div class="line_right">{{basic.householdaddress || '未填写'}}</div><div class="clear"></div>
        </div>
        <div class="detail_line" v-if="fieldStore.basic.residence.is_display">
          <div class="line_left">{{fieldStore.basic.residence.field_cn}}：</div>
          <div class="line_right">{{basic.residence || '未填写'}}</div><div class="clear"></div>
        </div>
        <div class="detail_line" v-if="fieldStore.basic.custom_field_1.is_display">
          <div class="line_left">{{fieldStore.basic.custom_field_1.field_cn}}：</div>
          <div class="line_right">{{basic.custom_field_1 || '未填写'}}</div><div class="clear"></div>
        </div>
        <div class="detail_line" v-if="fieldStore.basic.custom_field_2.is_display">
          <div class="line_left">{{fieldStore.basic.custom_field_2.field_cn}}：</div>
          <div class="line_right">{{basic.custom_field_2 || '未填写'}}</div><div class="clear"></div>
        </div>
        <div class="detail_line" v-if="fieldStore.basic.custom_field_3.is_display">
          <div class="line_left">{{fieldStore.basic.custom_field_3.field_cn}}：</div>
          <div class="line_right">{{basic.custom_field_3 || '未填写'}}</div><div class="clear"></div>
        </div>
      </div>
    </van-dialog>
    <div class="alw-wx-layer" v-if="showWxLayer" @click="cancelShare"></div>
    <div class="alw-layer" v-if="showLayer" @click="cancelShare"></div>
    <SharePoster v-if="showPoster" @closePoster="closePoster" :type="'resume'" :infoid="shareid"></SharePoster>
    <van-overlay z-index="3" :show="showPoster" @click="showPoster=false"/>
    <van-popup v-model="showShare" position="bottom">
      <Share @cancelShare="cancelShare"
              @handleForward="handleForward"
              @handlePoster="handlePoster"></Share>
    </van-popup>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import http from '@/utils/http'
import api from '@/api'
import Vue from 'vue'
import { ImagePreview } from 'vant'
import Share from '@/components/share/Share'
import SharePoster from '@/components/share/SharePoster'
Vue.use(ImagePreview)
export default {
  name: 'ResumePreview',
  components: {
    Share,
    SharePoster
  },
  data () {
    return {
      previewImgList: [],
      showDetail: false,
      shareid: 0,
      showShare: false,
      showWxLayer: false,
      showLayer: false,
      showPoster: false,
      shareUrl: '',
      wechatShareInfo: {}
    }
  },
  created () {
    this.initInfo()
    console.log(this.fieldStore)
  },
  computed: {
    basic () {
      return this.$store.state.resume.basic
    },
    contact () {
      return this.$store.state.resume.contact
    },
    intentionList () {
      return this.$store.state.resume.intention_list
    },
    educationList () {
      return this.$store.state.resume.education_list
    },
    workList () {
      return this.$store.state.resume.work_list
    },
    trainingList () {
      return this.$store.state.resume.training_list
    },
    projectList () {
      return this.$store.state.resume.project_list
    },
    certificateList () {
      return this.$store.state.resume.certificate_list
    },
    languageList () {
      return this.$store.state.resume.language_list
    },
    imgList () {
      return this.$store.state.resume.img_list
    },
    fieldStore () {
      return this.$store.state.resume.field_rule
    }
  },
  methods: {
    doShare () {
      this.showShare = true
    },
    cancelShare () {
      this.showShare = false
      this.showWxLayer = false
      this.showLayer = false
    },
    handleForward () {
      const agent = navigator.userAgent.toLowerCase()
      if (agent.indexOf('micromessenger') < 0) {
        setTimeout(() => {
          this.showLayer = true
        }, 150)
      } else {
        setTimeout(() => {
          this.showWxLayer = true
        }, 150)
      }
    },
    handlePoster () {
      this.shareid = this.basic.id
      this.showPoster = true
    },
    closePoster () {
      this.showPoster = false
    },
    // 初始化数据
    initInfo () {
      http
        .get(api.editResume)
        .then(res => {
          if (parseInt(res.code) === 200) {
            let shareUrl = this.$store.state.config.mobile_domain + 'resume/' + res.data.basic.id
            let wechatShareInfo = {
              fullname: res.data.basic.fullname,
              sex: res.data.basic.sex == 1 ? '男' : '女',
              age: res.data.basic.age + '岁',
              education: res.data.basic.education_text,
              experience: res.data.basic.experience_text,
              intention_jobs: res.data.basic.intention_jobs_text
            }
            wxshare(wechatShareInfo, 'resumeshow', shareUrl)
            // 更新基本资料
            this.$store.dispatch('setBasicInfo', res.data.basic)
            // 更新简历模块列表
            this.$store.dispatch('setModuleList', res.data.module_list)
            // 更新简历字段列表
            this.$store.dispatch('setFieldList', res.data.field_rule)
            // 更新联系方式
            this.$store.dispatch('setResumeContact', res.data.contact)
            // 更新简历求职意向
            this.$store.dispatch('setIntentionList', res.data.intention_list)
            // 更新简历工作经历
            this.$store.dispatch('setWorkList', res.data.work_list)
            // 更新简历教育经历
            this.$store.dispatch('setEducationList', res.data.education_list)
            // 更新简历项目经历
            this.$store.dispatch('setProjectList', res.data.project_list)
            // 更新简历培训经历
            this.$store.dispatch('setTrainingList', res.data.training_list)
            // 更新简历语言能力
            this.$store.dispatch('setLanguageList', res.data.language_list)
            // 更新简历获得证书
            this.$store.dispatch(
              'setCertificateList',
              res.data.certificate_list
            )
            // 更新简历作品
            this.$store.dispatch('setResumeImgList', res.data.img_list)
            this.setPreviewImgList()
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    /**
     * 格式化日期
     * @param time 时间戳
     * @param isHM 是否返回时分
     * @returns {string}
     */
    dateFormatFull (time, isHM) {
      let date = new Date(time * 1000)
      let Y = date.getFullYear()
      let M = date.getMonth() + 1
      let D = date.getDate()
      let H = date.getHours()
      let MM = date.getMinutes()
      let timeText = ''
      if (isHM) {
        timeText = `${Y}-${this.supTen(M)}-${this.supTen(D)} ${this.supTen(
          H
        )}:${this.supTen(MM)}`
      } else {
        timeText = `${Y}-${this.supTen(M)}-${this.supTen(D)}`
      }
      return timeText
    },
    /**
     * 不足10补0
     * @param num
     */
    supTen (num) {
      return parseInt(num) < 10 ? `0${num}` : num
    },
    // 格式化日期
    dateFormat (time) {
      time = new Date(time * 1000)
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    },
    // 更新预览图片
    setPreviewImgList () {
      this.previewImgList = this.imgList.map(function (item) {
        return item.img_src
      })
    },
    // 预览作品
    previewImg (index) {
      ImagePreview({
        images: this.previewImgList,
        showIndex: true,
        loop: false,
        startPosition: index,
        closeOnPopstate: true,
        closeable: true
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .details_pop_box {
    max-height: 300px; background: #ffffff; overflow-x: hidden; overflow-y: auto; font-size: 13px;
    padding: 20px;
    .detail_desc {
      width: 100%; background-color: #f5f5f5; line-height: 1.5; color: #666666; padding: 8px 20px; margin-top: 10px;
    }
    .detail_line {
      line-height: 1.5; margin-bottom: 5px;
      .line_left {
        float: left; color: #999999; text-align: right; width: 80px; line-height: 1.5;
      }
      .line_right {
        float: left; color: #333333; word-break: break-all; width: 190px; text-align: left; line-height: 1.5;
        .a_black {
          color: #333333; text-decoration: none;
        }
        .a_blue {
          color: #1887fb; text-decoration: none;
        }
      }
    }
  }
.box_13 {
  width: 300px;
  margin: 0 auto;
  padding-bottom: 30px;
}
.box_12 {
  width: 100%;
  .box_content {
    .img_item {
      .img {
        width: 100px;
        height: 100px;
        border-radius: 4px;
        border: 0;
      }
      &:nth-of-type(3n) {
        margin-right: 0;
      }
      float: left;
      position: relative;
      width: 100px;
      height: 100px;
      border-radius: 4px;
      margin: 0 20px 20px 0;
    }
    padding-bottom: 10px;
  }
}
.box_11 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      .right_txt {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #666666;
      }
      &:not(:last-child) {
        margin-bottom: 14px;
      }
      font-size: 15px;
      color: #333333;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_10 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #666666;
      }
      &:not(:last-child) {
        margin-bottom: 14px;
      }
      font-size: 15px;
      color: #333333;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_9 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: " ";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      padding: 0 25px;
      position: relative;
      &:not(:last-child) {
        margin-bottom: 15px;
      }
    }
    padding-bottom: 17.5px;
  }
}
.box_8 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: " ";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      padding: 0 25px;
      position: relative;
      &:not(:last-child) {
        margin-bottom: 15px;
      }
    }
    padding-bottom: 17.5px;
  }
}
.box_7 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: " ";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      &:not(:last-child) {
        margin-bottom: 15px;
      }
      padding: 0 25px;
      position: relative;
    }
    padding-bottom: 17.5px;
  }
}
.box_6 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: "";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      &:not(:last-child) {
        margin-bottom: 15px;
      }
      padding: 0 25px;
      position: relative;
    }
    padding-bottom: 17.5px;
  }
}
.box_5 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tag {
      .tag-item {
        float: left;
        padding: 4.5px 12px;
        font-size: 12px;
        color: #1787fb;
        margin: 0 10px 10px 0;
        background-color: #f4f9ff;
        border-radius: 26px;
      }
    }
    .dec {
      line-height: 1.8;
      font-size: 13px;
      color: #666666;
      word-break: break-all;
    }
    padding-bottom: 17px;
  }
}
.box_cac {
  .box_content {
    .item {
      &.phone {
        background: url('../../assets/images/cac_phone_ico.svg') 2px center
          no-repeat;
        background-size: 16px;
      }
      &.wx {
        background: url('../../assets/images/cac_wx_ico.svg') 0 center no-repeat;
        background-size: 16px;
      }
      &.email {
        background: url('../../assets/images/cac_email_ico.svg') 0 center
          no-repeat;
        background-size: 16px;
      }
      &.qq {
        background: url('../../assets/images/cac_qq_ico.svg') 0 center no-repeat;
        background-size: 16px;
      }
      font-size: 15px;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding: 0 20px 0 23px;
      word-break: break-all;
      &:not(:last-child) {
        margin-bottom: 9px;
      }
    }
    padding-bottom: 17px;
  }
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
}
.box_3 {
  .tx2 {
    .intent {
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .name {
      font-size: 15px;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      margin-bottom: 9.5px;
    }
    &:not(:first-child){margin-top: 17.5px;}
    display: block;
    position: relative;
  }
  .box_content {
    padding-bottom: 15px;
  }
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
}
.box_head {
  .txt {
    font-size: 18px;
    color: #333333;
    font-weight: bold;
    margin-right: 3px;
  }
  position: relative;
  width: 100%;
  padding: 22.5px 0;
}
.box_2 {
  .tx2 {
    position: relative;
    width: 100%;
    border-top: 1px solid #f8f8f8;
    font-size: 12px;
    color: #999999;
    padding: 12px 0;
    .right_txt {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 12px;
      color: #1787fb;
      padding: 12px 15px 12px 0;
      line-height: normal;
      &::after {
        position: absolute;
        right: 5px;
        top: 18px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #1787fb;
        border-right: 1px solid #1787fb;
        transform: rotate(45deg);
        content: ' ';
      }
    }
  }
  .name_group {
    .avatar_box {
      &.fe::after {
        content: ' ';
        position: absolute;
        right: -3px;
        bottom: 3px;
        width: 13px;
        height: 13px;
        border-radius: 100%;
        background: #ff4d94 url('../../assets/images/female.svg') center
          no-repeat;
        background-size: 10px;
      }
      &::after {
        content: ' ';
        position: absolute;
        right: -3px;
        bottom: 3px;
        width: 13px;
        height: 13px;
        border-radius: 100%;
        background: #50a6fa url('../../assets/images/male.svg') center no-repeat;
        background-size: 10px;
      }
      img {
        width: 48px;
        height: 48px;
        border: 0;
        border-radius: 100%;
      }
      position: absolute;
      right: 2px;
      top: 3.5px;
      width: 48px;
      height: 48px;
      border-radius: 100%;
    }
    .tx1 {
      font-size: 12px;
      color: #999999;
      margin-bottom: 7px;
      overflow: hidden;
      white-space: nowrap;
    }
    .info {
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .name {
      margin-bottom: 11px;
      .text{float: left;font-size: 18px;color: #333333;font-weight: bold;margin-right: 8px;}
      .qu_ico {
        float: left;
        width: 36px;
        height: 25px;
        background: url('../../assets/images/high_quality_ico.png') 0 6px no-repeat;
        background-size: 36px 14px;
      }
    }
    position: relative;
    padding: 0 50px 0 0;
    margin-bottom: 17.5px;
  }
  padding: 15px 0 0;
  position: relative;
}
.content_wrapper {
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;
}
</style>
