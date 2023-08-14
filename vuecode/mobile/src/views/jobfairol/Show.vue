<template>
  <div id="app">
    <Meta v-if="title!==''" pagealias="jobfairolshow" :custom_data="{title:title}" />
    <Head>网络招聘会详情</Head>
    <div class="b1">
      <img src="../../assets/images/jobfairol/2.png" alt="">
      <div class="n_box">
        <div class="n_item"><div class="t1">参会企业</div><div class="t2">{{ companyNum }}</div></div>
        <div class="n_item"><div class="t1">可投职位</div><div class="t2">{{ jobNum }}</div></div>
        <div class="n_item"><div class="t1">浏览次数</div><div class="t2">{{ clickNum }}</div></div>
      </div>
    </div>
    <div class="b2">
      <div class="b_nav">
        <div :class="'n_item '+(tab === 'com' || tab === 'job' ? 'active' : '')" @click="changeTab" data-type="com">企业大厅</div>
        <div :class="'n_item '+(tab === 'res' ? 'active' : '')" @click="changeTab" data-type="res">求职者大厅</div>
        <div :class="'n_item '+(tab === 'show' ? 'active' : '')" @click="changeTab" data-type="show">招聘会介绍</div>
      </div>
      <div class="bn_1">
        <div class="s_group" v-show="tab === 'com' || tab === 'job'">
          <div class="s_type" @click="filterItemShow = true">{{ filterText }}</div>
          <input type="text" class="search_ico" v-model="comJobKey" placeholder="请输入关键字">
          <div class="s_bt" @click="getComJob">搜索</div>
        </div>
        <!--求职者搜索-->
        <div class="s_group r" v-show="tab === 'res'">
          <input type="text" class="search_ico res" v-model="resKey" placeholder="请输入关键字">
          <div class="s_bt" @click="getRes">搜索</div>
        </div>
        <!--企业-->
        <div class="i_group" v-show="tab === 'com'">
          <van-empty
            image="search"
            description="没有找到对应的数据"
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
              <img :src="item.logo_src" alt="" class="c_logo">
              <div class="c_name substring"><a :href="item.company_url">{{ item.companyname }}</a></div>
              <div class="c_wx" @click="funShowQr(item)">微信直面</div>
                <div class="j_name substring" v-for="(item1, index1) in item.joblist" :key="index1">
                  {{ item1.jobname }}<span class="j_salary">{{ item1.wage_text }}</span>
                </div>
              <div class="j_nj" v-if="item.joblist.length <= 0">该企业暂无招聘职位</div>
              <div @click="toCompanyDetail(item.id)" class="j_more" v-if="item.joblist.length > 0">查看该公司全部职位></div>
            </div>
          </van-list>
        </div>
        <!--职位-->
        <div class="i_group" v-show="tab === 'job'">
          <van-empty
            image="search"
            description="没有找到对应的数据"
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
              <img :src="item.company_logo" alt="" class="c_logo">
              <div class="t1 substring"><a :href="item.job_url">{{ item.jobname }}</a><span class="j_salary">{{ item.wage_text }}</span></div>
              <div class="t2 substring">{{ item.education_text }} · {{ item.experience_text }} · {{ item.district_text }}</div>
              <div class="t3">
                <div class="c_name substring">{{ item.companyname }}</div><div class="c_r" v-if="item.company_audit === 1"></div>
                <div class="c_t" v-if="item.setmeal_icon !== ''"><img :src="item.setmeal_icon" alt=""></div>
                <div class="clear"></div>
              </div>
            </div>
          </van-list>
        </div>
        <!--求职者-->
        <div class="i_group" v-show="tab === 'res'">
          <van-empty
            image="search"
            description="没有找到对应的数据"
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
            <div class="r_item" v-for="(item, index) in resumeList" :key="index" @click="toResumeDetail(item)">
              <div class="up">
                <div class="avatar_box">
                  <img :src="item.photo_img_src" alt="" />
                  <div :class="'gender '+(item.sex === 1 ? 'male' : 'female')"></div>
                </div>
                <div class="tx1">
                  <div class="name"><a>{{ item.fullname }}</a></div>
                  <div class="level_ico" v-if="item.high_quality === 1"></div>
                  <div class="clear"></div>
                  <div class="wage">{{ item.refreshtime }}</div>
                </div>
                <div class="tx2">
                  {{ item.age_text }}岁 · {{ item.experience_text }} · {{ item.education_text }}
                </div>
              </div>
              <div class="tx3">
                想找
                <span>{{ item.intention_jobs }}</span>
                工作
              </div>
              <div class="tx3">
                想在
                <span>{{ item.intention_district }}</span>
                工作
              </div>
              <div class="tx4">{{ item.current_text }}</div>
            </div>
          </van-list>
        </div>
        <!--招聘会介绍-->
        <div class="i_group" v-show="tab === 'show'">
          <div class="j_des">
            <div class="des_text" style="white-space: pre-line;" v-html="content"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="join_bar" v-show="tab !== 'im'">
      <div class="b_item"><div class="i_btn l" @click="doApply" data-type="1">企业参会</div></div>
      <div class="b_item"><div class="i_btn r" @click="doApply" data-type="2">个人参会</div></div>
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
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Login from '@/components/Login'
export default {
  name: 'Index',
  components: {
    Login
  },
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{m}月{d}日 {h}:{i}')
    }
  },
  data () {
    return {
      filterItemShow: false,
      itemList: [{ name: '企业', tab: 'com' }, { name: '职位', tab: 'job' }],
      filterText: '企业',
      tab: 'com',
      com_loading: false,
      job_loading: false,
      res_loading: false,
      com_finished: false,
      job_finished: false,
      res_finished: false,
      finished_text: '',
      pageCJ: 1,
      pageJob: 1,
      pageRes: 1,
      pagesize: 15,
      jobfair_id: 0,
      title: '',
      companyNum: 0,
      jobNum: 0,
      clickNum: 0,
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
      thisType: 1
    }
  },
  created () {
    this.jobfair_id = this.$route.params.id
    this.getJobfairShow()
    this.getCompanyList()
    this.getJobList()
    this.getResumeList()
  },
  methods: {
    toResumeDetail (item) {
      this.$router.push('/resume/' + item.id)
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
        t.content = r.data.info.content
        let wechatShareInfo = {
          title: t.title,
          imgUrl: r.data.info.thumb_src
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
    },
    closeLogin () {
      this.showLogin = false
    }
  }
}
</script>

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
  .join_bar {
    .b_item {
      flex: 1;
      .i_btn {
        &.l {
          background-color: #3388ff;
        }
        &.r {
          background-color: #ff7e00;
        }
        display: block;width: 150px;padding: 7px 0;margin: 0 auto;font-size: 14px;color: #fff;text-align: center;
        border-radius: 34px;text-decoration: none;
      }
    }
    position: fixed;left: 0;bottom: 0;right: 0;display: flex; z-index: 3;width: 375px;height: 80px;padding-top: 22px;
    border-top: 1px solid #e2e2e2;background-color: #fff;
  }
  .b2 {
    padding-bottom: 80px;
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
    .bn_1 {
      padding-top: 10px;
      .i_group {
        padding: 0 17px;
        .j_des {
          padding: 10px 0 18px;
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
          .tag {
            padding: 3px 5px 3px 17px;
            border-radius: 3px;
            color: #ffffff;
            font-size: 10px;
            position: absolute;
            right: 17px;
            bottom: 15px;
            background: #ffa57d url("../../assets/images/fab_ico.svg") 5px center
            no-repeat;
            background-size: 10px;
          }
          .top {
            position: absolute;
            right: -25px;
            top: -25px;
            width: 50px;
            height: 50px;
            background-color: #feae41;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            transform: rotateZ(45deg);
            padding-top: 36px;
            font-size: 10px;
          }
          .tx4 {
            font-size: 15px;
            color: #999999;
            padding-right: 90px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            padding-bottom: 20px;
          }
          .tx3 {
            span {
              color: #666666;
            }
            font-size: 15px;
            color: #999999;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            padding-bottom: 9px;
          }
          .up {
            .tx2 {
              .time {
                position: absolute;
                right: 0;
                top: 15px;
                font-size: 13px;
                color: #999999;
              }
              position: relative;
              font-size: 15px;
              color: #666666;
              padding: 3px 0 10px;
            }
            .tx1 {
              .wage {
                position: absolute;
                right: 0;
                top: 25px;
                font-size: 13px;
                color: #999999;
              }
              .level_ico {
                float: left;
                margin-left: 10px;
                width: 36px;
                height: 25px;
                background: url("../../assets/images/resume_list_level_ico.png") 0 center
                no-repeat;
                background-size: 36px 15px;
              }
              .name {
                float: left;
                font-size: 18px;
                font-weight: bold;
                color: #333333;
                a {color: #333;}
              }
              position: relative;
              padding-top: 22.5px;
            }
            .avatar_box {
              .gender {
                &.female {
                  background: #ff8d65 url("../../assets/images/female_ico.svg") center
                  no-repeat;
                  background-size: 9px;
                }
                &.male {
                  background: #4fa5fa url("../../assets/images/male_ico.svg") center
                  no-repeat;
                  background-size: 9px;
                }
                position: absolute;
                right: 0;
                bottom: 2px;
                width: 13px;
                height: 13px;
                border-radius: 100%;
              }
              img {
                width: 49px;
                height: 49px;
                border: 0;
                border-radius: 100%;
              }
              position: absolute;
              left: 0;
              top: 22px;
              width: 49px;
              height: 49px;
            }
            position: relative;
            padding-left: 61px;
          }
          position: relative;
          width: 100%;
          background-color: #ffffff;
          padding: 0 0;
          overflow: hidden;
          border-bottom: 1px solid #f3f3f3;
          &:last-child {
            border-bottom: 0;
          }
        }
        .j_item {
          .t3 {
            .c_t {
              position: relative;float: left;width: 14px;height: 20px;margin-left: 5px;
              img {
                position: absolute;left: 0;top: 50%;transform: translate(0, -50%);width: 14px;height: 12px;
              }
            }
            .c_r {
              float: left;width: 15px;height: 20px;margin-left: 5px;
              background: url("../../assets/images/jobfairol/5.png") 0 center no-repeat;background-size: 15px 11px;
            }
            .c_name {
              float: left;max-width: 225px;height: 20px;line-height: 20px;font-size: 13px;color: #999;
            }
          }
          .t2 {
            margin: 10px 0;font-size: 14px;color: #666;
          }
          .t1 {
            .j_salary {
              position: absolute;right: 0;top: 50%;transform: translate(0, -50%);font-size: 14px;color: #ff5d24;
              font-weight: bold;
            }
            position: relative;padding-right: 100px;font-size: 16px;color: #333;font-weight: bold;
            a {color: #333;}
          }
          .c_logo {
            position: absolute;left: 0;top: 20px;width: 60px;height: 60px;border: 1px solid #e3e3e3;
          }
          position: relative;padding: 17px 0 17px 70px;border-bottom: 1px solid #f3f3f3;
          &:last-child {
            border-bottom: 0;
          }
        }
        .c_item {
          .j_more {
            display: block;padding: 2px 0;text-align: center;font-size: 13px;color: #999;text-decoration: none;
            position: absolute;left: 0;bottom: 10px;width: 100%;
          }
          .j_name {
            .j_salary {
              position: absolute;right: 0;top: 50%;transform: translate(0, -50%);color: #ff5d24;
            }
            position: relative;padding: 2px 65px 2px 0;font-size: 15px;color: #333;margin-bottom: 10px;
          }
          .j_nj {
            font-size: 13px;text-align: center;color: #999;height: 70px;line-height: 70px;
          }
          .c_wx {
            width: fit-content;padding: 3px 5px 3px 23px;margin-left: 48px;margin-bottom: 10px;font-size: 11px;color: #fff;
            background: #00c785 url("../../assets/images/jobfairol/4.png") 5px center no-repeat;
            background-size: 15px 12px;border-radius: 21px;position: absolute;right: 0;top: 15px;
          }
          .c_name {
            padding-left: 38px;font-size: 16px;color: #333;font-weight: bold;margin-bottom: 15px;margin-top: 15px;padding-right: 80px;
            a {color: #333;}
          }
          .c_logo {
            position: absolute;left: 0;top: 15px;width: 30px;height: 30px;border: 0;border-radius: 30px;
          }
          position: relative;padding: 5px 0 15px 0;border-bottom: 1px solid #f3f3f3;min-height: 150px;
          &:last-child {
            border-bottom: 0;
          }
        }
      }
      .s_group {
        &.r {
          &::before, &::after {
            display: none;
          }
        }
        .s_type {
          position: absolute;left: 16px;top: 50%;transform: translate(0, -50%);font-size: 13px;color: #333;
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
          font-size: 12px;
          color: #333;
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
    .b_nav {
      display: flex;width: 375px;border-bottom: 2px solid #f3f3f3;
      .n_item {
        &.active {
          position: relative;color: #333;font-weight: bold;
          &::after {
            content: '';position: absolute;left: 50%;bottom: 1px;transform: translate(-50%, 0);width: 30%;height: 1.5px;
            background-color: #1787fb;
          }
        }
        flex: 1;padding: 10px 0;font-size: 14px;color: #999;text-align: center;
      }
    }
  }
  .b1 {
    position: relative;width: 375px;height: 185px;margin-bottom: 48px;
    .n_box {
      .n_item {
        flex: 1;position: relative;text-align: center;
        &:not(:first-child) {
          &::before {
            content: '';position: absolute;left: 0;top: 50%;transform: translate(0, -50%);height: 17px;
            border-left: 1px solid #ececec;
          }
        }
        .t1 {
          font-size: 12px;color: #333;margin-bottom: 3px;
        }
        .t2 {
          font-size: 18px;color: #333;font-weight: bold;
        }
      }
      display: flex;position: absolute;left: 50%;bottom: -40px;transform: translate(-50%, 0);width: 340px;padding: 13px 0 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, .12), 0 0 6px rgba(0, 0, 0, .04);z-index: 1;border-radius: 4px;background: #fff;
    }
    img {
      width: 375px;height: 185px;border: 0;
    }
  }
</style>
