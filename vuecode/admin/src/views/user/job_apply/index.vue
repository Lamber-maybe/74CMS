<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>简历投递记录</span>
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
            <el-option label="公司名称" value="1" />
            <el-option label="公司ID" value="2" />
            <el-option label="职位名称" value="3" />
            <el-option label="职位ID" value="4" />
            <el-option label="求职者姓名" value="5" />
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
        <el-table-column label="求职者姓名" min-width="100">
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
        <el-table-column label="基本信息" min-width="200">
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
        <el-table-column label="职位名称" min-width="200">
          <template slot-scope="scope">
            <el-link :href="scope.row.job_link" target="_blank" type="primary">
              {{ scope.row.jobname }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="企业名称" min-width="200">
          <template slot-scope="scope">
            <el-link
              :href="scope.row.company_link"
              target="_blank"
              type="primary"
            >
              {{ scope.row.companyname }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column align="center" label="投递时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="是否已查看" min-width="90">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.is_look==1">已查看</el-tag>
            <el-tag v-if="scope.row.is_look==0" type="warning">未查看</el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="处理状态" min-width="80">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.handle_status==1">已同意</el-tag>
            <el-tag v-if="scope.row.handle_status==2" type="info">已拒绝</el-tag>
            <el-tag v-if="scope.row.handle_status==0" type="warning">待处理</el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="投递方式" min-width="80">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.source==1" type="info">委托投递</el-tag>
            <el-tag v-if="scope.row.source==0">自主投递</el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="来源" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.platform_cn }}</span>
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
  </div>
</template>

<script>
import { jobApplyList } from '@/api/resume'
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
      jobApplyList(param).then(response => {
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
    }
  }
}
</script>
