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
        >
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button
          v-if="posterIndex===undefined"
          type="warning"
          size="small"
          @click="changeTpl"
        >换一个</el-button>
        <el-button
          v-if="posterIndex===undefined"
          type="primary"
          size="small"
          @click="downloadIamge"
        >保存到电脑</el-button>
        <el-button size="small" @click="$emit('closeDialog')">关 闭</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { posterTplindexList } from '@/api/poster'
import { getToken } from '@/utils/auth'
import apiArr from '@/api'
import { makePoster } from '@/api/poster'
export default {
  props: ['posterId', 'posterType', 'posterIndex'],
  data() {
    return {
      currentTplIndex: 1,
      posterImg: '',
      indexlist: []
    }
  },
  created() {
    if (this.posterIndex !== undefined){
      this.currentTplIndex = this.posterIndex
    }
    this.fetchData()
    this.funPoster()
  },
  methods: {
    fetchData () {
      this.listLoading = true
      posterTplindexList({ type: this.posterType == 'job' ? 1 : (this.posterType == 'resume' ? 2 : 3) }).then(response => {
        this.indexlist = response.data
      }).catch(() => {
        this.listLoading = false
      })
    },
    changeTpl() {
      const c_index = this.indexlist.indexOf(this.currentTplIndex)
      let next_index = c_index + 1
      if (this.indexlist[next_index] === undefined){
        next_index = 0
      }
      this.currentTplIndex = this.indexlist[next_index]
      this.funPoster()
    },
    funPoster() {
      this.posterImg = ''
      const param = {
        type: this.posterType,
        id: this.posterId === undefined ? 0 : this.posterId,
        index: this.currentTplIndex
      }
      makePoster(param).then(response => {
        this.posterImg = response.data + '?_=' + Math.random()
      })
    },
    downloadIamge() {
      const locationUrl = window.global.RequestBaseUrl + apiArr.downloadPoster + (window.global.RequestBaseUrl.indexOf('?') == -1 ? '?' : '&') + 'admintoken=' + getToken() + '&type=' + this.posterType + '&id=' + (this.posterId === undefined ? 0 : this.posterId) + '&index=' + this.currentTplIndex
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
