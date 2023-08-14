<template>
  <section class="my_footer">
    <div class="link_nav">
      <div class="content">
          <a :href="link_url_web.joblist" target="_blank" class="link_item">找工作</a>
          <a :href="link_url_web.resumelist" target="_blank" class="link_item">找人才</a>
          <a :href="link_url_web.articlelist" target="_blank" class="link_item">职场资讯</a>
          <a :href="link_url_web.helplist" target="_blank" class="link_item">帮助中心</a>
        <div class="clear"></div>
      </div>
    </div>
        <div class="address">联系地址：{{config.contact_address}} <a target="_blank" href="https://beian.miit.gov.cn/">网站备案：{{config.icp}}</a></div>
        <div class="copyright">{{config.bottom_other}}</div>
  </section>
</template>

<script>
  export default {
    name: 'FooterBlock',
    data(){
        return {
            link_url_web:{}
        }
    },
    computed:{
      config(){
        return this.$store.state.config
      }
    },
    mounted(){
        this.link_url_web = this.$store.state.config.link_url_web
    }
  }
</script>

<style lang="scss" scoped>
  .my_footer {
    width: 100%;
    background-color: #fff;
    padding-bottom: 20px;
    margin-top: 15px;

    .link_nav {
      text-align: center;
      font-size: 0;
      margin-bottom: 10px;
      background-color: #1787fb;
      color: #fff;

      .content {
        display: inline-block;
        vertical-align: top;
        font-size: 0;
        padding: 8px 0;

        .link_item {
          float: left;
          display: block;
          font-size: 12px;
          position: relative;
          padding: 2px 0;
          color: #fff;

          &:not(:first-child) {
            margin-left: 26px;

            &::after {
              content: '';
              position: absolute;
              left: -13px;
              top: 3px;
              width: 1px;
              height: 13px;
              background-color: #f7f7f7;
            }
          }
        }
      }
    }

    .address {
      text-align: center;
      font-size: 12px;
      margin-bottom: 10px;
    }

    .copyright {
      text-align: center;
      font-size: 12px;
    }
  }
</style>
