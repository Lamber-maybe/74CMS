<template>
  <ul class="dialog_box">
    <li><span>简历姓名：</span><span class="color_1">{{remark_item.fullname}}</span></li>
    <li><span>基本信息：</span><span class="color_1">{{remark_item.age}}岁 | {{remark_item.sex_text}} | {{remark_item.education_text}} | {{remark_item.experience_text}} </span></li>
    <li><span>备注：</span> <el-input v-model="content" type="textarea" :rows="8" placeholder="请输入备注内容" maxlength="50" show-word-limit></el-input> </li>
  </ul>
</template>

<script>
export default {
  props:['remark_item'],
  data(){
    return{
      content:''
    }
  },
  methods:{
    initCB(){
      this.content = this.remark_item.remark
    }
  }
}
</script>

<style scoped>
  .el-textarea{
    width: 360px;
    margin-left: 70px;
  }
  .dialog_box{
    margin:8px 0 0 30px;
  }
  .dialog_box li{
    position: relative;
    margin-bottom: 24px;
    font-size: 14px;
    color: #999999;
  }
  .dialog_box li span{
    color: #666666;
  }
  .dialog_box li:nth-of-type(3) span{
    position: absolute;
    top: 0;
    left: 26px;
  }
  .dialog_box .color_1{
    color: #333333;
  }

</style>
