<template>
  <div class="app-container">
    <div class="list-search">
      <el-select
        v-model="setmeal"
        placeholder="不限套餐类型"
        class="list-options"
        @change="funSearch"
      >
        <el-option label="不限套餐类型" value="" />
        <el-option
          v-for="(item, index) in options_setmeal"
          :key="index"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <el-select
        v-model="expire"
        placeholder="不限到期时间"
        class="list-options"
        @change="funSearch"
      >
        <el-option label="不限到期时间" value="" />
        <el-option label="即将到期" value="2" />
        <el-option label="已到期" value="1" />
      </el-select>
      <el-input
        v-model="keyword"
        placeholder="请输入搜索内容"
        class="input-with-select"
        @keyup.enter.native="funSearchKeyword"
      >
        <el-select
          slot="prepend"
          v-model="key_type"
          placeholder="请选择"
          class="input-sel"
        >
          <el-option label="公司名称" value="1" />
          <el-option label="公司ID" value="2" />
          <el-option label="会员手机号" value="3" />
          <el-option label="会员ID" value="4" />
        </el-select>
        <el-button
          slot="append"
          icon="el-icon-search"
          @click="funSearchKeyword"
        />
      </el-input>
    </div>
    <div class="spaceline" />
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
    >
      <el-table-column label="企业名称" min-width="250">
        <template slot-scope="scope">
          <span>{{
            scope.row.companyname ? scope.row.companyname : "未完善企业资料"
          }}【uid:{{ scope.row.uid }}】</span>
        </template>
      </el-table-column>
      <el-table-column label="套餐名称" prop="setmeal_name" min-width="120" />
      <el-table-column label="联系人" min-width="180">
        <template slot-scope="scope">
          <span
            v-if="scope.row.contact"
          >{{ scope.row.contact }}({{ scope.row.mobile }})</span>
          <span v-else>-</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="到期时间" min-width="100">
        <template slot-scope="scope">
          <span v-if="scope.row.expire == 0">{{ scope.row.deadline_cn }}</span>
          <span v-if="scope.row.expire == 2" style="color: #e6a23c">{{
            scope.row.deadline_cn
          }}</span>
          <span v-if="scope.row.expire == 1" style="color: #f56c6c">{{
            scope.row.deadline_cn
          }}</span>
        </template>
      </el-table-column>
      <el-table-column label="剩余天数" min-width="100">
        <template slot-scope="scope">
          <span v-if="scope.row.expire == 0">{{ scope.row.surplus_days }}</span>
          <span v-if="scope.row.expire == 2" style="color: #e6a23c">{{
            scope.row.surplus_days
          }}</span>
          <span
            v-if="scope.row.expire == 1"
            style="color: #f56c6c"
          >{{ scope.row.surplus_days }}(已过期)</span>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" min-width="240">
        <template slot-scope="scope">
          <el-button size="small" type="primary" @click="funAdd(scope.row)">
            更换套餐
          </el-button>
          <el-button size="small" type="primary" @click="funEdit(scope.row)">
            编辑
          </el-button>
          <el-button size="small" @click="funLog(scope.row)"> 日志 </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-row :gutter="20">
      <el-col :span="8" />
      <el-col :offset="8" :span="16" style="text-align: right">
        <el-pagination
          background
          :current-page="currentPage"
          :page-sizes="[10, 20, 50, 100]"
          :page-size="pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </el-row>
    <el-dialog
      v-if="dialogListVisible"
      title="套餐日志"
      :visible.sync="dialogListVisible"
      width="50%"
      :close-on-click-modal="false"
      @close="dialogListVisible = false"
    >
      <dialist
        :uid="listUid"
        @setDialogFormVisible="dialogListVisible = false"
      />
    </el-dialog>
    <el-dialog
      v-if="dialogFormVisible"
      title="编辑套餐"
      :visible.sync="dialogFormVisible"
      width="56%"
      :close-on-click-modal="false"
    >
      <diaform
        :uid="listUid"
        @setDialogFormVisible="dialogFormVisible = false"
        @pageReload="fetchData"
      />
    </el-dialog>
    <el-dialog
      v-if="dialogAddVisible"
      title="更换套餐"
      :visible.sync="dialogAddVisible"
      width="25%"
      :close-on-click-modal="false"
    >
      <diaadd
        :uid="listUid"
        @setDialogFormVisible="dialogAddVisible = false"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaadd from './add.vue'
import diaform from './form.vue'
import dialist from './loglist.vue'
import { getClassify } from '@/api/classify'
import { companySetmealList } from '@/api/company_setmeal'

export default {
  components: {
    dialist,
    diaform,
    diaadd
  },
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      expire: '',
      setmeal: '',
      options_setmeal: [],
      dialogListVisible: false,
      dialogFormVisible: false,
      dialogAddVisible: false,
      listUid: 0
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getClassify({ type: 'setmealList' })
        .then(response => {
          this.options_setmeal = [...response.data]
          const param = {
            key_type: this.key_type,
            keyword: this.keyword,
            setmeal: this.setmeal,
            expire: this.expire,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return companySetmealList(param)
        })
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => { })
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    funSearch() {
      this.currentPage = 1
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    funLog(row) {
      this.listUid = row.uid
      this.dialogListVisible = true
    },
    funEdit(row) {
      if (row) {
        this.listUid = row.uid
      } else {
        this.listUid = 0
      }
      this.dialogFormVisible = true
    },
    funAdd(row) {
      if (row) {
        this.listUid = row.uid
      } else {
        this.listUid = 0
      }
      this.dialogAddVisible = true
    }
  }
}
</script>
<style scoped>
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
</style>
