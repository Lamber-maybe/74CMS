<template>
  <div class="client_job_wrapper">
    <div class="box">
      <div class="button">
        <div :class="type=='receivedDelivered' ? 'selectButton' : ''" @click="switchTemplate('receivedDelivered')">收到投递</div>
        <div :class="type=='activeDownload' ? 'selectButton' : ''" @click="switchTemplate('activeDownload')">主动下载</div>
        <div :class="type=='recentEnquiries' ? 'selectButton' : ''" @click="switchTemplate('recentEnquiries')">近期查看</div>
        <div :class="type=='viewed' ? 'selectButton' : ''" @click="switchTemplate('viewed')">职位被查看</div>
      </div>
    </div>
    <div class="tables">
      <el-table
        v-loading="loading"
        v-if="type == 'receivedDelivered'"
        :header-cell-style="{background:'#f9f9f9'}"
        :data="tableData"
        style="width: 100%"
      >
        <el-table-column
          prop="date"
          label="简历名称"
          width="200"
        >
          <template slot-scope="scope">
            <el-link
              :href="scope.row.link"
              target="_blank"
              type="primary"
              class="text"
              :underline="false"
            >
              <div style="display:inline-block;float: left">{{ scope.row.fullname }}</div>
            </el-link>
            <div style="display:inline-block;float: right">{{ scope.row.complete_percent }}%</div>
            <div style="clear:both;" />
            <div>{{ scope.row.sex_text }} / {{ scope.row.age }}岁 / {{ scope.row.education_text }} / {{ scope.row.experience_text }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="求职意向"
          width="400"
        >
          <template slot-scope="scope">
            <div>意向地区：{{ scope.row.intention_district }}</div>
            <div>意向职位：{{ scope.row.intention_jobs }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="jobname"
          label="投递职位"
        />
        <el-table-column
          prop="addtime"
          label="投递时间"
        />
        <el-table-column
          label="是否查看"
        >
          <template slot-scope="scope">
            <div v-if="scope.row.is_look == 0">否</div>
            <div v-if="scope.row.is_look == 1">审核通过</div>
          </template>
        </el-table-column>
        <el-table-column
          label="是否面邀"
        >
          <template slot-scope="scope">
            <div v-if="scope.row.interview == 0">否</div>
            <div v-if="scope.row.interview == 1">是</div>
          </template>
        </el-table-column>
        <el-table-column
          label="操作"
          width="180"
        >
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="goto(scope.row.link)">查看简历</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-table
        v-if="type == 'activeDownload'"
        :header-cell-style="{background:'#f9f9f9'}"
        :data="tableData"
        v-loading="loading"
        style="width: 100%"
      >
        <el-table-column
          prop="date"
          label="简历名称"
        >
          <template slot-scope="scope">
            <el-link
              :href="scope.row.link"
              target="_blank"
              type="primary"
            >
              <div>{{ scope.row.fullname }}</div>
            </el-link>
            <div>{{ scope.row.sex_text }} / {{ scope.row.age }}岁 / {{ scope.row.education_text }} / {{ scope.row.experience_text }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="求职意向"
        >
          <template slot-scope="scope">
            <div>意向地区：{{ scope.row.intention_district }}</div>
            <div>意向职位：{{ scope.row.intention_jobs }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="jobname"
          label="下载时间"
        >
          <template slot-scope="scope">
            {{ scope.row.addtime | timeFilter }}
          </template>
        </el-table-column>
        <el-table-column
          label="操作"
        >
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="goto(scope.row.link)">查看简历</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-table
        v-if="type == 'recentEnquiries'"
        :header-cell-style="{background:'#f9f9f9'}"
        :data="tableData"
        v-loading="loading"
        style="width: 100%"
      >
        <el-table-column
          prop="date"
          label="简历名称"
        >
          <template slot-scope="scope">
            <el-link
              :href="scope.row.link"
              target="_blank"
              type="primary"
              class="text"
              :underline="false"
            >
              <div>{{ scope.row.fullname }}</div>
            </el-link>
            <div>{{ scope.row.sex_text }} / {{ scope.row.age }}岁 / {{ scope.row.education_text }} / {{ scope.row.experience_text }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="求职意向"
        >
          <template slot-scope="scope">
            <div>意向地区：{{ scope.row.intention_district }}</div>
            <div>意向职位：{{ scope.row.intention_jobs }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="jobname"
          label="浏览时间"
        >
          <template slot-scope="scope">
            {{ scope.row.addtime | timeFilter }}
          </template>
        </el-table-column>
        <el-table-column
          label="操作"
        >
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="goto(scope.row.link)">查看简历</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-table
        v-if="type == 'viewed'"
        :header-cell-style="{background:'#f9f9f9'}"
        :data="tableData"
        v-loading="loading"
        style="width: 100%"
      >
        <el-table-column
          prop="date"
          label="简历名称"
        >
          <template slot-scope="scope">
            <el-link
              :href="scope.row.link"
              target="_blank"
              type="primary"
              class="text"
              :underline="false"
            >
              <div>{{ scope.row.fullname }}</div>
            </el-link>
            <div>{{ scope.row.sex_text }} / {{ scope.row.age }}岁 / {{ scope.row.education_text }} / {{ scope.row.experience_text }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="求职意向"
        >
          <template slot-scope="scope">
            <div>意向地区：{{ scope.row.intention_district }}</div>
            <div>意向职位：{{ scope.row.intention_jobs }}</div>
          </template>
        </el-table-column>
        <el-table-column
          prop="jobname"
          label="浏览职位"
        />
        <el-table-column
          prop="jobname"
          label="浏览时间"
        >
          <template slot-scope="scope">
            {{ scope.row.addtime | timeFilter }}
          </template>
        </el-table-column>
        <el-table-column
          label="操作"
        >
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="goto(scope.row.link)">查看简历</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="bortton-page">
      <el-col :span="24" style="text-align: right">
        <el-pagination
          background
          :current-page="currentPage"
          :page-sizes="[10, 15, 20, 30, 40]"
          :page-size="pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </div>
  </div>
</template>

<script>
import { jobApply, downResume, viewResume, viewed } from '@/api/company_crm'
import { parseTime } from '@/utils'

export default {
  name: 'Dynamic',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: {
    comid: {
      default: ''
    },
    uid: {
      default: ''
    }
  },
  data(){
    return {
      loading:false,
      currentPage: 1,
      pagesize: 10,
      total: 0,
      type: 'receivedDelivered',
      tableData: []
    }
  },
  created() {
    this.jobApply()
  },
  methods: {
    goto(target) {
      window.open(target)
    },
    jobApply() {
      this.loading = true
      jobApply(
        {
          'company_id': this.comid,
          'page': this.currentPage,
          'pagesize': this.pagesize
        }).then(res => {
        this.tableData = res.data.items
        this.total = res.data.total
        this.currentPage = res.data.current_page
        this.loading = false
      })
        .then(res => {
          this.loading = false
        })
    },
    downResume(){
      this.loading = true
      downResume(
        {
          'company_id': this.comid,
          'page': this.currentPage,
          'pagesize': this.pagesize
        }).then(res => {
        this.tableData = res.data.items
        this.total = res.data.total
        this.currentPage = res.data.current_page
        this.loading = false
      })
    },
    viewResume(){
      this.loading = true
      viewResume(
        {
          'company_uid': this.uid,
          'page': this.currentPage,
          'pagesize': this.pagesize
        }).then(res => {
        this.tableData = res.data.items
        this.total = res.data.total
        this.currentPage = res.data.current_page
        this.loading = false
      })
    },
    viewed(){
      this.loading = true
      viewed(
        {
          'company_uid': this.uid,
          'page': this.currentPage,
          'pagesize': this.pagesize
        }).then(res => {
        this.tableData = res.data.items
        this.total = res.data.total
        this.currentPage = res.data.current_page
        this.loading = false
      })
    },
    switchTemplate(type){
      this.type = type
      this.currentPage = 1
      if (this.type == 'receivedDelivered'){
        this.jobApply()
      }
      if (this.type == 'activeDownload'){
        this.downResume()
      }
      if (this.type == 'recentEnquiries'){
        this.viewResume()
      }
      if (this.type == 'viewed'){
        this.viewed()
      }
    },
    handleSizeChange(val){
      this.pagesize = val
      if (this.type == 'receivedDelivered'){
        this.jobApply()
      }
      if (this.type == 'activeDownload'){
        this.downResume()
      }
      if (this.type == 'recentEnquiries'){
        this.viewResume()
      }
      if (this.type == 'viewed'){
        this.viewed()
      }
    },
    handleCurrentChange(val){
      this.currentPage = val
      if (this.type == 'receivedDelivered'){
        this.jobApply()
      }
      if (this.type == 'activeDownload'){
        this.downResume()
      }
      if (this.type == 'recentEnquiries'){
        this.viewResume()
      }
      if (this.type == 'viewed'){
        this.viewed()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.tables{
  margin-bottom: 30px;
}
.client_job_wrapper{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding:0px 20px;
}
.box{
  margin: 15px 0px;
  .button{
    flex-shrink: 0;
    div{
      width: 90px;
      height: 30px;
      display: inline-block;
      background-color: #d4d4da30;
      border-radius: 20px;
      text-align: center;
      margin-top: 10px;
      font-size: 13px;
      line-height: 30px;
      cursor: pointer;
      color: #00000087;
      margin-right: 16px;
    }
    .selectButton{
      width: 90px;
      background-color: #0a79eb;
      color: #FFFFFF;
    }
  }
  .bortton-page{
    margin-top: 20px;
    padding-bottom: 40px;
  }
}
</style>
