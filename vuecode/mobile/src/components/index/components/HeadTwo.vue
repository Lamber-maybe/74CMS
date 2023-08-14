<template>
  <div class="hb">
    <Head :show_back="'false'" :show_sub="$store.state.config.subsite_open==1?'true':''">{{ $store.state.config.sitename }}</Head>
    <div class="b2">
      <img :src="$store.state.config.logo" alt="" />
    </div>
    <div class="b3" @click="toggleSearch">
      请输入职位/公司
      <div class="btn">搜索</div>
    </div>
    <Ad
      v-if="ad_dataset_top.items.length > 0"
      :dataset="ad_dataset_top"
      :no_top_border="true"
    ></Ad>
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
import http from '@/utils/http'
import api from '@/api'
import Ad from '@/components/Ad'
export default {
  name: 'HeadTwo',
  components: {
    Ad
  },
  data () {
    return {
      showSearch: false,
      ad_dataset_top: { alias: 'QS_top_slide', items: [] }
    }
  },
  mounted () {
    this.fetchAd()
  },
  methods: {
    fetchAd () {
      http
        .post(api.ad_list, {
          alias: ['QS_top_slide@mobile']
        })
        .then(res => {
          this.ad_dataset_top.items = res.data.items['QS_top_slide@mobile']
        })
        .catch(() => {})
    },
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
.b3 {
  .btn {
    position: absolute;
    right: 0;
    top: -1px;
    font-size: 14px;
    color: #ffffff;
    width: 60px;
    text-align: center;
    border: 1px solid #1787fb;
    background-color: #1787fb;
    z-index: 1;
    padding: 9px 0;
    border-radius: 0 4px 4px 0;
  }
  width: 340px;
  margin: 0 auto 15px;
  border: 1px solid #e2e2e2;
  border-radius: 4px;
  padding: 9px 0 9px 36px;
  font-size: 14px;
  background: #ffffff url("../../../assets/images/search_ico_gray.svg") 15px
    center no-repeat;
  background-size: 15px;
  color: #d2d2d2;
  position: relative;
  border-right: 0;
}
.b2 {
  img {
    max-height: 50px;
    max-width: 350px;
    border: 0;
    display: inline-block;
    vertical-align: top;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 15px 0;
  text-align: center;
  border-top: 1px solid #f3f3f3;
}
.b1 {
  .content {
    position: fixed;
    width: 100%;
    background-color: #ffffff;
    left: 0;
    top: 0;
    right: 0;
    font-size: 18px;
    font-weight: bold;
    color: #333333;
    padding: 18px 0 18px 16px;
    z-index: 99;
  }
  position: relative;
  width: 100%;
  height: 54px;
}
.hb {
  background-color: #ffffff;
}
</style>
