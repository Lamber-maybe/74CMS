<template>
  <div class="app-container">
    <el-table
      :key="tablekey"
      v-loading="listLoading"
      element-loading-text="Loading"
      :data="list"
      style="width: 100%;margin-bottom: 20px;"
      row-key="id"
      :default-expand-all="false"
      :lazy="true"
      :load="loadData"
      :tree-props="{ children: 'children', hasChildren: 'hasChildren' }"
    >
      <el-table-column
        prop="title"
        label="菜单名称"
      />
      <el-table-column
        prop="key"
        label="菜单关键字"
      />
      <el-table-column
        prop="type"
        label="菜单类型"
      />
      <el-table-column
        prop="url"
        label="链接地址"
      />
      <el-table-column
        prop="sort_id"
        label="排序"
        width="180"
      />
      <el-table-column
        fixed="right"
        label="操作"
        width="350"
      >
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.pid==0"
            size="small"
            @click="funAdd(scope.row)"
          >
            此菜单下添加子菜单
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
    <div class="spaceline" />
    <el-button
      size="small"
      type="primary"
      @click="funAdd"
    >
      添加
    </el-button>
    <el-button
      size="small"
      type="primary"
      @click="funSync"
    >
      同步菜单
    </el-button>
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
    >
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="100px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item
          label="所属菜单"
          prop="parentid"
        >
          <el-select
            v-model="form.pid"
            placeholder="请选择"
          >
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          label="名称"
          prop="title"
        >
          <el-input
            v-model="form.title"
            class="middle"
          />
        </el-form-item>
        <el-form-item
          label="菜单类型"
          prop="type"
        >
          <el-radio-group v-model="form.type">
            <el-radio label="click">
              点击事件
            </el-radio>
            <el-radio label="view">
              链接事件
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="form.type=='click'"
          label="菜单key"
          prop="key"
        >
          <el-input
            v-model="form.key"
            class="middle"
          />
        </el-form-item>
        <el-form-item
          v-if="form.type=='view'"
          label="跳转链接"
          prop="url"
        >
          <el-input
            v-model="form.url"
            class="middle"
          />
        </el-form-item>
        <el-form-item
          label="排序"
          prop="sort_id"
        >
          <el-input
            v-model.number="form.sort_id"
            class="mini"
          />
        </el-form-item>
        <el-form-item label="">
          <el-button
            type="primary"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
          <el-button @click="closeDialog">
            取 消
          </el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { wechatMenuList, wechatMenuAdd, wechatMenuEdit, wechatMenuDelete, wechatMenuSync } from '@/api/wechat_menu'

export default {
  components: {
    // diaform,
  },
  data() {
    return {
      options: [{ value: 0, label: '顶级菜单' }],
      form: {
        id: 0,
        pid: 0,
        title: '',
        key: '',
        type: 'click',
        url: '',
        pagepath: '',
        sort_id: 0
      },
      tablekey: 1,
      dialogTitle: '',
      dialogFormVisible: false,
      list: [],
      listLoading: true,
      rules: {
        title: [
          { required: true, message: '请输入名称', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        sort_id: [
          { type: 'number', message: '排序必须为数字', trigger: 'blur' }
        ]
      }
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
      wechatMenuList(param)
        .then(response => {
          that.list = [...response.data]
          that.options = [{ value: 0, label: '顶级菜单' }]
          that.list.forEach(element => {
            that.options.push({ value: element.id, label: element.title })
          })

          that.listLoading = false
        })
        .catch(() => {})
    },
    funAdd(row) {
      this.form = {
        id: 0,
        pid: 0,
        title: '',
        key: '',
        type: 'click',
        url: '',
        pagepath: '',
        sort_id: 0
      }
      if (row){
        this.form.pid = row.id
      }
      this.dialogTitle = '添加分类'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.form = row
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
          wechatMenuDelete(param)
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
          wechatMenuDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          wechatMenuAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          wechatMenuEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      if (parseInt(insertData.id) > 0) {
        this.editSave(insertData, formName)
      } else {
        const { id, ...data } = insertData
        console.log(id)
        this.addSave(data, formName)
      }
    },
    loadData(row, treeNode, resolve) {
      const param = {
        pid: row.id
      }
      wechatMenuList(param)
        .then(response => {
          resolve(response.data)
        })
        .catch(() => {})
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    pageReload() {
      this.fetchData()
      this.tablekey = !this.tablekey
    },
    funSync() {
      wechatMenuSync({})
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
  .el-input{
    width:400px;
  }
</style>
