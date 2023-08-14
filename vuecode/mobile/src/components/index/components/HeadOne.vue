<template>
  <div>
    <Head>{{ $store.state.config.sitename }}</Head>
    <Ad
      v-if="ad_dataset_top.items.length > 0"
      :dataset="ad_dataset_top"
      :no_top_border="true"
    ></Ad>
    <div class="b3" @click="toggleSearch">
      <div class="content">请输入关键字</div>
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
import http from '@/utils/http'
import api from '@/api'
import Ad from '@/components/Ad'
export default {
  name: 'HeadOne',
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
  .content {
    width: 340px;
    padding: 9px 0 9px 36px;
    color: #999999;
    font-size: 14px;
    margin: 0 auto;
    border-radius: 3px;
    background: #f3f3f3 url("../../../assets/images/search_ico_gray.svg") 15px
      center no-repeat;
    background-size: 15px;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 17px 0 0;
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
    padding: 18px 0;
    text-align: center;
    z-index: 99;
  }
  position: relative;
  width: 100%;
  height: 54px;
}
</style>
