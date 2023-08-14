<template>
  <div class="con">
    <div class="b1">{{$route.query.message}}</div>
  </div>
</template>

<script>
export default {
  name: 'Error',
  data(){
    return {
    }
  },
  created(){
  },
  mounted() {
  },
  methods:{

  }
}
</script>

<style lang="scss" scoped>
  .con {
    padding-top: 245px;text-align: center;
    .b1 {
      width: 300px;height: 160px;margin: 0 auto 36px;
      font-size:26px;
    }
  }
</style>
