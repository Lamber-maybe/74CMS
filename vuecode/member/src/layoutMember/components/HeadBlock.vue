<template>
  <div class="header_personal_wrapper">
    <div class="wrapper_content">
      <HeadTop></HeadTop>
      <div class="header_handle">
        <div>
          <div class="site_logo"><a :href="link_url_web.index"><img :src="config.logo"></a></div>
          <a :href="link_url_web.index" class="back_home" v-if="pageType === 1">返回首页</a>
          <a :href="link_url_web.index" class="back_home no_ico" v-if="pageType === 2">返回首页</a>
          <router-link to="/personal/message" class="head_notice" :class="msgUnread===true?'new':''" v-if="pageType === 1">通知消息</router-link>
          <div class="head_search" v-if="pageType === 1">
              <el-dropdown class="drop" @command="handleSearchType">
                <div class="drop_item">{{searchType==1?'职位':'企业'}}信息<i class="el-icon-arrow-down el-icon--right"></i></div>
                <el-dropdown-menu slot="dropdown">
                <el-dropdown-item command="1">职位信息</el-dropdown-item>
                <el-dropdown-item command="2">企业信息</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <label><input v-model="keyword" type="text" placeholder="请输入搜索关键字"></label>
            <div class="btn" @click="handlerSearch"></div>
          </div>
          <div class="add_step_box" v-if="pageType === 3">
            <el-steps :space="230" :active="stepNum" align-center>
              <el-step title="基本信息"></el-step>
              <el-step title="完善简历"></el-step>
              <el-step title="创建完成"></el-step>
            </el-steps>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
  import HeadTop from './HeadTop'
  export default {
    name: 'Head',
    components: {
      HeadTop
    },
    data () {
      return {
        // 1->个人会员中心 2->登录注册通用 3->创建简历
        pageType: 1,
        stepNum: 1,
        searchType:1,
        keyword:'',
        msgUnread:false,
        link_url_web:{}
      }
    },
    computed:{
      config(){
        return this.$store.state.config
      }
    },
    mounted () {
      if (this.$route.path === '/personal/resume/add_resume_step1') {
        this.pageType = 3
        this.stepNum = 1
      }
      if (this.$route.path === '/personal/resume/add_resume_step2') {
        this.pageType = 3
        this.stepNum = 2
      }
      if (this.$route.path === '/personal/resume/add_resume_step3') {
        this.pageType = 3
        this.stepNum = 3
      }
      if (this.$route.name === 'Register' || this.$route.name === 'MemberLogin' || this.$route.name === 'MemberBind'
        || this.$route.name === 'FindPwd' || this.$route.name === 'MemberOauthQq' || this.$route.name ===
        'MemberOauthWeixin'
        || this.$route.name === 'Wxpay' || this.$route.name === 'ResumeSave' || this.$route.name === 'Appeal') {
        this.pageType = 2
      }
      this.link_url_web = this.$store.state.config.link_url_web
        this.fetchMsgUnread()
    },
    methods:{
      fetchMsgUnread(){
        if(this.$store.state.LoginOrNot===false){
          return
        }
        http.get(api.member_msgunread).then(res=>{
          this.msgUnread = res.data==1?true:false
        }).catch(()=>{})
      },
      handleSearchType(type){
        this.searchType = type
      },
      handlerSearch(){
        let url = ''
        if(this.searchType==1){
          url = this.link_url_web.joblist_search_key
        }else{
          url = this.link_url_web.companylist_search_key
        }
        url = url.replace('_key_',this.keyword)
        window.location.href=url
      }
    }
  }
</script>

<style lang="scss" scoped>
  >>>.el-header{
    min-height: 128px !important;
  }
  .header_personal_wrapper {
    position: relative;
    background-color: #fff;

    &::after {
      content: '';
      position: absolute;
      left: 0;
      top: 37px;
      right: 0;
      border-bottom: 1px solid #f7f9fd;
    }

    .wrapper_content {
      width: 1200px;
      margin: 0 auto;

      .header_handle {
        width: 100%;
        height: 90px;
        position: relative;

        .site_logo {
          position: relative;
          float: left;
          width: 170px;
          height: 90px;

          img {
            max-height: 70px;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translate(0, -50%);
            border: 0;
          }
        }

        .head_crumb {
          position: relative;
          float: left;
          font-size: 18px;
          color: #333;
          padding-left: 40px;
          margin-top: 26px;

          &::after {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            width: 1px;
            height: 23px;
            background-color: #eee;
          }
        }

        .back_home {
          float: right;
          font-size: 14px;
          color: #333;
          padding: 2px 0 2px 23px;
          display: block;
          margin-top: 27px;
          background: url("../../assets/images/header/header_back_home_ico.png") 0 0 no-repeat;
          background-size: 20px;

          &.no_ico {
            background: none;
            padding-left: 0;
          }
        }

        .head_notice {
          float: right;
          font-size: 14px;
          color: #333;
          padding: 2px 0 2px 23px;
          display: block;
          margin: 27px 40px 0 0;
          position: relative;
          background: url("../../assets/images/header/header_notice_ico.png") 0 1px no-repeat;
          background-size: 20px;

          &.new::after {
            content: '';
            position: absolute;
            left: 12px;
            top: 1px;
            width: 7px;
            height: 7px;
            background-color: #ff5050;
            border-radius: 100%;
          }
        }

        .head_search {
          float: right;
          margin: 17px 40px 0 0;
          position: relative;
          width: 460px;
          height: 42px;
          border: 1px solid #efefef;
          border-radius: 4px;

          input {
            position: absolute;
            left: 117px;
            top: 50%;
            transform: translate(0, -50%);
            height: 30px;
            line-height: 30px;
            font-size: 14px;
            color: #333;
            width: 290px;
            padding: 0;
            margin: 0;
            border: 0;

            &::placeholder {
              color: #999;
            }
          }

          .btn {
            cursor: pointer;
            position: absolute;
            right: 0;
            top: 0;
            width: 45px;
            height: 40px;
            background: url("../../assets/images/header/header_search_1.png") center center no-repeat;
            background-size: 20px;
            &:hover {
              background: url("../../assets/images/header/header_search_2.png") center center no-repeat;
              background-size: 20px;
            }
          }

          &::after {
            content: '';
            position: absolute;
            left: 101px;
            top: 50%;
            transform: translate(0, -50%);
            width: 1px;
            height: 18px;
            background-color: #ebebeb;
          }

          .drop_item {
            width: 100px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
            font-size: 14px;
          }
        }

        .add_step_box {
          position: absolute;
          right: 0;
          top: 18px;
          width: 550px;
        }
      }
    }
  }
@-webkit-keyframes supervise_animation {
  0%{background-position:0% 0%;}
  50%{background-position:50% 100%;}
  100%{background-position:100% 0%;}
  }
</style>
