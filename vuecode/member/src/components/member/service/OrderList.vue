<template>
  <div>
    <div class="tab_header" v-if="utype==1">
      <div>订单类型：
        <el-select v-model="params.service_type" @change="doSearch(1,true)">
          <el-option
            v-for="(item,index) in options_type"
            :key="index"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </div>
      <div>订单状态：
        <el-select v-model="params.status" @change="doSearch(1,true)">
          <el-option
            v-for="(item,index) in options_status"
            :key="index"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </div>
    </div>
    <div class="tab-con">
        <el-table :header-cell-style="{background:'#fcfcfc',color:'#999ba1'}"  :data="dataset"  style="width: 100%" v-loading="listLoading" v-if="utype==1">
          <el-table-column  prop="information"  label="订单信息" >
            <template slot-scope="scope">
                <p class="tba_id">{{scope.row.oid}}</p>
                <p class="tab_title">{{scope.row.service_name}}</p>
            </template>
          </el-table-column>
          <el-table-column  prop="amount"  label="付款金额" align="center" width="150">
            <template slot-scope="scope">
                <span class="tab_price" >￥{{scope.row.amount}}</span>
            </template>
          </el-table-column>
          <el-table-column  prop="payment_name" label="支付方式" align="center">
            <template slot-scope="scope">
              {{scope.row.payment_name?scope.row.payment_name:'-'}}
            </template>
          </el-table-column>
          <el-table-column  prop="addtime"  label="下单时间" align="center">
            <template slot-scope="scope">
              {{scope.row.addtime | timeFilter}}
            </template>
          </el-table-column>
          <el-table-column  label="订单状态" align="center">
            <template slot-scope="scope">
                <div class="tab_type" v-if="scope.row.status==0">未付款</div>
                <div class="tab_type2" v-if="scope.row.status==1">已完成</div>
                <div class="tab_type3" v-if="scope.row.status==2">已取消</div>
            </template>
          </el-table-column>
          <el-table-column  header-align="center"  width="220" label="操作">
            <template slot-scope="scope">
                <el-button type="primary" size="mini" v-if="scope.row.status==0" @click="handlerRepay(scope.row)">支付</el-button>
                <el-button size="mini" type="warning" @click="handlerDetail(scope.row)">详情</el-button>
                <el-button size="mini" v-if="scope.row.status==0" @click="handlerCancel(scope.row)">取消</el-button>
                <el-button size="mini" v-if="scope.row.status==2" @click="handlerDel(scope.row)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-table :header-cell-style="{background:'#fcfcfc',color:'#999ba1'}"  :data="dataset"  style="width: 100%" v-loading="listLoading" v-else>
          <el-table-column  prop="information"  label="订单信息" width="250">
            <template slot-scope="scope">
                <p class="tba_id">{{scope.row.oid}}</p>
                <p class="tab_title">{{scope.row.service_name}}</p>
            </template>
          </el-table-column>
          <el-table-column  label="订单状态" align="center" width="130">
            <template slot-scope="scope">
                <div class="tab_type" v-if="scope.row.status==0">未付款</div>
                <div class="tab_type2" v-if="scope.row.status==1">已完成</div>
                <div class="tab_type3" v-if="scope.row.status==2">已取消</div>
            </template>
          </el-table-column>
          <el-table-column  prop="amount"  label="付款金额" align="center" width="150" v-if="utype==1">
            <template slot-scope="scope">
                <span class="tab_price" >￥{{scope.row.amount}}</span>
            </template>
          </el-table-column>
          <el-table-column  prop="payment_name" label="支付方式" align="center" v-if="utype==1">
            <template slot-scope="scope">
              {{scope.row.payment_name}}
            </template>
          </el-table-column>
          <el-table-column  prop="payment_name" label="支付方式" align="center" v-if="utype==2" width="140">
            <template slot-scope="scope">
                <p class="tba_id tab_price">￥{{scope.row.amount}}</p>
            </template>
          </el-table-column>
          <el-table-column  prop="addtime"  label="下单时间" align="center" width="164">
            <template slot-scope="scope">
              {{scope.row.addtime | timeFilter}}
            </template>
          </el-table-column>
          <el-table-column  header-align="center" align="right"  label="操作">
            <template slot-scope="scope">
                <el-button type="primary" size="mini" v-if="scope.row.status==0" @click="handlerRepay(scope.row)">支付</el-button>
                <el-button size="mini" type="warning" @click="handlerDetail(scope.row)">详情</el-button>
                <el-button size="mini" v-if="scope.row.status==0" @click="handlerCancel(scope.row)">取消</el-button>
                <el-button size="mini" v-if="scope.row.status==2" @click="handlerDel(scope.row)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
    </div>
    <pagination
      :total="total"
      :current-page="params.page"
      :page-size="params.pagesize"
      @handleCurrentChange="doSearch"
    ></pagination>
    <el-dialog title="继续支付" :visible.sync="showRepay"  width="540px" @opened="initRepay">
      <RepayDialog ref="repay" @submitPay="submitPay" :order-id="repayOrderId"></RepayDialog>
    </el-dialog>
  </div>
