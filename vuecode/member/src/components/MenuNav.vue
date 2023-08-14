<template>
    <el-tabs class="my_tabs" v-model="activeName"  @tab-click="handleClick">
        <el-tab-pane
                :label="item.label"
                :name="item.name"
                v-for="(item, index) in list"
                :key="index">
        </el-tab-pane>
    </el-tabs>
</template>

<script>
  export default {
    name: 'MenuNav',
    data () {
      return {
        activeName: ''
      }
    },
    mounted () {
      this.activeName = this.activeTab!==undefined?(this.activeTab+''):this.list.filter(item=>item.active)[0].name
    },
    props: ['list','activeTab'],
    methods: {
      handleClick (tab) {
        let tabIndex = parseInt(tab.index)
        if(this.$route.fullPath==this.list[tabIndex].href){
          return false;
        }
        this.$router.push(this.list[tabIndex].href)
      }
    }
  }
</script>

<style scoped>

</style>