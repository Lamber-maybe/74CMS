<template>
  <div class="head_box">
    <div class="head clear">
      <div class="refresh" v-if="showRefresh" @click="$emit('dorefresh')"><van-icon class="absolute_for" name="replay" color="#3088ff" /></div>
      <div class="refresh" v-if="showBack" @click="goBack">
        <van-icon class="absolute_for" name="arrow-left" color="#999999" />
      </div>
      <p class="head_text substring"><slot></slot></p>
      <div class="name_box" v-if="showAdmin" @click="toAdmin">
        <p class="name">admin</p>
        <van-icon class="absolute_for_y" name="arrow-down" size="14px" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Head',
  props: {
    showRefresh: {
      default: false
    },
    showBack: {
      default: true
    },
    showAdmin: {
      default: true
    },
    goBackCustom: {
      default: false
    }
  },
  data () {
    return {}
  },
  methods: {
    goBack () {
      if (this.goBackCustom) {
        this.$emit('goBackCustomMethod')
      } else {
        window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
      }
    },
    toAdmin () {
      this.$router.push('/config/admin')
    }
  }
}
</script>

<style lang="scss" scoped>
  .head_box {
    width: 100%;height: 120px;background-color: #ffffff;
    .head {
      width: 100%;height: 120px;position: fixed;background-color: #ffffff;z-index: 2;border-bottom: 1px solid #f3f3f3;
      .refresh {
        width: 100px;height: 120px;line-height: 120px;position: relative;float: left;text-align: center;
        .van-icon {
          position: absolute;left: 50%;top: 50%;
        }
      }
      .head_text {
        width: 450px;height: 120px;line-height: 120px;font-size: 36px;color: #333333;font-weight: bold;
        text-align: center;position: absolute;left: 150px;top: 0;
      }
      .name_box {
        float: right;width: 150px;height: 120px;line-height: 120px;position: relative;padding-right: 64px;
        text-align: right;
        .name {
          font-size: 28px;color: #666666;
        }
        .van-icon {
          position: absolute;right: 30px;top: 50%;
        }
      }
    }
  }
</style>
