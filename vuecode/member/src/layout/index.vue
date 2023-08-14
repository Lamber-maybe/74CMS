<template>
  <el-container class="my_container">
    <el-header height="114px">
      <head-block />
    </el-header>
    <el-container style="height: calc(100vh - 114px)">
      <el-aside width="240px">
        <side-nav />
      </el-aside>
      <el-main>
        <app-main />
        <footer-block />
      </el-main>
    </el-container>
    <FloatIm v-if="$route.path!='/company/im'"></FloatIm>
  </el-container>
</template>

<script>
import FloatIm from "@/components/FloatIm";
  import AppMain from './components/AppMain'
  import SideNav from './components/SideNav'
  import HeadBlock from './components/HeadBlock'
  import FooterBlock from './components/FooterBlock'
  export default {
    name: 'Layout',
    components: {
      AppMain,
      SideNav,
      HeadBlock,
      FooterBlock,
      FloatIm
    }
  }
</script>

<style lang="scss" scoped></style>
