<template>
  <div>
      <van-popup v-model="greetPpopup" position="right" :style="{ width:'100%', height: '100%' ,overflow: 'hidden'}">
          <div class="greet-wrapper">
              <Head :goback_custom="true" @gobackCustomMethod="gobackGreet">招呼语管理</Head>
              <div class="tips-wrapper">
                  <div class="t1">请选择默认打招呼语</div>
                  <div class="t2">选中的招呼语将在下次与招聘方沟通时生效</div>
              </div>
              <div class="greet-list">
                  <div class="greet-item" v-for="(item) in greetList" :key="item.id" @click="handleSelectGreet(item.id)">
                      <van-checkbox v-model="item.selected"></van-checkbox>
                      <div class="greet-text">{{item.content}}</div>
                  </div>
              </div>
          </div>
      </van-popup>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {mapState} from 'vuex'
export default {
  name: 'Greet',
  data () {
    return {
      greetPpopup: false,
      greetList: []
    }
  },
  created () {

  },
  computed: {
    ...mapState({
      imToken: state => state.imToken
    })
  },
  methods: {
    /**
     * 打开招呼语管理弹窗
     */
    openGreet () {
      this.greetPpopup = true
      this.getGreetList()
    },
    /**
     * 关闭招呼语管理弹窗
     */
    gobackGreet () {
      this.greetPpopup = false
    },
    /**
     * 获取招呼语列表
     */
    getGreetList () {
      http.post(api.hellomsgList, {token: this.imToken}).then(res => {
        if (res.code == 200) {
          // console.log(res)
          this.greetList = res.data.items
        }
      })
    },
    /**
     * 选择招呼语
     */
    handleSelectGreet (id) {
      http.post(api.hellomsgSelect, {token: this.imToken, id: id}).then(res => {
        console.log(res)
        if (res.code == 200) {
          this.$notify({ type: 'success', message: res.message })
        } else {
          this.$notify({ type: 'danger', message: res.message })
        }
        this.getGreetList()
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.tips-wrapper{
    font-size: 14px;
    margin-bottom: 6px;
    padding: 25px 15px;
    background:#fff url('../../../assets/images/im/greet-tips.png') no-repeat 284px center / 86px 50px;
    .t1{
        color: #333333;
        font-size: 16px;
        margin-bottom: 8px;
    }
    .t2{
        font-size: 12px;
        color: #999999;
    }
}
.greet-wrapper{
    width: 100%;
    height: 100%;
    background: #F3F3F3;
    .greet-list{
        overflow-y: auto;
        height:calc(100% - 50px);
        .greet-item{
            font-size: 14px;
            display: flex;
            background: #fff;
            padding: 22px 16px;
            border-bottom: 1px solid #F3F3F3;
            .greet-text{
                flex-shrink: 0;
                width: 300px;
                margin-left: 22px;
                line-height: 23px;
            }
        }
    }
}

</style>
