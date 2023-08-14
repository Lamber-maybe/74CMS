<template>
    <el-card>
      <company-title>增值服务</company-title>
      <menu-nav :list="navList"></menu-nav>
      <BuyIncrement type="stick"></BuyIncrement>
    </el-card>
</template>

<script>
  import BuyIncrement from '@/components/personal/service/BuyIncrement'
  export default{
    components:{
      BuyIncrement
    },
    created(){
    },
    data(){
      return{
        navList: [
          { label: '简历置顶', href: '/personal/service/stick', name: '/personal/service/stick', active: true },
          { label: '醒目标签', href: '/personal/service/tag', name: '/personal/service/tag', active: false },
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
