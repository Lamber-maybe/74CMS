<template>
  <div>
    <div class="datetime">{{ item.datetime }}</div>
    <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
      <img class="image" :src="item.avatar" />
    </div>
    <div
      :class="(item.message.status==1?'wechatcard':'wechatcard2')+' '+(item.self_side == 1 ? 'mine' : 'other')"
    >
      <div class="ico"></div>
      <div class="info" v-if="item.message.status==1">
        <div class="i1">{{item.message.contact}}的微信号</div>
        <div class="i2">{{item.message.wechat}}</div>
      </div>
      <div class="info" v-if="item.message.status!=1">
        <div class="i1">我想和您交换微信号，</div>
        <div class="i2">您是否同意</div>
      </div>
      <div class="clear"></div>
      <div class="btn" v-if="item.message.status==0 && item.self_side==0">
        <div class="refuse" @click="refuse(item)">拒绝</div>
        
        <el-popover
          placement="top"
          width="240"
          v-model="visibleWechat"
        >
          <p style="margin:10px 0;">确定与对方交换微信吗？</p>
          <el-input v-model.trim="input_wechat" size="small" placeholder="请输入您的微信号"></el-input>
          <div style="font-size:12px;color:#999;margin-top:10px;">微信号可以在基本资料中修改</div>
          <div style="text-align: center; margin: 14px 0 10px">
            <el-button size="small" @click="visibleWechat=false">取消</el-button>
            <el-button type="primary" size="small" @click="agree(item)">确定</el-button>
          </div>
          <div class="agree" slot="reference">同意</div>
        </el-popover>
        <div class="clear"></div>
      </div>
      <div class="copy" v-if="item.message.status==2">已拒绝</div>
      <div class="copy" v-if="item.message.status==1" @click="copy(item.message.wechat)">复制微信号</div>
    </div>
    <div
      class="status"
      :class="item.readed == 1 ? 'readed' : ''"
      v-if="item.self_side == 1"
    >
      {{ item.readed == 1 ? "已读" : "送达" }}
    </div>
    <div class="clear"></div>
  </div>
</template>

<script>
export default {
  name: 'ItemWechat',
  props:['item'],
  data(){
    return {
      input_wechat:'',
      visibleWechat:false
    }
  },
  created(){
    
  },
  mounted() {
    
  },
  methods:{
    copy (ele) {
      var oInput = document.createElement('input')
      oInput.value = ele
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$message({type:'success',message:'复制成功'})
    },
    refuse(item){
      this.$emit('refuse',item)
    },
    agree(item){
      item.message.wechat = this.input_wechat
      this.$emit('agree',item)
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.status {
  color: #1787fb;
  font-size: 12px;
  float: right;
  &.readed {
    color: #c9c9c9;
  }
}
.datetime {
  text-align: center;
  font-size: 12px;
  color: #999999;
  margin-bottom: 18px;
}
.photo {
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
}
.wechatcard {
  width: 280px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 28px;
  padding: 24px 0 0;
  height: 138px;
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .ico {
    float: left;
    width: 48px;
    height: 47px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 48px 47px;
    margin-left: 40px;
    background-image: url("../../../../assets/images/im/wx01.png");
  }
  .info {
    float: left;
    margin-left: 16px;
    line-height: 22px;
    height: 64px;
    .i1 {
      color: #666666;
      font-size: 14px;
    }
    .i2 {
      color: #02bf57;
      font-size: 14px;
    }
  }
  .copy {
    height: 48px;
    line-height: 47px;
    font-size: 14px;
    color: #02bf57;
    border-top: 1px solid #ededed;
    text-align: center;
    cursor: pointer;
    font-weight:bold;
  }
}
.wechatcard2 {
  width: 280px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 28px;
  padding: 24px 0 0;
  height: 138px;
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .ico {
    float: left;
    width: 48px;
    height: 47px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 48px 47px;
    margin-left: 40px;
    background-image: url("../../../../assets/images/im/wx01.png");
  }
  .info {
    float: left;
    margin-left: 16px;
    line-height: 22px;
    height: 64px;
    .i1 {
      color: #666666;
      font-size: 14px;
    }
    .i2 {
      color: #666666;
      font-size: 14px;
    }
  }
  .btn {
    .refuse {
      float: left;
      width: 70px;
      height: 29px;
      background: #ffffff;
      color: #5792ff;
      border: 1px solid #5792ff;
      border-radius: 5px;
      cursor: pointer;
      margin-right: 24px;
    }
    .agree {
      float: left;
      width: 70px;
      height: 29px;
      color: #fff;
      background: #5792ff;
      border: 1px solid #5792ff;
      border-radius: 5px;
      cursor: pointer;
    }
    padding: 0 56px;
    height: 52px;
    font-size: 16px;
    text-align: center;
  }
  .copy {
    height: 48px;
    line-height: 47px;
    font-size: 14px;
    color: #b2b2b2;
    border-top: 1px solid #ededed;
    text-align: center;
  }
}
</style>
