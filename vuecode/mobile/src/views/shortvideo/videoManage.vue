<template>
    <div id="app">
        <Head>视频管理</Head>
        <div class="notice_box">
              <div class="icon"></div>
              <van-notice-bar color="#ff6400" background="#fefbea">视频播放量超过{{playnum}}次，就可以自动上热门</van-notice-bar>
        </div>
        <div class="box_nav" v-if="parseInt($store.state.config.shortvideo_enable) == 1">
          <div
            class="item "
            :class="resumeShow == 'resume' ? 'active' : ''"
            @click="resumeShow = 'resume'"
          >
            我的视频
          </div>
          <div
            class="item "
            :class="resumeShow == 'video' ? 'active' : ''"
            @click="resumeShow = 'video'"
          >
            我的收藏
          </div>
        </div>
        <div class="context" v-if="resumeShow == 'resume'">
          <van-list
                class="list"
                v-model="loading"
                :finished="finished"
                :finished-text="finished_text"
                @load="onLoad"
                :immediate-check="false"
                v-if="show_empty != true"
                >
                    <div class="item" v-for="(item,index) in list" :key="index" @click="clickitem(item.id)">
                        <div class="top">
                            <div class="time">{{item.addtime_fmt}}</div>
                            <div :class="item.audit==1?'norelease':item.audit==2?'release':'notpass'">{{item.audit==1?'审核中':item.audit==2?'已审核':'审核不通过'}}</div>
                        </div>
                        <div class="info">
                            <div class="imgdiv">
                              <img :src="item.video_src+'?vframe/jpg/offset/1'" class="img">
                            </div>
                            <div class="centent">
                                <div class="title">{{item.title}}</div>
                                <div class="numdiv">
                                    <div class="play">播放：{{item.view_count}}次</div>
                                    <div class="favorites">被收藏：{{item.like}}次</div>
                                </div>
                            </div>
                        </div>
                        <div class="reason" v-if="item.reason">原因：{{item.reason}}</div>
                        <div class="bottom">
                            <div class="btndiv">
                                <div class="itembtn" @click.stop="updinfo(item.id)">修改</div>
                                <div class="itembtn" v-if="item.is_public=='2'" @click.stop="colse(item.id)">关闭</div>
                                <div class="itembtn" v-if="item.is_public=='1'" @click.stop="open(item.id)">开启</div>
                                <div class="itembtn" @click.stop="del(item.id)">删除</div>
                            </div>
                        </div>
                    </div>
                </van-list>
                <van-empty
                class="list two clearfix"
                image="search"
                description="没有找到对应的数据"
                style="background-color:#fff"
                v-if="show_empty == true"
                />
        </div>
        <div class="context" v-if="resumeShow == 'video'">
          <VideoList class="videolist" :videotype="utype" :gointype="'mycollection'" :id="''"></VideoList>
        </div>
        <div class="btn" v-if="utype == 2" @click="release">发布视频招聘</div>
        <div class="btn" v-else @click="release">发布视频简历</div>
    </div>
</template>
<script>
import VideoList from './components/VideoListtwo'
import http from '@/utils/http'
import api from '@/api'

