<template>
  <div id="app">
    <Meta v-if="title!==''" pagealias="jobfairolshow" :custom_data="{title:title}" />
    <Head>网络招聘会详情</Head>
    <div class="b1">
      <div class="banner_img">
        <div v-if="status == 0"><img src="../../assets/images/jobfairol/nostart.png" alt="" class="tast"></div>
        <div v-if="status == 1"><img src="../../assets/images/jobfairol/conduct.png" alt="" class="tast"></div>
        <div v-if="status == 2"><img src="../../assets/images/jobfairol/end.png" alt="" class="tast"></div>
        <vue-baberrage
        :isShow="barrageIsShow"
        :barrageList="log"
        :loop="barrageLoop"
        :messageHeight="messageHeight"
        :lanesCount="lanesCount"
        :boxHeight="boxHeight"
        >
        <!-- <template v-slot:default="slotProps">
          <div class="swiper-link">
            <div class="logos">
              <img :src="slotProps.item.avatar" alt="">
            </div>
            <div class="contents substring">
              {{ slotProps.item.msg }}
            </div>
          </div>
          </template> -->
        </vue-baberrage>
        <img class="banner-img" :src="mobile_header_logo" alt="">
      </div>
    </div>
    <div class="n_box">
      <div class="n_item"><div class="t2">{{ companyNum }}</div><div class="t1">参会企业</div></div>
      <div class="fgx">
        <img src="../../assets/images/jobfairol/fgx.png" alt="">
      </div>
      <div class="n_item"><div class="t2">{{ jobNum }}</div><div class="t1">可投职位</div></div>
      <div class="fgx">
        <img src="../../assets/images/jobfairol/fgx.png" alt="">
      </div>
      <div class="n_item"><div class="t2">{{ clickNum }}</div><div class="t1">浏览次数</div></div>
    </div>
    <div class="b2">
      <div class="bn_1">
        <div class="s_group">
          <div class="s_type" @click="filterItemShow = true">{{ filterText }}</div>
          <input type="text" class="search_ico" v-model="comJobKey" placeholder="请输入搜索名称">
          <div class="s_bt" @click="getComJob"></div>
        </div>
      </div>
      <div class="b_nav">
        <div :class="'n_item '+(tab === 'com' ? 'active' : '')" @click="changeTab" data-type="com">企业</div>
        <div :class="'n_item '+(tab === 'job' ? 'active' : '')" @click="changeTab" data-type="job">职位</div>
        <div :class="'n_item '+(tab === 'res' ? 'active' : '')" @click="changeTab" data-type="res">求职者</div>
        <div :class="'n_item '+(tab === 'show' ? 'active' : '')" @click="changeTab" data-type="show">简介</div>
      </div>
      <div class="list">
        <!--企业-->
        <div class="i_group" v-show="tab === 'com'">
          <van-empty
            image="search"
            description="暂无内容"
            style="background-color:#fff"
            v-if="companyList.length === 0 && tab === 'com'"
          />
          <van-list
            v-if="companyList.length > 0"
            v-model="com_loading"
            :finished="com_finished"
            :finished-text="finished_text"
            @load="onLoad"
            :immediate-check="true"
          >
            <div class="c_item" v-for="(item, index) in companyList" :key="index">
              <div class="c_item_inner">
                <img :src="item.logo_src" alt="" class="c_logo">
                <div class="c_name substring" @click.stop="companyUrl(item.id)">
                    <div class="companyname">
                      <div><a class="link">{{ item.companyname }}</a></div>
                      <div class="trade">
                        <span>{{ item.trade_name }}</span>
                        <span>|</span>
                        <span>{{ item.scale_name }}</span>
                      </div>
                    </div>
                    <div class="right_icon">
                      <img src="../../assets/images/jobfairol/fh.png" alt="">
                    </div>
                </div>
              </div>

              <div class="j_name substring" v-for="(item1, index1) in item.joblist" :key="index1" @click.stop="jobdetails(item.id)">
                <div class="name">
                  <span class="name-text substring">{{ item1.jobname }}</span>
                  <span class="j_salary">{{ item1.wage_text }}</span>
                </div>
                <div class="jobs substring">
                  <div class="address jobs_tag">
                    {{ item1.district_name }}
                  </div>
                  <div class="jy jobs_tag">
                    {{ item1.experience }}
                  </div>
                  <div class="xl jobs_tag">
                    {{ item1.education }}
                  </div>
                </div>
              </div>

              <div class="j_nj" v-if="item.joblist.length <= 0">
                  <div class="nojob">
                    <p>
                      暂无发布职位
                    </p>
                  </div>
              </div>
              <div  class="j_more" v-if="item.joblist.length > 0">
                <div class="btn zl" @click="getJob('compan',item.id,item.companyname,item.joblist)" data-type="1">
                  在线职聊
                </div>
                <div class="btn zm" @click="funShowQr(item)">
                  微信直面
                </div>
              </div>
            </div>
          </van-list>
        </div>
        <!--职位-->
        <div class="i_group" v-show="tab === 'job'">
          <van-empty
            image="search"
            description="暂无内容"
            style="background-color:#fff"
            v-if="jobList.length === 0 && tab === 'job'"
          />
          <van-list
            v-if="jobList.length > 0"
            v-model="job_loading"
            :finished="job_finished"
            :finished-text="finished_text"
            @load="onLoadJob"
            :immediate-check="true"
          >
            <div class="j_item" v-for="(item, index) in jobList" :key="index">
              <div class="header">
                <div class="job_name substring" @click.stop="jobdetails(item.id)">
                  {{ item.jobname }}
                </div>
                <div class="wage_text substring">
                  <span>
                    {{ item.district_text }} | {{ item.experience_text }} | {{ item.education_text }}
                  </span>
                </div>
                <div class="salary">
                  <span>{{ item.wage_text }}</span>
                </div>
              </div>

              <div class="compan">
                <img :src="item.company_logo" alt="" class="c_logo">
                <div class="com-info">
                  <div class="compan_name substring">
                    <span>{{ item.companyname }}</span>
                  </div>
                  <div class="company_text substring">
                    <span>{{ item.category }}</span>
                    <span>|</span>
                    <span>{{item.scale}}</span>
                  </div>
                </div>
                <div class="date">
                  <span>{{ item.updatetime }}</span>
                </div>
              </div>

              <div  class="j_more">
                <div class="btn zl" @click="getJob('job',item.id,item.jobname,item.company_id,item.companyname)" data-type="1">
                  在线职聊
                </div>
                <div class="btn zm" @click="funShowQr(item)">
                  微信直面
                </div>
              </div>

            </div>
          </van-list>
        </div>
        <!--求职者-->
        <div class="i_group" v-show="tab === 'res'">
          <van-empty
            image="search"
            description="暂无内容"
            style="background-color:#fff"
            v-if="resumeList.length === 0 && tab === 'res'"
          />
          <van-list
            v-if="resumeList.length > 0"
            v-model="res_loading"
            :finished="res_finished"
            :finished-text="finished_text"
            @load="onLoadRes"
            :immediate-check="true"
          >
            <div class="r_item" v-for="(item, index) in resumeList" :key="index">
              <div class="resume" @click="toResumeDetail(item)">
                <div>
                  <div class="job_name substring">
                    {{ item.intention_jobs }}
                  </div>
                  <div class="experience substring">{{ item.experience_text }} | {{ item.education_text }} | {{ item.age_text }}岁</div>
                </div>
                <div class="span">
                  <!-- <img src="../../assets/images/jobfairol/fh.png" alt=""> -->
                </div>
              </div>

              <div class="member">
                <div class="photo">
                  <img :src="item.photo_img_src" alt="" />
                  <div :class="'gender '+(item.sex === 1 ? 'male' : 'female')"></div>
                </div>
                <div class="members">
                  <div class="member_name substring">
                    {{ item.fullname }}
                  </div>
                  <div class="member_address substring">
                    <span>{{ item.intention_district }} | {{ item.intention_wage }}</span>
                  </div>
                </div>
                <div class="date"><span>{{ item.refreshtime }}</span></div>
              </div>

              <div  class="j_more">
                <div class="btn zl" @click="getJob('resume', item.id,item.fullname)" data-type="2">
                  在线职聊
                </div>
                <div class="btn zm" @click="toResumeDetail(item)">
                  查看简历
                </div>
              </div>
            </div>
          </van-list>
        </div>
        <!--招聘会介绍-->
        <div class="i_group" v-show="tab === 'show'">
          <div class="j_des editor-content-view">
            <div class="des_text" style="white-space: pre-line;" v-html="content"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="join_bar" v-show="tab !== 'im'">
      <div class="b_item l" @click="doApply" data-type="1">
        企业参会
      </div>
      <div class="b_item r" @click="doApply" data-type="2">
        个人参会
      </div>
    </div>
    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <Login
        :utype="thisType"
        :single_login="true"
        @afterLogin="afterLogin"
        :goback_custom="true"
        @gobackCustomMethod="closeLogin"
      ></Login>
    </van-popup>
    <van-action-sheet
      v-model="filterItemShow"
      :actions="itemList"
      cancel-text="取消"
      close-on-click-action
      @select="onItemSelected"
    />
    <van-dialog v-model="showQrcode" :title="diaQrTitle" confirm-button-text="知道了">
      <div class="dialog_wx_qr">
        <div class="dqr_img"><img :src="diaQrUrl"/></div>
        <div class="b_qr_txt">微信内长按二维码<br>远程面试，快速入职</div>
      </div>
    </van-dialog>
    <van-overlay :show="selectJobShow" z-index="3" :lock-scroll="false">
      <SelectJob @handleCommunicate="handleCommunicate" @handleCloseSelectJob="handleCloseSelectJob" :chatid="imChatid" :companyId="companyId" :isSelectJob="false"></SelectJob>
    </van-overlay>
  </div>
