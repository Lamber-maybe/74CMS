<template>
  <div class="popup_contacts">
    <div class="box">
      <div class="button">
        <div :class="type=='integral' ? 'selectButton' : ''" @click="switchTemplate('integral')">积分</div>
        <div :class="type=='resume' ? 'selectButton' : ''" @click="switchTemplate('resume')">简历点</div>
      </div>
    </div>
    <el-table
      v-if="type=='integral'"
      :header-cell-style="{background:'#f9f9f9'}"
      :data="tableData"
      style="width: 100%"
    >
      <el-table-column
        prop="content"
        label="积分来源"
      />
      <el-table-column
        prop="name"
        label="类型"
      >
        <template slot-scope="scope">
          <span v-if="scope.row.op == 1">
            增加
          </span>
          <span v-else>
            减少
          </span>
        </template>
      </el-table-column>
      <el-table-column
        prop="address"
        label="时间"
      >
        <template slot-scope="scope">
          {{ scope.row.addtime | timeFilter }}
        </template>
      </el-table-column>
      <el-table-column
        prop="points"
        label="积分"
      />
    </el-table>
    <el-table
      v-if="type=='resume'"
      :header-cell-style="{background:'#f9f9f9'}"
      :data="tableData"
      style="width: 100%"
    >
      <el-table-column
        prop="content"
        label="内容"
      />
      <el-table-column
        prop="address"
        label="时间"
      >
        <template slot-scope="scope">
          {{ scope.row.addtime | timeFilter }}
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
</template>

<script>
import { pointsLog, consumeLog } from '@/api/company_crm'
import { parseTime } from '@/utils'

export default {
  name: 'Integral',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: {
    uid: {
      default: ''
    },
    prop_type: {
      default: ''
    }
  },
  data(){
    return {
      type: 'integral',
      tableData: [],
      currentPage: 1,
      pagesize: 10,
      total: 0,
      d_uid: 0
    }
  },
  watch: {
    prop_type(value){
      this.type = value
      this.switchTemplate(value)
    },
    uid(value){
      this.d_uid = value
      this.pointsLog(value)
    }
  },
  methods: {
    pointsLog(uid){
      pointsLog({ 'page': this.currentPage, 'pagesize': this.pagesize, 'company_uid': uid })
        .then(res => {
          this.tableData = res.data.list
          this.total = res.data.total
          this.currentPage = res.data.current_page
        }).catch(() => {

        })
    },
    consumeLog(uid){
      consumeLog({ 'page': this.currentPage, 'page_size': this.pagesize, 'company_uid': uid })
        .then(res => {
          this.tableData = res.data.list
          this.total = res.data.total
          this.currentPage = res.data.current_page
        }).catch(() => {

        })
    },
    switchTemplate(type){
      this.type = type
      this.currentPage = 1
      if (type == 'integral'){
        this.pointsLog(this.d_uid)
      }
      if (type == 'resume'){
        this.consumeLog(this.d_uid)
      }
    },
    handleSizeChange(val){
      this.pagesize = val
      if (this.type == 'integral'){
        this.pointsLog(this.d_uid)
      }
      if (this.type == 'resume'){
        this.consumeLog(this.d_uid)
      }
    },
    handleCurrentChange(val){
      this.currentPage = val
      if (this.type == 'integral'){
        this.pointsLog(this.d_uid)
      }
      if (this.type == 'resume'){
        this.consumeLog(this.d_uid)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.popup_contacts{
  padding:0px 20px;
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding: 10px 20px;
}
.bortton-page{
  padding-bottom: 40px;
}
.box{
  margin: 15px 0px;
  .button{
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
.popup_contacts{
  padding:0px 20px;
}
.bortton-page{
  margin-top: 30px;
  padding-bottom: 40px;
}
</style>
