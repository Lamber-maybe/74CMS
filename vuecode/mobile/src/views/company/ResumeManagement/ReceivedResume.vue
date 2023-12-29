<template>
  <div id="app">
    <Head>收到的简历</Head>
    <ScrollNav :list="navList"></ScrollNav>
    <div class="box_1">
      <van-dropdown-menu>
        <van-dropdown-item
          v-model="params.jobid"
          :options="options_job"
          @change="doSearch"
        />
        <van-dropdown-item
          v-model="params.status"
          :options="options_status"
          @change="doSearch"
        />
        <van-dropdown-item
          v-model="params.is_look"
          :options="options_look"
          @change="doSearch"
        />
        <van-dropdown-item
          v-model="params.source"
          :options="options_source"
          @change="doSearch"
        />
      </van-dropdown-menu>
    </div>
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
          @click="handlerLook(item)"
        >
          <div class="list">
            <div class="tx1">
              <!--<div class="new" v-if="item.is_look == 0"></div>-->
              <div :class="[parseInt(item.is_look) === 0 ? 'avatar_box no_look' : 'avatar_box']">
                <img :src="item.photo_img_src" alt="照片"/>
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
                <van-tag type="warning" v-if="item.handle_status == 0">
                  待处理
                </van-tag>
                <van-tag type="primary" v-if="item.handle_status == 1">
                  已同意
                </van-tag>
                <van-tag type="danger" v-if="item.handle_status == 2">
                  已婉拒
                </van-tag>
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
            <div class="tx3">
              应聘职位：
              <span class="link">{{ item.jobname }}</span>
            </div>
<!--            <div class="tx3">-->
<!--              联系方式：-->
<!--              <span>{{ item.resume_contact | contactFilter }}</span>-->
<!--            </div>-->
            <div class="tx4">
              <div class="list_btn" @click.stop="handlerDel(item, index)">
                删除
              </div>
              <div class="list_btn" @click.stop="handlerRemark(item)">备注</div>
              <div
                class="list_btn"
                v-if="item.handle_status == 0"
                @click.stop="handlerRefuse(item)"
              >
                婉拒
              </div>
              <div
                class="list_btn"
                v-if="item.handle_status == 0"
                @click.stop="handlerAgree(item, index)"
              >
                同意
              </div>
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
        from="apply"
        :apply_id="apply_id"
        :apply_dataset_index="apply_dataset_index"
        :apply_fullname="apply_fullname"
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
          maxlength="50"
          rows="3"
          show-word-limit
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

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
  </div>
</template>

