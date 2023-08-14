<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="企业性质" prop="nature">
        <el-checkbox
          v-model="checkNatureAll"
          style="margin-right:20px;"
          @change="handleCheckAllNature"
        >
          不限
        </el-checkbox>
        <el-checkbox-group
          v-model="form.nature"
          @change="handleCheckedNatureChange"
        >
          <el-checkbox
            v-for="item in options_nature"
            :key="item.id"
            :label="item.id"
          >
            {{ item.name }}
          </el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="所属行业" prop="trade">
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
      <el-form-item label="所在地区" prop="district">
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
      <el-form-item label="企业福利" prop="tag">
        <el-select v-model="form.tag" multiple placeholder="不限" class="large">
          <el-option
            v-for="item in options_tag"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="企业排序" prop="time_order">
        <el-select v-model="form.time_order" placeholder="不限" class="large">
          <el-option label="企业刷新时间" value="refresh_time"></el-option>
          <el-option label="企业注册时间" value="register_time"></el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="会员套餐" prop="setmeal_id">
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
          <el-radio label="all">
            不限
          </el-radio>
          <el-radio label="filter_nojobs">
            过滤无职位企业
          </el-radio>
          <el-radio label="filter_noaudit">
            过滤未认证企业
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="职位限制">
        <el-radio-group v-model="form.job_limit" class="flex">
          <el-radio :label="0">
            不限
          </el-radio>
          <el-radio :label="1">
            1条
          </el-radio>
          <el-radio :label="2">
            2条
          </el-radio>
          <el-radio :label="3">
            3条
          </el-radio>
          <el-input-number v-model="form.condition" :min="0" style="width: 200px"></el-input-number>
        </el-radio-group>
        <span style="color: #999999"><i class="el-icon-warning-outline" style="color: #409EFF"></i> 每家企业最多展示多少职位</span>
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
      checkNatureAll: true,
      checkSetmealAll: true,
      props_citycategory: { multiple: true,checkStrictly: true },
      options_citycategory: [],
      options_trade: [],
      options_nature: [],
      options_tag: [],
      options_setmeal: [],
      form: {
        nature: [],
        trade: [],
        district: [],
        tag: [],
        setmeal_id: [],
        content: 'all',
        num: 10,
        job_limit:0,
        time_order:""
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
        type: 'companyNature,jobTag,citycategory,trade,setmealList'
      })
        .then(response => {
          this.options_nature = response.data.companyNature
          this.options_trade = response.data.trade
          this.options_tag = response.data.jobTag
          this.options_citycategory = response.data.citycategory
          this.options_setmeal = response.data.setmealList
        })
        .catch(() => {})
    },
    handleCheckAllNature(val) {
      if (val) {
        this.form.nature = []
      } else {
        if (this.form.nature.length == 0) {
          return false
        }
      }
    },
    handleCheckedNatureChange(value) {
      const checkedCount = value.length
      if (checkedCount > 0) {
        this.checkNatureAll = false
      } else {
        this.checkNatureAll = true
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
  width: 450px;
}
.el-checkbox-group {
  display: inline;
}
  .flex{
    display: flex;
    flex-direction: row;
    align-items: center;
  }
</style>
