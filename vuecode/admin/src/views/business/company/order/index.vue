<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>订单管理</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="status"
          placeholder="不限支付状态"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限支付状态" value="" />
          <el-option
            v-for="(item, index) in options_status"
            :key="index"
            :label="item"
            :value="index"
          />
        </el-select>
        <el-select
          v-model="payment"
          placeholder="不限支付方式"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限支付方式" value="" />
          <el-option
            v-for="(item, index) in options_payment"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-select
          v-model="service_type"
          placeholder="不限订单类型"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限订单类型" value="" />
          <el-option
            v-for="(item, index) in options_service_type"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-select
          v-model="add_settr"
          placeholder="不限下单时间"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限下单时间" value="" />
          <el-option label="3天内" value="3" />
          <el-option label="7天内" value="7" />
          <el-option label="15天内" value="15" />
          <el-option label="30天内" value="30" />
        </el-select>
        <el-select
          v-model="pay_settr"
          placeholder="不限付款时间"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限付款时间" value="" />
          <el-option label="3天内" value="3" />
          <el-option label="7天内" value="7" />
          <el-option label="15天内" value="15" />
          <el-option label="30天内" value="30" />
        </el-select>
        <el-select
          v-model="sort"
          placeholder="按下单时间排序"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="按下单时间排序" value="" />
          <el-option label="按付款时间排序" value="1" />
          <el-option label="按订单状态排序" value="2" />
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
            <el-option label="企业ID" value="1" />
            <el-option label="企业名称" value="2" />
            <el-option label="会员UID" value="3" />
            <el-option label="会员手机号" value="4" />
            <el-option label="订单号" value="5" />
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
        <el-table-column label="企业名称" prop="companyname" min-width="200" />
        <el-table-column label="服务内容" prop="service_name" min-width="150" />
        <el-table-column label="订单金额" min-width="120">
          <template slot-scope="scope">￥{{ scope.row.amount }}</template>
        </el-table-column>
        <el-table-column label="支付方式" min-width="120">
          <template slot-scope="scope">
            <span v-if="scope.row.payment == ''">
              -
            </span>
            <span v-else>
              <span v-for="(item, index) in options_payment" :key="index">
                <span v-if="item.id == scope.row.payment">{{ item.name }}</span>
              </span>
            </span>
          </template>
        </el-table-column>
        <el-table-column label="订单状态" min-width="100">
          <template slot-scope="scope">
            <el-tag :type="scope.row.status | statusFilter">
              {{ options_status[scope.row.status] }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" label="下单时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="付款时间" min-width="160">
          <template slot-scope="scope">
            <span v-if="scope.row.paytime > 0">
              <i class="el-icon-time" />
              {{ scope.row.paytime | timeFilter }}
            </span>
            <span v-else>-</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="340">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="fun_detail(scope.row)"
            >
              详情
            </el-button>
            <el-button
              v-if="scope.row.status == 0"
              size="small"
              type="warning"
              @click="funConfirm(scope.$index, scope.row)"
            >
              确认付款
            </el-button>
            <el-button
              v-if="scope.row.status == 0"
              size="small"
              type="danger"
              @click="funCancel(scope.$index, scope.row)"
            >
              关闭
            </el-button>
            <el-button
              size="small"
              @click="funCopy(scope.row.oid)"
            >
              复制单号
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8" />
        <el-col :offset="8" :span="16" style="text-align: right;">
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
    </el-card>
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
        <el-col :span="12">支付状态：{{ dialogContent.status }}</el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          实付金额：
          <span class="font_warning" style="font-weight:bold">
            {{ dialogContent.amount }}
          </span>
        </el-col>
        <el-col :span="12">支付方式：{{ dialogContent.payment }}</el-col>
      </el-row>
      <el-row v-if="dialogContent.amount_detail != ''">
        <el-col :span="24">支付详情：{{ dialogContent.amount_detail }}</el-col>
      </el-row>
      <el-row v-if="dialogContent.note != ''">
        <el-col :span="24" style="line-height:26px;">备注：{{ dialogContent.note }}</el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { orderList, orderConfirm, orderCancel } from '@/api/order'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    },
    statusFilter(status) {
      switch (status) {
        case 0:
          return 'warning'
        case 1:
          return 'success'
        case 2:
          return 'info'
        default:
          return 'info'
      }
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
      status: '',
      payment: '',
      service_type: '',
      add_settr: '',
      pay_settr: '',
      options_status: [],
      options_payment: [],
      options_service_type: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      keyword: '',
      sort: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true

      getClassify({ type: 'orderStatus,orderPayment,orderServiceTypeCompany' })
        .then(response => {
          const options_status_arr = [...response.data.orderStatus]
          options_status_arr.forEach(el => {
            this.options_status[el.id] = el.name
          })
          this.options_payment = [...response.data.orderPayment]
          this.options_service_type = [...response.data.orderServiceTypeCompany]

          const param = {
            utype: 1,
            status: this.status,
            payment: this.payment,
            service_type: this.service_type,
            add_settr: this.add_settr,
            pay_settr: this.pay_settr,
            sort: this.sort,
            key_type: this.key_type,
            keyword: this.keyword,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return orderList(param)
        })
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
        })
        .catch(() => {})
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
    goto(target) {
      this.$router.push(target)
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
        addtime: parseTime(info.addtime, '{y}-{m}-{d} {h}:{i}'),
        paytime:
          info.paytime == 0
            ? '未支付'
            : parseTime(info.paytime, '{y}-{m}-{d} {h}:{i}'),
        status: this.options_status[info.status],
        amount_detail: info.amount_detail,
        note:info.note
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
    },
    funCancel(index, row) {
      var that = this
      that
        .$confirm('确定关闭该订单吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          orderCancel(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    funConfirm(index, row) {
      var that = this
      this.$prompt('请输入备注', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        closeOnClickModal: false,
        beforeClose: (action, instance, done) => {
          if (action === 'confirm') {
            const note = instance.inputValue ? instance.inputValue : ''
            instance.confirmButtonLoading = true
            instance.confirmButtonText = '执行中...'
            const param = {
              id: row.id,
              note: note
            }
            orderConfirm(param).then(response => {
              instance.confirmButtonLoading = false
              done()
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
          } else {
            done()
          }
        }
      })
    },
    funCopy(oid){
      var oInput = document.createElement('input')
      oInput.value = oid
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$message({ type: 'success', message: '复制成功' })
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
