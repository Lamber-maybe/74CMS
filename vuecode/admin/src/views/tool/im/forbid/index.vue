<template>
  <div class="app-container">
    <div class="tip">
      <p>
        已禁聊会员无法使用在线聊天功能，系统提示 “ 抱歉，您暂时无法使用此功能哦 ”
      </p>
    </div>
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
      <el-table-column label="禁聊会员" prop="username" class="mini" />
      <el-table-column label="会员类型" prop="utype" class="mini">
        <template slot-scope="scope">
          {{ scope.row.utype==1?'企业':'个人' }}
        </template>
      </el-table-column>
      <el-table-column label="账号状态 " prop="status" class="mini">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.status == 1" type="success">正常</el-tag>
          <el-tag v-else type="danger">已锁定</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="屏蔽时间" prop="addtime" class="mini">
        <template slot-scope="scope">
          <i class="el-icon-time" />
          <span>{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="禁聊原因 " prop="reason" class="mini" />
      <el-table-column fixed="right" label="操作" width="320">
        <template slot-scope="scope">
          <el-button
            size="small"
            type="danger"
            @click.native="funIm(scope.row)"
          >
            解除屏蔽
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
  </div>
</template>

<script>
import { imForbidList } from '@/api/im'
import { parseTime } from '@/utils/index'
import { memberIm } from '@/api/member'

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
      pagesize: 20,
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
      imForbidList(param, 'get')
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => { })
    },
    funIm(row) {
      const tips = '确定将该会员解除禁聊吗？'
      const disable_im = 0
      this
        .$confirm(tips, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: row.uid,
            disable_im
          }
          memberIm(param).then(response => {
            this.$message.success(response.message)
            this.fetchData()
            return true
          })
        })
        .catch(() => { })
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
