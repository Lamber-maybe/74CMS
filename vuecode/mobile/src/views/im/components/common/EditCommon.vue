<template>
  <div>
      <Head :goback_custom="true" @gobackCustomMethod="gobackEditCommon">添加常用语</Head>
      <van-field rows="5" type="textarea" v-model="commonText" label="" placeholder="请输入您的常用语回复" />
      <div class="edit-btn">
          <van-button type="info" block round @click="handleEditCommon">保存</van-button>
      </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {mapState} from 'vuex'
export default {
  name: 'EditCommon',
  props: ['editCommonObj'],
  data () {
    return {
      commonText: ''
    }
  },
  watch: {
    editCommonObj (newVal) {
      if (newVal.content != undefined) {
        this.commonText = newVal.content
      }
    }
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken
    })
  },
  created () {
    if (this.editCommonObj.content != undefined) {
      this.commonText = this.editCommonObj.content
    }
  },
  methods: {
    /**
      * 返回
      */
    gobackEditCommon () {
      this.commonText = ''
      this.$emit('gobackEditCommon')
    },
    /**
     *  保存编辑
     */
    handleEditCommon () {
      if (this.commonText == '') {
        this.$notify({
          type: 'danger',
          message: '请输入常用语'
        })
        return false
      }
      this.handleEditCommonText()
    },
    /**
     * 编辑/添加常用语
     */
    handleEditCommonText () {
      if (this.editCommonObj.type == 'add') {
        this.handleAddCommontText()
      } else if (this.editCommonObj.type == 'edit') {
        this.handleEditCommontText()
      }
    },
    /**
     * 添加常用语
     */
    handleAddCommontText () {
      http.post(api.phraseAdd, {token: this.imToken, content: this.commonText}).then((res) => {
        if (res.code == 200) {
          this.$emit('updataCommonList')
          this.$notify({
            type: 'success ',
            message: res.message
          })
        } else {
          this.$notify({
            type: 'danger ',
            message: res.message
          })
        }
        setTimeout(() => {
          this.gobackEditCommon()
          this.commonText = ''
        }, 1000)
      })
    },
    /**
     * 编辑常用语
     */
    handleEditCommontText () {
      http.post(api.phraseEdit, {token: this.imToken, id: this.editCommonObj.id, content: this.commonText}).then((res) => {
        if (res.code == 200) {
          this.$emit('updataCommonList')
          this.$notify({
            type: 'success',
            message: res.message
          })
        } else {
          this.$notify({
            type: 'danger',
            message: res.message
          })
        }
        setTimeout(() => {
          this.gobackEditCommon()
          this.commonText = ''
        }, 1000)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.edit-btn{
    padding: 0 15px;
}
</style>
