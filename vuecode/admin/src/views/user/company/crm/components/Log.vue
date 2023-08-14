<template>
  <div class="popup_contacts">
    <div class="box">
      <div class="button">
        <div :class="type=='operation' ? 'selectButton' : ''" @click="switchTemplate('operation')">操作日志</div>
        <div :class="type=='sign' ? 'selectButton' : ''" @click="switchTemplate('sign')">登录日志</div>
      </div>
      <div v-if="type=='operation'" class="list-search">
        <el-input
          v-model="keyword"
          placeholder="请输入关键词"
          class="input-with-select"
          size="small"
          @keyup.enter.native="funSearchKeyword"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="funSearchKeyword"
          />
        </el-input>
      </div>
    </div>
    <div v-if="type== 'operation'">
      <el-table
        v-loading="loading"
        :header-cell-style="{background:'#f9f9f9'}"
        :data="tableData"
        style="width: 100%"
      >
        <el-table-column
          prop="date"
          label="时间"
        >
          <template slot-scope="scope">
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="content"
          label="操作"
        />
        <el-table-column
          prop="ip"
          label="IP"
        />
        <el-table-column
          prop="ip_addr"
          label="IP归属地"
        />
        <el-table-column
          prop="platform_cn"
          label="来源"
        />
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
    <div v-if="type== 'sign'">
      <el-table
        v-loading="loading"
        :header-cell-style="{background:'#f9f9f9'}"
        :data="tableData"
        style="width: 100%"
      >
        <el-table-column
          prop="date"
          label="时间"
        >
          <template slot-scope="scope">
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="content"
          label="操作"
        />
        <el-table-column
          prop="ip"
          label="IP"
        />
        <el-table-column
          prop="ip_addr"
          label="IP归属地"
        />
        <el-table-column
          prop="platform_cn"
          label="来源"
        />
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
  </div>
</template>

<script>
import { memberActionLog, memberLoginLog } from '@/api/member'
import { parseTime } from '@/utils'

export default {
  name: 'Log',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: {
    uid: {
      default: ''
    }
  },
  data(){
    return {
      loading: false,
      keyword: '',
      tableData: [],
      currentPage: 1,
      pagesize: 10,
      total: 0,
      d_uid: 0,
      type: 'operation'
    }
  },
  created() {
    this.d_uid = this.uid
    this.memberActionLog(this.uid)
  },
  methods: {
    memberActionLog(uid){
      this.loading = true
      memberActionLog({ 'uid': uid, 'page': this.currentPage, 'pagesize': this.pagesize, 'keyword': this.keyword }).then(response => {
        this.tableData = response.data.items
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
        this.loading = false
      })
    },
    loginLog(uid){
      this.loading = true
      memberLoginLog({ 'uid': uid, 'page': this.currentPage, 'pagesize': this.pagesize }).then(response => {
        this.tableData = response.data.items
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
        this.loading = false
      })
    },
    switchTemplate(type){
      this.type = type
      this.currentPage = 1
      if (this.type == 'operation'){
        this.memberActionLog(this.d_uid)
      }
      if (this.type == 'sign'){
        this.loginLog(this.d_uid)
      }
    },
    handleSizeChange(val){
      this.pagesize = val
      if (this.type == 'operation'){
        this.memberActionLog(this.d_uid)
      }
      if (this.type == 'sign'){
        this.loginLog(this.d_uid)
      }
    },
    funSearchKeyword(){
      if (this.keyword == ''){
        this.currentPage = 1
      }
      this.memberActionLog(this.d_uid)
    },
    handleCurrentChange(val){
      this.currentPage = val
      if (this.type == 'operation'){
        this.memberActionLog(this.d_uid)
      }
      if (this.type == 'sign'){
        this.loginLog(this.d_uid)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.list-search{
  display: inline-block;
  width: 0px;
  float: right;
  margin-top: 10px;
}
.list-search .input-with-select{
  width: 261px;
  float: right;
}
.bortton-page{
  margin-top: 30px;
  padding-bottom: 40px;
}
.popup_contacts{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding:0px 20px;
}
.bortton-page{
  padding-bottom: 40px;
}
.box{
  margin: 15px 0px;
  .button{
    display: inline-block;
    flex-shrink: 0;
    div{
      width: 90px;
      height: 30px;
      display: inline-block;
      background-color: #d4d4da30;
      border-radius: 20px;
      text-align: center;
      margin-top: 10px;
      font-size: 13px;
      line-height: 30px;
      cursor: pointer;
      color: #00000087;
      margin-right: 16px;
    }
    .selectButton{
      width: 90px;
      background-color: #0a79eb;
      color: #FFFFFF;
    }
  }
  .bortton-page{
    margin-top: 20px;
    padding-bottom: 40px;
  }
}
</style>
