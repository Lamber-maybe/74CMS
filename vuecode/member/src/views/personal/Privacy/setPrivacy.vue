<template>
  <div>
    <div class="privacy_box">
      <div class="privacy_title">
          简历公开状态 <span>简历状态关闭后，企业无法看到您的简历</span>
      </div>
      <div class="radio_box">
        <el-radio v-model="display" @change="showResume" label="1">开启</el-radio>
      </div>
      <div class="radio_box">
        <el-radio v-model="display" @change="showResume" label="0">关闭</el-radio>
      </div>
    </div>

    <div class="privacy_box">
      <div class="privacy_title">
          简历姓名显示 <span>选中匿名展示后，HR无法看到您的真实姓名</span>
      </div>
      <div class="radio_box">
        <el-radio v-model="display_name" @change="showName" label="1">实名显示(如：王创明)</el-radio>
      </div>
      <div class="radio_box">
        <el-radio v-model="display_name" @change="showName" label="0">匿名显示(如：王先生/女士)</el-radio>
      </div>
    </div>

  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'
  export default{
    data(){
        return{
          display:'1',
          display_name:'1'
        }
    },
    created() {
      http.get(api.resume_privates_info).then((res)=>{
        console.log(res)
        if(parseInt(res.code)==200){
          this.initData(res.data)
        }else{
          this.$message({ message:res.message,  type: 'error' });
        }
      })
    },
    methods:{
      initData(value){
        this.display=value.is_display +''
        this.display_name=value.display_name+''
      },
      showResume(val){
          http.post(api.resume_privates_set_display,{display:val}).then((res)=>{
            if(parseInt(res.code)==200){
              this.$message({message:res.message,  type: 'success' });
            }else{
              this.$message({ message:res.message,  type: 'error' });
            }
          })
      },
      showName(val){
        http.post(api.resume_privates_set_display_name,{display:val}).then((res)=>{
          if(parseInt(res.code)==200){
            this.$message({message:res.message,  type: 'success' });

          }else{
            this.$message({message:res.message,  type: 'error' });
          }
        })
      },

    }
  }
</script>

<style lang="scss" scoped>
  .privacy_box{
    width: 885px;
    height:150px ;
    background:#fcfcfc ;
    margin-bottom: 20px;
  }
  .privacy_title{
    font-size:16px ;
    color: #666666;
    padding: 30px 30px;
  }
  .privacy_title span{
    color: #c4c4c4;
    font-size: 14px;
  }
  .radio_box{
    padding: 0 0 15px 30px;
  }
</style>
