<template>
    <div id="app">
        <swiper class="banner" :options="swiperOption" @click-slide="handleClickSlide" v-if="$store.state.swiperLoaded">
          <swiper-slide v-for="(item, index) in dataset" :key="index">
            <img :src="item.imgurl"/>
          </swiper-slide>
        </swiper>
        <div class="searchdiv" @click="toggleSearch">
            <img src="../../../assets/freelance/images/resumelist_03.png"/>
            <span :class="params.title ? 'isnull' : ''">{{ params.title ? params.title : "请输入关键字"}}</span>
        </div>
        <van-popup
        v-model="show"
        position="top"
        :overlay="true"
        :style="{ height: '90%', width: '100%' }"
        >
            <TopSearch
                type="lgproject"
                @hideSearch="toggleSearch"
                @doSearch="doSearchByKeyword"
            ></TopSearch>
        </van-popup>
        <van-list
        class="list clearfix"
        v-model="loading"
        :finished="finished"
        :finished-text="finished_text"
        @load="onLoad"
        :immediate-check="false"
        v-if="show_empty != true"
        >
          <div id="fall-list" class="clearfix">
            <div id="line1" class="fall-line">
              <ListItem class="fall-item wow animate__zoomIn" data-wow-duration="1s" data-wow-delay="0.5s" v-for="(item,index) in data1" :key="index" :item="item" :type="videotype" :gointype="'playlist'" :index="index"></ListItem>
            </div>
            <div id="line2" class="fall-line">
              <ListItem class="fall-item wow animate__zoomIn" data-wow-duration="1s" data-wow-delay="0.5s" v-for="(item,index) in data2" :key="index" :item="item" :type="videotype" :gointype="'playlist'" :index="index"></ListItem>
            </div>
          </div>
        </van-list>
        <van-empty
        image="search"
        description="没有找到对应的数据"
        style="background-color:#fff;height:calc(100vh - 59px - 53px - 55px - 65px);"
        v-if="show_empty == true"
        />
        <BottomMenu></BottomMenu>
    </div>
</template>
<script>
import BottomMenu from './BottomMenu.vue'
import ListItem from './itemVideo'
import http from '@/utils/http'
import api from '@/api'
// import 'animate.css'
// import {WOW} from 'wowjs'

export default {
  props: ['videotype', 'apitype'],
  components: {
    BottomMenu,
    ListItem
  },
  data () {
    return {
      swiperOption: {
        loop: true,
        initialSlide: 0,
        speed: 800
      },
      show: false,
      loading: false,
      finished: false,
      finished_text: '',
      page: 1,
      pagesize: 4,
      show_empty: false,
      type: '1',
      params: {
        title: '',
        type: this.videotype
      },
      list: [],
      dataset: [],
      data1: [],
      data2: []
      // isgetData: true
    }
  },
  created () {
    this.fetchData(true)
    this.getadlist()
  },
  methods: {
    onLoad () {
      this.page++
      this.fetchData(false)
    },
    // 关键词搜索函数，重置所有参数，并且把当前页设置为第一页
    doSearchByKeyword (data) {
      // for (const key in this.params) {
      //   this.params[key] = ''
      // }
      this.params.title = data.keyword
      this.page = 1
      // this.isgetData = false
      this.fetchData(true)
    },
    toggleSearch () {
      this.show = !this.show
    },
    fetchData (init) {
      let that = this
      this.show_empty = false
      let conditions = { ...this.params }
      if (init == true) {
        this.page = 1
        this.finished_text = ''
        this.finished = false
      }
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      this.loading = true
      http
        .get(api.shortvideo_videolist, conditions)
        .then((res) => {
          if (init == true) {
            this.list = [...res.data]
          } else {
            this.list = this.list.concat(res.data)
          }
          // this.isgetData = true
          // 加载状态结束
          this.loading = false

          // 数据全部加载完成
          if (res.data.length < this.pagesize) {
            this.finished = true
            if (init == false) {
              this.finished_text = '没有更多了'
            } else if (res.data.length == 0) {
              this.show_empty = true
            }
          }
          // this.$nextTick(() => {
          //   new WOW().init({
          //     mobile: true,
          //     live: false
          //   })
          // })

          that.doSort(0, res.data, init)
        })
        .catch(() => {})
    },
    doSort (index, list, init) {
      if (index < list.length) {
        try {
          if (init) {
            this.data1 = []
            this.data2 = []
          }
          if (document.getElementById('line1').offsetHeight <= document.getElementById('line2').offsetHeight) {
            this.data1.push(list[index])
          } else {
            this.data2.push(list[index])
          }
          let that = this

          this.$nextTick(() => {
            // if (this.isgetData) {
            setTimeout(() => {
              that.doSort(index + 1, list)
            }, 400)
            // }
          })
        } catch (error) {
        }
      }
    },
    handleClickSlide (index, reallyIndex) {
      this.handlerClick(this.dataset[reallyIndex])
    },
    handlerClick (item) {
      if (item.link_url) {
        window.location.href = item.link_url
      } else if (item.company_id > 0) {
        this.$router.push('/company/' + item.company_id)
      } else if (item.inner_link) {
        let path = ''
        let params_type = 'query'
        switch (item.inner_link) {
          case 'index':
            path = '/'
            break
          case 'joblist':
            path = '/joblist'
            break
          case 'jobshow':
            path = '/job'
            params_type = 'params'
            break
          case 'resumelist':
            path = '/resumelist'
            break
          case 'noticeshow':
            path = '/notice'
            params_type = 'params'
            break
          case 'articlelist':
            path = '/newslist'
            break
          case 'articleshow':
            path = '/news'
            params_type = 'params'
            break
          default:
            path = ''
            break
        }
        if (path != '') {
          if (item.inner_link_params > 0) {
            if (params_type == 'query') {
              path = path + '?id=' + item.inner_link_params
            } else {
              path = path + '/' + item.inner_link_params
            }
          }
          this.$router.push(path)
        }
      }
    },
    getadlist () {
      http
        .get(api.shortvideo_adlist, {type: this.apitype})
        .then((res) => {
          this.dataset = res.data
        })
        .catch(() => {})
    }
  }
}
</script>
<style lang="scss" scoped>
#app{
    line-height: 1;
}
.animate__animated.animate__zoomIn {
  --animate-duration: .5s;
}
.banner{
  width: 100vw;
  background-color: #f8f8f8;
  img {
    width: 100vw;
    height: 121px;
    background-color: #000000;
  }
}
.searchdiv{
    height: 36px;
    margin: 10px 13px;
    border-radius: 50px;
    border: 1px solid #d0d0d0;
    display: flex;
    align-items: center;
    img{
        margin-left: 11px;
        margin-right: 8px;
        width: 14px;
        height: 14px;
    }
    span{
        color: #b4bdc9;
        font-size: 15px;
        &.isnull{
            color: #333333;
        }
    }
}
.list{
    background-color: #f2f4f3;
    min-height: calc(100vh - 59px - 53px - 55px - 121px);
}
#fall-list {
    position: relative;
  }
  #fall-list .fall-line {
    float: left;
    width: calc(50vw - 20px);
    margin-left: 13px;
  }
  #fall-list .fall-item {
    width: calc(50vw - 20px);
    margin-top: 10px;
    background-color: #fff
  }
  #fall-list .fall-item img {
    width: 100vw;
  }
</style>
