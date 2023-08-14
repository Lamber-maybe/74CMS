<template>
  <div id="app">
    <Head>视频发布</Head>
    <div class="notice_box">
      <div class="icon"></div>
      <van-notice-bar color="#ff6400" background="#fefbea">所发布的信息必须真实、准确，不得发布违法、违规、虚假、欺诈、涉黄等信息。</van-notice-bar>
    </div>
    <div class="title">上传视频</div>
    <div>
      <textarea v-model="sub.title" :placeholder="$store.state.LoginOrNot == true&&this.$store.state.LoginType == 1?'简要描述一下招聘信息或企业形象(限50字)':'简要描述一下求职期望、工作经验、技能特长等(限50字)'" maxlength="50"></textarea>
      <div class="video" v-if="imgshow" ref="videoImg">
        <div class="imgurl_box">
          <div class="img" :style="{'background':`url(${imgurl}) 50% 50% / cover no-repeat`}" @click="previewVideo"></div>
        </div>
        <div class="delete" @click="delvideo" v-if="progress=='100%'"></div>
        <div class="progress" v-if="isUpload">
          <i :style="{'width':progress}" class="progress_width"></i>
        </div>
      </div>
    </div>
    <div class="btndiv">
      <van-uploader
        :preview-image="false"
        :after-read="uploadVideo"
        :accept="'video/*'"
      >
        <div class="upload">上传视频</div>
      </van-uploader>
      <div class="import" @click="show=true">抖音导入</div>
    </div>
    <div class="titlediv">
      <div class="title">您的位置</div>
      <div class="titletisp" v-if="$store.state.config.is_open_map == 1">地址为自动获取，点击标注可修改位置</div>
      <div class="titletisp" v-else>地址为自动获取</div>
    </div>
    <div class="addressdiv">
      <div class="addresss">{{sub.address}}</div>
      <div class="addresssicon" @click="handlerShowMap" v-if="$store.state.config.is_open_map == 1">标注</div>
    </div>
    <van-popup
      v-model="showMap"
      position="top"
      :lazy-render="true"
      :overlay="true"
      style="width:100%;height:100%"
    >
      <Mapset
        ref="mapset"
        title="地图标注"
        :mapLat="sub.lat"
        :mapLng="sub.lon"
        :mapZoom="sub.map_zoom"
        :address="sub.address"
        @closeMap="handlerCloseMap"
      ></Mapset>
    </van-popup>
    <div class="release" @click="release">发布视频</div>
    <van-popup v-model="show" round position="center">
      <div class="popudiv">
        <div class="pptitle">获取抖音视频</div>
        <input v-model="douyinurl" type="text" class="ppinfo" placeholder="请把抖音视频分享的链接粘贴到此处"/>
        <div class="ppbtndiv">
          <div class="ppcancel" @click="show = false">取消</div>
          <div class="ppdefine" @click="douyin">确定</div>
        </div>
      </div>
    </van-popup>
    <van-popup
      v-model="updvideo"
      v-if="updvideo"
      round position="center"
      style="
         width:150px;
         height:150px;
         display: flex;
         justify-content: center;
         align-items: center;"
    >
      <van-loading size="24px" vertical>上传中...</van-loading>
    </van-popup>
    <baidu-map
      v-if="config.map_type == 1"
      class="bm-view"
      :ak="$store.state.config.map_ak"
      @ready="handlerMap"
    ></baidu-map>
    <TianMap
      class="bm-view"
      v-if="config.map_type == 2"
      :ak="config.tian_map_ak"
      @ready="handlerTianMap"
    >
    </TianMap>
    <div class="videodiv" @click="closevideo" v-if="videoshow">
      <video :src="videosrc" ref="video" @timeupdate="updateTime()" @click.stop="handleClickSlide()"
             style="width:321px;"></video>
      <div class="videoshow" v-if="playshow" @click.stop="handleClickSlide()"></div>
    </div>
  </div>
</template>
<script>
import Head from '../../components/Head.vue'
import Mapset from '@/components/Mapset'
import http from '@/utils/http'
import api from '@/api'
import Vue from 'vue'
import TianMap from '@/components/map/TianMap/TianMap'
import * as qiniu from 'qiniu-js'
import {mapState} from 'vuex'
let isSpider = new RegExp('^(Baiduspider|YisouSpider|Sogou|Googlebot|Sosospider|bingbot|360Spider)').test(navigator.userAgent)
Vue.component('BaiduMap', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/map/Map.vue'], resolve)
  }
})

