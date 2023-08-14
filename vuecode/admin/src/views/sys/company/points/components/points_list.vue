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
      <el-table-column prop="name" label="名称" width="180" />
      <el-table-column label="积分数">
        <template slot-scope="scope">{{ scope.row.points }}</template>
      </el-table-column>
      <el-table-column label="服务价格">
        <template slot-scope="scope">￥{{ scope.row.expense }}</template>
      </el-table-column>
      <el-table-column label="显示状态">
        <template slot-scope="scope">
          <el-tag :type="scope.row.is_display | colorFilter">
            {{ scope.row.is_display == 1 ? '显示' : '隐藏' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="推荐">
        <template slot-scope="scope">
          <el-tag :type="scope.row.recommend | colorFilter">
            {{ scope.row.recommend == 1 ? '是' : '否' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="排序">
        <template slot-scope="scope">
          <span>{{ scope.row.sort_id }}</span>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" width="220">
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
        <el-form-item label="积分数" prop="points">
          <el-input v-model.number="form.points" type="number" />
        </el-form-item>
        <el-form-item label="服务价格" prop="expense">
          <el-input v-model.number="form.expense" type="number" />
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
  servicePointsList,
  servicePointsDelete,
  servicePointsAdd,
  servicePointsEdit
} from '@/api/service_points'

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
    var checkNumber = (rule, value, callback) => {
      if (value <= 0) {
        return callback(new Error('值必须大于0'))
      } else {
        return callback()
      }
    }
    return {
      dialogTitle: '',
      dialogFormVisible: false,
      list: null,
      listLoading: true,
      form: {
        id: '',
        name: '',
        points: '',
        expense: '',
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
        points: [
          {
            required: true,
            message: '请输入积分数',
            trigger: 'blur'
          }
        ],
        expense: [
          {
            required: true,
            message: '请输入服务价格',
            trigger: 'blur'
          },
          { validator: checkNumber, trigger: 'blur' }
        ],
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
      servicePointsList(param)
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
        })
        .catch(() => {})
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          servicePointsAdd(insertData)
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
          servicePointsEdit(insertData)
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
        points: '',
        expense: '',
        is_display: true,
        recommend: true,
        sort_id: 0
      }
      this.dialogTitle = '添加套餐'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.form = {
        id: row.id,
        name: row.name,
        points: row.points,
        expense: row.expense,
        is_display: row.is_display == 1,
        recommend: row.recommend == 1,
        sort_id: row.sort_id
      }
      this.dialogTitle = '编辑套餐'
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
          servicePointsDelete(param)
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
