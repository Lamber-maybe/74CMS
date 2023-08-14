<template>
  <div>
    <div class="form_split_10"></div>
    <ArticleOne
      v-if="plan_id == 1"
      :dataset="dataset"
      @jumpTo="jumpTo"
    ></ArticleOne>
  </div>
</template>

<script>
import ArticleOne from './components/NewsOne'
export default {
  name: 'Article',
  props: ['plan_id', 'dataset'],
  components: {
    ArticleOne
  },
  created () {},
  methods: {
    jumpTo (params) {
      if (params.url == '') {
        this.$router.push('/news/' + params.id)
      } else {
        window.location.href = params.url
      }
    }
  }
}
</script>
