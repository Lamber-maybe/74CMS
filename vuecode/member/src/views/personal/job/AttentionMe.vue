<template>
  <div>
    <el-table :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#909399'}" v-loading="listLoading" style="width: 100%">
      <el-table-column prop="companyname" width="246" label="公司名称">
        <template slot-scope="scope">
          <p class="name_title" @click="handlerOuterLink(scope.row.company_link_url_web)">{{scope.row.companyname}}</p>
        </template>
      </el-table-column>
      <el-table-column prop="scale_text" width="259" label="公司信息">
        <template slot-scope="scope">
          <p class="border">
            <span class="company_title">{{scope.row.scale_text}}</span>  |  <span class="company_title">{{scope.row.district_text}}</span>  |   <span class="company_title">{{scope.row.nature_text}}</span>
          </p>

        </template>
      </el-table-column>
      <el-table-column prop="has_download" label="下载简历">
        <template slot-scope="scope">
          <el-tag size="medium" v-if="scope.row.has_download==1">已下载</el-tag>
          <el-tag size="medium" type="warning" v-if="scope.row.has_download==0">未下载</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="addtime" align="center" width="150" label="查看时间">
        <template slot-scope="scope">
          <span class="date_title">{{scope.row.addtime | timeFilter}}</span>
        </template>
      </el-table-column>

    </el-table>
    <pagination :total="total" :current-page="params.page" :page-size="params.pagesize"  @handleCurrentChange="doSearch"></pagination>
  </div>
</template>

<script>
  import { formatTime } from '@/utils/index'
  import http from '@/utils/http'
  import api  from '@/api'
   export default {
    data() {
      return {
        listLoading:false,
        dataset:[],
        total:1,
        params: {
          page: 1,
          pagesize: 10,
        },
      }
    },
    filters: {
      timeFilter(timestamp) {
        return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
      },
    },
    created() {
      this.fetchData(true)
    },
    methods: {
      fetchData(init){
        this.listLoading=true
        http.get(api.personal_manage_attention_me,this.params).then((res)=>{
          this.dataset=[...res.data.items]
          this.listLoading=false
          if(init==true){
            this.fetchTotal()
          }
        })
      },
      fetchTotal(){
          http.get(api.personal_manage_attention_me_total).then((res)=>{
            this.total=res.data
          })
      },
      doSearch(page){
        this.params.page=page
        this.fetchData()
      },
      handlerOuterLink(url){
        window.open(url)
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
    font-size: 13px;
    color: #666666;
  }

  .date_title {
    // font-size: 12px;
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
  .border{
    font-size: 10px;
    color: #e2e2e2;
  }
</style>
