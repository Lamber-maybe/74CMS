<template>
  <div>
    <Head>{{ title }}</Head>
    <Search @doSearch="doSearch" placeholder="请输入用户名/UID/手机号"></Search>
    <van-overlay z-index="10" :show="fetchDataFinish===false"><van-loading color="#1989fa" class="loading" >数据加载中...</van-loading></van-overlay>
    <van-empty
      image="search"
      description="没有找到对应的数据"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <van-list
      v-if="dataset.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
    <div class="b1">
      <div class="b_item" @click="jumpShow(item.uid)" v-for="(item,index) in dataset" :key="index">
        <p class="t1 substring">{{item.utype==1?(!item.companyname?'未知企业':item.companyname):(!item.fullname?'未知姓名':item.fullname)}}</p><van-icon class="arrow" name="arrow" color="#999999" />
        <div class="i_uid">UID:{{item.uid}}</div>
        <div class="t_line">
          <div class="tl_cell"><span class="l_title">用户名：</span><span class="l_con">{{item.username}}</span></div>
          <div class="tl_cell"><span class="l_title">状态：</span><span class="l_con" :class="item.status==0?'red':''">{{item.status==1?'正常':'已锁定'}}</span></div>
        </div>
        <div class="t_line last">
          <div class="tl_cell"><span class="l_title">手机号：</span><span class="l_con">{{item.mobile}}</span></div>
          <div class="tl_cell"><span class="l_title">{{ type === 0 ? '类型：' : '登录：' }}</span><span class="l_con" v-if="type===0">{{item.utype==1?'企业会员':'个人会员'}}</span><span class="l_con" v-else>{{item.last_login_time|timeFilter}}</span></div>
        </div>
        <div class="i_tip">{{item.reg_time|timeFilter}} 通过{{item.platform_cn}}注册</div>
        <div class="i_btn b1">查看</div>
      </div>
    </div>
    </van-list>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  import { formatTime } from '@/utils/index'
  export default {
    name: "userList",
    filters: {
      timeFilter (timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    data () {
      return {
        fetchDataFinish:false,
        dataset: [],
        loading: false,
        finished: false,
        finished_text: '',
        show_empty: false,
        keyword: '',
        page: 1,
        pagesize: 15,
        regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
        regularNumber:/^\d+$/,
        title: '',
        type: 1 // 1->企业会员 2->个人会员 3->无效会员
      }
    },
    created () {
      this.type = parseInt(this.$route.params.type)
      this.title = this.type === 1 ? '企业会员' : this.type === 0 ? '无效会员' : '个人会员'
      this.fetchData(true)
    },
    methods: {
      buildCondition(){
        let conditions = {
          keyword:this.keyword,
        }
        conditions.page = this.page
        conditions.pagesize = this.pagesize
        conditions.list_type = this.type==1?'company':(this.type==0?'invalid':'personal')
        if (this.regularMobile.test(conditions.keyword)) {
          conditions.key_type = 3
        }else if(this.regularNumber.test(conditions.keyword)){
          conditions.key_type = 1
        }else{
          conditions.key_type = 2
        }
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
          .get(api.memberList, conditions)
          .then(res => {
            if (init === true) {
              this.dataset = [...res.data.items]
            } else {
              this.dataset = this.dataset.concat(res.data.items)
            }
            // 加载状态结束
            this.loading = false
            this.fetchDataFinish = true

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
      doSearch (keyword) {
        this.fetchDataFinish = false
        this.keyword = keyword
        this.fetchData(true)
      },
      jumpShow (uid) {
        // 跳转到会员详情页
        this.$router.push(`/user/${this.type}/${uid}`)
      }
    }
  }
</script>

<style lang="scss" scoped>
.loading{
  text-align: center;
  top: 50%;
}
.van-overlay{
  background-color:rgba(255, 255, 255, 0.9)
}
  .b1 {
    .b_item {
      width: 100%;background-color: #ffffff;margin-top: 10px;position: relative;padding: 30px 30px 130px 30px;
      &:nth-of-type(1) { margin-top: 0; }
      .arrow { position: absolute;right: 20px;top: 30px;font-size: 42px; }
      .t1 { font-size: 30px;font-weight: bold;color: #333333;max-width: 510px;margin-bottom: 30px; }
      .t_line {
        display: flex;margin-bottom: 18px;
        &.last { margin-bottom: 0; }
        .tl_cell {
          flex: 1;font-size: 24px;
          .l_title { color: #999999; }
          .l_con {
            color: #333333;
            &.red { color: #f56c6c; }
          }
        }
      }
      .i_btn {
        position: absolute;bottom: 43px;right: 30px;font-size: 24px;color: #333333;padding: 8px 34px;
        border: 1PX solid #cccccc;border-radius: 999px;
        &.b1 { right: 30px; }
      }
      .i_uid {
        position: absolute;right: 62px;top: 33px;height: 38px;line-height: 38px;text-align: center;font-size: 24px;
        color: #999999;
      }
      .i_tip { position: absolute;left: 30px;bottom: 53px;font-size: 24px;color: #999999; }
    }
  }
</style>
