<template>
  <div class="clientInfo_wrapper">
    <resumeEdit :id="id" :resumeEnclosure="resumeEnclosure" @resumeDetails="resumeDetails" />
  </div>
</template>

<script>
import resumeEdit from '@/views/user/resume/edit'
export default {
  name: 'ResumeDetails',
  components: {
    resumeEdit
  },
  props: {
    id: {
      default: ''
    },
    resumeEnclosure:{
      default: {}
    }
  },
  methods: {
    resumeDetails(){
      this.$emit('resumeDetails')
    }
  }
}
</script>

<style  lang="scss" scoped>
.app-container {
  padding: 10px 0px;
}
.clientInfo_wrapper {
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
}
::v-deep .el-card__header{
  padding: 10px 10px;
}
</style>
