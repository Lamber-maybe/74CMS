<template>
  <div id="app">
    <Head
      :goback_custom="true"
      @gobackCustomMethod="$router.push('/member/personal/index')"
      >我的简历</Head
    >
    <div class="box_tip" v-if="showPerfectTip">
      您的简历完整度较低，严重影响求职质量，建议您立即完善
    </div>
    <div class="content_wrapper">
      <div class="box_1" v-if="parseInt(basic.stick) === 0 && !showPerfectTip" @click="$router.push('/member/order/add/stick')">
        简历不出众? 快来试试简历置顶，立即提高5倍曝光率!
      </div>
      <div class="box_2" v-if="this.moduleStore.basic.is_display === 1">
        <div class="name_group">
          <div
            :class="parseInt(basic.sex) === 1 ? 'avatar_box' : 'avatar_box fe'"
          >
            <img :src="basic.photo_img_src" :alt="basic.fullname" />
          </div>
          <router-link
            class="edit"
            to="/member/personal/resume/basic"
          ></router-link>
          <div class="name">{{ basic.fullname }}</div>
          <div class="info">
            {{ basic.age }}岁 · {{ basic.experience_text }} ·
            {{ basic.education_text }}
          </div>
        </div>
        <div class="tx1">{{ contact.mobile }}</div>
        <div class="tx2" v-if="fieldRule.contact.weixin!==undefined && fieldRule.contact.weixin.is_display === 1">{{ contact.weixin?contact.weixin:'未填写' }}</div>
        <div class="percent">完整度: {{ basic.complete_percent }}%</div>
      </div>
    </div>
    <div class="form_split_10"></div>
    <div class="content_wrapper">
      <!--求职意向-->
      <div class="box_3" v-if="this.moduleStore.intention.is_display === 1">
        <div class="box_head">
          <div class="txt">求职意向</div>
          <div class="required">必填</div>
          <router-link
            class="add_ico"
            to="/member/personal/resume/intention_edit/0"
          ></router-link>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <div class="tx1" @click="showPickerCurrent = !showPickerCurrent">
            求职状态
            <div class="right_txt">{{ currentName }}</div>
          </div>
          <van-popup v-model="showPickerCurrent" position="bottom">
            <van-picker
              show-toolbar
              :columns="columnsCurrent"
              :default-index="currentDefaultIndex"
              @confirm="onConfirmCurrent"
              @cancel="showPickerCurrent = false"
            />
          </van-popup>
          <router-link
            class="tx2"
            v-for="(item, index) in intentionList"
            :key="index"
            :to="'/member/personal/resume/intention_edit/' + item.id"
          >
            <div class="name">
              [{{ item.district_text }}] {{ item.category_text }}
            </div>
            <div class="intent">
              {{ item.wage_text }}，{{ item.nature_text }}
              {{ fieldRule.intention.trade.is_display === 1 && item.trade_text !== '' ? `，${item.trade_text}` : '' }}
            </div>
          </router-link>
        </div>
      </div>
      <!--特长标签-->
      <div class="box_4" v-if="this.moduleStore.tag.is_display === 1">
        <div class="box_head">
          <div class="txt">特长标签</div>
          <router-link
            class="add_ico"
            to="/member/personal/resume/tag"
          ></router-link>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <div
            class="tag-item"
            v-for="(item, index) in basic.tag_text_arr"
            :key="index"
          >
            {{ item }}
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <!--自我描述-->
      <div class="box_5" v-if="this.moduleStore.specialty.is_display === 1">
        <div class="box_head">
          <div class="txt">自我描述</div>
          <router-link
            class="edit_ico"
            to="/member/personal/resume/specialty"
          ></router-link>
          <div class="clear"></div>
        </div>
        <div class="box_content">{{ basic.specialty }}</div>
      </div>
      <!--教育经历-->
      <div class="box_6" v-if="this.moduleStore.education.is_display === 1">
        <div class="box_head">
          <div class="txt">教育经历</div>
          <div class="required">必填</div>
          <router-link
            class="add_ico"
            to="/member/personal/resume/education_edit/0"
          ></router-link>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <router-link
            class="tx1"
            v-for="(item, index) in educationList"
            :key="index"
            :to="'/member/personal/resume/education_edit/' + item.id"
          >
            <div class="t1">{{ item.school }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : "至今"
              }}
            </div>
            <div class="t3" v-if="fieldRule.education.major.is_display === 1">{{ item.major }}</div>
          </router-link>
        </div>
      </div>
      <!--工作经历-->
      <div class="box_7" v-if="this.moduleStore.work.is_display === 1">
        <div class="box_head">
          <div class="txt">工作经历</div>
          <div class="required">必填</div>
          <router-link
            class="add_ico"
            to="/member/personal/resume/work_edit/0"
          ></router-link>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <router-link
            class="tx1"
            v-for="(item, index) in workList"
            :key="index"
            :to="'/member/personal/resume/work_edit/' + item.id"
          >
            <div class="t1">{{ item.companyname }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : "至今"
              }}
            </div>
            <div class="t3">{{ item.jobname }}</div>
            <div class="t4">{{ item.duty }}</div>
          </router-link>
        </div>
      </div>
      <!--培训经历-->
      <div class="box_8" v-if="this.moduleStore.training.is_display === 1">
        <div class="box_head">
          <div class="txt">培训经历</div>
          <router-link
            class="add_ico"
            to="/member/personal/resume/train_edit/0"
          ></router-link>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <router-link
            class="tx1"
            v-for="(item, index) in trainingList"
            :key="index"
            :to="'/member/personal/resume/train_edit/' + item.id"
          >
            <div class="t1">{{ item.agency }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : "至今"
              }}
            </div>
            <div class="t3">{{ item.course }}</div>
            <div class="t4">{{ item.description }}</div>
          </router-link>
        </div>
      </div>
      <!--项目经历-->
      <div class="box_9" v-if="this.moduleStore.project.is_display === 1">
        <div class="box_head">
          <div class="txt">项目经历</div>
          <router-link
            class="add_ico"
            to="/member/personal/resume/project_edit/0"
          ></router-link>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <router-link
            class="tx1"
            v-for="(item, index) in projectList"
            :key="index"
            :to="'/member/personal/resume/project_edit/' + item.id"
          >
            <div class="t1">{{ item.projectname }}</div>
            <div class="t2">
              {{ dateFormat(item.starttime) }} 至
              {{
                parseInt(item.todate) === 0 ? dateFormat(item.endtime) : "至今"
              }}
            </div>
            <div class="t3">{{ item.role }}</div>
            <div class="t4">{{ item.description }}</div>
          </router-link>
        </div>
      </div>
      <!--获得证书-->
      <div class="box_10" v-if="this.moduleStore.certificate.is_display === 1">
        <div class="box_head">
          <div class="txt">获得证书</div>
          <span class="add_ico" @click="handlerCertificateAdd"></span>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <router-link
            class="tx1"
            v-for="(item, index) in certificateList"
            :key="index"
            :to="'/member/personal/resume/certificate_edit/' + item.id"
          >
            {{ item.name }}
            <div class="right_txt">{{ dateFormat(item.obtaintime) }}</div>
          </router-link>
        </div>
      </div>
      <!--语言能力-->
      <div class="box_11" v-if="this.moduleStore.language.is_display === 1">
        <div class="box_head">
          <div class="txt">语言能力</div>
          <span class="add_ico" @click="handlerLanguageAdd"></span>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <router-link
            class="tx1"
            v-for="(item, index) in languageList"
            :key="index"
            :to="'/member/personal/resume/language_edit/' + item.id"
          >
            {{ item.language_text }}，{{ item.level_text }}
          </router-link>
        </div>
      </div>
      <!--我的作品-->
      <div class="box_12" v-if="this.moduleStore.img.is_display === 1">
        <div class="box_head">
          <div class="txt">我的作品</div>
          <div class="clear"></div>
        </div>
        <div class="box_content">
          <div class="img_item" v-for="(item, index) in imgList" :key="index">
            <img
              :src="item.img_src"
              alt="item.title"
              class="img"
              @click="previewImg(index)"
            />
            <div class="audit_bg" v-if="parseInt(item.audit) !== 1">
              {{ item.audit_text }}
            </div>
            <div class="delete_ico" @click="deleteImg(item.id)"></div>
          </div>
          <van-uploader
            v-if="imgList.length < 6"
            :preview-image="false"
            :after-read="uploadImg"
            :accept="'image/*'"
          >
            <div class="img_item for_upload">添加图片</div>
          </van-uploader>
        </div>
      </div>
      <div class="box_13">
        <van-button round type="info" block @click="$router.push('/member/personal/preview')">预览简历</van-button>
      </div>
    </div>
    <div
      v-if="!showMoreBar"
      class="fixed_more"
      @click="showMoreBar = !showMoreBar"
    ></div>
    <div v-if="showMoreBar" class="fixed_more_open">
      <div class="ic1" @click="refreshResume">刷新</div>
      <router-link class="ic2" to="/member/personal/service">置顶</router-link>
      <router-link class="ic3" to="/member/personal/privacy">隐私</router-link>
      <div class="ic4" @click="showMoreBar = !showMoreBar"></div>
    </div>
    <van-dialog v-model="showCloseResume" title="简历设置" show-cancel-button>
      <div class="box_log_privacy">
        <div class="self_remind">
          简历设置为关闭后企业将无法搜索到您的简历哦
        </div>
        <div class="check_wrapper">
          <van-radio-group v-model="radio" direction="horizontal">
            <van-radio name="1" icon-size="17px">公开</van-radio>
            <van-radio name="2" icon-size="17px">关闭</van-radio>
          </van-radio-group>
        </div>
      </div>
    </van-dialog>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import {handlerHttpError} from '@/utils/error'
