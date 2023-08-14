<template>
  <div class="inv-top-menu-bar">
    <div class="top-menu">
      <div class="top-menu-list">
        <router-link
          v-for="(item, index) in nav"
          :key="index"
          :class="parseInt(item.active) === 1 ? 't-btn active' : 't-btn'"
          :to="item.href"
          >{{ item.text }}</router-link
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PersonalEquallyNav',
  props: ['nav']
}
</script>

<style lang="scss" scoped>
.inv-top-menu-bar {
  background: #ffffff;
  .top-menu {
    overflow: hidden;
    overflow-x: scroll;
    -webkit-overflow-scrolling: touch;
    &::-webkit-scrollbar {
      display: none;
    }
  }
  .top-menu-list {
    white-space: nowrap;
    .t-btn {
      position: relative;
      white-space: nowrap;
      display: inline-block;
      text-align: center;
      color: #666666;
      font-size: 15px;
      text-decoration: none;
      padding: 13px 0;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0.3);
      width: 50%;
      &::after {
        position: absolute;
        left: 50%;
        bottom: 5px;
        width: 16px;
        height: 3px;
        background-color: #1787fb;
        border-radius: 100%;
        transform: translate(-50%, 0);
      }
      &.active {
        color: #1787fb;
        &::after {
          content: " ";
        }
      }
    }
  }
}
</style>
