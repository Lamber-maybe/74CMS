<template>
  <div id="app">
    <MemberAccount :utype="2"></MemberAccount>
  </div>
</template>

<script>
import MemberAccount from '@/components/account/MemberAccount'
export default {
  name: 'Account',
  components: {
    MemberAccount
  }
}
</script>
