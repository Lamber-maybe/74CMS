<template>
  <div id="app">
    <Head>语言能力</Head>
    <div class="box_2">添加你掌握的语言，最多可添加6种语言</div>
    <van-field
      readonly
      clickable
      required
      :value="languageName"
      label="选择语种"
      placeholder="请选择"
      @click="showPickerLanguage = !showPickerLanguage"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerLanguage" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsLanguage"
        :default-index="languageDefaultIndex"
        @confirm="onConfirmLanguage"
        @cancel="showPickerLanguage = false"
      />
    </van-popup>
    <van-field
      readonly
      clickable
      required
      :value="levelName"
      label="熟悉程度"
      placeholder="请选择"
      @click="showPickerLanguageLevel = !showPickerLanguageLevel"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerLanguageLevel" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsLanguageLevel"
        :default-index="levelDefaultIndex"
        @confirm="onConfirmLanguageLevel"
        @cancel="showPickerLanguageLevel = false"
      />
    </van-popup>
    <div style="margin: 16px 16px 0;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
    <div class="box_1" v-if="parseInt(id) > 0" @click="deleteCurrentLanguage">
      删除此语言能力
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditLanguage',
  data () {
    return {
      languageDefaultIndex: 0,
      levelDefaultIndex: 0,
      showPickerLanguage: false,
      showPickerLanguageLevel: false,
      id: 0,
      language: '',
      languageName: '',
      level: '',
      levelName: ''
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'language')
    this.$store.dispatch('getClassify', 'languageLevel')
    // 获取路由中的id
    this.id = this.$route.params.id
    if (this.id > 0) {
      // 如果有id，根据id取出对应的语言能力
      let thisLanguage = this.languageStore.filter(
        item => parseInt(item.id) === parseInt(this.id)
      )
      this.initData(thisLanguage[0])
      // 恢复所选语种
      this.languageDefaultIndex = this.columnsLanguage.findIndex(
        item => parseInt(item.id) === parseInt(this.language)
      )
      // 恢复熟悉程度
      this.levelDefaultIndex = this.columnsLanguageLevel.findIndex(
        item => parseInt(item.id) === parseInt(this.level)
      )
    }
  },
  computed: {
    columnsLanguage () {
      return this.$store.state.classifyLanguage
    },
    columnsLanguageLevel () {
      return this.$store.state.classifyLanguageLevel
    },
    languageStore () {
      return this.$store.state.resume.language_list
    }
  },
  methods: {
    // 初始化接口返回数据
    initData (interfaceLanguage) {
      console.log(interfaceLanguage)
      this.language = interfaceLanguage.language
      this.languageName = interfaceLanguage.language_text
      this.level = interfaceLanguage.level
      this.levelName = interfaceLanguage.level_text
    },
    // 选择语种
    onConfirmLanguage (value) {
      this.language = value.id
      this.languageName = value.text
      this.showPickerLanguage = !this.showPickerLanguage
    },
    // 选择熟悉程度
    onConfirmLanguageLevel (value) {
      this.level = value.id
      this.levelName = value.text
      this.showPickerLanguageLevel = !this.showPickerLanguageLevel
    },
    // 保存
    handleSubmit () {
      if (!this.language) {
        this.$notify('请选择语种')
        return false
      }
      if (!this.level) {
        this.$notify('请选择熟悉程度')
        return false
      }
      http
        .post(api.resume_language_save, {
          id: this.id,
          language: this.language,
          level: this.level
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
    },
    // 删除此语言能力
    deleteCurrentLanguage () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该语言能力吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_language_delete, {
              id: this.id
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
  font-size: 13px;
  color: #999999;
  text-align: center;
  width: 100%;
  padding: 15px 0;
}
.box_2 {
  font-size: 13px;
  color: #666666;
  padding: 15px 16px;
  background-color: #f3f3f3;
}
</style>
