<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>企业推广</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="type"
          placeholder="不限推广方案"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限推广方案" value="" />
          <el-option label="职位置顶" value="jobstick" />
          <el-option label="紧急招聘" value="emergency" />
        </el-select>
        <el-select
          v-model="settr"
          placeholder="不限到期时间"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限到期时间" value="" />
          <el-option label="三天内到期" value="3" />
          <el-option label="一周内到期" value="7" />
          <el-option label="一月内到期" value="30" />
          <el-option label="三月内到期" value="90" />
        </el-select>
        <el-select
          v-model="sort"
          placeholder="按开通时间排序"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="按开通时间排序" value="" />
          <el-option label="按到期时间排序" value="1" />
        </el-select>
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
            <el-option label="职位名称" value="1" />
            <el-option label="企业名称" value="2" />
            <el-option label="职位ID" value="3" />
            <el-option label="企业ID" value="4" />
            <el-option label="会员UID" value="5" />
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
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="42" />
        <el-table-column label="所属企业" prop="companyname" min-width="220" />
        <el-table-column label="推广职位" prop="jobname" min-width="170" />
        <el-table-column align="center" label="推广类型" min-width="80">
          <template slot-scope="scope">
            {{ scope.row.type == 'jobstick' ? '置顶' : '紧急' }}
          </template>
        </el-table-column>
        <el-table-column align="center" label="推广天数" min-width="80">
          <template slot-scope="scope">
            {{ scope.row.days }}天
          </template>
        </el-table-column>
        <el-table-column align="center" label="开始时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="结束时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.deadline | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="170">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="funEdit(scope.row)">
              编辑
            </el-button>
            <el-button size="small" type="danger" @click="funCancel(scope.row)">
              取消推广
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="funAdd">
            添加推广
          </el-button>
          <el-button size="small" type="primary" @click="funRefresh">
            刷新职位
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right;">
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
    <el-dialog v-if="dialogVisible" :title="dialogTitle" :visible.sync="dialogVisible" width="35%">
      <dia_add
        v-if="itemInfo === null"
        @setDialogVisible="closeDialog"
        @pageReload="fetchData"
      />
      <dia_edit
        v-else
        :item-info="itemInfo"
        @setDialogVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import dia_add from './add.vue'
import dia_edit from './edit.vue'
import {
  jobPromotionList,
  jobPromotionCancel
} from '@/api/promotion_job'
import { parseTime } from '@/utils/index'
import { jobRefresh } from '@/api/job'

export default {
  components: {
    dia_add,
    dia_edit
  },
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data() {
    return {
      dialogTitle: '',
      dialogVisible: false,
      itemInfo: {},
      loading: false,
      options: [],
      settr: '',
      type: '',
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      rules: {},
      sort: '',
      tableIdarr: [],
      tableUidarr: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const params = {
        type: this.type,
        settr: this.settr,
        key_type: this.key_type,
        keyword: this.keyword,
        sort: this.sort,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      jobPromotionList(params)
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
    funSearch() {
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    goto(target) {
      this.$router.push(target)
    },
    funAdd() {
      this.itemInfo = null
      this.dialogTitle = '添加推广'
      this.dialogVisible = true
    },
    funEdit(info) {
      this.itemInfo = { ...info }
      this.dialogTitle = '编辑推广'
      this.dialogVisible = true
    },
    closeDialog() {
      this.dialogVisible = false
    },
    funCancel(row) {
      var that = this
      that
        .$confirm('确定取消推广吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          jobPromotionCancel(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      this.tableUidarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.pid)
          this.tableUidarr.push(item.uid)
        }
      }
    },
    funRefresh() {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要刷新的职位')
        return false
      }
      const param = {
        id: that.tableIdarr,
        uid: that.tableUidarr
      }
      jobRefresh(param).then(response => {
        that.$message.success(response.message)
        that.fetchData()
      })
    }
  }
}
</script>
