<template>
  <div class="clearfix">
    <div class="datetime">{{ item.datetime }}</div>
    <div class="out">
      <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
        <img class="image" :src="item.avatar">
      </div>
      <div class="invite" :class="item.self_side == 1 ? 'mine' : 'other'">
        <div class="inner" :class="item.self_side == 1 ? 'mine-name' : 'other-name'">
          <span class="type sliceText substring" :class="item.self_side == 1 ? 'mine' : 'other'">{{ item.name }}</span>
          <span class="type words" :class="item.self_side == 1 ? 'mine' : 'other'">
            <a v-if="item.is_delete == 1" class="gray" href="javascript:;">撤回</a>
            <a v-if="item.is_delete == 0" class="back" href="javascript:;" @click="backmsg(item)">撤回</a>|
            <a v-if="item.disable_im == 1" href="javascript:;" class="gray">禁聊</a>
            <a v-if="item.disable_im == 0" href="javascript:;" class="forbid" @click="forbidmsg(item.uid)">禁聊</a>
          </span>
        </div>
        <div class="title">{{ item.self_side == 1 ? '邀请对方投递简历' : '邀请您投递简历' }}</div>
        <div class="content">
          <div class="jobname">{{ item.message.jobname }}</div>
          <div class="info">{{ item.message.education_text }} · {{ item.message.experience_text }} ·
            {{ item.message.district_text }}
          </div>
          <div v-if="item.self_side == 1" class="bottom">
            <div v-if="item.message.status==0" class="btn">等待对方投递</div>
            <div v-else class="btn done" @click="toDetail">对方已投递 立即查看</div>
          </div>
          <div v-else class="bottom">
            <div v-if="item.message.status==0" class="btn done">投递简历</div>
            <div v-else class="btn">已投递</div>
          </div>
          <div class="wage">{{ item.message.wage_text }}</div>
          <div class="clear"/>
        </div>
        <div
          v-if="item.is_delete == 1"
          class="status"
          :class="item.self_side==1?'mine':'other'"
        >
          该消息已撤回
        </div>
      </div>
    </div>
    <!-- <div class="clear" /> -->
  </div>
</template>

<script>
import {messageBack} from '@/api/im'
import {memberIm} from '@/api/member'

export default {
  name: 'ItemInvite',
  props: ['item'],
  data() {
    return {}
  },
  created() {

  },
  mounted() {

  },
  beforeDestroy() {
  },
  methods: {
    backmsg(item) {
      const tips = '确定撤回该消息吗？'
      this
        .$confirm(tips, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            messageid: item.messageid
          }
          messageBack(param).then(response => {
            this.$message.success(response.message)
            item.is_delete = 1
            return true
          })
        })
        .catch(() => {
        })
    },
    forbidmsg(uid) {
      const tips = '确定禁聊该用户吗？'
      const disable_im = 1
      this
        .$confirm(tips, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: uid,
            disable_im
          }
          memberIm(param).then(response => {
            this.$message.success(response.message)
            return true
          })
        })
        .catch(() => {
        })
    },
    toDetail() {
      let url = this.$store.state.config.link_url_web.resumeshow
      url = url.replace('_id_', this.item.message.resumeid)
      window.open(url)
    }
  }
}
</script>

<style lang="scss" scoped>
.status {
  color: #c9c9c9;
  font-size: 12px;
  position: absolute;
  top: 189px;
  width: 74px;

  &.mine {
    right: 0;
  }

  &.other {
    left: 0;
  }
}

.datetime {
  text-align: center;
  font-size: 12px;
  color: #999999;
  margin-bottom: 18px;
}

.photo {
  &.mine {
    float: right;
  }

  &.other {
    float: left;
  }

  .image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
}

.out {
  border-top: 1px solid transparent
}

.invite {
  background-color: #fff;
  margin: 0 14px;
  width: 300px;
  height: 190px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 28px;
  position: relative;
  margin-top: 20px;

  &:hover .words {
    display: inline-block;

    a {
      margin: 0 4px;
      font-weight: 500;
    }

    .back {
      color: #f79317;
    }

    .gray {
      color: gray;
    }

    .forbid {
      color: #02bf57;
    }
  }

  .inner {
    font-size: 14px;
    position: absolute;
    top: -30px;
    z-index: 1000000;
    color: #999999;
    min-width: 325px;

    &.other-name {
      left: 0;
      right: auto;
    }

    &.mine-name {
      right: 0;
      left: auto;
    }

    &:hover .words {
      display: inline-block;

      a {
        margin: 0 4px;
        font-weight: 500;
      }

      .back {
        color: #f79317;
      }

      .gray {
        color: gray;
      }

      .forbid {
        color: #02bf57;
      }
    }
  }

  .words {
    display: none;

    a {
      margin: 0 4px;
      font-weight: 500;
    }

    .back {
      color: #f79317;
    }

    .forbid {
      color: #02bf57;
    }
  }

  .type {
    &.mine {
      float: right;
      margin-left: 15px;
    }

    &.other {
      float: left;
      margin-right: 15px;
    }

    &.sliceText {
      display: inline-block;
      max-width: 200px;
    }
  }

  &.mine {
    float: right;
  }

  &.other {
    float: left;
  }

  .title {
    height: 44px;
    line-height: 44px;
    background-color: #F8F8F8;
    font-size: 16px;
    color: #333;
    padding-left: 14px;
  }

  .content {
    padding: 14px 16px;
    position: relative;

    .jobname {
      font-size: 14px;
      color: #111111;
      font-weight: bold;
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .info {
      font-size: 14px;
      color: #666666;
    }

    .companyname {
      font-size: 12px;
      color: #999999;
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .addtime {
      border-top: 1px solid #ededed;
      font-size: 13px;
      color: #999999;
      margin-top: 4px;
    }

    .wage {
      position: absolute;
      top: 14px;
      right: 12px;
      color: #ff4f2c;
      font-size: 14px;
      font-weight: bold;
    }

    .btn {
      margin: 16px auto;
      width: 260px;
      height: 35px;
      line-height: 35px;
      background: #F8F8F8;
      border-radius: 18px;
      font-size: 14px;
      color: #7A7A7A;
      text-align: center;
      cursor: pointer;

      &.done {
        background-color: #1787FB;
        color: #fff;

        &:hover {
          opacity: 0.8;
        }
      }
    }
  }

}
</style>
