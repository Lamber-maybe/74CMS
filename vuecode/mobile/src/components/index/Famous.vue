<template>
  <div>
    <div class="form_split_10"></div>
    <FamousOne
      v-if="plan_id == 1"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></FamousOne>
    <FamousTwo
      v-if="plan_id == 2"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></FamousTwo>
    <FamousThree
      v-if="plan_id == 3"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></FamousThree>
    <FamousFour
      v-if="plan_id == 4"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></FamousFour>
  </div>
</template>

<script>
import FamousOne from './components/StarCompanyOne'
import FamousTwo from './components/StarCompanyTwo'
import FamousThree from './components/StarCompanyThree'
import FamousFour from './components/StarCompanyFour'
export default {
  name: 'Famous',
  props: ['plan_id', 'dataset'],
  components: {
    FamousOne,
    FamousTwo,
    FamousThree,
    FamousFour
  },
  created () {},
  methods: {
    jumpTo (params) {
      this.$router.push('/company/' + params.id)
    }
  }
}
</script>
