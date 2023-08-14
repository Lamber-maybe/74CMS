<template>
  <el-card>
    <personal-title>收藏关注</personal-title>
    <menu-nav :active-tab="$route.path" :list="navList"></menu-nav>
    <router-view></router-view>
  </el-card>
</template>

<script>
  export default {
    data() {
      return {
        navList: [
          { label: '收藏的职位', href: '/personal/fav/favjob', name: '/personal/fav/favjob', active: true },
          { label: '关注的企业', href: '/personal/fav/attention_company', name: '/personal/fav/attention_company', active: false },
          { label: '职位订阅', href: '/personal/fav/subscribe', name: '/personal/fav/subscribe', active: false }
        ]
      }
    }
  }
</script>

<style>

</style>
