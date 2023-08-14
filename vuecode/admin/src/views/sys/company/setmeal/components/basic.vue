<template>
  <div class="app-container">
    <div class="tip">
      <p>
        不同的运营阶段您可以选择不同的设置。
      </p>
    </div>
    <el-divider content-position="left">简历下载点数配置</el-divider>
    <el-form
      class="common-form"
      label-width="200px"
      style="margin-bottom:80px;"
    >
      <el-form-item
        v-for="(item, index) in resume_download_points_conf"
        :key="index"
        :label="item.name"
      >
        <el-input
          v-model="item.value"
          type="number"
          class="small"
          min="1"
          @blur="handle_val(item)"
          @change="handle_val(item)"
        />
        点
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form1')">保存</el-button>
      </el-form-item>
    </el-form>
    <el-divider content-position="left">
      优质简历下载点数配置
      <el-tooltip
        class="item"
        effect="dark"
        content="优质简历不按上方点数配置影响"
        placement="top-start"
      >
        <i class="el-icon-info" />
      </el-tooltip>
    </el-divider>
    <el-form class="common-form" label-width="200px">
      <el-form-item label="下载优质简历所需">
        <el-input
          v-model="resume_download_points_talent"
          type="number"
          class="small"
          min="1"
          @blur="handle_val2()"
          @change="handle_val2()"
        />
        点
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form2')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      resume_download_points_conf: [],
      resume_download_points_talent: 1
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      setConfig({}, 'get')
        .then(response => {
          this.resume_download_points_conf =
            response.data.resume_download_points_conf
          this.resume_download_points_talent =
            response.data.resume_download_points_talent
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {}
      if (formName == 'form1') {
        insertData.resume_download_points_conf = this.resume_download_points_conf
      } else {
        insertData.resume_download_points_talent = this.resume_download_points_talent
      }

      setConfig(insertData)
        .then(response => {
          this.$store.dispatch('config/getConfigInfo')
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    },
    handle_val(item) {
      if (item.value == '' || parseInt(item.value) < 1) {
        item.value = 1
      }
    },
    handle_val2() {
      if (
        this.resume_download_points_talent == '' ||
        parseInt(this.resume_download_points_talent) < 1
      ) {
        this.resume_download_points_talent = 1
      }
    }
  }
}
</script>
<style scoped>
.small {
  width: 240px;
}
.el-icon-info {
  color: #999;
  margin-left: 6px;
}
.el-divider {
  background: none;
}
</style>
