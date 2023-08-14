<template>
  <div>
    <div class="b1">
      <div class="title">
        名企招聘
        <div class="more" @click="goto('/companyList')">更多</div>
      </div>
      <div class="content">
        <div class="item" v-for="(item,index) in dataset" :key="index" @click="jumpTo(item.id)">
          <div class="logo_box">
            <img
              :src="
                item.logo == ''
                  ? require('../../../assets/images/index/index_head_three_bg.png')
                  : item.logo
              "
              :alt="item.companyname"
            />
          </div>
          <div class="tx1">{{ item.companyname }}</div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StarCompanyFour',
  props: ['dataset'],
  created () {},
  methods: {
    jumpTo (id) {
      this.$emit('jumpTo', { id })
    },
    goto (route) {
      this.$router.push(route)
    }
  }
}
</script>

<style lang="scss" scoped>
.b1 {
  .content {
    .item {
      .tx1 {
        padding: 0 6px 9px;
        font-size: 13px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .logo_box {
        img {
          position: absolute;
          left: 50%;
          top: 50%;
          transform: translate(-50%, -50%);
          max-width: 70px;
          max-height: 70px;
        }
        position: relative;
        width: 100%;
        height: 92px;
      }
      float: left;
      width: 125px;
      text-align: center;
    }
    width: 100%;
    padding-bottom: 10px;
  }
  .title {
    .more {
      &::after {
        position: absolute;
        right: 2px;
        top: 50%;
        width: 6px;
        height: 6px;
        margin-top: -3px;
        border-top: 1px solid #999999;
        border-right: 1px solid #999999;
        transform: rotate(45deg);
        content: " ";
      }
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 12px;
      color: #999999;
      line-height:normal;
      padding: 1px 11px 1px 0;
    }
    position: relative;
    font-size: 17px;
    color: #333333;
    padding: 17.5px 15px;
  }
  width: 100%;
  background-color: #ffffff;
}
</style>
