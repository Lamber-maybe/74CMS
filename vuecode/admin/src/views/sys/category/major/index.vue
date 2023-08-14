<template>
  <div class="app-container">
    <div class="tip">
      <p>
        正式使用后删除分类会导致与此分类关联的信息类别丢失，修改不会受影响。
      </p>
      <p>
        删除顶级分类将会自动删除此分类下的子分类。
      </p>
    </div>
    <el-button size="small" type="primary" @click="funAdd">添加</el-button>
    <el-button size="small" type="primary" @click="onSubmit()">保存</el-button>
    <div class="spaceline" />
    <el-table
      :key="tablekey"
      v-loading="listLoading"
      element-loading-text="Loading"
      size="mini"
      :data="list"
      style="width: 100%;margin-bottom: 20px;"
      row-key="id"
      border
      :default-expand-all="false"
      :lazy="true"
      :load="loadData"
      :tree-props="{ children: 'children', hasChildren: 'hasChildren' }"
    >
      <el-table-column prop="name" label="分类名称">
        <template slot-scope="scope">
          <el-input v-model="scope.row.name" size="mini" class="small" />
          (ID:{{ scope.row.id }})
        </template>
      </el-table-column>
      <el-table-column prop="sort_id" label="排序" width="180">
        <template slot-scope="scope">
          <el-input v-model="scope.row.sort_id" size="mini" class="mini" />
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.level != 2"
            size="small"
            @click="funAdd(scope.$index, scope.row)"
          >
            此类下添加子类
          </el-button>
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
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
    >
      <diaform
        :id="childId"
        :pid="childPid"
        @setDialogFormVisible="closeDialog"
        @pageReload="pageReload"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaform from './components/form.vue'
import { apiList, apiDelete, apiTableSave } from '@/api/config_major'

export default {
  components: {
    diaform
  },
  data() {
    return {
      tablekey: 1,
      childPid: 0,
      childId: 0,
      dialogTitle: '',
      infoLoading: false,
      dialogFormVisible: false,
      saveData: [],
      list: [],
      listLoading: true
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      var that = this
      that.listLoading = true
      const param = {}
      apiList(param)
        .then(response => {
          that.list = [...response.data]
          that.saveData = [...that.list]
          that.listLoading = false
        })
        .catch(() => {})
    },
    funAdd(index, row) {
      this.childId = 0
      if (row) {
        this.childPid = row.id
      } else {
        this.childPid = 0
      }
      this.dialogTitle = '添加分类'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.childPid = 0
      if (row) {
        this.childId = row.id
      } else {
        this.childId = 0
      }
      this.dialogTitle = '编辑分类'
      this.dialogFormVisible = true
    },
    funDelete(index, row) {
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
          apiDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    funDeleteBatch() {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要删除的数据')
        return false
      }
      that
        .$confirm('此操作将永久删除选中的数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          apiDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    loadData(row, treeNode, resolve) {
      const param = {
        pid: row.id
      }
      apiList(param)
        .then(response => {
          resolve(response.data)
          this.saveData = this.saveData.concat(response.data)
        })
        .catch(() => {})
    },
    onSubmit() {
      var that = this
      const insertData = [...that.saveData]
      apiTableSave(insertData)
        .then(response => {
          that.$message.success(response.message)
          return true
        })
        .catch(() => {})
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    pageReload() {
      this.fetchData()
      this.tablekey = !this.tablekey
    }
  }
}
</script>
