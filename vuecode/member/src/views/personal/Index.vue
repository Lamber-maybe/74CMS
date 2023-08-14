<template>
    <div>
      <div class="personal_top">
        <div class="top_content">
          <div class="avatar_box"><img :src="basic.photo_img_src"></div>
          <div class="info">
            <div class="name">{{basic.fullname}}</div>
            <div class="some">
              <div class="some_item">{{basic.sex_text}}</div>
              <div class="some_item">{{basic.age}}岁</div>
              <div class="some_item">{{basic.education_text}}</div>
              <div class="some_item">{{basic.experience_text}}</div>
              <div class="clear"></div>
            </div>
            <div class="contact">
              <div class="contact_item phone" v-if="contact.mobile !== ''">{{contact.mobile}}</div>
              <div class="contact_item wx" v-if="contact.weixin !== ''">{{contact.weixin}}</div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="top_block" @click="$router.push('/personal/job/jobapply')">
            <div class="tx1">{{resumeTotal.job_apply.number}}</div><div class="tx2">我的投递</div>
          </div>
          <div class="top_block" @click="$router.push('/personal/job/interview')">
            <div class="tx1">{{resumeTotal.interview.number}}</div><div class="tx2">面试邀请</div>
          </div>
          <div class="top_block" @click="$router.push('/personal/job/attention_me')">
            <div class="tx1">{{resumeTotal.attention_me.number}}</div><div class="tx2">对我感兴趣</div>
          </div>
          <div class="top_block" @click="$router.push('/personal/job/viewjob')">
            <div class="tx1">{{resumeTotal.view_job.number}}</div><div class="tx2">我的足迹</div>
          </div>
          <div class="clear"></div>
        </div>
      </div>
        <div class="integrity_tip" v-if="basic.complete_percent < $store.state.config.apply_job_min_percent">您的简历完整度较低，严重影响求职质量，建议立即完善简历信息
            <el-button class="r_btn" type="warning" size="small" @click="$router.push('/personal/resume')">去完善</el-button>
        </div>
        <div class="box_1">
            <div class="b_left">
                <div class="tx1">我的简历</div>
                <el-switch
                        class="tx_switch"
                        v-model="resumePublic"
                        active-color="#409eff"
                        inactive-color="#bec3d1"
                        inactive-text="公开状态"
                        @change="handleStatus">
                </el-switch>
                <div class="examine wait" v-if="basic.audit==0"></div>
                <div class="examine pass" v-if="basic.audit==1"></div>
                <div class="examine fail" v-if="basic.audit==2"></div>
                <div class="speed">
                    <el-progress type="circle" :show-text="false" :width="112" :percentage="basic.complete_percent"></el-progress>
                    <div class="p_txt">{{basic.complete_percent}}<span>%</span></div>
                    <div class="txt">简历完整度</div>
                </div>
                <div class="tx2" v-if="basic.complete_percent < $store.state.config.apply_job_min_percent">完整度太低严重影响求职质量，<router-link to="/persona/resume">去完善！</router-link></div>
                <div class="tx2" v-else>使用简历置顶提高简历曝光率，<a href="javascript:;" @click="$router.push('/personal/service/stick')">去置顶！</a></div>
            </div>
            <div class="b_right">
                <el-dropdown class="intention-top" @command="changeIntention">
                    <div class="drop_bar substring">{{currentIntention.category_text}}</div>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(item,index) in intentionList" :key="index" :command="item">{{item.category_text}}</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
                <div class="tx1 substring">工作地区:&nbsp;&nbsp;{{currentIntention.district_text}}</div>
                <div class="tx1 substring">期望薪资:&nbsp;&nbsp;{{currentIntention.wage_text}}/月</div>
                <div class="tx1 substring">期望行业:&nbsp;&nbsp;{{currentIntention.trade_text}}</div>
                <div class="tx1 substring">求职状态:&nbsp;&nbsp;{{basic.current_text}}</div>
            </div>
            <div class="b_btn">
                <div class="tx1">最近更新：{{basic.refreshtime|timeFilter}}</div>
                <div class="b_group">
                    <el-button type="primary" @click="$router.push('/personal/resume')">修改简历</el-button>
                    <el-button type="primary" plain @click="handlerPreview">预览简历</el-button>
                    <el-button type="primary" @click="refreshResume">刷新简历</el-button>
                    <el-button type="primary" plain @click="handlerJumpService('/personal/service/stick','stick')">置顶简历</el-button>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="box_2" v-if="ad_dataset_banner.items.length > 0">
            <el-carousel :height="70 + 'px'">
              <el-carousel-item v-for="(item,index) in ad_dataset_banner.items" :key="index">
                <img :src="item.image_src" class="img" @click="handleClickBanner(item)">
              </el-carousel-item>
            </el-carousel>
        </div>
        <div class="box_3">
            <div class="b_title">优选服务</div>
            <div class="b_group">
                <a href="javascript:;" class="b_item" @click="handlerJumpService('/personal/service/stick','stick')">
                    <p class="b_ico c1"></p><p class="tx1">简历置顶</p><p class="tx2">曝光量提升18倍，增大求职概率</p>
                </a>
                <a href="javascript:;" class="b_item" @click="handlerJumpService('/personal/service/tag','tag')">
                    <p class="b_ico c2"></p><p class="tx1">醒目标签</p><p class="tx2">众人中间脱颖而出，是金子就要发光</p>
                </a>
                <a href="javascript:;" class="b_item" @click="$router.push('/personal/fav/subscribe')">
                    <p class="b_ico c3"></p><p class="tx1">职位订阅</p><p class="tx2">好职位不能错过，精准智能匹配</p>
                </a>
                <a href="javascript:;" class="b_item" @click="handlerEntrust">
                    <p class="b_ico c4"></p><p class="tx1">委托投递</p><p class="tx2">无需登录，系统精准投递</p>
                </a>
                <a href="javascript:;" class="b_item" @click="$router.push('/personal/privacy/setPrivacy')">
                    <p class="b_ico c5"></p><p class="tx1">隐私设置</p><p class="tx2">贴心隐私保护，拒绝被打扰</p>
                </a>
                <a href="javascript:;" class="b_item" @click="$router.push('/personal/points')">
                    <p class="b_ico c6"></p><p class="tx1">我的{{$store.state.config.points_byname}}</p><p class="tx2">做任务赢{{$store.state.config.points_byname}}，每天进步一点点</p>
                </a>
                <div class="clear"></div>
            </div>
        </div>
        <div class="box_4">
            <div class="b_title">职位推荐</div>
            <div class="b_sw">
                <div class="sw_item" :class="item.id==recommendIntentionId?'active':''" v-for="(item,index) in intentionList" :key="index" @click="changeRecommendIntention(item)">{{item.category_text}}</div>
                <div class="clear"></div>
            </div>
            <div class="b_group" v-if="recommendList.length>0">
                <div class="b_item" v-for="(item,index) in recommendList" :key="index" @click="handlerRecommendLink(item)">
                    <div class="b_name substring">{{item.jobname}}</div>
                    <div class="b_city substring">{{item.district_text}}</div>
                    <div class="b_wage substring">{{item.wage_text}}/月</div>
                </div>
                <div class="clear"></div>
                <div class="b_more">想看更多推荐职位？<a target="_blank" :href="joblistUrl">去查看>></a></div>
            </div>
            <div class="b_empty" v-if="recommendList.length==0 && showRecommendEmpty===true">没有数据了</div>
        </div>
        <el-dialog title="委托投递" :visible.sync="showEntrust" width="400px" :close-on-press-escape="false" :close-on-click-modal="false" @opened="handlerEntrustInit">
        <Entrust ref="entrust" @closeEntrust="showEntrust=false"></Entrust>
        </el-dialog>
    </div>
