<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>在线客服</span>
      </div>
      <el-form
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        label-width="120px"
        :inline-message="true"
      >
        <el-form-item label="是否显示">
          <el-radio-group v-model="form.service_ol_open">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit()">保存</el-button>
        </el-form-item>
      </el-form>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="姓名" prop="name" width="200" />
        <el-table-column label="电话" prop="mobile" width="300" />
        <el-table-column label="微信" prop="weixin" width="300">
          <template slot-scope="scope">
            <el-popover
              placement="right"
              trigger="hover"
            >
              <img class="school_pop" :src="scope.row.weixin_url">
              <span slot="reference">
                <i class="el-icon-picture" />
              </span>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="QQ" prop="qq" />
        <el-table-column label="是否显示" prop="title">
          <template slot-scope="scope">
            <el-tag :type="scope.row.display | displayFilter">
              {{ scope.row.display == 1 ? '显示' : '隐藏' }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="排序" prop="sort" width="100" />
        <el-table-column fixed="right" label="操作" width="220">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
              编辑
            </el-button>
            <el-button
              size="small"
              type="danger"
              @click="funDelete(scope.$index, scope.row)"
            >
              删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="$router.push('/sys/basic/serviceol/add')">
            新增客服
          </el-button>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { serviceOlList, serviceOlDelete } from '@/api/serviceol'
import { setConfig } from '@/api/configuration'
export default {
  filters: {
    displayFilter(is_display) {
      const displayMap = {
        1: 'success',
        0: 'info'
      }
      return displayMap[is_display]
    }
  },
  data () {
    return {
      infoLoading: false,
      list: null,
      listLoading: false,
      form: {
        service_ol_open: 1
      }
    }
  },
  created() {
    this.fetchData()
    this.fetchInfo()
  },
  methods: {
    onSubmit () {
      const insertData = { ...this.form }
      setConfig(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    },
    fetchInfo () {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            service_ol_open
          } = { ...response.data }
          this.form.service_ol_open = parseInt(service_ol_open)
          console.log(this.form.service_ol_open)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    fetchData () {
      serviceOlList().then(response => {
        this.list = response.data.items
      })
    },
    funEdit (index, row) {
      this.$router.push({
        path: '/sys/basic/serviceol/edit',
        query: {
          id: row.id
        }
      })
    },
    funDelete (index, row) {
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          serviceOlDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped>
  .school_pop {
    max-width: 300px;max-height: 300px;
  }
  .el-icon-picture {
    vertical-align: middle; margin: 1px 0 0 5px;
  }
</style>

