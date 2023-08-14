<template>
  <el-container class="my_container">
    <el-header height="114px">
      <head-block />
    </el-header>
    <div class="personal_top_box" v-if="$route.path=='/personal/index'"></div>
    <el-container class="my_personal">
      <el-aside width="224px">
        <side-nav />
      </el-aside>
      <el-main class="my_main">
        <app-main />
      </el-main>
    </el-container>
    <footer-block />
  </el-container>
</template>

<script>
  import AppMain from './components/AppMain'
  import SideNav from './components/SideNav'
  import HeadBlock from './components/HeadBlock'
  import FooterBlock from './components/FooterBlock'
  export default {
    name: 'LayoutPersonal',
    components: {
      AppMain,
      SideNav,
      HeadBlock,
      FooterBlock
    }
  }
</script>

<style lang="scss" scoped>
  .my_container { background-color: #f3f3f3; }
  .my_personal {
    width: 1200px; margin: 10px auto 0;
    .my_main { background-color: #f3f3f3; padding: 0 0 0 20px; }
    .el-aside { background-color: #f3f3f3; }
  }
  .personal_bread {
    width: 1200px; height: 42px; margin: 0 auto;
    .el-breadcrumb { padding: 16px 0;}
  }
  .personal_top_box {
    display: inline-table;width: 100%; margin-bottom: 170px;
  }
</style>
