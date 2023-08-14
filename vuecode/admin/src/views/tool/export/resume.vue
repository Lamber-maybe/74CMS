<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>简历信息导出</span>
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
        <el-form-item label="性别">
          <el-select
            v-model="form.sex"
            placeholder="不限"
          >
            <el-option
              label="不限"
              value=""
            />
            <el-option
              label="男"
              value="1"
            />
            <el-option
              label="女"
              value="2"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="学历">
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
        <el-form-item label="工作经验">
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
import { exportResume } from '@/api/export'

export default {
  data() {
    return {
      waiting: false,
      infoLoading: false,
      options_education: [],
      options_experience: [],
      form: {
        daterange: [],
        sex: '',
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
      exportResume(insertData)
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
          '简历ID',
          '姓名',
          '性别',
          '年龄',
          '现居住地',
          '身高',
          '婚姻状况',
          '学历',
          '工作经验',
          '户籍',
          '专业',
          '身份证号',
          '求职状态',
          '意向行业',
          '意向职位',
          '意向薪资',
          '意向工作地区',
          '意向工作性质',
          '联系手机',
          '联系微信',
          '联系qq',
          '联系邮箱',
          '注册时间',
          '刷新时间'
        ] // 上面设置Excel的表格第一行的标题
        const filterVal = [
          'number',
          'id',
          'fullname',
          'sex',
          'age',
          'residence',
          'height',
          'marriage',
          'education',
          'experience',
          'householdaddress',
          'major',
          'idcard',
          'current',
          'intention_trade',
          'intention_jobs',
          'intention_wage',
          'intention_district',
          'intention_nature',
          'mobile',
          'weixin',
          'qq',
          'email',
          'addtime',
          'refreshtime'
        ]
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '简历导出(开始位置：' +
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
