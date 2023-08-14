<template>
  <div>
    <el-dialog
      title=""
      :visible="true"
      width="300px"
      center
      :show-close="false"
      custom-class="poster_dialog"
    >
      <div v-loading="posterImg == ''" style="height: 533px">
        <img
          v-if="posterImg != ''"
          id="posterImg"
          :src="posterImg"
          style="width: 300px"
        />
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button type="warning" size="small" @click="changeTpl" class="btn"
          >换一个</el-button
        >
        <el-button
          type="primary"
          size="small"
          @click="downloadIamge"
          class="btn"
          >保存到电脑</el-button
        >
        <el-button size="small" @click="$emit('closeDialog')" class="btn"
          >关 闭</el-button
        >
      </div>
    </el-dialog>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  props: ['posterId', 'type'],
  data() {
    return {
      currentTplIndex: 0,
      posterImg: ''
    }
  },
  created() {
    this.funPoster()
  },
  methods: {
    changeTpl() {
      this.currentTplIndex++
      if (this.currentTplIndex >= 3) {
        this.currentTplIndex = 0
      }
      this.funPoster()
    },
    funPoster() {
      this.posterImg = ''
      const param = {
        type: this.type || 'company',
        id: this.posterId,
        index: this.currentTplIndex
      }
      http
        .get(api.create_poster, param)
        .then(res => {
          this.posterImg = res.data + '?_=' + Math.random()
        })
        .catch(() => { })
    },
    downloadIamge() {
      const locationUrl = window.global.RequestBaseUrl + api.download_poster + '?type=company&id=' + this.posterId + '&index=' + this.currentTplIndex
      window.location.href = locationUrl
    }
  }
}
</script>
<style lang="scss" scoped>
::v-deep .el-dialog__header {
  display: none;
}
::v-deep .el-dialog__body {
  padding: 0;
}
.btn {
  width: auto;
  height: auto;
  padding: 10px 15px !important;
}
</style>
