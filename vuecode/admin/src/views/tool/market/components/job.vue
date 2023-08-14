<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="刷新时间" prop="refreshtime">
        <el-select v-model="form.refreshtime" placeholder="不限" class="large">
          <el-option label="不限" :value="-1" />
          <el-option label="3天内" :value="3" />
          <el-option label="7天内" :value="7" />
          <el-option label="15天内" :value="15" />
          <el-option label="30天内" :value="30" />
          <el-option label="90天内" :value="90" />
          <el-option label="180天内" :value="180" />
        </el-select>
      </el-form-item>
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
      <el-form-item label="认证状态">
        <el-select v-model="form.auth_cominfo" placeholder="不限营业执照认证">
          <el-option label="不限营业执照认证" :value="-1" />
          <el-option label="已认证" :value="1" />
          <el-option label="未认证" :value="0" />
        </el-select>
        <el-select v-model="form.auth_report" placeholder="不限实地认证">
          <el-option label="不限实地认证" :value="-1" />
          <el-option label="已认证" :value="1" />
          <el-option label="未认证" :value="0" />
        </el-select>
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
      checkWageAll: true,
      checkSetmealAll: true,
      props_jobcategory: { multiple: true },
      options_jobcategory: [],
      options_trade: [],
      options_tag: [],
      options_wage: [],
      options_setmeal: [],
      form: {
        refreshtime: -1,
        jobcategory: [],
        trade: [],
        tag: [],
        wage: [],
        setmeal_id: [],
        auth_cominfo: -1,
        auth_report: -1,
        auth_email: -1,
        auth_mobile: -1,
        auth_weixin: -1
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
        type: 'jobTag,jobcategory,trade,setmealList,wage'
      })
        .then(response => {
          this.options_tag = response.data.jobTag
          this.options_jobcategory = response.data.jobcategory
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
