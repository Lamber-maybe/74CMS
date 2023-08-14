<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>任务配置</span>
      </div>

      <el-form
        v-loading="infoLoading"
        class="common-form"
        label-width="0"
        :inline-message="true"
      >
        <el-form-item>
          <el-table border :data="list">
            <el-table-column label="任务名称" prop="name" />
            <el-table-column align="center" label="完成任务奖励积分">
              <template slot-scope="scope">
                <el-form-item>
                  <el-input
                    v-model="scope.row.points"
                    type="number"
                    style="width:120px"
                  />
                </el-form-item>
              </template>
            </el-table-column>
            <el-table-column align="center" label="每天奖励次数上限">
              <template slot-scope="scope">
                <el-form-item v-if="scope.row.daily == 1">
                  <el-input
                    v-model="scope.row.max_perday"
                    type="number"
                    style="width:120px"
                  />
                </el-form-item>
                <el-form-item v-else>
                  -
                </el-form-item>
              </template>
            </el-table-column>
            <el-table-column align="center" label="任务类型">
              <template slot-scope="scope">
                <span v-if="scope.row.daily == 1" class="font_warning">
                  日常任务
                </span>
                <span v-else class="font_brand">单次任务</span>
              </template>
            </el-table-column>
          </el-table>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { taskList, taskSave } from '@/api/task'

export default {
  data() {
    return {
      infoLoading: true,
      list: []
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      taskList({ utype: 2 }, 'get')
        .then(response => {
          this.list = [...response.data.items]
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = []
      this.list.forEach(function(val, index, arr) {
        const { id, points, daily, max_perday } = { ...val }
        const tmp_arr = {
          id,
          points,
          daily,
          max_perday
        }
        insertData.push(tmp_arr)
      })
      taskSave(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>
