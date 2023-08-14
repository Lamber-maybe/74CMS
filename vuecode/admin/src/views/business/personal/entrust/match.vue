<template>
  <div class="app-container">
    <el-tag v-for="(item,index) in intentionList" :key="index" :type="intentionId==item.id?'warning':'info'" @click="changeIntention(item.id)">{{ item.category_text }}</el-tag>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      stripe
      highlight-current-row
    >
      <el-table-column label="职位名称">
        <template slot-scope="scope">
          <el-link
            :href="scope.row.job_link_url_web"
            target="_blank"
            type="primary"
          >{{ scope.row.jobname }}</el-link>
        </template>
      </el-table-column>
      <el-table-column label="企业名称">
        <template slot-scope="scope">
          <el-link
            :href="scope.row.company_link_url_web"
            target="_blank"
            type="primary"
          >{{ scope.row.companyname }}</el-link>
        </template>
      </el-table-column>
      <el-table-column label="学历要求">
        <template slot-scope="scope">
          {{ scope.row.education_text }}
        </template>
      </el-table-column>
      <el-table-column label="经验要求">
        <template slot-scope="scope">
          {{ scope.row.experience_text }}
        </template>
      </el-table-column>
      <el-table-column label="薪资待遇">
        <template slot-scope="scope">
          {{ scope.row.wage_text }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="刷新时间">
        <template slot-scope="scope">
          {{ scope.row.refreshtime }}
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作">
        <template slot-scope="scope">
          <el-button v-if="scope.row.enable===1" size="mini" type="primary" @click="handlerApply(scope.row)">
            投递简历
          </el-button>
          <el-button v-else size="mini" type="primary" disabled>
            投递简历
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-row :gutter="20">
      <el-col :span="20" style="text-align: right;">
        <el-pagination
          layout="prev, pager, next"
          background
          :total="total"
          :current-page="currentPage"
          :page-size="pagesize"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { entrustIntentionList, entrustMatchList, entrustMatchApply } from '@/api/entrust'

export default {
  props: ['resumeId'],
  data() {
    return {
      intentionId: 0,
      intentionList: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 5
    }
  },
  created() {
    this.fetchIntentionData()
  },
  methods: {
    changeIntention(intentionid){
      this.intentionId = intentionid
      this.currentPage = 1
      this.fetchMatchList()
    },
    fetchIntentionData() {
      entrustIntentionList({ resumeid: this.resumeId }).then(response => {
        this.intentionList = response.data
        if (this.intentionList.length > 0){
          this.intentionId = this.intentionList[0].id + ''
          this.fetchMatchList()
        }
      })
    },
    fetchMatchList(){
      this.listLoading = true
      const params = {
        id: this.intentionId,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      entrustMatchList(params).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchMatchList()
    },
    handlerApply(row){
      this
        .$confirm('确定将当前简历投递给该职位吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const params = {
            jobid: row.id,
            resumeid: this.resumeId
          }
          entrustMatchApply(params).then(response => {
            row.enable = 0
            this.$message.success(response.message)
          })
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped>
.el-tag{
  margin-right:10px;
  cursor:pointer;
  margin-bottom:10px;
}
</style>
