<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="职位类别" prop="jobcategory">
        <el-cascader
          v-model="form.jobcategory"
          placeholder="不限"
          :options="options_jobcategory"
          :props="props_jobcategory"
          collapse-tags
          clearable
          class="large"
        />
      </el-form-item>
      <el-form-item label="目标地区" prop="district">
        <el-cascader
          v-model="form.district"
          placeholder="不限"
          :options="options_citycategory"
          :props="props_citycategory"
          collapse-tags
          clearable
          class="large"
        />
      </el-form-item>
      <el-form-item label="目标行业" prop="trade">
        <el-select
          v-model="form.trade"
          multiple
          placeholder="不限"
          class="large"
        >
          <el-option
            v-for="item in options_trade"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="职位亮点" prop="tag">
        <el-select v-model="form.tag" multiple placeholder="不限" class="large">
          <el-option
            v-for="item in options_tag"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="刷新时间" prop="refreshtime">
        <el-radio-group v-model="form.refreshtime">
          <el-radio :label="-1">
            不限
          </el-radio>
          <el-radio :label="1">
            今天
          </el-radio>
          <el-radio :label="7">
            7天内
          </el-radio>
          <el-radio :label="15">
            15天内
          </el-radio>
          <el-radio :label="30">
            30天内
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="薪资待遇" prop="wage">
        <el-checkbox
          v-model="checkWageAll"
          style="margin-right:20px;"
          @change="handleCheckAllWage"
        >
          不限
        </el-checkbox>
        <el-checkbox-group
          v-model="form.wage"
          @change="handleCheckedWageChange"
        >
          <el-checkbox
            v-for="item in options_wage"
            :key="item.id"
            :label="item.id"
          >
            {{ item.name }}
          </el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="企业套餐" prop="setmeal_id">
        <el-checkbox
          v-model="checkSetmealAll"
          style="margin-right:20px;"
          @change="handleCheckAllSetmeal"
        >
          不限
        </el-checkbox>
        <el-checkbox-group
          v-model="form.setmeal_id"
          @change="handleCheckedSetmealChange"
        >
          <el-checkbox
            v-for="item in options_setmeal"
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
          <el-radio label="stick">
            仅置顶职位
          </el-radio>
          <el-radio label="emergency">
            仅紧急职位
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
  name: 'Joblist',
  data() {
    return {
      checkWageAll: true,
      checkSetmealAll: true,
      props_jobcategory: { multiple: true },
      options_jobcategory: [],
      props_citycategory: { multiple: true },
      options_citycategory: [],
      options_trade: [],
      options_tag: [],
      options_wage: [],
      options_setmeal: [],
      form: {
        jobcategory: [],
        district: [],
        trade: [],
        tag: [],
        wage: [],
        setmeal_id: [],
        content: 'refreshtime',
        refreshtime: -1,
        num: 10
      },
      rules: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      getClassify({
        type: 'jobTag,jobcategory,citycategory,trade,setmealList,wage'
      })
        .then(response => {
          this.options_tag = response.data.jobTag
          this.options_jobcategory = response.data.jobcategory
          this.options_citycategory = response.data.citycategory
          this.options_trade = response.data.trade
          this.options_setmeal = response.data.setmealList
          this.options_wage = response.data.wage
        })
        .catch(() => {})
    },
    handleCheckAllWage(val) {
      if (val) {
        this.form.wage = []
      } else {
        if (this.form.wage.length == 0) {
          return false
        }
      }
    },
    handleCheckedWageChange(value) {
      const checkedCount = value.length
      if (checkedCount > 0) {
        this.checkWageAll = false
      } else {
        this.checkWageAll = true
      }
    },
    handleCheckAllSetmeal(val) {
      if (val) {
        this.form.setmeal_id = []
      } else {
        if (this.form.setmeal_id.length == 0) {
          return false
        }
      }
    },
    handleCheckedSetmealChange(value) {
      const checkedCount = value.length
      if (checkedCount > 0) {
        this.checkSetmealAll = false
      } else {
        this.checkSetmealAll = true
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
