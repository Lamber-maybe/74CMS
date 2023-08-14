<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closePopout')"
    >举报{{typeCn}}</Head>
    <div class="form_split_10"></div>
    <div class="notice_bar">举报{{typeCn}}：{{type===1?jobname:fullname}}<div class="right_txt" @click="$emit('closePopout')">查看{{typeCn}}</div></div>
    <div class="form_split_10"></div>
    <div class="feed_container">
      <div class="feed_title">举报原因</div>
      <div class="feed_type">
        <div class="type_item" :class="item.id == reason ? 'selected' : ''" v-for="(item, index) in options_tipoff" :key="index" @click="handlerReason(item)">{{ item.text }}</div>
        <div class="clear"></div>
      </div>
      <div class="feed_title">备注说明(必填)</div>
      <textarea
        v-model="content"
        class="feed_area"
        rows="8"
        placeholder="请简要描述您的问题，以便我们尽快核实解决"
      ></textarea>
      <div class="feed_title">上传凭证</div>
      <div class="up_text">请提供明确指向举报原因的证据，如聊天截图、门店招 牌等信息</div>
      <div class="feed_up">
        <div class="img_item" v-for="(item, index) in imgList" :key="index">
          <img
            :src="item.img_src"
            alt="item.title"
            class="img"
            @click="previewImg(index)"
          />
          <div class="delete_ico" @click="deleteImg(index)"></div>
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
      <div style="margin: 16px 16px 16px;">
        <van-button round block type="info" @click="handleSubmit">提交</van-button>
      </div>
    </div>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import Vue from 'vue'
import { ImagePreview } from 'vant'
Vue.use(ImagePreview)
export default {
  name: 'Tipoff',
  props: ['type', 'jobname', 'fullname', 'target_id'],
  data () {
    return {
      uploading: false,
      typeCn: '',
      reason: 0,
      content: '',
      imgList: [],
      previewImgList: []
    }
  },
  computed: {
    options_tipoff () {
      if (this.type === 1) {
        return this.$store.state.classifyTipoffJob
      } else {
        return this.$store.state.classifyTipoffResume
      }
    }
  },
  methods: {
    initCB () {
      if (this.type === 1) {
        this.typeCn = '职位'
      } else {
        this.typeCn = '简历'
      }
      this.content = ''
      if (this.type === 1) {
        this.$store.dispatch('getClassify', 'tipoffJob').then(res => {
          this.reason = res.data[0].id
        })
      } else {
        this.$store.dispatch('getClassify', 'tipoffResume').then(res => {
          this.reason = res.data[0].id
        })
      }
      this.imgList = []
      this.previewImgList = []
    },
    handlerReason (item) {
      this.reason = item.id
    },
    // 提交
    handleSubmit () {
      if (this.reason == 0) {
        this.$notify('请选择举报原因')
        return false
      }
      if (this.content == '') {
        this.$notify('请填写备注说明')
        return false
      }
      if (this.content.length > 200) {
        this.$notify('最多填写200个字')
        return false
      }
      let imgArr = []
      this.imgList.forEach(element => {
        imgArr.push(element.img)
      })
      http
        .post(api.tipoff, { target_id: this.target_id, type: this.type, reason: this.reason, content: this.content, img: imgArr })
        .then(res => {
          this.$notify({
            type: 'success',
            message: res.message
          })
          this.$emit('closePopout')
        })
        .catch(() => {})
    },
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
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          this.uploading = false
          this.$notify({ type: 'success', message: res.message })
          this.imgList.push({
            img: res.data.file_id,
            img_src: res.data.file_url
          })
          this.setPreviewImgList()
        })
        .catch(err => {
          this.uploading = false
          console.log(err)
        })
    },
    deleteImg (index) {
      this.imgList.splice(index, 1)
      this.setPreviewImgList()
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
    }
  }
}
</script>

<style lang="scss" scoped>
  .feed_container {
    .feed_up {
      padding: 10px 0;
      .img_item {
        &.for_upload {
          margin: 0;
          padding-top: 67px;
          text-align: center;
          font-size: 14px;
          color: #c0c0c0;
          background: #f8f8f8 url("../assets/images/upload_add_ico.svg") center
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
    }
    .feed_area {
      &::placeholder {
        color: #c9c9c9;
      }
      width: 100%;
      border: 1px solid #e2e2e2;
      resize: none;
      padding: 8px 12px;
      font-size: 14px;
      line-height: 1.6;
    }
    .up_text{font-size: 14px;color: #c9c9c9;line-height: 1.6;word-break: break-all;}
    .feed_type {
      .type_item {
        &.selected {
          background-color: #f4f9ff;
          border: 1px solid #f4f9ff;
          color: #1787fb;
        }
        float: left;
        margin-right: 10px;
        padding: 5px 12px;
        font-size: 11px;
        color: #666666;
        background-color: #ffffff;
        border: 1px solid #e2e2e2;
        margin-bottom:10px;
      }
    }
    .feed_title {
      padding: 17px 0;
      font-size: 16px;
      color: #333333;
      font-weight: bold;
    }
    padding: 0 16px;
  }
  .notice_bar {
    width: 100%;
    font-size: 12px;
    color: #ff6600;
    position: relative;
    padding: 11px 0 11px 33px;
    background: #fffbeb url("../assets/images/remind_ico.svg") 13px center
    no-repeat;
    background-size: 15px;
    .right_txt {
      position: absolute;
      right: 0;
      top: 50%;transform: translate(0,-50%);
      font-size: 12px;
      color: #1787fb;
      padding: 13px 17px;
    }
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
