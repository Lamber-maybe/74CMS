<template>
  <div>
        <el-table :header-cell-style="{background:'#fcfcfc',color:'#909399'}"  v-loading="listLoading" :data="dataset"  style="width: 100%">
          <el-table-column  prop="content"  :label="$store.state.config.points_byname+'来源'" ></el-table-column>
          <el-table-column  prop="op"  label="类型" >
            <template slot-scope='scope'>
              <span v-if="scope.row.op == 1" style="color:#13ab5e">增加</span>
              <span v-else style="color:#fd0f0f">减少</span>
            </template>
          </el-table-column>
          <el-table-column  prop="addtime"  label="时间">
            <template slot-scope='scope'>
              <span>{{scope.row.addtime  |  timeFilter}}</span>
            </template>
          </el-table-column>
          <el-table-column  prop="points" width="100"  :label="$store.state.config.points_byname">
            <template slot-scope="scope">
              <span class="integral_color" >{{scope.row.points}}</span>
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
    data(){
      return{
        dataset:[],
        listLoading:false,
				total:0,
				params: {
					page: 1,
					pagesize: 10
				},
      }
    },
    filters: {
      timeFilter (timestamp) {
        return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    created() {
      this.fetchPointsList(true)
    },
    methods:{
			doSearch() {
				this.params.page++
				this.fetchPointsList(false)
			},
			fetchTotal () {
				http
					.get(api.member_pointslog_total, this.params)
					.then(res => {
						this.total = res.data
					})
					.catch(() => {})
			},
      fetchPointsList(init){
        this.listLoading=true
        http.get(api.member_pointslog).then((res=>{
          this.dataset=[...res.data.items]
          this.listLoading=false
          if(init===true){
							this.fetchTotal()
						}
        }))
        .catch(() => {})
      }
    }
  }
</script>

<style lang="scss" scoped>
  .integral_color{
    color: #1787fb;
  }
  .header_wrapper{
    display: flex;
    margin-top: 14px;
    margin-bottom: 40px;
  }
  .header_img{
    width: 342px;
    display: flex;
    margin-left: 20px;
  }
  .header_img>img{
    width: 90px;
    height: 90px;
    margin-right: 20px;
    margin-top: 10px;
  }
  .header_img_text p{
    font-size: 27px;
    color: #333333;
    margin-bottom: 16px;
  }
  .header_img_interal{
    font-size:12px ;
    color: #333333;
    margin-bottom: 16px;
  }
  .header_img_interal>img{
    vertical-align: sub;
    margin-right: 5px;
  }
  .header_img_text .el-button{
    border-radius:12px ;
    color:#ffffff ;
    font-size: 14px;
    background: #ff704f;
    height: 30px;
    padding: 8px 27px;
  }

  .header_text{
    display: flex;
  }
  .border{
    display: inline-block;
    height: 65px;
    border-left: 1px solid #f3f3f3;
    margin-right: 100px;
    margin-top: 15px;
  }
  .text_box p{
    font-size:14px ;
    color:#333333 ;
    margin-bottom: 22px;
  }
  .text_box p span{
    color: #fa7445;
    font-size:11px ;
    padding: 0 5px;
  }
  .btn_box .el-button{
    border-radius:14px;
  }
  .btn_box .el-button:nth-of-type(1){
      padding: 8px 25px;
      font-size: 12px;
      color: #ffffff;
      background: #ff704f;
  }
  .btn_box .el-button:nth-of-type(2){
      padding: 8px 33px;
      font-size: 12px;
      color: #1787fb;
  }
</style>
