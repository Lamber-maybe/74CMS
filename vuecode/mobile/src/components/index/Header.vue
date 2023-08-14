<template>
  <div>
    <HeadOne v-if="plan_id == 1"></HeadOne>
    <HeadTwo v-if="plan_id == 2"></HeadTwo>
    <HeadThree v-if="plan_id == 3"></HeadThree>
    <HeadFour v-if="plan_id == 4"></HeadFour>
  </div>
</template>

<script>
import HeadOne from './components/HeadOne'
import HeadTwo from './components/HeadTwo'
import HeadThree from './components/HeadThree'
import HeadFour from './components/HeadFour'
export default {
  name: 'Header',
  props: ['plan_id'],
  components: {
    HeadOne,
    HeadTwo,
    HeadThree,
    HeadFour
  },
  created () {},
  methods: {}
}
</script>
