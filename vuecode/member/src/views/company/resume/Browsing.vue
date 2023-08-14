<template>
	<el-card>
		<div >
			<menuheader>浏览记录</menuheader>
			<div class="list">
				<el-table :data="dataset" :header-cell-style="{background:'#fcfcfc',color:'#b8babd'}" v-loading="listLoading" @selection-change="handleSelectionChange">
					<el-table-column type="selection"></el-table-column>
					<el-table-column  header-align="left" align="left" label="基本信息">
						<template slot-scope="scope">
							<div class="information_wrapper">
								<div class="portraitImg">
									<img :src="scope.row.photo_img_src" />
								</div>
								<div class="information_list">
                  <div class="fn_bar">
                    <a class="name a-link" :href="scope.row.resume_link_url_web" target="_blank">{{scope.row.fullname}}</a>
                    <div class="hq" v-if="scope.row.high_quality > 0"></div>
                    <div class="clear"></div>
                  </div>
									<p class="information">{{scope.row.age}}岁 | {{scope.row.education_text}} | {{scope.row.experience_text}} | {{scope.row.sex_text}}
									</p>
								</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column width="340px" header-align="left" label="求职意向">
						<template slot-scope="scope">
								<p class="intention_post">想找 <span>{{scope.row.intention_jobs}}</span> 工作</p>
								<p class="intention_local">想在 <span>{{scope.row.intention_district}}</span> 工作</p>
						</template>
					</el-table-column>
					<el-table-column header-align="center" align="center" label="浏览时间">
						<template slot-scope="scope">
              <div class="time">
                {{ scope.row.addtime | timeFilter }}
              </div>
						</template>
					</el-table-column>
					<el-table-column header-align="center" width="170px" label="操作">
						<template slot-scope="scope">
							<el-button size="mini" type="primary" @click="handlerDownload(scope.row.resume_id)">下载简历</el-button>
							<el-button size="mini" @click="handlerDel(scope.row)">删除</el-button>
						</template>
					</el-table-column>
				</el-table>
				<div class="del_btn">
					<el-button type="mini" @click="handlerDelBatch">删除</el-button>
				</div>

        <pagination
          :total="total"
          :current-page="params.page"
          :page-size="params.pagesize"
          @handleCurrentChange="doSearch"
        ></pagination>
			</div>
		</div>
	</el-card>
</template>

<script>
import { formatTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import menuheader from './components/menuHeader.vue'
export default{
	components:{
		menuheader
	},
  filters: {
    timeFilter(timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
    },
  },
  data() {
    return {
	listLoading:false,
      dataset: [],
      total: 0,
      params: {
        page: 1,
        pagesize: 10,
      },
		multipleSelection:[],
		showDirectService:false,
		directServiceInfo:{}
    }
  },
  created() {
    this.fetchData(true)

  },
  methods: {
    fetchData(init) {
	this.listLoading = true
      http
        .get(api.company_view_resume_list, this.params)
        .then((res) => {
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
        .get(api.company_view_resume_list_total,{})
        .then((res) => {
          this.total = res.data
        })
        .catch(() => {})
    },
    doSearch(page) {
      this.params.page = page
      this.fetchData()
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    handlerDel (item) {
      this.$confirm('确定删除该查看记录吗？','系统提示',{type:'warning'})
        .then(() => {
          http
            .post(api.company_view_resume_del, { id: item.id })
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
      this.$confirm('确定删除选择的查看记录吗？','系统提示',{type:'warning'})
        .then(() => {
          http
            .post(api.company_view_resume_del_batch, { id: idarr })
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
    handlerDownload (resume_id) {
		this.$confirm('确定下载该简历吗？','系统提示',{type:'warning'})
        .then(() => {
          http
          .post(api.resumedownload, {resume_id})
          .then(res => {
            if (res.data.done == 0) {
              this.showDirectService = true
              this.directServiceInfo = {
                use_type: res.data.use_type,
                need_points: res.data.need_points,
                need_expense: res.data.need_expense,
                discount: res.data.discount,
                resume: resume_id,
                btnCn: res.data.use_type == 'points' ? '立即兑换' : '立即支付'
              }
              return false
            } else {
              this.$message({ type: 'success', message: res.message })
              this.fetchData()
            }
          })
          .catch(() => {
          })
        })
        .catch(() => {
          // on cancel
        })
    }
  },
}
</script>

<style lang="scss" scoped>
.a-link{
  cursor:pointer;
}
.a-link:hover{
  text-decoration:underline;
  color:#1787fb;
}
.browsing_wrapper{
	display: flex;
	line-height: 40px;
	margin-bottom: 27px;
}
.header_input{
	width:  305px;
}
.browsing_wrapper>p{
	flex: 1;
	font-size: 17px;
	color: #333333;
	font-weight: bold;
}
.information_list .fn_bar .name{
  display: block;float: left;height: 20px;line-height: 20px;
    cursor:pointer;
    color: #333;
    font-size: 16px;
}
.information_list .fn_bar .hq {
	float: left;
	margin-left: 10px;
	width: 45px;
	height: 20px;
  background: url("../../../assets/images/great.png") 0 center no-repeat;
}
.information{
	color: #999;
	font-size: 12px;
  margin-top: 6px;
}
.information_wrapper{
	display: flex;

}
.portraitImg{
	flex: 1;
	margin-right: 10px;
	border-radius: 50%;
}
.portraitImg img{
	border-radius: 50%;
  width:52px;
  height:52px;
}
.information_list{
	flex: 8;
}
.el-pagination{
	text-align: center;
	margin-top: 20px;
}
.intention_post , .intention_local{
	font-size: 14px;
	color: #999;
}
.intention_post span,.intention_local span{
	color: #666666;
}

.time{
  color: #666;
  font-size: 14px;
}

.del_btn {
	margin-top: 20px;
}
</style>
