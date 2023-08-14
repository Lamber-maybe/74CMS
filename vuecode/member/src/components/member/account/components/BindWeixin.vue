<template>
  <div class="wx_img">
    <div>
      <img :src="$store.state.config.wechat_qrcode" width="175" height="175"/>
    </div>
    <p>
      使用微信扫一扫，按提示快速绑定
    </p>
  </div>
</template>

<script>
</script>

<style lang="scss" scoped>
  .wx_img{
    text-align: center;
  }
  .wx_img>div{
  }
  .wx_img p{
    margin-top: 22px;
    font-size: 14px;
    color: #030303;
  }
</style>
