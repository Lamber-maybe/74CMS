<template>
    <div class="im-item-box">
        <div class="im-time">
            {{itemInvite.datetime}}
        </div>
        <div class="delivery-title">
            <!-- 企业邀请您投递简历 -->
            {{itemInvite.self_side == 1?'邀请对方投递简历':'企业邀请您投递简历'}}
        </div>
        <div class="delivery-box">
            <div class="delivery-top">
                <div class="delivery-info">
                    <div class="delivery-name substring">{{itemInvite.message.jobname}}</div>
                    <div class="delivery-salary substring">{{itemInvite.message.wage_text}}</div>
                </div>
                <div class="delivery-demand">
                    <div class="demand-item">{{itemInvite.message.education_text}}</div>
                    <div class="demand-item">{{itemInvite.message.experience_text}}</div>
                    <div class="demand-item">{{itemInvite.message.district_text}}</div>
                </div>
            </div>
            <div class="delivery-bottom">
                <!-- <div class="company-info-box">
                  <div class="company-img">
                      <img src="https://img-qn.51miz.com/preview/element/00/01/14/42/E-1144226-3AD97E15.jpg!/quality/90/unsharp/true/compress/true/fwfh/830x400" alt="">
                  </div>
                  <div class="company-info">
                      <div class="company-name substring">葛经理</div>
                      <div class="company-text substring">江苏熠华建设工程有限公司</div>
                  </div>
                </div> -->
                <!-- delivery-btn-ac -->
                <div v-if="itemInvite.self_side == 1">
                  <div class="delivery-btn delivery-btn-ac" v-if="itemInvite.message.status==0">等待对方投递</div>
                  <div class="delivery-btn" v-else @click="handleSeeResume">对方已投递 立即查看</div>
                </div>
                <div v-else>
                  <div class="delivery-btn" v-if="itemInvite.message.status == 0" @click="handleDeliverResume">投递简历</div>
                  <div class="delivery-btn delivery-btn-ac" v-else>已投递</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
import {mapActions, mapState} from 'vuex'
export default {
  props: ['itemInvite'],
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
    interviewTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken
    })
  },
  methods: {
    ...mapActions(['webSocket_send']),
    /**
     * 投递简历
     */
    handleDeliverResume () {
      var msgObj = {
        controller: 'SendInvite',
        action: 'applyjob',
        args: {
          token: this.imToken,
          messageid: this.itemInvite.messageid
        }
      }
      this.webSocket_send(msgObj)
    },
    /**
     * 查看简历
     */
    handleSeeResume () {
      this.$router.push('/resume/' + this.itemInvite.message.resumeid)
    }
  }
}
</script>
<style lang="scss" scoped>
//发送职位开始 / 企业邀请投递简历开始
.delivery-title{
  width: 345px;
  color:#333333 ;
  font-size:18px ;
  height: 49px;
  line-height: 49px;
  font-weight: bold;
  border-bottom: 1px solid #e8e8e8;
  margin: 0 auto;
  background: #fff;
  padding: 0 15px;
}
.delivery-box{
  width: 345px;
  max-height: 275px;
  background: #FFFFFF;
  border-radius: 5px;
  margin: 0 auto;
  font-size: 14px;
  padding: 0 15px;
  .delivery-top{
    padding:18px 0 23px;
    border-bottom: 1px solid #e8e8e8;
    .delivery-info{
      display: flex;
      margin-bottom: 16px;
      .delivery-name{
        flex: 2.5;
        color:#2E2E2E ;
        font-size:18px ;
        font-weight: bold;
        margin-right: 30px;
      }
      .delivery-salary{
        flex: 1.5;
        color:#FF5017 ;
        font-size:16px ;
      }
    }
    .delivery-demand{
      display: flex;
      flex-wrap: wrap;
      .demand-item{
        color: #666666;
        font-size: 14px;
        margin-right: 6px;
        background: #F8F8F8;
        padding: 5px 8px;
        &:last-child{
          margin-right: 0;
        }
      }
    }
  }
  .delivery-bottom{
    padding: 16px 0 12px;
    .company-info-box{
      display: flex;
      margin-bottom: 13px;
      .company-img{
        width: 32px;
        height: 32px;
        margin-right: 17px;
        img{
          width: 100%;
          height: 100%;
        }
      }
      // .company-info{
      //   width: calc(345px - 32px - 17px - 32px);
      //   .company-name{
      //     color: #333333;
      //     font-size: 13px;
      //     font-weight: bold;
      //   }
      //   .company-text{
      //     color: #999999;
      //     font-size: 12px;
      //   }
      // }
    }
    .company-time{
      color:#999999 ;
      font-size:12px ;
    }
    .delivery-btn{
      width: 250px;
      height: 35px;
      background: #1787FB;
      border-radius: 17px;
      text-align: center;
      line-height: 35px;
      color: #fff;
      margin:0 auto 0;
      &.delivery-btn-ac{
        background: #CECECE;
      }
    }
  }
}
</style>
