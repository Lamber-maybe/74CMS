<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">
        <span class="line"></span>
        我的客户跟进
      </div>
      <div class="tableList" v-loading="listLoading">
        <div class="tableHeader">
          <span class="content1">客户</span>
          <span class="content2">数量</span>
          <span class="content3">操作</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">今日跟进的客户</span>
          <span class="content2">{{ dataArr.today_follow }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'today_follow')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">30天内跟进的客户</span>
          <span class="content2">{{ dataArr.thirty_follow }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'thirty_follow')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">15天内未跟进的客户</span>
          <span class="content2">{{ dataArr.fifteen_not_follow }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'fifteen_not_follow')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">7天内未跟进的客户</span>
          <span class="content2">{{ dataArr.seven_not_follow }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'seven_not_follow')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">即将转入公海的客户</span>
          <span class="content2">{{ dataArr.international_waters }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/myClient', '', 'international_waters')">查看</span>
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
import { checkRoleAuth } from '@/utils/role';
export default {
  props: ['height', 'dataArr'],
  data() {
    return {
      daterange: '',
      listLoading: true
    };
  },
  watch: {
    dataArr(val) {
      this.listLoading = false;
      this.dataArr = val;
    }
  },
  created() {},
  methods: {
    jumpPath(routePath, message, type) {
      if (type == 'today_follow') {
        //今日跟进客户
        localStorage.setItem('followupScreen', '1');
        checkRoleAuth(routePath, message);
      } else if (type == 'thirty_follow') {
        //30天未跟进客户
        localStorage.setItem('followupScreen', '2');
        checkRoleAuth(routePath, message);
      } else if (type == 'fifteen_not_follow') {
        //15天未跟进客户
        localStorage.setItem('followupScreen', '4');
        checkRoleAuth(routePath, message);
      } else if (type == 'seven_not_follow') {
        //7天未跟进客户
        localStorage.setItem('followupScreen', '3');
        checkRoleAuth(routePath, message);
      } else if (type == 'international_waters') {
        //即将转入公海的客户
        localStorage.setItem('followupScreen', '7');
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
