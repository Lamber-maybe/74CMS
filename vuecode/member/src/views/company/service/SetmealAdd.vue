<template>
    <BuySetmeal :type="type"></BuySetmeal>
</template>

<script>
  import BuySetmeal from '@/components/company/service/BuySetmeal'
  export default{
    components:{
      BuySetmeal
    },
    data(){
      return{
        type:''
      }
    },
    created(){
      this.type = this.$route.params.type===undefined?'jobstick':this.$route.params.type
    },
    methods:{
    },
  }
</script>

<style lang="scss" scoped="scoped">

</style>
