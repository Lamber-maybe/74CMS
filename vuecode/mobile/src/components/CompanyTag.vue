<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('handlerCloseTag')" :show_right="'false'">
      {{ title }}
    </Head>
    <div class="box_1">
      您最多可以添加10个{{ title }}，还可以添加自定义标签哦
    </div>
    <div class="box_2">
      <div
        :class="parseInt(item.selected) > 0 ? 'tag-item selected' : 'tag-item'"
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
      <van-button
        round
        block
        type="info"
        native-type="button"
        @click="handleSubmit"
      >
        保存
      </van-button>
    </div>
    <van-dialog
      v-model="showCustom"
      title="添加自定义标签"
      show-cancel-button
      @confirm="handleConfirm"
      get-container="body"
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
export default {
  name: 'CompanyTag',
  props: ['tag_arr', 'title'],
  computed: {
    tagList () {
      return this.$store.state.classifyJobTag
    }
  },
  data () {
    return {
      showCustom: false,
      selectedTags: [],
      loopTags: [],
      customText: ''
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'jobTag')
  },
  methods: {
    initCB () {
      this.$store.dispatch('getClassify', 'jobTag')
      this.loopTags = this.tagList
      let interfaceTags = this.tag_arr
      if (interfaceTags.length) {
        this.loopTags = this.loopTags.map(function (item) {
          let thisIndex = interfaceTags.findIndex(
            val => parseInt(item.id) === parseInt(val)
          )
          return {
            id: item.id,
            text: item.text,
            selected: thisIndex > -1 ? 1 : 0
          }
        })
        let _this = this
        interfaceTags.forEach(function (item) {
          if (!parseInt(item)) {
            _this.loopTags.push({ id: '', text: item, selected: 1 })
          }
        })
        this.syncSelectedTag()
      }
    },
    // 同步已选中标签
    syncSelectedTag () {
      this.selectedTags = this.loopTags.filter(
        item => parseInt(item.selected) > 0
      )
    },
    // 添加自定义标签
    handleCustom () {
      // 判断选中企业福利的数量
      if (this.selectedTags.length >= 10) {
        this.$notify({
          type: 'warning',
          message: '您最多可以选择10个' + this.title
        })
        return false
      } else {
        this.showCustom = !this.showCustom
      }
    },
    // 自定义标签确定
    handleConfirm () {
      if (this.customText == '') {
        this.$notify({
          type: 'warning',
          message: '请输入标签内容'
        })
        return false
      }
      this.loopTags.push({ id: '', text: this.customText, selected: 1 })
      this.customText = ''
      this.syncSelectedTag()
    },
    // 选择标签
    tagClick (val) {
      let thisIndex = 0
      // 判断是否是自定义标签
      if (val.id) {
        thisIndex = this.loopTags.findIndex(
          item => parseInt(item.id) === parseInt(val.id)
        )
      } else {
        thisIndex = this.loopTags.findIndex(item => item.text === val.text)
      }
      if (this.loopTags[thisIndex].selected > 0) {
        this.$set(this.loopTags[thisIndex], 'selected', 0)
      } else {
        // 判断选中企业福利的数量
        if (this.selectedTags.length >= 10) {
          this.$notify({
            type: 'warning',
            message: '您最多可以选择10个' + this.title
          })
        } else {
          this.$set(this.loopTags[thisIndex], 'selected', 1)
        }
      }
      this.syncSelectedTag()
    },
    // 保存
    handleSubmit () {
      let tagIdArr = []
      let tagTextArr = []
      this.selectedTags.forEach(function (item) {
        tagTextArr.push(item.text)
        if (item.id) {
          tagIdArr.push(item.id)
        } else {
          tagIdArr.push(item.text)
        }
      })
      this.$emit('handlerCloseTag', { tagIdArr, tagTextArr })
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
    padding: 4.5px 15px 4.5px 32px;
    background: #f4f9ff url("../assets/images/add_ico_blue.svg") 15px center
      no-repeat;
    background-size: 14px;
    border-radius: 26px;
  }
}
.box_2 {
  padding: 10px 15px 0 15px;
  .tag-item {
    &.selected {
      background-color: #f4f9ff;
      color: #1787fb;
    }
    float: left;
    padding: 4.5px 12px;
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
  padding: 12.5px 16px;
}
</style>
