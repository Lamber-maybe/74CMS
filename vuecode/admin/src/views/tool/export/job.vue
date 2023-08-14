<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>职位信息导出</span>
      </div>

      <el-form
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="180px"
      >
        <el-form-item label="添加时间">
          <el-date-picker
            v-model="form.daterange"
            type="daterange"
            value-format="yyyy-MM-dd"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
          />
        </el-form-item>
        <el-form-item label="学历要求">
          <el-select
            v-model="form.education"
            placeholder="不限"
          >
            <el-option
              label="不限"
              value=""
            />
            <el-option
              v-for="(item, index) in options_education"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="工作经验要求">
          <el-select
            v-model="form.experience"
            placeholder="不限"
          >
            <el-option
              label="不限"
              value=""
            />
            <el-option
              v-for="(item, index) in options_experience"
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
import { exportJob } from '@/api/export'

export default {
  data() {
    return {
      waiting: false,
      infoLoading: false,
      options_education: [],
      options_experience: [],
      form: {
        daterange: [],
        education: '',
        experience: '',
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
      getClassify({ type: 'education,experience' })
        .then(response => {
          this.options_education = [...response.data.education]
          this.options_experience = [...response.data.experience]
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      that.waiting = true
      const insertData = { ...that.form }
      exportJob(insertData)
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
          '职位ID',
          '职位名称',
          '企业名称',
          '职位类别',
          '薪资待遇',
          '学历要求',
          '工作经验要求',
          '招聘人数',
          '所属部门',
          '年龄要求',
          '工作地区',
          '详细地址',
          '联系人',
          '联系手机',
          '联系座机',
          '联系人微信',
          '联系人qq',
          '联系人邮箱',
          '添加时间',
          '刷新时间',
          '审核状态',
          '招聘状态',
          '点击量'
        ] // 上面设置Excel的表格第一行的标题
        const filterVal = [
          'number',
          'id',
          'jobname',
          'companyname',
          'category',
          'wage',
          'education',
          'experience',
          'amount',
          'department',
          'age',
          'district',
          'address',
          'contact',
          'mobile',
          'telephone',
          'weixin',
          'qq',
          'email',
          'addtime',
          'refreshtime',
          'audit',
          'is_display',
          'click'
        ]
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '职位导出(开始位置：' +
            this.form.offset +
            '，最大条数' +
            this.form.limit +
            ')'
        )
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
