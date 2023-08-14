<template>
  <div>
    <el-dialog title="验证码" :visible.sync="showDialog" :modal="showMadal" width="350px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
       <el-form  label-width="0px" :inline="true">
        <el-form-item label=" ">
          <el-input v-model="code"  style="width:120px;"></el-input>
        </el-form-item>
        <el-form-item label=" ">
          <img class="captcha_img" :src="src" @click="changeImg" />
        </el-form-item>
        <div class="clearfix"></div>
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
    },
    show (callback) {
      this.showDialog = true
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
    }
  }
}
</script>

<style lang="scss" scoped>
.captcha_img{
  width:130px;
  height:100%;
}
</style>
