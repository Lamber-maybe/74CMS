<template>
  <div class="app-container">
    <div class="tip">
      <p>
        正式使用后删除分类会导致与此分类关联的信息类别丢失，修改不会受影响。
      </p>
    </div>
    <el-button size="small" type="primary" @click="funAdd">添加</el-button>
    <el-button size="small" type="primary" @click="onSubmit()">保存</el-button>
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
      <el-table-column :label="'分类名称（' + aliasCn + '）'">
        <template slot-scope="scope">
          <el-input v-model="scope.row.name" size="mini" class="small" />
          (ID:{{ scope.row.id }})
        </template>
      </el-table-column>
      <el-table-column prop="sort_id" label="排序" width="180">
        <template slot-scope="scope">
          <el-input v-model="scope.row.sort_id" size="mini" class="mini" />
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" width="320">
        <template slot-scope="scope">
          <el-button
            size="small"
            type="primary"
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
    <el-dialog
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="30%"
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
        <el-form-item label="分类名称" prop="name">
          <el-input
            v-model="form.name"
            autocomplete="off"
            style="width:300px"
          />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input
            v-model.number="form.sort_id"
            autocomplete="off"
            style="width:100px"
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
  apiEdit,
  apiTableSave
} from '@/api/config_category'

export default {
  data() {
    return {
      saveData: [],
      aliasCn: '',
      alias: '',
      dialogTitle: '',
      infoLoading: false,
      dialogFormVisible: false,
      form: {
        id: '',
        name: '',
        sort_id: 0
      },
      list: null,
      listLoading: true,
      rules: {
        name: [
          { required: true, message: '请输入分类组名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        sort_id: [
          { type: 'number', message: '排序必须为数字', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.alias = this.$route.query.alias
    this.aliasCn = this.$route.query.alias_cn
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        alias: this.alias
      }
      apiList(param)
        .then(response => {
          this.list = response.data
          this.saveData = [...this.list]
          this.listLoading = false
        })
        .catch(() => {})
    },
    funAdd() {
      this.dialogTitle = `添加${this.aliasCn}`
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.dialogTitle = `编辑${this.aliasCn}`
      this.dialogFormVisible = true
      this.infoLoading = true
      const param = { id: row.id }
      apiEdit(param, 'get')
        .then(response => {
          this.form = { ...response.data }
          this.infoLoading = false
        })
        .catch(() => {})
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
          apiDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    funSave(formName) {
      const insertData = { ...this.form }
      insertData.alias = this.alias
      if (parseInt(insertData.id) > 0) {
        const { alias, ...data } = insertData
        console.log(alias)
        this.editSave(data, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        this.addSave(data, formName)
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.dialogFormVisible = false
              this.fetchData()
              this.form = {
                id: '',
                name: '',
                sort_id: ''
              }
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
          apiEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.dialogFormVisible = false
              this.fetchData()
              this.form = {
                id: '',
                name: '',
                sort_id: ''
              }
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    onSubmit() {
      var that = this
      const insertData = [...that.saveData]
      apiTableSave(insertData)
        .then(response => {
          that.$message.success(response.message)
          this.fetchData()
          return true
        })
        .catch(() => {})
    }
  }
}
</script>
