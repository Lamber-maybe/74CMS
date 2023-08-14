<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closePopout')"
      >建议反馈</Head
    >
    <div class="form_top_border"></div>
    <div class="feed_container">
      <div class="feed_title">意见类型</div>
      <div class="feed_type">
        <div
          class="type_item"
          :class="item.id == type ? 'selected' : ''"
          v-for="(item, index) in options_feedback"
          :key="index"
          @click="handlerType(item)"
        >
          {{ item.text }}
        </div>
        <div class="clear"></div>
      </div>
      <div class="feed_title">反馈内容(必填)</div>
      <textarea
        v-model="content"
        class="feed_area"
        rows="8"
        placeholder="请具体描述您要反馈的内容，以便我们及时处理，感谢您的支持。"
      ></textarea>
      <div style="margin: 16px 16px 0;">
        <van-button round block type="info" @click="handleSubmit"
          >提交</van-button
        >
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Feedback',
  data () {
    return {
      type: 0,
      content: ''
    }
  },
  computed: {
    options_feedback () {
      return this.$store.state.classifyFeedback
    }
  },
  methods: {
    initCB () {
      this.content = ''
      this.$store.dispatch('getClassify', 'feedback').then(res => {
        this.type = res.data[0].id
      })
    },
    handlerType (item) {
      this.type = item.id
    },
    // 提交
    handleSubmit () {
      if (this.type == 0) {
        this.$notify('请选择反馈类型')
        return false
      }
      if (this.content == '') {
        this.$notify('请填写反馈内容')
        return false
      }
      if (this.content.length > 200) {
        this.$notify('最多填写200个字')
        return false
      }
      http
        .post(api.feedback, { type: this.type, content: this.content })
        .then(res => {
          this.$notify({
            type: 'success',
            message: res.message
          })
          this.$emit('closePopout')
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.feed_container {
  .feed_area {
    &::placeholder {
      color: #c9c9c9;
    }
    width: 100%;
    border: 1px solid #e2e2e2;
    resize: none;
    padding: 8px 12px;
    font-size: 14px;
    line-height: 1.6;
  }
  .feed_type {
    .type_item {
      &.selected {
        background-color: #f4f9ff;
        border: 1px solid #f4f9ff;
        color: #1787fb;
      }
      float: left;
      margin-right: 10px;
      padding: 5px 12px;
      font-size: 11px;
      color: #666666;
      background-color: #ffffff;
      border: 1px solid #e2e2e2;
    }
  }
  .feed_title {
    padding: 17px 0;
    font-size: 16px;
    color: #333333;
    font-weight: bold;
  }
  padding: 0 16px;
}
</style>
