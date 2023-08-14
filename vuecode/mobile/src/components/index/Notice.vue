<template>
  <div>
    <div class="form_split_10"></div>
    <NoticeOne
      v-if="plan_id == 1"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></NoticeOne>
    <NoticeTwo
      v-if="plan_id == 2"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></NoticeTwo>
  </div>
</template>

<script>
import NoticeOne from './components/NoticeOne'
import NoticeTwo from './components/NoticeTwo'
export default {
  name: 'Notice',
  props: ['plan_id', 'dataset'],
  components: {
    NoticeOne,
    NoticeTwo
  },
  created () {},
  methods: {
    jumpTo (params) {
      if (params.url == '') {
        this.$router.push('/notice/' + params.id)
      } else {
        window.location.href = params.url
      }
    }
  }
}
</script>
