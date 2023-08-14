<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      label-width="200px"
      :inline-message="true"
    >
      <el-form-item label="招聘状态" prop="recruit_status">
        <el-radio-group v-model="form.recruit_status">
          <el-radio
            v-for="(item, index) in options_display"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="审核状态" prop="audit_status">
        <el-radio-group v-model="form.audit_status">
          <el-radio
            v-for="(item, index) in options_audit"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="职位性质无法匹配时为" prop="nature">
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
      <el-form-item label="职位类别无法匹配时为" prop="jobcategory_arr">
        <el-cascader
          v-model="form.jobcategory_arr"
          :options="options_jobcategory"
          :show-all-levels="false"
          filterable
        />
      </el-form-item>
      <el-form-item label="学历要求无法匹配时为" prop="education">
        <el-select v-model="form.education" placeholder="请选择学历要求">
          <el-option label="不限学历" :value="0" />
          <el-option
            v-for="(item, index) in options_education"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="工作经验无法匹配时为" prop="experience">
        <el-select v-model="form.experience" placeholder="请选择工作经验">
          <el-option label="不限经验" :value="0" />
          <el-option
            v-for="(item, index) in options_experience"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        label="招聘人数无法匹配时为"
        prop="recruit_num"
      >
        <el-input v-model.number="form.recruit_num" />
        <span class="smalltip">
          <i class="el-icon-info" />
          0表示若干
        </span>
      </el-form-item>
      <el-form-item label="薪资待遇无法匹配时为" prop="minwage">
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
        <el-select
          v-model="form.maxwage"
          placeholder="请选择"
          class="wage-sel"
        >
          <el-option
            v-for="(item, index) in options_maxwage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="工作地区无法匹配时为" prop="citycategory_arr">
        <el-cascader
          v-model="form.citycategory_arr"
          :options="options_citycategory"
          :show-all-levels="false"
        />
      </el-form-item>
      <el-form-item label="岗位福利无法匹配时为" prop="welfare">
        <el-select
          v-model="form.welfare"
          multiple
          filterable
          allow-create
          default-first-option
          placeholder="请选择岗位福利"
          :multiple-limit="6"
          class="tag-input"
        >
          <el-option
            v-for="(item, index) in options_tag"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        label="年龄要求无法匹配时为"
        prop="minwage"
      >
        <el-select
          v-model="form.minage"
          :disabled="form.age_na === true"
          placeholder="请选择"
          class="age-sel"
          @change="minage_change"
        >
          <el-option
            v-for="(item, index) in options_minage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        -
        <el-select
          v-model="form.maxage"
          :disabled="form.age_na === true"
          placeholder="请选择"
          class="age-sel"
        >
          <el-option
            v-for="(item, index) in options_maxage"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-checkbox
          v-model="form.age_na"
          style="margin-left:10px;"
        >不限
        </el-checkbox>
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          @click="onSubmit"
        >
          保存
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import { getSeting, saveJobSeting } from '@/api/datacollection'

var age_data = []

for (let i = 16; i <= 65; i++) {
  age_data.push(i)
}

var wage_data = []
let current = 0
let unit1 = 500
let unit2 = 5000
for (let i = 0; i < 37; i++) {
  if(current<15000){
    current += unit1
  }else{
    current += unit2
  }
  wage_data.push(current)
}

