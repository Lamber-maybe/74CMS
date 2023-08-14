<template>
  <el-card>
    <company-title>订单列表</company-title>
    <OrderList :utype="1"></OrderList>
  </el-card>
</template>

<script>
import OrderList from '@/components/member/service/OrderList'
export default {
  components: {
    OrderList
  }
}
</script>
