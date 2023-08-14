<template>
  <div class="my_app">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  created () {
    window.addEventListener('beforeunload', () => {
        if(window.ws!==undefined){
          window.ws.close();
        }
        // 刷新页面时把config置空，保证系统配置信息的时效性
        this.$store.state.config = ''
        localStorage.setItem('vuex', JSON.stringify(this.$store.state))
    })
  },
}
</script>

<style lang="scss"></style>
