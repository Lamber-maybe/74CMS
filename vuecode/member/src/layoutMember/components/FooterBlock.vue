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
    <div class="address">
      联系地址：{{ config.contact_address }}
    </div>
    <div class="qualification">
      <!-- ICP -->
      <span class="qualification_span1" v-if="config.icp">
        网站备案：
        <a target="_blank" href="https://beian.miit.gov.cn/">
          {{ config.icp }}
        </a>
      </span>
      <!-- 公网安备 -->
      <span class="qualification_span1" v-if="config.network_security">
        公网安备：
        <a target="_blank"
           :href="`http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=`+findNumByStr(config.network_security)">
          {{ config.network_security }}
        </a>
      </span>
      <!-- 人力服务许可证 -->
      <span class="qualification_span1"
            v-if="config.qualification_publicity.service_license || config.qualification_publicity.service_license_url">
        人力服务许可证：
        <span v-if="config.qualification_publicity.service_license_url">
          <a target="_blank" :href="config.qualification_publicity.service_license_url">
            {{ config.qualification_publicity.service_license ? config.qualification_publicity.service_license : '查看' }}
          </a>
        </span>
        <span v-else>
          {{ config.qualification_publicity.service_license ? config.qualification_publicity.service_license : '查看' }}
        </span>
      </span>
      <!-- 营业执照 -->
      <span class="qualification_span1"
            v-if="config.qualification_publicity.business_license || config.qualification_publicity.business_license_url">
        营业执照：
        <span v-if="config.qualification_publicity.business_license_url">
          <a target="_blank" :href="config.qualification_publicity.business_license_url">
            {{ config.qualification_publicity.business_license ? config.qualification_publicity.business_license : '查看' }}
          </a>
        </span>
        <span v-else>
          {{ config.qualification_publicity.business_license ? config.qualification_publicity.business_license : '查看' }}
        </span>
      </span>
      <!-- ICP许可证 -->
      <span class="qualification_span1"
            v-if="config.qualification_publicity.business_licenses || config.qualification_publicity.business_licenses_url">
        ICP许可证：
        <span v-if="config.qualification_publicity.business_licenses_url">
          <a target="_blank" :href="config.qualification_publicity.business_licenses_url">
            {{ config.qualification_publicity.business_licenses ? config.qualification_publicity.business_licenses : '查看' }}
          </a>
        </span>
        <span v-else>
          {{ config.qualification_publicity.business_licenses ? config.qualification_publicity.business_licenses : '查看' }}
        </span>
      </span>
    </div>
    <div class="copyright">
      {{ config.bottom_other }}
    </div>
  </section>
</template>

<script>
export default {
  name: 'FooterBlock',
  data() {
    return {
      link_url_web: {},
      routePath: '',
      config: {}
    }
  },
  created() {
    this.routePath = this.$route.path
    this.config = this.$store.state.config
  },
  watch: {
    $route(to) {
      this.routePath = to.path
    }
  },
  mounted() {
    this.link_url_web = this.$store.state.config.link_url_web
  },
  methods: {
    // 提取字符串中的数字
    findNumByStr(str) {
      return str ? str.match(/\d+/g)[0] : ''
    }
  }
}
</script>

<style lang="scss" scoped>
.my_footer_mgt{
  margin-top: 15px;
}
  .my_footer {
    width: 100%;
    background-color: #fff;
    padding-bottom: 20px;
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

    .qualification {
      text-align: center;
      font-size: 12px;
      margin-bottom: 10px;

      .qualification_span1 {
        margin-right: 15px;
      }
    }

    .copyright {
      text-align: center;
      font-size: 12px;
    }
  }
</style>
