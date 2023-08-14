<template>
  <div class="search-bar">
    <div class="search-group">
      <label><input id="searchKey" name="key" class="search" :placeholder="placeholder" v-model="keyword" ></label>
    </div>
    <button type="button" class="search-btn" @click="$emit('doSearch',keyword)">搜索</button>
  </div>
</template>

<script>
  export default {
    name: "Search",
    props: ['placeholder'],
    data(){
      return {
        keyword:''
      }
    }
  }
</script>

<style lang="scss" scoped>
  .search-bar { width: 100%; background-color: #ffffff; position: relative; padding: 25px 30px; box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1); z-index: 1; }
  .search-bar .search-group { position: relative; width: 550px; height: 72px; background: #f6f6f6 url("../assets/images/4.png") 21px center no-repeat; border-radius: 6px; background-size: 28px 29px; }
  .search-bar .search-group .search { position: absolute; left: 60px; top: 50%; transform: translate(0, -50%); font-size: 28px; padding: 16px 0; width: 450px; border: none; background: transparent; }
  .search-bar .search-group .search::placeholder { color: #c6c6c6; }
  .search-bar .search-btn { position: absolute; right: 30px; top: 50%; transform: translate(0, -50%); width: 120px; height: 72px; line-height: 72px; text-align: center; border-radius: 6px; background-color: #3088ff; font-size: 28px; color: #ffffff; border: none; }
</style>
