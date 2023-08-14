<template>
    <div class="companyIndex">
        <div class="left_sidebar">
          <!-- 我的客服 -->
          <div class="customer_service" v-if="serviceInfo.name!==undefined">
            <div class="left_box">
              <div class="portrait">
                <img :src="serviceInfo.photo" alt="">
              </div>
              <div>
                <span class="text_1">{{serviceInfo.name}}</span>
                <a class="text_2" @click="showServicerComplaint=true">我要投诉客服>></a>
              </div>
              <div class="text_3" v-if="isMobile">
                <div class="text_3_box">手机号：<span>{{serviceInfo.mobile?serviceInfo.mobile:'暂无'}}</span></div>
              </div>
              <div class="text_4"  v-if="isTel">
                <div class="text_4_box" v-if="serviceInfo.tel">联系电话：<span>{{serviceInfo.tel?serviceInfo.tel:'暂无'}}</span></div>
              </div>
            </div>


            <div class="right_box">
              <a class="QQ_btn" @click="wakeupQq(serviceInfo.qq)">QQ联系</a>
              <a class="Wx_btn" @click="showServicerQrcode=true">微信联系</a>
            </div>
          </div>
            <!-- 内容头部 -->
           <el-card>
                <div class="card">
                  <div :class="'cardItem i' + (index+1)" v-for="(item,index) in warpnavlist" :key="index" @click="$router.push(item.href)">
                    <div class="i_num">{{item.num}}</div><div class="i_title">{{item.text}}</div>
                  </div>
                </div>
            </el-card>
            <!-- VIP -->
           <el-card>
                <div class="companyWarp">
                    <div class="companyVip">
                        <div class="v_ic_b"><img src="../../assets/images/vip_title_img.png"  alt=""/></div>
                        <div class="v_ic_tit">我的套餐：{{setmeal.name}}</div>
                        <div class="v_ic_date" v-if="parseInt(setmeal.deadline) === 0">无限期</div>
                        <div class="v_ic_date" v-else>有效期至 {{ setmeal.deadline | timeFilter }}</div>
                        <div class="clear"></div>
                    </div>
                    <div class="companyVipDetailsLInk" @click="$router.push('/company/service/setmeal/detail')">
                        了解{{setmeal.name}}招聘特权
                    </div>
                </div>
                <div class="companyVipDetails" style="margin: 0 51px;">
                    <div>
                        <p class="DetailsTitle">可发职位数</p>
                        <div class="DetailsNumber">{{ enable_num }}</div>
                        <el-button class="nbd" round @click="handlerJobadd('btnLoading1')" :loading="btnLoading1">发布职位</el-button>
                        <p class="detailsItem"><router-link to="/company/service/setmeal/add">升级套餐></router-link></p>
                    </div>
                    <div>
                        <p class="DetailsTitle">简历剩余点数</p>
                        <div class="DetailsNumber">{{setmeal.download_resume_point}}</div>
                        <el-button class="nbd" round @click="toSearchResume">搜索简历</el-button>
                        <p class="detailsItem"><router-link to="/company/service/increment/add/resume_package">简历增值包></router-link></p>
                    </div>
                    <div>
                        <p class="DetailsTitle">免费刷新数</p>
                        <div class="DetailsNumber">{{ lave_refresh_count }}</div>
                        <el-button class="nbd" round @click="handlerRefreshBatch">一键刷新</el-button>
                        <p class="detailsItem"><router-link to="/company/service/increment/add/job_refresh">智能刷新></router-link></p>
                    </div>
                    <div>
                        <p class="DetailsTitle">我的{{$store.state.config.points_byname}}</p>
                        <div class="DetailsNumber">{{mypoints}}</div>
                        <el-button class="nbd" round @click="$router.push('/company/service/point')">做任务赚{{$store.state.config.points_byname}}</el-button>
                        <p class="detailsItem"><router-link to="/company/service/point">充值{{$store.state.config.points_byname}}></router-link></p>
                    </div>
                </div>

            </el-card>

            <!-- echarts图表 -->
            <el-card>
                <echarts></echarts>
            </el-card>

            <!-- 广告 -->
           <div class="banner" v-if="ad_dataset_banner.items.length > 0">
             <el-carousel :height="80 + 'px'">
               <el-carousel-item v-for="(item,index) in ad_dataset_banner.items" :key="index">
                 <img :src="item.image_src" class="img" @click="handleClickBanner(item)">
               </el-carousel-item>
             </el-carousel>
            </div>
            <!-- 人才推荐 -->
           <div class="recommend_content">
                <div class="recommend">
                    <div class="recommend_title">
                        人才推荐<span class="t_sub"> 根据您所选职位推荐人才</span>
                    </div>
                    <div class="recommend_select">
                        选择职位：
                        <el-select v-model="selJobItemId" class="el_input self_input" size="mini" @change="fetchDataRecommend">
                            <el-option
                                v-for="(item, index) in published_joblist"
                                :key="index"
                                :label="item.jobname"
                                :value="item.id"
                            >
                            </el-option>
                        </el-select>
                    </div>
                </div>
                <div class="recommend_list">
                    <div v-for="(item,index) in recommend_list" :key="index" class="recommend_item_con" @click="funDetail(item.resume_link_url_web)">
                        <div class="recommend_item_title">
                         <div class="recommend_item_img">
                            <div class="item_img">
                              <img :src="item.photo_img_src" />
                            </div>
                            <div class="item_text">
                              <div class="fn_bar">
                                <a class="fullname" target="_blank" :href="item.resume_link_url_web">{{item.fullname}}</a>
                                <div class="hq" v-if="item.high_quality > 0"></div>
                                <div class="clear"></div>
                              </div>
                              <p>{{ item.age_text }}岁，{{ item.experience_text }}经验，{{ item.education_text }}</p>
                            </div>
                          </div>

                        </div>
                        <div class="recommend_item_information substring"><span>想找</span>{{ item.intention_jobs }}<span> 工作</span></div>
                        <div class="recommend_item_welfare substring">
                          <span>想在</span>{{item.intention_district}}<span> 工作</span>
                        </div>
                        <div class="recommend_item_company">{{ item.current_text }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 右边部分 320-->
        <div class="right_sidebar">
            <!-- 积分区域 -->
            <div class="integral_con">
                <div class="integral">
                  <div class="ic_txt">我的{{$store.state.config.points_byname}}：{{mypoints}}</div>
                  <div class="ic_tip" @click="$router.push('/company/service/point')">做任务赚{{$store.state.config.points_byname}}</div>
                </div>
                <div class="signIn">
                    <el-button class="integral_btn" round size="medium" @click="handlerSignin" v-if="hasSign === false">签到</el-button>
                    <el-button class="integral_btn disabled" round size="medium" disabled v-else>已签到</el-button>
                </div>
            </div>
            <!-- 职位信息 -->
          <div class="right_cb">
            <div class="c_name_b">
              <div class="c_name substring"><router-link to="/company/manage/baseinfo">{{companyinfo.companyname}}</router-link></div>
              <div class="c_rz" v-if="companyinfo.company_audit==1"></div>
              <div class="clear"></div>
            </div>
            <div class="c_bd">
              <div class="b_cell tel">手机已认证</div>
              <div class="b_cell wx" v-if="companyinfo.bind_weixin==0">微信未绑定</div>
              <div class="b_cell bd" v-if="companyinfo.bind_weixin==1">微信已绑定</div>
              <div class="clear"></div>
            </div>
            <div class="c_btn">
              <div class="adb" @click="handlerJobadd('btnLoading2')">发布职位</div>
              <div class="adj" @click="handlerRefreshBatch">刷新职位</div>
              <div class="clear"></div>
            </div>
            <div class="c_hb">
              <div class="h_cell" @click="$router.push('/company/service/increment/add/jobstick')">
                <div class="h_ico zd"></div><div class="h_txt">职位置顶</div><div class="bg_ico"></div>
              </div>
              <div class="h_cell" @click="$router.push('/company/service/increment/add/job_refresh')">
                <div class="h_ico sx"></div><div class="h_txt">智能刷新</div>
              </div>
              <div class="h_cell" @click="$router.push('/company/service/increment/add/emergency')">
                <div class="h_ico jj"></div><div class="h_txt">职位紧急</div>
              </div>
              <div class="h_cell" @click="$router.push('/company/service/increment/add/resume_package')">
                <div class="h_ico jl"></div><div class="h_txt">简历包</div>
              </div>
              <div class="clear"></div>
            </div>
            <div class="right_forward" @click="funPoster">
              转发到朋友圈
            </div>
          </div>
        </div>
        <el-dialog title="专属客服" :visible.sync="showServicerQrcode" width="350px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
            <div class="wx_img">
                <img :src="serviceInfo.wx_qrcode" width="175" height="175"/>
            </div>
        </el-dialog>
        <el-dialog title="投诉客服" :visible.sync="showServicerComplaint" width="450px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
            <CustomerServiceComplaint :cs-id="serviceInfo.id" @closeComplaint="showServicerComplaint=false"></CustomerServiceComplaint>
        </el-dialog>
        <Poster
          v-if="showPoster"
          :poster-id="posterId"
          type="company"
          @closeDialog="showPoster = false"
        />
    </div>
</template>

<script>
import CustomerServiceComplaint from "@/components/company/CustomerServiceComplaint";
import echarts from './echarts/index.vue'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Poster from '@/components/Poster'
export default {
  name: 'CompanyIndex',
  components:{
      echarts,
      CustomerServiceComplaint,
      Poster
  },
  filters: {
    timeFilter (timestamp) {
      return timestamp ? parseTime(timestamp, '{y}-{m}-{d}') : ''
    }
  },
  data(){
      return{
          screenWidth: document.body.clientWidth,
          timer:false,
          isMobile:true,
          isTel:true,
          showPoster: false,
          posterId: '',
          showServicerComplaint:false,
          showServicerQrcode:false,
          btnLoading1:false,
          btnLoading2:false,
          warpnavlist:[
              {num: 0, text: '收到投递', href: '/company/resume'},
              {num: 0, text: '我的下载', href: '/company/resume/download'},
              {num: 0, text: '面试邀请', href: '/company/resume/interview'},
              {num: 0, text: '我的收藏', href: '/company/resume/fav'},
              {num: 0, text: '浏览记录', href: '/company/resume/browsing'},
          ],
          recommend_list:[],
          switch_title:false,
          nature:[],
          companyinfo:{},
          setmeal:{},
          mypoints:0,
          resumelist_url_web:'',
          published_joblist:[],
          selJobItemId:'',
          serviceInfo:{},
          lave_refresh_count: 0,
          enable_num: 0,
          ad_dataset_banner: { alias: 'QS_member_company_banner', items: [] },
      }
  },
  created() {
    this.fetchData()
  },
  mounted () {
    window.addEventListener('resize', ()=>{
      if (!this.timer) {
          this.screenWidth = document.body.clientWidth
          if(this.screenWidth < 1200){
            if(this.serviceInfo.tel && this.serviceInfo.mobile){
              this.isTel = false
              this.isMobile = true
            } else {
              if(this.serviceInfo.tel!=''){
                this.isTel = true
                this.isMobile = false
              }else if(this.serviceInfo.mobile!=''){
                this.isTel = false
                this.isMobile = true
              }
            }
          } else{
            this.isTel = true
            this.isMobile = true
          }
          this.timer = true
          let that = this
          setTimeout(function () {
              that.timer = false
          }, 400)
      }
    })
    // window.onresize =()=>{
    //   return (()=>{
    //     this.screenWidth = document.body.clientWidth
    //   })()
    // }
  },
  watch: {
  },
  computed: {
    hasSign(){
      return this.$store.state.userSignin
    }
  },
  methods:{
        funPoster() {
          this.showPoster = true
          this.posterId = this.companyinfo.id
        },
        funDetail(url){
          window.open(url)
        },
        handleClickBanner(item){
            if(item.link_url !== ''){
                window.open(item.link_url)
            }
        },
        fetchDataPublishedJoblist () {
            http
                .get(api.get_publish_jobs, {})
                .then(res => {
                this.published_joblist = [...res.data.items]
                if (this.published_joblist.length > 0) {
                    this.selJobItemId = this.published_joblist[0].id
                    this.fetchDataRecommend()
                }
                })
                .catch(() => {})
        },
        fetchDataRecommend () {
            if (this.selJobItemId === 0) {
                return false
            }
            let params = {
                id: this.selJobItemId,
                page: 1,
                pagesize: 12
            }
            http
                .get(api.recommend_resumelist, params)
                .then(res => {
                    this.recommend_list = [...res.data.items]
                })
                .catch(() => {})
        },
        async fetchData () {
            try {
                let res = await http.post(api.company_index, {})
                const { companyinfo, manage, setmeal,mypoints,resumelist_url_web,refresh_count,enable_num } = {...res.data}
                this.warpnavlist[0].num = manage.job_apply.number
                this.warpnavlist[1].num = manage.down_resume.number
                this.warpnavlist[2].num = manage.interview.number
                this.warpnavlist[3].num = manage.fav.number
                this.warpnavlist[4].num = manage.view.number
                this.companyinfo = companyinfo
                this.setmeal = setmeal
                this.refresh_count = refresh_count
                this.mypoints = mypoints
                this.resumelist_url_web = resumelist_url_web
                this.enable_num = enable_num
                // 套餐剩余免费刷新数
                let setmealRefreshCount = parseInt(this.setmeal.refresh_jobs_free_perday)
                if (setmealRefreshCount <= 0) {
                  this.lave_refresh_count = 0
                } else if (setmealRefreshCount <= parseInt(this.refresh_count)) {
                  this.lave_refresh_count = 0
                } else {
                  this.lave_refresh_count = setmealRefreshCount - parseInt(this.refresh_count)
                }
                if(parseInt(companyinfo.notice_auth_complete) === 1){
                  this.$confirm('根据相关法律法规要求，需要您完善企业认证信息','系统提示',{
                      confirmButtonText: '去完善',
                      type: 'warning'
                  })
                  .then(() => {
                    this.$router.push('/company/auth')
                  })
                  .catch(() => {
                      // on cancel
                  })
                }

                res = await http.get(api.get_publish_jobs, {})
                this.published_joblist = [...res.data.items]
                if (this.published_joblist.length > 0) {
                    this.selJobItemId = this.published_joblist[0].id
                    this.fetchDataRecommend()
                }
                res = await http.get(api.customer_service, {})
                this.serviceInfo = { ...res.data.info }
                res = await http.post(api.ad_list, {alias: ['QS_member_company_banner@web']})
                this.ad_dataset_banner.items = res.data.items['QS_member_company_banner@web']
            } catch (err) {
                console.log(err)
            }
      },
        handlerJobadd (btnName) {
			this[btnName] = true
        http
            .get(api.company_check_jobadd_num, {})
            .then(res => {
				this[btnName] = false
            if (res.data.enable_addjob_num <= 0) {
                this.$confirm('您当前是' +
                    res.data.setmeal_name +
                    '，当前可发布职位数为0，建议您立即升级套餐或将暂时不招聘的职位设为关闭！','系统提示',{
                    confirmButtonText: '升级套餐',
                    type: 'warning'
                })
                .then(() => {
                 this.$router.push('/company/service/setmeal')
                })
                .catch(() => {
                    // on cancel
                })
                return false
            } else {
                this.$router.push('/company/jobadd')
            }
            })
            .catch(() => {})
        },
        handlerRefreshBatch () {
            this.$confirm('确定刷新所有发布中的职位吗？','系统提示',{
                type: 'warning'
            })
            .then(() => {
            http
                .post(api.company_job_refresh_batch, {})
                .then(res => {
                if (res.data.done === 0) {
                    this.$confirm(res.message,'系统提示',{type: 'warning'})
                    .then(() => {
                        this.$router.push('/company/joblist')
                    })
                    .catch(() => {
                        // on cancel
                    })
                    return false
                } else {
                    this.$message({ type: 'success', message: res.message })
                }
                })
                .catch(() => {})
            })
            .catch(() => {
            // on cancel
            })
        },
        fetchServiceData () {
        http
            .get(api.customer_service, {})
            .then(res => {
            this.serviceInfo = { ...res.data.info }
            })
            .catch(() => {})
        },
        wakeupQq(qq){
            if(qq){
                window.open('http://wpa.qq.com/msgrd?v=3&uin='+qq+'&site=在线客服&menu=yes')
            }
        },
        fetchAd () {
        http
            .post(api.ad_list, {
            alias: ['QS_member_company_banner@web']
            })
            .then(res => {
            this.ad_dataset_banner.items =
                res.data.items['QS_member_company_banner@web']
            })
            .catch(() => {})
        },
        handlerSignin () {
          http.get(api.member_signin).then(res => {
            this.$store.commit('setUserSignin', {
              signin: true
            })
            this.$store.commit('setUserPoints',{
              points:res.data.points
            })
            this.$message({ type: 'success', message: res.message })
          }).catch(() => {})
        },
        toSearchResume(){
            window.open(this.resumelist_url_web)
        },
  }
}
</script>

<style lang="scss" scoped>
.customer_service{
  min-height: 62px;
  border: 1px solid #EBEEF5;
  margin-bottom: 18px;
  border-radius: 5px;
  box-shadow: 0 2px 12px 0 rgb(0 0 0 / 10%);
  padding-left:90px;
  background: url(../../assets/images/member/customer_service_img.png) no-repeat left center #FFF;
  display: flex;
  align-items: center;
  justify-content: space-between;
  .left_box{
    display: flex;
    align-items: center;
  }
  .right_box {
    display: flex;
    align-items: center;
    margin-right: 30px;
  }
  .portrait{
    width:44px ;
    height: 44px;
    margin-right: 15px;
    border-radius: 50%;
    img{
      width: 100%;
      height: 100%;
      border-radius: 50%;
    }
  }
  .text_1{
    color:#333333;
    font-size: 18px;
    font-weight: bold;
    margin-right: 14px;
    white-space:nowrap;
  }
  .text_2{
    color:#999999 ;
    font-size: 12px;
    margin-right: 40px;
    cursor: pointer;
    white-space:nowrap;
  }
  .text_3,
  .text_4{
    margin-right: 20px;
    color:#333333 ;
    font-size: 14px;
    font-weight: 500;
  }
  .text_3{
    // width: 190px;
    .text_3_box{
      padding-left: 35px;
      background: url(../../assets/images/member/customer_icon_1.png) no-repeat left center /25px 25px;
      white-space:nowrap;
    }
  }
  .text_4{
    // width: 210px;
    .text_4_box{
      padding-left: 35px;
      background: url(../../assets/images/member/customer_icon_2.png) no-repeat left center/26px 26px;
      white-space:nowrap;
    }
  }
  .QQ_btn{
    display: block;
    width: 90px;
    height: 30px;
    border-radius:30px;
    line-height: 30px;
    padding-left: 30px;
    color: #fff;
    font-size: 12px;
    border: 1px solid #1787fb;
    margin-right: 20px;
    cursor: pointer;
    background: url("../../assets/images/customer_qq.png") no-repeat 12px center#1787fb;
    &:hover{
      text-decoration: none;
      background-color: #157eeb;
    }
  }
  .Wx_btn{
    display: block;
    width: 90px;
    height: 30px;
    border-radius:30px;
    line-height: 30px;
    color: #fff;
    font-size: 12px;
    padding-left: 30px;
    border: 1px solid #37c561;
    cursor: pointer;
    background: url("../../assets/images/customer_wx.png") no-repeat 11px center #37c561;
    &:hover{
      text-decoration: none;
      background-color: #33b65a;
    }
  }
}
  .card{
    display: flex;height: 100px;justify-content: space-between;
    .cardItem{
      .i_title {
        font-size: 14px;
      }
      .i_num {
        font-size: 30px;margin-bottom: 2px;
      }
      &::after {
        content: '';position: absolute;right: 15px;top: 15px;width: 70px;height: 70px;
      }
      &.i1 {
        background-image: linear-gradient(to right, #88ebb2 , #a1f7bd);
        &::after {
          background: url("../../assets/images/member/16.png") center center no-repeat;background-size: 70px 70px;
        }
      }
      &.i2 {
        background-image: linear-gradient(to right, #69b0ff , #81c8ff);
        &::after {
          background: url("../../assets/images/member/17.png") center center no-repeat;background-size: 70px 70px;
        }
      }
      &.i3 {
        background-image: linear-gradient(to right, #feb6a5 , #ffc5b7);
        &::after {
          background: url("../../assets/images/member/18.png") center center no-repeat;background-size: 70px 70px;
        }
      }
      &.i4 {
        background-image: linear-gradient(to right, #ffc893 , #ffdcab);
        &::after {
          background: url("../../assets/images/member/19.png") center center no-repeat;background-size: 70px 70px;
        }
      }
      &.i5 {
        background-image: linear-gradient(to right, #8fa9ff , #a4bcff);
        &::after {
          background: url("../../assets/images/member/20.png") center center no-repeat;background-size: 70px 70px;
        }
      }
      position: relative;flex: 1;height: 100px;padding: 25px 0 0 27px;transition: linear all .2s;color: #fff;
      border-radius: 8px;background-color: #0a79eb;margin-right: 10px;cursor: pointer;
      &:hover {
        -webkit-transform:translateY(-3px);transform:translateY(-3px);z-index:1;
      }
    }
  }
  .companyVipDetailsLInk{
    flex: 1.5;padding-left: 20px;
    font-size: 14px;
    color: #ff704f;
    line-height: 23px;
    cursor:pointer;
    background: url("../../assets/images/viplog.png") 0 4px no-repeat;
    white-space:nowrap;
  }
  .companyVip {
    .v_ic_date {
      float: left;padding-top: 3px;
      font-size: 14px;color: #999;
    }
    .v_ic_tit {
      float: left;margin: 0 10px 0 5px;
      font-size: 18px;color: #333;font-weight: bold;
    }
    .v_ic_b {
      position: relative;float: left;width: 20px;height: 23px;
      img {
        position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);width: 20px;height: 17px;
      }
    }
  }
  /*右侧企业信息*/
  .right_cb {
    .c_hb {
      .h_cell {
        .h_txt {
          font-size: 14px;color: #333;
        }
        .h_ico {
          &.jl {
            background: url("../../assets/images/operation_4.png") 0 0 no-repeat;
          }
          &.jj {
            background: url("../../assets/images/operation_3.png") 0 0 no-repeat;
          }
          &.sx {
            background: url("../../assets/images/operation_2.png") 0 0 no-repeat;
          }
          &.zd {
            background: url("../../assets/images/operation_1.png") 0 0 no-repeat;
          }
          width: 46px;height: 46px;margin: 0 auto 6px;
          border-radius: 4px;
        }
        .bg_ico {
          position: absolute;right: 10px;top: -18px;width: 53px;height: 38px;
          background: url("../../assets/images/com_index_bg_ico.png") 0 0 no-repeat;
        }
        position: relative;float: left;width: 135px;margin-bottom: 25px;
        text-align: center;cursor: pointer;
      }
    }
    .c_btn {
      .adb {
        float: left;width: 135px;height: 54px;padding-top: 16px;padding-left: 56px;font-size: 14px;color: #fff;cursor: pointer;
        background: url("../../assets/images/com_index_adj_bg.png") 0 0 no-repeat;background-size: 139px 54px;transition: linear all .2s;
        &:hover {
          -webkit-transform:translateY(-3px);transform:translateY(-3px);z-index:1;
        }
      }
      .adj {
        float: right;width: 135px;height: 54px;padding-top: 16px;padding-left: 56px;font-size: 14px;color: #fff;cursor: pointer;
        background: url("../../assets/images/com_index_re_bg.png") 0 0 no-repeat;background-size: 139px 54px;transition: linear all .2s;
        &:hover {
          -webkit-transform:translateY(-3px);transform:translateY(-3px);z-index:1;
        }
      }
      margin-bottom: 35px;
    }
    .c_bd {
      .b_cell {
        &.wx {
          background: url("../../assets/images/positionWx.png") 0 0 no-repeat;
        }
        &.bd {
          background: url("../../assets/images/positionBd.png") 0 0 no-repeat;
        }
        &.tel {
          background: url("../../assets/images/positionTel.png") 0 0 no-repeat;
        }
        float: left;line-height: 16px;padding-left: 22px;margin-right: 20px;
        font-size: 12px;color: #999;
      }
      margin-bottom: 15px;
    }
    .c_name_b {
      .c_rz {
        float: left;width: 22px;height: 20px;margin-left: 5px;
        background: url("../../assets/images/com_index_rz_ico.png") 0 3px no-repeat;
      }
      .c_name {
        a {
          font-size: 16px;color: #333;
        }
        float: left;max-width: 240px;line-height: 20px;
      }
      margin-bottom: 15px;
    }
    width: 320px;min-height: 395px;padding: 25px 25px 28px;margin-bottom: 10px;
    background-color: #fff;border-radius: 7px;box-shadow: 0 2px 12px 0 rgba(0,0,0,.1);
    .right_forward{
      width: 220px;
      height: 40px;
      margin: 0 auto;
      border:1px solid #3bc664;
      padding-left: 82px;
      border-radius: 30px;
      display: flex;
      line-height: 39px;
      background: url(../../assets/images/member/wx_circle.png) no-repeat 44px center /21px 21px;
      color: #50b674;
      font-size: 16px;
      cursor: pointer;
    }
  }
  // 积分区域
  .integral_con{
    .integral_btn{
      width: 80px;font-size: 14px;border: 0;padding: 9px 20px;transition: all .3s;-webkit-transition: all .3s;
      position: absolute;right: 19px;top: 27px;background: #ff704f; color: #fff;
      &:hover { background: #e66648; }
      &.disabled {
        color: #a7a7a7;background: #f3f3f3;
        &:hover { color: #a7a7a7;background: #f3f3f3; }
      }
    }
    .ic_tip {
      padding-left: 40px;color: #1787fb;font-size: 14px;cursor:pointer;
    }
    .ic_txt {
      font-size: 14px;color: #333;padding-left: 40px;position: relative;margin-bottom: 10px;
      background: url("../../assets/images/integral.png") 23px center no-repeat;
    }
    width: 320px;height: 80px;background: #fff;border-radius: 7px;margin-bottom: 10px;position: relative;padding-top: 20px;
    box-shadow: 0 2px 12px 0 rgba(0,0,0,.1);
  }
  .el-button.nbd {
    border: 0;padding: 10px 24px;transition: all .3s;-webkit-transition: all .3s;
  }
  .el-button.nbd:hover {
    background: #e66648;
  }
 .companyIndex{
   display: flex;
 }
 .left_sidebar{
   flex: 1;
   // width: calc(100% - 320px);
   // width: 80%;
   margin-right: 10px;
 }

 .right_sidebar{
   width: 320px !important;
 }

// vip

.el-card{
    margin-bottom: 10px;
}
.companyWarp{
    display: flex;
    margin-bottom: 42px;
    align-items: center;
}
.companyVip{
    flex: 8;
}
.vipBigTile{
    font-size: 18px;
    color: #333333;
    margin:0  5px;
}
.vipMixTile{
    font-size: 14px;
    color: #999999;
}

.companyVipDetails{
    display: flex;
    text-align: center;
    padding-bottom: 50px;
}
.companyVipDetails>div{
    flex: 1;
}
.companyVipDetails .el-button{
    background: #ff704f;
    color: #ffffff;
    font-size: 12px;
}
.companyVipDetails .DetailsTitle{
    color: #666666;
    font-size: 16px;
}
.companyVipDetails .DetailsNumber{
    color: #ff704f;
    font-size: 24px;
    height: 60px;
    padding: 10px 0;
}
.companyVipDetails .DetailsNumber>span{
    font-size: 16px;
}
.detailsItem{
    margin: 14px;
}
.detailsItem a{
    font-size: 12px;
    color: #ff704f;
}
.banner{
    height: 80px;
    margin: 10px 0;
    background: #fff;
}
.banner img{
    width: 100%;
    height: 100%;
}

// 人才推荐
.recommend_content{
    background: #fff;
}
.recommend{
    display: flex;
    align-items: center;
}
.recommend_title{
    flex: 8;
    font-size:18px ;
    color: #333333;
    padding: 30px 20px 30px 45px;font-weight: bold;
  background: url("../../assets/images/recommendTitleLogo.png") 20px 31px no-repeat;
}
  .recommend_title .t_sub {
    font-size: 14px;color: #999;font-weight: normal;
  }
.recommend_select{
    flex: 2;
}

.el_input{
    width: 140px;
}

.recommend_list{
    display: flex;
    flex-wrap:wrap;
    margin: 0px -1px -1px -1px;
}
.recommend_list>div{
    width: 321.5px;
    border: 1px solid #f3f3f3;
    margin:0px -1px -1px 0px ;transition: linear all .2s;
  &:hover {
    -webkit-transform:translateY(-3px);transform:translateY(-3px);z-index:1;
  }
}
.recommend_item_con>div{
    margin: 0 20px;
}
.recommend_item_con:hover{
  cursor:pointer;
}
.recommend_item_con .fn_bar .fullname{
  display: block;float: left;height: 22px;line-height: 22px;
    cursor:pointer;
    color: #333;
    font-size: 16px;
}
.recommend_item_con .fn_bar .hq {
	float: left;
	margin-left: 10px;
	width: 45px;
	height: 22px;
  background: url("../../assets/images/great.png") 0 center no-repeat;
}
.recommend_item_con .fn_bar .fullname:hover{
    color:#4196E1;
    text-decoration-line:underline;
}

.recommend_item_title{
    font-size: 12px;
    padding: 10px 0 ;
    color: #a9a9a9;
}
.recommend_item_title>div{
  padding: 5px 0;
}
.recommend_item_title>div div{
  font-size: 17px;
  color: #333333;
  margin-right: 15px;
  display: inline-block;
}
.recommend_item_information,.recommend_item_welfare{
    margin: 6px 0;
    font-size: 14px;
    color: #666;
}
.recommend_item_information>span,.recommend_item_welfare>span{
    color: #999999;
    margin-right: 5px;
    font-size: 13px;
}
.recommend_item_welfare{
    padding: 10px 0 14px 0;
    border-bottom: 1px solid #f3f3f3;
}
.recommend_item_border{
    height: 5px;
    background: #ccc;
}
.recommend_item_company{
    padding: 12px 0 17px 0;
    color: #999999;
    font-size: 12px;
}
.nature{
    margin: 5px 11px;
    padding: 5px 11px;
    background: #e9f8ff;
    margin: 0 3px;
    font-size: 12px;
}
.recommend_item_img{
  display: flex;
}
.item_img{
  width: 44px;
  height:44px;
  border-radius: 50%;
}
.item_img img{
  width: 100%;
  height: 100%;
  border-radius: 50%;
}
.item_text p{
  padding-top: 7px;
  color:#999 ;
  font-size: 12px;
}

// 职位信息
.user{
    display: flex;
    padding: 4px 0;
    font-size:12px ;
    color: #999;
}
.user div{
    width: 83px;
    height: 16px;
    font-size: 12px;
}
.user div img{
    vertical-align: middle;
    margin-right: 5px;
}
.user div:nth-of-type(1){
    margin-right: 10px;
}
.refresh_btn{
    background: #3b71f7;
}
.operation{
    display: flex;
    flex-wrap: wrap;
    padding: 10px 0;
}
.operation div{
    width: 138px;
    text-align: center;
    margin: 10px 0;
    position: relative;
    cursor:pointer;
}
.operation div>p{
    margin: 5px 0;
    font-size: 14px;
    color: #333;
}
.positionTitle{
    font-size: 16px;
    color: #333333;
    // font-weight: bold;
    line-height: 43px;
}

// .btns{
// margin: 15px 0;
// overflow: hidden;
// }

// .btns button{
//     padding: 12px 25px;
// }

// .btns button:nth-of-type(1){
//     margin-right: 10px;
// }
.icon_plus{
    background: #FFFFFF;
    border-radius: 50%;
    font-size: 12px;
    padding: 2px;
}
.icon_plus_color{
    color: #ff8468;
}
.icon_refresh_color{
    color: #4d81f8;
}
.operation_topIcon{
    position: absolute;
    right: 20px;
    top: -13px;
    font-size: 10px;
    background: #ff1c00;
    color:#fff ;
    padding: 4px 3px;
    border-radius: 6px 6px 6px 0;
}

.customer_information{
    display: flex;
    margin-top: 20px ;
}
.customer_informationImg{
    margin-left: 5px;
    img{
        width:68px;
        height:68px;
        border-radius:50%;
    }
}
.detailed_information{
    flex: 1;
    margin-left: 19px;
}
.customer_name{
    font-size: 16px;
    color: #333333;
}

.customer_tel{
    font-size: 14px;
    color: #666666;
    margin-bottom: 13px;
}
.wx_img{
    text-align: center;
}
</style>