</template>

<script type="text/javascript">
import wxshare from '@/assets/js/share.js'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Login from '@/components/Login'
import SelectJob from '@/views/im/components/SelectJob.vue'
import {mapMutations} from 'vuex'
// import Vue from 'vue'
import { vueBaberrage } from 'vue-baberrage'
// Vue.use(vueBaberrage)
export default {
  name: 'Index',
  components: {
    Login,
    SelectJob,
    vueBaberrage
  },
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{m}月{d}日 {h}:{i}')
    }
  },
  data () {
    return {
      barrageIsShow: true,
      barrageLoop: true,
      messageHeight: 50,
      boxHeight: 50,
      lanesCount: 1,
      filterItemShow: false,
      itemList: [{ name: '职位', tab: 'job' } , { name: '企业', tab: 'com' }, { name: '求职者', tab: 'res' }],
      filterText: '职位',
      tab: 'com',
      com_loading: false,
      job_loading: false,
      res_loading: false,
      com_finished: false,
      job_finished: false,
      res_finished: false,
      finished_text: '',
      status: 0,
      pageCJ: 1,
      pageJob: 1,
      pageRes: 1,
      pagesize: 15,
      jobfair_id: 0,
      title: '',
      companyNum: 0,
      jobNum: 0,
      clickNum: 0,
      mobile_header_logo: '',
      content: '',
      jobList: [],
      companyList: [],
      resumeList: [],
      showLogin: !1,
      comJobKey: '',
      resKey: '',
      showQrcode: false,
      diaQrUrl: '',
      diaQrTitle: '',
      thisType: 1,
      is_company_login: false,
      log: [],
      jobid: 0,
      selectJobShow: false,
      companyId: '',
      imChatid: '',
      jobname: '',
      fullname: ''
    }
  },
  created () {
    this.jobfair_id = this.$route.params.id
    this.getJobfairShow()
    this.getCompanyList()
    this.getJobList()
    this.getResumeList()
    this.is_company_login =
      !!(this.$store.state.LoginOrNot === true && this.$store.state.LoginType == 1)
  },
  methods: {
    ...mapMutations(['setImShowParams', 'setimChatid']),
    toResumeDetail (item) {
      http.get(api.jobfairol_addlog, {content_id: item.id, type: 3}).then(r => {
        this.$router.push('/resume/' + item.id)
      }).catch(() => {})
    },
    jobdetails (id) {
      http.get(api.jobfairol_addlog, {content_id: id, type: 2}).then(r => {
        this.$router.push('/job/' + id)
      }).catch(() => {})
    },

    companyUrl (id) {
      http.get(api.jobfairol_addlog, {content_id: id, type: 1}).then(r => {
        this.$router.push('/company/' + id)
      }).catch(() => {})
    },
    /**
     * 选择沟通职位
     * @jobItem 当前沟通职位信息
     */
    handleCommunicate (jobItem) {
      this.selectJobShow = false
      this.jobid = jobItem.id
      this.jobname = jobItem.jobname
      this.selectJobObj = jobItem
      this.doMsg()
    },
    /**
     * 选择职位弹窗关闭
     */
    handleCloseSelectJob () {
      this.selectJobShow = false
    },
    getJob (istype, id, name, company_id = 0, companyname = '') {
      let loginType = this.$store.state.LoginType
      let confirmText = ''
      if (this.$store.state.LoginType === false) {
        if (istype === 'compan') {
          this.thisType = 2
          confirmText = '当前操作需要登录个人账号'
        } else if (istype === 'job') {
          this.thisType = 2
          confirmText = '当前操作需要登录个人账号'
        } else {
          confirmText = '当前操作需要登录企业账号'
        }
      } else {
        if (istype === 'compan' && loginType === 1) {
          this.thisType = 2
          confirmText = '当前操作需要登录个人账号'
        } else if (istype === 'job' && loginType === 1) {
          this.thisType = 2
          confirmText = '当前操作需要登录个人账号'
        } else if (istype === 'resume' && loginType === 2) {
          confirmText = '当前操作需要登录企业账号'
        } else {
          confirmText = ''
        }
      }

      if (confirmText != '') {
        // 未登录
        this.$dialog
          .confirm({
            title: '提示',
            message: confirmText,
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
          })
          .catch(() => {})
      } else {
        if (istype === 'compan') { // 企业id、企业名称
          this.companyId = id
          this.fullname = name
          if (company_id.length == 1) {
            this.jobid = company_id[0].id
          }
          this.doMsg()
        } else if (istype === 'job') { // 职位id、z职位名称、企业id、企业名称
          this.jobid = id
          this.jobname = name
          this.companyId = company_id
          this.fullname = companyname
          this.doMsg()
        } else { // 简历id 简历名称
          this.getcompanyinfo()
          this.jobid = 0
          this.resume_id = id
          this.fullname = name
          this.doMsg()
        }
      }
    },
    getcompanyinfo () {
      http
        .post(api.company_index, {}).then(res => {
          var {companyinfo} = res.data
          this.companyId = companyinfo.id
        })
    },
    // 在线职聊
    doMsg () {
      http.post(api.imStart, {token: this.$store.state.imToken, resumeid: this.resume_id, jobid: this.jobid, companyid: this.companyId}).then(res => {
        // disabled 不能使用功能
        // bind_weixin绑定微信SelectJob
        // complete_resume完善简历
        // 空字符串 正常使用
        // choose_job 选择职位
        // pay 需要购买增值服务，触屏是快捷支付
        if (parseInt(res.code) == 200) {
          if (res.data.next == '') {
            this.setImShowParams({
              jobname: this.jobname,
              name: this.fullname,
              resumeid: this.resume_id,
              jobid: this.jobid,
              companyId: this.companyId
            })
            this.setimChatid(res.data.chatid)
            this.$router.push({path: '/im/' + res.data.chatid})
            return false
          }
          if (res.data.next == 'disabled') {
            // this.$notify({ type: 'danger', message: res.message })
            this.$dialog({
              title: '系统提示',
              message: res.message,
              showConfirmButton: true
            }).then(() => {
            })
            return false
          }
          if (res.data.next == 'complete_resume') {
            this.$dialog.confirm({
              title: '系统提示',
              message: res.message,
              confirmButtonText: '去完善简历',
              showCancelButton: true
            }).then(() => {
              this.$router.push({path: '/member/personal/resume'})
            }).catch(() => {
            })
            return false
          }
          if (res.data.next == 'bind_weixin') {
            this.bindWeixinShow = true
          }
          if (res.data.next == 'pay') {
            // 快捷支付
            this.$dialog.confirm({
              title: '系统提示',
              message: res.message,
              confirmButtonText: '去支付'
            })
              .then(() => {
                this.$router.push({path: '/member/order/add/common?type=service&service_type=im'})
              })
              .catch(() => {})
          }
          if (res.data.next == 'choose_job') {
            this.selectJobShow = true
          }
        }
      })
    },
    // 微信直面
    funShowQr (item) {
      this.showQrcode = true
      this.diaQrUrl = item.qrcode_src
      this.diaQrTitle = item.companyname
    },
    // 企业、职位搜索
    getComJob () {
      if (this.tab === 'com') {
        this.getCompanyList(true)
      } else if (this.tab === 'job') {
        this.getJobList(true)
      }
    },
    // 求职者搜索
    getRes () {
      this.getResumeList(true)
    },
    // 筛选选择
    onItemSelected (item) {
      this.filterText = item.name
      this.tab = item.tab
    },
    // 详情
    getJobfairShow: function () {
      var t = this
      http.get(api.jobfairol_show, {id: t.jobfair_id}).then(r => {
        t.title = r.data.info.title
        t.companyNum = r.data.info.total_company
        t.jobNum = r.data.info.total_job
        t.clickNum = r.data.info.click
        t.content = r.data.info.content.replace(/<p([\s\w"=\/\.:;]+)((?:(style="[^"]+")))/ig, '<p')
          .replace(/<p>/ig, '<p style="font-size: 15px; line-height: 25px;">')
          .replace(/<img([\s\w"-=\/\.:;]+)((?:(height="[^"]+")))/ig, '<img$1')
          .replace(/<img([\s\w"-=\/\.:;]+)((?:(width="[^"]+")))/ig, '<img$1')
          .replace(/<img([\s\w"-=\/\.:;]+)((?:(style="[^"]+")))/ig, '<img$1')
          .replace(/<img([\s\w"-=\/\.:;]+)((?:(alt="[^"]+")))/ig, '<img$1')
          .replace(/<img([\s\w"-=\/\.:;]+)/ig, '<img style="width: 100%;" $1')
        t.mobile_header_logo = r.data.info.mobile_header_logo
        t.status = r.data.info.status

        for (let i = 0; i < r.data.log.length; i++) {
          this.log.push({
            avatar: r.data.log[i].logo,
            msg: r.data.log[i].content,
            time: 5,
            extraWidth: 150
          })
        }

        let wechatShareInfo = {
          title: t.title
          // imgUrl: r.data.info.thumb_src
        }
        wxshare(wechatShareInfo, 'online_jobfairshow', location.href)
      }).catch(() => {})
    },
    // 企业列表
    getCompanyList: function (init) {
      var t = this
      http.get(api.jobfairol_comlist, {jobfair_id: t.jobfair_id, keyword: t.comJobKey, page: t.pageCJ, pagesize: t.pagesize}).then(r => {
        if (init === true) {
          this.companyList = [...r.data.items]
        } else {
          this.companyList = this.companyList.concat(r.data.items)
        }
        // 加载状态结束
        this.com_loading = false

        // 数据全部加载完成
        if (r.data.items.length < this.pagesize) {
          this.com_finished = true
          if (init === false) {
            this.finished_text = '没有更多了'
          }
        }
      }).catch(() => {})
    },
    // 职位列表
    getJobList: function (init) {
      var t = this
      http.get(api.jobfairol_joblist, {jobfair_id: t.jobfair_id, keyword: t.comJobKey, page: t.pageJob, pagesize: t.pagesize}).then(r => {
        if (init === true) {
          this.jobList = [...r.data.items]
        } else {
          this.jobList = this.jobList.concat(r.data.items)
        }
        // 加载状态结束
        this.job_loading = false

        // 数据全部加载完成
        if (r.data.items.length < this.pagesize) {
          this.job_finished = true
          if (init === false) {
            this.finished_text = '没有更多了'
          }
        }
      }).catch(() => {})
    },
    // 简历列表
    getResumeList: function (init) {
      var t = this
      http.get(api.jobfairol_resumelist, {jobfair_id: t.jobfair_id, keyword: t.resKey, page: t.pageRes, pagesize: t.pagesize}).then(r => {
        if (init === true) {
          this.resumeList = [...r.data.items]
        } else {
          this.resumeList = this.resumeList.concat(r.data.items)
        }
        // 加载状态结束
        this.res_loading = false

        // 数据全部加载完成
        if (r.data.items.length < this.pagesize) {
          this.res_finished = true
          if (init === false) {
            this.finished_text = '没有更多了'
          }
        }
      }).catch(() => {})
    },
    onLoad: function () {
      this.pageCJ++
      this.getCompanyList(!1)
    },
    onLoadJob: function () {
      this.pageJob++
      this.getJobList(!1)
    },
    onLoadRes: function () {
      this.pageRes++
      this.getResumeList(!1)
    },
    toCompanyDetail: function (id) {
      this.$router.push('/company/' + id)
    },
    changeTab: function (e) {
      this.tab = e.target.dataset.type
    },
    // 参会
    doApply (e) {
      let t = this
      let loginType = this.$store.state.LoginType

      t.thisType = parseInt(e.target.dataset.type)
      let confirmText = t.thisType === 1 ? '当前操作需要登录企业账号' : '当前操作需要登录个人账号'
      if (this.$store.state.LoginOrNot === false) {
        // 未登录
        this.$dialog
          .confirm({
            title: '提示',
            message: confirmText,
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
          })
          .catch(() => {})
      } else {
        if (loginType !== t.thisType) {
          this.$dialog
            .confirm({
              title: '提示',
              message: confirmText
            })
            .then(() => {})
            .catch(() => {})
        } else {
          http.post(api.jobfairol_apply, {jobfair_id: t.jobfair_id}).then(r => {
            this.$notify({ type: 'success', message: r.message })
          }).catch(() => {})
        }
      }
    },
    afterLogin (data) {
      this.showLogin = false
      this.is_company_login = true
    },
    closeLogin () {
      this.showLogin = false
    }
  }
}

</script>
<style lang="scss">
.banner_img{
  .baberrage-stage{
    height: 30px;
    bottom: 30px;
  }
  .baberrage-item{
      background-color: rgba(0,0,0,.4);
      color: #fff;
      border-radius: 15px;
      font-size: 10px;
      padding: 0;
      .normal{
        width: auto;
        background: transparent;
        padding: 0;
        .baberrage-avatar{
          width: 30px;
          height: 30px;
          border-radius: 50%;
          margin-right: 10px;
        }
      }
      .baberrage-msg{
        font-size: 14px;
        padding-left: 0;
        padding-right: 15px;
      }
  }
}

</style>
<style lang="scss" scoped>
  .dqr_img {
    width: 200px;height: 200px;margin: 0 auto;
    img {
      width: 200px;height: 200px;
    }
  }
  .b_qr_txt {
    font-size: 12px;color: #999;line-height: 1.8;text-align: center;
  }
  .hr img{
    position: absolute;
  }
  .join_bar {
      position: fixed;
      left: 0;
      bottom: 0;
      right: 0;
      display: flex;
      z-index: 3;
      width: 375px;
      border-top: 1px solid #e2e2e2;
      background-color: #fff;
      .b_item {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        &.l {
          background-color: #3388ff;
          font-size: 16px;
        }
        &.r {
          background-color: #ff7e00;
          font-size: 16px;
        }
        display: block;
        width: 150px;
        padding: 18px 0;
        margin: 0 auto;
        font-size: 14px;
        color: #fff;
        text-align: center;
        //border-radius: 34px;text-decoration: none;
      }
  }
  .b2 {
    padding-bottom: 65px;
    .bn_2 {
      .h_bar {
        .ava {
          position: absolute;right: 15px;top: 50%;transform: translate(0, -50%);width: 60px;height: 26px;
          background: url("../../assets/images/jobfairol/7.png") 0 0 no-repeat;
          background-size: 60px 26px;
        }
        &::before {
          content: '';position: absolute;left: 18px;top: 50%;transform: translate(0, -50%);width: 7px;height: 7px;
          background-color: #1787fb;border-radius: 7px;
        }
        position: relative;padding: 16px 0 16px 29px;font-size: 14px;color: #333;background-color: #fff;
      }
    }
    .bn_1{
      padding-top: 10px;
      .s_group {
        &.r {
          &::before, &::after {
            display: none;
          }
        }
        .s_type {
          position: absolute;left: 16px;top: 50%;transform: translate(0, -50%);font-size: 14px;color: #2C2C2C;
          font-weight: 500;
        }
        &::before {
          content: '';position: absolute;left: 50px;top: 50%;transform: translate(0, -50%);width: 8px;height: 5px;
          background: url("../../assets/images/jobfairol/3.png") 0 0 no-repeat;background-size: 8px 5px;
        }
        &::after {
          content: '';position: absolute;left: 68px;top: 50%;transform: translate(0, -50%);height: 20px;
          border-left: 1px solid #bbbbbb;
        }
        input[type=search] {
          -webkit-appearance: textfield;
          -webkit-box-sizing: content-box;
        }
        input::-webkit-search-decoration,
        input::-webkit-search-cancel-button {
          display: none;
        }
        .s_bt {
          position: absolute;right: 0;top: 0;height: 100%;
          background: url("../../assets/images/search_ico_gray.svg") 0 center no-repeat;
          background-size: 13px;
          font-size: 12px;padding: 10px 10px 10px 20px;
        }
        .search_ico {
          font-size: 14px;
          color: #BCBCBC;
          font-weight: 500;
          padding: 10px 0 10px 0;
          position: absolute;
          background: transparent;
          top: 0;
          left: 78px;
          line-height: normal;
          border: 0;
          width: 180px;
          &::placeholder {
            color: #c9c9c9;
          }
          &.res {
            left: 15px;
          }
        }
        position: relative;
        width: 340px;
        height: 37px;
        margin: 0 auto 5px;
        background-color: #f8f8f8;
        text-align: center;
        border-radius: 36px;
      }
    }
    .list {
      background: #F5F3F3;
      .i_group {
        .j_des {
          padding: 10px 20px 18px;
          background: #fff;
          .qr_txt {
            font-size: 12px;color: #999;margin-bottom: 2px;text-align: center;
          }
          .qr_box {
            width: 120px;height: 120px;margin: 0 auto;padding: 5px;
            img {
              width: 100px;height: 100px;border: 0;
            }
          }
          .des_text {
            line-height: 1.8;font-size: 13px;color: #666;margin-bottom: 20px;
          }
        }
        .r_item {
          padding: 17px ;
          background: #fff;
          width: 100%;
          border-bottom: 1px solid #f3f3f3;
          .resume{
            display: flex;
            margin-bottom: 12px;
            .job_name{
              width: 300px;
              flex-shrink:0 ;
              font-size: 17px;
              font-weight: bold;
              color: #343434;
            }
            .experience{
              font-size: 13px;
              color: #525252;
              padding: 4px;
            }
            .span{
              width: 12.5px;
              margin-left: auto;
              background: url('../../assets/images/jobfairol/fh.png') no-repeat center 7px;
              background-size:12.5px 12.5px ;
              // img{
              //   height: 12.5px;
              // }
            }
          }
          .member{
            display: flex;
            .photo{
              flex-shrink: 0;
              width: 32px;
              height: 32px;
              border-radius: 50%;
              position: relative;
              img{
                border-radius: 50%;
                width: 100%;
                height: 100%;
                display: block;
              }
              .gender{
                position: absolute;
                right: -2px;
                top: -2px;
                width: 11px;
                height: 11px;
                &.male{
                  background: url('../../assets/images/jobfairol/nan.png') no-repeat center center;
                  background-size: 11px 11px;
                }
                &.female{
                  background: url('../../assets/images/jobfairol/nv.png') no-repeat center center;
                  background-size: 11px 11px;
                }
              }
            }
            .members{
              width: 220px;
              flex-shrink: 0;
              margin-left: 10px;
            }
            .member_name{
              font-size: 14px;
              color: #403F3F;
            }
            .member_address{
              font-size: 12px;
              color: #999999;
            }
            .date{
              font-size: 11px;
              color: #C3C3C3;
              margin-left: auto;
            }
          }

          .j_more {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 17px;
            .btn{
              flex-shrink:0 ;
              font-size: 14px;
              padding: 6px 45px;
              border-radius: 5px;
              display: flex;
              align-items: center;
              justify-content: center;
              &.zl{
                background-color: white;
                border: solid 1px #FF7E00;
                color: #FF9332;
              }
              &.zm{
                background-color: white;
                border: solid 1px #1787FB;
                color: #1787FB;
                margin-left: 19px;
              }
            }
          }
        }
        .j_item {
          background: #fff;
          border-bottom: 1px solid #f3f3f3;
          padding: 17px 17px;
          margin-bottom: 4px;
          .header{
            padding: 0 0px 12px;
            position: relative;
            .job_name{
              width: 270px;
              flex-shrink:0 ;
              font-size: 17px;
              font-weight: bold;
              color: #343434;
            }
            .wage_text{
              font-size: 13px;
              color: #525252;
              padding: 4px 0;
            }
            .salary{
              position: absolute;
              right: 0;
              top: 0;
              font-size: 16px;
              color: #FC6502;
              font-weight: bold;
              margin-left: auto;
            }
          }
          .compan{
            display: flex;
            .com-info{
              flex-shrink: 0;
              width: 220px;
            }
            .compan_name{
              flex-shrink: 0;
              font-size: 14px;
              color: #403F3F;
              padding: 0 9px;
              font-weight: 500;
            }
            .company_text{
              flex-shrink: 0;
              font-size: 12px;
              color: #999999;
              padding: 3px 9px;
            }
            .date{
              font-size: 11px;
              color: #C3C3C3;
              margin-left: auto;
            }
            .c_logo{
              flex-shrink: 0;
              width: 32px;
              height: 32px;
              border-radius: 4px;
            }
          }
          .j_more {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 17px;
            .btn{
              flex-shrink:0 ;
              font-size: 14px;
              padding: 6px 45px;
              border-radius: 5px;
              display: flex;
              align-items: center;
              justify-content: center;
              &.zl{
                background-color: white;
                border: solid 1px #FF7E00;
                color: #FF9332;
              }
              &.zm{
                background-color: white;
                border: solid 1px #1787FB;
                color: #1787FB;
                margin-left: 19px;
              }
            }
          }
        }
        .c_item {
          margin-bottom: 4px;
          background: #fff;
          padding: 16px 17px;
          position: relative;
          border-bottom: 1px solid #f3f3f3;
          .c_item_inner{
            display: flex;
            .c_logo {
              width: 40px;
              height: 40px;
              border: 0;
              border-radius: 4px;
              flex-shrink:0 ;
              display: block;
              margin-right: 15px;
            }
            .c_name {
              width: 300px;
              font-size: 16px;
              color: #333;
              font-weight: bold;
              flex-shrink: 0;
              display: flex;
              .companyname{
                flex-shrink: 0;
                width: 275px;
                .link {
                  color: #343434;
                  font-size: 17px;
                  font-weight: bold;
                  overflow: hidden;
                  text-overflow: ellipsis;
                  white-space: nowrap;
                }
                .trade{
                  font-size: 13px;
                  color: #525252;
                  margin-top: 4px;
                  font-weight: normal;
                  span{
                    padding: 0px 5px 0px 0px;
                  }
                }
              }
              .right_icon{
                img{
                  width: 12.5px;
                  height: 12.5px;
                }
              }
            }
          }

          .j_name {
            position: relative;
            font-size: 15px;
            color: #333;
            // margin-bottom: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #F3F3F3;
            .name{
              width: 100%;
              .name-text{
                width: 250px;
                display: block;
              }
              .j_salary {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translate(0, -50%);
                color: #FC6502;
                font-weight: bold;
              }
            }

            .jobs{
              margin-top: 8px;
              display: flex;
              .jobs_tag{
                flex-shrink: 0;
                background: #F5F3F3;
                padding: 2px 10px;
                margin-right: 6px;
                color: #999999;
                font-size: 12px;
                border-radius: 2px;
              }
            }
          }
          .j_nj {
            font-size: 13px;
            text-align: center;
            color: #999;
            .nojob{
              padding-top: 180px;
              background: url("../../assets/images/jobfairol/no_data.png") no-repeat center 10px;
            }
          }
          .j_more {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 17px;
            .btn{
              flex-shrink:0 ;
              font-size: 14px;
              padding: 6px 45px;
              border-radius: 5px;
              display: flex;
              align-items: center;
              justify-content: center;
              &.zl{
                background-color: white;
                border: solid 1px #FF7E00;
                color: #FF9332;
              }
              &.zm{
                background-color: white;
                border: solid 1px #1787FB;
                color: #1787FB;
                margin-left: 19px;
              }
            }
          }
          &:last-child {
            border-bottom: 0;
          }
        }
      }
    }
    .b_nav {
      display: flex;
      align-items: center;
      width: 375px;
      border-bottom: 2px solid #f3f3f3;
      // height: 40px;
      .n_item {
        &.active {
          position: relative;
          font-size: 20px;
          font-weight: bold;
          color: #1787FB;
          &::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -2px;
            transform: translate(-50%, 0);
            width: 20px;
            height: 2px;
            background-color: #1787FB;
          }
        }
        flex: 1;
        padding: 10px 0;
        font-size: 17px;
        color: #666262;
        text-align: center;
        font-weight: bold;
      }
    }
  }
  .b1 {
    position: relative;
    width: 375px;
    .tast{
      width: 62px;
      height: 22px;
      position: absolute;
    }
  }
  .n_box {
    .n_item {
      font-size: 17px;
      font-family: PingFang SC;
      font-weight: bold;
      color: #242424;

      flex: 1;
      text-align: center;
      &:not(:first-child) {
        &::before {
          content: '';position: absolute;left: 0;top: 50%;transform: translate(0, -50%);height: 17px;
          border-left: 1px solid #ececec;
        }
      }
      .t1 {
        font-size: 13px;color: #313131;margin-bottom: 3px;
        font-weight: 500;
      }
      .t2 {
        font-size: 24px;color: #3388FF;font-weight: bold;
      }
    }
    display: flex;
    padding: 13px 0 20px 0;
    border-radius:20px 20px 0 0;
    background: #fff;
    margin-top: -20px;
    position: relative;
    margin-bottom:10px ;
    background: #fff url("../../assets/images/jobfairol/hr.png")no-repeat bottom center;
  }
  .fgx img{
    width: 7px;
    height: 47px;
  }

  .banner_img {
    background:#f8f8f8 ;
    position: relative;
  }
  .banner_img_inner{
    width: 1200px;
    margin: 0 auto  ;
    height: 363px;
    padding-top: 40px;
    padding-bottom: 20px;
    position: relative;
  }

  .banner_img .banner-img {
    width: 100%;
    height: 140px;
    display: block;
  }

  .banner_img .notice_list {
    position: absolute;
    left: 0;
    top: 335px;
    white-space: nowrap;
  }

  .banner_img .notice_list div {
    display: inline-block;
    padding: 8px 17px 8px 25px;
    background: rgba(0, 0, 0, 0.3);
    color: #ffffff;
    font-size: 14px;
    border-radius: 20px;
    position: relative;
  }

  .banner_img .notice_list div a {
    color: #ffffff;
    font-size: 14px;
    cursor: pointer;
  }

  .banner_img .notice_list div::before {
    content: "";
    position: absolute;
    left: 15px;
    top: 15px;
    width: 4px;
    height: 4px;
    background: #fff;
  }

  .banner_img .notice_list .list1 {
    margin-left: 90px;
    margin-right: 190px;
  }

  .banner_img .notice_list .list2 {
    margin-right: 140px;
  }

  .banner_img .notice_list .list3,
  .banner_img .notice_list .list4 {
    margin-right: 180px;
  }
</style>
