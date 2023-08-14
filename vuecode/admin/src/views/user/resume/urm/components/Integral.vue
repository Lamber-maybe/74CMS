<template>
  <div class="popup_contacts">
    <div class="tables">
      <el-table
        v-loading="loading"
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
    </div>
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
    }
  },
  data(){
    return {
      loading: false,
      tableData: [],
      currentPage: 1,
      pagesize: 10,
      total: 0,
      d_uid: 0
    }
  },
  created() {
    this.d_uid = this.uid
    this.pointsLog(this.d_uid)
  },
  methods: {
    pointsLog(uid){
      this.loading = true
      pointsLog({ 'page': this.currentPage, 'pagesize': this.pagesize, 'company_uid': uid })
        .then(res => {
          this.tableData = res.data.list
          this.total = res.data.total
          this.currentPage = res.data.current_page
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    handleSizeChange(val){
      this.pagesize = val
      this.pointsLog(this.d_uid)
    },
    handleCurrentChange(val){
      this.currentPage = val
      this.pointsLog(this.d_uid)
    }
  }
}
</script>

<style lang="scss" scoped>
.tables{
  margin-top: 20px;
}
.popup_contacts{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding: 20px 20px;
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
