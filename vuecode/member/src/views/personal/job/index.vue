<template>

  <el-card>
    <personal-title>求职管理</personal-title>
     <warning :title="title"></warning>
    <menu-nav :active-tab="$route.path" :list="navList"></menu-nav>
    <router-view></router-view>
  </el-card>

</template>

<script>
  import warning from '@/components/Warning'
  export default {
    components:{
      warning
    },
    data() {
      return {
        navList: [
          { label: '我的投递', href: '/personal/job/jobapply', name: '/personal/job/jobapply', active: false },
          { label: '面试邀请', href: '/personal/job/interview', name: '/personal/job/interview', active: true },
          { label: '视频面试', href: '/personal/job/interview_video', name: '/personal/job/interview_video', active: false },
          { label: '对我感兴趣', href: '/personal/job/attention_me', name: '/personal/job/attention_me', active: false },
          { label: '我的足迹', href: '/personal/job/viewjob', name: '/personal/job/viewjob', active: false },
        ],
        title:'安全提醒：招聘企业无权收取任何费用，求职中请加强自我保护，避免上当受骗！'
      }
    }
  }
</script>
