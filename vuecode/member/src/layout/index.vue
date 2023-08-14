<template>
  <el-container class="my_container">
    <el-header height="114px">
      <head-block />
    </el-header>
    <el-container>
      <el-aside width="240px">
        <side-nav />
      </el-aside>
      <el-main>
        <app-main />
        <footer-block />
      </el-main>
    </el-container>
  </el-container>
</template>

<script>
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
      FooterBlock
    }
  }
</script>

<style lang="scss" scoped></style>