<template>
  <div class="app-container">
    <el-card>
      <div class="tip">
        <p>
          短视频功能基于七牛云储存，请确保您已在 系统->基础配置->系统配置->文件上传->七牛云 正确配置七牛云账号信息。如未配置该模块将无法正常使用。
        </p>
      </div>
      <config-basic />
    </el-card>

  </div>
</template>

<script>
    import configBasic from './components/basic.vue'


    export default {
        components: {
            configBasic
        }
    }
</script>
