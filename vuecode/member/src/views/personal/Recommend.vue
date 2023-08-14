<template>
    <div class="p_recommend">
        <personal-title>智能推荐</personal-title>
        <menu-nav v-if="intentionList.length>0" :list="intentionList" :active-tab="params.id"></menu-nav>
        <div class="p_group">
            <div class="p_item" v-for="(item, index) in dataset" :key="index">
                <div class="p_name">
                    <div class="worry" v-if="item.emergency === 1">急</div><div class="name substring"><a :href="item.job_link_url_web" target="_blank">{{ item.jobname }}</a></div>
                    <div class="clear"></div>
                    <div class="wage">{{ item.wage_text }}</div>
                </div>
                <div class="p_info">
                    <div class="info_item">{{ item.education_text }}</div><div class="info_item">{{ item.experience_text }}</div><div class="info_item substring">{{ item.district_text }}</div>
                    <div class="clear"></div>
                </div>
                <div class="p_tag">
                    <div class="tag_item" v-for="(tag, k) in item.tag_text_arr.slice(0,5)" :key="k">{{ tag }}</div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
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
    name: 'PersonalRecommend',
    data() {
      return {
        intentionList: [],
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
      this.fetchDataIntention()
    },
    methods: {
        fetchDataIntention () {
            http
            .get(api.get_intentions, {})
            .then(res => {
                let counter = 0;
                res.data.items.forEach(ele => {
                    let tmp = {
                        label: ele.category_text, href: '/personal/recommend?id='+ele.id, name: ele.id+'', active: counter==0?true:false
                    }
                    this.intentionList.push(tmp)
                    if(counter==0 && !this.params.id){
                        this.params.id = ele.id
                    }
                    counter++
                });
                if (this.intentionList.length > 0) {
                    this.fetchData(true)
                }
            })
            .catch(() => {})
        },
        fetchData (init) {
            this.listLoading = true
            http
            .get(api.recommend_joblist, this.params)
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
            .get(api.recommend_joblist_total, this.params)
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
    .p_recommend {
        width: 956px; background-color: #fff; padding: 0 35px;
        .p_group {
            position: relative;
            .p_item {
                position: relative; float: left; width: 433px; height: 130px; background-color: #fcfcfc; margin: 0 20px 20px 0;
                padding: 0 22px;transition: linear all .2s;
                &:hover {
                    -webkit-transform:translateY(-3px);transform:translateY(-3px);z-index:1;
                }
                &:nth-of-type(2n) { margin-right: 0; }
                .p_name {
                    padding-top: 18px; position: relative; margin-bottom: 14px;
                    .worry {
                        float: left; width: 18px;height: 19px;font-size: 12px;color: #fff;text-align: center;line-height: 19px;
                        background-color: #ff8980;border-radius: 2px;margin: 1px 9px 0 0;
                    }
                    .name {font-size: 16px;color: #333;max-width: 280px;float: left;}
                    .wage {position: absolute;right: 0;top: 20px;color: #ff552e;}
                }
                .p_info {
                    margin-bottom: 18px;
                    .info_item {
                        position: relative;float: left;max-width: 230px;
                        &:not(:first-child) {
                            padding-left: 14px;
                            &::before {
                                content: '';position: absolute;left: 7px;top: 4px;width: 1px;height: 10px;background-color: #e2e2e2;
                            }
                        }
                    }
                }
                .p_tag {
                    .tag_item {
                        float: left; background-color: #fff;border: 1px solid #e2e2e2;font-size: 12px;border-radius: 3px;padding: 3px 9px;
                        &:not(:first-child) {margin-left: 10px;}
                    }
                }
            }
        }
    }
</style>