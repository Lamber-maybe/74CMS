<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">
        <span class="line"></span>
        我的线索跟进
      </div>
      <div class="tableList" v-loading="listLoading">
        <div class="tableHeader">
          <span class="content1">线索</span>
          <span class="content2">数量</span>
          <span class="content3">操作</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">跟进0次的线索</span>
          <span class="content2">{{ dataArr.not_following_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','not_following_clue')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">跟进1次的线索</span>
          <span class="content2">{{ dataArr.following_one_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','following_one_clue')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">跟进2次的线索</span>
          <span class="content2">{{ dataArr.following_two_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','following_two_clue')">查看</span>
        </div>
        <div class="tableHeader tableContent">
          <span class="content1">跟进3次及以上的线索</span>
          <span class="content2">{{ dataArr.following_three_clue }}</span>
          <span class="content3" @click="jumpPath('/user/company/crm/my','','following_three_clue')">查看</span>
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
        if (type == 'not_following_clue') {
          //跟进0次线索
          localStorage.setItem('followCountScreen', '1');
          checkRoleAuth(routePath, message);
        } else if (type == 'following_one_clue') {
          //跟进1次线索
          localStorage.setItem('followCountScreen', '2');
          checkRoleAuth(routePath, message);
        } else if (type == 'following_two_clue') {
          //跟进2次线索
          localStorage.setItem('followCountScreen', '3');
          checkRoleAuth(routePath, message);
        } else if (type == 'following_three_clue') {
          //跟进4次线索
          localStorage.setItem('followCountScreen', '4');
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
