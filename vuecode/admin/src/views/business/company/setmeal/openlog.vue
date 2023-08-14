<template>
  <div class="app-container">
    <div class="list-search">
      <el-select
        v-model="setmeal_id"
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
        v-model="type"
        placeholder="不限开通方式"
        class="list-options"
        @change="funSearch"
      >
        <el-option label="不限开通方式" value="" />
        <el-option
          v-for="(item, index) in options_setmeal_open_type"
          :key="index"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <el-select
        v-model="admin_id"
        placeholder="不限操作人"
        class="list-options"
        @change="funSearch"
      >
        <el-option label="不限操作人" value="" />
        <el-option
          v-for="(item, index) in options_admin"
          :key="index"
          :label="item.username"
          :value="item.id"
        />
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
          <el-option label="会员UID" value="4" />
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
            scope.row.companyname
              ? scope.row.companyname
              : "[未填写企业名称]"
          }}【uid:{{ scope.row.uid }}】</span>
        </template>
      </el-table-column>
      <el-table-column label="开通套餐名称" prop="setmeal_name" min-width="120" />
      <el-table-column prop="type_cn" label="开通方式" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.type_cn }}</span>
          <i
            v-if="scope.row.order_id > 0"
            style="
              margin-left: 6px;
              color: #409eff;
              font-size: 16px;
              cursor: pointer;
            "
            class="el-icon-view"
            @click="fun_detail(scope.row)"
          />
        </template>
      </el-table-column>
      <el-table-column
        min-width="150"
        align="center"
        prop="created_at"
        label="开通时间"
      >
        <template slot-scope="scope">
          <i class="el-icon-time" />
          <span>{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作人" min-width="100">
        <template slot-scope="scope">
          <span>{{
            scope.row.admin_username ? scope.row.admin_username : "-"
          }}</span>
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
    <el-dialog title="订单详情" :visible.sync="dialogFormVisible" width="35%">
      <el-row>
        <el-col :span="12">服务名称：{{ dialogContent.service_name }}</el-col>
        <el-col :span="12">订单号：{{ dialogContent.oid }}</el-col>
      </el-row>
      <el-row>
        <el-col :span="12">服务金额：{{ dialogContent.service_amount }}</el-col>
        <el-col :span="12">下单时间：{{ dialogContent.addtime }}</el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          折后金额：{{ dialogContent.service_amount_after_discount }}
        </el-col>
        <el-col :span="12">支付时间：{{ dialogContent.paytime }}</el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          优惠券抵扣：{{ dialogContent.deduct_amount }}
        </el-col>
        <el-col :span="12">支付状态：已支付</el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          实付金额：
          <span class="font_warning" style="font-weight: bold">
            {{ dialogContent.amount }}
          </span>
        </el-col>
        <el-col :span="12">支付方式：{{ dialogContent.payment }}</el-col>
      </el-row>
      <el-row v-if="dialogContent.amount_detail != ''">
        <el-col :span="24">支付详情：{{ dialogContent.amount_detail }}</el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { companySetmealOpenLog } from '@/api/company_setmeal'
import { adminAllList } from '@/api/admin'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      dialogFormVisible: false,
      dialogContent: {
        oid: '',
        service_name: '',
        service_amount: '',
        service_amount_after_discount: '',
        amount: '',
        deduct_amount: '',
        payment: '',
        addtime: '',
        paytime: '',
        status: '',
        amount_detail: ''
      },
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      type: '',
      setmeal_id: '',
      admin_id: '',
      options_admin: [],
      options_setmeal: [],
      options_setmeal_open_type: [],
      options_payment: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getClassify({ type: 'setmealList,setmealOpenType,orderPayment' })
        .then(response => {
          this.options_setmeal = [...response.data.setmealList]
          this.options_setmeal_open_type = [...response.data.setmealOpenType]
          this.options_payment = [...response.data.orderPayment]
          const param = {
            key_type: this.key_type,
            keyword: this.keyword,
            setmeal_id: this.setmeal_id,
            type: this.type,
            admin_id: this.admin_id,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return companySetmealOpenLog(param)
        })
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
          return adminAllList()
        })
        .then(response => {
          this.options_admin = [...response.data]
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
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    fun_detail(info) {
      this.dialogContent = {
        oid: info.oid,
        service_name: info.service_name,
        service_amount: '￥' + info.service_amount,
        service_amount_after_discount:
          '￥' + info.service_amount_after_discount,
        amount: '￥' + info.amount,
        deduct_amount: '￥' + info.deduct_amount,
        addtime: parseTime(info.order_addtime, '{y}-{m}-{d} {h}:{i}'),
        paytime:
          info.paytime == 0
            ? '未支付'
            : parseTime(info.paytime, '{y}-{m}-{d} {h}:{i}'),
        amount_detail: info.amount_detail
      }
      if (info.payment == '') {
        this.dialogContent.payment = '-'
      } else {
        for (const item of this.options_payment) {
          if (item.id == info.payment) {
            this.dialogContent.payment = item.name
            break
          }
        }
      }
      this.dialogFormVisible = true
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
