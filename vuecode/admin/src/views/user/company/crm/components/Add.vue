<template>
  <div class="app-container">
    <el-divider content-position="left">
      新增线索
    </el-divider>
    <div class="table-s">
      <el-form
        ref="form"
        :model="form"
        label-width="120px"
        :rules="rules"
      >
        <el-form-item label="线索名称：" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="联系人：" prop="contacts">
          <el-input v-model="form.contacts" />
        </el-form-item>
        <el-form-item label="联系电话：" prop="mobile">
          <el-input oninput="value=value.replace(/[^0-9.]/g,'')" v-model="form.mobile" />
        </el-form-item>
        <el-form-item label="联系微信：">
          <el-input v-model="form.weixin" />
        </el-form-item>
        <el-form-item label="所在地区" prop="citycategory_arr">
          <el-cascader
            v-model="form.district"
            :options="options_citycategory"
            :show-all-levels="false"
          />
        </el-form-item>
        <!--        <el-form-item label="所在地区：">-->
        <!--          <el-cascader-->
        <!--            v-model="form.district"-->
        <!--            :options="regionScreenData"-->
        <!--            :props="{ checkStrictly: true }"-->
        <!--            clearable-->
        <!--          />-->
        <!--        </el-form-item>-->
        <el-form-item label="详细地址：">
          <el-input v-model="form.address" />
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="form.remark" />
        </el-form-item>
        <el-form-item label="企业规模">
          <el-select v-model="form.scale" placeholder="请选择">
            <el-option
              v-for="item in scaleData"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="客户行业">
          <el-select v-model="form.trade" placeholder="请选择">
            <el-option
              v-for="item in industryScreenData"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="所属销售">
          <el-radio-group v-model="form.sale">
            <el-radio label="1">我的线索</el-radio>
            <el-radio label="0">公共线索</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">立即创建</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>

import { classify, clueAdd } from '@/api/company_crm'
import {validMobile} from "@/utils/validate";

export default {
  name: 'Add',
  data() {
    var validateContactMobile = (rule, value, callback) => {
      if (!validMobile(value)) {
        callback(new Error('请输入正确的手机号'))
      } else {
        callback()
      }
    }
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
          { required: true, message: '请输入联系电话', trigger: 'blur' },
          { validator: validateContactMobile, trigger: 'blur' }
        ]
      },
      submitLoading: false,
      form: {
        sale: '1',
        name: '',
        contacts: '',
        mobile: '',
        weixin: '',
        district: '',
        address: '',
        remark: '',
        trade: '',
        company_size: ''
      },
      scaleData: [],
      options_citycategory: [],
      industryScreenData: []
    }
  },
  created () {
    this.classify()
  },
  methods: {
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          clueAdd(insertData).then(res => {
            if (res.code == 200){
              this.$message.success(res.message)
            } else {
              this.$message.error(res.message)
            }
            that.submitLoading = false
            if (this.form.sale == '0'){
              setTimeout(function() {
                that.$router.push('/user/company/crm/internationalWaters')
              }, 1500)
            } else {
              setTimeout(function() {
                that.$router.push('/user/company/crm/my')
              }, 1500)
            }
          }).catch(() => {
            that.submitLoading = false
          })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
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
    }
  }
}
</script>

<style  scoped lang="scss">
  .table-s{
    margin-left: 50px;
  }
  ::v-deep .el-input{
    width: 450px;
  }
</style>
