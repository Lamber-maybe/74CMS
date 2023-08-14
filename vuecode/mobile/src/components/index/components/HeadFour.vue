<template>
  <div>
    <Head :show_back="'false'">{{ $store.state.config.sitename }}</Head>
    <Ad
      v-if="ad_dataset_top.items.length > 0"
      :dataset="ad_dataset_top"
      :no_top_border="true"
    ></Ad>
    <div class="b3" @click="toggleSearch">
      <div class="content">
        请输入职位/公司
        <div class="btn">搜索</div>
      </div>
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
  name: 'HeadFour',
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
    .btn {
      position: absolute;
      right: 0;
      top: 0;
      font-size: 14px;
      color: #1787fb;
      background-color: #e0effe;
      padding: 12.5px 0;
      width: 60px;
      text-align: center;
    }
    width: 345px;
    padding: 12.5px 0 12.5px 36px;
    color: #d2d2d2;
    font-size: 14px;
    border-radius: 44px;
    overflow: hidden;
    background: #ffffff url("../../../assets/images/search_ico_gray.svg") 15px
      center no-repeat;
    background-size: 15px;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    position: absolute;
    left: 50%;
    transform: translate(-50%, 0);
    top: -26px;
  }
  width: 100%;
  margin-bottom: 10px;
  position: relative;
  background-color: #ffffff;
  z-index: 1;
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
    padding: 14.5px 0;
    text-align: center;
    z-index: 99;
  }
  position: relative;
  width: 100%;
  height: 54px;
}
</style>
