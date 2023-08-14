<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>视频面试</span>
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
        <el-table-column label="企业名称">
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
        <el-table-column label="面试职位">
          <template slot-scope="scope">
            <el-link :href="scope.row.job_link" target="_blank" type="primary">
              {{ scope.row.jobname }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column label="面试简历" width="400">
          <template slot-scope="scope">
            <el-link
              :href="scope.row.resume_link"
              target="_blank"
              type="primary"
            >
              {{ scope.row.fullname }}
            </el-link>
            (<span>{{ scope.row.age }}</span>
            /
            <span>{{ scope.row.sex_ }}</span>
            /
            <span>{{ scope.row.education_ }}</span>
            /
            <span>{{ scope.row.experience_ }}</span>)
          </template>
        </el-table-column>
        <el-table-column align="center" label="面试时间">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.interview_time | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="面试房间">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.room_status=='overtime'" type="info">已关闭</el-tag>
            <el-tag v-if="scope.row.room_status=='nostart'" type="warning">未开启</el-tag>
            <el-tag v-if="scope.row.room_status=='opened'" type="success">开启中</el-tag>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button size="small" type="primary" @click="fun_detail(scope.row)">
              详情
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
    <el-dialog title="面试详情" :visible.sync="dialogFormVisible" width="30%">
      <el-row>
        <el-col :span="24">联系人：{{ dialogContent.contact }}</el-col>
      </el-row>
      <el-row>
        <el-col :span="24">联系电话：{{ dialogContent.tel }}</el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import { companyInterviewVideoList } from '@/api/company'
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
      dialogContent: {
        contact: '',
        tel: ''
      },
      dialogFormVisible: false
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
      companyInterviewVideoList(param).then(response => {
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
    fun_detail(info) {
      this.dialogContent = {
        contact: info.contact,
        tel: info.tel
      }
      this.dialogFormVisible = true
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
