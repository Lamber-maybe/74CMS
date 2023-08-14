<template>
  <div id="app">
    <Head>我的下载</Head>
    <ScrollNav :list="navList"></ScrollNav>
    <div class="form_split_10"></div>
    <van-empty
      image="search"
      description="没有找到对应的信息"
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
      <div class="list_wrapper">
        <div
          v-for="(item, index) in dataset"
          :key="index"
          @click="$router.push('/resume/' + item.resume_id)"
        >
          <div class="list">
            <div class="tx1">
              <div class="avatar_box">
                <img :src="item.photo_img_src" alt="照片" />
              </div>
              <div class="name">
                <div class="name_txt">{{ item.fullname }}</div>
                <div class="level" v-if="item.high_quality == 1"></div>
                <div class="clear"></div>
                <div class="time">{{ item.addtime | timeFilter }}</div>
              </div>
              <div class="some">
                {{ item.age }}岁 · {{ item.sex_text }} ·
                {{ item.education_text }} · {{ item.experience_text }}
              </div>
            </div>
            <div class="tx2">
              想找
              <span class="text">{{ item.intention_jobs }}</span>
              工作
            </div>
            <div class="tx2">
              想在
              <span class="text">{{ item.intention_district }}</span>
              工作
            </div>
            <div class="tx3" v-if="item.recent_work != ''">
              最近职位：{{ item.recent_work }}
            </div>
            <div class="tx3" v-else>
              {{ item.current_text }}
            </div>
            <div class="tx4">
              <div class="list_btn" @click.stop="openAddInvitation(item)">
                面试邀请
              </div>
              <div class="list_btn" @click.stop="handlerRemark(item)">备注</div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <van-popup
      :lazy-render="false"
      v-model="showInvite"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <AddInvitation
        ref="child"
        from="download"
        :apply_fullname="apply_fullname"
        :resume_id="apply_resume_id"
        @closePopup="closeAddInvitation"
      ></AddInvitation>
    </van-popup>
    <van-popup
      v-model="showRemark"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <Head :goback_custom="true" @gobackCustomMethod="showRemark = false">
        备注
      </Head>
      <van-form @submit="submitRemark">
        <van-field
          type="textarea"
          rows="2"
          name="remark"
          v-model="remark_item.remark"
          label=""
          placeholder="请输入备注内容"
        />
        <div style="margin: 16px;">
          <van-button round block type="info" native-type="submit">
            确定
          </van-button>
        </div>
      </van-form>
    </van-popup>
  </div>
</template>

<script>
import { formatTime } from '@/utils/index'
import AddInvitation from '@/components/AddInvitation'
import http from '@/utils/http'
import api from '@/api'
import ScrollNav from '@/components/ScrollNav'
export default {
  name: 'ResumeDownload',
  components: {
    AddInvitation,
    ScrollNav
  },
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    }
  },
  data () {
    return {
      apply_resume_id: 0,
      apply_fullname: '',
      showInvite: false,
      showRemark: false,
      remark_item: {},
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      params: {
        page: 1,
        pagesize: 15
      },
      navList: [
        { text: '收到投递', href: '/member/company/jobapply', active: false },
        { text: '我的下载', href: '/member/company/download', active: true },
        { text: '面试邀请', href: '/member/company/interview', active: false },
        {
          text: '视频面试',
          href: '/member/company/interview_video',
          active: false
        },
        { text: '我的收藏', href: '/member/company/fav', active: false },
        {
          text: '浏览记录',
          href: '/member/company/view_resume',
          active: false
        }
      ]
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    fetchData (init) {
      this.show_empty = false
      if (init === true) {
        this.params.page = 1
        this.finished = false
        this.finished_text = ''
      }
      http
        .get(api.company_downresume_list, this.params)
        .then((res) => {
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
            } else if (res.data.items.length === 0) {
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
    openAddInvitation (item) {
      if (item.audit != 1) {
        this.$notify('该简历尚未通过审核，不能继续此操作')
        return false
      }
      this.apply_fullname = item.fullname
      this.apply_resume_id = item.resume_id
      this.$refs.child.initCB()
      this.showInvite = true
    },
    closeAddInvitation () {
      this.showInvite = false
    },
    handlerRemark (item) {
      this.showRemark = true
      this.remark_item = item
    },
    submitRemark (values) {
      values.resume_id = this.remark_item.resume_id
      http
        .post(api.remark_resume, values)
        .then((res) => {
          this.dataset.forEach(element => {
            if (element.resume_id == this.remark_item.resume_id) {
              element.remark = values.remark
            }
          })
          this.showRemark = false
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.list_wrapper {
  .list {
    .tx4 {
      .list_btn {
        float: right;
        font-size: 12px;
        padding: 4px 18px;
        background-color: #fff;
        border: 1px solid #e2e2e2;
        margin-left: 12px;
        border-radius: 26px;
        &.orange {
          border-color: #ff5d24;
          color: #ff5d24;
        }
      }
      position: relative;
      width: 100%;
      border-top: 1px dashed #eeeeee;
      padding: 12px 0;
      text-align: right;
    }
    .tx3 {
      .link {
        color: #1787fb;
      }
      font-size: 13px;
      color: #999999;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding-bottom: 16px;
    }
    .tx2 {
      .text {
        color: #666666;
      }
      font-size: 13px;
      color: #999999;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      margin-bottom: 8px;
    }
    .tx1 {
      .new {
        position: absolute;
        right: -15px;
        top: 0;
        width: 24px;
        height: 24px;
        background: url('../../../assets/images/arrow_new_ico.svg') 0 no-repeat;
        background-size: 24px;
      }
      .avatar_box {
        img {
          width: 49px;
          height: 49px;
          border: 0;
          border-radius: 100%;
        }
        position: absolute;
        left: 0;
        top: 18px;
        width: 49px;
        height: 49px;
      }
      .some {
        font-size: 15px;
        color: #666666;
        padding: 0 0 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 11px;
      }
      .name {
        margin-bottom: 7px;
        .time {
          position: absolute;
          right: 0;
          top: 24px;
          font-size: 12px;
          color: #999999;
        }
        .level {
          float: left;
          width: 35px;
          height: 25px;
          background: url('../../../assets/images/level_hight_ico.png') 0 6px
            no-repeat;
          background-size: 35px 14px;
        }
        .name_txt {
          float: left;
          font-size: 18px;
          font-weight: bold;
          color: #333333;
          padding-right: 8px;
          max-width: 175px;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
        }
        padding-top: 21px;
        position: relative;
      }
      position: relative;
      padding-left: 63px;
    }
    width: 100%;
    background-color: #ffffff;
    padding: 0 15px;
  }
  width: 100%;
}
</style>
