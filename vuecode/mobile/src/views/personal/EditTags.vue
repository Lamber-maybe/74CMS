<template>
  <div id="app">
    <Head>特长标签</Head>
    <div class="form_top_border"></div>
    <div class="box_1">您最多可以添加10个特长标签，还可以添加自定义标签哦</div>
    <div class="box_2">
      <div
        :class="item.select ? 'tag-item select' : 'tag-item'"
        v-for="(item, index) in loopTags"
        :key="index"
        @click="tagClick(item)"
      >
        {{ item.text }}
      </div>
    </div>
    <div class="clear"></div>
    <div class="box_3">
      <div class="add_self_tag" @click="handleCustom">自定义</div>
    </div>
    <div style="margin: 16px 16px 0;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
    <van-dialog
      v-model="showCustom"
      title="添加自定义标签"
      show-cancel-button
      @confirm="handleConfirm"
    >
      <div class="box_4">
        <div class="self_input">
          <label>
            <input
              type="text"
              v-model="customText"
              maxlength="5"
              placeholder="请输入标签内容(最多5个字)"
            />
          </label>
        </div>
      </div>
    </van-dialog>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditTags',
  data () {
    return {
      showCustom: false,
      selectedTags: [],
      loopTags: [],
      customText: ''
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'resumeTag')
  },
  mounted () {
    this.loopTags = this.tagList
    let interfaceTags = this.basicStore.tag
    // 重构数据
    this.loopTags = this.loopTags.map(function (item) {
      return { id: item.id, text: item.text, select: false }
    })
    // 恢复选中
    if (interfaceTags.length) {
      let _this = this
      this.loopTags = this.loopTags.map(function (item) {
        let thisIndex = interfaceTags.findIndex(val => parseInt(item.id) === parseInt(val))
        return { id: item.id, text: item.text, select: thisIndex > -1 }
      })
      // 恢复自定义标签
      interfaceTags.forEach(function (item) {
        // 自定义标签无id
        if (!parseInt(item)) {
          _this.loopTags.push({ id: '', text: item, select: true })
        }
      })
    }
    this.syncSelectedTag()
  },
  computed: {
    tagList () {
      return this.$store.state.classifyResumeTag
    },
    basicStore () {
      return this.$store.state.resume.basic
    }
  },
  methods: {
    // 同步已选中标签
    syncSelectedTag () {
      this.selectedTags = this.loopTags.filter(item => item.select)
    },
    // 添加自定义标签
    handleCustom () {
      // 判断选中特长标签的数量
      if (this.selectedTags.length >= 10) {
        this.$notify({ type: 'warning', message: '您最多可以选择10个特长标签' })
        return false
      } else {
        this.showCustom = !this.showCustom
      }
    },
    // 自定义标签确定
    handleConfirm () {
      this.loopTags.push({ id: '', text: this.customText, select: true })
      this.customText = ''
      this.syncSelectedTag()
    },
    // 选择标签
    tagClick (data) {
      // 判断选中特长标签的数量
      if (!data.select && this.selectedTags.length >= 10) {
        this.$notify({ type: 'warning', message: '您最多可以选择10个特长标签' })
        return false
      }
      this.loopTags = this.loopTags.map(function (item) {
        let isSelect = false
        if (data.id) {
          isSelect = parseInt(item.id) === parseInt(data.id) ? !item.select : item.select
        } else {
          isSelect = item.text === data.text ? !item.select : item.select
        }
        return { id: item.id, text: item.text, select: isSelect }
      })
      this.syncSelectedTag()
    },
    // 保存
    handleSubmit () {
      let submitTag = []
      this.selectedTags.forEach(function (item) {
        if (item.id) {
          submitTag.push(item.id)
        } else {
          submitTag.push(item.text)
        }
      })
      http
        .post(api.resume_tag_save, {
          tag: submitTag
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.$router.push({ path: '/member/personal/resume' })
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
  width: 290px;
  margin: 0 auto;
  padding: 20px 0 30px;
  .self_input {
    width: 100%;
    border-bottom: 1px solid #e2e2e2;
    input {
      border: 0;
      padding: 10px 5px;
      font-size: 13px;
      color: #333333;
      width: 100%;
      &::placeholder {
        color: #c9c9c9;
      }
    }
  }
}
.box_3 {
  padding: 0 15px;
  .add_self_tag {
    font-size: 12px;
    color: #1787fb;
    border: 1px solid #1787fb;
    display: inline-block;
    padding: 7px 15px 7px 32px;
    background: #f4f9ff url('../../assets/images/add_ico_blue.svg') 15px center
      no-repeat;
    background-size: 14px;
    border-radius: 26px;
  }
}
.box_2 {
  padding: 10px 15px 0 15px;
  .tag-item {
    &.select {
      background-color: #f4f9ff;
      color: #1787fb;
    }
    float: left;
    padding: 7px 12px;
    font-size: 12px;
    color: #999999;
    margin: 0 10px 10px 0;
    background-color: #f3f3f3;
    border-radius: 26px;
  }
}
.box_1 {
  width: 100%;
  font-size: 13px;
  color: #c9c9c9;
  padding: 15px 16px;
}
</style>
