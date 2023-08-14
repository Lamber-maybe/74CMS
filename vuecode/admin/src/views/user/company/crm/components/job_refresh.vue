<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">
        <span class="line"></span>
        我的客户
      </div>
      <div class="tableList" v-loading="listLoading">
        <div class="tableHeader">
          <span class="content1">客户</span>
          <span class="content2">数量</span>
          <span class="content3">操作</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">我的总客户</span>
          <span class="content2">{{ dataArr.total_company }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', '')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">今日新增客户</span>
          <span class="content2">{{ dataArr.today_company }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'today_company')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">认证资料待审核的客户</span>
          <span class="content2">{{ dataArr.auth_company }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'auth_company')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">到期未续费的客户</span>
          <span class="content2">{{ dataArr.expiring_soon }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'expiring_soon')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">即将到期的客户</span>
          <span class="content2">{{ dataArr.expire_company }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'expire_company')">查看</span>
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
import { checkRoleAuth } from '@/utils/role';
export default {
  props: ['height', 'dataArr', 'gradeScreenData'],
  data() {
    return {
      daterange: '',
      listLoading: true
    };
  },
  created() {},
  watch: {
    dataArr(val) {
      this.listLoading = false;
      this.dataArr = val;
    },
    gradeScreenData(val) {
      // console.log('客户等级：', val);
    }
  },
  methods: {
    jumpPath(routePath, message, type) {
      if (type == 'today_company') {
        //新增客户
        localStorage.setItem('gradeScreen', '1');
        checkRoleAuth(routePath, message);
      } else if (type == 'auth_company') {
        //认证资料待审核
        localStorage.setItem('clue_audit', '1');
        checkRoleAuth(routePath, message);
      } else if (type == 'expiring_soon') {
        //到期未续费客户
        localStorage.setItem('setmealDeadline', '2');
        checkRoleAuth(routePath, message);
      } else if (type == 'expire_company') {
        //即将到期客户
        localStorage.setItem('setmealDeadline', '1');
        checkRoleAuth(routePath, message);
      } else {
        if (routePath == '/user/urmList') {
          localStorage.setItem('resume_audit', '');
        }
        checkRoleAuth(routePath, message);
      }
    }
  }
};
</script>
<style scoped>
.tit {
  font-size: 18px;
  color: #333;
  margin-bottom: 20px;
  position: relative;
}

.tit .line {
  display: inline-block;
  width: 3px;
  height: 20px;
  background: #417fff;
  position: absolute;
  top: -0.5px;
  left: -20px;
}

.tableList {
  width: 100%;
}

.tableHeader {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: #666;
  font-size: 14px;
  background: #f9fafc;
}

.content1 {
  display: inline-block;
  width: 45%;
  height: 38px;
  line-height: 38px;
  padding-left: 16px;
  box-sizing: border-box;
}

.content2 {
  display: inline-block;
  width: 45%;
  text-align: center;
  height: 38px;
  line-height: 38px;
}

.content3 {
  display: inline-block;
  width: 10%;
  text-align: center;
  height: 38px;
  line-height: 38px;
}

.tableContent {
  background: transparent;
  border-bottom: 1px dashed #d0d0d0;
}

.tableContent .content1,
.tableContent .content2,
.tableContent .content3 {
  height: 48px;
  line-height: 48px;
}

.tableContent .content3 {
  color: #409eff;
  font-size: 13px;
  cursor: pointer;
}
</style>
