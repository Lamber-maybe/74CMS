<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>短信群发</span>
      </div>
      <div class="tip">
        <p>如需群发短信，请咨询客服提交申请，我们收到申请后会与您联系确认并处理（注：群发短信至少提前一天申请预约，发送成功后会扣除短信条数）</p>
      </div>
      <div class="messageStep">
        <div class="messageStep1">
          <p class="icon">
            <img src="../../../../assets/images/marketing/1.png" alt="">
          </p>
          <div class="text">
            <p class="text1">填写申请</p>
            <p class="text2">咨询客服填写申请</p>
          </div>
        </div>
        <p class="messageLine" />
        <div class="messageStep1">
          <p class="icon">
            <img src="../../../../assets/images/marketing/2.png" alt="">
          </p>
          <div class="text">
            <p class="text1">确认信息</p>
            <p class="text2">与您确认群发信息</p>
          </div>
        </div>
        <p class="messageLine" />
        <div class="messageStep1">
          <p class="icon">
            <img src="../../../../assets/images/marketing/3.png" alt="">
          </p>
          <div class="text">
            <p class="text1">群发短信</p>
            <p class="text2">短信群发成功</p>
          </div>
        </div>
      </div>
      <div class="box">
        <div class="boxTop">
          <p class="boxTopTxt1">
            短信字数计算
          </p>
          <p class="boxTopTxt2">
            当前短信字数：<span>{{ mesLength }}个</span>，计费条数：<span>{{ mesTotal }}条</span>（数据仅供参考，请以实际为准）
          </p>
        </div>
        <div class="messageContentBox">
          <el-input v-model="message" resize="none" class="messageContent" type="textarea" placeholder="请输入群发短信内容" @input="messageInput" />
        </div>
        <el-button class="messageBtn" @click="concatClick()">咨询客服</el-button>
      </div>
    </el-card>
    <el-dialog
      v-if="messageDialogVisible"
      title=""
      :visible.sync="messageDialogVisible"
      width="377px"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <p class="messageQrcodeTxt3">咨询客服</p>
      <p class="messageQrcode">
        <img src="../../../../assets/images/marketing/qrcode.png">
      </p>
      <p class="messageQrcodeTxt1">微信扫码咨询或拨打电话</p>
      <p class="messageQrcodeTxt2">182-3404-5230</p>
    </el-dialog>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import sendConfig from '../../market/components/send_config.vue'
import { marketTaskAdd } from '@/api/market'

export default {
  components: {
    sendConfig
  },
  data() {
    return {
      submiting: false,
      checkSetmealAll: true,
      options_setmeal: [],
      data: {
        target: 'setmeal',
        title: '',
        content: '',
        send_type: [],
        condition: null
      },
      form: {
        setmeal_id: [],
        overtime: -1
      },
      rules: {},
      message: '',
      mesLength: 0,
      messageDialogVisible: false,
      mesTotal: 0,
      mesSize: 70
    }
  },
  created() {
  },
  methods: {
    messageInput(e){
      this.mesLength = e.length
      this.mesTotal = Math.ceil(e.length / this.mesSize)
    },
    closeDialog() {
      this.messageDialogVisible = false
    },
    concatClick(){
      this.messageDialogVisible = true
    }
  }
}
</script>
<style scoped>
.large {
  width: 608px;
}
.el-checkbox-group {
  display: inline;
}
.messageStep{
  display: flex;
	justify-content: start;
	align-items: center;
  padding-left: 20px;
}
.messageStep .messageLine{
   width: 147px;
   border-bottom: 1px dashed #D1D1D1;
   margin: 0 21px;
}
.messageStep1{
  display: flex;
	justify-content: start;
	align-items: center;
}
.messageStep1 .icon{
  width:50px;
  height:50px;
  margin-right: 14px;
}
.messageStep1 .icon image{
  display: block;
  width: 100%;
  height: 100%;
}
.messageStep1 .text .text1{
  font-size:14px;
  color:#232323;
  margin: 0 0 6px 0;
}
.messageStep1 .text .text2{
  font-size:14px;
  color:#7E7E7E;
  margin: 0;
}
.box{
  max-width:961px;
  margin-top: 66px;
  padding-left: 20px;
}
.box .boxTop{
  display: flex;
	justify-content: space-between;
	align-items: center;
  font-size:14px;
  color:#232323;
}
.box .boxTop .boxTopTxt2{
  color:#8B8B8B;
}
.box .boxTop .boxTopTxt2 span{
  color:#E20303;
}
.messageContentBox .messageContent{
  width: 100%;
  height: 95px;
  font-size:14px;
  border-radius: 4px;
}
>>>.el-textarea__inner{
  height:100%;
  border:1px solid #F1F1F1;
}
.messageBtn{
  width: 130px;
  height: 40px;
  background: linear-gradient(70deg, #FF6410, #FF981F);
  border-radius: 4px;
  font-size: 14px;
  color: #fff;
  margin-top: 40px;
}
.messageQrcode{
  width:158px;
  height:158px;
  margin:0 auto;
}
.messageQrcodeTxt1{
  width: 100%;
  text-align: center;
  color: #7E7E7E;
  font-size: 14px;
  margin: 21px 0 7px 0;
}
.messageQrcodeTxt2{
  width: 100%;
  text-align: center;
  font-size: 18px;
  color: #188AFF;
  margin: 0;
}
.messageQrcodeTxt3{
  color:#474747;
  font-weight: 600;
  width: 100%;
  text-align: center;
  font-size: 20px;
}
>>>.el-dialog__wrapper{
  top: 100px;
}
</style>
