<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
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
      <el-form-item label="输出内容">
        <el-radio-group v-model="form.content">
          <el-radio label="refreshtime">
            刷新时间
          </el-radio>
          <el-radio label="promotion">
            推广优先
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="生成数量">
        <el-radio-group v-model="form.num">
          <el-radio :label="10">
            10条
          </el-radio>
          <el-radio :label="20">
            20条
          </el-radio>
          <el-radio :label="30">
            30条
          </el-radio>
          <el-radio :label="50">
            50条
          </el-radio>
          <el-radio :label="80">
            80条
          </el-radio>
        </el-radio-group>
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
        jobcategory: [],
        district: [],
        education: [],
        experience: [],
        content: 'refreshtime',
        num: 10
      },
      rules: {

      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      getClassify({
        type: 'education,experience,jobcategory,citycategory'
      })
        .then(response => {
          this.options_education = response.data.education
          this.options_experience = response.data.experience
          this.options_jobcategory = response.data.jobcategory
          this.options_citycategory = response.data.citycategory
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
