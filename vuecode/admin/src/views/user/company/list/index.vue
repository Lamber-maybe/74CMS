<template>
  <companylist listtype="all" :show-audit-option="true" />
</template>

<script>
import companylist from '../components/list.vue'

export default {
  components: {
    companylist
  }
}
</script>
