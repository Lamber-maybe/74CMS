<template>
    <div>
        <!-- 常用语列表 -->
        <van-popup v-model="setuppopup" position="right" :style="{ width:'100%', height: '100%' ,overflow: 'hidden'}">
            <SetCommonWords ref="SetCommonWords" @gobackCustomMethod="gobackCustomMethod" @handleCommonItemData="handleCommonItemData"></SetCommonWords>
        </van-popup>
        <!-- 常用语列表 -->

        <!-- 常用语编辑弹窗开启 -->
        <van-popup v-model="editCommonPopup" position="right" :style="{ width:'100%', height: '100%' ,overflow: 'hidden'}">
          <EditCommon :editCommonObj="curtCommonItem" @gobackEditCommon="gobackEditCommon" @updataCommonList="updataCommonList"></EditCommon>
        </van-popup>
        <!-- 常用语编辑弹窗结束 -->
    </div>
</template>

<script>
// import http from '@/utils/http'
// import api from '@/api'
import SetCommonWords from './common/SetCommonWords.vue'
import EditCommon from './common/EditCommon.vue'

export default {
  name: 'CommonIndex',
  data () {
    return {
      editCommonPopup: false,
      setuppopup: false,
      curtCommonItem: {}
    }
  },
  components: {
    SetCommonWords,
    EditCommon
  },
  methods: {
    /**
     * 打开常用语列表弹窗
     */
    handleOpenCommonList () {
      this.setuppopup = true
      // 更新列表
      this.$nextTick(function () {
        this.$refs.SetCommonWords.getCommonList()
      })
    },
    /**
     * 常用语设置面板关闭
     */
    gobackCustomMethod () {
      this.setuppopup = false
    },
    /**
     * 添加编辑常用语弹窗关闭
     */
    gobackEditCommon () {
      this.editCommonPopup = false
    },
    /**
     * 获取 编辑/添加页面 数据
     */
    handleCommonItemData (curtCommonItem) {
      this.curtCommonItem = curtCommonItem
      this.editCommonPopup = true
    },
    /**
     * 更新列表数据
     */
    updataCommonList () {
      this.$refs.SetCommonWords.getCommonList()
      this.$emit('updataCommonList')
    }
  }
}
</script>

<style>

</style>
