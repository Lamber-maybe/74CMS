<template>
  <div class="dashboard-container">
    <el-row :gutter="20">
      <el-col :span="18">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span>今日信息统计</span>
            </div>
            <el-row :gutter="20">
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/member/personal')">
                    <div class="tit1">新增个人会员</div>
                    <div class="num">{{ baseinfo.today_data.reg_personal_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.reg_personal_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/resume/list')">
                    <div class="tit1">新增简历</div>
                    <div class="num">{{ baseinfo.today_data.resume_add_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.resume_add_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/resume/list')">
                    <div class="tit1">简历刷新数</div>
                    <div class="num">{{ baseinfo.today_data.resume_refresh_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.resume_refresh_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/personal_jobapply')">
                    <div class="tit1">投递数</div>
                    <div class="num">{{ baseinfo.today_data.job_apply_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.job_apply_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/business/personal/order')">
                    <div class="tit1">个人完成订单</div>
                    <div class="num">{{ baseinfo.today_data.orderpay_personal_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.orderpay_personal_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
            </el-row>
            <el-row :gutter="20" style="margin-top:20px;">
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/member/company')">
                    <div class="tit1">新增企业会员</div>
                    <div class="num">{{ baseinfo.today_data.reg_company_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.reg_company_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/job/list')">
                    <div class="tit1">新增职位</div>
                    <div class="num">{{ baseinfo.today_data.job_add_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.job_add_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/job/list')">
                    <div class="tit1">职位刷新数</div>
                    <div class="num">{{ baseinfo.today_data.job_refresh_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.job_refresh_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/user/company_down')">
                    <div class="tit1">下载数</div>
                    <div class="num">{{ baseinfo.today_data.down_resume_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.down_resume_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
              <el-col :span="4-8">
                <el-card shadow="hover" class="no-border">
                  <div class="today-info" @click="$router.push('/business/company/order')">
                    <div class="tit1">企业完成订单</div>
                    <div class="num">{{ baseinfo.today_data.orderpay_company_today }}</div>
                    <div class="tit2">昨日：{{ baseinfo.today_data.orderpay_company_yesterday }}</div>
                  </div>
                </el-card>
              </el-col>
            </el-row>
          </el-card>
        </el-row>
        <el-row style="margin-top:20px;">
          <el-card>
            <el-tabs v-model="chartTabSelected">
              <el-tab-pane label="个人/企业注册" name="0" />
              <el-tab-pane label="下载简历" name="1" />
              <el-tab-pane label="投递职位" name="2" />
              <el-tab-pane label="收入金额" name="3" />
            </el-tabs>
            <reg_line v-if="chartTabSelected=='0'" />
            <down_resume_line v-if="chartTabSelected=='1'" />
            <jobapply_line v-if="chartTabSelected=='2'" />
            <income_line v-if="chartTabSelected=='3'" />
          </el-card>
        </el-row>
      </el-col>
      <el-col :span="6">
        <el-row>
          <el-card>
            <div slot="header" class="clearfix">
              <span>今日待办</span>
            </div>
            <el-table
              :show-header="false"
              :data="baseinfo.pending_data"
              style="width: 100%;cursor:pointer;"
              @row-click="handlerClickPending"
            >
              <el-table-column
                prop="title"
                label="待办事项"
                width="180"
              />
              <el-table-column
                prop="num"
                label="数量"
                align="right"
              >
                <template slot-scope="scope">
                  <span class="num-circle">{{ scope.row.num }}</span>
                  <i class="el-icon-arrow-right" />
                </template>
              </el-table-column>
            </el-table>
          </el-card>
        </el-row>
        <el-row style="margin-top:20px;">
          <el-card>
            <div slot="header" class="clearfix">
              <span>更新日志</span>
            </div>
            <el-timeline :reverse="false">
              <el-timeline-item
                v-for="(item, index) in upgradeLog"
                :key="index"
                :timestamp="item.time"
              >
                <el-link target="_blank" :href="item.url">{{ item.title }}</el-link>
              </el-timeline-item>
            </el-timeline>
          </el-card>
        </el-row>
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <el-card>
        <div slot="header" class="clearfix">
          <span>服务器信息</span>
        </div>
        <el-row :gutter="20" style="font-size:13px;">
          <el-col :span="6">
            操作系统：{{ baseinfo.server_info.os }}
          </el-col>
          <el-col :span="6">
            PHP版本：{{ baseinfo.server_info.php_version }}
          </el-col>
          <el-col :span="6">
            MySQL版本：{{ baseinfo.server_info.mysql_version }}
          </el-col>
          <el-col :span="6">
            web服务器：{{ baseinfo.server_info.web_server }}
          </el-col>
        </el-row>
        <el-row :gutter="20" style="font-size:13px;margin-top:20px;">
          <el-col :span="6">
            允许最大上传文件：{{ baseinfo.server_info.upload_max }}
          </el-col>
          <el-col :span="6">
            curl版本：{{ baseinfo.server_info.curl_version }}
          </el-col>
          <el-col :span="6">
            ThinkPHP框架版本：{{ baseinfo.server_info.framework_version }}
          </el-col>
          <el-col :span="6">
            服务器当前时间：{{ baseinfo.server_info.server_time }}
          </el-col>
        </el-row>
      </el-card>
    </el-row>
    <el-row style="margin-top:20px;">
      <el-card>
        <div slot="header" class="clearfix">
          <span>系统信息</span>
        </div>
        <el-row :gutter="20" style="font-size:13px;">
          <el-col :span="6">
            当前版本：<span class="color-link">v{{ baseinfo.version }}</span>
          </el-col>
          <el-col :span="6">
            授权类型：<a class="a-link color-link" href="https://www.74cms.com" target="_blank">{{ authorize.authorize }}</a>
          </el-col>
          <el-col :span="6">
            骑士人才系统官网：<a class="a-link" href="https://www.74cms.com" target="_blank">www.74cms.com</a>
          </el-col>
          <el-col :span="6">
            程序开发：74CMS程序开发组
          </el-col>
        </el-row>
        <el-row :gutter="20" style="font-size:13px;margin-top:10px;">
          <el-col>
            <div v-html="authorize.copyright" />
          </el-col>
        </el-row>
      </el-card>
    </el-row>
    <el-row style="margin-top:20px;">
      <el-card>
        <div slot="header" class="clearfix">
          <span>官方动态</span>
        </div>
        <el-col :span="14">
          <div class="official-news-list">
            <div v-for="(item,index) in officialNews" :key="index" class="item"><a class="a-link" :href="item.link" target="_blank">{{ item.title }}</a><span class="time">{{ item.addtime }}</span></div>
          </div>
          <div class="clearfix" />
        </el-col>
        <el-col :span="10" style="text-align:center;">
          <img class="qrcode" src="static/wechat.jpg">
        </el-col>
      </el-card>
    </el-row>
  </div>
</template>

<script>
import { dashboardBaseinfo, dashboardUpgradeLog, dashboardAuthorize, dashboardOfficialNews } from '@/api/dashboard'
import reg_line from './components/reg_line.vue'
import down_resume_line from './components/down_resume_line.vue'
import jobapply_line from './components/jobapply_line.vue'
import income_line from './components/income_line.vue'

export default {
  name: 'Dashboard',
  components: {
    reg_line,
    down_resume_line,
    jobapply_line,
    income_line
  },
  data() {
    return {
      baseinfo: {
        today_data: {},
        pending_data: [],
        server_info: {},
        version: ''
      },
      upgradeLog: [],
      authorize: {},
      officialNews: [],
      chartTabSelected: '0'
    }
  },
  computed: {
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      dashboardBaseinfo({}).then(response => {
        this.baseinfo = response.data
      })
      dashboardUpgradeLog({}).then(response => {
        this.upgradeLog = response.data
      })
      dashboardAuthorize({}).then(response => {
        this.authorize = response.data
      })
      dashboardOfficialNews({}).then(response => {
        this.officialNews = response.data
      })
    },
    handlerClickPending(e){
      switch (e.alias){
        case 'company_audit':
          this.$router.push('/user/company/noaudit')
          return
        case 'job_audit':
          this.$router.push('/user/job/noaudit')
          return
        case 'resume_audit':
          this.$router.push('/user/resume/noaudit')
          return
        case 'cancel_apply':
          this.$router.push('/user/cancel_apply')
          return
        case 'tipoff':
          this.$router.push('/content/feedback/tipoff')
          return
        case 'feedback':
          this.$router.push('/content/feedback/suggest')
          return
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.dashboard {
	&-container {
		margin: 20px;
	}
	&-text {
		font-size: 30px;
		line-height: 46px;
	}
}
.el-col--4{
  width:20%;
}
.a-link{
  color:#409eff;
}
.a-link:hover{
  color:#66b1ff;
  text-decoration: underline;
}
.el-timeline{
padding-top:10px;height:227px;overflow-y:scroll;
margin-left:-25px;
}
.official-news-list{
  font-size:14px;
  margin-bottom:20px;
  // width:500px;
 .item{
   height:30px;
   line-height:30px;
   border-bottom:1px dotted #e3e3e3;
   a{
     width:80%;
     display:inline-block;
     overflow: hidden;
     white-space: nowrap;
     text-overflow: ellipsis;
   }
   .time{
     font-size:13px;
     color:#999;
     float:right;
   }
 }
}
.qrcode{
  // width:600px;
}
.today-info{
  cursor:pointer;
  .tit1{
    font-size:14px;
    color:#999;
  }
  .num{
    font-size:18px;
    color:#666;
    margin-top:6px;
  }
  .tit2{
    font-size:12px;
    color:#999;
    margin-top:6px;
  }
}
.clearfix{
  clear:both;
}
.num-circle{
  color:#fff;
  margin-right: 10px;
  background-color:#ff5722;
  border-radius:10px;
  padding:2px 6px;
  font-size:12px;
}
.color-link{
  font-size: 12px;
  padding: 3px 6px;
  background-color:#ff5722;
  color:#fff;
  border-radius:4px;
}
.color-link:hover{
  color:#fff;
  text-decoration:none;
}
.no-border{
  border:0;
}
</style>
