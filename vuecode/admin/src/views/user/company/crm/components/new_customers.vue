<template>
  <div>
    <el-card class="box-card" style="position:relative;">
      <div class="tit">
        新客户排行榜（{{ currentMonth }}月）
        <!-- <div>
          <el-date-picker v-model="date" type="month" placeholder="选择月">
          </el-date-picker>
        </div> -->
      </div>
      <el-table v-loading="listLoading" :data="dataset" size="mini" :height="height" :row-class-name="tableRowClassName">
        <!-- <el-table-column label="排名" type="index" width="50" align="center"></el-table-column> -->
        <el-table-column label="排名">
          <template slot-scope="scope">
            <img v-if="scope.row.ranking == 1" class="top_icon" src="../../../../../assets/images/company/crm/SaleStatistics/top1.png" alt="">
            <img v-if="scope.row.ranking == 2" class="top_icon" src="../../../../../assets/images/company/crm/SaleStatistics/top2.png" alt="">
            <img v-if="scope.row.ranking == 3" class="top_icon" src="../../../../../assets/images/company/crm/SaleStatistics/top3.png" alt="">
            <div v-if="scope.row.ranking != 1 && scope.row.ranking != 2 && scope.row.ranking != 3" style="color: #B0B0B0;font-size: 18px;font-style:italic;">
              {{ scope.row.ranking }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="姓名">
          <template slot-scope="scope">
            <div style="color: #666;">{{ scope.row.username }}</div>
          </template>
        </el-table-column>
        <el-table-column label="新客户数" align="center">
          <template slot-scope="scope">
            <div style="color: #FF7C0E;">{{ scope.row.num }}个</div>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
export default {
  props: ['height', 'dataArr'],
  data() {
    return {
      daterange: '',
      listLoading: true,
      dataset: [],
      date: new Date(),
      currentMonth: new Date().getMonth() + 1
    }
  },
  watch: {
    dataArr(val) {
      this.listLoading = false
      this.dataArr = val
      this.dataset = val.new_customersdeal_ranking_list
    }
  },
  created() {},
  methods: {
    tableRowClassName({ row, rowIndex }) {
      // console.log(row, rowIndex)
      if (rowIndex === 0) {
        return 'top1'
      } else if (rowIndex === 1) {
        return 'top2'
      } else if (rowIndex === 2) {
        return 'top3'
      }
      return ''
    }
  }
}
</script>
<style scoped>
.tit {
  font-size: 18px;
  color: #333;
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

::v-deep .el-table th.is-leaf,
::v-deep .el-table td {
  border-bottom: none;
}

/* ::v-deep .el-table tr.top1 {
  background: url('../../../../../assets/images/company/crm/SaleStatistics/top_bg1.png') left 2px no-repeat;
  background-size: 100% 80%;
}

::v-deep .el-table tr.top1 .el-table_2_column_4 {
  background: url('../../../../../assets/images/company/crm/SaleStatistics/top1.png') 15px 6px no-repeat;
  background-size: 40% 50%;
}

::v-deep .el-table .top2 {
  background: url('../../../../../assets/images/company/crm/SaleStatistics/top_bg2.png') left 2px no-repeat;
  background-size: 100% 80%;
}

::v-deep .el-table tr.top2 .el-table_2_column_4 {
  background: url('../../../../../assets/images/company/crm/SaleStatistics/top2.png') 15px 6px no-repeat;
  background-size: 40% 50%;
}

::v-deep .el-table .top3 {
  background: url('../../../../../assets/images/company/crm/SaleStatistics/top_bg3.png') left 2px no-repeat;
  background-size: 100% 80%;
}

::v-deep .el-table tr.top3 .el-table_2_column_4 {
  background: url('../../../../../assets/images/company/crm/SaleStatistics/top3.png') 15px 6px no-repeat;
  background-size: 40% 50%;
} */
::v-deep .tit .el-date-editor.el-input {
  width: 100px;
}

::v-deep .el-table tr .el-table_2_column_5 {
  box-sizing: border-box;
  padding-left: 40px;
}

::v-deep .tit .el-input__inner {
  height: 28px;
  line-height: 28px;
  font-size: 12px;
}

::v-deep .tit .el-input__icon {
  line-height: 28px;
}

::v-deep .el-input--prefix .el-input__inner {
  padding-right: 0;
}

.top_icon {
  /* margin-top: 3px; */
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
