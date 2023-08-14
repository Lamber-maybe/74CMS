<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="工作性质" prop="nature">
        <el-radio-group v-model="form.nature">
          <el-radio
            v-for="(item, index) in options_nature"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="意向职位" prop="jobcategory_arr">
        <el-cascader
          v-model="form.jobcategory_arr"
          :options="options_jobcategory"
          :show-all-levels="false"
        />
      </el-form-item>
      <el-form-item label="工作地区" prop="citycategory_arr">
        <el-cascader
          v-model="form.citycategory_arr"
          :options="options_citycategory"
          :show-all-levels="false"
          :props="{ checkStrictly: true }"
        />
      </el-form-item>
      <el-form-item label="期望薪资" prop="minwage">
        <el-select
          v-model="form.minwage"
          placeholder="请选择"
          class="wage-sel"
          @change="minwage_change"
        >
          <el-option
            v-for="(item, index) in options_minwage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        -
        <el-select v-model="form.maxwage" placeholder="请选择" class="wage-sel">
          <el-option
            v-for="(item, index) in options_maxwage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        v-if="live_fields.trade === true"
        label="期望行业"
        prop="trade"
      >
        <el-select v-model="form.trade" placeholder="请选择">
          <el-option
            v-for="(item, index) in options_trade"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label=" ">
        <el-button
          type="primary"
          :loading="submitLoading"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { getClassify } from '@/api/classify'
import { getFieldRule } from '@/api/configuration'
import { resumeIntentionAddAndEdit } from '@/api/resume'

