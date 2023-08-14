<template>
  <div>
    <div class="b1">
      <Head :show_back="'false'" :bg="'tran'" :show_sub="$store.state.config.subsite_open==1?'true':''">{{ $store.state.config.sitename }}</Head>
      <div class="head_search" @click="toggleSearch">请输入职位/公司</div>
    </div>
    <van-popup
      v-model="showSearch"
      position="top"
      :overlay="true"
      :style="{ height: '90%', width: '100%' }"
    >
      <TopSearch
        type="job"
        @hideSearch="toggleSearch"
        @doSearch="doSearchByKeyword"
      ></TopSearch>
    </van-popup>
  </div>
</template>

<script>
import { obj2Param } from '@/utils/index'
export default {
  name: 'HeadThree',
  data () {
    return {
      showSearch: false
    }
  },
  methods: {
    toggleSearch () {
      this.showSearch = !this.showSearch
    },
    doSearchByKeyword (data) {
      let params = {
        keyword: data.keyword,
        page: 1
      }
      this.$router.push('/joblist' + obj2Param(params, '?'))
    }
  }
}
</script>

<style lang="scss" scoped>
.b1 {
  .head_search {
    width: 340px;
    margin: 15px auto 0;
    padding: 9px 0 9px 36px;
    font-size: 14px;
    color: #d2d2d2;
    border-radius: 38px;
    background: #ffffff url('../../../assets/images/search_ico_gray.svg') 15px
      center no-repeat;
    background-size: 15px;
  }
  .head_content {
    padding: 16px 0;
    text-align: center;
    position: relative;
    font-size: 18px;
    font-weight: bold;
    color: #ffffff;
    margin-bottom: 15px;
  }
  width: 100%;
  height: 145px;
  background: url('../../../assets/images/index/index_head_three_bg.png') 0
    no-repeat;
  background-size: 100% 145px;
}
</style>
