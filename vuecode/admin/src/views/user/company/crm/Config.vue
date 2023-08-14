<template>
  <div v-loading="loading" class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>CRM设置</span>
      </div>
      <div class="box">
        <div class="button">
          <div :class="type=='grade' ? 'selectButton' : ''" @click="switchTemplate('grade')">客户等级（生命周期）</div>
          <div :class="type=='configure' ? 'selectButton' : ''" @click="switchTemplate('configure')">客户配置</div>
          <div :class="type=='clue' ? 'selectButton' : ''" @click="switchTemplate('clue')">线索设置</div>
          <div :class="type=='label' ? 'selectButton' : ''" @click="switchTemplate('label')">标签设置</div>
        </div>
      </div>
      <div v-if="type=='grade'" class="bottoms">
        <p class="title1">当前客户生命周期：{{ open_count }}个阶段</p>
        <p class="title2">至多设置6个阶段，用于标记客户生命周期状态，首尾两个阶段不可编辑</p>
        <div class="step">
          <div class="step_box">
            <div class="step_num">1</div>
            <div class="step_input1">
              <el-input v-model="step_name1" placeholder="请输入内容" :disabled="true" />
            </div>
          </div>
          <div class="step_box">
            <div class="step_num">2</div>
            <div :class="step1_button?'step_input_button':'step_input_button1'">
              <div class="step_input">
                <el-input v-if="step1_button == true" v-model="step_name2.name" readonly />
                <el-input v-if="step1_button == false" v-model="step_name2.name" maxlength="6" show-word-limit />
              </div>
              <div v-if="step1_button == true" class="step_button">
                <span @click="step1_button = false">编辑</span>
                <el-switch v-model="step_name2.is_open" style="margin-left: 10px;" @change="switchBut(step_name2)" />
              </div>
              <div v-if="step1_button == false" class="step_button">
                <span @click="step1_button = true">取消</span>
                <span style="margin-left: 20px;" @click="preservation(step_name2,'step1_button')">保存</span>
              </div>
            </div>
          </div>
          <div class="step_box">
            <div class="step_num">3</div>
            <div :class="step2_button?'step_input_button':'step_input_button1'">
              <div class="step_input">
                <el-input v-if="step2_button == true" v-model="step_name3.name" readonly />
                <el-input v-if="step2_button == false" v-model="step_name3.name" maxlength="6" show-word-limit />
              </div>
              <div v-if="step2_button == true" class="step_button">
                <span @click="step2_button = false">编辑</span>
                <el-switch v-model="step_name3.is_open" style="margin-left: 10px;" @change="switchBut(step_name3)" />
              </div>
              <div v-if="step2_button == false" class="step_button">
                <span @click="step2_button = true">取消</span>
                <span style="margin-left: 20px;" @click="preservation(step_name3,'step2_button')">保存</span>
              </div>
            </div>
          </div>
          <div class="step_box">
            <div class="step_num">4</div>
            <div :class="step3_button?'step_input_button':'step_input_button1'">
              <div class="step_input">
                <el-input v-if="step3_button == true" v-model="step_name4.name" readonly />
                <el-input v-if="step3_button == false" v-model="step_name4.name" maxlength="6" show-word-limit />
              </div>
              <div v-if="step3_button == true" class="step_button">
                <span @click="step3_button = false">编辑</span>
                <el-switch v-model="step_name4.is_open" style="margin-left: 10px;" @change="switchBut(step_name4)" />
              </div>
              <div v-if="step3_button == false" class="step_button">
                <span @click="step3_button = true">取消</span>
                <span style="margin-left: 20px;" @click="preservation(step_name4,'step3_button')">保存</span>
              </div>
            </div>
          </div>
          <div class="step_box">
            <div class="step_num">5</div>
            <div :class="step4_button?'step_input_button':'step_input_button1'">
              <div class="step_input">
                <el-input v-if="step4_button == true" v-model="step_name5.name" readonly />
                <el-input v-if="step4_button == false" v-model="step_name5.name" maxlength="6" show-word-limit />
              </div>
              <div v-if="step4_button == true" class="step_button">
                <span @click="step4_button = false">编辑</span>
                <el-switch v-model="step_name5.is_open" style="margin-left: 10px;" @change="switchBut(step_name5)" />
              </div>
              <div v-if="step4_button == false" class="step_button">
                <span @click="step4_button = true">取消</span>
                <span style="margin-left: 20px;" @click="preservation(step_name5,'step4_button')">保存</span>
              </div>
            </div>
          </div>
          <div class="step_box">
            <div class="step_num">6</div>
            <div class="step_input1"><el-input v-model="step_name6" placeholder="请输入内容" :disabled="true" /></div>
          </div>
        </div>
      </div>
      <div v-if="type=='configure'" class="bottoms_two">
        <el-form ref="form" :model="form" label-width="150px">
          <el-form-item label="主动领取客户限制/天">
            <el-input-number v-model="configureData.customer_receive_limit.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示销售当天能主动领取客户的限制数，0为不允许领取。
            </span>
          </el-form-item>
          <el-form-item label="未跟进掉入公海">
            <el-input-number v-model="configureData.customer_fall_seas.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示销售未跟进自己的客户达到设置的天数后，客户会自动掉入公共客户中，0为不掉入。
            </span>
          </el-form-item>
          <el-form-item label="禁止领取期限(销售)">
            <el-input-number v-model="configureData.customer_forbidden_sale.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示销售将客户丢入公客后多长时间不能再领取该客户，0为不允许领取。
            </span>
          </el-form-item>
          <el-form-item label="禁止领取期限(系统)">
            <el-input-number v-model="configureData.customer_forbidden_sys.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示系统自动掉公客的客户掉入公客后多长时间不能再领取该客户，0为不允许领取。
            </span>
          </el-form-item>
          <el-form-item label="客户分配销售规则">
            <el-radio-group v-model="configureData.customer_allocation_rule.value">
              <el-radio label="0">不分配</el-radio>
              <el-radio label="1">自动分配</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item v-if="configureData.customer_allocation_rule.value == 1" label="选择销售">
            <el-select v-model="configureData.admin_id" multiple placeholder="请选择销售">
              <el-option
                v-for="item in customer_service"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="configureSubmit">保存</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div v-if="type=='clue'" class="bottoms_two">
        <el-form ref="form" :model="form" label-width="160px">
          <el-form-item label="主动领取线索限制/天">
            <el-input-number v-model="threadeData.thread_receive_limit.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示销售当天能主动领取线索的限制数，0为不允许领取。
            </span>
          </el-form-item>
          <el-form-item label="未跟进掉入线索池">
            <el-input-number v-model="threadeData.thread_fall_seas.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示销售未跟进自己的客户达到设置的天数后，客户会自动掉入线索池中，0为不掉入。
            </span>
          </el-form-item>
          <el-form-item label="禁止领取期限(销售)">
            <el-input-number v-model="threadeData.thread_forbidden_sale.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示销售将客户丢入公客后多长时间不能再领取该客户，0为不允许领取。
            </span>
          </el-form-item>
          <el-form-item label="禁止领取期限(系统)">
            <el-input-number v-model="threadeData.thread_forbidden_sys.value" :min="0" :max="9999" />
            <span class="smalltip">
              <i class="el-icon-info" />
              表示系统自动掉公客的客户掉入公客后多长时间不能再领取该客户，0为不允许领取。
            </span>
          </el-form-item>
          <el-form-item label="是否允许线索名称重复">
            <el-radio-group v-model="threadeData.thread_duplicate_name.value">
              <el-radio label="0">否</el-radio>
              <el-radio label="1">是</el-radio>
            </el-radio-group>
            <span class="smalltip">
              <i class="el-icon-info" />
              表示录入线索时，线索名称是否可以重复
            </span>
          </el-form-item>
          <!--          <el-form-item label="线索分配销售规则">-->
          <!--            <el-radio-group v-model="threadeData.thread_allocation_rule.value">-->
          <!--              <el-radio label="0">自动分配</el-radio>-->
          <!--              <el-radio label="1">不自动分配</el-radio>-->
          <!--            </el-radio-group>-->
          <!--          </el-form-item>-->
          <el-form-item>
            <el-button type="primary" @click="configureSubmit">保存</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div v-if="type=='label'" class="bottoms_two">
        <el-table
          :data="labelTableData"
          style="width: 100%"
        >
          <el-table-column
            label="分类名称"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.name }}</span>
            </template>
          </el-table-column>
          <el-table-column
            label="排序"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.order }}</span>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button
                size="mini"
                @click="handleEdit(scope.row)"
              >编辑</el-button>
              <el-button
                size="mini"
                type="danger"
                @click="handleDelete(scope.row.id)"
              >删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="bortton-page">
          <el-col :span="8">
            <el-button size="small" type="primary" @click="funAuditBatch">
              添加
            </el-button>
          </el-col>
        </div>
      </div>
    </el-card>
    <el-dialog
      :title="title"
      :visible.sync="dialogVisible"
      width="30%"
    >
      <el-form ref="form" :model="form" :rules="rules" label-width="120px">
        <el-form-item label="标签名称:" prop="name">
          <el-input v-model="form.name" style="width: 80%" />
        </el-form-item>
        <el-form-item label="排序:">
          <el-input v-model="form.order" type="number" :min="0" style="width: 80%" />
        </el-form-item>
        <el-form-item>
          <el-button v-if="dialog_type == 'edit'" type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button v-if="dialog_type == 'add'" type="primary" @click="onAdd('form')">保存</el-button>
          <el-button @click="dialogVisible = false">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>

