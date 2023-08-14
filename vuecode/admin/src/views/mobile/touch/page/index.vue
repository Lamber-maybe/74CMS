<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>页面管理</span>
      </div>
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
import { pageMobileList } from '@/api/page_mobile'

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
      pageMobileList({}).then(response => {
        this.list = response.data
        this.listLoading = false
      })
    },
    funEdit(index, row) {
      this.$router.push({
        path: '/mobile/touch/page/edit',
        query: {
          id: row.id
        }
      })
    }
  }
}
</script>
