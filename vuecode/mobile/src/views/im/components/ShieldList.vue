<template>
  <div>
      <van-popup v-model="ShieldPopup" position="right" :style="{ width:'100%', height: '100%' ,overflow: 'hidden'}">
          <div class="shield-wrapper">
                <Head :goback_custom="true" @gobackCustomMethod="gobackEditCommon">已屏蔽聊天管理</Head>
                <div class="shield-list" v-if="blacklist.length>0">
                    <div class="shield-item" v-for="(item) in blacklist" :key="item.id">
                        <div class="text1">{{item.showname}}</div>
                        <div class="text2">沟通职位：{{item.jobname}}</div>
                        <div class="text3">屏蔽时间：{{item.addtime}}</div>
                        <div class="shield-dle" @click="handleDelBack(item)">
                            <van-icon name="close" />
                        </div>
                    </div>
                </div>
                <van-empty v-else description="暂无屏蔽聊天" />
          </div>
      </van-popup>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {mapState} from 'vuex'
export default {
  name: 'ShieldList',
  data () {
    return {
      ShieldPopup: false,
      blacklist: []
    }
  },
  created () {
    this.getBlacklist()
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken
    })
  },
  methods: {
    /**
     * 打开屏蔽聊天弹窗
     */
    handleOpenShieldPopup  () {
      this.ShieldPopup = true
    },
    /**
     * 关闭屏蔽聊天弹窗
     */
    gobackEditCommon () {
      this.ShieldPopup = false
    },
    /**
     * 黑名单列表
     */
    getBlacklist () {
      http.post(api.blacklist, {token: this.imToken}).then(res => {
        if (res.code == 200) {
          this.blacklist = res.data.items
        }
      })
    },
    /**
     * 删除黑名单
     */
    handleDelBack (blackItem) {
      http.post(api.del_blacklist, {token: this.imToken, id: blackItem.id}).then(res => {
        if (res.code == 200) {
          this.$notify({ type: 'success', message: res.message })
        } else {
          this.$notify({ type: 'danger', message: res.message })
        }
        this.getBlacklist()
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.shield-wrapper{
    height: 100%;
    background: #FAFAFA;
    .shield-list{
        .shield-item{
            height: 106px;
            position: relative;
            font-size: 14px;
            background: #fff;
            position: relative;
            padding:15px 12px 16px 15px;
            &::after{
                position: absolute;
                box-sizing: border-box;
                content: ' ';
                bottom: 0;
                width:93% ;
                height: 1px;
                background:#ebedf0 ;
                // margin: 0 12px 0 15px;
            }
            .text1{
                font-size: 16px;
                color: #333333;
                font-weight: 500;
            }
            .text2{
                font-size: 14px;
                color: #666666;
                font-weight: 500;
                line-height: 30px;
            }
            .text3{
                font-size: 12px;
                color: #999999;
                font-weight: 500;
            }
            .shield-dle{
                width: 30px;
                height: 30px;
                position: absolute;
                right: 0px;
                top: 50%;
                transform: translateY(-50%);
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    }
}
</style>
