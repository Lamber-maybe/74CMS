<template>
    <div class="common-wrapper">
        <Head :goback_custom="true" @gobackCustomMethod="gobackCustomMethod">管理常用语</Head>
        <van-notice-bar left-icon="info" :text="tipsText" />
        <div class="common-list-con">
          <div class="setup-popup-common-list">
            <draggable :list="phraseList" :options="draggableOption">
              <div class="setup-common-item" v-for="(item) in phraseList" :key="item.id">
                <div class="setup-common-text">
                  {{item.content}}
                </div>
                <div class="setup-common-btn-box">
                  <div class="right-btn">
                    <van-button class="del-btn" size="small" round @click="handleDelCommon(item.id)">删除</van-button>
                    <van-button class="edit-btn" size="small" round @click="handleOpenEditCommon('edit',item)">编辑</van-button>
                  </div>
                  <div v-show="draggableOption.sort" class="sort-box"></div>
                </div>
              </div>
            </draggable>
          </div>
          <div class="setup-btn">
            <van-button class="sort-btn" color="#01B990" round @click="handleSort">
              {{draggableOption.sort ? '完成' : '排序'}}
            </van-button>
            <van-button color="#F56F02" icon="plus" round @click="handleOpenEditCommon('add')">添加(15/{{phraseList.length}})条</van-button>
            <span class="sort-tips">拖拽常用语文字可以对该常用语的位置进行上/下排序</span>
          </div>
        </div>
      </div>
</template>

<script>
import draggable from 'vuedraggable'
import http from '@/utils/http'
import api from '@/api'
import {mapState} from 'vuex'
import EditCommon from './EditCommon.vue'
export default {
  name: 'SetCommonWords',
  data () {
    return {
      // 拖拽参数
      draggableOption: {
        sort: false,
        scroll: true,
        disabled: true,
        containment: '.setup-popup-common-list'
      },
      // 拖拽按钮气泡弹窗提示
      sortTipsStatus: false,
      // 常用语列表
      phraseList: [],
      // 顶部提示
      tipsText: '',
      curtCommonItem: {}
    }
  },
  created () {
    // this.getCommonList()
  },
  components: {
    draggable,
    EditCommon
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken
    })
  },
  methods: {
    /**
     * 管理常用语返回按钮
     */
    gobackCustomMethod () {
      this.$emit('gobackCustomMethod', false)
    },
    /**
     * 排序按钮
     */
    handleSort () {
      this.draggableOption.sort = !this.draggableOption.sort
      this.draggableOption.disabled = !this.draggableOption.disabled
      this.sortTipsStatus = !this.sortTipsStatus
      if (!this.draggableOption.sort) {
        http.post(api.phraseSortAll, {token: this.imToken, sort_data: this.phraseList}).then((res) => {
          console.log(res)
          if (res.code == 200) {
            this.$notify({ type: 'success', message: res.message })
          } else {
            this.$notify({ type: 'danger', message: res.message })
          }
        }).catch(() => {})
      }
    },
    /**
     * 获取常用语列表
     */
    getCommonList () {
      http.post(api.phraseList, {token: this.imToken}).then((res) => {
        if (res.code == 200) {
          this.phraseList = res.data.items
          this.tipsText = '最多可发布15条常用语，您还可以添加' + (15 - this.phraseList.length) + '条。'
        }
      })
    },
    /**
     * 删除常用语
     */
    handleDelCommon (id) {
      this.$dialog.confirm({
        title: '系统提示',
        message: '是否要删除该常用语'
      })
        .then(() => {
          http.post(api.phraseDel, {token: this.imToken, id: id}).then((res) => {
            if (res.code == 200) {
              this.$notify({ type: 'success', message: res.message })
            } else {
              this.$notify({ type: 'error', message: res.message })
            }
            // 更新消息列表
            this.getCommonList()
          })
        })
        .catch(() => {
        })
    },
    /**
     * 编辑添加弹窗开启
     * @type edit / add 区分是添加还是编辑
     * @commonItemData 如果是编辑则传递当前点击项数据
     */
    handleOpenEditCommon (type, commonItemData) {
      if (type == 'edit') {
        this.curtCommonItem = {
          type: type,
          id: commonItemData.id,
          content: commonItemData.content
        }
      } else {
        this.curtCommonItem = {
          type: type
        }
      }
      console.log(this.phraseList.length)
      if (this.phraseList.length >= 15) {
        this.$notify('最多添加15条常用语')
        return false
      }
      this.$emit('handleCommonItemData', this.curtCommonItem)
    }
  }
}
</script>

<style lang="scss" scoped>
// 管理常用语开始
.common-wrapper{
  width: 375px;
  height: 100%;
  overflow: hidden;
}
.common-list-con{
  height: calc(100% - 40px);
  .setup-popup-common-list{
    background: #F3F3F3;
    height: calc(100% - 40px - 82px);
    width: 375px;
    overflow-x: hidden;
    overflow-y: auto;
    .setup-common-item{
      padding: 0 15px;
      margin-bottom: 5px;
      background: #fff;
      .setup-common-text{
        border-bottom: 1px solid #EEEEEE;
        padding: 16px 0;
        font-size: 14px;
      }
      .setup-common-btn-box{
        padding: 17px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: row-reverse;
        .right-btn{
          display: flex;
        }
        .edit-btn{
          margin: 0 10px;
        }
        .sort-box{
          width:21px ;
          height: 21px;
          background: url('../../../../assets/images/im/sort.png') no-repeat center center / 21px 21px;
          &.sort-box-ac{
            background: url('../../../../assets/images/im/sort-ac.png') no-repeat center center / 21px 21px;
          }
        }
      }
    }
  }
}

.setup-btn{
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px 0;
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  .van-button{
    width: 160px;
    height: 44px;
    margin-right: 15px;
    &:last-child{
      margin: 0;
    }
  }
  .sort-tips{
      position: absolute;
      bottom: 60px;
      left: 20px;
      padding: 10px 15px;
      background: #4C4A4A;
      border-radius: 3px;
      font-size: 13px;
      color: #fff;
      &::before{
        content: '';
        border: 5px solid transparent;
        position: absolute;
        left: 35px;
        bottom: -10px;
        border-top: 5px solid #4C4A4A;
      }
    }
}
// 管理常用语结束
</style>
