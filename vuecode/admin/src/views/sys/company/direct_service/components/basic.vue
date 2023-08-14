<template>
  <div class="app-container">
    <el-divider content-position="left">下载单份简历</el-divider>
    <el-form
      class="common-form"
      label-width="200px"
      style="margin-bottom:80px;"
    >
      <el-form-item label="单份简历下载" prop="single_resume_download_open">
        <el-switch v-model="single_resume_download_open" />
      </el-form-item>
      <el-form-item label="普通简历单价">
        <!-- <el-input
          type="number"
          v-model.number="single_resume_download_expense"
          class="small"
          min="0"
          @blur="format_number(0, 'single_resume_download_expense')"
        >
          <template slot="append"
            >元</template
          >
        </el-input> -->
        <el-table
          border
          :data="single_resume_download_expense_conf"
          size="mini"
        >
          <el-table-column label="刷新时间" prop="name" />
          <el-table-column align="center" label="单价">
            <template slot-scope="scope">
              <el-input
                v-model.number="scope.row.value"
                type="number"
                class="small"
                min="0"
                size="mini"
              ><template
                slot="append"
              >元</template>
              </el-input>
            </template>
          </el-table-column>
        </el-table>
      </el-form-item>
      <el-form-item
        label="优质简历单价"
        prop="single_resume_download_expense_talent"
      >
        <el-input
          v-model.number="single_resume_download_expense_talent"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'single_resume_download_expense_talent')"
        >
          <template
            slot="append"
          >元</template>
        </el-input>
      </el-form-item>
      <el-form-item
        :label="'允许' + $store.state.config.points_byname + '全抵'"
        prop="single_resume_download_enable_points_deduct"
      >
        <el-switch v-model="single_resume_download_enable_points_deduct" />
      </el-form-item>
      <el-form-item
        :label="'所需' + $store.state.config.points_byname"
        prop="single_resume_download_deduce_points"
      >
        <el-table border :data="single_resume_download_points_conf" size="mini">
          <el-table-column label="刷新时间" prop="name" />
          <el-table-column
            align="center"
            :label="'所需' + $store.state.config.points_byname"
          >
            <template slot-scope="scope">
              <el-input
                v-model.number="scope.row.value"
                type="number"
                class="small"
                min="0"
                size="mini"
              ><template slot="append">{{
                $store.state.config.points_quantifier
              }}</template>
              </el-input>
            </template>
          </el-table-column>
        </el-table>
      </el-form-item>
      <el-form-item
        :label="'优质简历所需' + $store.state.config.points_byname"
        prop="single_resume_download_points_talent"
      >
        <el-input
          v-model.number="single_resume_download_points_talent"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'single_resume_download_points_talent')"
        >
          <template slot="append">{{
            $store.state.config.points_quantifier
          }}</template>
        </el-input>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form1')">保存</el-button>
      </el-form-item>
    </el-form>
    <el-divider content-position="left">
      刷新职位
    </el-divider>
    <el-form class="common-form" label-width="200px">
      <el-form-item label="刷新职位单价" prop="single_job_refresh_expense">
        <el-input
          v-model.number="single_job_refresh_expense"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'single_job_refresh_expense')"
        >
          <template
            slot="append"
          >元</template>
        </el-input>
      </el-form-item>
      <el-form-item
        label="允许积分全抵"
        prop="single_job_refresh_enable_points_deduct"
      >
        <el-switch v-model="single_job_refresh_enable_points_deduct" />
      </el-form-item>
      <el-form-item
        :label="'所需' + $store.state.config.points_byname"
        prop="single_job_refresh_deduce_points"
      >
        <el-input
          v-model.number="single_job_refresh_deduce_points"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'single_job_refresh_deduce_points')"
        >
          <template slot="append">{{
            $store.state.config.points_quantifier
          }}</template>
        </el-input>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form2')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      single_resume_download_open: false,
      single_resume_download_expense_conf: [],
      single_resume_download_enable_points_deduct: false,
      single_resume_download_points_conf: [],
      single_resume_download_expense_talent: 0,
      single_resume_download_points_talent: 0,
      single_job_refresh_expense: 0,
      single_job_refresh_enable_points_deduct: false,
      single_job_refresh_deduce_points: 0
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    format_number(default_val, field) {
      if (this[field] == '' || parseInt(this[field]) < default_val) {
        this[field] = default_val
      }
    },
    fetchInfo() {
      this.infoLoading = true
      setConfig({}, 'get')
        .then(response => {
          this.single_resume_download_open =
            response.data.single_resume_download_open == 1
          this.single_resume_download_expense_conf =
            response.data.single_resume_download_expense_conf
          this.single_resume_download_enable_points_deduct =
            response.data.single_resume_download_enable_points_deduct == 1
          this.single_resume_download_points_conf =
            response.data.single_resume_download_points_conf
          this.single_job_refresh_expense =
            response.data.single_job_refresh_expense
          this.single_job_refresh_enable_points_deduct =
            response.data.single_job_refresh_enable_points_deduct == 1
          this.single_job_refresh_deduce_points =
            response.data.single_job_refresh_deduce_points
          this.single_resume_download_expense_talent =
            response.data.single_resume_download_expense_talent
          this.single_resume_download_points_talent =
            response.data.single_resume_download_points_talent
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      let insertData = {}
      if (formName == 'form1') {
        insertData = {
          single_resume_download_open:
            this.single_resume_download_open === true ? 1 : 0,
          single_resume_download_expense_conf: this
            .single_resume_download_expense_conf,
          single_resume_download_enable_points_deduct:
            this.single_resume_download_enable_points_deduct === true ? 1 : 0,
          single_resume_download_points_conf: this
            .single_resume_download_points_conf,
          single_resume_download_expense_talent: this
            .single_resume_download_expense_talent,
          single_resume_download_points_talent: this
            .single_resume_download_points_talent
        }
      } else {
        insertData = {
          single_job_refresh_expense: this.single_job_refresh_expense,
          single_job_refresh_enable_points_deduct:
            this.single_job_refresh_enable_points_deduct === true ? 1 : 0,
          single_job_refresh_deduce_points: this
            .single_job_refresh_deduce_points
        }
      }

      setConfig(insertData)
        .then(response => {
          this.$store.dispatch('config/getConfigInfo')
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped>
.small {
  width: 240px;
}
.el-icon-info {
  color: #999;
  margin-left: 6px;
}
.el-divider {
  background: none;
}
</style>
