<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span  style="font-weight: 600;color: #333;">培训经历</span>
        <el-button
          style="float: right; padding: 6px 18px;font-size: 13px;"
          type="primary"
          round
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
        ><i class="el-icon-plus" /> 你还没有添加培训经历，点击添加</el-button>
      </div>
      <div
        v-for="(item,index) in list"
        :key="index"
        class="item-row"
      >
        <span
          class="item-li"
          style="width:48%"
        >{{ item.timerange }}</span>
        <span
          class="item-li lr"
          style="width:48%"
        >
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
        <div class="clearfix" />
        <span class="item-li">培训机构：{{ item.agency }}</span>
        <span class="item-li">培训课程：{{ item.course }}</span>
        <div class="clearfix" />
        <span class="item-li wide">培训内容：{{ item.description }}</span>
      </div>
    </el-card>
    <el-dialog
      append-to-body
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaform
        :item-id="itemId"
        :rid="id"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaform from './resumeTrainingForm.vue'
import { resumeTrainingList, resumeTrainingDelete } from '@/api/resume'

export default {
  components: {
    diaform
  },
  props: ['id'],
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
        rid: this.id
      }
      resumeTrainingList(param).then(response => {
        this.list = response.data.items
        this.$emit('setLoading', 'resumeTraining')
      })
    },
    funAdd() {
      this.itemId = 0
      this.dialogTitle = '添加培训经历'
      this.dialogFormVisible = true
    },
    funEdit(row) {
      if (row) {
        this.itemId = row.id
      } else {
        this.itemId = 0
      }
      this.dialogTitle = '修改培训经历'
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
          resumeTrainingDelete(param).then(response => {
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
	padding-bottom: 12px;
	margin-bottom: 4px;
}
.item-row:last-child {
	border-bottom: 0;
}
.item-li {
	max-width: 48%;
  min-width: 24%;
  margin-right: 20px;
	height: 30px;
	line-height: 30px;
	display: inline-block;
}
.item-li.wide {
	width: 70%;
}
.item-li.lr {
  float: right;
	text-align: right;
}
.empty {
	width: 100%;
	height: 70px;
	line-height: 70px;
	text-align: center;
}
</style>
