<template>
  <div id="app">
    <Head>自我描述</Head>
    <div class="form_top_border"></div>
    <div class="reset_after">
      <textarea
        v-model="specialty"
        rows="5"
        placeholder="你可以通过描述你擅长的东西，也可以简单的介绍一 下，说说你的求职目标。"
        show-word-limi
      >
      </textarea>
    </div>
    <div style="margin: 16px 16px 0;">
      <van-button round block type="info" @click="handleSubmit">
        保存
      </van-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'EditSpecialty',
  data () {
    return {
      specialty: ''
    }
  },
  created () {
    this.initData(this.basicStore)
  },
  computed: {
    basicStore () {
      return this.$store.state.resume.basic
    }
  },
  methods: {
    // 初始化接口返回数据
    initData (interfaceBasic) {
      this.specialty = interfaceBasic.specialty
    },
    // 保存
    handleSubmit () {
      if (!this.specialty) {
        this.$notify('请填写自我描述')
        return false
      }
      http
        .post(api.resume_specialty_save, {
          specialty: this.specialty
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
.reset_after{
  textarea{
    width: 100%;
    line-height: 24px;
    padding: 24px 27px;
    border: none;
    font-size: 13.5px;
    color: #000000ba;
    background-color: white;
    resize: none;
  }
  textarea::-webkit-input-placeholder{
    color: #bec7cb;
  }
}
</style>
