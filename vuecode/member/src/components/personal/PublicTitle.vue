<template>
    <div class="title">
        <div class="text"><slot></slot></div>
    </div>
</template>

<script>
  export default {
    name: 'PublicTitle',
  }
</script>

<style lang="scss" scoped>
    .title {
        position: relative;background-color: #fff;
        .text {font-size: 18px;color: #333;padding: 30px 0 15px;}
    }
</style>