<template>
  <div id="app">
    <router-view/>
  </div>
</template>
<script>
export default {
  mounted () {
      let code = this.getQueryVariable('code')
      let state = this.getQueryVariable('state')
      if(code!==undefined && state=='oauth'){
        location.href = location.href.replace(location.search,'')+'login/oauth/weixin?code='+code
      }
  },
  methods:{
    getQueryVariable(variable)
    {
      var query = window.location.search.substring(1);
      var vars = query.split("&");
      for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if(pair[0] == variable){return pair[1];}
      }
      return false;
    }
  }
}
</script>

<style lang="scss">
</style>
