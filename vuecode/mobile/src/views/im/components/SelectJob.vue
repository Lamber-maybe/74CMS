<template>
    <div class="select-job-wrapper">
        <div class="job-title">
          {{isSelectJob ? '切换沟通职位' : '沟通职位'}}
            <van-icon class="title-cross" name="cross" @click="handleCloseSelectJob" />
        </div>
        <div class="job-list">
            <div class="job-list-item" v-for="(item,index) in jobList" :key="index">
                <div class="job-name substring">{{item.jobname}}</div>
                <div class="job-salary substring">{{item.wage_text}}</div>
                <div class="job-request substring">{{item.education_text}} · {{item.experience_text}} · {{item.district_text}}</div>
                <div v-if="isSelectJob">
                  <van-button v-if="item.id == jobId" class="select-job-btn" type="info" size="small" block plain round>沟通中</van-button>
                  <van-button v-else class="select-job-btn" type="info" size="small" block plain round @click="handleSelectJob(item)">切换</van-button>
                </div>
                <div v-else>
                  <van-button class="select-job-btn" type="info" size="small" block plain round @click="handleCommunicate(item)">沟通</van-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {mapState, mapMutations} from 'vuex'
export default {
  props: ['chatid', 'companyId', 'isSelectJob'],
  data () {
    return {
      jobList: [],
      curtName: ''
    }
  },
  watch: {
    companyId (newValue) {
      if (newValue) {
        this.getJobList()
      }
    }
  },
  created () {
    if (this.isSelectJob && (this.companyId != '' || this.companyId != 0)) {
      this.getJobList()
    }
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken,
      jobId: state => state.imShowParams.jobid,
      imShowParams: state => state.imShowParams,
      LoginType: state => state.LoginType
    })
  },
  methods: {
    ...mapMutations(['setImShowParams']),
    /**
     * 获取职位列表
     */
    getJobList () {
      http.get(api.job_list, {company_id: this.companyId})
        .then((res) => {
          if (res.code == 200) {
            this.jobList = res.data.items
            if (this.isSelectJob && this.companyId != '') {
              this.curtName = this.jobList.filter(res => {
                return res.id == this.jobId
              })[0]
              this.$emit('getJobName', this.curtName ? this.curtName.jobname : '')
            }
          }
        })
    },
    /**
     * 关闭切换窗口
     */
    handleCloseSelectJob () {
      this.$emit('handleCloseSelectJob')
    },
    /**
     * 切换职位
     * @item 当前点击职位信息
     */
    handleSelectJob (item) {
      http.post(api.change_job, {
        token: this.imToken,
        jobid: item.id,
        chat_id: this.chatid
      }).then((res) => {
        if (res.code == 200) {
          this.$notify({
            type: 'success',
            message: '切换成功'
          })
          this.imShowParams.jobid = item.id
          this.setImShowParams(this.imShowParams)
          this.getJobList()

          this.$emit('handleSelectJob', item)
        }
      })
    },
    /**
     * 沟通职位
     * @item 当前点击职位信息
     */
    handleCommunicate (item) {
      this.$emit('handleCommunicate', item)
    }
  }
}
</script>

<style lang="scss" scoped>
// 切换职位开始
.select-job-wrapper{
  width:328px ;
  height: 339px;
  background: #fff;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%,-50%);
  border-radius: 15px;
  padding: 0 19px;
  .job-title{
      position: relative;
      text-align: center;
      color: #666666;
      font-size: 17px;
      padding: 16px 0;
      .title-cross{
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
      }
  }
  .job-list{
      overflow-y: auto;
      height: 273px;
      .job-list-item{
          position: relative;
          height: 91px;
          border-bottom: 1px solid #EEEEEE;
          padding: 12px 0;
          .job-name{
              max-width:200px;
              color:#333333;
              font-size:15px;
              font-weight: bold;
          }
          .job-salary{
              max-width:200px;
              color: #FF5017;
              font-size: 14px;
              line-height: 24px;
          }
          .job-request{
              max-width:200px;
              font-size: 13px;
              color: #666666;
          }
          .select-job-btn{
              width: 65px;
              height: 25px;
              position: absolute;
              top: 50%;
              right: 0px;
              transform: translateY(-50%);
          }
      }
  }
}
// 切换职位结束
</style>
