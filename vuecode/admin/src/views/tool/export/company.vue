<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>企业信息导出</span>
      </div>

      <el-form
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="180px"
      >
        <el-form-item label="注册时间">
          <el-date-picker
            v-model="form.daterange"
            type="daterange"
            value-format="yyyy-MM-dd"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
          />
        </el-form-item>
        <el-form-item label="企业认证">
          <el-select
            v-model="form.audit"
            placeholder="不限"
          >
            <el-option
              label="不限"
              value=""
            />
            <el-option
              v-for="(item, index) in options_audit"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="套餐类型">
          <el-select
            v-model="form.setmeal"
            placeholder="不限"
          >
            <el-option
              label="不限"
              value=""
            />
            <el-option
              v-for="(item, index) in options_setmeal"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="套餐到期">
          <el-select
            v-model="form.setmeal_overtime"
            placeholder="不限"
          >
            <el-option
              label="不限"
              value=""
            />
            <el-option
              v-for="(item, index) in options_setmeal_overtime"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="所属行业">
          <el-select
            v-model="form.trade"
            placeholder="不限"
          >
            <el-option
              label="不限"
              value=""
            />
            <el-option
              v-for="(item, index) in options_trade"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="导出条数">
          <el-select
            v-model="form.limit"
            placeholder="请选择导出条数"
          >
            <el-option
              label="100"
              value="100"
            />
            <el-option
              label="200"
              value="200"
            />
            <el-option
              label="500"
              value="500"
            />
            <el-option
              label="800"
              value="800"
            />
            <el-option
              label="1000"
              value="1000"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="开始位置">
          <el-input
            v-model.number="form.offset"
            type="number"
          />
        </el-form-item>
        <el-form-item label="">
          <el-button
            type="primary"
            :loading="waiting"
            @click="onSubmit('form')"
          >
            导出
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { exportCompany } from '@/api/export'

export default {
  data() {
    return {
      waiting: false,
      infoLoading: false,
      options_audit: [],
      options_setmeal: [],
      options_setmeal_overtime: [
        { id: '0', name: '已到期' },
        { id: '3', name: '三天内' },
        { id: '7', name: '一周内' },
        { id: '30', name: '一月内' },
        { id: '180', name: '半年内' }
      ],
      options_trade: [],
      form: {
        daterange: [],
        audit: '',
        setmeal: '',
        setmeal_overtime: '',
        trade: '',
        limit: '100',
        offset: 1
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.infoLoading = true
      getClassify({ type: 'companyAudit,setmealList,trade' })
        .then(response => {
          this.options_audit = [...response.data.companyAudit]
          this.options_setmeal = [...response.data.setmealList]
          this.options_trade = [...response.data.trade]
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      that.waiting = true
      const insertData = { ...that.form }
      exportCompany(insertData)
        .then(response => {
          if (response.code == 200) {
            that.exportExcel(response.data.items)
          } else {
            that.$message.error(response.message)
            that.waiting = false
          }
          return true
        })
        .catch(() => {
          that.waiting = false
        })
    },
    exportExcel(list) {
      require.ensure([], () => {
        const { export_json_to_excel } = require('@/excel/Export2Excel')
        const tHeader = [
          '序号',
          '企业ID',
          '企业名称',
          '企业简称',
          '企业性质',
          '所属行业',
          '所在地区',
          '企业规模',
          '注册资金',
          '联系人',
          '联系手机',
          '联系座机',
          '联系人微信',
          '联系人qq',
          '联系人邮箱',
          '审核状态',
          '注册时间',
          '刷新时间'
        ] // 上面设置Excel的表格第一行的标题
        const filterVal = [
          'number',
          'id',
          'companyname',
          'short_name',
          'nature',
          'trade',
          'district',
          'scale',
          'registered',
          'contact',
          'mobile',
          'telephone',
          'weixin',
          'qq',
          'email',
          'audit',
          'addtime',
          'refreshtime'
        ]
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '企业导出(开始位置：' +
            this.form.offset +
            '，最大条数' +
            this.form.limit +
            ')'
        ) // 最后一个是表名字
        this.waiting = false
      })
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]))
    }
  }
}
</script>

<style scoped>
.el-select,
.el-input {
  width: 350px;
}
</style>