import {
  editConfig,
  delCrmTag,
  addCrmTag,
  lifeCycleAll,
  lifeCycleEdit,
  lifeCycleSwitch,
  getSysConfigByCategory,
  getAllCrmTags,
  editCrmTag,
  clueAdminLists,
  addContact, editContact
} from '@/api/company_crm'

export default {
  name: 'Config',
  data(){
    return {
      rules: {
        name: [
          { required: true, message: '请输入标签名称', trigger: 'blur' },
          { max: 10, message: '长度在 0 到 10 个字符', trigger: 'blur' }
        ]
      },
      title: '添加',
      dialog_type: 'add',
      dialogVisible: false,
      configureData: [],
      labelTableData: [],
      threadeData: [],
      step1_button: true,
      step2_button: true,
      step3_button: true,
      step4_button: true,
      input: '',
      form: {
        id: 0,
        name: '',
        order: 0
      },
      type: 'grade',
      value1: true,
      loading: false,
      step_name1: '新客户',
      step_name2: '',
      step_name3: '',
      step_name4: '',
      step_name5: '',
      step_name6: '完成 | 可选状态：流失客户/成交客户',
      customer_service: [],
      open_count: 0
    }
  },
  created() {
    this.lifeCycleAll()
    this.adminlists()
  },
  methods: {
    adminlists(){
      clueAdminLists().then(response => {
        this.customer_service = response.data
      })
    },
    lifeCycleAll(){
      this.loading = true
      lifeCycleAll().then(response => {
        this.open_count = response.data.open_count
        this.step_name1 = response.data.res[0].name
        var step_name2_is_open = null
        if (response.data.res[1].is_open == 1){
          step_name2_is_open = true
        } else {
          step_name2_is_open = false
        }
        this.step_name2 = {
          'name': response.data.res[1].name,
          'id': response.data.res[1].id,
          'is_open': step_name2_is_open
        }
        var step_name3_is_open = null
        if (response.data.res[2].is_open == 1){
          step_name3_is_open = true
        } else {
          step_name3_is_open = false
        }
        this.step_name3 = {
          'name': response.data.res[2].name,
          'id': response.data.res[2].id,
          'is_open': step_name3_is_open
        }
        var step_name4_is_open = null
        if (response.data.res[3].is_open == 1){
          step_name4_is_open = true
        } else {
          step_name4_is_open = false
        }
        this.step_name4 = {
          'name': response.data.res[3].name,
          'id': response.data.res[3].id,
          'is_open': step_name4_is_open
        }
        var step_name5_is_open = null
        if (response.data.res[4].is_open == 1){
          step_name5_is_open = true
        } else {
          step_name5_is_open = false
        }
        this.step_name5 = {
          'name': response.data.res[4].name,
          'id': response.data.res[4].id,
          'is_open': step_name5_is_open
        }
        this.loading = false
        // this.step_name6 = response.data[5].name
      })
    },
    switchBut(value){
      var is_open = ''
      if (value.is_open){
        is_open = 1
      } else {
        is_open = 0
      }
      lifeCycleSwitch({ 'id': value.id, 'is_open': is_open }).then(response => {
        if (response.code == 200) {
          this.$message({ type: 'success', message: response.message })
          this.lifeCycleAll()
        }
      }).catch((response) => {
        this.lifeCycleAll()
      })
    },
    preservation(value, type){
      this.$confirm('保存后将更新所有客户的该状态名称，是否确认保存', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        title: '修改客户状态',
        type: 'warning'
      }).then(() => {
        lifeCycleEdit(value).then(response => {
          if (response.code == 200) {
            this.$message({ type: 'success', message: response.message })
            this.lifeCycleAll()
            this[type] = true
          } else {
            this.$message({ type: 'error', message: response.message })
          }
        })
      }).catch(() => {

      })
    },

    switchTemplate(type){
      this.type = type
      if (this.type == 'configure'){
        this.loading = true
        getSysConfigByCategory({ 'category': 'customer' }).then(response => {
          this.configureData = response.data
          this.configureData.customer_allocation_rule.value = this.configureData.customer_allocation_rule.value.toString()
          this.loading = false
        })
      }
      if (this.type == 'clue'){
        this.loading = true
        getSysConfigByCategory({ 'category': 'thread' }).then(response => {
          this.threadeData = response.data
          this.threadeData.thread_duplicate_name.value = this.threadeData.thread_duplicate_name.value.toString()
          this.threadeData.thread_allocation_rule.value = this.threadeData.thread_allocation_rule.value.toString()
          this.loading = false
        })
      }
      if (this.type == 'label'){
        this.loading = true
        getAllCrmTags().then(response => {
          this.labelTableData = response.data
          this.loading = false
        })
      }
    },
    configureSubmit(){
      var data = ''
      if (this.type == 'configure'){
        data = this.configureData
      }
      if (this.type == 'clue'){
        data = this.threadeData
      }
      editConfig(data).then(response => {
        if (response.code == 200) {
          this.$message({ type: 'success', message: response.message })
        } else {
          this.$message({ type: 'error', message: response.message })
        }
      })
      // if (this.type == 'configure'){
      //   data = {
      //     'customer_receive_limit': this.configureData.customer_receive_limit.value,
      //     'customer_fall_seas': this.configureData.customer_fall_seas.value,
      //     'customer_forbidden_sale': this.configureData.customer_forbidden_sale.value,
      //     'customer_forbidden_sys': this.configureData.customer_forbidden_sys.value,
      //     'customer_allocation_rule': this.configureData.customer_allocation_rule.value
      //   }
      // }
      // if (this.type == 'clue'){
      //   data = {
      //     'thread_receive_limit': this.configureData.thread_receive_limit.value,
      //     'thread_fall_seas': this.configureData.thread_fall_seas.value,
      //     'thread_forbidden_sale': this.configureData.thread_forbidden_sale.value,
      //     'thread_forbidden_sys': this.configureData.thread_forbidden_sys.value,
      //     'thread_duplicate_name': this.configureData.thread_duplicate_name.value,
      //     'thread_allocation_rule': this.configureData.thread_allocation_rule.value,
      //   }
      // }
    },
    funAuditBatch(){
      this.form.id = 0
      this.form.name = ''
      this.form.order = 0
      this.dialog_type = 'add'
      this.title = '添加'
      this.dialogVisible = true
    },
    handleEdit(value){
      this.form.id = value.id
      this.form.name = value.name
      this.form.order = value.order
      this.dialog_type = 'edit'
      this.title = '编辑'
      this.dialogVisible = true
    },
    onAdd(formName){
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          addCrmTag(insertData).then(response => {
            if (response.code == 200) {
              this.$message({ type: 'success', message: response.message })
              this.switchTemplate(this.type)
              this.dialogVisible = false
            } else {
              this.$message({ type: 'error', message: response.message })
            }
          })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    onSubmit(formName){
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          editCrmTag(insertData).then(response => {
            if (response.code == 200) {
              this.$message({ type: 'success', message: response.message })
              this.switchTemplate(this.type)
              this.dialogVisible = false
            } else {
              this.$message({ type: 'error', message: response.message })
            }
          })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    handleDelete(id){
      this.$confirm('是否确定删除此标签?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        delCrmTag({ 'id': id }).then(response => {
          if (response.code == 200) {
            this.$message({ type: 'success', message: response.message })
            this.switchTemplate(this.type)
            this.dialogVisible = false
          } else {
            this.$message({ type: 'error', message: response.message })
          }
        })
      }).catch(() => {

      })
    },
    editConfig(){

    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep .el-form-item{
  max-width: 100%;
}
::v-deep .el-input-number{
  width: 350px;
}
.bortton-page{
  margin-top: 20px;
}
.bottoms_two{
  margin-top: 50px;
  margin-left: 20px;
  margin-bottom: 50px;
  .title1{
    font-size:18px;
  }
  .title2{
    color: #999;
  }
  ::v-deep .el-input__inner{
    width: 350px;
  }
}
.step_input{
  display: inline-block;
  width: 380px;
}
.step_input1{
  display: inline-block;
  width: 500px;
  margin-left: 20px;
}
.step{
  ::v-deep .el-input__inner{
    border: none;
  }
  .step_box{
      .step_num{
         width:30px;
         height:30px;
         color: white;
         text-align: center;
         line-height: 30px;
         border-radius: 20px;
         background: #0a79eb;
         position: relative;
         display: inline-block;
         &:before{
           content: '';
           position: absolute;
           top: 40px;
           left: 14px;
           width: 2px;
           height: 30px;
           background: #999;
         }
      }
      .step_input_button1{
        margin-left: 20px;
        border-radius: 6px;
        padding-right: 15px;
        border: 1px solid #338aff;
        box-shadow: 0 0 8px rgb(151 171 185 / 16%);
        display: inline-block;
        .step_button{
          font-size: 14px;
          display: inline-block;
          margin-left: 20px;
          span{
            color: rgb(30, 136, 229);
            cursor:pointer;
          }

        }
      }
      .step_input_button{
        margin-left: 20px;
        border-radius: 6px;
        padding-right: 15px;
        border: 1px solid #DCDFE6;
        display: inline-block;
        .step_button{
          font-size: 14px;
          display: inline-block;
          margin-left: 20px;
          span{
            color: rgb(30, 136, 229);
            cursor:pointer;
          }

        }
      }
      &:last-child .step_num:before{
         display: none;
      }
    margin-top: 40px;
  }
  margin-top: 30px;
}
.bottoms{
  margin-top: 50px;
  margin-left: 20px;
  margin-bottom: 50px;
  .title1{
    font-size:18px;
  }
  .title2{
    color: #999;
  }
}
.box{
  .button{
    flex-shrink: 0;
    div{
      width: 160px;
      height: 40px;
      display: inline-block;
      background-color: #d4d4da30;
      border-radius: 5px;
      text-align: center;
      margin-top: 10px;
      font-size: 13px;
      line-height: 40px;
      cursor: pointer;
      color: #00000087;
      font-weight: bold;
      margin-right: 30px;
    }
    .selectButton{
      width: 160px;
      background-color: #0a79eb;
      color: #FFFFFF;
      font-weight: bold;
    }
  }
}
</style>
