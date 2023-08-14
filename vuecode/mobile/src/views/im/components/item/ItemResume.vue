<template>
    <div class="im-item-box" >
        <div class="im-time">
          {{itemResume.datetime}}
        </div>
        <div class="resume-box" @click="handleLink">
            <div class="resume-top">
                <div class="resume-txt1">
                  <div class="resume-info">
                      <div class="resume-name substring">{{itemResume.message.fullname}}</div>
                      <div class="resume-reg substring">
                        <span>{{itemResume.message.age}}岁</span>
                        <span>{{itemResume.message.education}}</span>
                        <span>{{itemResume.message.experience}}</span>
                      </div>
                  </div>
                  <div class="resume-img">
                      <img :src="itemResume.avatar" alt="">
                  </div>
                </div>
                <div class="resume-txt2">
                <span class="label">期望：</span>
                <span class="job-txt">{{itemResume.message.intention_category}}</span>
                <span class="job-salary">{{itemResume.message.intention_wage}}</span>
                </div>
            </div>
            <div class="resume-bottom">
                <div class="resume-btm-txt1">{{itemResume.message.education_school}} · {{itemResume.message.education_major}}</div>
                <div class="resume-btm-txt2">{{itemResume.message.work_companyname}} · {{itemResume.message.work_jobname}}</div>
            </div>
        </div>
    </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
import {mapState} from 'vuex'
export default {
  props: ['itemResume'],
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
      if (this.imShowParams.resumeid) {
        this.$router.push({path: '/resume/' + this.imShowParams.resumeid})
      } else {
        this.$router.push({path: '/member/personal/preview'})
      }
    }
  }
}
</script>
<style lang="scss" scoped>
// 发送简历开始
.resume-box{
  width: 345px;
  max-height: 216px;
  background: #FFFFFF;
  border-radius: 10px;
  font-size: 14px;
  margin: 0 auto 20px;
  padding: 0 10px;
  .resume-top{
    padding: 22px 0px 10px 8px;
    .resume-txt1{
      display: flex;
      padding: 0px 0px 8px 0;
      .resume-img{
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-left: 10px;
        img{
          width: 100%;
          height: 100%;
          border-radius: 50%;
        }
      }
      .resume-info{
        .resume-name{
          flex-shrink:0 ;
          width: 250px;
          color: #333333;
          font-size: 18px;
          font-weight: bold;
          margin-bottom: 3px;
        }
        .resume-reg{
          flex-shrink:0 ;
          width: 250px;
          span{
            color: #999;
            font-size: 15px;
            position: relative;
            margin-right: 10px;
            &::before{
              content: '';
              position: absolute;
              right: -8px;
              top: 50%;
              width: 3px;
              height: 3px;
              background:#999;
              border-radius: 50%;
              transform: translateY(-50%);
            }
            &:last-child::before{
              display: none;
            }
          }
        }
      }
    }
    .resume-txt2{
      // margin-left: 12px;
      .label,
      .job-txt{
        color: #666666;
        font-size: 15px;
      }
      .job-salary{
        color: #FF5017;
        font-size: 15px;
        margin-left: 10px;
      }
    }
  }
  .resume-bottom{
    border-top: 1px dotted #CBCBCB;
    padding: 15px 0 15px 10px;
    color: #666666;
    font-size: 13px;
    .resume-btm-txt1{
      margin-bottom: 4px;
      padding-left: 25px;
      background: url('../../.././../assets/images/im/resume-bg-1.png') no-repeat left center;
      background-size: 16px 14px;
    }
    .resume-btm-txt2{
      padding-left: 25px;
      background: url('../../.././../assets/images/im/resume-bg-2.png') no-repeat left center;
      background-size: 16px 14px;

    }
  }
}
// 发送简历结束
</style>
