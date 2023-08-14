<template>
  <div id="app" class="my_app">
    <Head :bg="'tran'">企业管理</Head>
    <div class="box_1">
      <div class="box_info" :class="top_loading === true ? 'wait' : ''">
        <van-skeleton avatar :row="3" :loading="top_loading">
          <div class="box_top">
            <div class="avatar_box">
              <img :src="basic.logo_src" alt="logo" />
            </div>
            <div class="name">{{ basic.companyname }}</div>
            <div class="some">
              {{ basic.scale_text }} · {{ basic.district_text }} ·
              {{ basic.nature_text }}
            </div>
          </div>
        </van-skeleton>
        <div class="box_bottom">
          企业资料
          <div
            class="right_txt"
            @click="$router.push('/member/company/profile')"
          >
            修改企业资料
          </div>
        </div>
      </div>
    </div>
    <div class="box_2">
      企业风采
      <div class="right_txt">最多可上传6张企业风采图片</div>
    </div>
    <div class="box_3">
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
    <div class="box_4">
      <van-button
        type="info"
        round
        block
        @click="$router.push('/company/' + basic.id)"
      >
        预览企业主页
      </van-button>
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
  name: 'CompanyManagement',
  beforeMount () {
    // 自定义body背景色
    document
      .querySelector('body')
      .setAttribute('style', 'background-color:#ffffff')
  },
  beforeDestroy () {
    // 去掉自定义body背景色
    document.querySelector('body').removeAttribute('style')
  },
  data () {
    return {
      uploading: false,
      top_loading: true,
      basic: {},
      imgList: [],
      previewImgList: []
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      http
        .get(api.company_profile, {})
        .then(res => {
          const { basic, img_list } = { ...res.data }
          this.basic = basic
          this.top_loading = false
          this.imgList = img_list
          this.setPreviewImgList()
        })
        .catch(() => {})
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
        .postFormData(api.company_img_upload, { file: file.file })
        .then(res => {
          this.uploading = false
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.imgList.push({
              id: res.data.id,
              img: res.data.file_id,
              title: '',
              addtime: Date.parse(new Date()) / 1000,
              audit: res.data.audit,
              audit_text: res.data.audit_text,
              img_src: res.data.file_url
            })
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
          message: '确定删除该照片吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.company_img_del, {
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
    // 返回上一页
    goBack () {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    }
  }
}
</script>

<style lang="scss" scoped>
.van-skeleton {
  margin-bottom: 10px;
}
.box_4 {
  padding: 35px;
}
.box_3 {
  .img_item {
    &.for_upload {
      margin: 0;
      padding-top: 70px;
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
  padding: 10px;
}
.box_2 {
  .right_txt {
    position: absolute;
    right: 17px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 13px;
    color: #999999;
  }
  width: 100%;
  position: relative;
  font-size: 18px;
  color: #333333;
  font-weight: bold;
  padding: 15px 17px;
}
.box_1 {
  .box_info {
    &.wait {
      padding: 15px 15px 0 15px;
    }
    .box_bottom {
      .right_txt {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #999999;
        padding-right: 15px;
      }
      &::after {
        position: absolute;
        right: 16px;
        top: 22px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      width: 100%;
      border-top: 1px solid #f3f3f3;
      position: relative;
      padding: 15px 0 15px 30px;
      background: url("../../assets/images/company_man_info_ico.png") 13px
        center no-repeat;
      background-size: 11px 13px;
      font-size: 14px;
      color: #333333;
    }
    .box_top {
      .avatar_box {
        img {
          width: 56px;
          height: 56px;
          border: 0;
          border-radius: 7px;
        }
        position: absolute;
        left: 20px;
        top: 20px;
        box-shadow: 0 0 5px #c2c2c2;
        width: 56px;
        height: 56px;
      }
      .some {
        padding-bottom: 18px;
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .name {
        padding: 21px 0 11px;
        font-size: 18px;
        color: #333333;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      padding: 0 20px 0 90px;
      width: 100%;
    }
    position: relative;
    width: 340px;
    background-color: #ffffff;
    border-radius: 5px;
    margin: 0 auto;
    box-shadow: 0 1px 5px #c2c2c2;
  }
  position: relative;
  width: 100%;
  margin-bottom: 10px;
}
.my_app {
  background: url("../../assets/images/serve_index_top_bg.png") center 0
    no-repeat;
  background-size: 375px 191px;
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
