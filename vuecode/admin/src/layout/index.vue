<template>
  <div :class="classObj" class="app-wrapper">
    <div
      class="topnav"
      style="width:100%;height:60px;position:fixed;z-index:1001"
    >
      <logo :collapse="false" />

      <el-menu
        :default-active="modulePath"
        class="el-menu-demo"
        mode="horizontal"
        background-color="#2B2F3A"
        text-color="#fff"
        active-text-color="#409EFF"
        style="margin-left:210px"
        @select="select"
      >
        <el-menu-item
          v-for="(m, index) in $store.state.permission.routers"
          v-show="m.hidden !== true"
          :key="index"
          :index="m.path"
        >
          {{ m.meta.title }}
        </el-menu-item>
        <el-submenu index="2" style="float:right;">
          <template slot="title">
            <i class="el-icon-s-custom" />
            {{ name }}
            <span style="color:#4FC08D">（{{ rolename }}）</span>
          </template>
          <el-menu-item @click.native="personal">
            <i class="el-icon-s-tools" />
            个人中心
          </el-menu-item>
          <el-menu-item @click.native="logout">
            <i class="el-icon-switch-button" />
            退出
          </el-menu-item>
        </el-submenu>
        <el-menu-item style="float:right;" @click="clearCache">
          <i class="el-icon-refresh" />
          更新缓存
        </el-menu-item>
        <el-menu-item style="float:right;" @click="visiteWebsite">
          <i class="el-icon-s-home" />
          网站首页
        </el-menu-item>
      </el-menu>
      <div class="clearfix" />
    </div>
    <sidebar class="sidebar-container" :parent-menu="selectedModulePath" />
    <div class="main-container" style="padding-top:60px">
      <div :class="{ 'fixed-header': fixedHeader }">
        <navbar />
        <div v-if="new_version_notice==1 && $route.path != '/upgrade'" class="newversion">
          <span class="text">发现新版本</span>
          <span class="btn" @click="$router.push('/upgrade')">立即更新</span>
        </div>
      </div>
      <app-main />
    </div>
    <!--今日提醒跟进-->
    <div class="remind" @click="funFollow">
      <div class="remind_title">
        <div>跟进提醒<div class="remind_num">{{ record_num }}</div></div>
      </div>
    </div>
    <!--今日提醒跟进弹窗-->
    <el-drawer
      v-if="FollowDrawer"
      size="80%"
      :with-header="false"
      :visible.sync="FollowDrawer"
    >
      <div class="follow_header">
        <div class="follow_title">今日跟进提醒(<span style="color:red">{{ record_num }}</span>)</div>
        <div class="follow_select">
          <el-select v-model="followScreen" placeholder="不限类别" @change="funFollowScreen">
            <el-option
              v-for="item in followData"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </div>
      </div>
      <div class="follow_table">
        <el-table
          v-loading="loading"
          :data="tableData"
          style="width: 100%"
          :header-cell-style="{padding:'20px 0px'}"
          :cell-style="{padding:'20px 0px'}"
        >
          <el-table-column
            prop="type_name"
            label="类别"
            width="180"
          >
            <template slot="header" scope="scope">
              <div style="text-align: center">类别</div>
            </template>
            <template scope="scope">
              <div v-if="scope.row.type == 1 && scope.row.next_time > time" class="clue">线索</div>
              <div v-if="scope.row.type == 2 && scope.row.next_time > time" class="customer">客户</div>
              <div v-if="scope.row.type == 1 && scope.row.next_time <= time" class="overdue">线索</div>
              <div v-if="scope.row.type == 2 && scope.row.next_time <= time" class="overdue">客户</div>
            </template>
          </el-table-column>
          <el-table-column
            label="名称"
          >
            <template scope="scope">
              <div v-if="scope.row.name != ''">
                <span v-if="scope.row.next_time <= time" style="color:#929191">
                  {{ scope.row.name }}
                </span>
                <span v-else>{{ scope.row.name }}</span>
              </div>
              <div v-else>
                未完善企业资料
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="address"
            label="联系电话"
          >
            <template scope="scope">
              <span v-if="scope.row.next_time <= time" style="color:#929191">
                {{ scope.row.link_mobile }}({{ scope.row.link_man }})
              </span>
              <span v-else>{{ scope.row.link_mobile }}({{ scope.row.link_man }})</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="date"
            label="预约时间"
          >
            <template scope="scope">
              <span v-if="scope.row.next_time <= time" style="color:#929191">
                {{ scope.row.next_time | timeFilter }}
                <div class="overdue_s">过期</div>
              </span>
              <span v-else>{{ scope.row.next_time | timeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="date"
            label="操作"
          >
            <template scope="scope">
              <el-button v-if="scope.row.next_time <= time" size="mini" type="info" plain disabled>立即跟进</el-button>
              <el-button v-else size="mini" type="primary" @click="followUpImmediately(scope.row)">立即跟进</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="bortton-page">
          <el-col :span="24" style="text-align: right">
            <el-pagination
              background
              :current-page="currentPage"
              :page-sizes="[10, 15, 20, 30, 40]"
              :page-size="pagesize"
              layout="total, sizes, prev, pager, next, jumper"
              :total="total"
              @size-change="handleSizeChange"
              @current-change="handleCurrentChange"
            />
          </el-col>
        </div>
      </div>
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { Navbar, Sidebar, AppMain } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import Logo from './components/Logo'
import { clearCache } from '@/api/configuration'
import { officialData } from '@/api/dashboard'
import { toBeFollowedup } from '@/api/company_crm'
import { parseTime } from '@/utils'
export default {
  name: 'Layout',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    Navbar,
    Sidebar,
    AppMain,
    Logo
  },
  mixins: [ResizeMixin],
  data() {
    return {
      loading: false,
      followData: [{
        value: '0',
        label: '不限类别'
      }, {
        value: '1',
        label: '线索跟进'
      }, {
        value: '2',
        label: '客户跟进'
      }],
      followScreen: '0',
      time: Date.parse(new Date()) / 1000,
      currentPage: 1,
      pagesize: 10,
      tableData: [],
      record_num: 0,
      total: 0,
      selectedModulePath: '/',
      new_version_notice: 0,
      FollowDrawer: false
    }
  },
  computed: {
    ...mapGetters(['name', 'rolename']),
    modulePath() {
      const matched = this.$route.matched
      const matchedModulePath = matched[0].path == '' ? '/' : matched[0].path
      // this.selectedModulePath = matchedModulePath
      return matchedModulePath
    },
    sidebar() {
      return this.$store.state.app.sidebar
    },
    device() {
      return this.$store.state.app.device
    },
    fixedHeader() {
      return this.$store.state.settings.fixedHeader
    },
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile'
      }
    }
  },
  watch: {
    $route: {
      handler: function(val, oldVal){
        const matched = this.$route.matched
        const matchedModulePath = matched[0].path == '' ? '/' : matched[0].path
        this.selectedModulePath = matchedModulePath
      },
      // 深度观察监听
      deep: true
    }
  },
  mounted(){
    const matched = this.$route.matched
    const matchedModulePath = matched[0].path == '' ? '/' : matched[0].path
    this.selectedModulePath = matchedModulePath
    this.fetchData()
    this.toBeFollowedup()
  },
  methods: {
    followUpImmediately(row){
      if (row.type == 1){
        this.FollowDrawer = false
        this.$router.push({
          path: '/user/company/crm/my',
          query: {
            'time': Date.parse(new Date()) / 1000
          }
        })
        localStorage.setItem('clue_type', 'follow')
        localStorage.setItem('clue_id', row.clue_id)
      } else {
        this.$router.push({
          path: '/user/company/crm/myClient',
          query: {
            'time': Date.parse(new Date()) / 1000
          }
        })
        localStorage.setItem('clue_company_id', row.company_id)
        localStorage.setItem('clue_type', 'follow')
        localStorage.setItem('clue_id', row.clue_id)
        this.FollowDrawer = false
      }
    },
    handleClose(){
      this.FollowDrawer = false
    },
    funFollowScreen(){
      this.toBeFollowedup()
    },
    toBeFollowedup() {
      this.loading = true
      toBeFollowedup({ 'page': this.currentPage, 'pagesize': this.pagesize, 'type': this.followScreen }).then(response => {
        if (response.data){
          this.tableData = response.data.rows
          this.record_num = response.data.pages.count
          this.total = response.data.pages.record_num
          this.currentPage = response.data.pages.now_page
        } else {
          this.tableData = []
          this.record_num = 0
          this.total = 0
          this.currentPage = 1
        }
        this.loading = false
      })
    },
    handleSizeChange(val){
      this.pagesize = val
      this.toBeFollowedup()
    },
    handleCurrentChange(val){
      this.currentPage = val
      this.toBeFollowedup()
    },
    funFollow() {
      this.FollowDrawer = true
    },
    fetchData() {
      officialData({}).then(response => {
        this.new_version_notice = response.data.new_version_notice
      })
    },
    select(e) {
      if (e !== null) {
        this.selectedModulePath = e
        this.$store.state.permission.routers.forEach(element => {
          if (element.path === e){
            if (element.children !== undefined){
              if (element.children[0].children !== undefined){
                this.$router.push(element.children[0].children[0].path)
              } else {
                this.$router.push(element.children[0].path)
              }
            } else {
              this.$router.push(element.path)
            }
          }
        })
      }
    },
    clearCache() {
      clearCache({}).then(response => {
        if (response.code == 200) {
          this.$store.dispatch('config/getConfigInfo')
          this.$message.success(response.message)
        }
      })
    },
    visiteWebsite(){
      window.open(this.$store.state.config.sitedomain + this.$store.state.config.sitedir)
    },
    logout() {
      this.$confirm('确定退出吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          this.$store.dispatch('user/logout').then(() => {
            // this.$router.push(`/login?redirect=${this.$route.fullPath}`)
            this.$router.push(`/login`)
          })
        })
        .catch(() => {})
    },
    personal(){
      this.$router.push('/corpwechat/personal')
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/mixin.scss';
@import '~@/styles/variables.scss';
::v-deep .el-drawer__wrapper{
  overflow:visible
}
::v-deep  .el-drawer{
  overflow:visible
}
.close {
  width: 40px;
  height: 60px;
  position: absolute;
  left: -40px;
  top: 50%;
  transform: translateY(-50%);
  background: #f5f5f5;
  border-radius: 8px 0 0 8px;
  cursor: pointer;
  .el-icon-close {
    font-size: 30px;
    color: #777;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }
}
.follow_title{
  display: inline-block;
  line-height: 78px;
  margin-left: 55px;
}
.follow_select{
  float:right;
  margin-top: 20px;
  margin-right: 55px;
  display: inline-block;
}
.overdue_s{
  width: 44px;
  height: 22px;
  background: #F4F4F4;
  border-radius: 2px;
  font-size: 12px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  color: #929191;
  text-align: center;
  line-height: 22px;
  margin:0 auto;
  display: inline-block;
}
.overdue{
  width: 44px;
  height: 22px;
  background: #F4F4F4;
  border-radius: 2px;
  font-size: 12px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  color: #929191;
  text-align: center;
  line-height: 22px;
  margin:0 auto;
}
.bortton-page{
  margin-top: 20px;
  padding-bottom: 40px;
}
.customer{
  width: 44px;
  height: 22px;
  background: #EFFFF0;
  border-radius: 2px;
  font-size: 12px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  color: #6BCB71;
  text-align: center;
  line-height: 22px;
  margin:0 auto;
}
.clue{
  width: 44px;
  height: 22px;
  background: #F1F9FF;
  border-radius: 2px;
  font-size: 12px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  color: #409EFF;
  text-align: center;
  line-height: 22px;
  margin:0 auto;
}
.tableRowClass{
  padding: 20px 0px;
}
.follow_header{
  height: 78px;
  border-bottom: 1px solid #F5F5F5;
}
.remind{
  cursor:pointer;
  z-index: 15;
  position: fixed;
  bottom: 150px;
  right: 0px;
  width: 30px;
  height: 132px;
  color: #FFFFFF;
  font-size: 14px;
  padding: 22px 2px;
  font-family: Microsoft YaHei;
  font-weight: 400;
  text-align: center;
  line-height: 16px;
  background:url('../assets/images/company/crm/remind.png') no-repeat;
  .remind_title{
    display: inline-block;
    margin:0 auto;
  }
  .remind_num{
    width: 21px;
    height: 21px;
    margin-left: 3px;
    margin-top: 2px;
    font-size: 10px;
    color:#FF0000;
    text-align: center;
    line-height: 21px;
    background: #FFFFFF;
    opacity: 0.9;
    border-radius: 50%;
  }
}
.app-wrapper {
  @include clearfix;
  position: relative;
  height: 100%;
  width: 100%;
  &.mobile.openSidebar {
    position: fixed;
    top: 0;
  }
}
.drawer-bg {
  background: #000;
  opacity: 0.3;
  width: 100%;
  top: 0;
  height: 100%;
  position: absolute;
  z-index: 999;
}

.fixed-header {
  position: fixed;
  top: 60px;
  right: 0;
  z-index: 9;
  width: calc(100% - #{$sideBarWidth});
  transition: width 0.28s;
}

.hideSidebar .fixed-header {
  width: calc(100% - 54px);
}

.mobile .fixed-header {
  width: 100%;
}

.newversion{
  position:absolute;
  top:0;
  right:20px;
  height: 50px;
  line-height: 50px;
}
.newversion .text{
  font-size:15px;
  background: url("../assets/images/01.png");
  background-repeat: no-repeat;
  padding-left:24px;

}
.newversion .btn{
  font-size:14px;
  margin-left:30px;
  padding:6px 14px;
  color:#fff;
  background-color: #ff5722;
  border-radius:14px;
  cursor:pointer;
}
.newversion .btn:hover{
  background-color: #ff5722db;
}
</style>