import http from '@/utils/http'
import api from '@/api'
import Vue from 'vue'
import { ImagePreview } from 'vant'
Vue.use(ImagePreview)
export default {
  name: 'EditResume',
  data () {
    return {
      uploading: false,
      radio: 1,
      imgList: [],
      previewImgList: [],
      showCloseResume: false,
      showMoreBar: false,
      current: '',
      currentName: '',
      showPickerCurrent: false,
      currentDefaultIndex: 0,
      showPerfectTip: true,
      moduleStore: {}
    }
  },
  computed: {
    basic () {
      return this.$store.state.resume.basic ? this.$store.state.resume.basic : {}
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
    columnsCurrent () {
      return this.$store.state.classifyCurrent
    },
    siteConfig () {
      return this.$store.state.config
    },
    fieldRule () {
      return this.$store.state.resume.field_rule
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'current')
    this.initInfo()
  },
  methods: {
    // 初始化数据
    initInfo () {
      http
        .get(api.editResume)
        .then(res => {
          if (parseInt(res.code) === 200) {
            if (res.data === null) {
              handlerHttpError({code: 50007, message: '请先添加一份简历'})
              return
            }
            // 更新基本资料
            this.$store.dispatch('setBasicInfo', res.data.basic)
            // 更新简历模块列表
            this.$store.dispatch('setModuleList', res.data.module_list)
            this.moduleStore = res.data.module_list
            console.log(this.moduleStore)
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
            // 当前求职状态
            this.current = this.basic.current
            this.currentName = this.basic.current_text
            // 作品
            if (this.$store.state.resume.img_list.length) {
              this.imgList = this.$store.state.resume.img_list
              this.setPreviewImgList()
            }
            // 恢复求职状态
            this.currentDefaultIndex = this.columnsCurrent.findIndex(
              item => parseInt(item.id) === parseInt(this.basic.current)
            )
            // 是否出现继续完善简历提示和是否显示引导置顶简历
            this.showPerfectTip = parseInt(this.basic.complete_percent) < parseInt(this.siteConfig.apply_job_min_percent)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 选择求职状态
    onConfirmCurrent (value) {
      this.current = value.id
      this.currentName = value.text
      this.showPickerCurrent = !this.showPickerCurrent
      http
        .post(api.resume_current_save, {
          current: this.current
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({type: 'success', message: '成功修改求职状态'})
            let currentBasic = this.$store.state.resume.basic
            currentBasic.current = this.current
            currentBasic.current_text = this.currentName
            // 更新基本资料
            this.$store.dispatch('setBasicInfo', currentBasic)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 格式化日期
    dateFormat (time) {
      time = new Date(time * 1000)
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      return `${year}-${month}`
    },
    // 作品上传
    uploadImg (file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$notify('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      this.uploading = true
      http
        .postFormData(api.resume_img_save, { file: file.file })
        .then(res => {
          this.uploading = false
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.imgList.push({
              audit: res.data.audit,
              audit_text: res.data.audit_text,
              id: res.data.id,
              img: res.data.file_id,
              img_src: res.data.file_url
            })
            // 更新简历作品
            this.$store.dispatch('setResumeImgList', this.imgList)
            this.setPreviewImgList()
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          this.uploading = false
          console.log(err)
        })
    },
    // 删除作品
    deleteImg (id) {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该作品吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_img_delete, {
              id: id
            })
            .then(res => {
              if (parseInt(res.code) === 200) {
                this.$notify({ type: 'success', message: res.message })
                this.imgList.splice(
                  this.imgList.findIndex(
                    item => parseInt(item.id) === parseInt(id)
                  ),
                  1
                )
                // 更新简历作品
                this.$store.dispatch('setResumeImgList', this.imgList)
                this.setPreviewImgList()
              } else {
                this.$notify(res.message)
              }
            })
            .catch(err => {
              console.log(err)
            })
        })
        .catch(() => {
          // 取消按钮
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
    },
    // 更新预览图片
    setPreviewImgList () {
      this.previewImgList = this.imgList.map(function (item) {
        return item.img_src
      })
    },
    // 刷新简历
    refreshResume () {
      http
        .post(api.resume_refresh)
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlerCertificateAdd () {
      if (this.certificateList.length >= 6) {
        this.$notify('最多可以添加6份证书')
      } else {
        this.$router.push('/member/personal/resume/certificate_edit/0')
      }
    },
    handlerLanguageAdd () {
      if (this.certificateList.length >= 6) {
        this.$notify('最多可添加6种语言')
      } else {
        this.$router.push('/member/personal/resume/language_edit/0')
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.box_tip {
  width: 100%;
  font-size: 12px;
  color: #ff6600;
  position: relative;
  padding: 10.5px 0 10.5px 33px;
  background: #fffbeb url("../../assets/images/remind_ico.svg") 13px 11px
    no-repeat;
  background-size: 15px;
}
.box_log_privacy {
  padding: 15px 0 30px 0;
  width: 300px;
  margin: 0 auto;
  .check_wrapper {
    font-size: 16px;
    color: #666666;
    text-align: center;
    width: 190px;
    margin: 0 auto;
    padding-top: 30px;
    .van-radio--horizontal:not(:last-child) {
      margin-right: 75px;
    }
    .van-radio--horizontal:last-child {
      margin-right: 0;
    }
  }
  .self_remind {
    width: 100%;
    color: #ff6600;
    font-size: 12px;
    border-radius: 4px;
    padding: 14px 0 14px 33px;
    background: #fffbeb url("../../assets/images/remind_ico.svg") 13px center
      no-repeat;
    background-size: 16px;
  }
}
.fixed_more_open {
  .ic4 {
    width: 45px;
    padding: 12px 0 25px;
    border-radius: 45px;
    position: relative;
    &::after {
      position: absolute;
      left: 19.5px;
      top: 12px;
      width: 6px;
      height: 6px;
      border-bottom: 1px solid #1787fb;
      border-right: 1px solid #1787fb;
      transform: rotate(45deg);
      content: " ";
    }
  }
  .ic3 {
    font-size: 11px;
    padding-top: 23px;
    margin-bottom: 2px;
    display: block;
    background: url("../../assets/images/fixed_more_open_ys.svg") center 0
      no-repeat;
    background-size: 20px;
    color: #1787fb;
  }
  .ic2 {
    font-size: 11px;
    padding-top: 20px;
    margin-bottom: 15px;
    display: block;
    background: url("../../assets/images/fixed_more_open_top.svg") center 0
      no-repeat;
    background-size: 16px;
    color: #1787fb;
  }
  .ic1 {
    font-size: 11px;
    padding-top: 20px;
    margin-bottom: 15px;
    background: url("../../assets/images/fixed_more_open_refresh.svg") center 0
      no-repeat;
    background-size: 16px;
  }
  position: fixed;
  right: 25px;
  bottom: 150px;
  z-index: 3;
  box-shadow: 0 0 5px #c2c2c2;
  color: #1787fb;
  width: 45px;
  border-radius: 45px;
  padding: 20px 0 0;
  text-align: center;
  background-color: #ffffff;
  overflow: hidden;
}
.fixed_more {
  position: fixed;
  right: 25px;
  bottom: 150px;
  z-index: 2;
  box-shadow: 0 0 5px #c2c2c2;
  background: #ffffff url("../../assets/images/resume_fixed_more_ico.svg")
    center no-repeat;
  background-size: 23px;
  width: 37px;
  height: 37px;
  border-radius: 100%;
}
.box_13 {
  width: 300px;
  margin: 0 auto;
  padding-bottom: 45px;
}
.box_12 {
  width: 100%;
  .box_content {
    .img_item {
      &.for_upload {
        margin: 0;
        padding-top: 67px;
        text-align: center;
        font-size: 14px;
        color: #c0c0c0;
        background: #f8f8f8 url("../../assets/images/upload_add_ico.svg") center
          20px no-repeat;
        background-size: 32px;
      }
      .delete_ico {
        &::before {
          position: absolute;
          left: 5px;
          top: 9.5px;
          width: 10px;
          border-top: 1px solid #ffffff;
          content: " ";
          transform: rotate(45deg);
        }
        &::after {
          position: absolute;
          left: 9.5px;
          top: 5px;
          height: 10px;
          border-right: 1px solid #ffffff;
          content: " ";
          transform: rotate(45deg);
        }
        position: absolute;
        right: -6px;
        top: -6px;
        width: 20px;
        height: 20px;
        border-radius: 100%;
        z-index: 2;
        background-color: rgba(0, 0, 0, 0.5);
      }
      .audit_bg {
        position: absolute;
        left: 0;
        top: 0;
        width: 100px;
        height: 100px;
        border-radius: 4px;
        text-align: center;
        line-height: 100px;
        color: #c0c0c0;
        font-size: 15px;
        background-color: rgba(0, 0, 0, 0.75);
        z-index: 1;
      }
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
    padding-bottom: 30px;
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
        margin-bottom: 15px;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 7px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      font-size: 15px;
      color: #333333;
      position: relative;
      display: block;
      padding-right: 30px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
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
        right: 20px;
        top: 2px;
        font-size: 13px;
        color: #666666;
      }
      &:not(:last-child) {
        margin-bottom: 17px;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 8px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      font-size: 15px;
      color: #333333;
      position: relative;
      display: block;
      padding-right: 80px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    padding-bottom: 17px;
  }
}
.box_9 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
        padding-top: 8px;
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
        margin-bottom: 9px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 9px;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 47px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      &:not(:last-child) {
        margin-bottom: 17px;
      }
      padding-right: 25px;
      display: block;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_8 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
        padding-top: 8px;
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
        margin-bottom: 9px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 9px;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 47px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      &:not(:last-child) {
        margin-bottom: 17px;
      }
      padding-right: 25px;
      display: block;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_7 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
        padding-top: 8px;
        word-break: break-all;
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
        margin-bottom: 9px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 9px;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 47px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      &:not(:last-child) {
        margin-bottom: 17px;
      }
      padding-right: 25px;
      display: block;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_6 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
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
        margin-bottom: 9px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 9px;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 33px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      &:not(:last-child) {
        margin-bottom: 17px;
      }
      padding-right: 25px;
      display: block;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_5 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    padding-bottom: 15px;
    line-height: 1.8;
    font-size: 13px;
    color: #666666;
    word-break: break-all;
  }
}
.box_4 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    padding-bottom: 2px;
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
    &::after {
      position: absolute;
      right: 6px;
      top: 20px;
      width: 6px;
      height: 6px;
      border-top: 1px solid #666666;
      border-right: 1px solid #666666;
      transform: rotate(45deg);
      content: " ";
    }
    padding-right: 25px;
    margin-top: 17.5px;
    display: block;
    position: relative;
  }
  .tx1 {
    .right_txt {
      position: absolute;
      right: 20px;
      top: 50%;
      transform:translate(0,-50%);
      font-size: 13px;
      color: #666666;
    }
    &::after {
      position: absolute;
      right: 6px;
      top: 7px;
      width: 6px;
      height: 6px;
      border-top: 1px solid #666666;
      border-right: 1px solid #666666;
      transform: rotate(45deg);
      content: " ";
    }
    font-size: 15px;
    color: #333333;
    font-weight: bold;
    position: relative;
  }
  .box_content {
    padding-bottom: 15px;
  }
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
}
.box_head {
  .edit_ico {
    position: absolute;
    right: -15px;
    top: 10px;
    height: 50px;
    width: 50px;
    background: url("../../assets/images/resume_edit_ico.svg") center no-repeat;
    background-size: 16px;
  }
  .add_ico {
    position: absolute;
    right: -15px;
    top: 10px;
    height: 50px;
    width: 50px;
    background: url("../../assets/images/resume_add_ico.svg") center no-repeat;
    background-size: 16px;
  }
  .van-tag {
    padding: 0 0.5em;
  }
  .required{float:left;font-size:9px;font-weight:bold;color:#ff5757;border:1PX solid #ff5757;padding:0 3px;margin:6px 0 0 5px;}
  .txt {
    float: left;
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
    font-size: 12px;
    color: #333333;
    padding: 2.5px 0 2.5px 23px;
    background: url("../../assets/images/resume_wx_ico.png") 0 center no-repeat;
    background-size: 16.5px 16px;
  }
  .tx1 {
    font-size: 12px;
    color: #333333;
    padding: 2.5px 0 2.5px 23px;
    margin-bottom: 5.5px;
    background: url("../../assets/images/resume_phone_ico.png") 0 center
      no-repeat;
    background-size: 16.5px 16px;
  }
  .percent {
    position: absolute;
    right: -17px;
    bottom: 16px;
    color: #ff5d24;
    font-size: 13px;
    padding: 7px 9px 7px 16px;
    background-color: #f3f3f3;
    border-radius: 18px 0 0 18px;
  }
  .name_group {
    .edit {
      position: absolute;
      right: 0;
      top: 6px;
      width: 36px;
      height: 26px;
      background: url("../../assets/images/resume_edit_ico.svg") center 2.5px
        no-repeat;
      background-size: 13px;
    }
    .avatar_box {
      &.fe::after {
        content: " ";
        position: absolute;
        right: -3px;
        bottom: 3px;
        width: 13px;
        height: 13px;
        border-radius: 100%;
        background: #ff4d94 url("../../assets/images/female.svg") center
          no-repeat;
        background-size: 10px;
      }
      &::after {
        content: " ";
        position: absolute;
        right: -3px;
        bottom: 3px;
        width: 13px;
        height: 13px;
        border-radius: 100%;
        background: #50a6fa url("../../assets/images/male.svg") center no-repeat;
        background-size: 10px;
      }
      img {
        width: 48px;
        height: 48px;
        border: 0;
        border-radius: 100%;
      }
      position: absolute;
      left: 0;
      top: 50%;
      transform: translate(0, -50%);
      width: 48px;
      height: 48px;
      border-radius: 100%;
    }
    .info {
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .name {
      font-size: 18px;
      color: #333333;
      font-weight: bold;
      padding: 2.5px 0 10.5px 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    position: relative;
    padding: 0 50px 0 61px;
    margin-bottom: 15.5px;
  }
  padding: 12.5px 0 15.5px;
  position: relative;
}
.box_1 {
  width: 100%;
  font-size: 12px;
  color: #ffffff;
  border-radius: 5px;
  padding: 11.5px 20px 11.5px 30px;
  background: #fb8a78 url("../../assets/images/resume_top_tip_bg.png") 10px
    center no-repeat;
  background-size: 13px 14px;
  position: relative;
  margin-bottom: 10px;
  &::after {
    position: absolute;
    right: 11px;
    top: 16px;
    width: 7px;
    height: 7px;
    border-top: 1px solid #ffffff;
    border-right: 1px solid #ffffff;
    transform: rotate(45deg);
    content: " ";
  }
}
.content_wrapper {
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;
}
.van-overlay{
  text-align:center;
  z-index:2;
}
.van-loading{
  top:36%;
}
.van-loading__text{
  color:#c3c3c3;
}
</style>
