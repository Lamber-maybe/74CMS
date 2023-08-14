<template>
  <div>
    <el-table :header-cell-style="{background:'#fcfcfc',color:'#909399',fontSize:'14px',fontWeight:'normal'}" :data="dataset" style="width: 100%">
      <el-table-column prop="title" label="招聘会内容" width="650">
        <template slot-scope='scope'>
          <div class="jobFair_con">
            <a class="title" :href="scope.row.jobfair_url" target="_blank">{{scope.row.title}}</a>
          </div>
        </template>
      </el-table-column>

      <el-table-column align="center" label="举办时间">
        <template slot-scope='scope'>
          {{scope.row.starttime | timeFilter}} 至 {{scope.row.endtime | timeFilter}}
        </template>
      </el-table-column>
      <el-table-column align="center" label="审核状态">
        <template slot-scope='scope'>
          <el-tag size="small" v-if="scope.row.audit === 0" type="info">未审核</el-tag>
          <el-tag size="small" v-else-if="scope.row.audit === 1" type="success">审核通过</el-tag>
          <el-tag size="small" v-else-if="scope.row.audit === 2" type="warning">等待审核</el-tag>
          <el-tag size="small" v-else type="danger">审核未通过</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="120">
        <template slot-scope='scope'>
          <el-button type="primary" size="mini"><a class="jobfairUrl" :href="scope.row.jobfair_url" target="_blank">查看详情</a></el-button>
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
</template>

<script>
  import { parseTime } from '@/utils/index'
  import http from '@/utils/http'
  import api from '@/api'
  export default{
    filters: {
      timeFilter (timestamp) {
        return parseTime(timestamp, '{y}-{m}-{d}')
      }
    },
    data(){
      return{
        listLoading:false,
        dataset: [],
        total:0,
        params: {
          page: 1,
          pagesize: 10
        }
      }
    },
    created(){
      this.fetchData()
    },
    methods:{
      doSearch:function(page) {
        this.params.page = page
        this.fetchData()
      },
      fetchData:function() {
        http.get(api.company_jobfairol_reserve, this.params).then(res => {
          this.dataset = [...res.data.items]
          this.listLoading = false
          this.total = res.data.total
        }).catch(() => {})
      }
    }
  }
</script>
<style scoped lang="scss">
  .jobFair_con{
    margin:10px 0 ;
  }
  .title{
    color: #333333;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 14px;
    display: block;
  }
  .time , .place{
    color: #666666;
    font-size: 14px;
  }
  .jobfairUrl{
    color:#fff
  }
  .jobfairUrl:hover{
    text-decoration: none;
  }
  .user_name{
    color: #666666;
    font-size:14px ;
    margin-bottom: 14px;
  }
  .user_tel{
    color: #999999;
    font-size:14px ;
  }
</style>
