<template>
	<div class='echarts_con'>
		<img class="echarts_icon" src="../../../assets/images/echarts_icon.png" />
		<el-select v-model="jobid"  class="el_select"  size="mini" placeholder="最近7天" @change="changeJob">
			<el-option
				v-for="(item,index) in publishedJoblist"
				:key="index"
				:label="item.jobname"
				:value="item.id">
				</el-option>
		</el-select>
		<div id="main" style="width: 100%;height:305px;"></div>
	</div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
	import echarts from 'echarts'
	export default {
		data(){
			return{
				jobid:0,
				publishedJoblist:[],
				dateArr:[],
				viewData:[],
				applyData:[],
				charts: ''
			}
		},
		created(){
			this.fetchData(null, true)
		},
		mounted(){
			this.$nextTick(function() {
				this.drawChart('main')
			})
		},
		methods:{
			fetchData(e, init = false) {
				if (init === false) {
					this.charts.showLoading()
				}
				http.get(api.get_publish_jobs, {}).then(response=>{
					this.publishedJoblist = response.data.items
					if(this.publishedJoblist.length==0){
						return false
					}
					this.jobid = this.publishedJoblist[0].id
					this.fetchLineData();

				}).catch(()=>{})

				},
				drawChart(id) {
				this.charts = echarts.init(document.getElementById(id))
				this.charts.showLoading()
				this.charts.setOption({
					title: {
						text: '招聘效果统计',
						padding:[2,0,0,28],
						textStyle:{
							fontWeight:'bold',
							fontSize:'18'
						}
					},
					tooltip: {
					trigger: 'axis'
					},
					legend: {
					data: ['职位浏览', '应聘统计']
					},
					grid: {
					left: '3%',
					right: '4%',
					bottom: '3%',
					containLabel: true
					},
					xAxis: {
					type: 'category',
					boundaryGap: false,
					data: []
					},
					yAxis: {
					type: 'value'
					},
					series: [
					{
						name: '职位浏览',
						type: 'line',
						data: []
					},
					{
						name: '应聘统计',
						type: 'line',
						data: []
					}
					]
				})
        window.onresize = ()=>{
        this.charts.resize()
        }
			},
			fetchLineData(){
				http.post(api.company_index_stat, {id:this.jobid}).then(res => {
					this.dateArr = res.data.dateArr
					this.viewData = res.data.viewData
					this.applyData = res.data.applyData
					this.charts.setOption({
						xAxis: {
							type: 'category',
							boundaryGap: false,
							data: res.data.dateArr
						},
						series: [
							{
							name: '职位浏览',
							type: 'line',
							data: res.data.viewData,
							lineStyle: {
							color: '#3aa1ff'
							},
							itemStyle: {
							color: '#3aa1ff'
							}
							},
							{
							name: '应聘统计',
							type: 'line',
							data: res.data.applyData,
							lineStyle: {
							color: '#f2637b'
							},
							itemStyle: {
							color: '#f2637b'
							}
							}
						]
					})
					this.charts.hideLoading()
				})
				.catch(() => {})
			},
			changeJob(e){
				this.jobid = e
				this.fetchLineData();
			}
		}
	}
</script>

<style scoped>
	.echarts_con{
		position: relative;
	}
	.el_select{
		width: 119px;
		position: absolute;
		right: 50px;
		top: 0;
		z-index: 1;
	}
	.echarts_icon{
		width: 18px;
		height: 18px;
		position: absolute;
		left: 0;
		top: 0;
	}
</style>
