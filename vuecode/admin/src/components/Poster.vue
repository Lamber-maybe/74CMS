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
      <span slot="footer" class="dialog-footer">
        <el-button type="warning" size="small" @click="changeTpl"
          >换一个</el-button
        >
        <el-button type="primary" size="small" @click="downloadIamge"
          >保存到电脑</el-button
        >
        <el-button size="small" @click="$emit('closeDialog')">关 闭</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import apiArr from '@/api'
import { makePoster } from '@/api/poster'
export default {
  props: ['posterId', 'posterType'],
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
        type: this.posterType,
        id: this.posterId,
        index: this.currentTplIndex
      }
      makePoster(param).then(response => {
        this.posterImg = response.data + '?_=' + Math.random()
      })
    },
    downloadIamge() {
      const locationUrl = window.global.RequestBaseUrl + apiArr.downloadPoster + '?admintoken=' + getToken() + '&type=' + this.posterType + '&id=' + this.posterId + '&index=' + this.currentTplIndex
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
</style>
