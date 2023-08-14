<template>
  <div class="popup_contacts">
    <div class="box">
      <div class="button">
        <div :class="type=='delivery' ? 'selectButton' : ''" @click="switchTemplate('delivery')">主动投递</div>
        <div :class="type=='invite' ? 'selectButton' : ''" @click="switchTemplate('invite')">收到面邀</div>
        <div :class="type=='viewed' ? 'selectButton' : ''" @click="switchTemplate('viewed')">被查看</div>
        <div :class="type=='browsed' ? 'selectButton' : ''" @click="switchTemplate('browsed')">浏览过</div>
      </div>
      <div v-if="type== 'delivery'" class="talbe">
        <el-table
          v-loading="loading"
          :header-cell-style="{background:'#f9f9f9'}"
          :data="tableData"
          style="width: 100%"
        >
          <el-table-column
            prop="jobname"
            label="职位名称"
          >
            <template slot-scope="scope">
              <a style="color: #1787fb;" target="_blank" :href="scope.row.job_link_url_web">{{ scope.row.jobname }}</a>
            </template>
          </el-table-column>
          <el-table-column
            prop="companyname"
            label="公司名称"
          >
            <template slot-scope="scope">
              <a style="color: #1787fb;" target="_blank" :href="scope.row.company_link_url_web">{{ scope.row.companyname }}</a>
            </template>
          </el-table-column>
          <el-table-column
            prop="status_text"
            label="反馈状态"
          />
          <el-table-column
            prop="addtime"
            label="投递时间"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.addtime | timeFilter }}</span>
            </template>
          </el-table-column>
        </el-table>
        <div class="bortton-page">
          <el-col :span="24" style="text-align: right">
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
        </div>
      </div>
      <div v-if="type== 'invite'" class="talbe">
        <el-table
          v-loading="loading"
          :header-cell-style="{background:'#f9f9f9'}"
          :data="tableData"
          style="width: 100%"
        >
          <el-table-column
            prop="jobname"
            label="职位名称"
          >
            <template slot-scope="scope">
              <a style="color: #1787fb;" target="_blank" :href="scope.row.job_link_url_web">{{ scope.row.jobname }}</a>
            </template>
          </el-table-column>
          <el-table-column
            prop="companyname"
            label="公司名称"
          >
            <template slot-scope="scope">
              <a style="color: #1787fb;" target="_blank" :href="scope.row.company_link_url_web">{{ scope.row.companyname }}</a>
            </template>
          </el-table-column>
          <el-table-column
            prop="overtime"
            label="查看状态"
          >
            <template slot-scope="scope">
              <span v-if="scope.row.overtime == 0">未查看</span>
              <span v-else>已查看</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="ip_addr"
            label="面试时间"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.interview_time | timeFilter }}</span>
            </template>
          </el-table-column>
        </el-table>
        <div class="bortton-page">
          <el-col :span="24" style="text-align: right">
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
        </div>
      </div>
      <div v-if="type== 'viewed'" class="talbe">
        <el-table
          v-loading="loading"
          :header-cell-style="{background:'#f9f9f9'}"
          :data="tableData"
          style="width: 100%"
        >
          <el-table-column
            prop="companyname"
            label="公司名称"
          >
            <template slot-scope="scope">
              <a style="color: #1787fb;" target="_blank" :href="scope.row.company_link_url_web">{{ scope.row.companyname }}</a>
            </template>
          </el-table-column>
          <el-table-column
            prop="content"
            label="公司信息"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.scale_text }} | {{ scope.row.district_text }} | {{ scope.row.nature_text }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="ip"
            label="下载简历"
          >
            <template slot-scope="scope">
              <span v-if="scope.row.has_download == 1">已下载</span>
              <span v-else>未下载</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="ip_addr"
            label="查看时间"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.addtime | timeFilter }}</span>
            </template>
          </el-table-column>
        </el-table>
        <div class="bortton-page">
          <el-col :span="24" style="text-align: right">
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
        </div>
      </div>
      <div v-if="type== 'browsed'" class="talbe">
        <el-table
          v-loading="loading"
          :header-cell-style="{background:'#f9f9f9'}"
          :data="tableData"
          style="width: 100%"
        >
          <el-table-column
            prop="jobname"
            label="职位名称"
          >
            <template slot-scope="scope">
              <div>
                <a style="color: #1787fb;" target="_blank" :href="scope.row.job_link_url_web">{{ scope.row.jobname }}</a>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="jobname"
            label="企业名称"
          >
            <template slot-scope="scope">
              <div>
                <a style="color: #1787fb;" target="_blank" :href="scope.row.company_link_url_web">{{ scope.row.companyname }}</a>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="content"
            label="职位要求"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.education_text }} | {{ scope.row.experience_text }} | {{ scope.row.district_text }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="wage_text"
            label="薪资"
          />
          <el-table-column
            prop="addtime"
            label="浏览时间"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.addtime | timeFilter }}</span>
            </template>
          </el-table-column>
        </el-table>
        <div class="bortton-page">
          <el-col :span="24" style="text-align: right">
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { resumeUrmAttentionMe, resumeUrmInterview, resumeUrmJobApply, resumeUrmViewJob } from '@/api/resume_urm'
import { parseTime } from '@/utils'

