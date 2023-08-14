<template>
  <div class="app-container">
    <div class="list-search">
      <el-input
        v-model="keyword"
        placeholder="请输入敏感词"
        class="input-with-select"
      >
        <el-button
          slot="append"
          icon="el-icon-search"
          @click="funSearchKeyword"
        />
      </el-input>
    </div>
    <div class="spaceline" />
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
      @selection-change="handleSelectionChange"
    >
      <el-table-column
        type="selection"
        width="42"
      />
      <el-table-column label="敏感词" prop="name" />
      <el-table-column label="替换词" prop="replace_text" />
      <el-table-column
        fixed="right"
        label="操作"
        width="220"
      >
        <template slot-scope="scope">
          <el-button
            size="small"
            type="primary"
            @click="funEdit(scope.row)"
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
    <el-row :gutter="20">
      <el-col :span="8">
        <el-button
          size="small"
          type="primary"
          @click="funAdd"
        >
          添加
        </el-button>
        <el-button
          size="small"
          type="primary"
          @click="funUploadImport"
        >
          导入
        </el-button>
        <el-button
          size="small"
          type="danger"
          @click="funDeleteBatch"
        >
          删除所选
        </el-button>
      </el-col>
      <el-col
        :span="16"
        style="text-align: right;"
      >
        <el-pagination
          background
          :current-page="currentPage"
          :page-sizes="[10,15, 20, 30, 40]"
          :page-size="pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </el-row>
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="25%"
      :close-on-click-modal="false"
    >
      <el-form ref="form" :model="form" status-icon :rules="rules" label-width="70px">
        <el-form-item label="敏感词" prop="name">
          <el-input v-model="form.name" type="text" autocomplete="off" style="max-width:300px;" />
        </el-form-item>
        <el-form-item label="替换词" prop="replace_text">
          <el-input v-model="form.replace_text" type="text" autocomplete="off" style="max-width:300px;" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog
      v-if="dialogImportVisible"
      title="导入敏感词"
      :visible.sync="dialogImportVisible"
      width="40%"
      :close-on-click-modal="false"
    >
      <div class="tip">
        <p>
          仅支持TXT文件
        </p>
        <p>
          格式：小姐姐(屏蔽的字、词)|xxx(替换的字、词、符号)
        </p>
        <p>
          多个关键字请换行处理！
        </p>
      </div>
      <el-form ref="form_import" :model="form_import" status-icon :rules="rules_import" label-width="70px">
        <el-form-item label="敏感词" prop="file">
          <el-upload
            action=""
            :auto-upload="false"
            :show-file-list="false"
            :on-change="readData"
          >
            <el-button plain size="mini">上传txt</el-button>
          </el-upload>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onImport('form_import')">导入</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { badwordList, badwordAdd, badwordEdit, badwordDelete, badwordImport } from '@/api/badword'

export default {
  data() {
    return {
      dialogImportVisible: false,
      dialogFormVisible: false,
      dialogTitle: '',
      form: {
        id: '',
        name: '',
        replace_text: ''
      },
      rules: {
        name: [
          { required: true, message: '请填写敏感词', trigger: 'blur' }
        ],
        replace_text: [
          { required: true, message: '请填写替换词', trigger: 'blur' }
        ]
      },
      tableIdarr: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      keyword: '',
      form_import: {
        file: ''
      },
      rules_import: {
        file: [
          { required: true, message: '请上传txt文件', trigger: 'blur' }
        ]
      },
      importList: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      badwordList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },

    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    funEdit(row) {
      this.form = row
      this.dialogFormVisible = true
      this.dialogTitle = '修改敏感词'
    },
    funAdd() {
      this.form = {}
      this.dialogFormVisible = true
      this.dialogTitle = '添加敏感词'
    },
    funAddSave(insertData){
      badwordAdd(insertData)
        .then(response => {
          this.$message.success(response.message)
          this.dialogFormVisible = false
          this.fetchData()
          return true
        })
        .catch(() => {})
    },
    funEditSave(insertData){
      badwordEdit(insertData)
        .then(response => {
          this.$message.success(response.message)
          this.dialogFormVisible = false
          this.fetchData()
          return true
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (that.form.id == '' || that.form.id === undefined){
            that.funAddSave(insertData)
          } else {
            that.funEditSave(insertData)
          }
        } else {
          return false
        }
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
          badwordDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funDeleteBatch() {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要删除的数据')
        return false
      }
      that
        .$confirm('此操作将永久删除选中的数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          badwordDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
        }
      }
    },
    onImport(formName) {
      const that = this
      this.$refs[formName].validate(valid => {
        if (valid) {
          badwordImport(that.importList)
            .then(response => {
              this.$message.success(response.message)
              this.dialogImportVisible = false
              this.fetchData()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    funUploadImport(){
      this.importList = []
      this.dialogImportVisible = true
    },
    readData(file) {
      const that = this
      if (file.raw.type !== 'text/plain'){
        that.$message.error('请上传txt文件')
        return
      }
      const reader = new FileReader()
      reader.readAsText(file.raw, 'gb2312')
      reader.onerror = e => {
        that.$message.error('读取文件错误')
        return
      }
      reader.onload = e => {
        const array = e.target.result.split('\n')
        const datalist = []
        array.forEach(element => {
          const item = element.split('|')
          datalist.push({ name: item[0], replace_text: item[1] })
        })
        if (datalist.length == 0){
          that.$message.error('没有读取到文件信息')
          return
        }
        that.importList = datalist
        that.form_import.file = file
        that.$message.success('上传成功')
      }
    }

  }
}
</script>
