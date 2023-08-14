<template>
  <resumelist listtype="all" :show-options-audit="true" />
</template>

<script>
import resumelist from '../components/list.vue'

export default {
  components: {
    resumelist
  }
}
</script>