export default {
  data() {
    return {
      options_display: [],
      options_audit: [],
      options_nature: [],
      options_jobcategory: [],
      options_education: [],
      options_experience: [],
      options_minwage: [],
      options_maxwage: [],
      options_minage: [],
      options_maxage: [],
      options_citycategory: [],
      options_tag: [],
      form: {
        recruit_status: 1,
        audit_status: 0,
        nature: 1,
        jobcategory_arr: '',
        education: '',
        experience: '',
        recruit_num: '',
        citycategory_arr: '',
        welfare: '',
        minwage: '',
        maxwage: '',
        minage: '',
        maxage: '',
        age_na: false
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      getClassify({
        type:
          'jobDisplay,jobAudit,jobNature,jobcategory,citycategory,jobTag,education,experience'
      }, 'get')
        .then(response => {
          this.options_display = response.data.jobDisplay
          this.options_audit = response.data.jobAudit
          this.options_nature = response.data.jobNature
          this.options_jobcategory = response.data.jobcategory
          this.options_education = response.data.education
          this.options_experience = response.data.experience
          for (let index = 0; index < wage_data.length; index++) {
            const tmp_json = {
              id: wage_data[index],
              name: wage_data[index] + '元/月'
            }
            this.options_minwage.push(tmp_json)
          }
          this.options_citycategory = response.data.citycategory
          this.options_citycategory = this.options_citycategory.map((item) => {
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
          this.options_tag = response.data.jobTag
          for (let index = 0; index < age_data.length; index++) {
            const tmp_json = {
              id: age_data[index],
              name: age_data[index] + '岁'
            }
            this.options_minage.push(tmp_json)
          }
        })
        .catch(() => {})
      getSeting({}, 'get')
        .then(response => {
          this.job_seting = response.data.job_seting
          if (Object.keys(this.job_seting).length) {
            this.form = this.job_seting
            this.form.jobcategory_arr = []
            this.form.citycategory_arr = []
            if (this.job_seting.category1 != 0) {
              this.form.jobcategory_arr.push(this.job_seting.category1)
            }
            if (this.job_seting.category2 != 0) {
              this.form.jobcategory_arr.push(this.job_seting.category2)
            }
            if (this.job_seting.category3 != 0) {
              this.form.jobcategory_arr.push(this.job_seting.category3)
            }
            if (this.job_seting.district1 != 0) {
              this.form.citycategory_arr.push(this.job_seting.district1)
            }
            if (this.job_seting.district2 != 0) {
              this.form.citycategory_arr.push(this.job_seting.district2)
            }
            if (this.job_seting.district3 != 0) {
              this.form.citycategory_arr.push(this.job_seting.district3)
            }
            const arrData = this.form.welfare.split(',')
            for (var i = 0; i < arrData.length; i++) {
              if (!isNaN(arrData[i])) {
                arrData[i] = parseInt(arrData[i])
              }
            }
            this.form.welfare = arrData
            if (this.form.minwage) {
              this.handle_maxwage(this.form.minwage)
            }
            if (this.form.minage) {
              this.handle_maxage(this.form.minage)
            }
            this.form.age_na = this.form.age_na == 1
          }
        })
        .catch(() => {})
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
    },
    minage_change(e) {
      this.handle_maxage(e)
    },
    handle_maxage(minage) {
      if (!minage) {
        return false
      }
      this.options_maxage = []
      const startindex = age_data.indexOf(minage) + 1
      if (minage >= this.form.maxage) {
        this.form.maxage = age_data[startindex]
      }
      for (let index = startindex; index < age_data.length; index++) {
        const tmp_json = {
          id: age_data[index],
          name: age_data[index] + '岁'
        }
        this.options_maxage.push(tmp_json)
      }
    },
    onSubmit() {
      const insertData = {
        recruit_status: this.form.recruit_status,
        audit_status: this.form.audit_status,
        nature: this.form.nature,
        category1: this.form.jobcategory_arr[0],
        category2: this.form.jobcategory_arr[1],
        category3: this.form.jobcategory_arr[2],
        experience: this.form.experience,
        education: this.form.education,
        recruit_num: this.form.recruit_num,
        minwage: this.form.minwage,
        maxwage: this.form.maxwage,
        district1: this.form.citycategory_arr[0],
        district2: this.form.citycategory_arr[1],
        district3: this.form.citycategory_arr[2],
        welfare: this.form.welfare.toString(),
        minage: this.form.minage,
        maxage: this.form.maxage,
        age_na: this.form.age_na == true ? 1 : 0
      }
      saveJobSeting(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>

<style scoped>
.el-input,
.el-select,
.el-cascader {
  width: 300px;
}
.el-select.tag-input {
  width: 550px;
}
</style>
