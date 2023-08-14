<template>
  <div class="header_personal_wrapper">
    <div class="wrapper_content">
      <HeadTop></HeadTop>
      <div class="header_handle">
        <div class="site_logo"><a :href="link_url_web.index"><img :src="config.logo"></a></div>
        <a :href="link_url_web.index" class="back_home">返回首页</a>
        <router-link to="/personal/message" class="head_notice" :class="msgUnread===true?'new':''">通知消息</router-link>
        <div class="head_search">
            <el-dropdown class="drop" @command="handleSearchType">
              <div class="drop_item">{{searchType==1?'职位':'企业'}}信息<i class="el-icon-arrow-down el-icon--right"></i></div>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item command="1">职位信息</el-dropdown-item>
                <el-dropdown-item command="2">企业信息</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
            <label><input v-model="keyword" type="text" @keyup.enter="handlerSearch" placeholder="请输入搜索关键字"></label>
          <div class="btn" @click="handlerSearch"></div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
  import HeadTop from '../../layoutMember/components/HeadTop'
  export default {
    name: 'Head',
    components: {
      HeadTop
    },
    data(){
      return {
        searchType:1,
        keyword:'',
        link_url_web:{},
        msgUnread:false,
      }
    },
    computed:{
      config(){
        return this.$store.state.config
      }
    },
    mounted(){
        this.link_url_web = this.$store.state.config.link_url_web
        this.fetchMsgUnread()
    },
    methods:{
      fetchMsgUnread(){
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

      .header {
        width: 100%;
        padding: 11px 0;

        .header_left {
          float: left;
        }

        .header_right {
          float: right;
        }

        .not_login {
          font-size: 12px;
        }

        .item {
          display: block;
          float: left;
          font-size: 12px;
          position: relative;

          &:not(:first-child) {
            margin-left: 20px;

            &::before {
              content: '';
              position: absolute;
              left: -10px;
              top: 1px;
              width: 1px;
              height: 13px;
              background-color: #dddddd;
            }
          }

          &.home {
            background: url("../../assets/images/header/header_home_ico.png") 0 0 no-repeat;
            background-size: 13px;
            padding-left: 18px;
          }

          &.phone {
            background: url("../../assets/images/header/header_phone_ico.png") 0 1px no-repeat;
            background-size: 13px;
            padding-left: 18px;
          }
        }

        .co9 {
          color: #999;
        }

        .blue {
          color: #409eff;
        }

        .drop {
          float: left;
          margin-left: 20px;

          &::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 1px;
            width: 1px;
            height: 13px;
            background-color: #dddddd;
          }
        }
      }

      .header_handle {
        width: 100%;
        height: 76px;

        .site_logo {
          overflow: hidden;
          position: relative;
          float: left;
          width: 170px;
          height: 76px;

          img {
            max-width:170px;
            max-height: 70px;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translate(0, -50%);
            border: 0;
          }
          &::before{
            content: "";
            position: absolute;
            left: -10px;
            top: -460px;
            width: 200px;
            height: 10px;
            background-color: rgba(255, 255, 255, 0.5);
            transform: rotate(-45deg);
            animation: searchLights 1.5s ease-in 1s infinite;
            z-index: 333;
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
          transition: all .3s;
          -webkit-transition: all .3s;

          &:hover {
            border-color: #C0C4CC;
          }

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
            background-size: 18px;
            transition: all .3s;
            -webkit-transition: all .3s;
            &:hover {
              background: url("../../assets/images/header/header_search_2.png") center center no-repeat;
              background-size: 18px;
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
      }
    }
  }
@-webkit-keyframes searchLights {
  0% {
    left: -80px;
    top: 0;
  }
  to {
    left: 130px;
    top: 0px;
  }
}
@-o-keyframes searchLights {
  0% {
    left: -80px;
    top: 0;
  }
  to {
    left: 130px;
    top: 0px;
  }
}
@-moz-keyframes searchLights {
  0% {
    left: -80px;
    top: 0;
  }
  to {
    left: 130px;
    top: 0px;
  }
}
@keyframes searchLights {
  0% {
    left: -80px;
    top: 0;
  }
  to {
    left: 130px;
    top: 0px;
  }
}
</style>
