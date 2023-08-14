<template>
  <el-form ref="form" label-width="100px">
  <el-form-item label="委托天数" v-if="hasEntrust===false">
    <el-select v-model="days" placeholder="请选择委托天数">
      <el-option label="3天" :value="3"></el-option>
      <el-option label="7天" :value="7"></el-option>
      <el-option label="15天" :value="15"></el-option>
    </el-select>
  </el-form-item>
  <el-form-item label="剩余委托天数" v-if="hasEntrust===true">
    <span style="color:#e6a23c">{{showDays}}天</span>
  </el-form-item>
  <el-form-item>
    <el-button type="primary" @click="onSubmit" v-if="hasEntrust===false">确定委托</el-button>
    <el-button type="primary" @click="onCancel" v-if="hasEntrust===true">取消委托</el-button>
  </el-form-item>
</el-form>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
  export default{
    props:[],
    data() {
      return {
        hasEntrust:false,
        days:3,
        showDays:0,
      }
    },
    mounted(){
      
    },
    methods: {
      initCB(){
        this.fetchData()
      },
      fetchData(){
        http.get(api.personal_entrust).then(res=>{
          if(res.data===null){
            this.hasEntrust = false
          }else{
            this.hasEntrust = true
            this.showDays = res.data.days
          }
        })
      },
      onSubmit() {
        http.post(api.personal_entrust_save ,{days:this.days}).then(res=>{
          this.$message({type:'success',message:res.message})
          this.$emit('closeEntrust')
        })
      },
      onCancel(){
        http.post(api.personal_entrust_cancel).then(res=>{
          this.$message({type:'success',message:res.message})
          this.hasEntrust = false
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
 
</style>
