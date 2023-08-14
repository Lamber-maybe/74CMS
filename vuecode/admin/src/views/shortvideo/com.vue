<template>
  <div class="app-container">
    <el-card>
      <div
              slot="header"
              class="clearfix"
      >
        <span>视频招聘</span>
      </div>
      <vlist :vtype="1" />
    </el-card>
  </div>
</template>

<script>
    import vlist from './components/vlist.vue'

    export default {
        components: {
            vlist
        },
        data(){
            return {
            }
        }
    }
</script>
