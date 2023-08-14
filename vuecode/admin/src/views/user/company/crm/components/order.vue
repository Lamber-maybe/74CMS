<template>
  <div class="order_wrapper">
    <div class="search_wrapper">
      <el-select v-model="params.service_type" class="select_item" placeholder="服务类型" @change="handleServiceType">
        <el-option
          v-for="item in orderServiceTypeCompany"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <el-select v-model="params.payment" class="select_item" placeholder="支付方式" @change="handlePaytype">
        <el-option
          v-for="item in orderPayment"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
    </div>
    <el-table v-loading="loading" :data="tableList" :header-cell-style="{background:'#f9f9f9'}" style="width: 100%">
      <el-table-column width="20" />
      <el-table-column prop="service_name" label="服务内容" />
      <el-table-column prop="service_amount" label="订单金额" />
      <el-table-column prop="payment_name" label="支付方式">
        <template slot-scope="scoped">
          {{ scoped.row.payment_name ? scoped.row.payment_name : '-' }}
        </template>
      </el-table-column>
      <el-table-column prop="addtime" label="下单时间">
        <template slot-scope="scoped">
          <span v-if="scoped.row.addtime == 0">-</span>
          <span v-if="scoped.row.addtime != 0">{{ scoped.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column prop="paytime" label="付款时间">
        <template slot-scope="scoped">
          <span v-if="scoped.row.paytime == 0">-</span>
          <span v-if="scoped.row.paytime != 0">{{ scoped.row.paytime | timeFilter }}</span>

        </template>
      </el-table-column>
      <el-table-column label="操作" width="210">
        <template slot-scope="scoped">
          <el-button size="small" @click.native="funCopy(scoped.row.oid)">复制单号</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="bortton-page">
      <el-col :span="24" style="text-align: right">
        <el-pagination
          background
          :current-page="params.page"
          :page-sizes="[10, 20, 50, 100]"
          :page-size="params.pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="params.total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </div>
    <!--    <AddOrder ref="addOrder" @updataList="getData" />-->
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { crmOrderList } from '@/api/company_crm'
import { parseTime } from '@/utils'
import { getClassify } from '@/api/classify'
import { orderList } from '@/api/order'

export default {
  name: 'Order',
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
      tableList: [],
      params: {
        page: 1,
        pagesize: 10,
        company_uid: '',
        total: 0,
        payment: '',
        service_type: ''
      },
      orderPayment: [],
      orderServiceTypeCompany: [],
      ownerList: []
    }
  },
  created () {
    this.getData(this.uid)
    this.fetchData()
  },
  methods: {
    fetchData() {
      getClassify({ type: 'orderServiceTypeCompany,orderPayment' })
        .then(response => {
          this.orderPayment = [...response.data.orderPayment]

          this.orderServiceTypeCompany = [...response.data.orderServiceTypeCompany]
        }).catch(() => {})
    },
    getData (uid) {
      this.loading = true
      if (uid){
        this.params.company_uid = uid
      } else {
        this.params.company_uid = this.uid
      }
      crmOrderList(this.params).then(res => {
        this.tableList = res.data.items
        this.params.total = res.data.total
        this.loading = false
      })
    },
    funCopy (oid) {
      var oInput = document.createElement('input')
      oInput.value = oid
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$message({ type: 'success', message: '复制成功' })
    },
    // 分页每页展示多少条
    handleSizeChange (value) {
      this.params.page_size = value
      this.getData(this.uid)
    },
    // 分页当前第几页
    handleCurrentChange (value) {
      this.params.page = value
      this.getData(this.uid)
    },
    handleServiceType(){
      this.getData()
    },
    handlePaytype(){
      this.getData()
    }
  }
}
</script>

<style lang="scss" scoped>
.search_wrapper ::v-deep .el-input__inner{
  height: 41px;
  line-height: 41px;
}
.order_wrapper{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding: 10px 20px;
  .search_wrapper{
    padding: 13px 0 13px 20px;
    .select_item{
      width: 175px;
      margin-right: 20px;
    }
    .order_time{
      width: 360px;
    }
  }
}
::v-deep .is-dark{
  background: #fff;
}

::v-deep .el-dialog__body{
  padding: 30px 20px;
}
::v-deep .el-dialog__footer{
  padding: 12px 20px 12px 0;
}
::v-deep .el-timeline {
  margin: 20px 0 0 20px;
  padding: 0;
}
.el-row{
  height: 40px;
  line-height: 40px;
}
.el-col{
  height: 40px;
  line-height: 40px;
}
.bortton-page{
  margin-top: 20px;
  padding-bottom: 40px;
}
</style>
