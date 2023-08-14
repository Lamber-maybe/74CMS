<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>快捷语设置</span>
      </div>
      <div class="tip">
        <p>
          74IM是国内首家专业的即时通讯云服务提供商，专注为互联网、移动互联网开发者提供即时通讯基础能力和云端服务
          <a
            href="http://imcenter.74cms.com/"
            target="_blank"
            style="color: rgb(30, 136, 229);"
          >
            马上申请
          </a>
        </p>
      </div>
      <el-table
        v-loading="listLoading"
        element-loading-text="Loading"
        fit
        highlight-current-row
        :data="list"
        style="width: 100%;"
      >
        <el-table-column
          prop="content"
          label="内容"
          min-width="300"
        />
        <el-table-column label="会员类型">
          <template slot-scope="scope">
            {{ scope.row.utype == 1 ? '企业' : '求职者' }}
          </template>
        </el-table-column>
        <el-table-column label="排序">
          <template slot-scope="scope">
            <span>{{ scope.row.sort_id }}</span>
          </template>
        </el-table-column>
        <el-table-column
          fixed="right"
          label="操作"
          width="160"
        >
          <template slot-scope="scope">
            <el-button
              type="primary"
              size="small"
              @click="funEdit(scope.$index, scope.row)"
            >
              修改
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
        </el-col>
        <el-col
          :span="16"
          style="text-align: right;"
        >
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
        v-if="dialogFormVisible"
        :title="dialogTitle"
        :visible.sync="dialogFormVisible"
        width="40%"
        :close-on-click-modal="false"
      >
        <el-form
          ref="form"
          class="common-form"
          :model="form"
          label-width="120px"
          :rules="rules"
          :inline-message="true"
        >
          <el-form-item label="会员类型">
            <el-radio-group v-model="form.utype">
              <el-radio label="1">
                企业
              </el-radio>
              <el-radio label="2">
                求职者
              </el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item
            label="内容"
            prop="content"
          >
            <el-input
              v-model="form.content"
              class="large"
            />
          </el-form-item>
          <el-form-item
            label="排序"
            prop="sort_id"
          >
            <el-input
              v-model.number="form.sort_id"
              type="number"
              class="small"
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
      </el-dialog>
    </el-card>
  </div>
</template>

<script>
import {
  quickmsgList,
  quickmsgAdd,
  quickmsgEdit,
  quickmsgDelete
} from '@/api/im'

export default {
  data() {
    return {
      dialogTitle: '',
      dialogFormVisible: false,
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      form: {
        id: 0,
        utype: '1',
        content: '',
        sort_id: 0
      },
      rules: {
        content: [
          {
            required: true,
            message: '请输入内容',
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
      quickmsgList(param)
        .then(response => {
          this.list = response.data.items
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
          this.listLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (insertData.id > 0) {
            quickmsgEdit(insertData)
              .then(response => {
                this.$message.success(response.message)
                this.closeDialog()
                this.fetchData()
                return true
              })
              .catch(() => {})
          } else {
            quickmsgAdd(insertData)
              .then(response => {
                this.$message.success(response.message)
                this.closeDialog()
                this.fetchData()
                return true
              })
              .catch(() => {})
          }
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
    funAdd(index, row) {
      this.form = {
        id: 0,
        utype: '1',
        content: '',
        sort_id: 0
      }
      this.dialogTitle = '添加'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.form = { ...row }
      this.form.utype = this.form.utype + ''
      this.dialogTitle = '编辑'
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
          quickmsgDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
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
.el-input.large {
  width: 500px;
}
.el-input.small {
  width: 200px;
}
</style>
