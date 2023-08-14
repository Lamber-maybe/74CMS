<template>
  <div id="app">
    <Head>选择分站</Head>
    <div class="d1">当前选择地区 <span class="s_name">{{config.subsite_info.district_text}}</span></div>
    <div class="d2 clearfix">
      <div class="d2_cell substring" :class="config.subsite_info.id==0?'checked':''" @click="choose_subsite(0)">总站</div>
      <div class="d2_cell substring" v-for="(item,index) in config.subsite_list" :key="index" :class="item.id==config.subsite_info.id?'checked':''" @click="choose_subsite(item.id)">{{item.district_text}}</div>
    </div>
  </div>
</template>

<script>
// import VueCookies from 'vue-cookies'
export default {
  name: 'SubsiteChoice',
  computed: {
    config () {
      return this.$store.state.config
    }
  },
  methods: {
    choose_subsite (subsiteid) {
      localStorage.setItem("qscms_subsiteid",subsiteid)
      // VueCookies.set('qscms_subsiteid', subsiteid)
      window.location.href = this.$store.state.config.mobile_domain
    }
  }
}
</script>

<style lang="scss" scoped>
.d1 {
  padding: 15px 15px;font-size: 15px;border-top: 1px solid #dcdfe6;
  .s_name {
    padding-left: 18px;color: #1989fa;margin-left: 5px;
    background: url("../assets/images/sub_current_ico.png") 0 center no-repeat;
    background-size: 13px;
  }
}
.d2 {
  padding: 0 15px;
  .d2_cell {
    width: 108px;float: left;margin-right: 10px;text-align: center;padding: 6px 10px;
    border: 1px solid #dcdfe6;border-radius: 4px;font-size: 15px;margin-bottom: 10px;
    &.checked {
      background: #1989fa;border-color: #1989fa;color: #ffffff;
    }
    &:nth-of-type(3n) {
      margin-right: 0;
    }
  }
}
</style>
