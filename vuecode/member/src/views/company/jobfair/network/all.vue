<template>
  <div>
    <el-table :header-cell-style="{background:'#fcfcfc',color:'#909399',fontSize:'14px',fontWeight:'normal'}" :data="dataset" style="width: 100%">
      <el-table-column prop="title" label="网络招聘会内容">
        <template slot-scope='scope'>
          <div class="jobFair_con">
            <p class="title">{{scope.row.title}}</p>
            <p class="time">举办时间：{{scope.row.starttime | timeFilter}} 至 {{scope.row.endtime | timeFilter}}</p>
            <p class="place">参会企业 <span class="num">{{scope.row.total_company}}</span> 参会个人 <span class="num">{{scope.row.total_personal}}</span> &nbsp;浏览次数 <span class="num">{{scope.row.click}}</span></p>
          </div>

        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="120">
        <template slot-scope='scope'>
          <el-button type="primary" size="mini" v-if="scope.row.score === 2"><a class="jobfairUrl" :href="scope.row.jobfair_url" target="_blank">我要参会</a></el-button>
          <el-button type="info" size="mini" v-if="scope.row.score === 0" disabled>已结束</el-button>
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
    methods: {
      fetchData:function() {
        http.get(api.company_jobfairol, this.params).then(res => {
          this.dataset = [...res.data.items]
          this.listLoading = false
          this.total = res.data.total
        }).catch(() => {})
      },
      doSearch:function(page) {
        this.params.page = page
        this.fetchData()
      }
    }
  }
</script>

<style lang="scss" scoped>
  .jobFair_con{
    margin:10px 0 10px 0px;
  }
.title{
    color: #333333;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 14px;
}
.time , .place{
  color: #666666;
  font-size: 14px;
}
  .num {
    font-weight: bold;margin-right: 5px;
  }
  .jobfairUrl{
    color:#fff
  }
  .jobfairUrl:hover{
    text-decoration: none;
  }
</style>
