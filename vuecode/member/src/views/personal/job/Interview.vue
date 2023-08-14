<template>
  <div>
    <el-table :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#909399'}" v-loading="listLoading" style="width: 100%">
      <el-table-column prop="jobname" width="226" label="职位名称">
        <template slot-scope="scope">
          <p class="name_title" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</p>
        </template>
      </el-table-column>
      <el-table-column prop="companyname" width="239" label="公司名称">
        <template slot-scope="scope">
          <span class="company_title" @click="handlerOuterLink(scope.row.company_link_url_web)">{{scope.row.companyname}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center">
        <template slot="header">
          <el-dropdown trigger="click" @command="filterStatus">
            <span class="el-dropdown-link" style="cursor:pointer;color:rgb(144, 147, 153)">
              {{status_label}}<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-for="(item,index) in optionStatus" :key="index" :command="item">{{item.text}}</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
        <template slot-scope="scope">
          <el-tag size="medium" v-if="scope.row.is_look==1">已查看</el-tag>
          <el-tag size="medium" type="warning" v-if="scope.row.is_look==0">未查看</el-tag>
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
      <el-table-column align="center" label="操作">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handlerDetail(scope.row)">详情</el-button>
        </template>
      </el-table-column>

    </el-table>
    <pagination :total="total" :current-page="params.page" :page-size="params.pagesize" @handleCurrentChange="doSearch"></pagination>



    <el-dialog title="面试详情" :visible.sync="dialogVisible" width="540px">
      <interviewDialog :detail-item="detailItem" :is-video="false"></interviewDialog>
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
        status_label:'查看状态',
        settr_label:'面试时间',
        optionStatus: [
          { text: '不限', value: '' },
          { text: '已查看', value: 1 },
          { text: '未查看', value: 0 }
        ],
        optionSettr:[
          { text: '不限', value: '' },
          { text: '3天内', value: 3 },
          { text: '7天内', value: 7 },
          { text: '15天内', value: 15 },
          { text: '30天内', value: 30 },
        ],
        dialogVisible:false,
        total:1,
        listLoading:false,
        dataset:[],
        params: {
          is_look:'',
          settr:'',
          page: 1,
          pagesize: 10,
        },
        detailItem:{}
      }
    },
    created() {
      this.fetchData(true)
    },
    filters:{
      timeFilter(timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      },
    },
    methods: {
      handlerDetail(item){
        this.detailItem = item
        this.dialogVisible = true
        http.post(api.personal_manage_interview_setLook, {id: item.id}).then(()=>{
          item.is_look = 1
        })
      },
      fetchData(init){
        this.listLoading=true
        http.get(api.personal_manage_interview,this.params).then((res)=>{
          this.dataset=[...res.data.items]
          this.listLoading=false
          if(init==true){
            this.fetchTotal()
          }
        })
      },
      fetchTotal(){
        http.get(api.personal_manage_interview_total,this.params).then((res)=>{
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
      filterStatus(item) {
        this.params.page = 1
        this.params.is_look = item.value
        if(item.value>0){
          this.status_label = item.text
        }else{
          this.status_label = '查看状态'
        }
        this.fetchData(true)
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
</style>
