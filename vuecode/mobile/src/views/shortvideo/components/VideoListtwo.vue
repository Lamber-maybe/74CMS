<template>
    <div id="app">
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
              <ListItem class="itemtwo" v-for="(item,index) in data1" :key="index" :item="item" :type="videotype" :gointype="gointype" :listid="id" :index="index"></ListItem>
            </div>

            <div id="line2" class="fall-line">
              <ListItem class="itemtwo" v-for="(item,index) in data2" :key="index" :item="item" :type="videotype" :gointype="gointype" :listid="id" :index="index"></ListItem>
            </div>
          </div>
        </van-list>
        <van-empty
        image="search"
        description="没有找到对应的数据"
        style="background-color:#fff;height:calc(100vh - 59px - 53px - 55px - 65px);"
        v-if="show_empty == true"
        />
    </div>
</template>
<script>
import ListItem from './itemVideo'
import http from '@/utils/http'
import api from '@/api'

export default {
  props: ['videotype', 'id', 'gointype'],
  components: {
    ListItem
  },
  data () {
    return {
      loading: false,
      finished: false,
      finished_text: '',
      page: 1,
      pagesize: 15,
      params: {
      },
      show_empty: false,
      list: [],
      data1: [],
      data2: []
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    onLoad () {
      this.page++
      this.fetchData(false)
    },
    fetchData (init) {
      let that = this
      let conditions
      let httpurl
      if (this.gointype == 'unitplay') {
        if (this.videotype == 2) {
          conditions = {type: '2', rid: this.id}
        } else {
          conditions = {type: '1', comid: this.id}
        }
        httpurl = api.shortvideo_videolist
      } else if (this.gointype == 'mycollection') {
        conditions = { ...this.params }
        httpurl = api.shortvideo_myfavorites
      }
      if (init == true) {
        this.page = 1
        this.finished_text = ''
        this.finished = false
      }
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      this.loading = true
      http
        .get(httpurl, conditions)
        .then((res) => {
          if (init == true) {
            this.list = [...res.data]
          } else {
            this.list = this.list.concat(res.data)
          }
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
          that.doSort(0, res.data)
        })
        .catch(() => {})
    },
    doSort (index, list) {
      if (index < list.length) {
        try {
          if (document.getElementById('line1').offsetHeight <= document.getElementById('line2').offsetHeight) {
            this.data1.push(list[index])
          } else {
            this.data2.push(list[index])
          }
          let that = this
          this.$nextTick(() => {
            setTimeout(() => {
              that.doSort(index + 1, list)
            }, 400)
          })
        } catch (error) {
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped>
#app{
    line-height: 1;
}
.list{
    min-height: 100%;
}
#fall-list {
    position: relative;
  }
  #fall-list .fall-line {
    float: left;
    width: calc(50vw - 20px);
    margin-left: 13px;
    margin-top: 10px;
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
