<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>委托投递</span>
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
        >
          <el-select
            slot="prepend"
            v-model="key_type"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="姓名" value="1" />
            <el-option label="UID" value="2" />
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
        <el-table-column label="求职者姓名">
          <template slot-scope="scope">
            <el-link
              :href="scope.row.resume_link"
              target="_blank"
              type="primary"
            >
              {{ scope.row.fullname }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="基本信息" width="400">
          <template slot-scope="scope">
            <span>{{ scope.row.age }}</span>
            /
            <span>{{ scope.row.sex_ }}</span>
            /
            <span>{{ scope.row.education_ }}</span>
            /
            <span>{{ scope.row.experience_ }}</span>
          </template>
        </el-table-column>
        <el-table-column label="委托期限">
          <template slot-scope="scope">
            {{ scope.row.days }}天
          </template>
        </el-table-column>
        <el-table-column align="center" label="简历创建时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="简历刷新时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.refreshtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="handlerMatch(scope.row)"
            >
              匹配
            </el-button>
            <el-button
              size="small"
              type="primary"
              @click="handlerRefresh(scope.row)"
            >
              刷新
            </el-button>
            <el-button size="small" type="danger" @click="handlerDelete(scope.row)">
              删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="handlerRefreshBatch">
            刷新
          </el-button>
          <el-button size="small" type="danger" @click="handlerDeleteBatch">
            删除
          </el-button>
        </el-col>
        <el-col :offset="8" :span="16" style="text-align: right;">
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
      v-if="showMatchWindow"
      title="匹配职位"
      :visible.sync="showMatchWindow"
      width="50%"
      :close-on-click-modal="false"
      @close="showMatchWindow=false"
    >
      <match :resume-id="resumeId" @setDialogFormVisible="showMatchWindow=false" />
    </el-dialog>
  </div>
</template>

<script>
import match from './match.vue'
import { entrustList, entrustDelete } from '@/api/entrust'
import { parseTime } from '@/utils/index'
import { resumeRefresh } from '@/api/resume'

export default {
  components: {
    match
  },
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      tableIdarr: [],
      resumeId: 0,
      showMatchWindow: false,
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
    fetchData() {
      this.listLoading = true
      const param = {
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      entrustList(param).then(response => {
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
    handlerMatch(row){
      this.resumeId = row.resume_id
      this.showMatchWindow = true
    },
    handlerRefresh(row){
      var that = this
      const param = {
        id: row.resume_id
      }
      resumeRefresh(param).then(response => {
        that.$message.success(response.message)
        that.fetchData()
      })
    },
    handlerRefreshBatch(){
      var that = this
      if (that.tableIdarr.length == 0){
        that.$message.error('请选择要刷新的简历')
        return false
      }
      const arr = []
      this.list.forEach(element => {
        if (that.tableIdarr.indexOf(element.id) !== -1){
          arr.push(element.resume_id)
        }
      })
      const param = {
        id: arr
      }
      resumeRefresh(param).then(response => {
        that.$message.success(response.message)
        that.fetchData()
      })
    },
    handlerDelete(row){
      var that = this
      that
        .$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          entrustDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    handlerDeleteBatch(){
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要删除的信息')
        return false
      }
      var that = this
      that
        .$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          entrustDelete(param).then(response => {
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
    }
  }
}
</script>
<style scoped>
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
</style>
