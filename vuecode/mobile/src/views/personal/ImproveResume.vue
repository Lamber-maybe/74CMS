<template>
    <div class="improve_resume">
      <Head>简历优化</Head>
      <div class="banner">
        <div class="t1">
          您的简历可优化{{defectResumeNum}}项
        </div>
        <div class="t2">
          您的简历完整度较差，严重影响求职<br/>
          质量，建议您立即完善
        </div>
      </div>
      <div class="resume_list_no_module">
        <div class="tips">
          完整的简历，更容易获得招聘方的关注
        </div>
        <div v-if="defectResumeAry.length>0">
          <div class="resume_module" v-for="(item,index) in defectResumeAry" :key="index">
            <div class="t1">补充{{item.remarks}}</div>
            <div class="t2">
              {{ defectResumeTextObj[item.field] }}
            </div>
            <div class="btn_box">
              <van-button @click="handleDefectResumeJump(item)" class="btn" type="info">去补充</van-button>
            </div>
          </div>
        </div>
        <van-empty v-else description="暂无优化项" />
      </div>
      <div class="footer">
        <router-link to="/member/personal/index">
          稍后再完善，点击进入会员中心<van-icon class="arrow_icon" name="arrow" />
        </router-link>
      </div>
    </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'ImproveResume',
  data () {
    return {
      defectResumeAry: [],
      defectResumeTextObj: {
        specialty: '您还没有添加自我描述，简单介绍下自己，向HR展示下您的优势。',
        education: '建议补充教育经历，详细的教育经历可以加大你面试的成功率',
        work: '建议补充工作经历，让HR看到你过往的工作能力',
        training: '建议补充培训经历，更好的展示你的学习能力和职业技能水平',
        project: '建议补充项目经历，向HR展示你对岗位的操作技能和胜任力',
        certificate: '建议补充证书获得情况，突出您的个人能力，以此来获得较高的入职希望',
        language: '建议补充语言能力，让HR一眼看出你的语言能力，让简历的效果事半功倍。',
        tag: '建议补充特长标签，更能体现出您的个人能力、特长、兴趣 ...',
        img: '建议补充自己的作品，让HR直观了解您的能力，增加面试邀请的机率'
      },
      defectResumeNum: 0
    }
  },
  created () {
    this.getDefectResumeInfo()
  },
  methods: {
    // 获取优化简历项
    getDefectResumeInfo () {
      http.get(api.defectResumeInfo).then((res) => {
        if (res.code == 200) {
          this.defectResumeAry = res.data.no_complete_array
          this.defectResumeNum = res.data.no_complete_count
        }
      })
    },
    // 优化简历跳转
    handleDefectResumeJump (item) {
      let routerObj = {
        basic: '/member/personal/resume/basic',
        intention: '/member/personal/resume/intention_edit/0',
        specialty: '/member/personal/resume/specialty',
        education: '/member/personal/resume/education_edit/0',
        work: '/member/personal/resume/work_edit/0',
        training: '/member/personal/resume/train_edit/0',
        project: '/member/personal/resume/project_edit/0',
        certificate: '/member/personal/resume/certificate_edit/0',
        language: '/member/personal/resume/language_edit/0',
        tag: '/member/personal/resume/tag',
        img: '/member/personal/resume'
      }
      this.$router.push(routerObj[item.field])
    }
  }
}
</script>

<style lang="scss" scoped>
  .public_head{
    background: #fff;
  }
  .improve_resume{
    background: #007dff;
    box-sizing: border-box;
    min-height: 100vh;
    .banner{
      height: 150px;
      color: #fff;
      padding: 30px 15px 0;
      background: url('../../assets/images/improve_resume/improve_resume_bg.png') no-repeat right -50px / 115px 160px;
      .t1{
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
      }
      .t2{
        font-size: 13px;
      }
    }
    .resume_list_no_module{
      margin: 0 15px;
      padding: 24px 0 0;
      border-radius: 15px;
      background:#fff url("../../assets/images/improve_resume/improve_line.png") repeat-x;
      .tips{
        width: 306px;
        height: 35px;
        line-height: 35px;
        border-radius: 20px;
        margin: 0 auto 5px;
        color: #ff6e27;
        font-size: 13px;
        padding-left: 41px;
        background: #fff url("../../assets/images/improve_resume/improve_tips.png") no-repeat 12px center / 24px 24px;
      }
      .resume_module{
        padding: 25px 24px;
        position: relative;
        border-bottom: 1px solid #e7ebef;
        &:last-child{
          border-bottom: none;
        }
        .t1{
          color: #161616;
          font-size: 16px;
          font-weight: bold;
          margin-bottom: 18px;
        }
        .t2{
          color: #666666;
          font-size: 12px;
          width: 210px;
          line-height: 24px;
        }
        .btn_box{
          position: absolute;
          right: 24px;
          top: 50%;
          transform: translateY(-50%);
          .btn{
            width:70px ;
            height: 30px;
            color: #ffffff;
            font-size: 12px;
          }
        }
      }
    }
    .footer{
      text-align: center;
      padding: 25px 0;
      color: #89c3ff;
      font-size: 14px;
      vertical-align: middle;
      a{
        color: #89c3ff;
      }
      .arrow_icon{
        vertical-align: middle;
      }
    }
  }
</style>
