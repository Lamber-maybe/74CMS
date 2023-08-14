<template>
  <div class="app-container">
    <el-card class="box-card main">
      <div slot="header" class="clearfix">
        <span>新增参会企业</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/jobfairol/exhibitors/company/list')"
        >
          返回
        </el-button>
        <el-button
          style="float: right; padding: 0;"
          type="text"
          @click="onSubmit('form')"
          :disabled="issubmit"
        >
          保存
        </el-button>
      </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="500" valign="top">
            <el-form
              ref="form"
              class="common-form"
              label-width="120px"
              :inline-message="true"
            >
              <div class="searchKey">
                <el-form-item class="w400" label="企业名称">
                  <el-input v-model="companyname" @input="onCompany('companyname')" />
                </el-form-item>
                <el-form-item class="w400" label="或会员ID">
                  <el-input v-model="uid" @input="onCompany('uid')" />
                </el-form-item>
              </div>
              <el-form-item label="" prop="note" class="w460"></el-form-item>
              <el-form-item label="">
                <el-button type="primary" @click="onSubmit('form')" :disabled="issubmit">保存</el-button>
                <el-button @click="goto">返回</el-button>
              </el-form-item>
            </el-form>
          </td>
          <td valign="top">
            <el-table
              v-if="list.length>0"
              v-loading="listLoading"
              class="companyWrap"
              :data="list"
              element-loading-text="Loading"
              max-height="490"
              fit
              highlight-current-row
            >
              <el-table-column width="42">
                <template slot-scope="scope">
                  <label><input v-model="uid" type="radio" class="uid" name="uid" :value="scope.row.uid"></label>
                </template>
              </el-table-column>
              <el-table-column label="公司名称" show-overflow-tooltip>
                <template slot-scope="scope">
                  <el-link :href="scope.row.company_link" target="_blank" type="primary">
                    {{ scope.row.companyname }}
                  </el-link>
                </template>
              </el-table-column>
              <el-table-column align="center" prop="created_at" label="刷新时间" width="200">
                <template slot-scope="scope">
                  <i class="el-icon-time" />
                  <span>{{ scope.row.refreshtime | timeFilter }}</span>
                </template>
              </el-table-column>
              <el-table-column align="center" prop="created_at" label="添加时间" width="200">
                <template slot-scope="scope">
                  <i class="el-icon-time" />
                  <span>{{ scope.row.addtime | timeFilter }}</span>
                </template>
              </el-table-column>
            </el-table>
            <div v-if="list.length<=0" class="companyWrap companyNo">没有找到对应的公司。</div>
          </td>
        </tr></tbody>
      </table>
    </el-card>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import { companySearch, participateAdd } from '@/api/jobfairol'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      issubmit: false,
      companyname: '',
      uid: '',
      jobfair_id: '',
      key: '',
      type: '',
      list: [],
      listLoading: !1
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.jobfair_id = this.$route.query.id
  },
  methods: {
    onCompany: function(t){
      const param = {
        key: this[t],
        type: t
      }
      if (param.key === ''){
        this.list = []
      } else {
        this.listLoading = true
        companySearch(param).then(res => {
          this.list = res.data.items
          this.listLoading = false
        })
      }
    },
    onSubmit(formName) {
      const that = this
      that.issubmit = true
      if (!this.uid){
        this.$message.error('请选择企业')
        that.issubmit = false
        return !1
      }
      const insertData = {
        jobfair_id: this.jobfair_id,
        uid: this.uid,
        utype: 1
      }
      participateAdd(insertData).then(response => {
        this.$message.success(response.message)
        setTimeout(function() {
          that.goto()
        }, 1500)
        return true
      }).catch(() => {
        that.issubmit = false
        return false
      })
    },
    goto() {
      this.$router.push({
        path: '/jobfairol/exhibitors/company/list',
        query: {
          jobfair_id: this.jobfair_id
        }
      })
    }
  }
}
</script>
<style scoped>
.w400{width:400px}
.w460{width:460px}
.searchKey{background:#f5f7f8;padding:24px 0 2px;border:1px solid #dfdfdf;border-right:0}
.companyWrap{border:1px solid #dfdfdf}
.companyNo{padding:66px 20px;color:#0066CC}
</style>
