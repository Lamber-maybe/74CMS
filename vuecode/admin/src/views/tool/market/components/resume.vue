<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="注册时间" prop="reg_time">
        <el-select v-model="form.reg_time" placeholder="不限" class="large">
          <el-option label="不限" :value="-1" />
          <el-option label="3天内" :value="3" />
          <el-option label="7天内" :value="7" />
          <el-option label="15天内" :value="15" />
          <el-option label="30天内" :value="30" />
          <el-option label="90天内" :value="90" />
          <el-option label="180天内" :value="180" />
        </el-select>
      </el-form-item>
      <el-form-item label="登录时间" prop="login_time">
        <el-select v-model="form.login_time" placeholder="不限" class="large">
          <el-option label="不限" :value="-1" />
          <el-option label="3天内" :value="3" />
          <el-option label="7天内" :value="7" />
          <el-option label="15天内" :value="15" />
          <el-option label="30天内" :value="30" />
          <el-option label="90天内" :value="90" />
          <el-option label="180天内" :value="180" />
        </el-select>
      </el-form-item>
      <el-form-item label="简历照片" prop="photo">
        <el-radio-group v-model="form.photo">
          <el-radio :label="-1">不限</el-radio>
          <el-radio :label="0">无照片</el-radio>
          <el-radio :label="1">有照片</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="未完善项" prop="module">
        <el-select
          v-model="form.module"
          multiple
          placeholder="不限"
          class="large"
        >
          <el-option
            v-for="item in options_module"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="意向职位" prop="jobcategory">
        <el-cascader
          v-model="form.jobcategory"
          placeholder="不限"
          :options="options_jobcategory"
          :props="{ multiple: true, checkStrictly: true }"
          clearable
          class="large"
        />
      </el-form-item>
      <el-form-item label="意向地区" prop="district">
        <el-cascader
          v-model="form.district"
          placeholder="不限"
          :options="options_citycategory"
          :props="{ multiple: true, checkStrictly: true }"
          clearable
          class="large"
        />
      </el-form-item>
      <el-form-item label="最高学历" prop="education">
        <el-checkbox
          v-model="checkEducationAll"
          style="margin-right:20px;"
          @change="handleCheckAllEducation"
        >
          不限
        </el-checkbox>
        <el-checkbox-group
          v-model="form.education"
          @change="handleCheckedEducationChange"
        >
          <el-checkbox
            v-for="item in options_education"
            :key="item.id"
            :label="item.id"
          >
            {{ item.name }}
          </el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="工作经验" prop="experience">
        <el-checkbox
          v-model="checkExperienceAll"
          style="margin-right:20px;"
          @change="handleCheckAllExperience"
        >
          不限
        </el-checkbox>
        <el-checkbox-group
          v-model="form.experience"
          @change="handleCheckedExperienceChange"
        >
          <el-checkbox
            v-for="item in options_experience"
            :key="item.id"
            :label="item.id"
          >
            {{ item.name }}
          </el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="账号选项">
        <el-select v-model="form.auth_email" placeholder="不限绑定邮箱">
          <el-option label="不限绑定邮箱" :value="-1" />
          <el-option label="已绑定" :value="1" />
          <el-option label="未绑定" :value="0" />
        </el-select>
        <el-select v-model="form.auth_mobile" placeholder="不限认证手机">
          <el-option label="不限认证手机" :value="-1" />
          <el-option label="已认证" :value="1" />
          <el-option label="未认证" :value="0" />
        </el-select>
        <el-select v-model="form.auth_weixin" placeholder="不限绑定微信">
          <el-option label="不限绑定微信" :value="-1" />
          <el-option label="已绑定" :value="1" />
          <el-option label="未绑定" :value="0" />
        </el-select>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
export default {
  data() {
    return {
      checkEducationAll: true,
      checkExperienceAll: true,
      options_jobcategory: [],
      options_citycategory: [],
      options_module: [],
      options_education: [],
      options_experience: [],
      form: {
        reg_time: -1,
        login_time: -1,
        photo: -1,
        module: [],
        jobcategory: [],
        district: [],
        education: [],
        experience: [],
        auth_email: -1,
        auth_mobile: -1,
        auth_weixin: -1
      },
      rules: {
        input: [
          {
            required: true,
            message: '请选择优惠券',
            trigger: 'blur'
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
      getClassify({
        type: 'education,experience,jobcategory,citycategory,resumeModule'
      })
        .then(response => {
          this.options_education = response.data.education
          this.options_experience = response.data.experience
          this.options_jobcategory = response.data.jobcategory
          this.options_citycategory = response.data.citycategory
          this.options_module = response.data.resumeModule
        })
        .catch(() => {})
    },
    handleCheckAllEducation(val) {
      if (val) {
        this.form.education = []
      } else {
        if (this.form.education.length == 0) {
          return false
        }
      }
    },
    handleCheckedEducationChange(value) {
      const checkedCount = value.length
      if (checkedCount > 0) {
        this.checkEducationAll = false
      } else {
        this.checkEducationAll = true
      }
    },
    handleCheckAllExperience(val) {
      if (val) {
        this.form.experience = []
      } else {
        if (this.form.experience.length == 0) {
          return false
        }
      }
    },
    handleCheckedExperienceChange(value) {
      const checkedCount = value.length
      if (checkedCount > 0) {
        this.checkExperienceAll = false
      } else {
        this.checkExperienceAll = true
      }
    }
  }
}
</script>
<style scoped>
.large {
  width: 608px;
}
.el-checkbox-group {
  display: inline;
}
</style>
