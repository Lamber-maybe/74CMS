<template>
  <div class="app-container">
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
    >
      <el-table-column label="菜单名称" prop="title" />
      <el-table-column label="自定义名称">
        <template slot-scope="scope">
          <span v-if="scope.row.custom_title != ''">
            {{ scope.row.custom_title }}
          </span>
          <span v-else class="font_info">系统默认</span>
        </template>
      </el-table-column>
      <el-table-column label="跳转url">
        <template slot-scope="scope">
          <span v-if="scope.row.link_url != ''">
            {{ scope.row.link_url }}
          </span>
          <span v-else class="font_info">系统默认</span>
        </template>
      </el-table-column>
      <el-table-column label="图标">
        <template slot-scope="scope">
          <el-popover
            v-if="scope.row.iconUrl != ''"
            placement="right"
            width="120"
            height="120"
            trigger="click"
          >
            <img :src="scope.row.iconUrl" style="width:120px;height:120px;">
            <span slot="reference" class="font_brand" style="cursor:pointer">
              [点击查看]
            </span>
          </el-popover>
          <span v-else class="font_info">系统默认</span>
        </template>
      </el-table-column>
      <el-table-column label="排序" prop="sort_id" />
      <el-table-column align="center" label="是否显示">
        <template slot-scope="scope">
          <el-tag :type="scope.row.is_display | colorFilter">
            {{ scope.row.is_display == 1 ? '显示' : '隐藏' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" width="300">
        <template slot-scope="scope">
          <el-button size="small" type="primary" @click="funEdit(scope.row)">
            修改
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog
      v-if="dialogFormVisible"
      title="编辑菜单"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaform
        :item-info="itemInfo"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaform from './form.vue'
import { mobileIndexMenuList } from '@/api/configuration'

export default {
  filters: {
    colorFilter(val) {
      if (val == 1) {
        return 'success'
      } else {
        return 'danger'
      }
    }
  },
  components: {
    diaform
  },
  data() {
    return {
      itemInfo: null,
      dialogFormVisible: false,
      dialogListVisible: false,
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
      mobileIndexMenuList({}).then(response => {
        this.list = response.data.items
        this.listLoading = false
      })
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    funEdit(row) {
      this.itemInfo = row
      this.dialogFormVisible = true
    }
  }
}
</script>
