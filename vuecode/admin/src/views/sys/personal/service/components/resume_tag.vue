<template>
  <div class="app-container">
    <el-table
      v-loading="listLoading"
      element-loading-text="Loading"
      fit
      highlight-current-row
      :data="list"
      style="width: 100%"
    >
      <el-table-column prop="name" label="名称" min-width="180" />
      <el-table-column label="服务时长" min-width="80">
        <template slot-scope="scope">{{ scope.row.days }}天</template>
      </el-table-column>
      <el-table-column label="服务价格" min-width="100">
        <template slot-scope="scope">￥{{ scope.row.expense }}</template>
      </el-table-column>
      <el-table-column label="积分抵扣" min-width="150">
        <template slot-scope="scope">
          <span v-if="scope.row.enable_points_deduct == 0">不允许抵扣</span>
          <span v-if="scope.row.enable_points_deduct == 1">允许全额兑换</span>
          <span v-if="scope.row.enable_points_deduct == 2">
            允许抵扣{{ scope.row.deduct_max }}元
          </span>
        </template>
      </el-table-column>
      <el-table-column label="显示状态" min-width="80">
        <template slot-scope="scope">
          <el-tag :type="scope.row.is_display | colorFilter">
            {{ scope.row.is_display == 1 ? '显示' : '隐藏' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="推荐" min-width="80">
        <template slot-scope="scope">
          <el-tag :type="scope.row.recommend | colorFilter">
            {{ scope.row.recommend == 1 ? '是' : '否' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="排序" min-width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.sort_id }}</span>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" min-width="150">
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="small"
            @click="funEdit(scope.$index, scope.row)"
          >
            编辑
          </el-button>
          <el-button
            size="small"
            type="danger"
            :disabled="form.reg_service == scope.row.id"
            @click="funDelete(scope.$index, scope.row)"
          >
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-button size="small" type="primary" @click="funAdd">添加</el-button>
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="30%"
      :close-on-click-modal="false"
    >
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="false"
      >
        <el-form-item label="服务名称" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="服务时长" prop="days">
          <el-input v-model.number="form.days" type="number" />
        </el-form-item>
        <el-form-item label="服务价格" prop="expense">
          <el-input v-model.number="form.expense" type="number" />
          元
        </el-form-item>
        <el-form-item label="积分抵扣" prop="enable_points_deduct">
          <el-radio-group v-model="form.enable_points_deduct">
            <el-radio label="0">不允许</el-radio>
            <el-radio label="1">全额兑换</el-radio>
            <el-radio label="2">部分抵扣</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="form.enable_points_deduct == 2"
          label="最大允许抵扣"
          prop="deduct_max"
        >
          <el-input v-model.number="form.deduct_max" type="number" />
          元
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" type="number" />
        </el-form-item>
        <el-form-item label="显示" prop="is_display">
          <el-switch v-model="form.is_display" />
        </el-form-item>
        <el-form-item label="推荐" prop="recommend">
          <el-switch v-model="form.recommend" />
        </el-form-item>
        <el-form-item label=" ">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="closeDialog">取 消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import {
  serviceResumeTagList,
  serviceResumeTagDelete,
  serviceResumeTagAdd,
  serviceResumeTagEdit
} from '@/api/service_resume_tag'

export default {
  filters: {
    colorFilter(val) {
      if (val == 1) {
        return 'success'
      } else {
        return 'danger'
      }
    }
  },
  data() {
    var checkDeductMax = (rule, value, callback) => {
      if (this.form.enable_points_deduct == 2) {
        if (!value) {
          return callback(new Error('请输入最大允许抵扣金额'))
        } else if (parseFloat(value) >= parseFloat(this.form.expense)) {
          return callback(new Error('抵扣金额必须小于服务价格'))
        }
      }
      return callback()
    }
    var checkNumber = (rule, value, callback) => {
      if (value <= 0) {
        return callback(new Error('值必须大于0'))
      } else {
        if (
          this.form.enable_points_deduct == 2 &&
          parseFloat(value) <= parseFloat(this.form.deduct_max)
        ) {
          return callback(new Error('服务价格必须大于抵扣金额'))
        }
        return callback()
      }
    }
    var checkInteger = (rule, value, callback) => {
      if (!Number.isInteger(value)) {
        callback(new Error('请输入数字值'))
      } else {
        if (value <= 0) {
          callback(new Error('值必须大于0'))
        } else {
          callback()
        }
      }
      return callback()
    }
    return {
      dialogTitle: '',
      dialogFormVisible: false,
      list: null,
      listLoading: true,
      form: {
        id: '',
        name: '',
        days: '',
        expense: '',
        enable_points_deduct: 0,
        deduct_max: '',
        is_display: true,
        recommend: true,
        sort_id: 0
      },
      rules: {
        name: [
          {
            required: true,
            message: '请输入服务名称',
            trigger: 'blur'
          }
        ],
        days: [
          {
            required: true,
            message: '请输入服务时长',
            trigger: 'blur'
          },
          { validator: checkInteger, trigger: 'blur' }
        ],
        expense: [
          {
            required: true,
            message: '请输入服务价格',
            trigger: 'blur'
          },
          { validator: checkNumber, trigger: 'blur' }
        ],
        deduct_max: [{ validator: checkDeductMax, trigger: 'blur' }],
        sort_id: [
          {
            required: true,
            message: '请输入排序',
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
      const param = {}
      serviceResumeTagList(param)
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
        })
        .catch(() => {})
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          serviceResumeTagAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.fetchData()
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
          serviceResumeTagEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.fetchData()
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
      insertData.is_display = insertData.is_display === true ? 1 : 0
      insertData.recommend = insertData.recommend === true ? 1 : 0
      if (parseInt(insertData.id) > 0) {
        that.editSave(insertData, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        that.addSave(data, formName)
      }
    },
    funAdd(index, row) {
      this.form = {
        id: '',
        name: '',
        days: '',
        expense: '',
        enable_points_deduct: '0',
        deduct_max: '',
        is_display: true,
        recommend: true,
        sort_id: 0
      }
      this.dialogTitle = '添加服务'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.form = {
        id: row.id,
        name: row.name,
        days: row.days,
        expense: row.expense,
        enable_points_deduct: row.enable_points_deduct + '',
        deduct_max: row.deduct_max,
        is_display: row.is_display == 1,
        recommend: row.recommend == 1,
        sort_id: row.sort_id
      }
      this.dialogTitle = '编辑服务'
      this.dialogFormVisible = true
    },
    funDelete(index, row) {
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          serviceResumeTagDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    closeDialog() {
      this.dialogFormVisible = false
    }
  }
}
</script>
<style scoped>
.el-input,
.el-input-group {
  width: 70%;
}
</style>
