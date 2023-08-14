<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>页面管理</span>
      </div>
      <el-form
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="120px"
        :inline-message="true"
      >
        <el-form-item label="路由规则">
          <el-radio-group v-model="form.route_rule">
            <el-radio label="def">默认</el-radio>
            <el-radio label="qishi_6_0_min">骑士6.0极简</el-radio>
            <el-radio label="qishi_6_0_pathinfo">骑士6.0默认</el-radio>
            <el-radio label="qishi_3_7">骑士3.7</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        </el-form-item>
      </el-form>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="页面名称" prop="name" width="200" />
        <el-table-column label="seo标题" prop="seo_title" />
        <el-table-column label="缓存时长" width="200">
          <template slot-scope="scope">
            <span v-if="scope.row.enable_cache==1">{{ scope.row.expire==0?'不缓存':scope.row.expire+'秒' }}</span>
            <span v-else style="font-style:italic;color:#d3d3d3;font-size:13px;">不可缓存</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
              编辑
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
import { setConfig, clearCache } from '@/api/configuration'
import { pageList } from '@/api/page'

export default {
  data() {
    return {
      list: null,
      listLoading: true,
      infoLoading: true,
      form: {
        route_rule: 'def'
      }
    }
  },
  created() {
    this.fetchInfo()
    this.fetchData()
  },
  methods: {
    clearCache() {
      clearCache({}).then(response => {
        if (response.code == 200) {
          this.$store.dispatch('config/getConfigInfo')
        }
      })
    },
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            route_rule
          } = { ...response.data }
          this.form = {
            route_rule
          }
          console.log(this.form)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    fetchData() {
      this.listLoading = true
      pageList({}).then(response => {
        this.list = response.data
        this.listLoading = false
      })
    },
    funEdit(index, row) {
      this.$router.push({
        path: '/sys/basic/page/edit',
        query: {
          id: row.id
        }
      })
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.clearCache()
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
