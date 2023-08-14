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
    <div class="spaceline" />
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
        <el-form-item label="排序" prop="sort_id">
          <el-input
            v-model.number="form.sort_id"
            autocomplete="off"
            style="width: 100px"
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
  imQuickmsgEdit
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
      form: {
        id: '',
        content: '',
        sort_id: 0
      },
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
      const param = {
        utype: 1
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
      if (insertData.id) {
        this.editSave(insertData, formName)
      } else {
        this.addSave(insertData, formName)
      }
    },
    editSave(insertData, formName) {
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
