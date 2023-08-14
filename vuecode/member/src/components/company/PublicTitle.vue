<template>
    <div class="main-title">
        <div class="text"><slot></slot></div>
    </div>
</template>

<script>
  export default {
    name: 'PublicTitle',
  }
</script>

<style lang="scss" scoped>
    .main-title {
        position: relative;background-color: #fff;
        .text {font-size: 18px;color: #333;padding: 20px 0;}
    }
</style>