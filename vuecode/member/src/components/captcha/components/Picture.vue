<template>
  <div>
    <el-dialog title="输入图中验证码" :visible.sync="showDialog" :modal="showMadal" width="350px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true" :before-close="handleClose" append-to-body>
       <el-form  label-width="0px" :inline="true" @submit.native.prevent>

        <div class="verification_code">
          <div class="code_text">
            <p>验证码</p>
          </div>
          <div class="code_photo">
            <el-form-item>
              <img class="captcha_img" :src="src" @click="changeImg" />
            </el-form-item>
          </div>
        </div>
        <div class="clearfix"></div>
         <div class="input">
           <el-form-item label=" ">
             <el-input ref="ipt" v-model="code" @keydown.native.enter="handlerConfirm" placeholder="请输入图中验证码" class="input_code"></el-input>
           </el-form-item>
         </div>
        <el-form-item label=" ">
            <el-button type="primary" @click="handlerConfirm" style="width:280px;">确定</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'CaptchaPicture',
  props:['mask'],
  data () {
    return {
      showDialog: false,
      code: '',
      src: '',
      secret_str: '',
      callback: null,
      showMadal:false
    }
  },
  mounted () {

  },
  created () {},
  computed: {},
  methods: {
    handlerConfirm () {
      if (this.code == '') {
        this.$message.error('请输入验证码')
        return false
      }
      let data = {code: this.code, secret_str: this.secret_str}
      this.callback(data)
      this.showDialog = false
      this.code = ''
    },
    show (callback) {
      this.showDialog = true
      this.$nextTick(()=>{
        this.$refs.ipt.focus()
      })
      this.callback = callback
      if(this.mask===true){
        this.showMadal = true
      }
      this.changeImg()
    },
    changeImg () {
      http
        .get(api.captcha_picture, {})
        .then(res => {
          this.secret_str = res.data.secret_str
          this.src = res.data.src
        })
        .catch(() => {})
    },
    handleClose(done) {
      this.$emit('setSubmitFun')
      done()
    }
  }
}
</script>
<style lang="scss">
  .input{
    display: block;
    margin-top: -20px;
    .input_code .el-input__inner{
      height: 51px;
      text-align: center;
      margin-top: -10px;
    }
  }
</style>
<style lang="scss" scoped>

.verification_code{
  width: 281px;
  border: 1px solid #DCDFE6;
  height: 51px;
  margin: 0 11px auto;
  border-radius: 4px;
  .code_text{
    float: left;
    width: 110px;
    background: #c7bfbf3d;
    height: 100%;
    text-align: center;
    line-height: 50px;
  }
  .code_photo{
    margin-right: -10px;
    .captcha_img{
      width: 168px;
      height: 49px;
    }
    float: right;
  }
}
.input_code{
  width:281px;
  height: 51px;
}
</style>
