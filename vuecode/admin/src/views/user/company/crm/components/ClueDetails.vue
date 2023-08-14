<template>
  <div>
    <div class="box">
      <el-form ref="form" :model="form" label-width="100px" :rules="rules">
        <el-form-item label="线索名称：" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="联系人：" prop="contacts">
          <el-input v-model="form.contacts" />
        </el-form-item>
        <el-form-item label="联系手机：" prop="mobile">
          <el-input v-model="form.mobile" oninput="value=value.replace(/[^0-9.]/g,'')" />
        </el-form-item>
        <el-form-item label="联系微信：">
          <el-input v-model="form.weixin" />
        </el-form-item>
        <el-form-item label="公司规模：">
          <el-select v-model="form.scale" placeholder="请选择">
            <el-option
              v-for="item in scaleData"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="所属行业：">
          <el-select v-model="form.trade" placeholder="请选择">
            <el-option
              v-for="item in industryScreenData"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <!--        <el-form-item label="所在地区：">-->
        <!--          <el-cascader-->
        <!--            v-model="form.district"-->
        <!--            :options="regionScreenData"-->
        <!--            :props="{ checkStrictly: true }"-->
        <!--            clearable-->
        <!--          />-->
        <!--        </el-form-item>-->
        <el-form-item label="所在地区" prop="citycategory_arr">
          <el-cascader
            v-model="form.district"
            :options="options_citycategory"
            :show-all-levels="false"
          />
        </el-form-item>
        <el-form-item label="详细地址：">
          <el-input v-model="form.address" />
        </el-form-item>
        <el-form-item label="备注：">
          <el-input v-model="form.remark" style="width: 558px;" :rows="3" type="textarea" />
        </el-form-item>
        <el-form-item class="e_button">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button type="primary" @click="conversion">转化为客户</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { classify, clueAdd, clueDetails, clueEdit } from '@/api/company_crm'

export default {
  name: 'ClueDetails',
  props: ['clue_id', 'details'],
  data(){
    return {
      rules: {
        name: [
          { required: true, message: '请输入线索名称', trigger: 'blur' },
          { max: 20, message: '长度在 0 到 20 个字符', trigger: 'blur' }
        ],
        contacts: [
          { required: true, message: '请输入联系人', trigger: 'blur' },
          { max: 6, message: '长度在 0 到 6 个字符', trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '请输入联系电话', trigger: 'blur' }
        ]
      },
      form: {
        'name': '',
        'contacts': '',
        'mobile': '',
        'weixin': '',
        'scale': '',
        'industry': '',
        'region': '',
        'address': '',
        'trade': '',
        'remark': '',
        'district': []
      },
      submitLoading: false,
      scaleData: [],
      options_citycategory: [],
      industryScreenData: []
    }
  },
  watch: {
    details(value){
      this.form.name = value.name
      this.form.contacts = value.contacts
      this.form.mobile = value.mobile
      this.form.weixin = value.weixin
      this.form.scale = value.scale
      this.form.district = value.citycategory_arr
      this.form.trade = value.trade
      this.form.address = value.address
      this.form.remark = value.remark
    }
  },
  created () {
    this.classify()
  },
  methods: {
    classify(){
      classify({ 'type': 'citycategory,trade,companyScale' }).then(res => {
        this.options_citycategory = [...res.data.citycategory]
        this.options_citycategory = this.options_citycategory.map((item) => {
          if (item.children.length) {
            let level2Array = item.children
            level2Array = level2Array.map((level2Item) => {
              if (level2Item.children.length) {
                return { label: level2Item.label, value: level2Item.value, children: level2Item.children }
              } else {
                return { label: level2Item.label, value: level2Item.value }
              }
            })
            return { value: item.value, label: item.label, children: level2Array }
          } else {
            return { value: item.value, label: item.label }
          }
        })
        this.industryScreenData = res.data.trade
        this.scaleData = res.data.companyScale
      }).catch(() => {

      })
    },
    conversion(){
      this.$emit('conversion')
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const that = this
      const insertData = { ...this.form }
      insertData.clue_id = this.clue_id
      if (insertData.district.length == 0) {
        insertData.district1 = 0
        insertData.district2 = 0
        insertData.district3 = 0
        insertData.district = 0
      }
      if (insertData.district.length == 1) {
        insertData.district1 = insertData.district[0]
        insertData.district = insertData.district[0]
        insertData.district2 = 0
        insertData.district3 = 0
      }
      if (insertData.district.length == 2) {
        insertData.district1 = insertData.district[0]
        insertData.district2 = insertData.district[1]
        insertData.district3 = 0
        insertData.district = insertData.district[1]
      }
      if (insertData.district.length == 3) {
        insertData.district1 = insertData.district[0]
        insertData.district2 = insertData.district[1]
        insertData.district3 = insertData.district[2]
        insertData.district = insertData.district[2]
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          clueEdit(insertData).then(res => {
            if (res.code == 200){
              this.$message.success(res.message)
              this.$emit('clueDetails')
            } else {
              this.$message.error(res.message)
            }
            that.submitLoading = false
          }).catch(() => {
            that.submitLoading = false
          })
        } else {
          this.submitLoading = false
          return false
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .e_button{
    margin-top: 35px;
  }
  ::v-deep .el-form-item{
    margin-bottom: 20px;
  }
  ::v-deep .el-form-item__label{
    font-size: 14px;
    color: #606266;
    font-weight:normal;
  }
  .step_button {
    display: inline-block;
    margin-left: 20px;
    span {
      color: rgb(30, 136, 229);
      cursor: pointer;
    }
  }
  ::v-deep .el-button{
    width: 130px;
  }
  ::v-deep .el-input{
    display: inline-block;
    width: 100%;
  }
  .box{
    margin: 26px 30px;
    ::v-deep .el-input__inner{
      width: 558px;
    }
  }
</style>