</template>

<script>
import RepayDialog from '@/components/member/service/RepayDialog.vue'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
  export default{
  name: 'OrderList',
  props: ['utype'],
    components:{
        RepayDialog
    },
    filters: {
      timeFilter (timestamp) {
        return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
      }
    },
    data () {
      return {
        repayOrderId:0,
        showRepay:false,
        listLoading:false,
        dataset: [],
        total:0,
        params: {
          service_type: '',
          status: '',
          page: 1,
          pagesize: 15
        },
        options_type: [{ text: '全部类型', value: '' }],
        options_status: [{ text: '全部状态', value: '' }]
      }
    },
    created () {
      this.fetchData(true)
    },
    methods: {
      fetchData (init) {
        this.listLoading = true
        let url =
            this.utype == 1 ? api.company_orderlist : api.personal_orderlist
        http
          .get(url, this.params)
          .then(res => {
            this.dataset = [...res.data.items]
            this.listLoading = false
            if (init === true) {
              this.fetchDataTotal()
              this.options_type = [{ label: '全部类型', value: '' }]
              res.data.filter_type.forEach(element => {
                this.options_type.push(element)
              })
              this.options_status = [{ label: '全部状态', value: '' }]
              res.data.filter_status.forEach(element => {
                this.options_status.push(element)
              })
            }
          })
          .catch(() => {})
      },
      fetchDataTotal () {
        let url =
            this.utype == 1 ? api.company_orderlist_total : api.personal_orderlist_total
        http
          .get(url, this.params)
          .then(res => {
            this.total = res.data
          })
          .catch(() => {})
      },
      doSearch(page,initPagination) {
        this.params.page = page
        this.fetchData(initPagination)
      },
      handlerCancel (item) {
        let url =
            this.utype == 1 ? api.company_order_cancel : api.personal_order_cancel
        this.$confirm('确定取消该订单吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(url, { id: item.id })
              .then(res => {
                this.$message({ type: 'success', message: res.message })
                this.fetchData(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      handlerDel (item) {
        let url =
            this.utype == 1 ? api.company_order_del : api.personal_order_del
        this.$confirm('确定删除该订单吗？','系统提示',{type:'warning'})
          .then(() => {
            http
              .post(url, { id: item.id })
              .then(res => {
                this.$message({ type: 'success', message: res.message })
                this.fetchData(true)
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      },
      handlerDetail(item){
        if(this.utype == 1){
            this.$router.push('/company/service/order/'+item.id)
        }else{
            this.$router.push('/personal/service/order/'+item.id)
        }
      },
      initRepay(){
        this.$refs.repay.fetchData()
      },
      handlerRepay(item){
        this.repayOrderId = item.id
        this.showRepay = true
      },
      submitPay(){
        this.showRepay = false
        this.fetchData(true)
      },
    }
  }
</script>

<style lang="scss" scoped>
  .tab_header{
    display: flex;
    padding: 25px 0 25px 33px;
    background: #f9f9f9;
    color: #7f7f7f;
    font-size: 14px;
  }
  .tab_header div{
    // flex: 1;
  }
  .tab_header div:nth-of-type(2){
    margin-left:20px;
  }
  .tba_id{
    color: #1787fb;
    font-size: 14px;
  }
  .tab_title{
    font-size: 14px;
    color: #333;
  }
  .tab_price{
    font-size: 14px;
    color: #ff6600;
  }
  .tab_type{
    width: 60px;
    padding: 0 10px;
    font-size: 12px;
    color: #e6a552;
    border: 1px solid #faecd8;
    background: #fdf6ec;
    border-radius: 5px;
    display: inline-block;
  }
  .tab_type2{
    width: 60px;
    padding: 0 10px;
    font-size: 12px;
    color: #4a88f7;
    border: 1px solid #d9ecff;
    background: #ecf5ff;
    display: inline-block;
    border-radius: 5px;
  }
  .tab_type3{
    width: 60px;
    padding: 0 10px;
    font-size: 12px;
    color: #f57878;
    border: 1px solid #feebeb;
    background: #fef0f0;
    border-radius: 5px;
    display: inline-block;
  }
  .font_color{
    color: #1787fb;
  }

</style>
