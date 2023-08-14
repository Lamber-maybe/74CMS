<template>
  <div class="header_wrapper">
    <HeadTop></HeadTop>
    <div class="header_handle">
      <div class="site_logo"><a :href="link_url_web.index"><img :src="config.logo"></a></div>
      <div class="head_crumb">会员管理中心</div>
      <a :href="link_url_web.index" class="back_home">返回首页</a>
      <router-link to="/company/message" class="head_notice" :class="msgUnread===true?'new':''">通知消息</router-link>
      <div class="head_search">
        <label><input v-model="keyword" type="text" @keyup.enter="searchResume" placeholder="使用关键词搜索简历"></label>
        <div class="btn" @click="searchResume"></div>
      </div>
      <div class="clear"></div>
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
      HeadTop,
    },
    computed:{
      config(){
        return this.$store.state.config
      }
    },
    data(){
      return {
        keyword:'',
        link_url_web:{},
        msgUnread:false,
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
      searchResume(){
        let url = this.link_url_web.resumelist_search_key
        url = url.replace('_key_',this.keyword)
        location.href=url
      }
    }
  }
</script>

<style lang="scss" scoped>
  .header_wrapper {
    padding: 0 40px;
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

    .header_handle {
      padding: 0 10px;
      width: 100%;
      height: 70px;

      .site_logo {
        overflow: hidden;
        position: relative;
        float: left;
        width: 170px;
        height: 70px;

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
        margin: 19px 40px 0 0;
        position: relative;
        width: 280px;
        height: 38px;
        border: 1px solid #efefef;
        border-radius: 38px;

        &:hover {
          border-color: #C0C4CC;
          .btn {
            background: url("../../assets/images/header/header_search_2.png") center center no-repeat;
            background-size: 16px;
          }
        }

        input {
          position: absolute;
          left: 15px;
          top: 50%;
          transform: translate(0, -50%);
          height: 30px;
          line-height: 30px;
          font-size: 12px;
          color: #333;
          width: 210px;
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
          height: 36px;
          background: url("../../assets/images/header/header_search_1.png") center center no-repeat;
          background-size: 16px;
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
