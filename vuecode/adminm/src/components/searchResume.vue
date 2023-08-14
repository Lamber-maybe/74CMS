<template>
  <div>
    <div class="cc_top">
      <input type="text" class="cip absolute_for_y" v-model="keyword" placeholder="请输入简历ID/姓名/手机号" />
      <div class="cc_btn absolute_for_y" @click="doSearch">搜索</div>
    </div>
    <van-empty
      image="search"
      description="没有找到对应的数据"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <div class="cc_content">
    <van-list
      v-if="dataset.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
      <van-radio-group v-model="selected_id">
        <div class="cc_item" v-for="(item,index) in dataset" :key="index" @click="funSelected(item)">
          <p class="t1 substring">{{item.fullname}}</p>
          <p class="t2 substring">联系电话：{{item.mobile}}</p>
          <van-radio class="absolute_for_y" :name="item.id"></van-radio>
        </div>
      </van-radio-group>
    </van-list>
    </div>
    <div class="bottom"><van-button type="primary" block color="#1787fb" @click="funConfirm">确定</van-button></div>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  export default {
    name: "searchCompany",
    data () {
      return {
        selected_id: 1,
        row:{},
        dataset: [],
        loading: false,
        finished: false,
        finished_text: '',
        show_empty: true,
        keyword: '',
        page: 1,
        pagesize: 15,
      }
    },
    methods:{
      funSelected(row){
        this.row = row
        this.selected_id = row.id
      },
      funConfirm(){
        this.$emit('onConfirm',this.row)
      },
      buildCondition(){
        let conditions = {
          keyword:this.keyword,
        }
        conditions.page = this.page
        conditions.pagesize = this.pagesize
        return conditions
      },
      fetchData (init) {
        this.show_empty = false

        if (init === true) {
          this.page = 1
          this.finished_text = ''
          this.finished = false
        }
        let conditions = this.buildCondition()
        http
          .get(api.searchResume, conditions)
          .then(res => {
            if (init === true) {
              this.dataset = [...res.data.items]
            } else {
              this.dataset = this.dataset.concat(res.data.items)
            }
            // 加载状态结束
            this.loading = false

            // 数据全部加载完成
            if (res.data.items.length < this.pagesize) {
              this.finished = true
              if (init === false) {
                this.finished_text = '没有更多了'
              } else if (res.data.items.length === 0) {
                this.show_empty = true
              }
            }
          })
          .catch(() => {})
      },
      onLoad () {
        this.page++
        this.fetchData(false)
      },
      doSearch () {
        this.fetchData(true)
      },
    }
  }
</script>

<style lang="scss" scoped>
  .bottom {
    position: fixed;left: 0;bottom: 0;right:0;width: 100%;border-top: 1PX solid #f3f3f3;padding: 0 0;z-index: 9;
    .van-button { border-radius: 0; }
  }
  .cc_content {
    width: 100%;padding: 0 30px;max-height: 80vh;overflow-y: auto;
    .cc_item {
      position: relative;border-top: 1PX solid #e2e2e2;
      &:nth-of-type(1) { border-top: 0; }
      .van-radio {
        position: absolute;right: 30px;top: 50%;z-index: 3;
      }
      .t1 {
        padding: 20px 70px 20px 0;font-size: 28px;
      }
      .t2 {
        padding: 0 70px 20px 0;font-size: 24px;
      }
    }
  }
  .cc_top {
    position: relative;width: 100%;height: 120px;background-color: #f6f6f6;
    .cip {
      position: absolute;left: 30px;top: 50%;width: 400px;height: 72px;line-height: 72px;
      padding-left: 15px;font-size: 26px;border: 0;
      &::placeholder { color: #999999; }
    }
    .cc_btn {
      position: absolute;right: 30px;top: 50%;width: 120px;height: 72px;line-height: 72px;
      text-align: center;background-color: #3088ff;border-radius: 6px;font-size: 26px;
      color: #ffffff;border: 0;
    }
  }
</style>
