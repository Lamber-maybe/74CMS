<template>
  <div>
    <div class="form_split_10"></div>
    <HotOne v-if="plan_id == 1" :dataset="dataset" @jumpTo="jumpTo"></HotOne>
    <HotTwo v-if="plan_id == 2" :dataset="dataset" @jumpTo="jumpTo"></HotTwo>
    <HotThree
      v-if="plan_id == 3"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></HotThree>
  </div>
</template>

<script>
import HotOne from './components/HotOne'
import HotTwo from './components/HotTwo'
import HotThree from './components/HotThree'
export default {
  name: 'Hotword',
  props: ['plan_id', 'dataset'],
  components: {
    HotOne,
    HotTwo,
    HotThree
  },
  created () {},
  methods: {
    jumpTo (params) {
      this.$router.push('/joblist?keyword=' + params.keyword)
    }
  }
}
</script>
