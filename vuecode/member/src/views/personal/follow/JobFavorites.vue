<template>
  <div>
    <el-table type="index" ref="multipleTable"  :data="list" :header-cell-style="{background:'#fcfcfc',color:'#909399'}"  style="width: 100%"  v-loading="listLoading" @selection-change="handleSelectionChange">
      <el-table-column type="selection"></el-table-column>
      <el-table-column  prop="companyname" width="300"  label="职位名称">
        <template slot-scope="scope">
          <span class="name_title" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</span>
          <p class="name_title2" @click="handlerOuterLink(scope.row.company_link_url_web)">{{scope.row.companyname}}</p>
        </template>
      </el-table-column>
      <el-table-column  prop="education_text" width="200"    label="职位要求">
        <template slot-scope="scope">
          <p class="name_title3"><span>{{scope.row.education_text}}</span>  |  <span>{{scope.row.experience_text}}</span> |  <span>{{scope.row.district_text}}</span></p>
        </template>
      </el-table-column>
      <el-table-column  prop="wage_text"  label="薪资">
        <template slot-scope="scope">
          <span  class="font_color">{{scope.row.wage_text}}/月</span>
        </template>
      </el-table-column>
      <el-table-column  prop="addtime" align="center"  label="收藏时间">
        <template slot-scope="scope">
          <span class="date_title">{{scope.row.addtime | timeFilter}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center"  label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handlerCancel(scope.row,scope.$index)">取消收藏</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="checkbox_wrapper">
        <el-button size="mini" @click="handlerCancelBatch">取消收藏</el-button>
    </div>
    <pagination :total="total" :current-page="params.page" :page-size="params.pagesize" @handleCurrentChange="doSearch"></pagination>

  </div>
</template>

<script>
  import { formatTime } from '@/utils/index'
  import http from '@/utils/http'
  import api from '@/api'
  export default{
    data(){
      return{
        total:0,
        checked:'',
        listLoading:false,
        list:[],
        params: {
          page: 1,
          pagesize: 10,
        },
        multipleSelection:[]
      }
    },
    filters: {
      timeFilter(timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d}', true)
      },
    },
    mounted(){
      this.tableList(true)
    },
    methods:{
      // 获取数据
      tableList(init){
        this.listLoading=true
        http.get(api.personal_manage_fav_job).then((res)=>{
          this.list=[...res.data.items]
          if(init==true){
            this.fetchTotal()
          }
          this.listLoading=false
        }).catch(() => {})
      },
      // 获取分页总条数
      fetchTotal(){
        http.get(api.personal_manage_attention_company_total).then((res)=>{
          this.total=res.data
        }).catch(() => {})
      },
      // 分页
      doSearch(page){
        this.params.page = page
        this.tableList()
      },
      handleSelectionChange(val) {
        this.multipleSelection = val;
      },
      // 取消收藏
      handlerCancel(item){
        this.$confirm('确定取消收藏该职位吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_fav_job_cancel, { id: item.id })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '取消收藏成功'
                })
                this.tableList(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      handlerCancelBatch () {
        if(this.multipleSelection.length==0){
          this.$message.error('请选择职位')
          return false
        }
        let idarr = [];
        this.multipleSelection.forEach(element => {
          idarr.push(element.id)
        });
        this.$confirm('确定取消收藏选中的职位吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_fav_job_cancel_batch, { id: idarr })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '取消收藏成功'
                })
                this.tableList(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      handlerOuterLink(url){
        window.open(url)
      }
    }
  }
</script>

<style lang="scss" scoped>
  .Tips_text{
    background: #fefce8;
    color: #795f21;
    padding: 18px 20px;
    margin-bottom: 20px;
  }
  .Tips_text img{
    vertical-align: sub;
    margin-right: 5px;
  }

  .name_title {
    cursor:pointer;
    font-size: 14px;
    color: #1787fb;
  }
  .name_title:hover{
    text-decoration:underline;
  }
  .name_title2 {
    cursor:pointer;
    font-size: 13px;
    color: #a9a9a9;
  }
  .name_title2:hover{
    text-decoration:underline;
  }
  .name_title3 {
    font-size: 13px;
    color: #a9a9a9;
  }
  .company_title {
    font-size: 13px;
    color: #333333;
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
  .font_color{
    color: #ff6600;
  }
</style>
