<template>
  <el-container :class="[isAuto ? 'my_container auto' : 'my_container']">
    <el-header height="128px">
      <head-block />
    </el-header>
    <el-container class="my_member">
      <el-main class="my_main">
        <app-main />
      </el-main>
    </el-container>
    <footer-block />
  </el-container>
</template>

<script>
  import AppMain from './components/AppMain'
  import HeadBlock from './components/HeadBlock'
  import FooterBlock from './components/FooterBlock'
  export default {
    name: 'LayoutMember',
    components: {
      AppMain,
      HeadBlock,
      FooterBlock
    },
    data () {
      return {
        show: false,
        isAuto: false
      }
    },
    watch: {
      $route (to,from) {
        if (from.path == '/wxpay') {
            this.isAuto = true
        }
      }
    },
    mounted () {
    }
  }
</script>

<style lang="scss" scoped>
  .my_container { 
    background-color: #f3f3f3; 
  }
  .my_member {
    width: 1200px; margin: 0 auto; 
    .my_main { background-color: #f3f3f3; padding: 0; overflow: auto;}
    .el-aside { background-color: #f3f3f3; }
  }
  .my_member2{
    width: 1200px; 
    float: left;
  }
  .my_main2 {background-color: transparent; padding: 0; overflow: visible;}
</style>