var wage_data = []
// 【ID1000220】【bug】后台添加简历薪资范围不一致
let current = 0
const unit1 = 500
const unit2 = 5000
for (let i = 0; i < 37; i++) {
  if (current < 15000) {
    current += unit1
  } else {
    current += unit2
  }
  wage_data.push(current)
}
export default {
  props: ['itemId','rid'],
  data() {
    return {
      submitLoading: false,
      live_fields: {
        trade: true
      },
      infoLoading: false,
      options_nature: [],
      options_jobcategory: [],
      options_citycategory: [],
      options_minwage: [],
      options_maxwage: [],
      options_trade: [],
      form: {
        id: 0,
        rid: 0,
        nature: 1,
        jobcategory_arr: [],
        citycategory_arr: [],
        category1: 0,
        category2: 0,
        category3: 0,
        district1: 0,
        district2: 0,
        district3: 0,
        minwage: '',
        maxwage: '',
        trade: ''
      },
      rules: {
        nature: [
          {
            required: true,
            message: '请选择工作性质',
            trigger: 'change'
          }
        ],
        jobcategory_arr: [
          {
            required: true,
            message: '请选择意向职位',
            trigger: 'change'
          }
        ],
        citycategory_arr: [
          {
            required: true,
            message: '请选择工作地区',
            trigger: 'change'
          }
        ],
        minwage: [
          {
            required: true,
            message: '请选择期望薪资',
            trigger: 'change'
          }
        ],
        trade: [
          {
            required: true,
            message: '请选择期望行业',
            trigger: 'change'
          }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      getFieldRule({}, 'get')
        .then(response => {
          const extra_rule = response.data.ResumeIntention
          if (extra_rule.trade.is_display == 0) {
            this.live_fields.trade = false
            this.rules.trade = []
          } else if (extra_rule.trade.is_require == 0) {
            this.rules.trade = []
          }
          return getClassify({
            type: 'resumeNature,jobcategory,citycategory,trade'
          })
        })
        .then(response => {
          this.options_nature = [...response.data.resumeNature]
          this.options_jobcategory = [...response.data.jobcategory]
          this.options_jobcategory = this.options_jobcategory.map((item) => {
            if (item.children.length) {
              let level2Array = item.children
              level2Array = level2Array.map((level2Item) => {
                if (level2Item.children.length) {
                  return { label: level2Item.label, value: level2Item.value, children: level2Item.children }
                } else {
                  return { label: level2Item.label, value: level2Item.value }
                }
              })
              return { value: item.value, label: item.label, children: level2Array }
            } else {
              return { value: item.value, label: item.label }
            }
          })
          // 【ID1000188】【优化】后台修改简历意向地区时，二级分类地区后为空 zdq 2022.07.06
          var options_citycategory = [...response.data.citycategory]
          for (let i = 0; i <= options_citycategory.length - 1; i++){
            if (options_citycategory[i]['children'].length <= 0){
              delete options_citycategory[i]['children']
            } else {
              for (let a = 0; a <= options_citycategory[i]['children'].length - 1; a++){
                if (options_citycategory[i]['children'][a]['children'].length <= 0){
                  delete options_citycategory[i]['children'][a]['children']
                }
              }
            }
          }
          this.options_citycategory = options_citycategory
          this.options_trade = [...response.data.trade]
          for (let index = 0; index < wage_data.length; index++) {
            const tmp_json = {
              id: wage_data[index],
              name: wage_data[index] + '元/月'
            }
            this.options_minwage.push(tmp_json)
          }
          // 【ID1000220】【bug】后台添加简历薪资范围不一致
          const param = {
            id: this.itemId
          }
          return resumeIntentionAddAndEdit(param, 'get')
        })
        .then(response => {
          if (response.data.info != null) {
            this.form = { ...response.data.info }
            this.form.jobcategory_arr = []
            this.form.citycategory_arr = []

            if (this.form.category1 != 0) {
              this.form.jobcategory_arr.push(this.form.category1)
            }
            if (this.form.category2 != 0) {
              this.form.jobcategory_arr.push(this.form.category2)
            }
            if (this.form.category3 != 0) {
              this.form.jobcategory_arr.push(this.form.category3)
            }
            if (this.form.district1 != 0) {
              this.form.citycategory_arr.push(this.form.district1)
            }
            if (this.form.district2 != 0) {
              this.form.citycategory_arr.push(this.form.district2)
            }
            if (this.form.district3 != 0) {
              this.form.citycategory_arr.push(this.form.district3)
            }
          } else {
            this.form.rid = this.rid
          }
          if (this.form.minwage) {
            this.handle_maxwage(this.form.minwage)
          }
          if (this.form.trade == 0) {
            this.form.trade = ''
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const that = this
      const insertData = { ...that.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          const tmp_jobcategory_arr = this.form.jobcategory_arr
          insertData.category1 = tmp_jobcategory_arr[0]
          insertData.category2 =
            tmp_jobcategory_arr[1] !== undefined ? tmp_jobcategory_arr[1] : 0
          insertData.category3 =
            tmp_jobcategory_arr[2] !== undefined ? tmp_jobcategory_arr[2] : 0
          const tmp_citycategory_arr = this.form.citycategory_arr
          insertData.district1 = tmp_citycategory_arr[0]
          insertData.district2 =
            tmp_citycategory_arr[1] !== undefined ? tmp_citycategory_arr[1] : 0
          insertData.district3 =
            tmp_citycategory_arr[2] !== undefined ? tmp_citycategory_arr[2] : 0

          resumeIntentionAddAndEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    },
    minwage_change(e) {
      this.handle_maxwage(e)
    },
    handle_maxwage(minwage) {
      if (!minwage) {
        return false
      }
      this.options_maxwage = []
      const startindex = wage_data.indexOf(minwage) + 1
      if (minwage >= this.form.maxwage) {
        this.form.maxwage = wage_data[startindex]
      }
      for (let index = startindex; index < wage_data.length; index++) {
        const tmp_json = {
          id: wage_data[index],
          name: wage_data[index] + '元/月'
        }
        this.options_maxwage.push(tmp_json)
      }
    }
  }
}
</script>
<style scoped>
.el-input {
  width: 160px;
}
.wage-sel {
  width: 130px;
}
.el-cascader {
  width: 272px;
}
.wage-sel .el-input,
.wage-sel .el-date-editor.el-input__inner {
  width: 130px;
}
</style>
