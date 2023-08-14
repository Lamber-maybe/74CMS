<template>
  <div id="app">
    <RegChoose v-if="utype == 0"></RegChoose>
    <RegCompany v-if="utype == 1"></RegCompany>
    <RegPersonal v-if="utype == 2"></RegPersonal>
  </div>
</template>

<script>
import RegChoose from '@/components/RegChoose'
import RegCompany from '@/components/RegCompany'
import RegPersonal from '@/components/RegPersonal'
export default {
  name: 'Reg',
  components: {
    RegChoose,
    RegCompany,
    RegPersonal
  },
  data () {
    return {
      utype: 1
    }
  },
  created () {},
  watch: {
    $route: {
      immediate: true,
      handler (to, from) {
        this.utype =
          this.$route.params.utype_alias === 'company'
            ? 1
            : this.$route.params.utype_alias === 'personal'
              ? 2
              : 0
      }
    }
  }
}
</script>
