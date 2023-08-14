<template>
  <div>
    <div class="b1">
      <div class="title">
        名企招聘
        <div class="more" @click="goto('/companyList')">更多</div>
      </div>
      <div class="inv-top-menu-bar">
        <div class="top-menu">
          <div class="top-menu-list">
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
              <div class="tx2">
                在招职位
                <span>{{ item.jobnum }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StarCompanyTwo',
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
  .inv-top-menu-bar {
    background-color: #ffffff;
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
      .item {
        &:first-child {
          margin-left: 15px;
        }
        .tx2 {
          span {
            color: #ffaf24;
          }
          padding: 5px 0 17.5px;
          font-size: 11px;
          color: #666666;
        }
        .tx1 {
          padding: 17.5px 15px 0 0;
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
            max-width: 50px;
            max-height: 50px;
          }
          position: absolute;
          top: 12px;
          left: 14px;
          width: 50px;
          height: 50px;
          border: 1px solid #e3e3e3;
          border-radius: 2px;
        }
        position: relative;
        width: 200px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        margin: 2px 8px 15px 0;
        display: inline-block;
        vertical-align: top;
        padding-left: 75px;
      }
    }
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
    padding: 20px 15px;
  }
  width: 100%;
  background-color: #ffffff;
}
</style>
