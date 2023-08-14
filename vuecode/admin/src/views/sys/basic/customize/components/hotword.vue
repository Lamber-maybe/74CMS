<template>
  <div class="app-container">
    <el-form
      ref="config"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="180px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="显示方式">
        <el-radio-group v-model="configForm.hotword_display_method">
          <el-radio label="0">默认排序</el-radio>
          <el-radio label="1">随机排序</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmitConfig('config')"
          >保存</el-button
        >
      </el-form-item>
    </el-form>
    <div class="list-search" style="display: inline-block">
      <el-input
        v-model="keyword"
        placeholder="请输入搜索关键词"
        class="input-with-select"
        @keyup.enter.native="funSearchKeyword"
      >
        <el-select
          slot="prepend"
          v-model="key_type"
          placeholder="请选择"
          class="input-sel"
        >
          <el-option label="关键词" value="1" />
        </el-select>
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
      border
      size="mini"
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
    >
      <el-table-column label="关键词" prop="word" class="mini" />
      <el-table-column prop="hot" label="排序" width="180">
        <template slot-scope="scope">
          <el-input v-model="scope.row.hot" size="mini" class="mini" />
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
    <el-row :gutter="20">
      <el-col :span="8">
        <el-button size="small" type="primary" @click="funAdd">添加</el-button>
        <el-button size="small" type="primary" @click="onSubmit()"
          >保存</el-button
        >
      </el-col>
      <el-col :span="16" style="text-align: right">
        <el-pagination
          background
          :current-page="currentPage"
          :page-sizes="[10, 20, 50, 100]"
          :page-size="pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </el-row>
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
        <el-form-item label="关键词" prop="word">
          <el-input
            v-model="form.word"
            autocomplete="off"
            style="width: 300px"
          />
        </el-form-item>
        <el-form-item label="排序" prop="hot">
          <el-input
            v-model.number="form.hot"
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
  hotwordList,
  hotwordDelete,
  hotwordAdd,
  hotwordEdit,
  hotwordSaveAll
} from '@/api/hotword'
import { setConfig } from '@/api/configuration'

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
        word: '',
        hot: 0
      },
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 20,
      key_type: '1',
      keyword: '',
      configForm: {
        hotword_display_method: ''
      },
      rules: {
        word: [
          { required: true, message: '请输入关键词', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        hot: [
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
      setConfig({}, 'get')
        .then(response => {
          const {
            hotword_display_method
          } = { ...response.data }
          this.configForm = {
            hotword_display_method
          }
          const param = {
            key_type: this.key_type,
            keyword: this.keyword,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return hotwordList(param)
        })
        .then(response => {
          this.list = response.data.items
          this.saveData = [...this.list]
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize

        })
        .catch(() => { })
    },
    funAdd() {
      this.dialogTitle = '添加关键词'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.dialogTitle = '修改关键词'
      this.dialogFormVisible = true
      this.infoLoading = true
      const param = { id: row.id }
      hotwordEdit(param, 'get')
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
          hotwordDelete(param)
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
          hotwordAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.dialogFormVisible = false
              this.fetchData()
              this.form = {
                id: '',
                word: '',
                hot: ''
              }
              return true
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
          hotwordEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.dialogFormVisible = false
              this.fetchData()
              this.form = {
                id: '',
                word: '',
                hot: ''
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
      hotwordSaveAll(insertData)
        .then(response => {
          that.$message.success(response.message)
          this.fetchData()
          return true
        })
        .catch(() => { })
    },
    onSubmitConfig(formName) {
      const insertData = { ...this.configForm }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => { })
        } else {
          return false
        }
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
