<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>企业过期套餐提醒</span>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        :rules="rules"
        label-width="100px"
      >
        <el-form-item label="会员套餐" prop="setmeal_id">
          <el-checkbox
            v-model="checkSetmealAll"
            style="margin-right:20px;"
            @change="handleCheckAllSetmeal"
          >
            不限
          </el-checkbox>
          <el-checkbox-group
            v-model="form.setmeal_id"
            @change="handleCheckedSetmealChange"
          >
            <el-checkbox
              v-for="item in options_setmeal"
              :key="item.id"
              :label="item.id"
            >
              {{ item.name }}
            </el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="到期时间" prop="overtime">
          <el-select v-model="form.overtime" placeholder="不限" class="large">
            <el-option label="不限" :value="-1" />
            <el-option label="已到期" :value="0" />
            <el-option label="3天内" :value="3" />
            <el-option label="7天内" :value="7" />
            <el-option label="15天内" :value="15" />
            <el-option label="30天内" :value="30" />
            <el-option label="90天内" :value="90" />
            <el-option label="180天内" :value="180" />
          </el-select>
        </el-form-item>
        <el-divider content-position="left">发送设置</el-divider>
        <sendConfig ref="sendConfig" />
        <el-form-item label="">
          <el-button
            type="primary"
            :loading="submiting"
            @click="onSubmit('form')"
          >
            生成任务
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import sendConfig from './components/send_config.vue'
import { marketTaskAdd } from '@/api/market'

export default {
  components: {
    sendConfig
  },
  data() {
    return {
      submiting: false,
      checkSetmealAll: true,
      options_setmeal: [],
      data: {
        target: 'setmeal',
        title: '',
        content: '',
        send_type: [],
        condition: null
      },
      form: {
        setmeal_id: [],
        overtime: -1
      },
      rules: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      getClassify({
        type: 'setmealList'
      })
        .then(response => {
          this.options_setmeal = response.data
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      that.$refs[formName].validate(valid1 => {
        if (valid1) {
          that.$refs.sendConfig.$refs['form'].validate(valid => {
            if (valid) {
              if (that.submiting === true) {
                return false
              }
              that.submiting = true
              const { title, content, send_type } = {
                ...that.$refs.sendConfig.form
              }
              that.data.title = title
              that.data.content = content
              that.data.send_type = send_type
              that.data.condition = { ...that.form }
              const insertData = { ...that.data }
              marketTaskAdd(insertData)
                .then(response => {
                  that.submiting = false
                  that.$message.success(response.message)
                  setTimeout(function() {
                    that.$router.push('/tool/market/list')
                  }, 1500)
                  return true
                })
                .catch(() => {
                  that.submiting = false
                })
            } else {
              return false
            }
          })
        } else {
          return false
        }
      })
    },

    handleCheckAllSetmeal(val) {
      if (val) {
        this.form.setmeal_id = []
      } else {
        if (this.form.setmeal_id.length == 0) {
          return false
        }
      }
    },
    handleCheckedSetmealChange(value) {
      const checkedCount = value.length
      if (checkedCount > 0) {
        this.checkSetmealAll = false
      } else {
        this.checkSetmealAll = true
      }
    }
  }
}
</script>
<style scoped>
.large {
  width: 608px;
}
.el-checkbox-group {
  display: inline;
}
</style>
