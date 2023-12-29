<template>
  <div class="app-container">
    <el-tabs type="border-card" v-model="activeName" @tab-click="handleClick">
      <el-tab-pane name="toolimbasic" label="基本配置" :lazy="true">
        <toolimbasic ref="toolimbasic" />
      </el-tab-pane>
      <el-tab-pane name="toolimper" label="个人配置" :lazy="true">
        <toolimper ref="toolimper" />
      </el-tab-pane>
      <el-tab-pane name="toolimcom" label="企业配置" :lazy="true">
        <toolimcom ref="toolimcom" />
      </el-tab-pane>
      <el-tab-pane name="toolimunread" label="未读消息提醒" :lazy="true">
        <toolimunread ref="toolimunread" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import toolimbasic from './components/basic.vue'
import toolimper from './components/per.vue'
import toolimcom from './components/com.vue'
import toolimunread from './components/unread.vue'
export default {
  components: {
    toolimbasic,
    toolimper,
    toolimcom,
    toolimunread
  },
  data(){
    return {
      activeName: 'toolimbasic'
    }
  },
  mounted() {
    this.$refs.toolimbasic.iniFun()
  },
  methods:{
    handleClick(tab, event){
      this.$nextTick(() => {
        this.$refs[tab.name].iniFun()
      });
    }
  }
}
</script>
