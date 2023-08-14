<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      label-width="200px"
      :inline-message="true"
    >
      <el-form-item label="显示状态" prop="is_display">
        <el-radio-group v-model="form.is_display">
          <el-radio
            v-for="(item, index) in options_display"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="认证状态" prop="audit_status">
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
      <el-form-item label="企业性质无法匹配时为" prop="nature">
        <el-select v-model="form.nature" placeholder="请选择企业性质">
          <el-option
            v-for="(item, index) in options_nature"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="公司规模无法匹配时为" prop="scale">
        <el-select v-model="form.scale" placeholder="请选择公司规模">
          <el-option
            v-for="(item, index) in options_scale"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="所属行业无法匹配时为" prop="trade">
        <el-select v-model="form.trade" placeholder="请选择所属行业">
          <el-option
            v-for="(item, index) in options_trade"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        label="注册资金无法匹配时为"
        prop="registered"
      >
        <el-input
          v-model="form.registered"
          placeholder="请输入注册资金"
          class="input-with-select"
        >
          <el-select
            slot="append"
            v-model="form.currency"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="万人民币" value="0" />
            <el-option label="万美元" value="1" />
          </el-select>
        </el-input>
      </el-form-item>
      <el-form-item label="所在地区无法匹配时为" prop="citycategory_arr">
        <el-cascader
          v-model="form.citycategory_arr"
          :options="options_citycategory"
          :show-all-levels="false"
        />
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
import { getSeting, saveCompanySeting } from '@/api/datacollection'

export default {
  data() {
    return {
      options_display: [
        {
          'id': 1,
          'name': '显示'
        },
        {
          'id': 0,
          'name': '不显示'
        }
      ],
      options_audit: [],
      options_nature: [],
      options_scale: [],
      options_trade: [],
      options_citycategory: [],
      form: {
        is_display: '',
        audit_status: '',
        nature: '',
        scale: '',
        trade: '',
        registered: '',
        currency: '0',
        citycategory_arr: ''
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
          'companyDisplay,companyAudit,companyNature,trade,citycategory,companyScale,jobTag,setmealList'
      }, 'get')
        .then(response => {
          this.options_audit = response.data.companyAudit
          this.options_nature = response.data.companyNature
          this.options_scale = response.data.companyScale
          this.options_trade = response.data.trade
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
        })
        .catch(() => {})
      getSeting({}, 'get')
        .then(response => {
          this.company_seting = response.data.company_seting
          if (Object.keys(this.company_seting).length) {
            this.form = this.company_seting
            this.form.currency = String(this.form.currency)
            this.form.citycategory_arr = []
            if (this.company_seting.district1 != 0) {
              this.form.citycategory_arr.push(this.company_seting.district1)
            }
            if (this.company_seting.district2 != 0) {
              this.form.citycategory_arr.push(this.company_seting.district2)
            }
            if (this.company_seting.district3 != 0) {
              this.form.citycategory_arr.push(this.company_seting.district3)
            }
          }
        })
        .catch(() => {})
    },
    onSubmit() {
      const insertData = {
        is_display: this.form.is_display,
        audit_status: this.form.audit_status,
        nature: this.form.nature,
        scale: this.form.scale,
        trade: this.form.trade,
        registered: this.form.registered,
        currency: this.form.currency,
        district1: this.form.citycategory_arr[0],
        district2: this.form.citycategory_arr[1],
        district3: this.form.citycategory_arr[2]
      }
      saveCompanySeting(insertData)
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
.el-form.common-form .input-sel {
  width: 120px;
}
</style>
