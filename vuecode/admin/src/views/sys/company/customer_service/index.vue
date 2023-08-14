<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>客服配置</span>
      </div>
      <el-table
        v-loading="listLoading"
        element-loading-text="Loading"
        fit
        highlight-current-row
        :data="list"
        style="width: 100%"
      >
        <el-table-column label="姓名" min-width="100">
          <template slot-scope="scope">
            {{ scope.row.name }}
          </template>
        </el-table-column>
        <el-table-column prop="mobile" label="手机号" min-width="120" />
        <el-table-column prop="tel" label="座机" min-width="120" />
        <el-table-column prop="weixin" label="微信" min-width="120" />
        <el-table-column prop="qq" label="QQ" min-width="120" />
        <el-table-column label="状态" align="center" min-width="80">
          <template slot-scope="scope">
            <el-tag :type="scope.row.status | colorFilter">
              {{ scope.row.status == 1 ? '正常' : '暂停' }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="关联企业数" align="center" min-width="110">
          <template slot-scope="scope">
            <span>
              {{ scope.row.company_num }}
            </span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="150">
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
      <el-button size="small" type="primary" @click="funAdd">
        添加
      </el-button>
    </el-card>
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="30%"
      :close-on-click-modal="false"
    >
      <diaform
        :item-info="itemInfo"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaform from './form.vue'
import {
  customerServiceList,
  customerServiceDelete
} from '@/api/customer_service'

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
  components: {
    diaform
  },
  data() {
    return {
      detailContent: {},
      itemInfo: null,
      dialogTitle: '',
      dialogFormVisible: false,
      list: null,
      listLoading: true
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {}
      customerServiceList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
      })
    },
    funAdd(index, row) {
      this.itemInfo = null
      this.dialogTitle = '添加客服'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      if (row) {
        this.itemInfo = row
      }
      this.dialogTitle = '编辑客服'
      this.dialogFormVisible = true
    },
    funDelete(index, row) {
      var that = this
      that
        .$confirm('删除后该客服下的企业将变为无客服状态，确认删除吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          customerServiceDelete(param).then(response => {
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
