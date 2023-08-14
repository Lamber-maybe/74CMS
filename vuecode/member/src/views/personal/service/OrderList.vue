<template>
    <el-card>
      <company-title>增值服务</company-title>
      <menu-nav :list="navList"></menu-nav>
      <OrderList :utype="2"></OrderList>
    </el-card>
</template>

<script>
  import OrderList from '@/components/member/service/OrderList'
  export default{
    components:{
      OrderList
    },
    created(){
    },
    data(){
      return{
        navList: [
          { label: '简历置顶', href: '/personal/service/stick', name: '/personal/service/stick', active: false },
          { label: '醒目标签', href: '/personal/service/tag', name: '/personal/service/tag', active: false },
          { label: '我的订单', href: '/personal/service/order', name: '/personal/service/order', active: true }
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
