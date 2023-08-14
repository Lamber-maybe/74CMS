<template>
  <div id="app">
    <OrderList :utype="$store.state.LoginType"></OrderList>
  </div>
</template>

<script>
import OrderList from '@/components/service/OrderList'
export default {
  name: 'Order',
  components: {
    OrderList
  }
}
</script>
