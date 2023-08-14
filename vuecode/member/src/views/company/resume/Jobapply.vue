<template>
  <el-card>
    <menuheader>收到投递</menuheader>
      <div class="resumedetails">
      <div class="resume_header">
        <div>
          <span class="resume_header_font">应聘职位：</span>
          <el-select
            v-model="params.jobid"
            placeholder="请选择"
            @change="doSearch(1,true)"
          >
            <el-option
              v-for="(item,index) in options_job"
              :key="index"
              :label="item.text"
              :value="item.value"
            ></el-option>
          </el-select>
          <span class="resume_header_font resume_header_margin">处理状态：</span>
          <el-select
            v-model="params.status"
            placeholder="全部"
            @change="doSearch(1,true)"
          >
            <el-option
              v-for="(item,index) in options_status"
              :key="index"
              :label="item.text"
              :value="item.value"
            ></el-option>
          </el-select>
        </div>
        <div>
          <span class="resume_header_font">查看状态：</span>
          <el-select
            v-model="params.is_look"
            placeholder="全部"
            @change="doSearch(1,true)"
          >
            <el-option
              v-for="(item,index) in options_look"
              :key="index"
              :label="item.text"
              :value="item.value"
            ></el-option>
          </el-select>
          <span class="resume_header_font resume_header_margin">简历来源：</span>
          <el-select
            v-model="params.source"
            placeholder="全部"
            @change="doSearch(1,true)"
          >
            <el-option
              v-for="(item,index) in options_source"
              :key="index"
              :label="item.text"
              :value="item.value"
            ></el-option>
          </el-select>
        </div>
      </div>

      <div class="resume_list">
        <div class="resume_handle">
          <span class="bar"></span>近两周简历处理情况
        </div>
        <div class="resume_header_font">
          收到简历：<span>{{watchSituation.total}}</span> 待查看简历：<span>{{watchSituation.noWatch}}</span> 查看率：<span
            >{{watchSituation.percent}}</span
          >
        </div>
      </div>
    </div>

    <el-table
      :data="dataset"
      :header-cell-style="{ background: '#fcfcfc', color: '#b8babd' }"
       v-loading="listLoading"
    >
      <el-table-column
        width="280"
        header-align="left"
        align="left"
        prop="name"
        label="基本信息"
      >
        <template slot-scope="scope">
          <div class="information_wrapper">
            <div class="portraitImg">
              <img :src="scope.row.photo_img_src" />
            </div>
            <div class="information_list">
              <div class="fn_bar">
                <div class="name a-link" @click="handlerLook(scope.row)">{{scope.row.fullname}}</div>
                <div class="hq" v-if="scope.row.high_quality > 0"></div>
                <div class="no_look" v-if="parseInt(scope.row.is_look) === 0"></div>
                <div class="clear"></div>
              </div>
              <p class="information">
                {{ scope.row.age }}岁 | {{ scope.row.education_text }} |
                {{ scope.row.experience_text }} | {{ scope.row.sex_text }}
              </p>
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="应聘职位" align="center" prop="jobname">
        <template slot-scope="scope">
          <a class="stations_fonts a-link" :href="scope.row.job_link_url_web" target="_blank">{{ scope.row.jobname }}</a>
        </template>
      </el-table-column>
      <el-table-column
        width="300px"
        header-align="left"
        label="求职意向"
      >
        <template slot-scope="scope">
          <p class="intention_post">
            想找 <span>{{ scope.row.intention_jobs }}</span> 工作
          </p>
          <p class="intention_local">
            想在 <span>{{ scope.row.intention_district }}</span> 工作
          </p>
        </template>
      </el-table-column>
      <el-table-column
        header-align="center"
        align="center"
        width="200px"
        label="应聘时间"
      >
        <template slot-scope="scope">
          <p class="time">{{ scope.row.addtime | timeFilter }}</p>
        </template>
      </el-table-column>
      <el-table-column header-align="center" align="center" label="操作状态">
        <template slot-scope="scope">
          <div
            class="btnsColor1 btn"
            v-if="scope.row.handle_status == 0"
            type="warning"
            size="mini"
          >
            待处理
          </div>
          <div
            class="btnsColor2 btn"
            v-if="scope.row.handle_status == 1"
            type="primary"
            size="mini"
          >
            已同意
          </div>
          <div
            class="btnsColor3 btn"
            v-if="scope.row.handle_status == 2"
            type="danger"
            size="mini"
          >
            已婉拒
          </div>
        </template>
      </el-table-column>
      <el-table-column header-align="center" align="right" width="380" label="操作">
        <template slot-scope="scope">
          <el-button
            size="mini"
            type="primary"
            v-if="scope.row.handle_status == 0"
            @click.stop="handlerAgree(scope.row)"
            >同意</el-button
          >
          <el-button
            size="mini"
            type="danger"
            v-if="scope.row.handle_status == 0"
            @click="handlerRefuse(scope.row)"
            >婉拒</el-button
          >
          <el-button size="mini" type="primary" @click="handlerRemark(scope.row)">备注</el-button>
          <el-button size="mini" type="warning" @click="handlerSave(scope.row.resume_id)">保存到电脑</el-button>
          <el-button size="mini" @click="handlerDel(scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination
      :total="total"
      :current-page="params.page"
      :page-size="params.pagesize"
      @handleCurrentChange="doSearch"
    ></pagination>

    <el-dialog width='540px' :destroy-on-close="true"  title="面试邀请"  :visible.sync="showInvite" @opened="handlerInviteOpened" @closed="handlerInviteClose">
      <Invite
        ref="invite"
        from="apply"
        :apply_id="apply_id"
        :apply_fullname="apply_fullname"
      ></Invite>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="handlerInviteClose(true)" :loading="btnLoading">确  定</el-button>
        <el-button  @click="handlerInviteClose">取  消</el-button>
      </span>
    </el-dialog>

    <el-dialog width='540px'  title="填写备注"  :visible.sync="showRemark" @opened="handlerRemarkOpened" @closed="handlerRemarkClose">
      <Remark ref="remark" :remark_item="remark_item"></Remark>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="handlerRemarkClose(true)" :loading="btnLoading">确  定</el-button>
        <el-button  @click="handlerRemarkClose">取  消</el-button>
      </span>
    </el-dialog>


  </el-card>
