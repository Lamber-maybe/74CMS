<template>
  <div class="main">
    <el-form ref="form" label-width="80px" :model="form" :rules="rules">
      <el-form-item label="期望职位" prop="categoryArr">
        <el-cascader
          ref="intCategory"
          v-model="form.categoryArr"
          placeholder="请选择期望职位"
          :options="columnsJobcategory"
          filterable
          ></el-cascader>
      </el-form-item>
      <el-form-item label="工作地区" prop="districtArr">
        <el-cascader
          ref="intDistrict"
          v-model="form.districtArr"
          placeholder="请选择期望地区"
          :options="columnsCitycategory"
          :props="{ checkStrictly: true }"
          filterable
         ></el-cascader>
      </el-form-item>
        <el-form-item label="最低薪资" prop="minwage">
        <el-select class="w118"
                      placeholder="请选择"
                      v-model="form.minwage"
                      @change="handlerMinwageChange">
            <el-option
              v-for="(item, index) in columnsMinWage"
               :key="index"
               :label="item.name"
               :value="item.id">
            </el-option>
          </el-select>
      </el-form-item>
        <el-form-item label="最高薪资" prop="maxwage">
          <el-select class="w118" v-model="form.maxwage" placeholder="请选择">
            <el-option
              v-for="(item, index) in options_maxwage"
              :key="index"
              :label="item.name"
              :value="item.id"
            ></el-option>
          </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">确定</el-button>
        <el-button @click="onCancel" v-if="hasInfo===true">取消订阅</el-button>
      </el-form-item>
    </el-form>

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
  </div>
</template>
<script>
  import http from '@/utils/http'
  import api from '@/api'
  import WeChatQrcode from '@/components/WeChatQrcode'
  export default {
    components: {
      WeChatQrcode
    },
    data() {
      return {
        form:{
          categoryArr: [],
          districtArr: [],
          minwage:'',
          maxwage:'',
        },
        optionCategory: [],
        optionDistrict: [],
        options_maxwage:[],
        hasInfo:false,
        rules: {
          categoryArr: [
            { required: true, message: '请选择期望职位', trigger: 'change' }
          ],
          districtArr: [
            { required: true, message: '请选择期望地区', trigger: 'change' }
          ],
          minwage: [
            { required: true, message: '请选择最低薪资', trigger: 'change' }
          ],
          maxwage: [
            { required: true, message: '请选择最高薪资', trigger: 'change' }
          ]
        }
      }
    },
    created(){
      this.fetchData();
    },
    computed: {
      columnsCitycategory () {
        return this.$store.state.classifyCityCategory
      },
      columnsJobcategory () {
        return this.$store.state.classifyJobCategory
      },
      columnsMinWage () {
        let returnArr = []
        let arr = [...this.$store.state.classifyWage]
        if(arr.length>0){
          arr.pop()
        }
        for (let index = 0; index < arr.length; index++) {
          let tmp_json = {
            id: arr[index],
            name: arr[index] + '元/月'
          }
          returnArr.push(tmp_json)
        }
        return returnArr
      }
    },
    mounted(){
      this.$store.dispatch('getClassify', 'jobcategory')
      this.$store.dispatch('getClassify', 'citycategory')
      this.$store.dispatch('getClassifyWage')
    },
    methods: {
      fetchData(){
        http.get(api.personal_subscribe_job).then(res=>{
          if(res.data!==null){
            this.hasInfo = true
            this.form.minwage = res.data.minwage >0 ? res.data.minwage:''
            this.form.maxwage = res.data.maxwage >0 ? res.data.maxwage:''
            if(this.form.minwage!=''){
              this.handlerMinwageChange(this.form.minwage)
            }
            res.data.category1 = res.data.category1 > 0 ? res.data.category1 : 0
            res.data.category2 = res.data.category2 > 0 ? res.data.category2 : 0
            res.data.category3 = res.data.category3 > 0 ? res.data.category3 : 0
            if(res.data.category1>0){
              this.form.categoryArr.push(res.data.category1)
              if(res.data.category2>0){
                this.form.categoryArr.push(res.data.category2)
                if(res.data.category3>0){
                  this.form.categoryArr.push(res.data.category3)
                }
              }
            }
            if(res.data.district1>0){
              this.form.districtArr.push(res.data.district1)
              if(res.data.district2>0){
                this.form.districtArr.push(res.data.district2)
                if(res.data.district3>0){
                  this.form.districtArr.push(res.data.district3)
                }
              }
            }
          }
        })
      },
      handlerMinwageChange(e) {
        this.handleMaxwageChange(e)
      },
      handleMaxwageChange(minwage) {
        let wage_data = this.$store.state.classifyWage
        if (!minwage) {
          return false
        }
        this.options_maxwage = []
        let startindex = wage_data.indexOf(minwage) + 1
        if (minwage >= this.form.maxwage) {
          this.form.maxwage = wage_data[startindex]
        }
        for (let index = startindex; index < wage_data.length; index++) {
          let tmp_json = {
            id: wage_data[index],
            name: wage_data[index] + '元/月',
          }
          this.options_maxwage.push(tmp_json)
        }
      },
      onSubmit() {
        let submitData = {
          category1:0,
          category2:0,
          category3:0,
          district1:0,
          district2:0,
          district3:0,
          minwage:this.form.minwage,
          maxwage:this.form.maxwage
        }
        if(this.form.categoryArr.length===3){
          submitData.category1 = this.form.categoryArr[0]
          submitData.category2 = this.form.categoryArr[1]
          submitData.category3 = this.form.categoryArr[2]
        }else if(this.form.categoryArr.length===2){
          submitData.category1 = this.form.categoryArr[0]
          submitData.category2 = this.form.categoryArr[1]
        }else if(this.form.categoryArr.length===1){
          submitData.category1 = this.form.categoryArr[0]
        }
        if(this.form.districtArr.length===3){
          submitData.district1 = this.form.districtArr[0]
          submitData.district2 = this.form.districtArr[1]
          submitData.district3 = this.form.districtArr[2]
        }else if(this.form.districtArr.length===2){
          submitData.district1 = this.form.districtArr[0]
          submitData.district2 = this.form.districtArr[1]
        }else if(this.form.districtArr.length===1){
          submitData.district1 = this.form.districtArr[0]
        }
        this.$refs.form.validate((valid) => {
          if (valid) {
            http.post(api.personal_subscribe_job_save ,submitData).then(res=>{
              this.$message({type:'success',message:res.message})
              this.hasInfo = true
              /**
               * 【ID1000719】
               * 【新增】公众号引导弹窗场景（职位订阅）
               * cy 2023-7-17
               */
              this.popupWechatQrcodeWindow('user_pc_job_subscribe')
            })
          } else {
            return false;
          }
        });

      },
      onCancel() {
        http.post(api.personal_subscribe_job_cancel).then(res=>{
          this.$message({type:'success',message:res.message})
          this.hasInfo = false
          this.$refs.form.resetFields();
        })
      },
      // 弹出微信二维码弹框
      popupWechatQrcodeWindow(val) {
        this.$refs.weChatQrcodeRef.handleOpen(val, '扫码绑定公众号，求职快人一步')
      }
    }
  }
</script>
<style lang="scss" scoped>
.main{
  width:600px;
  .el-cascader{
    width:448px;
  }
}
</style>
