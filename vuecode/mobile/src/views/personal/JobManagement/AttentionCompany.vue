<template>
  <div id="app">
    <Head>关注的企业</Head>
    <PersonalEquallyNav :nav="navList"></PersonalEquallyNav>
    <div class="form_split_10"></div>
    <van-empty
      image="search"
      description="没有找到对应的信息"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <van-list
      v-if="itemList.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="box_1">
        <div
          v-for="(item, index) in itemList"
          :key="index"
          @click="toDetail(item.company_id)"
        >
          <div class="box_item">
            <div class="txt_wrapper">
              <div class="logo_box">
                <img :src="item.logo_src" :alt="item.companyname" />
              </div>
              <div class="tx1">
                <div class="name">{{ item.companyname }}</div>
                <div
                  class="auth_ico"
                  v-if="parseInt(item.company_audit) === 1"
                ></div>
                <div class="clear"></div>
              </div>
              <div class="tx2">{{ item.district_text }}</div>
              <div class="tx4">
                {{ item.scale_text }} · {{ item.trade_text }} ·
                {{ item.nature_text }}
              </div>
            </div>
            <div class="tx3">
              热招：
              <span class="job">{{ item.first_jobname }}</span>
              等{{ item.jobnum }}个岗位
              <van-button
                class="right_btn"
                round
                size="small"
                plain
                color="#1787fb"
                @click.stop="cancelAttention(item.id)"
              >
                取消关注
              </van-button>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import PersonalEquallyNav from '@/components/PersonalEquallyNav'

export default {
  name: 'AttentionCompany',
  components: {
    PersonalEquallyNav
  },
  data () {
    return {
      page: 1,
      pagesize: 15,
      itemList: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      navList: [
        { text: '职位收藏夹', href: '/member/personal/favjob', active: 0 },
        {
          text: '关注的企业',
          href: '/member/personal/attention_company',
          active: 1
        }
      ]
    }
  },
  created () {
    this.getData(true)
  },
  methods: {
    /**
     * 获取数据
     */
    getData (init) {
      this.show_empty = false
      if (init === true) {
        this.page = 1
        this.finished = false
        this.finished_text = ''
      }
      http
        .get(api.personal_manage_attention_company, {
          page: this.page,
          pagesize: this.pagesize
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            if (init === true) {
              this.itemList = [...res.data.items]
            } else {
              this.itemList = this.itemList.concat(res.data.items)
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
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    /**
     * List初始化
     */
    onLoad () {
      this.page++
      this.getData(false)
    },
    /**
     * 跳转详情页
     * @param id 企业id
     */
    toDetail (id) {
      this.$router.push('/company/' + id)
    },
    /**
     * 取消关注企业
     * @param id
     */
    cancelAttention (id) {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定取消关注该企业吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.personal_manage_attention_company_cancel, {
              id: id
            })
            .then(res => {
              if (parseInt(res.code) === 200) {
                this.$notify({ type: 'success', message: res.message })
                this.itemList = this.itemList.filter(
                  item => parseInt(item.id) !== parseInt(id)
                )
              } else {
                this.$notify(res.message)
              }
            })
            .catch(err => {
              console.log(err)
            })
        })
        .catch(() => {
          // 取消按钮
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  width: 100%;
  .box_item {
    width: 100%;
    background-color: #ffffff;
    padding: 17px 16px 0;
    .txt_wrapper {
      position: relative;
      padding-left: 74px;
      .logo_box {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translate(0, -50%);
        width: 60px;
        height: 60px;
        font-size: 10px;
        img {
          width: 60px;
          height: 60px;
          border: 0;
        }
      }
    }
    .tx4 {
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      max-width: 260px;
      margin-bottom: 11px;
    }
    .tx3 {
      position: relative;
      width: 100%;
      border-top: 1px solid #fafafa;
      padding: 15px 85px 15px 0;
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      .job {
        color: #1787fb;
      }
      .right_btn {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        padding: 0 13px;
      }
    }
    .tx2 {
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      max-width: 260px;
      margin-bottom: 7px;
    }
    .tx1 {
      margin-bottom: 9px;
      position: relative;
      .name {
        font-size: 16px;
        color: #333333;
        font-weight: bold;
        float: left;
        max-width: 220px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .auth_ico {
        float: left;
        margin-left: 7px;
        width: 31px;
        height: 22px;
        background: url("../../../assets/images/tip_ico_auth.png") 0 4px
          no-repeat;
        background-size: 31px 16px;
      }
    }
  }
}
</style>
