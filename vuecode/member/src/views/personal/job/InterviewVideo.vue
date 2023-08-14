<template>
  <div>
    <el-table :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#909399'}" v-loading="listLoading" style="width: 100%">
      <el-table-column prop="jobname" width="180" label="职位名称">
        <template slot-scope="scope">
          <p class="name_title" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</p>
        </template>
      </el-table-column>
      <el-table-column prop="companyname" width="215" label="公司名称">
        <template slot-scope="scope">
          <span class="company_title" @click="handlerOuterLink(scope.row.company_link_url_web)">{{scope.row.companyname}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" width="150">
        <template slot="header">
          <el-dropdown trigger="click" @command="filterSettr">
            <span class="el-dropdown-link" style="cursor:pointer;color:rgb(144, 147, 153)">
              {{settr_label}}<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-for="(item,index) in optionSettr" :key="index" :command="item">{{item.text}}</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
        <template slot-scope="scope">
          <span class="date_title">{{scope.row.interview_time | timeFilter}}</span>
        </template>
      </el-table-column>

      <el-table-column prop="is_look" align="center"   label="操作">
        <template slot-scope="scope">
          <div v-if="scope.row.is_look" >
            <el-button type="primary" size="mini" @click="handlerDetail(scope.row)">详情</el-button>
            <el-button type="primary" size="mini" @click="enterRoom(scope.row.id)">进入房间</el-button>
            <el-button type="primary" size="mini" @click="copyUrl(scope.row.id)">复制链接</el-button>
            <el-button type="danger" size="mini" @click="handlerNotice(scope.row.id)">提醒上线</el-button>
          </div>
          <div v-else>
            <el-button type="primary" size="mini" @click="handlerDetail(scope.row)">详情</el-button>
            <el-button size="mini">房间未开启</el-button>
          </div>

        </template>
      </el-table-column>

    </el-table>
    <pagination :total="total"  :current-page="params.page" :page-size="params.pagesize" @handleCurrentChange="doSearch" ></pagination>


    <el-dialog title="面试详情" :visible.sync="dialogVisible" width="540px">
      <interviewDialog :detail-item="detailItem" :is-video="true"></interviewDialog>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
        <el-button  @click="dialogVisible = false">取 消</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { formatTime } from '@/utils/index'
  import http from '@/utils/http'
  import api from '@/api'
  import interviewDialog from './dialog.vue'
  export default {
    components:{
      interviewDialog
    },
    data() {
      return {
        settr_label:'面试时间',
        optionSettr:[
          { text: '不限', value: '' },
          { text: '3天内', value: 3 },
          { text: '7天内', value: 7 },
          { text: '15天内', value: 15 },
          { text: '30天内', value: 30 },
        ],
        dialogVisible:false,
        params: {
          settr:'',
          page: 1,
          pagesize: 10,
        },
        listLoading:false,
        dataset:[],
        total:1,
        detailItem:{}
      }
    },
    created() {
      this.fetchData(true)
    },
    filters:{
      timeFilter(timestamp){
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    methods: {
      handlerDetail(item){
        this.detailItem = item
        this.dialogVisible = true
        http.post(api.personal_manage_interview_video_setLook, {id: item.id})
      },
      fetchData(init){
        this.listLoading=true
        http.get(api.personal_manage_interview_video,this.params).then((res)=>{
          this.dataset=[...res.data.items]
          this.listLoading=false
          if(init==true){
            this.fetchTotal()
          }
        })
      },
      fetchTotal(){
        http.get(api.personal_manage_interview_video_total,this.params).then((res)=>{
          this.total=res.data
        })
      },
      doSearch(page){
        this.params.page=page
        this.fetchData()
      },
      handlerOuterLink(url){
        window.open(url)
      },
      filterSettr(item) {
        this.params.page = 1
        this.params.settr = item.value
        if(item.value>0){
          this.settr_label = item.text
        }else{
          this.settr_label = '面试时间'
        }
        this.fetchData(true)
      },
			handlerNotice (id) {
			this.$confirm('确定提醒企业吗？','系统提示',{type:'warning'})
				.then(() => {
				http
					.post(api.personal_manage_interview_video_notice, { id })
					.then(() => {
					this.$message({
						type: 'success',
						message: '提醒成功'
					})
					})
					.catch(() => {})
				})
				.catch(() => {
				// on cancel
				})
			},
			copyUrl (id) {
				var oInput = document.createElement('input')
        oInput.value = this.$store.state.config.sitedomain + this.$store.state.config.sitedir + 'member/video/' + id
				document.body.appendChild(oInput)
				oInput.select() // 选择对象
				document.execCommand('Copy') // 执行浏览器复制命令
				oInput.className = 'oInput'
				oInput.style.display = 'none'
				this.$message({type:'success',message:'复制成功'})
			},
      enterRoom (id) {
        this.$router.push('/video/' + id)
      }
    }
  }
</script>

<style lang="scss" scoped>
  .name_title {
    cursor:pointer;
    font-size: 14px;
    color: #1787fb;
  }
  .name_title:hover{
    text-decoration:underline;
  }

  .company_title {
    cursor:pointer;
    font-size: 13px;
    color: #333333;
  }
  .company_title:hover{
    text-decoration:underline;
  }

  .date_title {
    font-size: 13px;
    color: #333333;
  }

  .checkbox_wrapper {
    margin-top: 20px;
  }

  .checkbox_wrapper .el-checkbox {
    margin: 0 22px 0 14px;
  }

  .download_box {
    padding: 5px 11px;
    font-size: 12px;
    border-radius: 5px;
  }

  .bg1 {
    background: #fdf6ec;
    color: #e6a552;
    border: 1px solid #faecd8;
  }

  .bg2 {
    background: #ecf5ff;
    color: #4a88f7;
    border: 1px solid #d9ecff;
  }
  .el-table__header-wrapper{
    margin-right: 20px;
  }
</style>
