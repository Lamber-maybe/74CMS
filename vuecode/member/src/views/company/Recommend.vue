<template>
    <div class="main_container">
        <company-title>智能推荐</company-title>
        <menu-nav v-if="published_joblist.length>0" :list="published_joblist" :active-tab="params.id"></menu-nav>
        <div class="my_table bg_th">
            <el-table  :data="dataset" style="width: 100%" v-loading="listLoading">
                <el-table-column  width="380" prop="information" label="基本信息">
                    <template slot-scope="scope">
                        <div class="tab_information">
                          <div class="information_img">
                            <img :src="scope.row.photo_img_src" />
                          </div>
                          <div class="information_text">
                            <p><a target="_blank" :href="scope.row.resume_link_url_web">{{scope.row.fullname}}</a></p>
                            <span>{{scope.row.age_text}}岁 · {{scope.row.sex_text}} · {{scope.row.education_text}} · {{scope.row.experience_text}}经验</span>
                          </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column  prop="intention_wage"  label="期望薪资">
                  <template slot-scope="scope">
                      <span class="salary_text">{{scope.row.intention_wage}}/月</span>
                  </template>
                </el-table-column>
                <el-table-column  prop="current_text" label="求职状态">
                    <template slot-scope="scope">
                        <span class="current_text">{{scope.row.current_text}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="求职意向">
                    <template slot-scope="scope">
                          <p class="intention_type"><span>想找</span> {{scope.row.intention_jobs}} <span>工作</span></p>
                          <p class="intention_region"><span>想在</span> {{scope.row.intention_district}} <span>工作</span></p>
                    </template>
                </el-table-column>
                <el-table-column width="190" align='center' prop="refreshtime" label="刷新时间">
                    <template slot-scope="scope" >
                        <p class="date_text">{{scope.row.refreshtime}}</p>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <pagination
          :total="total"
          :current-page="params.page"
          :page-size="params.pagesize"
          @handleCurrentChange="doSearch"
        ></pagination>
    </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
  export default {
    data() {
      return {
        published_joblist: [],
        listLoading:false,
        dataset: [],
        total: 0,
        params: {
          id:0,
          page: 1,
          pagesize: 10,
        },
      }
    },
		watch:{
			'$route':function(newVal){
        if(newVal.query.id!==undefined){
          this.params.id = newVal.query.id
          this.dataset = []
          this.fetchData(true)
        }
			}
		},
    created () {
      if(this.$route.query.id!==undefined){
        this.params.id = this.$route.query.id
      }
      this.fetchDataPublishedJoblist()
    },
    methods: {
      fetchDataPublishedJoblist () {
        http
          .get(api.get_publish_jobs, {})
          .then(res => {
            let counter = 0;
            res.data.items.forEach(ele => {
              let tmp = {
                label: ele.jobname, href: '/company/recommend?id='+ele.id, name: ele.id+'', active: counter==0?true:false
              }
              this.published_joblist.push(tmp)
              if(counter==0 && !this.params.id){
                this.params.id = ele.id
              }
              counter++
            });
            if (this.published_joblist.length > 0) {
              this.fetchData(true)
            }
          })
          .catch(() => {})
      },
      fetchData (init) {
        this.listLoading = true
        http
          .get(api.recommend_resumelist, this.params)
          .then(res => {
            this.dataset = [...res.data.items]
            this.listLoading = false
            if(init===true){
              this.fetchDataTotal()
            }
          })
          .catch(() => {})
      },
      fetchDataTotal () {
        http
          .get(api.recommend_resumelist_total, this.params)
          .then(res => {
            this.total = res.data
          })
          .catch(() => {})
      },
      doSearch(page) {
        this.params.page = page
        this.fetchData()
      }
    }
  }
</script>

<style lang="scss" scoped>
.tab_information{
  display: flex;
}
.information_img{
  margin-right: 10px;
  border-radius: 50%;
}
.information_img img{
  border-radius: 50%;
  width:52px;
  height:52px;
}
.information_text p{
  font-size: 16px;
  color: #333333;
}
.information_text p a{
  font-size: 16px;
  color: #333333;
}
.information_text span{
  display: inline-block;
  font-size: 14px;
  color: #999999;
  margin-top: 10px;
}
.label_text{
  background:#fdf6ec ;
  font-size:12px ;
  color:#e6a552 ;
  border: 1px solid #faecd8;
  padding: 6px 11px;
  border-radius: 5px;
}
.salary_text{
  font-size: 14px;
  color: #ff704f;
}
.current_text{
  font-size:14px ;
  color:#999 ;
}
.intention_type span,.intention_region span{
  font-size: 14px;
  color: #999;
}
.intention_type , .intention_region{
  font-size:14px ;
  color:#666666 ;
}
.date_text{
  font-size: 14px;
  color: #666;
}
</style>
