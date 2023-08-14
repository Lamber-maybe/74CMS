<template>
  <div class="app-container">
    <div class="tip">
      <p>
        不同的运营阶段您可以选择不同的设置。
      </p>
      <p>
        套餐到期后，套餐类型显示已过期，会员权限按套餐到期使用权限执行。套餐到期后会员资源不改变，无有效期限制。
      </p>
    </div>
    <el-divider content-position="left">套餐基础配置</el-divider>
    <el-form
      ref="form1"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="新注册会员赠送服务" required class="form_item_1">
        <el-radio-group v-model="form.reg_service">
          <el-radio v-for="(item, index) in list" :key="index" :label="item.id">
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="套餐到期前提醒" prop="meal_min_remind" class="form_item_2">
        <el-input v-model="form.meal_min_remind" class="small">
          <template slot="prepend">提前</template>
          <template slot="append">天</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          0为不提醒
        </span>
      </el-form-item>
      <el-form-item label="名企套餐" class="form_item_4">
        <el-checkbox-group v-model="form.famous_enterprises">
          <el-checkbox v-for="(item, index) in list" :key="index" :label="item.id">{{ item.name }}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form1')">保存</el-button>
      </el-form-item>
    </el-form>
    <el-divider content-position="left">重新开通套餐配置</el-divider>
    <el-form
      ref="form2"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="重开套餐时间处理" required>
        <el-radio-group v-model="form.reopen_setmeal_deadline">
          <el-radio label="1">叠加</el-radio>
          <el-radio label="0">不叠加</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="重开套餐资源处理" required>
        <el-radio-group v-model="form.reopen_setmeal_resource">
          <el-radio label="1">叠加</el-radio>
          <el-radio label="0">不叠加</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form2')">保存</el-button>
      </el-form-item>
    </el-form>
    <el-divider content-position="left">会员到期套餐配置</el-divider>
    <el-form
      ref="form3"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="套餐到期使用权限" class="form_item_3">
        <span
          style="color:#3a8ee6;cursor:pointer;"
          @click="fun_config_overtime"
        >
          [配置使用权限]
        </span>
      </el-form-item>
      <el-form-item label="套餐到期资源处理" required>
        <el-radio-group v-model="form.overtime_setmeal_resource">
          <el-radio label="1">保留</el-radio>
          <el-radio label="0">清空</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="套餐到期超出职位处理" required>
        <el-radio-group v-model="form.overtime_setmeal_jobnum">
          <el-radio label="1">保留全部职位</el-radio>
          <el-radio label="0">关闭超出职位</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form3')">保存</el-button>
      </el-form-item>
    </el-form>
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      :width="dialog_width"
      :close-on-click-modal="false"
    >
      <overtimeform
        @setDialogFormVisible="closeDialog"
      />
    </el-dialog>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
import { setmealList } from '@/api/setmeal'
import overtimeform from './overtimeform.vue'

export default {
  components: {
    overtimeform
  },
  data() {
    return {
      dialog_width: '56%',
      dialogTitle: '',
      dialogFormVisible: false,
      list: null,
      form: {
        reg_service: 1,
        meal_min_remind: 30,
        famous_enterprises: [],
        reopen_setmeal_deadline: 1,
        reopen_setmeal_resource: 1,
        overtime_setmeal_resource: 1,
        overtime_setmeal_jobnum: 1
      },
      rules: {
        meal_min_remind: [
          {
            required: true,
            message: '请输入套餐到期提前几天提醒',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      setmealList({})
        .then(response => {
          this.list = response.data.items
          return setConfig({}, 'get')
        })
        .then(response => {
          const { reg_service, meal_min_remind, famous_enterprises, reopen_setmeal_deadline, reopen_setmeal_resource, overtime_setmeal_resource, overtime_setmeal_jobnum } = { ...response.data }
          var famous_enterprises_arr = famous_enterprises == '' ? [] : famous_enterprises.split(',')
          this.form = {
            reg_service: parseInt(reg_service),
            meal_min_remind,
            famous_enterprises: [],
            reopen_setmeal_deadline,
            reopen_setmeal_resource,
            overtime_setmeal_resource,
            overtime_setmeal_jobnum
          }
          for (var iterator of famous_enterprises_arr) {
            this.form.famous_enterprises.push(parseInt(iterator))
          }
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {}
      if (formName === 'form1'){
        insertData.reg_service = this.form.reg_service
        insertData.famous_enterprises = this.form.famous_enterprises.length > 0 ? this.form.famous_enterprises.join(',') : ''
        insertData.meal_min_remind = this.form.meal_min_remind
      } else if (formName === 'form2') {
        insertData.reopen_setmeal_deadline = this.form.reopen_setmeal_deadline
        insertData.reopen_setmeal_resource = this.form.reopen_setmeal_resource
      } else {
        insertData.overtime_setmeal_resource = this.form.overtime_setmeal_resource
        insertData.overtime_setmeal_jobnum = this.form.overtime_setmeal_jobnum
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    fun_config_overtime() {
      this.dialog_width = '30%'
      this.dialogTitle = '套餐过期使用权限配置'
      this.dialogFormVisible = true
    }
  }
}
</script>
<style lang="scss" scoped>
.small {
  width: 240px;
}
.el-icon-info {
  color: #999;
  margin-left: 6px;
}
.el-divider {
  background: none;
}
.form_item_4{
  margin-top:20px;
}
.form_item_1 .el-form-item__label,
.form_item_4 .el-form-item__label{
  line-height: initial;
}
.form_item_2{
  margin-bottom: 5px;
}
.form_item_3{
  margin-bottom: 15px;
}
.form_item_1 .el-radio, .el-radio__input{
  line-height: 36px;
}
.form_item_4 .el-checkbox, .el-checkbox__input{
  line-height: 24px;
}
.form_item_4 .el-form-item__content{
  line-height: 24px;
}
.el-row {
  margin-bottom: 20px;
  margin-left: 40px;
  &:last-child {
    margin-bottom: 0;
  }
}
</style>
