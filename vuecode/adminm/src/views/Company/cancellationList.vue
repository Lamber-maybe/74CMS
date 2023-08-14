<template>
  <div>
    <Head>企业注销申请</Head>
    <van-overlay z-index="10" :show="fetchDataFinish===false">
      <van-loading color="#1989fa" class="loading">数据加载中...</van-loading>
    </van-overlay>
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
        <div class="b_item" v-for="(item,index) in dataset" :key="index">
          <p class="t1 substring">{{ item.companyname }}</p>
          <div class="i_tag" :class="item.status | statusFilter">{{ options_status[item.status] }}</div>
          <div class="t_line">
            <div class="tl_cell">
              <span class="l_title">联系人：</span>
              <span class="l_con">{{ item.contact }}</span>
            </div>
            <div class="tl_cell">
              <span class="l_title">申请时间：</span>
              <span class="l_con">{{ item.addtime|timeFilter }}</span>
            </div>
          </div>
          <div class="t_line last">
            <div class="tl_cell">
              <span class="l_title">手机号：</span>
              <span class="l_con">{{ item.mobile }}</span>
            </div>
            <div class="tl_cell">
              <span class="l_title">处理时间：</span>
              <span class="l_con" v-if="item.handlertime>0">{{ item.handlertime|timeFilter }}</span>
              <span class="l_con" v-else>-</span>
            </div>
          </div>
          <div class="i_btn b1" @click="funDel(item)">删除</div>
          <div class="i_btn b2" @click="funHandle(item)">处理</div>
          <div class="i_btn b3" v-if="item.is_backups === 0" @click="backups(item)">备份</div>
          <div class="i_btn b3-1" v-else>已备份</div>
        </div>
      </div>
    </van-list>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {formatTime} from '@/utils/index'

export default {
  name: "cancellationList",
  filters: {
    timeFilter(timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    },
    statusFilter(status) {
      switch (status) {
        case 0:
          return 'wait'
        case 1:
          return 'passed'
      }
    }
  },
  data() {
    return {
      fetchDataFinish: false,
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      keyword: '',
      page: 1,
      pagesize: 15,
      options_status: ['待处理', '已处理'],
      utype: 1
    }
  },
  created() {
    this.fetchData(true)
  },
  methods: {
    buildCondition() {
      let conditions = {}
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      conditions.utype = this.utype
      return conditions
    },
    fetchData(init) {
      this.show_empty = false

      if (init === true) {
        this.page = 1
        this.finished_text = ''
        this.finished = false
      }
      let conditions = this.buildCondition()
      http
          .get(api.memberCancelApply, conditions)
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
          .catch(() => {
          })
    },
    onLoad() {
      this.page++
      this.fetchData(false)
    },
    funDel(row) {
      this.$dialog
          .confirm({
            title: '系统提示',
            message: '确定删除吗?'
          })
          .then(() => {
            http
                .post(api.memberCancelApplyDelete, {id: row.id})
                .then(res => {
                  this.$toast.success(res.message)
                  this.fetchData(true)
                })
                .catch(() => {
                })
          })
          .catch(() => {
            // on cancel
          })
    },
    funHandle(row) {
      if (row.is_backups === 0) {
        this.$dialog
            .confirm({
              title: '系统提示',
              message: '您在注销之前，可备份保留会员信息，是否备份【' + row.companyname + '】的会员信息？',
              cancelButtonText: '进行处理',
              confirmButtonText: '备份'
            })
            .then(() => {
              http
                  .post(api.memberCancelApplyBackups, {id: row.id})
                  .then(res => {
                    this.$toast.success(res.message)
                    this.fetchData(true)
                  })
                  .catch(() => {
                  })
            })
            .catch(() => {
              this.$dialog
                  .confirm({
                    title: '系统提示',
                    message: '注销将删除该企业的所有数据，确定注销吗?'
                  })
                  .then(() => {
                    http
                        .post(api.memberCancelApplyHandle, {id: row.id})
                        .then(res => {
                          this.$toast.success(res.message)
                          this.fetchData(true)
                        })
                        .catch(() => {
                        })
                  })
                  .catch(() => {
                    // on cancel
                  })
            })
      } else {
        this.$dialog
            .confirm({
              title: '系统提示',
              message: '注销将删除该企业的所有数据，确定注销吗?'
            })
            .then(() => {
              http
                  .post(api.memberCancelApplyHandle, {id: row.id})
                  .then(res => {
                    this.$toast.success(res.message)
                    this.fetchData(true)
                  })
                  .catch(() => {
                  })
            })
            .catch(() => {
              // on cancel
            })
      }
    },
    backups(row) {
      this.$dialog
          .confirm({
            title: '系统提示',
            message: '确定备份吗?'
          })
          .then(() => {
            http
                .post(api.memberCancelApplyBackups, {id: row.id})
                .then(res => {
                  this.$toast.success(res.message)
                  this.fetchData(true)
                })
                .catch(() => {
                })
          })
          .catch(() => {
            // on cancel
          })
    },
  }
}
</script>

<style lang="scss" scoped>
.loading {
  text-align: center;
  top: 50%;
}

.van-overlay {
  background-color: rgba(255, 255, 255, 0.9)
}

.b1 {
  .b_item {
    width: 100%;
    background-color: #ffffff;
    margin-top: 10px;
    position: relative;
    padding: 30px 30px 130px 30px;

    &:nth-of-type(1) {
      margin-top: 0;
    }

    .arrow {
      position: absolute;
      right: 20px;
      top: 30px;
      font-size: 42px;
    }

    .t1 {
      font-size: 30px;
      font-weight: bold;
      color: #333333;
      max-width: 510px;
      margin-bottom: 30px;
    }

    .t_line {
      display: flex;
      margin-bottom: 18px;

      &.last {
        margin-bottom: 0;
      }

      .tl_cell {
        flex: 1;
        font-size: 24px;

        .l_title {
          color: #999999;
        }

        .l_con {
          color: #333333;
        }
      }
    }

    .i_btn {
      position: absolute;
      bottom: 43px;
      right: 30px;
      font-size: 24px;
      color: #333333;
      padding: 8px 34px;
      border: 1PX solid #cccccc;
      border-radius: 999px;

      &.b1 {
        right: 30px;
      }

      &.b2 {
        right: 170px;
      }

      &.b3 {
        right: 310px;
      }

      &.b3-1 {
        right: 310px;
        background: #999999;
      }

      &.blue {
        color: #248efb;
        border-color: #248efb;
      }

      &.gray {
        color: #999999;
        border-color: #ececec;
      }
    }

    .i_tag {
      position: absolute;
      right: 62px;
      top: 33px;
      width: 104px;
      text-align: center;
      padding: 6px 0;
      font-size: 20px;
      border-radius: 999px;

      &.wait {
        background-color: #fdf6ec;
        color: #ff8e50;
      }

      &.passed {
        background-color: #f0f9eb;
        color: #67c23a;
      }

      &.failed {
        background-color: #fef0f0;
        color: #f56c6c;
      }

      &.not {
        background-color: #f4f4f5;
        color: #909399;
      }
    }
  }
}
</style>
