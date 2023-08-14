<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>短链接列表</span>
      </div>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" />
        <el-table-column label="ID" prop="id" />
        <el-table-column label="短码" prop="code" />
        <el-table-column label="备注" prop="remark">
          <template slot-scope="scope">
            <span v-if="scope.row.remark" v-html="scope.row.remark" />
            <span v-if="scope.row.remark">-</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="到期时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span v-if="!scope.row.endtime">永久</span>
            <span v-else v-html="timeFilter(scope.row.endtime)" />
          </template>
        </el-table-column>
        <el-table-column align="center" label="创建时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ timeFilter(scope.row.addtime) }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="创建者">
          <template slot-scope="scope">
            <span v-if="scope.row.admin_id" v-html="scope.row.admin_name" />
            <span v-if="scope.row.admin_id<=0">系统</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="250">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="funEdit(scope.row)">
              编辑
            </el-button>
            <el-button size="small" @click="genQrcode(scope.row)">
              二维码
            </el-button>
            <el-button size="small" @click="copyShort(scope.row)">
              复制短链
            </el-button>
            <el-button size="small" type="danger" @click="funDelete(scope.row)">
              删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="funAdd">
            添加
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除
          </el-button>
        </el-col>
        <el-col v-if="total>pagesize" :span="16" style="text-align: right;">
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
    </el-card>
    <el-dialog
      :title="form.title"
      :visible.sync="form.show"
      min-width="45%"
      :close-on-click-modal="true"
      @close="form.show=false"
    >
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="20%"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item
          label="源地址"
          prop="url"
        >
          <el-input v-model="form.url" />
        </el-form-item>
        <el-form-item
          label="备注"
          prop="remark"
        >
          <el-input
            v-model="form.remark"
          />
        </el-form-item>
        <el-form-item
          label="过期时间"
          prop="endtime"
        >
          <el-date-picker
            v-model="form.endtime"
            type="date"
            placeholder="请选择过期时间"
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            class="width"
          />
        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            @click="onSubmit"
          >
            保存
          </el-button>
          <el-button @click="form.show=false">
            取 消
          </el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog
      title="短链接二维码"
      :visible.sync="qrcodeShow"
      width="300px"
      :close-on-click-modal="true"
      @close="qrcodeShow=false"
    >
      <div ref="qrcode" class="qrcode" />
    </el-dialog>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import QRCode from 'qrcodejs2'
import request from '@/utils/request'

export default {
  data() {
    return {
      tableIdarr: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      qrcodeShow: false,
      form: {
        title: '',
        show: false,
        url: '',
        remark: '',
        id: 0,
        endtime: ''
      },
      rules: {
        url: [
          {
            required: true,
            message: '请输入要压缩的URL地址',
            trigger: 'blur'
          },
          {
            pattern: /^(https?|ftp|file):\/\/[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|]$/,
            message: '请输入正确的URL地址',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    onSubmit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          const insertData = Object.assign({}, this.form)
          delete insertData.title
          delete insertData.show
          request({ url: '/short_url/save', method: 'post', data: insertData })
            .then(response => {
              this.$message.success(response.message)
              this.form.show = false
              this.fetchData()
            })
        }
      })
    },
    copyShort(row) {
      var oInput = document.createElement('input')
      oInput.value = this.config.sitedomain.trim('/') + '/s/' + row.code
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$message({ type: 'success', message: '复制成功' })
    },
    genQrcode(row) {
      this.qrcodeShow = true
      this.$nextTick(() => {
        this.$refs.qrcode.innerHTML = ''
        new QRCode(this.$refs.qrcode, {
          text: this.config.sitedomain.trim('/') + '/s/' + row.code,
          width: 260,
          height: 260,
          colorDark: '#000000',
          colorLight: '#ffffff',
          correctLevel: QRCode.CorrectLevel.H
        })
      })
    },
    timeFilter(timestamp) {
      if (parseInt(timestamp) == 0) return '永久'
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    },
    fetchData() {
      this.listLoading = true
      const params = {
        page: this.currentPage,
        pagesize: this.pagesize
      }
      request({ url: '/short_url/lists', params }).then(response => {
        this.list = response.data.list
        this.listLoading = false
        this.total = parseInt(response.data.total)
        this.currentPage = response.data.current_page
        this.pagesize = parseInt(response.data.pagesize)
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
    funAdd() {
      this.form = {
        show: true,
        url: '',
        remark: '',
        endtime: '',
        title: '添加短链接'
      }
    },
    funEdit(row) {
      this.form = {
        id: row.id,
        show: true,
        url: row.url,
        remark: row.remark,
        endtime: this.timeFilter(row.endtime),
        title: '编辑短链接'
      }
    },
    funDelete(row) {
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const data = {
            id: [row.id]
          }
          request({ url: '/short_url/del', method: 'post', data }).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {
        })
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
          const data = {
            id: that.tableIdarr
          }
          request({ url: '/short_url/del', method: 'post', data }).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {
        })
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
        }
      }
    }
  }
}
</script>
<style scoped lang="scss">
  .qrcode {
    display: inline-block;

    img {
      width: 132px;
      height: 132px;
      background-color: #fff; //设置白色背景色
      padding: 6px; // 利用padding的特性，挤出白边
      box-sizing: border-box;
    }
  }
</style>
