<template>
    <div>
        <div class="common-header">
          <div class="close" @click="handleCommonClose"></div>
          <div class="title">常用语</div>
          <div class="setup" @click="handleOpenSetCommonPopup"></div>
        </div>
        <div class="common-list">
          <div class="common-item substring" v-for="(item) in phraseList" :key="item.id" @click="handleSetCommonText(item.content)">
              {{item.content}}
          </div>
        </div>
    </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {mapState} from 'vuex'
export default {
  name: 'CommonWords',
  data () {
    return {
      phraseList: []
    }
  },
  created () {
    // this.getCommonList()
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken
    })
  },
  methods: {
    /**
     * 获取常用语列表
     */
    getCommonList () {
      http.post(api.phraseList, {token: this.imToken}).then((res) => {
        if (res.code == 200) {
          this.phraseList = res.data.items
        }
      })
    },
    /**
     * 常用语点击
     * @commonText 点击常用语文字
     */
    handleSetCommonText (commonText) {
      this.$emit('handleSetMessageText', commonText)
    },
    /**
     * 常用语关闭
     */
    handleCommonClose () {
      this.$emit('handleCommonClose')
    },
    /**
     * 打开常用语设置
     */
    handleOpenSetCommonPopup () {
      this.$emit('handleOpenSetCommonPopup')
    }
  }
}
</script>

<style lang="scss" scoped>
// 常用语开始
.common-header{
  display: flex;
  align-items: center;
  justify-content: center;
  .close{
    width: 38px;
    height: 58px;
    position: relative;
    padding: 15px 10px;
    &::after,
    &::before{
      content: '';
      position: absolute;
      width: 20px;
      height: 2px;
      background:#737373 ;
      left: 50%;
      top: 50%;
    }
    &::after{
      transform: translate(-50%,-50%) rotate(45deg);
    }
    &::before{
      transform: translate(-50%,-50%) rotate(-45deg);
    }
  }
  .title{
    color: #666666;
    font-size:17px ;
    flex: 2;
    text-align: center;
    padding: 15px 10px;
  }
  .setup{
    width:38px;
    height: 58px;
    background: url('../../../assets/images/im/im-list-set-up.png') no-repeat center center / 18px 18px;
    padding: 15px 10px;
  }
}
.common-list{
  font-size: 14px;
  height: 270px;
  overflow-y: auto;
  .common-item{
    color:#555555 ;
    font-size:14px ;
    line-height: 47px;
    padding: 0 17px;
  }
}
// 常用语结束
</style>
