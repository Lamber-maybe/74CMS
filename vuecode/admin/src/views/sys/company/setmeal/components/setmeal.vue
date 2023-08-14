<template>
  <div class="app-container">
    <div class="tip">
      <p>
        不同的运营阶段您可以选择不同的设置。
      </p>
    </div>
    <el-table
      v-loading="listLoading"
      element-loading-text="Loading"
      fit
      highlight-current-row
      :data="list"
      style="width: 100%"
    >
      <el-table-column
        prop="name"
        label="套餐名称"
        min-width="120"
      />
      <el-table-column label="价格" min-width="100">
        <template slot-scope="scope">
          {{ scope.row.expense == 0 ? '免费' : scope.row.expense }}
        </template>
      </el-table-column>
      <el-table-column label="限时优惠" min-width="100">
        <template slot-scope="scope">
          <span v-if="scope.row.preferential_open == 0">无</span>
          <span v-else>
            {{ scope.row.preferential_expense }}
            <el-tooltip
              class="item"
              effect="dark"
              :content="
                timeFilter(scope.row.preferential_expense_start) +
                  ' 至 ' +
                  timeFilter(scope.row.preferential_expense_end)
              "
              placement="top-start"
            >
              <i class="el-icon-time" />
            </el-tooltip>
          </span>
        </template>
      </el-table-column>
      <el-table-column label="时长" min-width="100">
        <template slot-scope="scope">
          {{ scope.row.days == 0 ? '无限期' : `${scope.row.days}天` }}
        </template>
      </el-table-column>
      <el-table-column label="内容" min-width="250">
        <template slot-scope="scope">
          简历点 {{ scope.row.download_resume_point }}；赠积分
          {{ scope.row.gift_point }}；
          <span
            style="cursor:pointer;color:#3a8ee6;font-size:12px;"
            @click="fun_detail(scope.row)"
          >
            [查看全部]
          </span>
        </template>
      </el-table-column>
      <el-table-column label="推荐" min-width="80">
        <template slot-scope="scope">
          <el-tag :type="scope.row.recommend | colorFilter">
            {{ scope.row.recommend == 1 ? '是' : '否' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="排序" min-width="60">
        <template slot-scope="scope">
          <span>{{ scope.row.sort_id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="显示状态" min-width="80">
        <template slot-scope="scope">
          <el-tag :type="scope.row.is_display | colorFilter">
            {{ scope.row.is_display == 1 ? '显示' : '隐藏' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="会员申请" min-width="90">
        <template slot-scope="scope">
          <el-tag :type="scope.row.is_apply | colorFilter">
            {{ scope.row.is_apply == 1 ? '允许' : '不允许' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" min-width="150">
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="small"
            @click="funEdit(scope.$index, scope.row)"
          >
            编辑
          </el-button>
          <el-button
            size="small"
            type="danger"
            :disabled="$store.state.config.reg_service == scope.row.id"
            @click="funDelete(scope.$index, scope.row)"
          >
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-button size="small" type="primary" @click="funAdd">添加套餐</el-button>
    <el-dialog
      v-if="dialogFormVisible"
      :title="dialogTitle"
      :visible.sync="dialogFormVisible"
      :width="dialog_width"
      :close-on-click-modal="false"
    >
      <diaform
        v-if="dialog_type == 'form'"
        :id="childId"
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
      <div v-if="dialog_type == 'detail'">
        <el-row>
          <el-col :span="24">
            简历点：
            <b>{{ detailContent.download_resume_point }}</b>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            职聊次数：
            <b>{{ detailContent.im_total }}</b>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            赠送积分：
            <b>{{ detailContent.gift_point }}</b>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            在招职位：
            <b>{{ detailContent.jobs_meanwhile }}</b>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            免费刷新职位次数：
            <b>{{ detailContent.refresh_jobs_free_perday }}</b>
            &nbsp;次/天
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            下载简历数上限：
            <b>{{ detailContent.download_resume_max_perday }}</b>
            &nbsp;份/天
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            发起聊天数上限：
            <b>{{ detailContent.im_max_perday }}</b>
            &nbsp;份/天
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            增值服务折扣：
            <b>{{ detailContent.service_added_discount }}</b>
            &nbsp;折
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            视频面试：
            <b>
              {{
                detailContent.enable_video_interview == 1 ? '可用' : '不可用'
              }}
            </b>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            收到简历免费查看：
            <b>{{ detailContent.show_apply_contact == 1 ? '是' : '否' }}</b>
          </el-col>
        </el-row>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import diaform from './form.vue'
import { setmealList, setmealDelete } from '@/api/setmeal'
import { parseTime } from '@/utils/index'

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
      dialog_width: '56%',
      detailContent: {},
      dialog_type: 'form',
      childId: 0,
      dialogTitle: '',
      dialogFormVisible: false,
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
      const param = {}
      setmealList(param)
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
        })
        .catch(() => {})
    },
    funAdd(index, row) {
      this.dialog_width = '56%'
      this.dialog_type = 'form'
      this.childId = 0
      this.dialogTitle = '添加套餐'
      this.dialogFormVisible = true
    },
    funEdit(index, row) {
      this.dialog_width = '56%'
      this.dialog_type = 'form'
      if (row) {
        this.childId = row.id
      } else {
        this.childId = 0
      }
      this.dialogTitle = '编辑套餐'
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
          setmealDelete(param)
            .then(response => {
              that.$message.success(response.message)
              that.fetchData()
              return true
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}年{m}月{d}日')
    },
    fun_detail(item) {
      this.dialog_width = '25%'
      this.dialog_type = 'detail'
      this.detailContent = item
      this.dialogTitle = '套餐内容'
      this.dialogFormVisible = true
    }
  }
}
</script>
<style>
</style>
<style lang="scss" scoped>
.el-row {
  margin-bottom: 20px;
  margin-left: 40px;
  &:last-child {
    margin-bottom: 0;
  }
}
</style>