export default {
  components: {VideoList},
  data () {
    return {
      resumeShow: 'resume',
      loading: false,
      finished: false,
      finished_text: '',
      page: 1,
      pagesize: 15,
      show_empty: false,
      type: '1',
      params: {
      },
      list: [],
      listtwo: [],
      utype: '',
      playnum: '0'
    }
  },
  created () {
    if (this.$store.state.LoginOrNot == true) {
      this.utype = this.$store.state.LoginType == 1 ? '2' : '1'
      this.playnum = this.$store.state.LoginType == 2 ? this.$store.state.config.shortvideo_finding_hot : this.$store.state.config.shortvideo_jobing_hot
    }
    this.fetchData(true)
  },
  methods: {
    onLoad () {
      this.page++
      if (this.resumeShow == 'resume') {
        this.fetchData(false)
      }
    },
    fetchData (init) {
      this.show_empty = false
      let conditions = { ...this.params }
      if (init == true) {
        this.page = 1
        this.finished_text = ''
        this.finished = false
      }
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      this.loading = true
      http
        .get(api.shortvideo_myvideo, conditions)
        .then((res) => {
          if (init == true) {
            this.list = [...res.data]
          } else {
            this.list = this.list.concat(res.data)
          }
          // 加载状态结束
          this.loading = false

          // 数据全部加载完成
          if (res.data.length < this.pagesize) {
            this.finished = true
            if (init == false) {
              this.finished_text = '没有更多了'
            } else if (res.data.length == 0) {
              this.show_empty = true
            }
          }
        })
        .catch(() => {})
    },
    updinfo (id) {
      this.$router.push({
        path: '/shortvideo/release',
        query: {
          id: id
        }
      })
    },
    open (id) {
      var that = this
      that.$dialog
        .confirm({
          title: '系统提示',
          message: '确定要开启吗?'
        })
        .then(() => {
          const param = {
            id: id,
            public: 2
          }
          http
            .post(api.shortvideo_isopen, param)
            .then(res => {
              if (parseInt(res.code) == 200) {
                this.$notify({ type: 'success', message: res.message })
                this.fetchData(true)
              } else {
                this.$notify(res.message)
              }
            }).catch(err => {
              console.log(err)
            })
        })
        .catch(() => {})
    },
    colse (id) {
      var that = this
      that.$dialog
        .confirm({
          title: '系统提示',
          message: '视频关闭后将不再对外显示，确定要关闭吗?'
        })
        .then(() => {
          const param = {
            id: id,
            public: 1
          }
          http
            .post(api.shortvideo_isopen, param)
            .then(res => {
              if (parseInt(res.code) == 200) {
                this.$notify({ type: 'success', message: res.message })
                this.fetchData(true)
              } else {
                this.$notify(res.message)
              }
            }).catch(err => {
              console.log(err)
            })
        })
        .catch(() => {})
    },
    del (id) {
      var that = this
      that.$dialog
        .confirm({
          title: '系统提示',
          message: '视频删除后不可恢复，确定要删除吗?'
        })
        .then(() => {
          const param = {
            id: id
          }
          http
            .post(api.shortvideo_del, param)
            .then(res => {
              if (parseInt(res.code) == 200) {
                this.$notify({ type: 'success', message: res.message })
                this.fetchData(true)
              } else {
                this.$notify(res.message)
              }
            }).catch(err => {
              console.log(err)
            })
        })
        .catch(() => {})
    },
    clickitem (id) {
      this.$router.push({
        path: '/shortvideo/videoplay',
        query: {
          id: id,
          gointype: 'myplay',
          listid: 0,
          videotype: this.$store.state.LoginType
        }
      })
    },
    release () {
      if (parseInt(this.$store.state.config.shortvideo_enable) == 1) {
        this.$router.push('/shortvideo/release')
      } else {
        var that = this
        that.$dialog
          .confirm({
            title: '系统提示',
            message: '抱歉，暂不支持发布视频'
          })
          .then(() => {
          })
          .catch(() => {})
      }
    }
  }
}
</script>
<style lang="scss" scoped>
#app{
    position: absolute;
    width: 100vw;
    height: 100%;
    line-height: 1;
}
.notice_box{
    border-top: 5px solid #f3f3f3;
    display: flex;
    height: 38px;
    font-size: 12px;
    background: #fefbea;
    color: #ff6400;
    align-items: center;
    margin-bottom: 10px;
    .van-notice-bar{
        height: 100%;
        flex: 8;
        padding-left: 0;
    }
    .icon{
        flex: 1;
        height: 100%;
        border-radius: 4px;
        background: url('../../assets/images/remind_ico.svg') 10px center no-repeat #fefbea;
        background-size: 15px 15px;
    }
}
.box_nav {
  .item {
    &.active {
      &::after {
        content: " ";
        position: absolute;
        bottom: 7px;
        left: 50%;
        transform: translate(-50%, 0);
        width: 17px;
        height: 3px;
        background-color: #1787fb;
        border-radius: 3px;
      }
      color: #1787fb;
    }
    flex: 1;
    position: relative;
    padding: 15px 0;
    text-align: center;
    font-size: 17px;
    font-weight: bold;
    color: #333333;
  }
  width: 100%;
  background-color: #ffffff;
  display: flex;
  box-shadow: 0 1px 5px #f1eded;
  z-index: 1;
  position: relative;
  margin-top: 10px;
}
.context{
    height: calc(100% - 53px - 37.5px - 120px);
    overflow: auto;
    position: relative;
    .list{
        height: 100%;
        border-top: 5px solid #f2f4f3;
        .item{
            background-color: #FFFFFF;
            border-bottom: 5px solid #f2f4f3;
            padding: 17px;
            .top{
                position: relative;
                display: flex;
                align-items: center;
                .time{
                    background: url('../../assets/images/shortvideo/time.png') 0 center no-repeat;
                    background-size: 11px 11px;
                    padding-left: 19px;
                    font-size: 12px;
                    color: #555555;
                }
                .norelease{
                    position: absolute;
                    right: 0px;
                    padding: 8px 6px;
                    background-color: #fffbf8;
                    font-size: 12px;
                    color: #ff7200;
                }
                .release{
                    position: absolute;
                    right: 0px;
                    padding: 8px 6px;
                    background-color: #f4f9ff;
                    font-size: 12px;
                    color: #1787fb;
                }
                .notpass{
                    position: absolute;
                    right: 0px;
                    padding: 8px 6px;
                    background-color: #fff5f5;
                    font-size: 12px;
                    color: #ff0000;
                }
            }
            .info{
                margin-top: 17px;
                display: flex;
                .imgdiv{
                  width: 65px;
                  height: 68px;
                  background-color: #000;
                  border-radius: 10px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  overflow: hidden;
                  img{
                    width: 65px;
                  }
                }
                .centent{
                    width: calc(100vw - 34px - 65px - 8px);
                    position: relative;
                    margin-left: 8px;
                    .title{
                        line-height: 1.5;
                        font-size: 15px;
                        color: #333333;
                        font-weight: bold;
                        display: -webkit-box;
                        -webkit-box-orient: vertical;
                        -webkit-line-clamp: 2;
                        overflow: hidden;
                    }
                    .numdiv{
                        width: calc(100vw - 34px - 65px - 8px);
                        position: absolute;
                        bottom: 5px;
                        display: flex;
                        font-size: 12px;
                        color: #555555;
                        .play{
                            flex: 1;
                        }
                        .favorites{
                            flex: 1;
                        }
                    }
                }
            }
            .reason{
                margin-top: 12px;
                font-size: 13px;
                color: #ff2424;
                padding-bottom: 16px;
                border-bottom: 1px solid #f2f2f2;
            }
            .bottom{
                position: relative;
                margin-top: 13px;
                height: 27px;
                .btndiv{
                    position: absolute;
                    right: 0px;
                    display: flex;
                    .itembtn{
                        width: 61px;
                        height: 25px;
                        color: #555555;
                        font-size: 13px;
                        border-radius: 50px;
                        border: 1px solid #d8d8d8;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin-left: 12px;
                    }
                }
            }
        }
    }
    .videolist{
      position: absolute;
    }
}
.context::-webkit-scrollbar{
        display: none;
    }
.btn{
    position: absolute;
    bottom: 14px;
    left: 0;
    right: 0;
    margin: 0 auto;
    width: 322px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #FFFFFF;
    background-color: #1989fa;
    border-radius: 50px;
}
</style>