</template>

<script>
import { formatTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import menuheader from './components/menuHeader.vue'
import Remark from './components/Remark.vue'
import Invite from './components/Invite.vue'
export default {
  components: {
    menuheader,
    Remark,
    Invite
  },
  filters: {
    timeFilter(timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
  },
  data() {
    return {
      jobid: 0,
      btnLoading:false,
      showInvite:false,
      apply_id:0,
      apply_fullname:'',
      showRemark:false,
      remark_item:{},
      listLoading:false,
      dataset: [],
      total: 0,
      params: {
        jobid: 0,
        status: '',
        source: '',
        is_look: '',
        page: 1,
        pagesize: 10,
      },
      watchSituation:{
        total:0,
        noWatch:0,
        percent:'100%'
      },
      options_job: [{ text: '全部', value: 0 }],
      options_status: [
        { text: '全部', value: '' },
        { text: '待处理', value: 0 },
        { text: '已同意', value: 1 },
        { text: '已拒绝', value: 2 },
      ],
      options_look: [
        { text: '全部', value: '' },
        { text: '未查看', value: 0 },
        { text: '已查看', value: 1 }
      ],
      options_source: [
        { text: '全部', value: '' },
        { text: '自主投递', value: 0 },
        { text: '委托投递', value: 1 },
      ]
    }
  },
  created() {
    this.fetchData(true)
  },
  methods: {
    fetchData(init) {
      this.listLoading = true
      http
        .get(api.company_jobapply_list, this.params)
        .then((res) => {
          this.options_job = [{ text: '全部', value: 0 }]
          let options_job = [...res.data.option_jobs]
          options_job.forEach((element) => {
            this.options_job.push({
              text: element.jobname,
              value: element.id,
            })
          })
          this.dataset = [...res.data.items]
          this.listLoading = false
          if(init===true){
            this.fetchTotal()
            this.fetchWatchSituation()
          }
        })
        .catch(() => {})
    },
    fetchTotal(){
      http
        .get(api.company_jobapply_list_total, this.params)
        .then((res) => {
          this.total = res.data
        })
        .catch(() => {})
    },
    fetchWatchSituation(){
      http
        .get(api.company_jobapply_watch_situation, {})
        .then((res) => {
          this.watchSituation = {
            total:res.data.total,
            noWatch:res.data.noWatch,
            percent:res.data.watch_percent
          }
        })
        .catch(() => {})
    },
    doSearch(page,initPagination) {
      this.params.page = page
      this.fetchData()
      if(initPagination===true){
        this.fetchTotal()
      }
    },
    handlerAgree (item) {
     this.jobid = item.jobid
      if (item.audit != 1) {
        this.$message.error('该简历尚未通过审核，不能继续此操作')
        return false
      }
      this.btnLoading = true
      http
        .post(api.company_jobapply_set_agree_pre, { id: item.id })
        .then((res) => {
          this.btnLoading = false
          if (res.data.finish === 1) {
            this.fetchData(true)
            this.$message({
              type: 'success',
              message: '简历处理成功'
            })
          } else {
            this.openAddInvitation(item)
          }
        })
        .catch(() => {})
    },
    openAddInvitation (item) {
      this.apply_id = item.id
      this.apply_fullname = item.fullname
      this.showInvite = true
    },
    handlerInviteOpened(){
      this.$refs.invite.initCB(this.jobid)
    },
    handlerInviteClose(setData){
      if(setData===true){
        this.btnLoading = true
        this.$refs.invite.$refs.form.validate((valid) => {
          if (valid) {
            let data = this.$refs.invite.form;
            data.apply_id = this.apply_id
            http
              .post(api.company_jobapply_set_agree, data)
              .then(() => {
                this.btnLoading = false
                this.fetchData(true)
                this.$message({ type: 'success', message: '邀请面试成功' })
                this.showInvite = false
              })
              .catch(() => {
                this.btnLoading = false
              })
          } else {
            this.btnLoading = false
            return false;
          }
        });
      }else{
        this.showInvite = false
      }
    },
    handlerRefuse (item) {
      if (item.audit != 1) {
        this.$message.error('该简历尚未通过审核，不能继续此操作')
        return false
      }
      this.$confirm('确定将处理状态更改为【已婉拒】吗？','系统提示',{type:'warning'})
        .then(() => {
          http
            .post(api.company_jobapply_set_refuse, { id: item.id })
            .then(() => {
              this.$message({
                type: 'success',
                message: '简历处理成功'
              })
              this.fetchData(true)
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerDel (item) {
      this.$confirm('确定删除该简历吗？','系统提示',{type:'warning'})
        .then(() => {
          http
            .post(api.company_jobapply_del, { id: item.id })
            .then(() => {
              this.$message({
                type: 'success',
                message: '简历删除成功'
              })
              this.fetchData(true)
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerLook (item) {
      if (item.is_look === 0) {
        item.is_look = 1
        http
          .post(api.company_jobapply_set_looked, { id: item.id })
          .then(() => {
            window.open(item.resume_link_url_web)
          })
          .catch(() => {})
      } else {
        window.open(item.resume_link_url_web)
      }
    },
    handlerRemark (item) {
      this.showRemark = true
      this.remark_item = item
    },
    handlerRemarkOpened(){
      this.$refs.remark.initCB()
    },
    handlerRemarkClose(setData){
      this.showRemark = false
      if(setData===true){
        this.btnLoading = true
        let data = {
          remark:this.$refs.remark.content,
          resume_id:this.remark_item.resume_id
        }
        this.remark_item.remark = this.$refs.remark.content
        http
          .post(api.remark_resume, data)
          .then(() => {
            this.btnLoading = false
            this.showRemark = false
          })
          .catch(() => {
            this.btnLoading = false
          })
      }
      this.$refs.remark.content = ''
    },
    handlerSave(resume_id){
      let routeData = this.$router.resolve({
        name: "ResumeSave",
        query: '',
        params:{id:resume_id}
      });
      window.open(routeData.href, '_blank');
    },
  },
}
</script>

<style lang="scss" scoped>
.a-link{
  cursor:pointer;
}
.a-link:hover{
  text-decoration:underline;
  color:#1787fb;
}
.resumedetails {
  display: flex;
  background-color: #f9f9f9;
  padding: 18px 0;
}
.resumedetails  .resume_header {
  flex: 5.5;
  padding-left: 35px;
}
.resume_header > div:nth-of-type(1) {
  margin-bottom: 10px;
}

.resume_list {
  flex: 4;
}
.resume_list div:nth-of-type(2) {
  margin-left: 15px;
}
.resume_header_font {
  font-size: 14px;
  color: #666666;
}
.resume_header_margin {
  margin-left: 44px;
}
.resume_header_font span {
  color: #4a88f7;
  font-size: 22px;
  margin-right: 65px;
}
.resume_list .resume_handle {
  line-height: 20px;
  margin-bottom: 10px;
  padding: 15px 0;
  color: #333;
  font-size: 14px;
}
.bar {
  width: 4px;
  height: 16px;
  background: #1787fb;
  display: inline-block;
  margin: -3px 5px -3px 0;
}
.el-checkbox {
  margin-left: 20px;
}
.information_list .fn_bar .name{
  display: block;float: left;height: 20px;line-height: 20px;
    cursor:pointer;
    color: #333;
    font-size: 16px;
}
.information_list .fn_bar .hq {
	float: left;
	margin-left: 10px;
	width: 45px;
	height: 20px;
  background: url("../../../assets/images/great.png") 0 center no-repeat;
}
.information_list .fn_bar .no_look {
  float: left;margin-left: 10px;width: 33px;height: 20px;background: url("../../../assets/images/no_look.png") 0 center no-repeat;
}
.information {
  color: #999;
  font-size: 14px;
  margin-top: 6px;
}
.information_wrapper {
  display: flex;
}
.portraitImg {
  flex: 1;
  margin-right: 10px;
  border-radius: 50%;
}
.portraitImg img {
  border-radius: 50%;
  width: 52px;
  height: 52px;
}
.information_list {
  flex: 8;
}
.information_list img {
  vertical-align: -1px;
  margin-left: 10px;
}
.el-pagination {
  text-align: center;
  margin-top: 20px;
}
.intention_post,
.intention_local {
  font-size: 14px;
  color: #999;
}
.intention_post span,
.intention_local span {
  color: #666666;
}

.time{
  color:#666 ;
  font-size: 14px;
}
.del_btn {
  margin-left: 14px;
  margin-top: 20px;
}
.del_btn .el-button {
  margin-left: 15px;
}
.stations_fonts {
  font-size: 14px;
  color: #1787fb;
}
.btn {
  width: 60px;
  height: 24px;
  display: inline-block;
  font-size: 12px;
}
.btnsColor1 {
  background: #fdf6ec;
  color: #e8ab60;
}
.btnsColor2 {
  background: #ecf5ff;
  color: #4a88f7;
}
.btnsColor3 {
  background: #fef0f0;
  color: #f68b8b;
}
.el-select {
  width: 259px;
}


</style>
