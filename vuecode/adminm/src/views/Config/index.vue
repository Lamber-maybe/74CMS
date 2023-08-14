<template>
  <div>
    <Head :show-back="false" :show-admin="true" @accessMobile="accessMobile">系统工具</Head>
    <div v-if="access_mobile=='all' || access_mobile.clearcache == 1 || access_mobile.site_status == 1 || access_mobile.reg_status == 1" class="public_item_title">系统项目</div>
    <div class="b1">
      <div class="bc_line">
        <div v-if="access_mobile=='all' || access_mobile.clearcache == 1" class="bc_cell c1" @click="clearcache"><p class="t1">更新缓存</p></div>
        <div v-if="access_mobile=='all' || access_mobile.site_status == 1" class="bc_cell c2" @click="closeOpenSite"><p class="t1">网站启停</p><p class="t2">{{globalConfig.isclose==1?'关闭中':'已开启'}}</p></div>
        <div v-if="access_mobile=='all' || access_mobile.reg_status == 1" class="bc_cell c3" @click="closeOpenReg"><p class="t1">暂停注册</p><p class="t2">{{globalConfig.closereg==1?'关闭注册':'允许注册'}}</p></div>
      </div>
    </div>
    <div class="public_item_title">个人业务管理</div>
    <div class="b1">
      <div class="bc_line">
        <div class="bc_cell c4" @click="$router.push('/config/admin')"><p class="t1">账号信息</p></div>
        <div class="bc_cell c5" @click="logout"><p class="t1">退出登录</p></div>
        <div class="bc_cell c6"></div>
      </div>
    </div>
    <BottomNav></BottomNav>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api' 
  export default {
    name: "configIndex",
    data(){
      return {
        globalConfig:{},
        access_mobile: {
          'clearcache' : 0,
          'site_status' : 0,
          'reg_status' : 0
        }
      }
    },
    created(){
      this.config()
    },
    methods:{
      config () {
        let that = this
        http
          .get(api.config, {})
          .then(res => {
            that.globalConfig = res.data
          })
          .catch(() => {})
      },
      clearcache () {
        let that = this
        that.$dialog.confirm({
          title: '',
          message: '确定清除缓存吗',
        })
          .then(() => {
            http
              .get(api.clearcache, {})
              .then(res => {
                that.$toast.success(res.message);
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          });
      },
      closeOpenSite () {
        let that = this
        let title = that.globalConfig.isclose==0?'关闭':'开启'
        that.$dialog.confirm({
          title: '',
          message: '确定'+title+'网站吗',
        })
          .then(() => {
            http
              .post(api.closeOpenSite, {is_close:that.globalConfig.isclose==1?0:1})
              .then(() => {
                that.$toast.success(title+'网站成功');
                that.config()
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          });
      },
      closeOpenReg () {
        let that = this
        let title = that.globalConfig.closereg==0?'关闭':'开启'
        that.$dialog.confirm({
          title: '',
          message: '确定'+title+'会员注册吗',
        })
          .then(() => {
            http
              .post(api.closeOpenReg, {is_close:that.globalConfig.closereg==1?0:1})
              .then(() => {
                that.$toast.success(title+'会员注册成功');
                that.config()
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          });
      },
      logout () {
        let that = this
        that.$dialog.confirm({
          title: '',
          message: '确定退出吗',
        })
          .then(() => {
            localStorage.removeItem('admintoken')
            that.$toast.success('退出成功');
            location.reload()
          })
          .catch(() => {
            // on cancel
          });
      },
      accessMobile(val){
        if (val == 'all'){
          this.access_mobile = val
        } else {
          let checkedKeys = val.checkedKeys
          for (const element of checkedKeys) {
            this.access_mobile[element] = 1
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .b1 {
    width: 100%;background-color: #ffffff;
    .bc_line {
      display: flex;border-top: 1PX solid #f2f2f2;
      &:nth-of-type(1) { border-top: 0; }
      .bc_cell {
        flex: 1;border-right: 1PX solid #f2f2f2;text-align: center;height: 210px;padding-top: 128px;
        .t1 { font-size: 26px;color: #333333; }
        .t2 { font-size: 18px;color: #999999; }
        &:nth-of-type(3n) { border-right:0; }
        &.c1 { background: url("../../assets/images/config/1.png") center 43px no-repeat; background-size: 60px 71px; }
        &.c2 { background: url("../../assets/images/config/2.png") center 51px no-repeat; background-size: 60px 59px; }
        &.c3 { background: url("../../assets/images/config/3.png") center 49px no-repeat; background-size: 57px 60px; }
        &.c4 { background: url("../../assets/images/config/4.png") center 54px no-repeat; background-size: 65px 60px; }
        &.c5 { background: url("../../assets/images/config/5.png") center 47px no-repeat; background-size: 63px 60px; }
      }
    }
  }
  .public_item_title {
    width: 100%;height: 70px;line-height: 70px;padding-left: 28px;background-color: #f3f3f3;font-size: 26px;
    color: #999999;
  }
</style>
