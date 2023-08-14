<template>
  <div id="app">
    <Head>隐私设置</Head>
    <div class="box_1">
      <div class="tx1">简历公开状态</div>
      <div class="tx2">
        简历公开
        <van-switch
          class="self_switch"
          :value="display"
          @input="onInput"
          size="18px"
          active-color="#45db5e"
        />
      </div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_2">
      <div class="tx1">简历姓名显示</div>
      <van-radio-group v-model="displayName">
        <div class="tx2" @click="handleLine('1')">
          实名显示
          <span>(如：王昌明)</span>
          <van-radio
            class="self_radio"
            name="1"
            checked-color="#2199ff"
            @click="handleDisplayName(displayName)"
          ></van-radio>
        </div>
        <div class="tx2" @click="handleLine('0')">
          匿名显示
          <span>(如：王先生/女士)</span>
          <van-radio
            class="self_radio"
            name="0"
            checked-color="#2199ff"
            @click="handleDisplayName(displayName)"
          ></van-radio>
        </div>
      </van-radio-group>
    </div>
    <div class="form_split_10"></div>
    <div class="box_3">
      <div class="tx1">屏蔽企业</div>
      <div class="tx2" v-for="(item, index) in blackList" :key="index">
        {{ item.companyname }}
        <div class="del" @click="handleDelete(item.id)"></div>
      </div>
    </div>
    <div class="box_4">
      <van-button type="info" block round to="/member/personal/blacklist_add">
        添加企业
      </van-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'PrivacySetting',
  data () {
    return {
      radio: 1,
      display: true,
      displayName: '1',
      blackList: []
    }
  },
  created () {
    // 获取隐私设置信息
    http
      .get(api.resume_privates_info)
      .then(res => {
        if (parseInt(res.code) === 200) {
          this.initData(res.data)
        } else {
          this.$notify(res.message)
        }
      })
      .catch(err => {
        console.log(err)
      })
  },
  computed: {
    basic () {
      return this.$store.state.resume.basic
    }
  },
  methods: {
    handleLine (value) {
      this.displayName = value
    },
    // 初始化数据
    initData (interfacePrivacy) {
      this.display = parseInt(interfacePrivacy.is_display) === 1
      this.displayName = `${interfacePrivacy.display_name}`
      this.blackList = interfacePrivacy.blacklist
    },
    // 简历状态切换
    onInput (checked) {
      let displayValue = 1
      if (!checked) {
        // 关闭时弹窗确认
        displayValue = 0
        this.$dialog
          .confirm({
            title: '提示',
            message:
              '简历设置为保密后，任何企业都将无法搜索到您的简历，是否确认设置保密？'
          })
          .then(() => {
            // 确定按钮
            this.switchTypeFun(displayValue)
            this.display = checked
          })
          .catch(() => {
            // 取消按钮
            this.display = !checked
          })
      } else {
        this.switchTypeFun(displayValue)
        this.display = checked
        this.updateStoreBasic(displayValue)
      }
    },
    // 简历状态切换提交接口
    switchTypeFun (value) {
      http
        .post(api.resume_privates_set_display, {
          display: value
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.updateStoreBasic(value)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 更新store基本资料
    updateStoreBasic (value) {
      let currentBasic = this.$store.state.resume.basic
      currentBasic.is_display = value
      // 更新基本资料
      this.$store.dispatch('setBasicInfo', currentBasic)
    },
    // 简历姓名显示切换
    handleDisplayName (value) {
      http
        .post(api.resume_privates_set_display_name, {
          display: parseInt(value)
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            let currentBasic = this.$store.state.resume.basic
            currentBasic.display_name = parseInt(value)
            // 更新基本资料
            this.$store.dispatch('setBasicInfo', currentBasic)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 删除屏蔽企业
    handleDelete (id) {
      http
        .post(api.resume_privates_deleteBlacklist, {
          id: id
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.blackList.splice(
              this.blackList.findIndex(
                item => parseInt(item.id) === parseInt(id)
              ),
              1
            )
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_4 {
  width: 300px;
  margin: 0 auto;
}
.box_3 {
  width: 100%;
  background-color: #ffffff;
  padding: 0 15px 35px 15px;
  .tx2 {
    position: relative;
    width: 100%;
    border-bottom: 1px solid #f8f8f8;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    word-break: break-all;
    font-size: 14px;
    color: #333333;
    padding: 12.5px 3px;
    .del {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translate(0, -50%);
      width: 30px;
      height: 100%;
      background: url("../../assets/images/round_close.svg") center no-repeat;
      background-size: 18px;
    }
  }
  .tx1 {
    padding: 27.5px 0 12.5px;
    font-size: 18px;
    color: #333333;
    font-weight: bold;
  }
}
.box_2 {
  width: 100%;
  background-color: #ffffff;
  padding: 0 0 12px 15px;
  .tx1 {
    padding: 27.5px 0 12.5px;
    font-size: 18px;
    color: #333333;
    font-weight: bold;
  }
  .tx2 {
    padding: 9.5px 0;
    font-size: 14px;
    color: #333333;
    position: relative;
    span {
      color: #999999;
    }
    .self_radio {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
    }
  }
}
.box_1 {
  width: 100%;
  background-color: #ffffff;
  padding: 0 0 12px 15px;
  .tx1 {
    padding: 27.5px 0 12.5px;
    font-size: 18px;
    color: #333333;
    font-weight: bold;
  }
  .tx2 {
    padding: 9.5px 0;
    font-size: 14px;
    color: #333333;
    position: relative;
    .self_switch {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
    }
  }
}
</style>