<script>
import {formatTime} from '@/utils/index'
import AddInvitation from '@/components/AddInvitation'
import http from '@/utils/http'
import api from '@/api'
import ScrollNav from '@/components/ScrollNav'
import WeChatQrcode from '@/components/WeChatQrcode'
export default {
  name: 'ReceivedResume',
  components: {
    AddInvitation,
    ScrollNav,
    WeChatQrcode
  },
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
    contactFilter (contact) {
      return contact == 0 ? '未获取联系方式' : contact
    }
  },
  data () {
    return {
      jobid: 0,
      showRemark: false,
      remark_item: {},
      apply_id: 0,
      apply_dataset_index: 0,
      apply_fullname: '',
      showInvite: false,
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      params: {
        jobid: 0,
        status: '',
        source: '',
        is_look: '',
        page: 1,
        pagesize: 15
      },
      options_job: [{text: '全部职位', value: 0}],
      options_status: [
        {text: '处理状态', value: ''},
        {text: '待处理', value: 0},
        {text: '已同意', value: 1},
        {text: '已拒绝', value: 2}
      ],
      options_source: [
        {text: '全部来源', value: ''},
        {text: '自主投递', value: 0},
        {text: '委托投递', value: 1}
      ],
      options_look: [
        {text: '查看状态', value: ''},
        {text: '未查看', value: 0},
        {text: '已查看', value: 1}
      ],
      navList: [
        {text: '收到投递', href: '/member/company/jobapply', active: true},
        {text: '我的下载', href: '/member/company/download', active: false},
        {text: '面试邀请', href: '/member/company/interview', active: false},
        {
          text: '视频面试',
          href: '/member/company/interview_video',
          active: false
        },
        {text: '我的收藏', href: '/member/company/fav', active: false},
        {text: '看过我', href: '/member/company/be_browsed', active: false},
        {text: '我看过', href: '/member/company/view_resume', active: false}
      ],
      companySetmeal: {}
    }
  },
  created () {
    let params = new URLSearchParams(window.location.search)
    let job_id = parseInt(params.get('job_id'))
    let options_look = parseInt(params.get('options_look'))
    if (job_id) {
      this.jobid = job_id
      this.params.jobid = job_id
      this.params.is_look = options_look
    }
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
      this.fetchSetmeal()
      http
        .get(api.company_jobapply_list, this.params)
        .then((res) => {
          if (init === true) {
            this.options_job = [{text: '全部职位', value: 0}]
            let options_job = [...res.data.option_jobs]
            options_job.forEach((element) => {
              this.options_job.push({
                text: element.jobname,
                value: element.id
              })
            })
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
        .catch(() => {
        })
    },
    onLoad () {
      this.params.page++
      this.fetchData(false)
    },
    doSearch () {
      this.params.page = 1
      this.fetchData(true)
    },
    handlerAgree (item, index) {
      this.jobid = item.jobid
      if (item.audit != 1) {
        this.$notify('该简历尚未通过审核，不能继续此操作')
        return false
      }
      http
        .post(api.company_jobapply_set_agree_pre, {id: item.id})
        .then((res) => {
          if (res.data.finish === 1) {
            item.handle_status = 1
            item.is_look = 1
            this.$notify({
              type: 'success',
              message: '简历处理成功'
            })
          } else {
            this.openAddInvitation(item, index)
          }
        })
        .catch(() => {
        })
    },
    openAddInvitation (item, index) {
      this.apply_id = item.id
      this.apply_dataset_index = index
      this.apply_fullname = item.fullname
      this.$refs.child.initCB(this.jobid)
      this.showInvite = true
    },
    closeAddInvitation (index) {
      if (index !== undefined) {
        this.dataset[index].handle_status = 1
        this.dataset[index].is_look = 1
      }
      this.showInvite = false
      /**
       * 【ID1000719】
       * 【新增】公众号引导弹窗场景（面试邀请）
       * cy 2023-7-19
       */
      this.popupWechatQrcodeWindow('company_m_interview_invite', 3)
    },
    handlerRefuse (item) {
      if (item.audit != 1) {
        this.$notify('该简历尚未通过审核，不能继续此操作')
        return false
      }
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定将处理状态更改为【已婉拒】吗？'
        })
        .then(() => {
          http
            .post(api.company_jobapply_set_refuse, {id: item.id})
            .then((res) => {
              this.$notify({
                type: 'success',
                message: '简历处理成功'
              })
              item.handle_status = 2
            })
            .catch(() => {
            })
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerDel (item, index) {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定删除该简历吗？'
        })
        .then(() => {
          http
            .post(api.company_jobapply_del, {id: item.id})
            .then((res) => {
              this.$notify({
                type: 'success',
                message: '简历删除成功'
              })
              this.dataset.splice(index, 1)
            })
            .catch(() => {
            })
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerLook (item) {
      if (item.is_look === 0 || item.resume_contact === 0) {
        item.is_look = 1
        http
          .post(api.company_jobapply_set_looked, {id: item.id})
          .then((res) => {
            this.fetchData(true)
            this.$router.push('/resume/' + item.resume_id)
          })
          .catch(() => {
          })
        // if (item.resume_contact) {
        //   item.is_look = 1
        //   http
        //     .post(api.company_jobapply_set_looked, {id: item.id})
        //     .then((res) => {
        //       this.fetchData(true)
        //       this.$router.push('/resume/' + item.resume_id)
        //     })
        //     .catch(() => {
        //     })
        //   return false
        // }
        // if (this.companySetmeal.resume_view_num_today == -1) {
        //   item.is_look = 1
        //   http
        //     .post(api.company_jobapply_set_looked, {id: item.id})
        //     .then((res) => {
        //       this.fetchData(true)
        //       this.$router.push('/resume/' + item.resume_id)
        //     })
        //     .catch(() => {
        //     })
        // } else if (this.companySetmeal.resume_view_num_today > 0) {
        //   this.$dialog
        //     .confirm({
        //       title: '系统提示',
        //       message: '您今天还可免费查看 ' + this.companySetmeal.resume_view_num_today + ' 次收到简历的联系方式，是否立即查看?',
        //       confirmButtonText: '立即查看'
        //     })
        //     .then(() => {
        //       item.is_look = 1
        //       http
        //         .post(api.company_jobapply_set_looked, {id: item.id})
        //         .then((res) => {
        //           this.fetchData(true)
        //           this.$router.push('/resume/' + item.resume_id)
        //         })
        //         .catch(() => {
        //         })
        //     })
        //     .catch(() => {
        //       // on cancel
        //     })
        // } else {
        //   this.$dialog
        //     .confirm({
        //       title: '系统提示',
        //       message: '您今天暂无可用免费查看次数，如需获取联系方式请下载简历后查看。',
        //       cancelButtonText: '取消',
        //       confirmButtonText: '继续查看'
        //     })
        //     .then(() => {
        //       item.is_look = 1
        //       http
        //         .post(api.company_jobapply_set_looked, {id: item.id})
        //         .then((res) => {
        //           this.fetchData(true)
        //           this.$router.push('/resume/' + item.resume_id)
        //         })
        //         .catch(() => {
        //         })
        //     })
        //     .catch(() => {
        //     })
        // }
      } else {
        this.$router.push('/resume/' + item.resume_id)
      }
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
          if (res.code == 200) {
            this.$notify({type: 'success', message: res.message})
            this.dataset.forEach(element => {
              if (element.resume_id == this.remark_item.resume_id) {
                element.remark = values.remark
              }
            })
          } else {
            this.$notify({type: 'error', message: res.message})
          }
          this.showRemark = false
        })
        .catch(() => {
        })
    },
    fetchSetmeal () {
      http
        .get(api.member_setmeal, {})
        .then(res => {
          this.companySetmeal = res.data.info
        })
        .catch(() => {
        })
    },
    // 弹出微信二维码弹框
    popupWechatQrcodeWindow (val, type) {
      this.$refs.weChatQrcodeRef.handleOpen(val, type)
    }
  },
  beforeRouteLeave (to, from, next) {
    /**
     * 【ID1000702】
     * 【bug】触屏端从记录列表页进入详情再返回后直接回到顶部
     * cy 2023-7-6
     */
    // 从列表页如果不是去详情页，则不缓存列表页
    this.$route.meta.keepAlive = to.name == 'resumeShow'
    next()
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
      padding-bottom: 8px;
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
        width: 30px;
        height: 30px;
        background: url('../../../assets/images/arrow_new_ico.svg') 0 no-repeat;
        background-size: 30px;
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

        &.no_look {
          &::after {
            content: '';
            position: absolute;
            right: 1px;
            top: 2px;
            width: 9px;
            height: 9px;
            background-color: #ff5200;
            border-radius: 999px;
            border: 1PX solid #ffffff;
          }
        }
      }

      .some {
        .van-tag {
          position: absolute;
          right: 0;
          top: 1.5px;
        }

        position: relative;
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
          background: url('../../../assets/images/level_hight_ico.png') 0 6px no-repeat;
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
