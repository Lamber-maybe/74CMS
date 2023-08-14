<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">
        <span class="line"></span>
        我的线索
      </div>
      <div class="tableList" v-loading="listLoading">
        <div class="tableHeader">
          <span class="content1">线索</span>
          <span class="content2">数量</span>
          <span class="content3">操作</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">我的总线索</span>
          <span class="content2">{{ dataArr.total_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','total_clue')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">今日新增的线索</span>
          <span class="content2">{{ dataArr.today_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','today_clue')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">本周新增的线索</span>
          <span class="content2">{{ dataArr.week_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','week_clue')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">本月新增的线索</span>
          <span class="content2">{{ dataArr.month_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','month_clue')">查看</span>
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
  import {
    checkRoleAuth
  } from '@/utils/role';
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
        if (type == 'total_clue') {
          //总线索
          localStorage.setItem('collectionScreen', '');
          checkRoleAuth(routePath, message);
        } else if (type == 'today_clue') {
          //今日新增线索
          localStorage.setItem('collectionScreen', '1');
          checkRoleAuth(routePath, message);
        } else if (type == 'week_clue') {
          //本周新增的线索
          localStorage.setItem('collectionScreen', '2');
          checkRoleAuth(routePath, message);
        } else if (type == 'month_clue') {
          //本月新增的线索
          localStorage.setItem('collectionScreen', '3');
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
