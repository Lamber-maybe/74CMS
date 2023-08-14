<template>
  <div class="app-container">
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item
        label="优惠券名称"
        prop="name"
      >
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item
        label="抵扣金额"
        prop="face_value"
      >
        <el-input
          v-model.number="form.face_value"
          type="number"
        />
      </el-form-item>
      <el-form-item
        label="绑定套餐"
        prop="bind_setmeal_id"
      >
        <el-select
          v-model="form.bind_setmeal_id"
          placeholder="请选择绑定套餐"
        >
          <el-option
            v-for="(item, index) in options_setmeal"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        label="有效期"
        prop="days"
      >
        <el-input
          v-model.number="form.days"
          type="number"
        />
      </el-form-item>
      <el-form-item label=" ">
        <el-button
          type="primary"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <el-button @click="closeDialog">
          取 消
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { couponAdd, couponEdit } from '@/api/coupon'
import { getClassify } from '@/api/classify'

export default {
  props: ['itemInfo'],
  data() {
    var validateFaceValue = (rule, value, callback) => {
      if (value < 0.01) {
        callback(new Error('抵扣金额最小为0.01元'))
      } else {
        callback()
      }
    }
    var validateDays = (rule, value, callback) => {
      if (value < 1) {
        callback(new Error('有效期最小为1天'))
      } else {
        callback()
      }
    }
    return {
      options_setmeal: [],
      form: {
        id: 0,
        name: '',
        face_value: '',
        bind_setmeal_id: '',
        days: ''
      },
      rules: {
        name: [
          {
            required: true,
            message: '请输入优惠券名称',
            trigger: 'blur'
          },
          {
            max: 30,
            message: '最大 30 个字符',
            trigger: 'blur'
          }
        ],
        face_value: [
          {
            required: true,
            message: '请输入抵扣金额',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '请正确输入抵扣金额',
            trigger: 'blur'
          },
          { validator: validateFaceValue, trigger: 'blur' }
        ],
        days: [
          {
            required: true,
            message: '请输入有效期',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '请正确输入有效期',
            trigger: 'blur'
          },
          { validator: validateDays, trigger: 'blur' }
        ],
        bind_setmeal_id: [
          {
            required: true,
            message: '请选择绑定套餐',
            trigger: 'change'
          }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      getClassify({ type: 'setmealList' })
        .then(response => {
          this.options_setmeal = response.data
        })
        .catch(() => {})
      if (this.item_info !== null) {
        this.form = { ...this.form, ...this.item_info }
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          couponAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          couponEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      if (parseInt(insertData.id) > 0) {
        that.editSave(insertData, formName)
      } else {
        that.addSave(insertData, formName)
      }
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
.el-select,
.el-input {
  width: 300px;
}
</style>
