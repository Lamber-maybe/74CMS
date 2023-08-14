<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span style="font-weight: 600;color: #333;">求职意向</span><div class="must">必填</div>
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
        ><i class="el-icon-plus" /> 你还没有添加求职意向，点击添加</el-button>
      </div>

      <div
        v-for="(item,index) in list"
        :key="index"
        class="item-row"
      >
        <span
          class="item-li"
          :style="'width:'+(live_fields.trade===true?li5_width:li4_width)"
        >工作性质：{{ item.nature_cn }}</span>
        <span
          class="item-li"
          :style="'width:'+(live_fields.trade===true?li5_width:li4_width)"
        >期望职位：{{ item.category_cn }}</span>
        <span
          class="item-li"
          :style="'width:'+(live_fields.trade===true?li5_width:li4_width)"
        >工作地区：{{ item.district_cn }}</span>
        <span
          v-if="live_fields.trade===true"
          class="item-li"
          :style="'width:'+(live_fields.trade===true?li5_width:li4_width)"
        >期望行业：{{ item.trade_cn }}</span>
        <span
          class="item-li lr"
          :style="'width:'+(live_fields.trade===true?li5_width:li4_width)"
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
      </div>
    </el-card>
    <el-dialog
      v-if="dialogFormVisible"
      append-to-body
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaform
        :rid="id"
        :item-id="itemId"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaform from './resumeIntentionForm.vue'
import { getFieldRule } from '@/api/configuration'
import { resumeIntentionList, resumeIntentionDelete } from '@/api/resume'

export default {
  components: {
    diaform
  },
  props: ['id', 'field_rule'],
  data() {
    return {
      li4_width: '24%',
      li5_width: '19%',
      list: null,
      itemId: 0,
      dialogTitle: '',
      dialogFormVisible: false,
      live_fields: {
        trade: true
      }
    }
  },
  created() {
    this.getFieldRule(this.field_rule)
    this.fetchData()
  },
  methods: {
    getFieldRule(response){
      const extra_rule = response.ResumeIntention
      if (extra_rule.trade.is_display == 0) {
        this.live_fields.trade = false
      }
    },
    fetchData() {
      resumeIntentionList({ 'rid': this.id }).then(response => {
        this.list = response.data.items
        this.$emit('setLoading', 'resumeIntention')
      })
    },
    funAdd() {
      /**
       * 【bug】简历求职意向最多应设置三条
       *  zch 2022.10.9
       *  【新增】
       */
      if (this.list.length >= 3) {
        this.$message({ message: '求职意向最多可填写三条', type: 'warning' })
        return false
      }
      this.itemId = 0
      this.dialogTitle = '添加求职意向'
      this.dialogFormVisible = true
    },
    funEdit(row) {
      if (row) {
        this.itemId = row.id
      } else {
        this.itemId = 0
      }
      this.dialogTitle = '修改求职意向'
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
          resumeIntentionDelete(param).then(response => {
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
.must{
  display: inline-block;
  width: 38.6px;
  height: 19.3px;
  line-height: 19.3px;
  text-align: center;
  background: #feefea;
  border-radius: 2px;
  font-size: 12px;
  font-weight: 500;
  color: #fe6135;
  -webkit-transform: scale(.83);
  transform: scale(.83);
}
.item-row {
  padding: 10px 0px;
	font-size: 14px;
	color: #666;
	border-bottom: 1px dashed #f3f3f3;
}
.item-row:last-child {
	border-bottom: 0;
}
.item-li {
	display: inline-block;
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