</template>

<script>
import Entrust from '@/components/personal/Entrust'
import {dateCompare,formatTime,adLink} from '@/utils/index'
import {handlerHttpError} from '@/utils/error'
import http from '@/utils/http'
import api from '@/api'
  export default {
    name: 'Index',
    components:{
        Entrust
    },
    filters: {
        timeFilter(timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
        },
    },
    data () {
      return {
          resumeTotal:{
            job_apply:0,
            interview:0,
            attention_me:0,
            view_job:0
          },
          showEntrust:false,
          showRecommendEmpty:false,
        showLowPop: false, // 是否显示简历完整度过低提示
        showRefreshPop: false, // 是否显示刷新简历弹窗
        resumePublic: true,
        currentIntention:{},
        recommendIntentionId:0,
        recommendList:[],
        previewUrl:'',
        joblistUrl:'',
        ad_dataset_banner: { alias: 'QS_member_personal_banner', items: [] },
        contact:{},
        intentionList:[]
      }
    },
    computed: {
        basic () {
            return this.$store.state.resume.basic
        }
        // contact () {
        //     return this.$store.state.resume.contact
        // },
        // intentionList () {
        //     return this.$store.state.resume.intention_list
        // }
    },
    watch: {
        basic: {
            deep: true,
            handler: function () {
                let basic = this.$store.state.resume.basic

                let dateInstance = new Date()
                let current_date = dateInstance.getFullYear() + '-' + (dateInstance.getMonth() + 1) + '-' + dateInstance.getDate()

                let resume_complete_notice_date = localStorage.getItem('web_resume_complete_notice_date')
                if (basic.complete_percent < this.$store.state.config.apply_job_min_percent && dateCompare(resume_complete_notice_date, current_date) != 'eq') {
                    this.showLowPop = true
                    localStorage.setItem('web_resume_complete_notice_date', current_date)
                }
                if (this.showLowPop === false) {
                    let resume_refresh_notice_date = localStorage.getItem('web_resume_refresh_notice_date')
                    if (dateCompare(resume_refresh_notice_date, current_date) != 'eq') {
                        this.showRefreshPop = true
                        localStorage.setItem('web_resume_refresh_notice_date', current_date)
                    }
                }
            }
        }
    },
    created () {
        this.initInfo()
    },
    methods:{
        handleClickBanner(item){
            if(item.link_url !== ''){
              window.open(item.link_url)
            }
        },
        handlerJumpService (path, type) {
            if (type == 'stick' && this.basic.stick === 1) {
            this.$message.error('该简历已经在推广状态，不能重复推广')
            return false
            }
            if (type == 'tag' && this.basic.service_tag !== '') {
            this.$message.error('该简历已经在推广状态，不能重复推广')
            return false
            }
            this.$router.push(path)
        },
        fetchAd () {
            http
                .post(api.ad_list, {alias: ['QS_member_personal_banner@web']})
                .then(res => {
                    this.ad_dataset_banner.items =res.data.items['QS_member_personal_banner@web']
                })
                .catch(() => {})
        },
        // 简历公开状态
        handleStatus (value) {
            if (!value) {
              this.$confirm('简历关闭后企业将无法搜索到您的简历，是否设为关闭?', '提示', {
                  confirmButtonText: '确定',
                  cancelButtonText: '取消',
                  type: 'warning',
              }).then(() => {
                  this.handleResumeStatus(value)
              }).catch(() => {
                  this.resumePublic = !value
              })
            } else {
              this.handleResumeStatus(value)
            }
        },
        // 简历状态开启关闭
        handleResumeStatus (value) {
          http.post(api.resume_privates_set_display, {
            display: value ? 1 : 0,
          }).then(res => {
            if (parseInt(res.code) === 200) {
                this.$message({ message: res.message, type: 'success' })
            } else {
                this.$message.error(res.message)
                this.resumePublic = !value
            }
          }).catch(err => {
            this.$message.error(err)
            this.resumePublic = !value
          })
        },
        // 刷新简历
        refreshResume () {
            this.showRefreshPop = false
            http
                .post(api.resume_refresh)
                .then(res => {
                if (parseInt(res.code) === 200) {
                    this.basic.refreshtime = res.data
                    this.$message({ type: 'success', message: res.message })
                } else {
                    this.$message.error(res.message)
                }
                })
                .catch(err => {
                console.log(err)
                })
        },
        // 初始化数据
        initInfo () {
            http
                .get(api.editResume)
                .then(res => {
                    if (res.data === null) {
                        handlerHttpError({code:50007,message:'请先添加一份简历'})
                        return false
                    } else if(res.data.intention_list.length==0){
                        handlerHttpError({code:50005,message:'请先完善简历求职意向'})
                        return false
                    }else{
                        this.fetchAd()
                        // 更新基本资料
                        this.contact = res.data.contact
                        this.intentionList = res.data.intention_list
                        this.$store.dispatch('setBasicInfo', res.data.basic)
                        this.$store.dispatch('setResumeContact', res.data.contact)
                        this.$store.dispatch('setIntentionList', res.data.intention_list)
                        this.resumePublic = res.data.basic.is_display==1?true:false
                        this.currentIntention = res.data.intention_list[0]
                        this.recommendIntentionId = res.data.intention_list[0].id
                        this.previewUrl = res.data.preview_url
                        this.changeRecommendIntention(this.currentIntention)
                        this.fetchResumeTotal()
                    }
                })
                .catch(err => {
                    console.log(err)
                })
        },
        fetchResumeTotal(){
            http.post(api.personal_index).then(res=>{
                this.resumeTotal = {
                    job_apply:res.data.manage.job_apply,
                    interview:res.data.manage.interview,
                    attention_me:res.data.manage.attention_me,
                    view_job:res.data.manage.view
                }
            })
        },
        changeIntention(item){
            this.currentIntention = item
        },
        handlerPreview(){
            window.open(this.previewUrl)
        },
        changeRecommendIntention(item){
            this.recommendIntentionId = item.id
            this.showRecommendEmpty = false
            this.recommendList = []
            http
            .get(api.recommend_joblist, {id:item.id,page:1,pagesize:16})
            .then(res => {
                this.recommendList = [...res.data.items]
                this.joblistUrl = res.data.joblist_link_url_web
                if(this.recommendList.length==0){
                    this.showRecommendEmpty = true
                }
            })
            .catch(() => {})
        },
        handlerRecommendLink(item){
            window.open(item.job_link_url_web)
        },
        handlerAdClick(item){
            adLink(item)
        },
        handlerEntrust(){
            this.showEntrust = true
        },
        handlerEntrustInit(){
            this.$refs.entrust.initCB()
        }
    }
  }
