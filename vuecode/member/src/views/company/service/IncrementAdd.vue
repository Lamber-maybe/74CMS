<template>
    <BuyIncrement :type="type"></BuyIncrement>
</template>

<script>
  import BuyIncrement from '@/components/company/service/BuyIncrement'
  export default{
    components:{
      BuyIncrement
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
