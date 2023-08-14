<template>
  <div>
    <van-dialog v-model="showDialog" @confirm="handlerConfirm" @cancel="showDialog=false" :before-close="beforeClose" title="输入验证码" show-cancel-button>
      <div class="captcha_box">
        <img class="captcha_img" :src="src" @click="changeImg" />
        <van-cell-group :border="false">
          <van-field label-width="120" :border="false" v-model="code" label=" " placeholder="请输入左侧验证码" />
        </van-cell-group>
        <div class="little-border">&nbsp;</div>
      </div>
    </van-dialog>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'CaptchaPicture',
  data () {
    return {
      showDialog: false,
      code: '',
      src: '',
      secret_str: '',
      callback: null,
      enableClose: false
    }
  },
  mounted () {

  },
  created () {},
  computed: {},
  methods: {
    handlerConfirm () {
      if (this.code == '') {
        this.$notify('请输入验证码')
        return false
      }
      let data = {code: this.code, secret_str: this.secret_str}
      this.callback(data)
      this.enableClose = true
    },
    beforeClose (action, done) {
      done(this.enableClose)
    },
    show (callback) {
      this.callback = callback
      this.changeImg()
      this.showDialog = true
    },
    changeImg () {
      http
        .get(api.captcha_picture, {})
        .then(res => {
          this.secret_str = res.data.secret_str
          this.src = res.data.src
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.captcha_box{
  position:relative;
  margin:20px 10px 15px;
}
.captcha_img{
  position:absolute;
  top:0;
  left:10px;
  width:120px;
  height:100%;
  z-index:1;
}
.little-border{
  position:absolute;
  width:calc(100% - 155px);
  border-bottom:1px solid #EEE;
  right:20px;
  z-index:10;
  bottom:1px;
}
</style>