export default {
  name: 'Dynamic',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: {
    uid: {
      default: ''
    }
  },
  data(){
    return {
      loading: false,
      type: 'delivery',
      tableData: [],
      currentPage: 1,
      pagesize: 10,
      total: 0
    }
  },
  created() {
    this.jobApply()
  },
  methods: {
    jobApply(){
      this.loading = true
      resumeUrmJobApply({ 'page': this.currentPage, 'pagesize': this.pagesize, 'uid': this.uid })
        .then(res => {
          this.tableData = res.data.items
          this.currentPage = res.data.current_page
          this.total = res.data.total
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    interview(){
      this.loading = true
      resumeUrmInterview({ 'page': this.currentPage, 'pagesize': this.pagesize, 'uid': this.uid })
        .then(res => {
          this.tableData = res.data.items
          this.currentPage = res.data.current_page
          this.total = res.data.total
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    attentionMe(){
      this.loading = true
      resumeUrmAttentionMe({ 'page': this.currentPage, 'pagesize': this.pagesize, 'uid': this.uid })
        .then(res => {
          this.tableData = res.data.items
          this.currentPage = res.data.current_page
          this.total = res.data.total
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    viewjob(){
      this.loading = true
      resumeUrmViewJob({ 'page': this.currentPage, 'pagesize': this.pagesize, 'uid': this.uid })
        .then(res => {
          this.tableData = res.data.items
          this.currentPage = res.data.current_page
          this.total = res.data.total
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    switchTemplate(type){
      this.type = type
      this.currentPage = 1
      if (this.type == 'delivery'){
        this.jobApply()
      }
      if (this.type == 'invite'){
        this.interview()
      }
      if (this.type == 'viewed'){
        this.attentionMe()
      }
      if (this.type == 'browsed'){
        this.viewjob()
      }
    },
    handleSizeChange(size){
      this.pagesize = size
      if (this.type == 'delivery'){
        this.jobApply()
      }
      if (this.type == 'invite'){
        this.interview()
      }
      if (this.type == 'viewed'){
        this.attentionMe()
      }
      if (this.type == 'browsed'){
        this.viewjob()
      }
    },
    handleCurrentChange(page){
      this.currentPage = page
      if (this.type == 'delivery'){
        this.jobApply()
      }
      if (this.type == 'invite'){
        this.interview()
      }
      if (this.type == 'viewed'){
        this.attentionMe()
      }
      if (this.type == 'browsed'){
        this.viewjob()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.talbe{
  margin-top: 20px;
}
.popup_contacts{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding:0px 20px;
}
.box{
  margin: 15px 0px;
.button{
  display: inline-block;
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
