<template>
  <div class="app-container">
    <div v-if="baseinfo.warning.rewrite == 1" class="tip danger">
      <p>
        系统检测到您的伪静态还没配置完成，将影响系统的正常运行，请先配置 >>
        <a
          style="color: #1e88e5"
          href="http://doc.74cms.com/#/se/quickstart?id=%e9%85%8d%e7%bd%aeurl%e9%87%8d%e5%86%99%e8%a7%84%e5%88%99"
          target="_blank"
        >配置教程</a>
      </p>
    </div>
    <div v-if="baseinfo.warning.install == 1" class="tip danger">
      <p>您还没有删除 install 文件夹，出于安全的考虑，我们建议您删除 install 文件夹。install文件夹位于 /public/ 目录下</p>
    </div>
    <div v-if="new_version_notice == 1" class="tip">
      <p>
        系统检测到新版本，为了更好的使用体验，建议您立即升级程序
        <a style="color: #1e88e5" href="javascript:" @click="jumpPath('/upgrade', '您的权限不足，请联系超级管理员升级系统。')">立即升级</a>
      </p>
    </div>
    <el-row :gutter="20">
      <el-col :span="18">
        <el-row :gutter="20">
          <el-col :span="6">
            <div v-loading="newInitDataGeneralLoading" class="topBox">
              <img class="icon" src="../../assets/images/dashboard/incomeIcon.png" alt="">
              <div class="topBox1">收入（总/月）元</div>
              <div class="topBox2">
                <span>{{ revenue.total }}</span>
                /
                <span>{{ revenue.month }}</span>
              </div>
              <div id="top_line1" style="height:100px;" />
              <div class="topBox3">
                <span>今日收入</span>
                <span>{{ revenue.today }}</span>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div v-loading="newInitDataGeneralLoading" class="topBox">
              <img class="icon" src="../../assets/images/dashboard/OrderIcon.png" alt="">
              <div class="topBox1">支付订单（总/月）</div>
              <div class="topBox2">
                <span>{{ order_num.total }}</span>
                /
                <span>{{ order_num.month }}</span>
              </div>
              <div id="top_line2" style="height:100px;" />
              <div class="topBox3">
                <span>今日订单</span>
                <span>{{ order_num.today }}</span>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div v-loading="newInitDataGeneralLoading" class="topBox">
              <img class="icon" src="../../assets/images/dashboard/memberIcon.png" alt="">
              <div class="topBox1">企业会员数</div>
              <div class="topBox2">
                <span>{{ company.total }}</span>
                <span class="label_small">{{ company.total_job }}职位</span>
              </div>
              <div id="top_line3" style="height:100px;" />
              <div class="topBox3">
                <span>本月新增会员</span>
                <span>{{ company.month }}</span>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div v-loading="newInitDataGeneralLoading" class="topBox">
              <img class="icon" src="../../assets/images/dashboard/memberIcon.png" alt="">
              <div class="topBox1">个人会员数</div>
              <div class="topBox2">
                <span>{{ personal.total }}</span>
                <span class="label_small">{{ personal.total_resume }}简历</span>
              </div>
              <div id="top_line4" style="height:100px;" />
              <div class="topBox3">
                <span>本月新增会员</span>
                <span>{{ personal.month }}</span>
              </div>
            </div>
          </el-col>
        </el-row>
        <el-row style="margin-top: 20px;margin-bottom: 20px;">
          <el-card>
            <div slot="header" class="clearfix"><span>快捷菜单</span></div>
            <div class="shortcutMenu">
              <div class="shortcutMenuItem" @click="jumpPath('/user/company/crm/myClient')">
                <img class="icon" src="../../assets/images/dashboard/shortMenuIcon1.png" alt="">
                <span class="text">我的客户</span>
              </div>
              <div class="shortcutMenuItem" @click="jumpPath('/user/job/list')">
                <img class="icon" src="../../assets/images/dashboard/shortMenuIcon2.png" alt="">
                <span class="text">职位管理</span>
              </div>
              <div class="shortcutMenuItem" @click="jumpPath('/user/urmList')">
                <img class="icon" src="../../assets/images/dashboard/shortMenuIcon3.png" alt="">
                <span class="text">简历管理</span>
              </div>
              <div class="shortcutMenuItem" @click="jumpPath('/sys/company/setmeal')">
                <img class="icon" src="../../assets/images/dashboard/shortMenuIcon4.png" alt="">
                <span class="text">套餐管理</span>
              </div>
              <div class="shortcutMenuItem" @click="jumpPath('/tool/marketing/wx_offiaccount/index')">
                <img class="icon" src="../../assets/images/dashboard/shortMenuIcon5.png" alt="">
                <span class="text">公众号营销</span>
              </div>
              <div class="shortcutMenuItem" @click="jumpPath('/content/ad/list')">
                <img class="icon" src="../../assets/images/dashboard/shortMenuIcon6.png" alt="">
                <span class="text">广告管理</span>
              </div>
              <div class="shortcutMenuItem" @click="jumpPath('/sys/basic/config')">
                <img class="icon" src="../../assets/images/dashboard/shortMenuIcon7.png" alt="">
                <span class="text">系统配置</span>
              </div>
            </div>
          </el-card>
        </el-row>
        <el-row>
          <el-card>
            <div slot="header" class="clearfix refreshBox">
              <span class="refreshBoxData">实时数据</span>
              <div class="refresh">
                <span class="refreshBtnTime">更新时间：{{ nowTime }}</span>
                <span class="refreshBtn" @click="newInitDataRealTime()">刷新</span>
              </div>
            </div>
            <div v-loading="newInitDataRealTimeLoading">
              <el-row :gutter="20">
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/member/personal')">
                      <div class="tit1">新增个人会员</div>
                      <div class="num">{{ baseinfo.today_data.reg_personal_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.reg_personal_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/urmList')">
                      <div class="tit1">新增简历</div>
                      <div class="num">{{ baseinfo.today_data.resume_add_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.resume_add_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/urmList')">
                      <div class="tit1">简历刷新数</div>
                      <div class="num">{{ baseinfo.today_data.resume_refresh_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.resume_refresh_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/job_apply')">
                      <div class="tit1">投递数</div>
                      <div class="num">{{ baseinfo.today_data.job_apply_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.job_apply_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/business/personal/order')">
                      <div class="tit1">个人完成订单</div>
                      <div class="num">{{ baseinfo.today_data.orderpay_personal_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.orderpay_personal_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
              </el-row>
              <el-row :gutter="20" style="margin-top: 20px">
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/member/company')">
                      <div class="tit1">新增企业会员</div>
                      <div class="num">{{ baseinfo.today_data.reg_company_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.reg_company_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/job/list')">
                      <div class="tit1">新增职位</div>
                      <div class="num">{{ baseinfo.today_data.job_add_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.job_add_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/job/list')">
                      <div class="tit1">职位刷新数</div>
                      <div class="num">{{ baseinfo.today_data.job_refresh_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.job_refresh_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/user/company_down')">
                      <div class="tit1">下载数</div>
                      <div class="num">{{ baseinfo.today_data.down_resume_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.down_resume_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
                <el-col :span="4 - 8" class="bg_card">
                  <el-card shadow="never" class="no-border">
                    <div class="today-info" @click="jumpPath('/business/company/order')">
                      <div class="tit1">企业完成订单</div>
                      <div class="num">{{ baseinfo.today_data.orderpay_company_today }}</div>
                      <div class="tit2">昨日：{{ baseinfo.today_data.orderpay_company_yesterday }}</div>
                    </div>
                  </el-card>
                </el-col>
              </el-row>
            </div>
          </el-card>
        </el-row>
        <el-row style="margin-top: 20px" class="chartTableBox">
          <el-card>
            <el-col :span="18">
              <el-tabs v-model="chartTabSelected">
                <el-tab-pane label="注册趋势" name="0" />
                <el-tab-pane label="收入趋势" name="1" />
                <el-tab-pane label="个人活跃度" name="2" />
                <el-tab-pane label="企业活跃度" name="3" />
              </el-tabs>
            </el-col>
            <el-col :span="6" />
            <reg_line :date7="date7" :date30="date30" :tab-val="chartTabSelected" />
          </el-card>
        </el-row>
      </el-col>
      <el-col :span="6">
        <el-row class="pending_data" style="margin-bottom: 20px;">
          <el-card>
            <div slot="header" class="clearfix"><span>短信充值</span></div>
            <div v-loading="newInitDataOfficialLoading" class="messageBox">
              <div class="messageBox1">
                <div class="messageBoxTxt1">短信剩余数/条</div>
                <div class="messageBoxTxt2">{{ sms_count }}</div>
              </div>
              <div class="messageBox2">
                <span class="messageBoxTxt3" @click="$router.push('/tool/marketing/messageList/index')">群发</span>
                <span class="messageBoxTxt4"><a href="https://www.74cms.com/sms.php" target="_blank">充值</a></span>
              </div>
            </div>
          </el-card>
        </el-row>
        <el-row class="pending_data pending_data_box">
          <el-card>
            <div slot="header" class="clearfix"><span>待办事项</span></div>
            <div v-loading="newInitDataLoading" class="pending_data_lists">
              <div class="leftOrRight">
                <img v-if="arrowVal == 'left'" class="el_lf" src="../../assets/images/dashboard/el_lf.png" alt="">
                <img
                  v-if="arrowVal == 'left'"
                  class="el_rt"
                  src="../../assets/images/dashboard/el_rt_act.png"
                  alt=""
                  @click="arrowClick('right')"
                >
                <img
                  v-if="arrowVal == 'right'"
                  class="el_lf"
                  src="../../assets/images/dashboard/el_lf_act.png"
                  alt=""
                  @click="arrowClick('left')"
                >
                <img v-if="arrowVal == 'right'" class="el_rt" src="../../assets/images/dashboard/el_rt.png" alt="">
              </div>
              <el-carousel
                ref="cardShow"
                class="el_carousel"
                :interval="5000"
                arrow="never"
                :autoplay="false"
                indicator-position="outside"
              >
                <el-carousel-item v-for="(item, index) in baseinfo.pending_data" :key="index" :autoplay="false">
                  <div
                    v-for="(items, index1) in item"
                    :key="index1"
                    class="pending_data_item"
                    @click="handlerClickPending(items)"
                  >
                    <div class="title">
                      {{ items.title }}
                      <img class="rtIcon" src="../../assets/images/dashboard/rt.png" alt="">
                    </div>
                    <div class="num">{{ items.num }}</div>
                  </div>
                </el-carousel-item>
              </el-carousel>
            </div>
          </el-card>
        </el-row>
        <el-row class="pending_data_log" style="margin-top: 20px">
          <el-card>
            <div slot="header" class="clearfix logHeader">
              <span>最近更新</span>
              <span class="moreLog"><a href="https://www.74cms.com/download.html">查看更多</a></span>
            </div>
            <div v-if="upgradeLog.length>0" class="upgradeLogLists">
              <div v-for="(item, index) in upgradeLog" :key="index" class="upgradeLogItem">
                <el-link class="linkUrl" target="_blank" :href="item.url">
                  <span>{{ item.time_cn }}更新日志</span>
                  <span>详情</span>
                </el-link>
              </div>
            </div>
            <div v-if="upgradeLog.length==0" class="upgradeLogLists upgradeLogListsNodata">暂无数据</div>
          </el-card>
        </el-row>
        <el-row class="pending_data pending_data_menu" style="margin-top: 20px;margin-bottom: 20px;">
          <el-card>
            <div slot="header" class="clearfix"><span>售后工单服务</span></div>
            <div class="descBox">
              <img class="icon_p" src="../../assets/images/dashboard/pending_data_bg.png" alt="">
              <div class="desc">快速准确记录、跟踪、处理和反馈问题</div>
              <div class="btns">
                <span class="btns1"><a href="https://www.74cms.com/service.php" target="_blank">提交工单</a></span>
                <span class="btns2"><a href="http://doc.74cms.com/#/" target="_blank">帮助手册</a></span>
              </div>
            </div>
          </el-card>
        </el-row>
        <el-row class="pending_data pending_data_qrcode" style="margin-top: 20px;">
          <el-card>
            <div class="descBox">
              <img class="qrcode" src="../../assets/images/dashboard/Wechat.png">
              <div class="descBoxTxt">
                <div>扫码关注公众号</div>
                <div>获取更多官方动态</div>
              </div>
            </div>
          </el-card>
        </el-row>
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <el-card>
        <div slot="header" class="clearfix"><span>服务器信息</span></div>
        <div v-loading="newInitDataLoading">
          <el-row :gutter="20" style="font-size: 13px">
            <el-col :span="6">操作系统：{{ baseinfo.system_info.os }}</el-col>
            <el-col :span="6">PHP版本：{{ baseinfo.system_info.php_version }}</el-col>
            <el-col :span="6">MySQL版本：{{ baseinfo.system_info.mysql_version }}</el-col>
            <el-col :span="6">web服务器：{{ baseinfo.system_info.web_server }}</el-col>
          </el-row>
          <el-row :gutter="20" style="font-size: 13px; margin-top: 20px">
            <el-col :span="6">允许最大上传文件：{{ baseinfo.system_info.upload_max }}</el-col>
            <el-col :span="6">curl版本：{{ baseinfo.system_info.curl_version }}</el-col>
            <el-col :span="6">ThinkPHP框架版本：{{ baseinfo.system_info.framework_version }}</el-col>
            <el-col :span="6">服务器当前时间：{{ baseinfo.system_info.server_time }}</el-col>
          </el-row>
        </div>
      </el-card>
    </el-row>
    <el-row class="systemInfo" style="margin-top: 20px">
      <el-card>
        <div slot="header" class="clearfix"><span>系统信息</span></div>
        <el-row v-loading="newInitDataOfficialLoading" :gutter="20" style="font-size: 13px;line-height: 34px;">
          <el-col :span="8">
            当前版本：
            <span class="color-link">v{{ baseinfo.version }}</span>
          </el-col>
          <el-col :span="8">
            最新版本：
            <span class="color-link2">v{{ latest_version }}</span>
            <!-- <span style="margin-left:10px;">请您及时更新到最新版本 <span class="color-link3"
                @click="$router.push('/upgrade')">立即升级</span></span> -->
            <span v-if="new_version_notice == 1" style="margin-left:10px;">
              <span class="color-link3" @click="jumpPath('/upgrade', '您的权限不足，请联系超级管理员升级系统。')">立即升级</span>
            </span>
          </el-col>
          <el-col :span="8">
            授权类型：
            <a class="a-link color-link" href="https://www.74cms.com" target="_blank">{{ authorize.authorize }}</a>
          </el-col>
          <el-col :span="8">
            骑士人才系统官网：
            <a class="a-link" href="https://www.74cms.com" target="_blank">www.74cms.com</a>
          </el-col>
          <el-col :span="8">程序开发：74CMS程序开发组</el-col>
        </el-row>
        <el-row :gutter="20" style="font-size: 13px; margin-top: 10px">
          <el-col>
            <div class="htmlInner" v-html="authorize.copyright" />
          </el-col>
        </el-row>
      </el-card>
    </el-row>
    <!-- <el-row style="margin-top: 20px">
      <el-card>
        <div slot="header" class="clearfix">
          <span>官方动态</span>
        </div>
        <el-col :span="14">
          <div class="official-news-list">
            <div v-for="(item, index) in officialNews" :key="index" class="item">
              <a class="a-link" :href="item.link" target="_blank">{{
                item.title
              }}</a><span class="time">{{ item.addtime }}</span>
            </div>
          </div>
          <div class="clearfix" />
        </el-col>
        <el-col :span="10" style="text-align: center">
          <img class="qrcode" src="static/wechat.png">
        </el-col>
      </el-card>
    </el-row> -->
  </div>
</template>

<script>
import {
  dashboardBaseinfo,
  officialData,
  newDataStatisticsIndex,
  newRealTimeData,
  newGeneralstatistics,
  newOfficialData
} from '@/api/dashboard'
import reg_line from './components/reg_line.vue'
import down_resume_line from './components/down_resume_line.vue'
import jobapply_line from './components/jobapply_line.vue'
import income_line from './components/income_line.vue'
import echarts from 'echarts'
import {
  checkRoleAuth
} from '@/utils/role'

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
        general_statistics: {},
        today_data: {},
        pending_data: [],
        system_info: {},
        version: '',
        warning: {
          rewrite: 0,
          install: 0
        }
      },
      upgradeLog: [],
      authorize: {},
      officialNews: [],
      latest_version: '',
      sms_count: '',
      chartTabSelected: '0',
      new_version_notice: 0,
      daterange: [],
      newInitDataLoading: true,
      newInitDataOfficialLoading: true,
      newInitDataGeneralLoading: true,
      newInitDataRealTimeLoading: true,
      nowTime: '',
      date7: '',
      date30: '',
      revenue: '',
      order_num: '',
      company: '',
      personal: '',
      arrowVal: 'left'
    }
  },
  computed: {},
  mounted() {
    this.newInitData()
    this.newInitDataOfficial()
    this.newInitDataGeneral()
    this.newInitDataRealTime()
    this.date7 = this.time7Default()
    this.date30 = this.time30Default()
  },
  methods: {
    arrowClick(val) {
      this.arrowVal = val
      if (val === 'right') {
        this.$refs.cardShow.next()
      } else {
        this.$refs.cardShow.prev()
      }
    },
    getNowTime() {
      var myDate = new Date()
      var year = myDate.getFullYear() // 获取当前年
      var mon = myDate.getMonth() + 1 // 获取当前月
      var date = myDate.getDate() // 获取当前日
      var hours = myDate.getHours() // 获取当前小时
      var minutes = myDate.getMinutes() // 获取当前分钟
      var seconds = myDate.getSeconds() // 获取当前秒
      // 以自己需要的方式拼接
      var now = year + '-' + this.getZero(mon) + '-' + this.getZero(date) + ' ' + this.getZero(hours) + ':' + this.getZero(minutes) + ':' + this.getZero(seconds)
      this.nowTime = now
    },
    // 个位数补0
    getZero(num) {
      // 单数前面加0
      if (parseInt(num) < 10) {
        num = '0' + num
      }
      return num
    },
    handleTabClick(tab, event) {
      this.chartTabSelected = event
    },
    fetchData() {
      dashboardBaseinfo({}).then(response => {
        this.baseinfo = response.data
      })
      officialData({}).then(response => {
        this.upgradeLog = response.data.upgrade_log
        this.authorize = response.data.authorize_info
        this.officialNews = response.data.official_news
        this.new_version_notice = response.data.new_version_notice
        this.latest_version = response.data.latest_version_text
      })
    },
    newInitData() {
      newDataStatisticsIndex().then(res => {
        if (res.code == 200) {
          const {
            pending_data,
            system_info,
            version,
            warning
          } = res.data
          this.newInitDataLoading = false
          // 待办事项数据重组
          const new_pending_data = []
          const new_pending_data1 = []
          const new_pending_data2 = []
          for (let i = 0; i < pending_data.length; i++) {
            if (i <= 5) {
              new_pending_data1.push(pending_data[i])
            } else {
              new_pending_data2.push(pending_data[i])
            }
          }
          new_pending_data.push(new_pending_data1)
          new_pending_data.push(new_pending_data2)
          this.baseinfo.pending_data = new_pending_data
          this.baseinfo.system_info = system_info
          this.baseinfo.version = version
          this.baseinfo.warning = warning
        } else {
          this.$message.error(res.message)
        }
      })
    },
    newInitDataOfficial() {
      newOfficialData().then(res => {
        if (res.code == 200) {
          this.newInitDataOfficialLoading = false
          this.upgradeLog = res.data.upgrade_log
          this.authorize = res.data.authorize_info
          this.officialNews = res.data.official_news
          this.new_version_notice = res.data.new_version_notice
          this.latest_version = res.data.latest_version_text
          this.sms_count = res.data.sms_count
        } else {
          this.$message.error(res.message)
        }
      })
    },
    newInitDataGeneral() {
      newGeneralstatistics().then(res => {
        if (res.code == 200) {
          this.newInitDataGeneralLoading = false
          const {
            revenue,
            order_num,
            company,
            personal
          } = res.data
          this.revenue = revenue
          this.order_num = order_num
          this.company = company
          this.personal = personal
          this.$nextTick(function() {
            this.drawChart('top_line1')
            this.drawChart('top_line2')
            this.drawChart('top_line3')
            this.drawChart('top_line4')
          })
        } else {
          this.$message.error(res.message)
        }
      })
    },
    newInitDataRealTime() {
      this.newInitDataRealTimeLoading = true
      newRealTimeData().then(res => {
        if (res.code == 200) {
          this.getNowTime()
          this.newInitDataRealTimeLoading = false
          this.baseinfo.today_data = res.data
        } else {
          this.$message.error(res.message)
        }
      })
    },
    drawChart(id) {
      this.charts = echarts.init(document.getElementById(id))
      if (id == 'top_line1') {
        // this.charts.showLoading()
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            show: false,
            data: this.revenue.list.xAxis
          },
          yAxis: {
            type: 'value',
            show: false
          },
          tooltip: {
            trigger: 'axis', // 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用
            axisPointer: {
              // 坐标轴指示器，坐标轴触发有效
              type: 'line' // 默认为直线，可选为：'line' | 'shadow'
            }
          },
          series: [{
            name: '',
            type: 'line',
            // smooth: true,
            // symbol: 'circle',
            // symbolSize: 5,
            // sampling: 'average',
            itemStyle: {
              color: '#0770FF'
            },
            stack: 'a',
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                offset: 0,
                color: 'rgba(81,123,255,0.5)'
              },
              {
                offset: 1,
                color: 'rgba(81,123,255,0.1)'
              }
              ])
            },
            data: this.revenue.list.series
          }]
        })
      } else if (id == 'top_line2') {
        // let data = this.data2;
        // this.charts.showLoading()
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            show: false,
            data: this.order_num.list.xAxis
          },
          yAxis: {
            type: 'value',
            show: false
          },
          tooltip: {
            trigger: 'axis', // 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用
            axisPointer: {
              // 坐标轴指示器，坐标轴触发有效
              type: 'line' // 默认为直线，可选为：'line' | 'shadow'
            }
          },
          series: [{
            name: '',
            type: 'line',
            smooth: true,
            symbol: 'circle',
            symbolSize: 5,
            sampling: 'average',
            itemStyle: {
              color: '#FF5773'
            },
            stack: 'a',
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                offset: 0,
                color: 'rgba(255,87,115,0.5)'
              },
              {
                offset: 1,
                color: 'rgba(255,87,115,0.1)'
              }
              ])
            },
            data: this.order_num.list.series
          }]
        })
      } else if (id == 'top_line3') {
        const data = this.data3
        // this.charts.showLoading()
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            show: false,
            data: this.company.list.xAxis
          },
          yAxis: {
            type: 'value',
            show: false
          },
          tooltip: {
            trigger: 'axis', // 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用
            axisPointer: {
              // 坐标轴指示器，坐标轴触发有效
              type: 'line' // 默认为直线，可选为：'line' | 'shadow'
            }
          },
          series: [{
            name: '',
            type: 'line',
            smooth: true,
            symbol: 'circle',
            symbolSize: 5,
            sampling: 'average',
            itemStyle: {
              color: '#FF8247'
            },
            stack: 'a',
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                offset: 0,
                color: 'rgba(255,130,71,0.5)'
              },
              {
                offset: 1,
                color: 'rgba(255,130,71,0.1)'
              }
              ])
            },
            data: this.company.list.series
          }]
        })
      } else if (id == 'top_line4') {
        const data = this.data4
        // this.charts.showLoading()
        this.charts.setOption({
          xAxis: {
            type: 'category',
            boundaryGap: false,
            show: false,
            data: this.personal.list.xAxis
          },
          yAxis: {
            type: 'value',
            show: false
          },
          tooltip: {
            trigger: 'axis', // 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用
            axisPointer: {
              // 坐标轴指示器，坐标轴触发有效
              type: 'line' // 默认为直线，可选为：'line' | 'shadow'
            }
          },
          series: [{
            name: '',
            type: 'line',
            smooth: true,
            symbol: 'circle',
            symbolSize: 5,
            sampling: 'average',
            itemStyle: {
              color: '#68E7E2'
            },
            stack: 'a',
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                offset: 0,
                color: 'rgba(104,231,226,0.5)'
              },
              {
                offset: 1,
                color: 'rgba(104,231,226,0.1)'
              }
              ])
            },
            data: this.personal.list.series
          }]
        })
      }
    },
    handlerClickPending(e) {
      switch (e.alias) {
        case 'all_company_audit':
          checkRoleAuth('/user/company/crm/allClient', '您的权限不足，请联系超级管理员。', { 'clue_audit': '1' })
          return
        case 'my_company_audit':
          checkRoleAuth('/user/company/crm/myClient', '您的权限不足，请联系超级管理员。', { 'clue_audit': '1' })
          return
        case 'job_audit':
          checkRoleAuth('/user/job/list', '您的权限不足，请联系超级管理员。', { 'job_audit': '0' })
          return
        case 'resume_audit':
          checkRoleAuth('/user/urmList', '您的权限不足，请联系超级管理员。', { 'resume_audit': '1' })
          return
        case 'commpany_cancel_apply':
          checkRoleAuth('/user/commpany_cancel_apply', '您的权限不足，请联系超级管理员。', { 'apply_status': '1' })
          return
        case 'person_cancel_apply':
          checkRoleAuth('/user/person_cancel_apply', '您的权限不足，请联系超级管理员。', { 'apply_status': '1' })
          return
        case 'tipoff':
          checkRoleAuth('/content/feedback/tipoff')
          return
        case 'feedback':
          checkRoleAuth('/content/feedback/suggest')
          return
      }
    },
    jumpPath(routePath, message) {
      if (routePath == '/user/urmList') {
        localStorage.setItem('resume_audit', '')
      }
      checkRoleAuth(routePath, message)
    },
    // 近7天
    time7Default() {
      const date = new Date()
      // 通过时间戳计算
      let defalutStartTime = date.getTime() - 7 * 24 * 3600 * 1000 // 转化为时间戳
      let defalutEndTime = date.getTime()
      const startDateNs = new Date(defalutStartTime)
      const endDateNs = new Date(defalutEndTime)
      // 月，日 不够10补0
      defalutStartTime =
          startDateNs.getFullYear() +
          '-' +
          (startDateNs.getMonth() + 1 >= 10 ? startDateNs.getMonth() + 1 : '0' + (startDateNs.getMonth() + 1)) +
          '-' +
          (startDateNs.getDate() >= 10 ? startDateNs.getDate() : '0' + startDateNs.getDate())
      defalutEndTime =
          endDateNs.getFullYear() +
          '-' +
          (endDateNs.getMonth() + 1 >= 10 ? endDateNs.getMonth() + 1 : '0' + (endDateNs.getMonth() + 1)) +
          '-' +
          (endDateNs.getDate() >= 10 ? endDateNs.getDate() : '0' + endDateNs.getDate())
      return [defalutStartTime, defalutEndTime]
    },
    // 近30天
    time30Default() {
      const date = new Date()
      // 通过时间戳计算
      let defalutStartTime = date.getTime() - 30 * 24 * 3600 * 1000 // 转化为时间戳
      let defalutEndTime = date.getTime()
      const startDateNs = new Date(defalutStartTime)
      const endDateNs = new Date(defalutEndTime)
      // 月，日 不够10补0
      defalutStartTime =
          startDateNs.getFullYear() +
          '-' +
          (startDateNs.getMonth() + 1 >= 10 ? startDateNs.getMonth() + 1 : '0' + (startDateNs.getMonth() + 1)) +
          '-' +
          (startDateNs.getDate() >= 10 ? startDateNs.getDate() : '0' + startDateNs.getDate())
      defalutEndTime =
          endDateNs.getFullYear() +
          '-' +
          (endDateNs.getMonth() + 1 >= 10 ? endDateNs.getMonth() + 1 : '0' + (endDateNs.getMonth() + 1)) +
          '-' +
          (endDateNs.getDate() >= 10 ? endDateNs.getDate() : '0' + endDateNs.getDate())
      return [defalutStartTime, defalutEndTime]
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

  .el-col--4 {
    width: 20%;
  }

  .a-link {
    color: #409eff;
  }

  .a-link:hover {
    color: #66b1ff;
    text-decoration: underline;
  }

  .el-timeline {
    padding-top: 10px;
    height: 227px;
    overflow-y: scroll;
    margin-left: -25px;
  }

  .official-news-list {
    font-size: 14px;
    margin-bottom: 20px;

    // width:500px;
    .item {
      height: 30px;
      line-height: 30px;
      border-bottom: 1px dotted #e3e3e3;

      a {
        width: 80%;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }

      .time {
        font-size: 13px;
        color: #999;
        float: right;
      }
    }
  }

  .qrcode {
    // width:600px;
  }

  .today-info {
    cursor: pointer;

    .tit1 {
      font-size: 14px;
      color: #474747;
    }

    .num {
      font-size: 24px;
      color: #232323;
      margin-top: 6px;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .tit2 {
      font-size: 13px;
      color: #474747;
      margin-top: 6px;
    }
  }

  .clearfix {
    clear: both;
  }

  .num-circle {
    color: #fff;
    margin-right: 10px;
    background-color: #ff5722;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 12px;
  }

  .color-link {
    font-size: 13px;
    padding: 3px 6px;
    color: #ff5722;
    border-radius: 4px;
  }

  .color-link:hover {
    color: #ff5722;
    text-decoration: none;
  }

  .color-link2 {
    color: #ff5722;
  }

  .color-link3 {
    font-size: 13px;
    padding: 3px 6px;
    background-color: #3ccc93;
    color: #fff;
    border-radius: 11px;
    cursor: pointer;
  }

  .no-border {
    border: 0;
  }

  // 改版
  .app-container {
    background: #f5f7f9;
  }

  ::v-deep .el-card__header {
    color: #2c2c2c;
    font-weight: bold;
    border-bottom: none;
  }
  ::v-deep .el-card{
    &.is-always-shadow{
      box-shadow: none;
      border: none;
    }
  }

  // -- 快捷菜单
  .refreshBox {
    .refreshBoxData {
      float: left;
    }

    .refresh {
      float: right;
      font-size: 13px;
      color: #a4a4a4;
      font-weight: initial;

      .refreshBtn {
        color: #1d82ff;
        background: url('../../assets/images/dashboard/refresh.png') left center no-repeat;
        background-size: 15px 15px;
        padding-left: 17px;
        margin-left: 10px;
        cursor: pointer;
      }
    }
  }

  .bg_card {
    ::v-deep .el-card__body:hover {
      background: url('../../assets/images/dashboard/selected.png') left center no-repeat;
      background-size: 100% 100%;
    }
  }

  .shortcutMenu {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;

    .shortcutMenuItem {
      width: 14.2%;
      cursor: pointer;

      .icon {
        display: block;
        margin: 0 auto 13px;
        width: 35px;
        height: 35px;
      }

      .text {
        display: block;
        text-align: center;
        font-size: 14px;
        color: #474747;
      }
    }
  }

  .topBox {
    background: #fff;
    padding: 13px 11px;
    border-radius: 3px;
    position: relative;

    .icon {
      display: block;
      position: absolute;
      top: 27px;
      right: 0px;
      width: 110px;
      height: 20px;
    }

    .topBox1 {
      font-size: 14px;
      color: #55585c;
      margin-bottom: 14px;
    }

    .topBox2 {
      font-size: 18px;
      color: #adadad;

      span {
        color: #232323;
        font-weight: bold;
      }
      .label_small{
        color: #55585C;
        font-size: 14px;
        margin-left: 10px;
        font-weight: initial;
      }
    }

    .topBox3 {
      font-size: 14px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid #f5f7f9;
      padding-top: 15px;

      span:nth-child(1) {
        color: #55585c;
      }

      span:nth-child(2) {
        color: #414549;
      }
    }
  }

  .pending_data {
    ::v-deep .el-card__body {
      padding: 0 20px 20px 20px;

      .pending_data_lists {
        // display: flex;
        // justify-content: space-between;
        // align-items: center;
        // flex-wrap: wrap;
        position: relative;

        .title_s {
          position: absolute;
          top: 18px;
          left: 0;
          font-weight: bold;
        }

        .leftOrRight {
          position: absolute;
          top: -55px;
          right: 0px;
          padding-top: 20px;

          .el_lf,
          .el_rt {
            display: inline-block;
            width: 16px;
            right: 16px;
            cursor: pointer;
          }

          .el_lf {
            margin-right: 10px;
          }
        }

        .pending_data_item {
          width: calc((100% - 14px) / 2);
          background: #f9fcff;
          padding: 15px 14px 15px;
          border-radius: 3px;
          box-sizing: border-box;
          margin-bottom: 13px;
          cursor: pointer;
          float: left;

          &:nth-child(odd) {
            margin-right: 14px;
          }

          &:hover {
            background: url('../../assets/images/dashboard/selected.png') left center no-repeat;
            background-size: 100% 100%;
          }

          .title {
            display: flex;
            align-items: center;
            justify-content: start;
            font-size: 12px;
            color: #55585c;
            margin-bottom: 15px;

            .rtIcon {
              display: inline-block;
              width: 5px;
              height: 10px;
              margin-left: 5px;
            }
          }

          .num {
            color: #ff8247;
            font-size: 18px;
          }
        }
      }

      .messageBox {
        background: #f9fcff;
        padding: 20px 15px 20px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;

        .messageBox1 {
          .messageBoxTxt1 {
            color: #55585c;
            font-size: 14px;
            margin-bottom: 14px;
          }

          .messageBoxTxt2 {
            color: #232323;
            font-size: 20px;
            font-weight: bold;
          }
        }

        .messageBox2 {
          .messageBoxTxt3 {
            display: inline-block;
            background: #e3f0ff;
            border: 1px solid #409eff;
            border-radius: 3px;
            width: 54px;
            height: 28px;
            line-height: 28px;
            text-align: center;
            color: #2b8aff;
            font-size: 13px;
            cursor: pointer;

            &:hover {
              background: #2b8aff;
              color: #fff;
            }
          }

          .messageBoxTxt4 {
            display: inline-block;
            background: #ffede5;
            border: 1px solid #ff8247;
            border-radius: 3px;
            width: 54px;
            height: 28px;
            line-height: 28px;
            text-align: center;
            color: #ff8247;
            font-size: 13px;
            margin-left: 8px;
            cursor: pointer;
            a{
              display: block;
              width: 100%;
              height: 100%;
            }

            &:hover {
              background: #ff8247;
              color: #fff;
            }
          }
        }
      }

      .descBox {
        padding: 0;
        position: relative;

        .icon_p {
          display: block;
          width: 66px;
          height: 76px;
          position: absolute;
          top: -24px;
          right: 5px;
        }

        .desc {
          color: #55585c;
          font-size: 12px;
          margin: 11px 0 15px;
        }

        .btns {
          display: flex;
          justify-content: start;
          align-items: center;

          .btns1 {
            background: #fff8f3;
            border: 1px solid #ff9640;
            border-radius: 3px;
            font-size: 13px;
            width: 78px;
            text-align: center;
            height: 28px;
            line-height: 28px;
            color: #ff8247;
            margin-right: 8px;
            cursor: pointer;
            a{
              display: block;
              width: 100%;
              height: 100%;
            }

            &:hover {
              background: #ff9640;
              color: #fff;
            }
          }

          .btns2 {
            background: #f1fff9;
            border: 1px solid #3ccc93;
            border-radius: 3px;
            font-size: 13px;
            width: 78px;
            text-align: center;
            height: 28px;
            line-height: 28px;
            color: #3ccc93;
            cursor: pointer;
            a{
              display: block;
              width: 100%;
              height: 100%;
            }

            &:hover {
              background: #3ccc93;
              color: #fff;
            }
          }
        }
      }
    }
  }

  .pending_data_log {
    .logHeader {
      overflow: hidden;

      .moreLog {
        float: right;
        color: #a0a0a0;
        font-size: 12px;
        font-weight: initial;
        margin-top: 3px;
        cursor: pointer;
      }
    }

    ::v-deep .el-card__body {
      padding: 0;

      .upgradeLogLists {
        height: 198px;
        overflow: hidden;
        &.upgradeLogListsNodata{
          width: 100%;
          height: 190px;
          line-height: 154px;
          font-size: 14px;
          text-align: center;
          color: #999;
        }

        .upgradeLogItem {
          background: url('../../assets/images/dashboard/linkIcon.png') 20px center no-repeat;
          background-size: 6px 4px;
          box-sizing: border-box;
          padding: 15px 20px 15px 32px;
          border-bottom: 1px solid #f5f7f9;

          .el-link.el-link--default:after,
          .el-link.el-link--primary.is-underline:hover:after,
          .el-link.el-link--primary:after {
            border: none;
          }

          .linkUrl {
            display: block;
            width: 100%;

            .el-link--inner {
              display: flex;
              align-items: center;
              justify-content: space-between;
              color: #55585c;

              &:hover {
                color: #2b8aff;
                border: none;
                text-decoration: none;
              }

              span:nth-child(1) {
                max-width: 60%;
                height: 17px;
                overflow: hidden;
              }

              span:nth-child(2) {
                background: url('../../assets/images/dashboard/rt.png') right center no-repeat;
                background-size: 6px 8px;
                box-sizing: border-box;
                padding-right: 10px;
              }
            }
          }
        }
      }
    }
  }

  .pending_data_menu {
    ::v-deep .el-card__header {
      padding-bottom: 0;
      padding-top: 25px;
    }
    ::v-deep .el-card__body{
      padding-bottom: 25px;
    }
  }

  .pending_data_qrcode {
    ::v-deep .el-card__body {
      padding-top: 26px;
      padding-bottom: 26px;
    }

    .descBox {
      display: flex;
      align-items: center;
      justify-content: start;

      .qrcode {
        display: block;
        width: 100px;
        height: 100px;
        margin-right: 18px;
      }

      .descBoxTxt {
        div {
          text-align: left;
          font-size: 14px;
          color: #55585c;

          &:first-child {
            margin-bottom: 6px;
          }
        }
      }
    }
  }

  .pending_data_box {
    ::v-deep .el-card__body {
      padding-bottom: 7px;
    }
  }

  .systemInfo {
    ::v-deep .el-card__body {
      padding-top: 0;
    }
  }

  .htmlInner {
    background: rgb(248, 252, 255) url('../../assets/images/dashboard/selected.png') left center no-repeat;
    background-size: 10% 100%;
    padding: 8px 20px;
    box-sizing: border-box;
  }

  .chartTableBox {
    ::v-deep .el-tabs__nav-wrap::after {
      background-color: initial;
    }
  }

  .pending_data_lists {
    position: relative;

    .title_s {
      position: absolute;
      top: 18px;
      left: 0;
      font-weight: bold;
    }

    ::v-deep .el_carousel {
      // padding-top: 52px;
    }

    ::v-deep .el-carousel__container {
      height: 273px;
    }

    ::v-deep .el-carousel__button {
      display: block;
      opacity: 1;
      width: 4px;
      height: 4px;
      background-color: #a4cdff;
      border: none;
      outline: 0;
      padding: 0;
      margin: 0;
      cursor: pointer;
      border-radius: 50%;
      -webkit-transition: 0.3s;
      transition: 0.3s;
    }

    ::v-deep .is-active .el-carousel__button {
      background: #2b8aff;
    }

    ::v-deep .el-carousel__arrow {
      border: none;
      outline: 0;
      padding: 0;
      margin: 0;
      height: 16px;
      width: 16px;
      cursor: pointer;
      -webkit-transition: 0.3s;
      transition: 0.3s;
      border-radius: 50%;
      background-color: transparent;
      color: #2b8aff;
      position: absolute;
      top: -22px;
      z-index: 10;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%);
      text-align: center;
      font-size: 12px;
      border: 1px solid #2b8aff;
    }

    ::v-deep .el-carousel__arrow--left {
      right: 26px;
      left: initial;
    }

    ::v-deep .el-carousel__arrow--right {
      right: 0px;
    }
  }
  ::v-deep .el-loading-mask {
    z-index: 1 !important;
  }
</style>
