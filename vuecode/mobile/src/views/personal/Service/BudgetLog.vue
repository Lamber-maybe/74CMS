<template>
  <div id="app" v-wechat-title="this.pageTitle">
    <PointsLog></PointsLog>
  </div>
</template>

<script>
import PointsLog from '@/components/account/PointsLog'
export default {
  name: 'PersonalBudgetLog',
  components: {
    PointsLog
  },
  data () {
    return {
      pageTitle: ''
    }
  },
  created () {
    this.pageTitle = this.$route.meta.title
    this.pageTitle.replace('积分', this.$store.state.config.points_byname)
  }
}
</script>
