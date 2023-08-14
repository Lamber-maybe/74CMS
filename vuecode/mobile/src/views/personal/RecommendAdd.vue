<template>
  <div id="app">
    <Head>求职意向</Head>
    <div class="form_split_10"></div>
    <div class="notice_bar">添加多个求职意向，可获得更多精准推荐的工作机会</div>
    <div class="box_1">
      <div class="box_content">
        <div class="tx1" @click="showPickerCurrent = !showPickerCurrent">
          求职状态
          <div class="right_txt">{{ currentName }}</div>
        </div>
        <van-popup v-model="showPickerCurrent" position="bottom">
          <van-picker
            show-toolbar
            :columns="columnsCurrent"
            :default-index="currentDefaultIndex"
            @confirm="onConfirmCurrent"
            @cancel="showPickerCurrent = false"
          />
        </van-popup>
        <router-link
          class="tx2"
          v-for="(item, index) in intentionList"
          :key="index"
          :to="'/member/personal/resume/intention_edit/' + item.id"
        >
          <div class="name">
            [{{ item.district_text }}] {{ item.category_text }}
          </div>
          <div class="intent">
            {{ item.wage_text }}，{{ item.nature_text }}，{{
            item.trade_text
            }}
          </div>
        </router-link>
      </div>
      <van-button round
                  type="info"
                  v-if="intentionList.length < 3"
                  @click="$router.push('/member/personal/resume/intention_edit/0')"
                  block>
        添加求职意向({{intentionList.length}}/3)
      </van-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'PersonalRecommendAdd',
  data () {
    return {
      current: '',
      currentName: '',
      showPickerCurrent: false,
      currentDefaultIndex: 0
    }
  },
  mounted () {
    this.initInfo()
  },
  computed: {
    basic () {
      return this.$store.state.resume.basic
    },
    intentionList () {
      return this.$store.state.resume.intention_list
    },
    columnsCurrent () {
      return this.$store.state.classifyCurrent
    }
  },
  methods: {
    initInfo () {
      http.get(api.editResume).then(res => {
        if (parseInt(res.code) === 200) {
          // 更新基本资料
          this.$store.dispatch('setBasicInfo', res.data.basic)
          // 更新简历求职意向
          this.$store.dispatch('setIntentionList', res.data.intention_list)
          // 当前求职状态
          this.current = this.basic.current
          this.currentName = this.basic.current_text
        } else {
          this.$notify(res.message)
        }
      })
        .catch(err => {
          console.log(err)
        })
    },
    // 选择求职状态
    onConfirmCurrent (value) {
      this.current = value.id
      this.currentName = value.text
      this.showPickerCurrent = !this.showPickerCurrent
      http
        .post(api.resume_current_save, {
          current: this.current
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({type: 'success', message: '成功修改求职状态'})
            let currentBasic = this.$store.state.resume.basic
            currentBasic.current = this.current
            currentBasic.current_text = this.currentName
            // 更新基本资料
            this.$store.dispatch('setBasicInfo', currentBasic)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  .box_content {
    padding-bottom: 50px;
    .tx2 {
      .intent {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .name {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 9.5px;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 21px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      padding-right: 25px;
      margin-top: 20px;display:block;position: relative;
    }
    .tx1 {
      .right_txt {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #666666;
      }
      &::after {
        position: absolute;
        right: 6px;
        top: 21px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      font-size: 15px;
      color: #333333;
      font-weight: bold;
      padding: 13.5px 0;
      position: relative;
      border-bottom: 1px solid #f5f5f5;
    }
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;
}
.notice_bar {
  width: 100%;
  padding: 11.5px 0 11.5px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
}
</style>
