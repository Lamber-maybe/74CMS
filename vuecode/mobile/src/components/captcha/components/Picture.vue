<template>
  <div>
    <van-dialog v-model="showDialog" @confirm="handlerConfirm" @cancel="showDialog=false" :before-close="beforeClose" title="输入图中验证码" show-cancel-button>
<!--      验证码样式修改-->
      <div>
        <div class="code">
          <p>验证码</p>
        </div>
        <img class="captcha_img" :src="src" @click="changeImg"  ref="seach_input"/>
      </div>

      <div class="captcha_box">
        <van-cell-group :border="false" >
          <van-field label-width="0" :border="false" v-model="code" label=" " placeholder="请输入图中验证码"  @keyup.enter.native="handlerConfirm" />
        </van-cell-group>
<!--        <div class="little-border">&nbsp;</div>-->
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
      this.showDialog = false
    },
    beforeClose (action, done) {
      this.$emit('setSubmitFun')
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
<!--验证码修改-->
<style lang="scss">
.captcha_box {
  .van-field__control{
    text-align: center;
  }
}
</style>
<style lang="scss" scoped>
.captcha_box{
  position:relative;
  width: 262px;
  margin: 20px auto;
  border: 1px solid #d2cece8c;
  margin-bottom: 47px;
  border-radius: 3px;
}

.captcha_img{
  position:absolute;
  top:0;
  left:10px;
  width:155px;
  z-index:1;
  margin: 76px 0px 0px 125px;
}
.little-border{
  position:absolute;
  width:calc(100% - 155px);
  border-bottom:1px solid #EEE;
  right:20px;
  z-index:10;
  bottom:1px;
}
.code{
   margin: 26px auto 0;
   border: 1px solid #d2cece8c;
   padding: 13px 0px 13px 31px;
   width: 262px;
  background: #e6eaf099;
  border-radius: 3px;
}
</style>
