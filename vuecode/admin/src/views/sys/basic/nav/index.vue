<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>导航管理</span>
      </div>
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="导航名称" prop="title" width="200" />
        <el-table-column label="跳转链接">
          <template slot-scope="scope">
            <span v-if="scope.row.link_type==1">{{ scope.row.page_cn }}</span>
            <span v-else>{{ scope.row.url }}</span>
          </template>
        </el-table-column>
        <el-table-column label="打开方式" width="200">
          <template slot-scope="scope">
            <span v-if="scope.row.target=='_blank'">新窗口</span>
            <span v-if="scope.row.target=='_self'">原窗口</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="显示状态">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.is_display == 1" type="success">显示</el-tag>
            <el-tag v-else type="danger">不显示</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="排序" prop="sort_id" />
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
          <el-button size="small" type="primary" @click="$router.push('/sys/basic/nav/add')">
            添加导航
          </el-button>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { navList, navDelete } from '@/api/nav'

export default {
  data() {
    return {
      list: null,
      listLoading: true
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      navList({}).then(response => {
        this.list = response.data
        this.listLoading = false
      })
    },
    funEdit(index, row) {
      this.$router.push({
        path: '/sys/basic/nav/edit',
        query: {
          id: row.id
        }
      })
    },
    funDelete(index, row){
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: [row.id]
          }
          navDelete(param).then(response => {
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
