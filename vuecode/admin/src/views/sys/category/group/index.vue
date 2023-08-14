<template>
  <div class="app-container">
    <div class="tip">
      <p>
        系统默认分组不能删除！
      </p>
      <p>
        删除分组将会自动删除此分组下的子分类！
      </p>
    </div>
    <el-button size="small" type="primary" @click="funAdd">
      添加分类组
    </el-button>
    <div class="spaceline" />
    <el-table
      v-loading="listLoading"
      border
      size="mini"
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
    >
      <el-table-column label="分类组名称" prop="name" />
      <el-table-column label="调用名" prop="alias" />
      <el-table-column label="类型">
        <template slot-scope="scope">
          <el-tag :type="scope.row.is_sys == 1 ? 'info' : ''">
            {{ scope.row.is_sys | sysFilter }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" width="320">
        <template slot-scope="scope">
          <el-button size="small" @click="funManage(scope.$index, scope.row)">
            管理分类
          </el-button>
          <el-button
            v-if="scope.row.is_sys == 0"
            size="small"
            type="primary"
            @click="funEdit(scope.$index, scope.row)"
          >
            编辑
          </el-button>
          <el-button v-else size="small" type="primary" disabled>编辑</el-button>
          <el-button
            v-if="scope.row.is_sys == 0"
            size="small"
            type="danger"
            @click="funDelete(scope.$index, scope.row)"
          >
            删除
          </el-button>
          <el-button v-else size="small" type="danger" disabled>删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
    >
      <el-form
        ref="form"
        v-loading="infoLoading"
        :model="form"
        label-width="100px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="分类组名称" prop="name">
          <el-input
            v-model="form.name"
            autocomplete="off"
            style="width:300px"
          />
        </el-form-item>
        <el-form-item label="调用名称" prop="alias">
          <el-input
            v-model="form.alias"
            autocomplete="off"
            style="width:300px"
          />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="funSave('form')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  apiList,
  apiDelete,
  apiAdd,
  apiEdit
} from '@/api/config_category_group'

export default {
  filters: {
    sysFilter(is_sys) {
      const sysMap = {
        1: '系统分组',
        0: '自定义分组'
      }
      return sysMap[is_sys]
    }
  },
  data() {
    var validateAlias = (rule, value, callback) => {
      if (value.startsWith('QS_')) {
        callback(new Error('调用名不能以“QS_”开头'))
      } else {
        callback()
      }
    }
    return {
      dialogTitle: '',
      infoLoading: false,
      dialogFormVisible: false,
      form: {
        id: '',
        name: '',
        alias: ''
      },
      list: null,
      listLoading: true,
      rules: {
        name: [
          { required: true, message: '请输入分类组名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        alias: [
          { required: true, message: '请输入调用名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' },
          { validator: validateAlias, trigger: 'blur' }
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
      apiList(param).then(response => {
        this.list = response.data
        this.listLoading = false
      })
    },
    funAdd() {
      this.dialogTitle = '添加分类组'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.dialogTitle = '编辑分类组'
      this.dialogFormVisible = true
      this.infoLoading = true
      const param = { id: row.id }
      apiEdit(param, 'get').then(response => {
        this.form = { ...response.data }
        this.infoLoading = false
      })
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
          apiDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funSave(formName) {
      const insertData = { ...this.form }
      if (parseInt(insertData.id) > 0) {
        this.editSave(insertData, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        this.addSave(data, formName)
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiAdd(insertData).then(response => {
            this.$message.success(response.message)
            this.dialogFormVisible = false
            this.fetchData()
            this.form = {
              id: '',
              name: '',
              alias: ''
            }
            return true
          })
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiEdit(insertData).then(response => {
            this.$message.success(response.message)
            this.dialogFormVisible = false
            this.fetchData()
            this.form = {
              id: '',
              name: '',
              alias: ''
            }
            return true
          })
        } else {
          return false
        }
      })
    },
    funManage(index, row) {
      this.$router.push({
        path: '/sys/category/grouplist',
        query: {
          alias: row.alias,
          alias_cn: row.name
        }
      })
    }
  }
}
</script>
