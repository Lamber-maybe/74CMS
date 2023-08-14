<template>
  <div id="app">
    <Head>系统消息</Head>
    <div class="form_top_border"></div>
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
      <div class="box_1">
        <div class="item" v-for="(item, index) in dataset" :key="index">
          <div class="title">
            {{ item.type_text }}
            <div class="right_txt">{{ item.addtime | timeFilter }}</div>
          </div>
          <div class="content">
            <div class="c1">
              {{ item.content }}
            </div>
            <div
              v-if="item.inner_link"
              class="c2"
              @click="handlerDetail(item)"
            >
              查看详情
            </div>
          </div>
        </div>
      </div>
    </van-list>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Msg',
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data () {
    return {
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      params: {
        page: 1,
        pagesize: 10
      }
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    fetchData (init) {
      if (init === true) {
        this.params.page = 1
        this.finished_text = ''
        this.finished = false
      }
      http
        .get(api.member_msglist, this.params)
        .then(res => {
          if (init === true) {
            this.dataset = [...res.data.items]
          } else {
            this.dataset = this.dataset.concat(res.data.items)
          }
          // 加载状态结束
          this.loading = false
          // 数据全部加载完成
          if (res.data.items.length < this.params.pagesize) {
            this.finished = true
            if (init === false) {
              this.finished_text = '没有更多了'
            } else if (res.data.items.length == 0) {
              this.show_empty = true
            }
          }
        })
        .catch(() => {})
    },
    onLoad () {
      this.params.page++
      this.fetchData(false)
    },
    handlerDetail (item) {
      if (item.utype == 1) {
        switch (item.inner_link) {
          case 'resumeshow':
            this.$router.push('/resume/' + item.inner_link_params)
            break
          case 'job_manage':
            this.$router.push('/member/company/joblist')
            break
          case 'company_index':
            this.$router.push('/member/company/index')
            break
          case 'company_auth':
            this.$router.push('/member/company/auth')
            break
          case 'ordershow':
            this.$router.push('/member/order/list')
            break
          case 'im':
            this.$router.push('/im/imlist')
            break
          case 'service':
            this.$router.push('/member/order/add/common?type=service')
            break
          case 'coupon':
            this.$router.push('/member/company/coupon')
            break
          case 'service_setmeal':
            this.$router.push('/member/order/add/common?type=setmeal')
            break
          case 'interview_list':
            this.$router.push('/member/company/interview')
            break
          case 'interview_list_video':
            this.$router.push('/member/company/interview_video')
            break
          case 'recommend_resumelist':
            this.$router.push('/member/company/recommend')
            break
          default:
            break
        }
      } else {
        switch (item.inner_link) {
          case 'companyshow':
            this.$router.push('/company/' + item.inner_link_params)
            break
          case 'interview_list':
            this.$router.push('/member/personal/interview')
            break
          case 'interview_list_video':
            this.$router.push('/member/personal/interview_video')
            break
          case 'apply_list':
            this.$router.push('/member/personal/jobapply')
            break
          case 'ordershow':
            this.$router.push('/member/order/list')
            break
          case 'joblist':
            this.$router.push('/joblist')
            break
          case 'resumeedit':
            this.$router.push('/member/personal/resume')
            break
          case 'im':
            this.$router.push('/im/imlist')
            break
          case 'recommend_joblist':
            this.$router.push('/member/personal/recommend')
            break
          default:
            break
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  .item {
    width: 100%;
    background-color: #ffffff;
    overflow: hidden;
    border-bottom: 1px solid #f3f3f3;
    padding: 10px 0 0;
    .content {
      width: 100%;
      padding: 0 29px;
      .c2 {
        position: relative;
        font-size: 14px;
        color: #333333;
        padding: 11px 5px;
        border-top: 1px solid #f8f8f8;
        &::after{
          position: absolute;right: 3px;top: 17px;width: 7px;height: 7px;border-top: 1px solid #999999;
          border-right: 1px solid #999999;transform: rotate(45deg);content: "";
        }
      }
      .c1 {
        padding: 6px 5px;
        line-height: 1.7;
        word-break: break-all;
        font-size: 14px;
        color: #333333;
      }
    }
    .title {
      position: relative;
      background-color: #fbfbfb;
      font-size: 15px;
      color: #333333;
      padding: 12px 18px;
      font-weight: bold;
      margin: 0 15px;
      .right_txt {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #999999;
      }
    }
  }
  width: 100%;
}
</style>
