<template>
  <div class="main">
    <div class="item"><img class="img" src="./../assets/images/auth_ico_ing.png" /></div>
    <div class="item lg">禁止访问当前网站！</div>
    <div class="item sm">您的IP已经被禁止访问，请联系网站管理员</div>
  </div>
</template>

<script>

export default {
  name: 'Ipdeny'
}
</script>

<style scoped>
    * {
        margin: 0;
        padding: 0;
    }
    html,
    body {
        position: relative;
        width: 100%;
        height: 100%;
    }
    .img {
        color: #F56C6C;
        font-size: 110px;
    }
    .main {
        text-align: center;
        width: 350px;
        height: 300px;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -175px;
        margin-top: -150px;
    }
    .lg {
        font-weight: bold;
        font-size: 20px;
    }
    .sm {
        color: #999;
        font-size: 16px;
    }
    .item {
        margin-bottom: 20px;
    }
</style>
