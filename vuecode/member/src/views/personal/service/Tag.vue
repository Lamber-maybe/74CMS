<template>
    <el-card>
      <company-title>增值服务</company-title>
      <menu-nav :list="navList"></menu-nav>
      <BuyIncrement type="tag"></BuyIncrement>
    </el-card>
</template>

<script>
  import BuyIncrement from '@/components/personal/service/BuyIncrement'
  export default{
    components:{
      BuyIncrement
    },
    created(){
      this.$store.dispatch('getClassify', 'resumeStrongTag')
    },
    data(){
      return{
        navList: [
          { label: '简历置顶', href: '/personal/service/stick', name: '/personal/service/stick', active: false },
          { label: '醒目标签', href: '/personal/service/tag', name: '/personal/service/tag', active: true },
          { label: '我的订单', href: '/personal/service/order', name: '/personal/service/order', active: false }
        ],
      }
    }
  }
</script>

<style lang="scss" scoped>
  .el-tabs{
    margin-bottom: 10px;
  }
</style>