</script>

<style lang="scss" scoped>
  .personal_top {
    width: 100%; height: 153px; position: absolute; left: 0; top: 114px;z-index: 1;
    background: url("../../assets/images/personal/personal_top_bg.jpg") center 0 no-repeat;
    .top_content {
      width: 1200px; height: 100%; margin: 0 auto; padding: 0 10px 0 160px; position: relative;
      .avatar_box {
        position: absolute; left: 0; top: 30px; z-index: 1; width: 136px; height: 136px; padding: 3px; border-radius: 136px;
        background-color: #fff;box-shadow: 0 2px 4px rgba(0, 0, 0, .12), 0 0 6px rgba(0, 0, 0, .04);
        img { width: 130px; height: 130px; border-radius: 130px; }
      }
      .info {
        float: left; padding-top: 36px; color: #fff;
        .name { font-size: 24px; margin-bottom: 9px; }
        .some {
          margin-bottom: 12px;
          .some_item {
            float: left; font-size: 16px; position: relative;
            &:not(:first-child) {
              margin-left: 20px;
              &::after {
                content: ''; position: absolute; left: -10px; top: 50%;transform: translate(0,-50%);width: 1px;
                height: 11px; background-color: #fff;
              }
            }
          }
        }
        .contact {
          .contact_item {
            float: left; padding-left: 25px; font-size: 16px; margin-right: 17px; position: relative;
            &.phone {
              &::after {
                content: ''; position: absolute; left: 0; top: 0; width: 19px; height: 19px; border-radius: 100%;
                background: #fff url("../../assets/images/personal/ico_1.png") 4px center no-repeat; background-size: 11px;
              }
            }
            &.wx {
              &::after {
                content: ''; position: absolute; left: 0; top: 0; width: 19px; height: 19px; border-radius: 100%;
                background: #fff url("../../assets/images/personal/ico_2.png") 3px center no-repeat; background-size: 14px;
              }
            }
          }
        }
      }
      .top_block {
          cursor:pointer;
        float: right; width: 121px; height: 96px; text-align: center; color: #fff; background: rgba(255,255,255,0.2);
        border-radius: 4px; font-size: 16px; margin: 28px 0 0 11px;
        .tx1 { height: 56px; line-height: 56px; font-weight: 700; }
        .tx2 {}
      }
    }
  }
    .integrity_tip {
        width: 956px; background: #fffaf2; position: relative; border: 1px solid #ff6600; font-size: 16px; color: #ff6600;
        padding: 16px 0 16px 42px; margin-bottom: 19px;
        &::before {
            content: ''; position: absolute; left: 13px; top: 16px; width: 21px; height: 21px;
            background: url("../../assets/images/personal/ico_3.png") 0 0 no-repeat; background-size: 21px;
        }
        .r_btn {
            position: absolute; right: 13px; top: 10px;
        }
    }
    .box_1 {
        position: relative; width: 956px; height: 287px; margin-bottom: 15px;
        background: #fff url("../../assets/images/personal/bg_1.jpg") right bottom no-repeat; background-size: 455px 115px;
        .b_left {
            position: relative; float: left; width: 285px;
            .tx1 { font-size: 22px; color: #404c5b; padding: 26px 0 0 35px; margin-bottom: 40px; }
            .tx_switch {
                position: absolute; left: 150px; top: 32px; color: #bec3d1;
                .el-switch__label { color: #bec3d1; }
            }
            &::before {
                content: ''; position: absolute; right: 0; top: 24px; width: 1px; height: 240px; background-color: #f3f3f3;
            }
            .examine {
                position: absolute; right: -26px; top: 105px; width: 70px; height: 73px;
                &.wait { background: #fff url("../../assets/images/personal/ico_4.png") 0 10px no-repeat; }
                &.pass { background: #fff url("../../assets/images/personal/ico_7.png") 0 10px no-repeat; }
                &.fail { background: #fff url("../../assets/images/personal/ico_8.png") 0 10px no-repeat; }
            }
            .speed {
                width: 112px; height: 112px; margin: 0 auto 25px; position: relative;
                .el-progress--circle .el-progress__text, .el-progress--dashboard .el-progress__text {
                    top: 35px; transform: translate(0,0); color: #000000; font-weight: 700;
                }
                .p_txt {
                  position: absolute;left: 50%;top: 33px;transform: translate(-50%, 0);font-size: 28px;
                  width: fit-content;
                  span {
                    font-size: 13px;margin-left: 3px;
                  }
                }
                .txt { width: 112px; position: absolute; left: 0; bottom: 26px; font-size: 13px; color: #777777; text-align: center; }
            }
            .tx2 {
                font-size: 13px; color: #6b798b; text-align: center;
                a { color: #1688fb; }
            }
        }
        .b_right {
            float: left; width: 410px; padding: 43px 0 0 43px;
            .intention-top{
                margin-bottom:28px;
            }
            .drop_bar {
                position: relative; color: #96a6b9; width: 184px; padding: 8px 36px 8px 32px; border-radius: 34px;
                // margin-bottom: 28px;
                background: #f7faf8 url("../../assets/images/personal/ico_5.png") 153px center no-repeat; cursor: pointer;
                &::before {
                    content: ''; position: absolute; left: 15px; top: 13px; width: 8px; height: 8px; border-radius: 8px;
                    background-color: #11c971;
                }
            }
            .tx1 {
                font-size: 16px; color: #7f8b9b; margin-bottom: 16px; width: 360px;
            }
        }
        .b_btn {
            float: left; width: 260px; background: url("../../assets/images/personal/ico_6.png") 38px 0 no-repeat;
            .tx1 { color: #ababab; padding: 36px 36px 0 0; text-align: right; margin-bottom: 60px; }
            .b_group {
                .el-button {
                    margin: 0 17px 17px 0;
                }
            }
        }
    }
    .box_2 {
        margin-bottom: 15px;
        .img { width: 956px; height: 70px; border: 0; }
    }
    .box_3 {
        width: 956px; height: 535px; background-color: #fff; margin-bottom: 15px;
        .b_title { font-size: 22px; color: #404c5b; padding: 27px 0 0 35px; margin-bottom: 40px; }
        .b_group {
            padding: 0 45px;
            .b_item {
                display: block; float: left; width: 272px; height: 180px; cursor: pointer; margin: 0 25px 25px 0;
                border: 1px solid #f1f1f1; text-align: center;
                &:nth-of-type(3n) { margin-right: 0; }
                &:hover { box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1); text-decoration: none; }
                .b_ico {
                    width: 100px; height: 100px; margin: 0 auto 4px;
                    &.c1 { background: url("../../assets/images/personal/ico_9.png") center center no-repeat; }
                    &.c2 { background: url("../../assets/images/personal/ico_10.png") center center no-repeat; }
                    &.c3 { background: url("../../assets/images/personal/ico_11.png") center center no-repeat; }
                    &.c4 { background: url("../../assets/images/personal/ico_12.png") center center no-repeat; }
                    &.c5 { background: url("../../assets/images/personal/ico_13.png") center center no-repeat; }
                    &.c6 { background: url("../../assets/images/personal/ico_14.png") center center no-repeat; }
                }
                .tx1 { font-size: 18px; color: #617083; margin-bottom: 13px; }
                .tx2 { color: #617083; }
            }
        }
    }
    .box_4 {
        width: 956px; background-color: #fff; position: relative;
        min-height:250px;
        .b_title { font-size: 22px; color: #404c5b; padding: 27px 0 22px 35px; }
        .b_sw {
            position: absolute; right: 35px; top: 33px;
            .sw_item {
                float: left; color: #617083; position: relative; padding-left: 15px; cursor: pointer;
                &:not(:first-child) { margin-left: 18px; }
                &::before {
                    content: ''; position: absolute; left: 0; top: 5px; height: 8px; width: 8px; border-radius: 8px;
                    background-color: #bec3d1;
                }
                &.active {
                    &::before { background-color: #52d6ae; }
                }
            }
        }
        .b_group {
            padding: 0 46px;
            .b_item {
                display: block; float: left; background-color: #fff; width: 216px; height: 114px; padding: 0 23px; cursor: pointer;
                .b_name { font-size: 16px; color: #6b798b; margin-bottom: 10px; padding-top: 17px; }
                .b_city { color: #999; margin-bottom: 12px; }
                .b_wage { color: #ff5a00; }
                &:hover {
                    background-color: #fbfbfb; text-decoration: none;
                    .b_name { color: #1688fb; }
                }
            }
            .b_more {
                color: #6b798b; padding: 20px 0; text-align: center;
                a { color: #1688fb; }
            }
        }
        .b_empty {
            height: 238px; background: #fff url("../../assets/images/personal/ico_15.png") center 58px no-repeat;
            text-align: center; padding-top: 198px;
        }
    }
</style>
