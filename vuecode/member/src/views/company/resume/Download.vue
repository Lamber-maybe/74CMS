<template>
  <el-card>
    <div>
      <menuheader>我的下载</menuheader>
      <div class="list">
        <el-table
          :data="dataset"
          :header-cell-style="{ background: '#fcfcfc', color: '#b8babd' }"
           v-loading="listLoading"
        >
          <el-table-column
            header-align="left"
            align="left"
            label="基本信息"
          >
            <template slot-scope="scope">
              <div class="information_wrapper">
                <div class="portraitImg">
                  <img
                    :src="scope.row.photo_img_src"
                  />
                </div>
                <div class="information_list">
                  <div class="fn_bar">
                    <a class="name a-link" :href="scope.row.resume_link_url_web" target="_blank">{{scope.row.fullname}}</a>
                    <div class="hq" v-if="scope.row.high_quality > 0"></div>
                    <div class="clear"></div>
                  </div>
                  <p class="information">
                    {{ scope.row.age }}岁 | {{ scope.row.education_text }} |
                    {{ scope.row.experience_text }} |
                    {{ scope.row.sex_text }}
                  </p>
                </div>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            header-align="left"
            prop="address"
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
            label="下载时间"
          >
            <template slot-scope="scope">
              <div class="time">
                {{ scope.row.addtime | timeFilter }}
              </div>

            </template>
          </el-table-column>
          <el-table-column
            header-align="center"
            align="center"
            width="300px"
            label="操作"
          >
            <template slot-scope="scope">
              <el-button size="mini" type="primary" @click="handlerRemark(scope.row)">备注</el-button>
              <el-button size="mini" @click="openAddInvitation(scope.row)">面试邀请</el-button>
              <el-button size="mini" type="warning" @click="handlerSave(scope.row.resume_id)">保存到电脑</el-button>
            </template>
          </el-table-column>
        </el-table>

        <pagination
          :total="total"
          :current-page="params.page"
          :page-size="params.pagesize"
          @handleCurrentChange="doSearch"
        ></pagination>
      </div>
    </div>
    <el-dialog width='540px'  title="填写备注"  :visible.sync="showRemark" @opened="handlerRemarkOpened" @closed="handlerRemarkClose">
      <Remark ref="remark" :remark_item="remark_item"></Remark>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="handlerRemarkClose(true)" :loading="btnLoading">确  定</el-button>
        <el-button  @click="handlerRemarkClose">取  消</el-button>
      </span>
    </el-dialog>
    <el-dialog width='540px'  title="面试邀请"  :visible.sync="showInvite" @opened="handlerInviteOpened" @closed="handlerInviteClose">
      <Invite
        ref="invite"
        from="download"
        :resume_id="resume_id"
        :apply_fullname="apply_fullname"
      ></Invite>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="handlerInviteClose(true)" :loading="btnLoading">确  定</el-button>
        <el-button  @click="handlerInviteClose">取  消</el-button>
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
      return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
    },
  },
  data() {
    return {
      btnLoading:false,
      showInvite:false,
      resume_id:0,
      apply_fullname:'',
      showRemark:false,
      remark_item:{},
      listLoading:false,
      dataset: [],
      total: 0,
      params: {
        page: 1,
        pagesize: 10,
      },
      dialogVisible:false
    }
  },
  created() {
    this.fetchData(true)

  },
  methods: {
    fetchData(init) {
        this.listLoading = true
      http
        .get(api.company_downresume_list, this.params)
        .then((res) => {
          this.dataset = [...res.data.items]
          this.listLoading = false
          if(init===true){
            this.fetchTotal()
          }
        })
        .catch(() => {})
    },
    fetchTotal(){
      http
        .get(api.company_downresume_list_total,{})
        .then((res) => {
          this.total = res.data
        })
        .catch(() => {})
    },
    doSearch(page) {
      this.params.page = page
      this.fetchData()
    },
    openAddInvitation (item) {
      if (item.audit != 1) {
        this.$message.error('该简历尚未通过审核，不能继续此操作')
        return false
      }
      this.resume_id = item.resume_id
      this.apply_fullname = item.fullname
      this.showInvite = true
    },
    handlerInviteOpened(){
      this.$refs.invite.initCB()
    },
    handlerInviteClose(setData){
      if(setData===true){
        this.btnLoading = true
        this.$refs.invite.$refs.form.validate((valid) => {
          if (valid) {
            let data = this.$refs.invite.form;
            data.resume_id = this.resume_id
            http
              .post(api.interview_add, data)
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
.browsing_wrapper {
  display: flex;
  line-height: 40px;
  margin-bottom: 27px;
}
.header_input {
  width: 305px;
}
.browsing_wrapper > p {
  flex: 1;
  font-size: 17px;
  color: #333333;
  font-weight: bold;
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
  font-size:14px ;
}
.del_btn {
  margin-left: 14px;
  margin-top: 20px;
}
.del_btn .el-button {
  margin-left: 30px;
}
</style>
