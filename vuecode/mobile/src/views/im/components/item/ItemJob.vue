<template>
    <div class="im-item-box">
        <div class="im-time">
            {{itemJob.datetime}}
        </div>
        <div class="job-box" @click="handleLink">
        <div class="job-top" >
            <div class="job-info">
            <div class="job-name substring">{{itemJob.message.jobname}}</div>
            <div class="job-salary substring">{{itemJob.message.wage_text}}</div>
            </div>
            <div class="job-demand">
            <div class="demand-item">{{itemJob.message.education_text}}</div>
            <div class="demand-item">{{itemJob.message.experience_text}}</div>
            <div class="demand-item">{{itemJob.message.district_text}}</div>
            </div>
        </div>
        <div class="job-bottom">
            <!-- <div class="company-info-box">
              <div class="company-img">
                  <img :src="itemJob.avatar" alt="">
              </div>
              <div class="company-info">
                  <div class="company-name substring">葛经理</div>
                  <div class="company-text substring">{{itemJob.message.companyname}}</div>
              </div>
            </div> -->
            <div class="company-time">{{itemJob.addtime | interviewTimeFilter}} 由{{itemJob.self_side==1?'你':'对方'}}发起的沟通</div>
        </div>
        </div>
    </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
import {mapState} from 'vuex'
export default {
  props: ['itemJob'],
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
      imShowParams: state => state.imShowParams
    })
  },
  methods: {
    handleLink () {
      this.$router.push({path: '/job/' + this.imShowParams.jobid})
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
.job-box{
  width: 345px;
  max-height: 275px;
  background: #FFFFFF;
  border-radius: 5px;
  margin: 0 auto;
  font-size: 14px;
  padding: 0 15px;
  .job-top{
    padding:18px 0 18px;
    border-bottom: 1px solid #e8e8e8;
    .job-info{
      display: flex;
      margin-bottom: 10px;
      .job-name{
        flex: 2.5;
        color:#2E2E2E ;
        font-size:18px ;
        font-weight: bold;
        margin-right: 30px;
      }
      .job-salary{
        flex: 1;
        color:#FF5017 ;
        font-size:16px ;
        text-align: right;
      }
    }
    .job-demand{
      display: flex;
      flex-wrap: wrap;
      .demand-item{
        color: #666666;
        font-size: 13px;
        margin-right: 6px;
        background: #F8F8F8;
        padding: 4px 8px;
        border-radius: 5px;
        &:last-child{
          margin-right: 0;
        }
      }
    }
  }
  .job-bottom{
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
      .company-info{
        width: calc(345px - 32px - 17px - 32px);
        .company-name{
          color: #333333;
          font-size: 13px;
          font-weight: bold;
        }
        .company-text{
          color: #999999;
          font-size: 12px;
        }
      }
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
      margin:22px auto 0;
      &.delivery-btn-ac{
        background: #CECECE;
      }
    }
  }
}
//发送职位结束 / 企业邀请投递简历结束
</style>
