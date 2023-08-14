<template>
  <div>
      <van-popup v-model="interviewPopup" :style="{ width:'100%', height: '100%',background:'transparent'}">
        <swiper ref="swiper" :options="swiperOption" v-if="list.length>0">
          <swiper-slide v-for="(item,index) in list" :key="index">
            <div :class="item.isOverdue ? 'item-con beOverdue' : 'item-con'">
              <div class="item-con-title">面试安排</div>
              <div class="item-con-info">
                <div class="item-con-t">
                  <div class="lable">面试职位：</div>
                  <div class="text">{{item.jobname}}</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">面试时间：</div>
                  <div class="text">{{item.interview_time}} </div>
                </div>
                <div class="item-con-t">
                  <div class="lable">联系方式：</div>
                  <div class="text">{{item.tel}}</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">面试地址：</div>
                  <div class="text">{{item.address}}</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">备注信息：</div>
                  <div class="text">{{item.note}}</div>
                </div>
              </div>
              <div class="item-con-btn" @click="handleInterviewArrangePopup">确认</div>
            </div>
          </swiper-slide>

          <!-- <swiper-slide>
            <div class="item-con">
              <div class="item-con-title">面试安排</div>
              <div class="item-con-info">
                <div class="item-con-t">
                  <div class="lable">面试职位：</div>
                  <div class="text">jobname</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">面试时间：</div>
                  <div class="text">interview_time</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">联系方式：</div>
                  <div class="text">tel</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">面试地址：</div>
                  <div class="text">address</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">备注信息：</div>
                  <div class="text">note</div>
                </div>
              </div>
              <div class="item-con-btn" @click="handleInterviewArrangePopup">确认</div>
            </div>
          </swiper-slide> -->
          <!-- <swiper-slide>
            <div class="item-con">
              <div class="item-con-title">面试安排</div>
              <div class="item-con-info">
                <div class="item-con-t">
                  <div class="lable">面试职位：</div>
                  <div class="text">jobname</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">面试时间：</div>
                  <div class="text">interview_time</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">联系方式：</div>
                  <div class="text">tel</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">面试地址：</div>
                  <div class="text">address</div>
                </div>
                <div class="item-con-t">
                  <div class="lable">备注信息：</div>
                  <div class="text">note</div>
                </div>
              </div>
              <div class="item-con-btn" @click="handleInterviewArrangePopup">确认</div>
            </div>
          </swiper-slide> -->

        </swiper>
        <div class="item-con item-width" v-else>
          <div class="item-con-title">面试安排</div>
          <div class="item-con-info">
            <div class="empty">
              该企业未向您发出面试邀请
            </div>

          </div>
          <div class="item-con-btn" @click="handleInterviewArrangePopup">确认</div>
        </div>
      </van-popup>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {mapState} from 'vuex'
export default {
  name: 'InterviewArrange',
  data () {
    return {
      // 窗口打开状态
      interviewPopup: false,
      // swiper参数
      swiperOption: {
        loop: false,
        slidesPerView: '.75',
        centeredSlides: false
      },
      list: []
    }
  },
  computed: {
    ...mapState({
      imShowParams: state => state.imShowParams
    })
  },
  methods: {
    /**
     * 打开面试安排窗口
     */
    handleOpenInterviewArrange () {
      this.interviewPopup = true
      this.getInterviewArrangeList()
    },
    /** 关闭窗口 */
    handleInterviewArrangePopup () {
      this.interviewPopup = false
      // this.$refs.swiper.destroySwiper()
      this.list = []
    },
    // 获取列表
    getInterviewArrangeList () {
      http.post(api.im_interview_info, {
        resumeid: this.imShowParams.resumeid,
        jobid: this.imShowParams.jobid
      }).then(res => {
        if (res.code == 200) {
          if (res.data.items.length > 0) {
            this.list = res.data.items
            if (this.list.length > 1) {
              this.swiperOption = {
                loop: false,
                slidesPerView: '1.15',
                centeredSlides: true
              }
            }
            this.list.forEach(item => {
              let oldTime = new Date(item.interview_time).getTime()
              let curTime = new Date().getTime()
              item.isOverdue = false
              if (curTime > oldTime) {
                item.isOverdue = true
              }
            })
          }
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
>>> .van-swipe__indicators{
  display: none;
}
>>>.swiper-slide{
  width: auto;
}
.van-popup {
    display: flex;
    align-items: center;
    justify-content: center;
}
.beOverdue{
  background: url('../../../assets/images/im/InterviewArrange.png') no-repeat 240px 10px / 60px 53px;
}
.item-width{
  width: 320px;
}
.item-con{
    // width: 320px;
    height: 294px;
    background-color: #fff;
    border-radius: 15px;
    margin: 0 10px;
    .item-con-title{
      text-align: center;
      color: #666666;
      font-size: 16px;
      padding: 22px 0;
      font-weight: bold;
    }
    .item-con-info{
      height: 170px;
      padding: 0 17px;
      .item-con-t{
        display: flex;
        line-height: 27px;
        .lable{
          color: #999999;
          font-size: 14px;
          width: 70px;
          flex-shrink: 0;
        }
        .text{
          color: #333333;
          font-size: 14px;
          white-space:normal;
        }
      }
    }
    .empty{
      height: 100%;
      background: url('../../../assets/images/im/empty.png') no-repeat top center /125px 125px ;
      color: #969799;
      font-size:14px ;
      text-align: center;
      padding-top: 135px;
    }
    .item-con-btn{
      font-size: 15px;
      color: #1787FB;
      text-align: center;
      border-top: 1px solid #eeeeee;
      padding: 17px 0;
    }
}
</style>
