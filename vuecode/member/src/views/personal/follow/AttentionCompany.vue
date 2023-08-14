<template>
  <div>
    <el-table  :data="followList" :header-cell-style="{background:'#fcfcfc',color:'#909399'}"  style="width: 100%"  v-loading="listLoading" @selection-change="handleSelectionChange">
      <el-table-column type="selection"></el-table-column>
      <el-table-column  prop="companyname" width="300"  label="公司信息">
        <template slot-scope="scope">
          <span class="name_title" @click="handlerOuterLink(scope.row.company_link_url_web)">{{scope.row.companyname}}</span>
          <p class="name_title2"><span>{{scope.row.scale_text}}</span>  |  <span>{{scope.row.trade_text}}</span> |  <span>{{scope.row.nature_text}}</span></p>
        </template>
      </el-table-column>
      <el-table-column  prop="jobnum" label="在招职位" width="200">
        <template slot-scope="scope">
          <span class="name_title3">{{scope.row.first_jobname}}</span>
          <p class="name_title4">等{{scope.row.jobnum}}个岗位</p>
        </template>
      </el-table-column>
      <el-table-column  prop="district_text"  label="公司地区">
        <template slot-scope="scope">
          <span  class="salary_text">{{scope.row.district_text}}</span>
        </template>
      </el-table-column>
      <el-table-column  prop="addtime" align="center" label="关注时间">
        <template slot-scope="scope">
          <span class="date_title">{{scope.row.addtime | timeFilter}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center"  label="操作">
        <template slot-scope='scope'>
          <el-button size="mini" @click="handlerCancel(scope.row)">取消关注</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="checkbox_wrapper">
        <el-button size="mini" @click="handlerCancelBatch">取消关注</el-button>
    </div>
    <pagination :total="total" :current-page="params.page" :page-size="params.pagesize" @handleCurrentChange="doSearch"></pagination>

  </div>
</template>

<script>
  import { formatTime } from '@/utils/index'
  import http from '@/utils/http'
  import api from '@/api'

  export default {
    filters: {
      timeFilter(timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d}', true)
      },
    },
    data() {
      return {
        listLoading:false,
        checked:'',
        followList:[],
        total:0,
        params: {
          page: 1,
          pagesize: 10,
        },
        multipleSelection:[]
      }
    },
    mounted() {
      this.fetchData(true)
    },
    methods: {
      fetchData(init) {
        http.get(api.personal_manage_attention_company).then((res) => {
          this.listLoading = true
          this.followList=[...res.data.items]
          this.listLoading = false
          if(init==true){
            this.fetchTotal()
          }
        })
        
      },
      fetchTotal(){
        http.get(api.personal_manage_attention_company_total).then((res)=>{
          this.total=res.data
        })
      },
      doSearch(page){
        this.params.page = page
        this.fetchData()
      },
      handleSelectionChange(val) {
        this.multipleSelection = val;
      },
      handlerCancel (item) {
        this.$confirm('确定取消关注该企业吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_attention_company_cancel, { id: item.id })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '取消关注成功'
                })
                this.fetchData(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      handlerCancelBatch () {
        if(this.multipleSelection.length==0){
          this.$message.error('请选择企业')
          return false
        }
        let idarr = [];
        this.multipleSelection.forEach(element => {
          idarr.push(element.id)
        });
        this.$confirm('确定取消关注选中的企业吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(api.personal_manage_attention_company_cancel_batch, { id: idarr })
              .then(() => {
                this.$message({
                  type: 'success',
                  message: '取消关注成功'
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

<style  lang="scss" scoped>
  .name_title {
    cursor:pointer;
    font-size: 14px;
    color: #1787fb;
  }
  .name_title:hover{
    text-decoration:underline;
  }
  .name_title2 {
    font-size: 12px;
    color: #a9a9a9;
  }
  .name_title3 {
    font-size: 14px;
    color: #1787fb;
  }
  .name_title4 {
    font-size: 12px;
    color: #a9a9a9;
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
