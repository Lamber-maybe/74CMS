<template>
    <components :is="who" :utype="utype"></components>
</template>

<script>
import { isMobile } from '@/utils/index'
import choose from "./components/Choose";
import register from "./components/Register";
  export default {
    name: 'Reg',
    components:{
      choose,
      register
    },
    data(){
      return {
        who:'',
        utype:0,
      }
    },
    created(){
      if(isMobile()===true){
        location.href=this.$store.state.config.mobile_domain+'member/reg/personal'
        return false
      }
      if(this.$route.params.utype===undefined){
        this.who = 'choose'
      }else{
        this.who = 'register'
        this.utype = this.$route.params.utype=='company'?1:2;
      }
    }
  }
</script>

<style lang="scss" scoped>

</style>
