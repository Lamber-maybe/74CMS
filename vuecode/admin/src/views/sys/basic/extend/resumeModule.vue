<template>
  <div class="app-container">
    <el-form
      v-loading="infoLoading"
      class="common-form"
      label-width="0"
      :inline-message="true"
    >
      <el-form-item>
        <el-table border :data="list">
          <el-table-column label="模块名称" prop="module_cn"/>
          <el-table-column align="center" label="是否显示">
            <template slot-scope="scope">
              <div v-if="false === scope.row.enable_close">
                <span style="color:#67C23A">显示</span>
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="系统不允许关闭此项"
                  placement="top-start"
                >
                  <i class="el-icon-question"/>
                </el-tooltip>
              </div>
              <el-switch
                v-else
                v-model="scope.row.is_display"
                @change="switchChangeBtn(scope.row)"
              />
            </template>
          </el-table-column>
          <el-table-column label="完整度">
            <template slot-scope="scope">
              <el-form-item>
                <div @click="switchBtn(scope.row)">
                  <el-input
                    v-model="scope.row.score"
                    :disabled="scope.row.is_display ? false : true "
                    type="number"
                    style="width:120px"
                    @change="handle_total"
                    @blur="handle_total"
                  />
                </div>
              </el-form-item>
            </template>
          </el-table-column>
        </el-table>
        <el-card shadow="never" style="border-top:0">
          完整度总和：
          <b :style="'color:' + total_color">{{ total }}</b>
          <el-alert
            v-if="infoLoading === false && total != 100"
            title="完整度总和必须等于100"
            type="error"
            :closable="false"
          />
        </el-card>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {setResumeModule} from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      list: [],
      total: 0,
      total_color: '#67C23A'
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    switchChangeBtn(info) {
      if (!info.is_display) {
        info.score = 0
      }
      this.handle_total()
    },
    switchBtn(info) {
      if (!info.is_display) {
        this.$message.warning('请先打开' + info.module_cn + '显示开关！')
      }
    },
    fetchInfo() {
      this.infoLoading = true
      const that = this
      setResumeModule({}, 'get')
        .then(response => {
          const data = [...response.data]
          data.forEach(function (val, index, arr) {
            val.is_display = val.is_display == 1
            val.enable_close = val.enable_close == 1
            that.list.push(val)
            if (val.is_display === true) {
              that.total = that.total + parseInt(val.score)
            }
          })

          this.infoLoading = false
        })
        .catch(() => {
        })
    },
    onSubmit(formName) {
      const that = this
      let hasError = false
      const insertData = []
      this.list.forEach(function (val, index, arr) {
        if (val.score == '') {
          val.score = 0
        }
        const tmp_val = {
          id: val.id,
          is_display: val.is_display === true ? 1 : 0,
          score: val.score
        }
        insertData[index] = tmp_val
      })
      if (this.total != 100) {
        that.$message.error('完整度总和必须等于100，当前总和' + this.total)
        hasError = true
      }

      if (hasError === true) {
        return false
      }
      setResumeModule(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {
        })
    },
    handle_total() {
      let score_counter = 0
      this.list.forEach(item => {
        if (item.is_display == 1) {
          if (item.score == '') {
            item.score = 0
          }
          score_counter = score_counter + parseInt(item.score)
        }
      })
      if (score_counter == 100) {
        this.total_color = '#67C23A'
      } else {
        this.total_color = '#F56C6C'
      }
      this.total = score_counter
    }
  }
}
</script>
<style scoped>
.el-icon-question {
  font-size: 16px;
}
</style>
