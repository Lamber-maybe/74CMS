<template>
  <div class="app-container">
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
    >
      <el-table-column label="常用语" prop="content" class="mini" />
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
    <div class="spaceline" />
    <el-button
      size="small"
      type="primary"
      @click="funAdd()"
    >
      添加
    </el-button>
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
        <el-form-item label="常用语" prop="content">
          <el-input
            v-model="form.content"
            autocomplete="off"
            style="width: 300px"
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
  imQuickmsgList,
  imQuickmsgDelete,
  imQuickmsgEdit,
  imQuickmsgSaveAll,
  imQuickmsgAdd
} from '@/api/im'

export default {
  data() {
    return {
      saveData: [],
      aliasCn: '',
      alias: '',
      dialogTitle: '',
      infoLoading: false,
      dialogFormVisible: false,
      form: {},
      list: null,
      listLoading: true,
      key_type: '1',
      keyword: '',
      configForm: {
        hotword_display_method: ''
      },
      rules: {
        content: [
          { required: true, message: '请输入常用语', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
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
      const param = {
        utype: 2
      }
      imQuickmsgList(param, 'get')
        .then(response => {
          this.list = response.data
          this.listLoading = false
        })
        .catch(() => { })
    },
    funEdit(index, row) {
      this.dialogTitle = '修改常用语'
      this.dialogFormVisible = true
      this.infoLoading = true
      const param = { id: row.id }
      imQuickmsgEdit(param, 'get')
        .then(response => {
          this.form = { ...response.data }
          this.infoLoading = false
        })
        .catch(() => { })
    },
    funAdd() {

      this.dialogTitle = '添加常用语'
      if (this.list.length >= 15)
      {
        this.dialogFormVisible = false
        this.$message.error('常用语最多15条');
      }
      else
      {
        this.dialogFormVisible = true
      }
      this.form = {}
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
          imQuickmsgDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => { })
        })
        .catch(() => { })
    },
    funSave(formName) {
      const insertData = { ...this.form }
      if (parseInt(insertData.id) > 0) {
        this.editSave(insertData, formName)
      } else {
        this.addSave(insertData, formName)
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.utype = 2
          imQuickmsgAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.dialogFormVisible = false
              this.fetchData()
            })
            .catch(() => { })
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          imQuickmsgEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.dialogFormVisible = false
              this.fetchData()
              this.form = {
                id: '',
                content: '',
                sort_id: ''
              }
              return true
            })
            .catch(() => { })
        } else {
          return false
        }
      })
    },
    onSubmit() {
      var that = this
      const insertData = [...that.saveData]
      imQuickmsgSaveAll(insertData)
        .then(response => {
          that.$message.success(response.message)
          this.fetchData()
          return true
        })
        .catch(() => { })
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    funSearch() {
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    }
  }
}
</script>
