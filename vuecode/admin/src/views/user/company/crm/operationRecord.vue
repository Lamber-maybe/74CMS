<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ title }}</span>
      </div>
      <div class="tip">
        <p>扣次规则：</p>
        <p>按搜索条件扣除查询额度，比如您要搜索<span>骑士</span>时，需扣除<span>1</span>次查询额度；</p>
        <p>如因搜索词不精准导致查询结果较多，搜索结果最多显示<span>10条</span>/页，如果您要切换到下一页，仍需再次扣除<span>1</span>次查询额度；</p>
        <p>如果您在<span>30</span>天内重复查询同一关键词且条件完全相同时，不扣除查询额度；如搜索的关键词相同但条件不同时，仍需要扣除查询额度。</p>
      </div>
      <div class="tab">
        <el-table ref="multipleTable" v-loading="loading" :data="tableData" tooltip-effect="dark"
          :header-cell-style="{background:'#f9f9f9', 'border-right': '1px solid #e4e6eb'}" border stripe
          style="width: 100%;">
          <el-table-column prop="create_time" label="查询时间"></el-table-column>
          <el-table-column prop="username" label="操作人"></el-table-column>
          <el-table-column prop="keyword" label="搜索词"></el-table-column>
          <el-table-column prop="content" label="搜索条件" width="400">
            <template slot-scope="scope">
              <div class="tableDiv" v-for="(items,indexs) in scope.row.query" :key="indexs">
                <span style="color: #999999;">{{items.title}}: </span>
                <span>{{items.name}}；</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="content" label="结果展示" width="400"></el-table-column>
          <el-table-column label="扣除查询余额">1</el-table-column>
        </el-table>
        <div class="pagination-box">
          <el-pagination background destroy-on-close :current-page="currentPage" :page-sizes="[10, 15, 20, 30, 40]"
            :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="total"
            @size-change="handleSizeChange" @current-change="handleCurrentChange" />
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
  import {
    getConsumeRecordList
  } from '@/api/directory'

  export default {
    name: 'operationRecord',
    data() {
      return {
        title: '资源消耗记录',
        loading: false,
        pagesize: 10,
        total: 0,
        total_page: 0,
        currentPage: 1,
        tableData: []
      }
    },
    computed: {},
    watch: {},
    mounted() {
      this.initData()
    },
    created() {},
    methods: {
      initData() {
        let params = {
          page: this.currentPage,
          pagesize: this.pagesize
        }
        getConsumeRecordList(params).then(res => {
          this.total = res.data.total
          this.total_page = res.data.total_page
          this.tableData = res.data.items
        })
      },
      handleSizeChange(val) {
        this.pagesize = val
        this.initData()
      },
      handleCurrentChange(val) {
        this.currentPage = val
        this.initData()
      }
    }
  }
</script>

<style scoped lang="scss">
  ::v-deep.el-table .cell {
    padding-left: 30px;
  }

  ::v-deep.el-table--border td:first-child .cell,
  ::v-deep.el-table--border th:first-child .cell {
    padding-left: 30px;
  }

  .pagination-box {
    text-align: right;
    margin-top: 20px;
  }

  .tip {
    p {
      span {
        color: #409EFF;
        padding: 0 2px;
      }
    }
  }

  .tableDiv {
    display: inline-block;
    width: auto;
  }
</style>
