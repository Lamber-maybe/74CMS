<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>获得证书</span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="funAdd"
        >添加</el-button>
      </div>
      <div
        v-if="list==null || list.length==0"
        class="empty"
      >
        <el-button
          type="primary"
          plain
          round
          @click="funAdd"
        ><i class="el-icon-plus" /> 你还没有添加证书，点击添加</el-button>
      </div>
      <div
        v-for="(item,index) in list"
        :key="index"
        class="item-row"
      >
        <span class="item-li">获得时间：{{ item.time }}</span>
        <span class="item-li">证书名称：{{ item.name }}</span>
        <span class="item-li lr">
          <el-button
            type="text"
            @click="funEdit(item)"
          >
            <i class="el-icon-edit-outline" />
            编辑
          </el-button>
          <el-button
            type="text"
            style="color: red"
            @click="funDelete(item)"
          ><i class="el-icon-delete" />删除</el-button>
        </span>
      </div>
    </el-card>
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaform
        :item-id="itemId"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaform from './resumeCertificateForm.vue'
import { resumeCertificateList, resumeCertificateDelete } from '@/api/resume'

export default {
  components: {
    diaform
  },
  data() {
    return {
      list: null,
      itemId: 0,
      dialogTitle: '',
      dialogFormVisible: false
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const param = {
        rid: this.$route.query.id
      }
      resumeCertificateList(param).then(response => {
        this.list = response.data.items
      })
    },
    funAdd() {
      this.itemId = 0
      this.dialogTitle = '添加证书'
      this.dialogFormVisible = true
    },
    funEdit(row) {
      if (row) {
        this.itemId = row.id
      } else {
        this.itemId = 0
      }
      this.dialogTitle = '修改证书'
      this.dialogFormVisible = true
    },
    funDelete(row) {
      var that = this
      that.$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          const param = {
            id: row.id,
            rid: row.rid
          }
          resumeCertificateDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    closeDialog() {
      this.dialogFormVisible = false
    }
  }
}
</script>

<style scoped>
.item-row {
	font-size: 14px;
	color: #666;
	border-bottom: 1px dashed #f3f3f3;
}
.item-row:last-child {
	border-bottom: 0;
}
.item-li {
	width: 32%;
	display: inline-block;
}
.item-li.lr {
	text-align: right;
}
.empty {
	width: 100%;
	height: 70px;
	line-height: 70px;
	text-align: center;
}
</style>
