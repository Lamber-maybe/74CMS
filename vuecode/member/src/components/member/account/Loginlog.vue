<template>
	<div class="my_table bg_th">
		<el-table :data="dataset"  style="width: 100%" v-loading="listLoading">
			<el-table-column  prop="date" label="登录时间">
				<template slot-scope="scope">
						{{ scope.row.addtime | timeFilter }}
				</template>
			</el-table-column>
			<el-table-column  prop="ip"  label="登录ip" ></el-table-column>
			<el-table-column  prop="ip_addr"  label="登录地址(仅供参考)"> </el-table-column>
			<el-table-column  width="300" align="center"  prop="platform_text"  label="登录端">
        <template slot-scope="scope">
        <div class="platform">{{scope.row.platform_text}}</div>
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
		name: 'AccountLoginlog',
		filters: {
			timeFilter (timestamp) {
			return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
			}
		},
		data(){
			return{
				listLoading:false,
				dataset: [],
				total: 0,
				params: {
					page: 1,
					pagesize: 10
				}
			}
		},
		created () {
			this.fetchData(true)
		},
		methods: {
			fetchData (init) {
				this.listLoading = true
				http
					.get(api.member_loginlog, this.params)
					.then(res => {
						this.dataset = [...res.data.items]
						this.listLoading = false
						if(init===true){
							this.fetchTotal()
						}
					})
					.catch(() => {})
			},
			fetchTotal(){
				http
					.get(api.member_loginlog_total,{})
					.then((res) => {
					this.total = res.data
					})
					.catch(() => {})
			},
			doSearch(page) {
				this.params.page = page
				this.fetchData()
			},
		}
	}
</script>

<style scoped lang="scss">
  .el-table{
    font-size: 14px;
    color: #333;
  }
  .platform{
    color:#999 ;
    font-size: 14px;
  }
</style>
