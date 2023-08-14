<template>
  <div>
    <el-table  :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#909399'}"  style="width: 100%" v-loading="listLoading" @selection-change="handleSelectionChange">
      <el-table-column type="selection"></el-table-column>
      <el-table-column  prop="jobname" width="225"  label="职位名称">
        <template slot-scope="scope">
          <span class="name_title" @click="handlerOuterLink(scope.row.job_link_url_web)">{{scope.row.jobname}}</span>
          <p class="name_title2" @click="handlerOuterLink(scope.row.company_link_url_web)">{{scope.row.companyname}}</p>
        </template>
      </el-table-column>
      <el-table-column  prop="education_text" width="259"   label="职位要求">
        <template slot-scope="scope">
          <p class="border">
            <span class="company_title">{{scope.row.education_text}}</span>  |  <span class="company_title">{{scope.row.experience_text}}</span>  |  <span class="company_title">{{scope.row.district_text}}</span>
          </p>
        </template>
      </el-table-column>
      <el-table-column  prop="wage_text"  label="薪资">
        <template slot-scope="scope">
          <span class="salary_text">{{scope.row.wage_text}}/月</span>
        </template>
      </el-table-column>
      <el-table-column  prop="addtime" align="center" width="150" label="浏览时间">
        <template slot-scope="scope">
          <span class="date_title">{{scope.row.addtime | timeFilter}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center"   label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handlerDel(scope.row)">删除</el-button>
        </template>
      </el-table-column>

    </el-table>
    <div class="checkbox_wrapper">
        <el-button size="mini" @click="handlerDelBatch">删除</el-button>
    </div>
    <pagination :total="total" :current-page="params.page" :page-size="params.pagesize" @handleCurrentChange="doSearch" ></pagination>
  </div>
</template>

<script>
  import { formatTime } from '@/utils/index'
  import http from '@/utils/http'
  import api from '@/api'
  export default{
    data(){
      return{
         dataset:[],
         listLoading:false,
         total:0,
         params:{
          page:1,
          pagesize: 10,
         },
          multipleSelection:[]
      }
    },
    created() {
      this.fetchData(true)
    },
    filters:{
      timeFilter(timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
      },
    },
    methods:{
      fetchData(init){ //获取列表数据
        this.listLoading=true
          http.get(api.personal_manage_view_job,this.params).then((res)=>{
            this.dataset=[...res.data.items]
            this.listLoading=false
            if(init==true){
              this.fetchTotal()
            }
          })
      },
      fetchTotal(){  //获取分页总数
        http.get(api.personal_manage_view_job_total,this.params).then((res)=>{
          this.total=res.data
        })
      },
      doSearch(page){
        this.params.page=page
        this.fetchData()
      },
      handleSelectionChange(val) {
        this.multipleSelection = val;
      },
      // 删除
      handlerDel(item){
        this.$confirm('确定删除该记录吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_view_job_del, { id: item.id })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '删除成功'
                })
                this.fetchData(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      handlerDelBatch () {
        if(this.multipleSelection.length==0){
          this.$message.error('请选择记录')
          return false
        }
        let idarr = [];
        this.multipleSelection.forEach(element => {
          idarr.push(element.id)
        });
        this.$confirm('确定删除选中的记录吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_view_job_del_batch, { id: idarr })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '删除成功'
                })
                this.fetchData(true)
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
  .name_title{
    cursor:pointer;
    font-size:14px ;
    color: #1787fb;
  }
  .name_title:hover{
    text-decoration:underline;
  }
  .name_title2{
    cursor:pointer;
    font-size: 12px;
    color: #999999;
  }
  .name_title2:hover{
    text-decoration:underline;
  }
  .company_title{
    font-size: 13px;
    color: #747474;
  }
  .salary_text{
    color: #ff6600;
    font-size:13px;
  }
  .date_title{
    font-size: 13px;
    color: #333333;
  }
  .checkbox_wrapper{
    margin-top: 20px;
  }
  .checkbox_wrapper .el-checkbox{
    margin: 0  22px 0 14px;
  }
  .border{
    font-size: 10px;
    color: #E2E2E2;
  }
</style>
