<template>
  <div>
    <div class="b1">
      <div
        class="link_block l1"
        v-if="company == 1"
        @click="$router.push('/companylist')"
      ></div>
      <div
        class="link_block l2"
        v-if="high_wage == 1"
        @click="$router.push('/joblist?minwage=5000')"
      ></div>
      <div
        class="link_block l3"
        v-if="nearby == 1"
        @click="$router.push('/jobnearby')"
      ></div>
      <div
        class="link_block l4"
        v-if="jobfair == 1"
        @click="$router.push('/jobfairol')"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LinkBlock',
  props: ['plan_id'],
  data () {
    return {
      jobfair: 0,
      company: 0,
      nearby: 0,
      high_wage: 0
    }
  },
  created () {
    let arr = this.plan_id.split(',')
    if (arr.includes('online_jobfair') === true) {
      this.jobfair = 1
    }
    if (arr.includes('company') === true) {
      this.company = 1
    }
    if (arr.includes('nearby') === true) {
      this.nearby = 1
    }
    if (arr.includes('high_wage') === true) {
      this.high_wage = 1
    }
  },
  methods: {}
}
</script>

<style lang="scss" scoped>
.b1 {
  .link_block {
    &.l1 {
      background: url("../../../assets/images/index/link_1.jpg") 0 no-repeat;
      background-size: 165px 75px;
    }
    &.l2 {
      background: url("../../../assets/images/index/link_2.jpg") 0 no-repeat;
      background-size: 165px 75px;
    }
    &.l3 {
      background: url("../../../assets/images/index/link_3.jpg") 0 no-repeat;
      background-size: 165px 75px;
    }
    &.l4 {
      background: url("../../../assets/images/index/link_4.jpg") 0 no-repeat;
      background-size: 165px 75px;
    }
    flex: 1;
    max-width: 4.4rem;
    height: 75px;
    border-radius: 4px;
  }
  display: flex;
  justify-content: space-between;
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px 17px 17px;
}
</style>
