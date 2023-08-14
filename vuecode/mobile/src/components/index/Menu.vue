<template>
  <div>
    <MenuOne v-if="plan_id == 1" :dataset="dataset" @jumpTo="jumpTo"></MenuOne>
    <MenuTwo v-if="plan_id == 2" :dataset="dataset" @jumpTo="jumpTo"></MenuTwo>
    <MenuThree
      v-if="plan_id == 3"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></MenuThree>
    <MenuFour
      v-if="plan_id == 4"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></MenuFour>
  </div>
</template>

<script>
import MenuOne from './components/MenuOne'
import MenuTwo from './components/MenuTwo'
import MenuThree from './components/MenuThree'
import MenuFour from './components/MenuFour'
export default {
  name: 'Menu',
  props: ['plan_id', 'dataset'],
  components: {
    MenuOne,
    MenuTwo,
    MenuThree,
    MenuFour
  },
  created () {},
  methods: {
    jumpTo (params) {
      if (params.url === '') {
        const menuRoute = {
          resume_add: '/member/personal/index',
          job_add: '/member/company/index',
          job: '/joblist',
          resume: '/resumelist',
          company: '/companylist',
          nearby: '/jobnearby',
          high_wage: '/joblist?minwage=5000',
          article: '/newslist'
        }
        this.$router.push(menuRoute[params.alias])
      } else {
        window.location.href = params.url
      }
    }
  }
}
</script>
