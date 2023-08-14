<template>
  <div>
    <div class="list-search">
      <el-input
        v-model="keyword"
        placeholder="请输入搜索内容"
        class="input-with-select"
        @keyup.enter.native="funSearchKeyword"
      >
        <el-select
          slot="prepend"
          v-model="key_type"
          placeholder="请选择"
          class="input-sel"
        >
          <el-option label="手机号" value="1" />
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
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
      style="width: 100%"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="42" />
      <el-table-column prop="mobile" label="电话号" />
      <el-table-column align="center" label="添加时间">
        <template slot-scope="scope">
          <i class="el-icon-time" />
          <span>{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column prop="note" label="备注" />
      <el-table-column prop="date" label="操作">
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="small"
            @click="funEdit(scope.row)"
          >编辑</el-button>
          <el-button
            type="danger"
            size="small"
            @click="funDelete(scope.row)"
          >删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-row :gutter="20">
      <el-col :span="8">
        <el-button type="primary" size="small" @click="funAdd">添加</el-button>
        <el-button size="small" type="danger" @click="funDeleteBatch">
          删除
        </el-button>
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
      width="25%"
    >
      <div class="dialog_box">
        <el-form ref="form" :rules="rules" :model="form" label-width="80px">
          <el-form-item label="电话号" prop="mobile">
            <el-input v-model="form.mobile" />
          </el-form-item>
          <el-form-item label="备注" prop="note">
            <el-input
              v-model="form.note"
              type="textarea"
              rows="3"
              resize="none"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit('form')">保存</el-button>
            <el-button @click="dialogFormVisible = false">取 消</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { smsBlacklist, smsBlacklistAdd, smsBlacklistEdit, smsBlacklistDelete } from '@/api/sms_blacklist'
import { parseTime } from '@/utils/index'
export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      form: {
        id: 0,
        mobile: '',
        note: ''
      },
      rules: {
        mobile: [
          {
            required: true,
            message: '请输入手机号',
            trigger: 'blur'
          }
        ]
      },
      dialogFormVisible: false,
      dialogTitle: '',
      tableIdarr: [],
      regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      smsBlacklist(param)
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => { })
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      if (!this.regularMobile.test(insertData.mobile)) {
        this.$message.error('手机号格式不正确')
        return false
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (insertData.id == 0) {
            smsBlacklistAdd(insertData)
              .then(response => {
                this.$message.success(response.message)
                this.dialogFormVisible = false
                this.fetchData()
                return true
              })
              .catch(() => { })
          } else {
            smsBlacklistEdit(insertData)
              .then(response => {
                this.$message.success(response.message)
                this.dialogFormVisible = false
                this.fetchData()
                return true
              })
              .catch(() => { })
          }
        } else {
          return false
        }
      })
    },
    funAdd(index, row) {
      this.form = {
        id: 0,
        mobile: '',
        note: ''
      }
      this.dialogTitle = '添加黑名单号码'
      this.dialogFormVisible = true
    },
    funEdit(row) {
      this.form = JSON.parse(JSON.stringify(row))
      this.dialogTitle = '编辑黑名单号码'
      this.dialogFormVisible = true
    },
    funDelete(row) {
      var that = this
      that
        .$confirm('从黑名单中删除此号码将会解除对该号码的限制，是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: [row.id]
          }
          smsBlacklistDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => { })
        })
        .catch(() => { })
    },
    funDeleteBatch() {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要删除的手机号')
        return false
      }
      that
        .$confirm('从黑名单中删除此号码将会解除对该号码的限制，是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          smsBlacklistDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => { })
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
        }
      }
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    }
  }
}
</script>

<style lang="scss" scoped>
.dialog_box {
  padding: 20px 20px 0;
  .date_item {
    width: 100%;
  }
}
.handle_box {
  padding-top: 15px;
}
</style>
