<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>积分管理</span>
      </div>
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
            <el-option label="企业ID" value="1" />
            <el-option label="企业名称" value="2" />
            <el-option label="会员UID" value="3" />
            <el-option label="会员手机号" value="4" />
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
      >
        <el-table-column label="企业名称" min-width="250">
          <template slot-scope="scope">
            <span>{{ scope.row.companyname }}【uid:{{ scope.row.uid }}】</span>
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          label="当前积分"
          prop="points"
          min-width="150"
        />
        <el-table-column label="联系人" min-width="180">
          <template slot-scope="scope">
            {{ scope.row.contact }}({{ scope.row.mobile }})
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="150">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
              编辑
            </el-button>
            <el-button size="small" @click="funLog(scope.row)">
              日志
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8" />
        <el-col :offset="8" :span="16" style="text-align: right;">
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
    </el-card>
    <el-dialog
      v-if="dialogFormVisible"
      title="编辑积分"
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
    <el-dialog
      v-if="dialogListVisible"
      title="积分变更日志"
      :visible.sync="dialogListVisible"
      width="50%"
      :close-on-click-modal="false"
      @close="closeListDialog"
    >
      <dialist :uid="uid" @setDialogFormVisible="closeListDialog" />
    </el-dialog>
  </div>
</template>

<script>
import dialist from './loglist.vue'
import diaform from './form.vue'
import { memberPointsList } from '@/api/member'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    diaform,
    dialist
  },
  data() {
    return {
      uid: 0,
      dialogListVisible: false,
      itemInfo: null,
      dialogFormVisible: false,
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    funLog(row) {
      this.uid = row.uid
      this.dialogListVisible = true
    },
    fetchData() {
      this.listLoading = true

      const param = {
        utype: 1,
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      memberPointsList(param)
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => {})
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
    goto(target) {
      this.$router.push(target)
    },
    funEdit(index, row) {
      this.itemInfo = row
      this.dialogFormVisible = true
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    closeListDialog() {
      this.dialogListVisible = false
    }
  }
}
</script>
