<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="6">
        <el-card>
          <div class="total_tit">个人增值服务总览</div>
          <div class="total_detail">
            历史成交金额：￥{{ total.per_service }}
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card>
          <div class="total_tit">企业开通套餐总览</div>
          <div class="total_detail">
            历史成交金额：￥{{ total.com_setmeal }}
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card>
          <div class="total_tit">企业推广服务总览</div>
          <div class="total_detail">
            历史成交金额：￥{{ total.com_promotion }}
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card>
          <div class="total_tit">企业增值服务总览</div>
          <div class="total_detail">
            历史成交金额：￥{{ total.com_service }}
          </div>
        </el-card>
      </el-col>
    </el-row>
    <el-row style="margin-top:20px;">
      <per_order
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
    <el-row style="margin-top:20px;">
      <com_order
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
    <el-row style="margin-top:20px;">
      <pay_type height="300px" :platform-options="platformOptions" />
    </el-row>
    <el-row style="margin-top:20px;">
      <order_pay
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
    <el-row style="margin-top:20px;">
      <pay_setmeal
        height="300px"
        :platform-options="platformOptions"
      />
    </el-row>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { orderTotal } from '@/api/stat'
import per_order from './components/per_order.vue'
import com_order from './components/com_order.vue'
import pay_type from './components/pay_type.vue'
import order_pay from './components/order_pay.vue'
import pay_setmeal from './components/pay_setmeal.vue'

export default {
  components: {
    per_order,
    com_order,
    pay_type,
    order_pay,
    pay_setmeal
  },
  data() {
    return {
      platformOptions: [],
      total: {
        per_service: '',
        com_setmeal: '',
        com_promotion: '',
        com_service: ''
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      orderTotal({}).then(response => {
        this.total = { ...response.data }
      })
      const params = {
        type: 'platform'
      }
      getClassify(params).then(response => {
        this.platformOptions = [...response.data]
      })
    }
  }
}
</script>
<style scoped>
.total_tit {
  font-size: 18px;
}
.total_detail {
  margin-top: 12px;
  font-size: 14px;
}
</style>
