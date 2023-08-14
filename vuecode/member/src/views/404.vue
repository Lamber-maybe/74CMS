<template>
  <div class="con">
    <div class="b1"></div>
    <a :href="$store.state.config.link_url_web.index" class="back_home">返回首页</a>
  </div>
</template>

<script>
export default {
  name: 'Page404',
  data(){
    return {
    }
  },
  created(){
  },
  mounted() {
  },
  methods:{

  }
}
</script>

<style lang="scss" scoped>
  .con {
    padding-top: 245px;text-align: center;
    .b1 {
      width: 300px;height: 160px;margin: 0 auto 36px;
      background: url("../assets/images/404.png") 0 0 no-repeat;
    }
    .back_home {
      display: block;
      width: 180px;
      color: #FFF;
      background-color: #409EFF;
      border-color: #409EFF;
      margin: 0 auto;
      padding: 11px 0;
      border-radius: 20px;
      &:hover {
        text-decoration: none;
        background: #66b1ff;
        border-color: #66b1ff;
        color: #FFF;
      }
    }
  }
</style>
