<template>
  <el-alert :title="title" type="warning" show-icon :closable="false"></el-alert>
</template>

<script>
  export default{
    data(){
      return{}
    },
    props:["title"]
  }
</script>


<style lang="scss" scoped>
.el-alert{
  margin-bottom: 20px;
	padding: 12px 16px;
}
</style>