export default {
  components: {
    Head,
    Mapset,
    TianMap
  },
  data () {
    return {
      updvideo: false,
      imgshow: false,
      showMap: false,
      show: false,
      douyinurl: '',
      imgurl: require('../../assets/images/shortvideo/default.jpg'),
      sub: {
        id: '',
        fid: '',
        title: '',
        lat: 0,
        lon: 0,
        map_zoom: 0,
        address: '',
        filesize: ''
      },
      BMap: {},
      qiniu_accessKey: '',
      qiniu_token: '',
      pre_key: '',
      videosrc: '',
      videoshow: false,
      playshow: true,
      progress: '',
      isUpload: false,
      TMap: {}
    }
  },
  created () {
    if (this.$route.query.id) {
      this.sub.id = this.$route.query.id
      this.getData()
    }
    this.getqiniu()
  },
  computed: {
    ...mapState(['config'])
  },
  methods: {
    previewVideo () {
      if (this.videosrc) {
        this.videoshow = true
      }
    },
    handlerTianMap ({TMap}) {
      this.TMap = TMap
      console.log(this.TMap)
      this.setTianlocation()
    },
    setTianlocation () {
      let that = this
      that.sub.address = '正在获取位置信息...'
      let TMap = that.TMap
      var geolocation = new TMap.Geolocation()
      var geocoder = new TMap.Geocoder()
      geolocation.getCurrentPosition(
        function (r) {
          if (this.getStatus() == 0) {
            geocoder.getLocation(r.lnglat, (data) => {
              const {result: {result: {addressComponent: {city, county}, location}}} = data
              const cityText = city + county
              that.sub.address = cityText
              that.sub.lat = location.lat
              that.sub.lon = location.lng
            })
          } else {
            that.sub.address = '获取定位失败'
          }
        },
        {enableHighAccuracy: true}
      )
    },
    handlerMap ({BMap, map}) {
      this.BMap = BMap
      this.setlocation()
    },
    setlocation () {
      let that = this
      that.sub.address = '正在获取位置信息...'
      let BMap = that.BMap
      var geolocation = new BMap.Geolocation()
      geolocation.getCurrentPosition(
        function (r) {
          if (this.getStatus() == BMAP_STATUS_SUCCESS) {
            that.sub.address = r.address.province + r.address.city
            that.sub.lat = r.point.lat
            that.sub.lon = r.point.lng
          }
        },
        {enableHighAccuracy: true}
      )
    },
    getData () {
      http.get(api.shortvideo_videodetail, {id: this.sub.id}).then(res => {
        this.sub.fid = res.data.fid
        this.sub.title = res.data.title
        this.sub.lat = res.data.lat
        this.sub.lon = res.data.lon
        this.sub.address = res.data.address
        this.imgurl = res.data.video_src + '?vframe/jpg/offset/1'
        this.imgshow = true
      }).catch(() => {
      })
    },
    // 获取七牛的地址
    getqiniu () {
      http.get(api.shortvideo_qiniu).then(res => {
        this.qiniu_accessKey = res.data.accessKey
        this.qiniu_token = res.data.token
        this.pre_key = res.data.key
      }).catch(() => {
      })
    },
    upload (file) {
      this.sub.filesize = file.size
      let key = new Date().getTime() + Math.random(1000) * 1000
      let suffix = file.name.substring(file.name.lastIndexOf('.'))
      key += suffix
      const putExtra = {
        fname: key + suffix
      }
      const config = {
        useCdnDomain: true
      }
      var observable = qiniu.upload(file, key, this.qiniu_token, putExtra, config)
      this.videosrc = ''
      observable.subscribe({
        next: (result) => {
          // 主要用来展示进度
          let progress = result.total.percent * 0.75
          this.progress = progress + '%'
        },
        error: (err) => {
          // 上传错误后触发
          console.log(err)
        },
        complete: (result) => {
          // 上传成功后触发。包含文件地址。
          http.post(api.shortvideo_address, {file_path: this.pre_key}).then(res => {
            if (parseInt(res.code) == 200) {
              let goon = true
              let waiting = () => {
                if (!goon) {
                  this.$notify({type: 'success', message: res.message})
                  this.sub.fid = res.data.file_id
                  this.imgurl = res.data.file_url + '?vframe/jpg/offset/0/'
                  this.videosrc = res.data.file_url
                  this.imgshow = true
                  this.updvideo = false
                  this.progress = '100%'
                  this.getqiniu()
                } else {
                  let img = new Image()
                  img.src = res.data.file_url + '?vframe/jpg/offset/0/' + Math.random()
                  img.onload = () => {
                    goon = false
                    waiting()
                  }
                  img.onerror = () => {
                    let num = parseInt(this.progress)
                    num += 2
                    if (num >= 99) {
                      num = 99
                    } else {
                      this.progress = num
                    }
                    this.progress = num + '%'
                    setTimeout(waiting, 1000)
                  }
                }
              }
              waiting()
            } else {
              this.$notify(res.message)
            }
          }).catch(() => {
          })
        }
      })
    },
    // 视频上传
    uploadVideo (file) {
      // this.updvideo = true
      this.imgshow = true
      this.isUpload = true
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['ogm', 'mpg', 'webm', 'wmv', 'asx', 'mov', 'mpeg', 'ogv', 'mp4', 'm4v', 'avi', 'quicktime']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      this.upload(file.file)
    },
    // 抖音视频上传
    douyin () {
      this.isUpload = false
      if (!this.douyinurl) {
        this.$notify('请把抖音视频分享的链接粘贴到此处')
        return false
      }
      var index = this.douyinurl.indexOf('http')
      if (index < 0) {
        this.$notify('粘贴的链接不正确')
        return false
      }
      var lastindex = this.douyinurl.lastIndexOf('复制此链接')
      if (lastindex <= 0) {
        lastindex = this.douyinurl.lastIndexOf('/ ')
      }
      var result = this.douyinurl.substring(index, lastindex)
      if (lastindex < index) {
        result = this.douyinurl.substring(index, this.douyinurl.length)
      }
      this.updvideo = true
      this.videosrc = ''
      http.get(api.shortvideo_douyin, {url: result}).then(res => {
        if (parseInt(res.code) == 200) {
          let goon = true
          let waiting = () => {
            if (!goon) {
              this.$notify({type: 'success', message: res.message})
              this.sub.title = res.data.title
              this.sub.fid = res.data.file_id
              this.sub.filesize = res.data.file_size
              this.imgurl = res.data.file_url + '?vframe/jpg/offset/0/'
              this.videosrc = res.data.file_url
              this.imgshow = true
              this.show = false
              this.updvideo = false
              this.douyinurl = ''
              this.getqiniu()
            } else {
              let img = new Image()
              img.src = res.data.file_url + '?vframe/jpg/offset/0/' + Math.random()
              img.onload = () => {
                goon = false
                waiting()
              }
              img.onerror = () => {
                setTimeout(waiting, 1000)
              }
            }
          }
          waiting()
        } else {
          this.$notify(res.message)
          this.updvideo = false
        }
      }).catch(() => {
        this.updvideo = false
      })
    },
    handlerShowMap () {
      this.showMap = true
    },
    handlerCloseMap (mapInfo) {
      this.showMap = false
      if (mapInfo == undefined) {
        return false
      }
      this.sub.lat = mapInfo.lat
      this.sub.lon = mapInfo.lng
      this.sub.map_zoom = mapInfo.zoom
      this.sub.address = mapInfo.address
      this.updvideo = false
    },
    delvideo () {
      this.progress = ''
      this.imgurl = require('../../assets/images/shortvideo/default.jpg')
      this.sub.fid = ''
      this.imgshow = false
    },
    release () {
      if (!this.sub.title) {
        this.$notify('请简要描述')
        return false
      }
      if (!this.sub.fid) {
        this.$notify('请上传视频')
        return false
      }
      http.postFormData(api.shortvideo_save, this.sub).then(res => {
        this.sub.fid = ''
        this.sub.title = ''
        this.$notify({type: 'success', message: res.message})
        this.$router.push({path: '/shortvideo/videoManage'})
      }).catch(() => {
      })
    },
    closevideo () {
      if (this.$refs.video) {
        if (this.$refs.video.paused) {
        } else {
          this.playshow = true
          this.$refs.video.pause()
        }
      }
      this.videoshow = false
    },
    updateTime () {
      if (this.$refs.video) {
        if (this.$refs.video.paused) {
          this.playshow = true
        } else {
          this.playshow = false
        }
      }
    },
    handleClickSlide () {
      if (this.$refs.video) {
        if (this.$refs.video.paused) {
          this.playshow = false
          this.$refs.video.play()
        } else {
          this.playshow = true
          this.$refs.video.pause()
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped>
  .notice_box {
    border-top: 5px solid #f3f3f3;
    display: flex;
    height: 38px;
    font-size: 12px;
    background: #fefbea;
    color: #ff6400;
    align-items: center;
    margin-bottom: 10px;

    .van-notice-bar {
      height: 100%;
      flex: 8;
      padding-left: 0;
    }

    .icon {
      flex: 1;
      height: 100%;
      border-radius: 4px;
      background: url('../../assets/images/remind_ico.svg') 10px center no-repeat #fefbea;
      background-size: 15px 15px;
    }
  }

  .tisp {
    margin: 13px 18px 0px 18px;
    padding: 7px 10px;
    font-size: 12px;
    color: #ff6600;
    background-color: #fdf6e6;
    border-radius: 3px;
    border: 1px solid #ffcdb1;
  }

  .title {
    margin: 18px 18px 0px 18px;
    font-size: 18px;
    color: #333333;
    font-weight: bold;
  }

  textarea {
    margin: 10px 18px 0px 18px;
    width: calc(100vw - 36px);
    height: 100px;
    line-height: 1.5;
    resize: none;
    border: none;
    font-size: 15px;
    color: #333333;
  }

  textarea::-webkit-input-placeholder {
    color: #c9c9c9;
  }

  .video {
    position: relative;
    margin: 0px 18px 0px 18px;
    width: 70px;
    height: 80px;
    // background-color: #cccccc;
    display: flex;
    align-items: center;
    justify-content: center;
    .imgurl_box{
      width: 100%;
      height: 100%;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      .img{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
      }
    }
    .progress{
      height: 10px;
      width: 100%;
      position:absolute;
      left: 0;
      right: 0;
      bottom: 0;
      .progress_width{
        width: 0%;
        display: block;
        height: 100%;
        background: #1787fb;
      }
    }
    .img {
      width: 70px;
    }

    .delete {
      width: 14px;
      height: 14px;
      position: absolute;
      top: -6px;
      right: -6px;
      background: url('../../assets/images/shortvideo/delete.png') center center no-repeat;
      background-size: 14px 14px;
    }
  }

  .btndiv {
    display: flex;
    border-bottom: 5px solid #f3f3f3;
    margin-top: 16px;
    padding-bottom: 26px;

    .upload {
      margin-left: 18px;
      background: url('../../assets/images/shortvideo/icon_video.png') 0 center no-repeat;
      background-size: 14px 14px;
      font-size: 14px;
      color: #666666;
      padding-left: 19px;
    }

    .import {
      margin-left: 24px;
      background: url('../../assets/images/shortvideo/icon_douyin.png') 0 center no-repeat;
      background-size: 12px 14px;
      font-size: 14px;
      color: #666666;
      padding-left: 17px;
    }
  }

  .titlediv {
    margin-top: 7px;
    position: relative;
    display: flex;

    .titletisp {
      position: absolute;
      font-size: 13px;
      color: #c9c9c9;
      bottom: 0px;
      right: 18px;
    }
  }

  .addressdiv {
    position: relative;
    display: flex;
    border-bottom: 5px solid #f3f3f3;
    margin-top: 21px;
    padding-bottom: 25px;

    .addresss {
      margin-left: 18px;
      font-size: 15px;
      color: #323233;
      width: calc(100vw - 20px - 70px);
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    .addresssicon {
      position: absolute;
      right: 18px;
      background: url('../../assets/images/shortvideo/add_address.png') right center no-repeat;
      background-size: 14px 14px;
      padding-right: 19px;
      color: #1a8afa;
      font-size: 15px;
    }
  }

  .release {
    margin: 34px 18px 0px 18px;
    height: 48px;
    font-size: 18px;
    color: #FFFFFF;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0px 0px 7px 0px rgba($color: #bddefe, $alpha: 1.0);
    border-radius: 50px;
    background-image: linear-gradient(to right, #4fa5fa, #1a8afa);
  }

  .popudiv {
    width: 85vw;
    background-color: #ffffff;
    text-align: center;

    .pptitle {
      margin-top: 20px;
      font-size: 17px;
      color: #333333;
      font-weight: bold;
    }

    .ppinfo {
      width: calc(85vw - 40px);
      height: 38px;
      margin-left: 20px;
      margin-right: 20px;
      margin-top: 25px;
      margin-bottom: 20px;
      border-radius: 50px;
      border: 1px solid #d5d5d5;
      font-size: 13px;
      padding-left: 15px;
      padding-right: 15px;
    }

    .ppinfo::-webkit-input-placeholder {
      color: #999999;
    }

    .ppbtndiv {
      width: 85vw;
      display: flex;
      border-top: 1px solid #f2f3f6;

      .ppcancel {
        flex: 1;
        height: 51px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #333333;
        font-size: 17px;
      }

      .ppdefine {
        flex: 1;
        height: 51px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #1989fa;
        font-size: 17px;
        border-left: 1px solid #f2f3f6;
      }
    }
  }

  .videodiv {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100vw;
    height: 100vh;
    background: rgba($color: #000000, $alpha: 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;

    .videoshow {
      position: absolute;
      margin: auto;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      width: 30px;
      height: 30px;
      background: url('../../assets/images/shortvideo/stop.png') center center no-repeat;
      background-size: 30px 30px;
    }
  }
</style>
