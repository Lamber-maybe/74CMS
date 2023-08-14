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
      </div>
      <app-main />
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { Navbar, Sidebar, AppMain } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import Logo from './components/Logo'
import { clearCache } from '@/api/configuration'

export default {
  name: 'Layout',
  components: {
    Navbar,
    Sidebar,
    AppMain,
    Logo
  },
  mixins: [ResizeMixin],
  data() {
    return {
      selectedModulePath: '/'
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
  },
  methods: {
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
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/mixin.scss';
@import '~@/styles/variables.scss';

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
</style>
