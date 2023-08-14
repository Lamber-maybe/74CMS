<template>
  <div>
    <PointTop></PointTop>
    <el-card>
      <div class="title">{{$store.state.config.points_byname}}收支明细</div>
      <el-table :header-cell-style="{ background: '#fcfcfc', color: '#a1a3a8' }"  :data="dataset"  style="width: 100%">
        <el-table-column  prop="content" label="详情"  ></el-table-column>
        <el-table-column label="类型" >
          <template slot-scope='scope'>
            <span v-if="scope.row.op == 1" style="color:#13ab5e">增加</span>
            <span v-else style="color:#fd0f0f">减少</span>
          </template>
        </el-table-column>
        <el-table-column label="时间">
            <template slot-scope='scope'>
              {{ scope.row.addtime | timeFilter }}
            </template>
        </el-table-column>
        <el-table-column :label="$store.state.config.points_byname" width="170">
            <template slot-scope='scope'>
              <span class="font_color">{{scope.row.points}}</span>
            </template>
        </el-table-column>
      </el-table>
        <pagination
          :total="total"
          :current-page="params.page"
          :page-size="params.pagesize"
          @handleCurrentChange="doSearch"
        ></pagination>
    </el-card>
  </div>
</template>

<script>
import PointTop from './components/PointTop.vue'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
  export default{
		components:{
      PointTop
		},
    filters: {
      timeFilter (timestamp) {
        return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    data(){
      return{
          points: 0,
          logo: '',
          companyname: '',
          taskPoints:{},
          dataset: [],
          total:0,
          params: {
            page: 1,
            pagesize: 10
          }
      }
    },
    created () {
      document.title = document.title.replace('积分', this.$store.state.config.points_byname)
      this.fetchData()
    },
    methods: {
      fetchData () {
        http
          .get(api.member_points, {})
          .then(res => {
            const { points, logo, companyname,taskPoints } = { ...res.data }
            this.points = points
            this.logo = logo
            this.companyname = companyname
            this.taskPoints = taskPoints
            this.fetchLogData(true)
          })
          .catch(() => {})
      },
      fetchLogData (init) {
        http
          .get(api.member_pointslog, this.params)
          .then(res => {
            this.dataset = [...res.data.items]
            if(init===true){
              this.fetchLogDataTotal()
            }
          })
          .catch(() => {})
      },
      fetchLogDataTotal () {
        http
          .get(api.member_pointslog_total, this.params)
          .then(res => {
            this.total = res.data
          })
          .catch(() => {})
      },
      handlerSignin () {
        http
          .post(api.member_signin, {})
          .then(res => {
            this.$message({ type: 'success', message: res.message })
            this.task.forEach(element => {
              if (element.alias === 'sign_in') {
                element.is_done = 1
              }
            })
          })
          .catch(() => {})
      },
      doSearch(page) {
        this.params.page = page
        this.fetchLogData()
      },
    }
  }
</script>
<style lang="scss" scoped>
.el-card{
  margin-bottom: 10px;
}
.el-table{
  color: #333;
}


.font_color{
  color: #1787fb;
}
.title{
  padding: 20px 0;
  font-size:16px;
  color: #333333;
}
</style>
