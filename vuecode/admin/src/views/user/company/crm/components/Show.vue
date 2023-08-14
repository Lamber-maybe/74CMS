<template>
  <div>
    <div v-loading="loading" class="popup_box">
      <!-- 基本信息 -->
      <div class="base">
        <div class="info">
          <div class="title">
            <div class="name">
              {{ details.name ? details.name : '-' }}
            </div>

            <div class="user">
              （<span>线索ID：
                {{ details.id ? details.id : '-' }}；
              </span>
              <span class="logoin" @click="conversion">转化为客户</span>）
            </div>
            <div class="users" style="margin-left: 30px;">
              转为客户将自动注册企业会员
            </div>
          </div>
          <div class="details">
            <el-row class="line">
              <el-col :span="8">
                <label class="line_label">所属销售：</label>
                <span class="text">
                  {{ details.admin_username ? details.admin_username : '-' }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">跟进记录：</label>
                <span class="text">
                  {{ details.visit_count }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">最后跟进：</label>
                <span class="text">
                  {{ details.last_visit_time ? details.last_visit_time : '-' }}
                </span>
              </el-col>
            </el-row>
            <el-row class="line">
              <el-col :span="8">
                <label class="line_label">所在地区：</label>
                <span class="text">
                  {{ details.district }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">创建人：</label>
                <span class="text">
                  {{ details.creat_username ? details.creat_username : '-' }}
                </span>
              </el-col>
              <el-col :span="8">
                <label class="line_label">创建时间：</label>
                <span class="text">
                  {{ details.createtime ? details.createtime : '-' }}
                </span>
              </el-col>
            </el-row>
          </div>
        </div>
      </div>
      <!-- 切换 -->
      <div class="tab_box">
        <el-tabs v-model="activeName" @tab-click="handleClick">
          <el-tab-pane label="跟进记录" name="first">
            <followUpRecord
              :clue_id="rowId"
              :contacts="details.contacts"
              :mobile="details.mobile"
              @clueDetails="clueDetails"
            />
          </el-tab-pane>
          <el-tab-pane label="线索详情" name="second">
            <clueDetailsEdit
              v-if="activeName == 'second'"
              :clue_id="rowId"
              :same-client-visible="sameClientVisible"
              :componey-list="componeyList"
              :com-id="comId"
              :details="details"
              @changeSameClient="changeSameClient"
              @clueDetails="clueDetails"
              @conversion="conversion"
              @hideDetail="hideDetail"
            />
          </el-tab-pane>
          <el-tab-pane label="联系人" name="third">
            <clueContact
              v-if="activeName == 'third'"
              :clue_id="rowId"
              :details="details"
              @clueDetails="clueDetails"
              @conversion="conversion"
            />
          </el-tab-pane>
        </el-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import followUpRecord from '@/views/user/company/crm/components/FollowUpRecord'
import clueDetailsEdit from '@/views/user/company/crm/components/ClueDetails'
import clueContact from '@/views/user/company/crm/components/clueContact'
import {
  clueDetails
} from '@/api/company_crm'
import {
  verifyRepeatCompany,
  mergeClueToCompany
} from '@/api/directory'
export default {
  name: 'Show',
  components: {
    clueDetailsEdit,
    followUpRecord,
    clueContact
  },
  props: {
    rowId: {
      default: ''
    }
  },
  data() {
    return {
      details: [],
      activeName: 'first',
      loading: false,
      sameClientVisible: false,
      componeyList: [],
      comId: 0
    }
  },
  created() {
    this.clueDetails()
  },
  methods: {
    getVerifyRepeatCompany() {
      verifyRepeatCompany({
        'clue_id': this.rowId
      }).then(res => {
        if (res.data == '') {
          this.$router.push('/user/company/add?id=' + this.details.id)
        } else {
          this.activeName = 'second'
          this.sameClientVisible = true
          this.componeyList = res.data
          this.componeyList[0].select = true
          this.comId = this.componeyList[0].id
        }
      })
    },
    conversion() {
      this.getVerifyRepeatCompany()
    },
    hideDetail(){
      this.$emit('hideDetail')
    },
    clueDetails() {
      this.loading = true
      clueDetails({
        'clue_id': this.rowId
      }).then(res => {
        this.details = res.data
        var citycategory_arr = []
        if (res.data.district1 != 0) {
          citycategory_arr.push(res.data.district1)
        }
        if (res.data.district2 != 0) {
          citycategory_arr.push(res.data.district2)
        }
        if (res.data.district3 != 0) {
          citycategory_arr.push(res.data.district3)
        }
        this.details.citycategory_arr = citycategory_arr
        this.details.citycategory_arr = citycategory_arr
        this.loading = false
      }).catch(() => {

      })
    },
    changeSameClient(val) {
      this.sameClientVisible = false
    },
    handleClick() {
      if (this.activeName == 'first' || this.activeName == 'second') {
        this.clueDetails()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  ::v-deep .el-tabs__header {
    margin: 0px;
  }

  ::v-deep .el-tabs__nav-scroll {
    line-height: 50px;
    padding-left: 30px;
  }

  ::v-deep .el-tabs__nav-wrap {
    background: #fcfcfc;
    border-top: 1px solid #ebeef5;
    border-bottom: 1px solid #ebeef5;
  }

  ::v-deep .el-tabs__nav-wrap::after {
    background-color: transparent
  }

  //弹窗
  .popup_box {
    height: 100%;

    // 基本信息
    .base {
      padding: 20px 30px;
      display: flex;

      .logo {
        width: 118px;
        height: 118px;
        margin-right: 20px;
        background-color: #ebeef5;
        border: 1px solid #ebeef5;
        padding: 20px;

        img {
          width: 100%;
          height: 100%;
        }
      }

      .info {
        flex: 1;

        .title {
          padding: 5px 0 18px;
          display: flex;

          .name {
            font-size: 20px;
            height: 20px;
            color: #303033;
            font-weight: 600;
            margin-right: 10px;
          }

          .users {
            padding-top: 6px;
            font-size: 12px;
            font-weight: 400;
            line-height: 14px;
            color: #c5c0c0;

            .logoin {
              color: #0078ff;
              cursor: pointer;
            }
          }

          .user {
            padding-top: 6px;
            font-size: 14px;
            font-weight: 400;
            line-height: 14px;
            color: #777;

            .logoin {
              color: #0078ff;
              cursor: pointer;
            }
          }
        }

        .details {
          max-width: 100%;

          .line {
            margin-bottom: 8px;

            .line_label {
              color: #777;
              font-size: 14px;
              line-height: 14px;
              font-weight: normal;
            }

            .text {
              font-size: 14px;
              line-height: 14px;
            }
          }
        }
      }

      .details {
        .invite_text {
          color: #1787fb;
        }
      }
    }

    .tab_box {
      position: relative;
    }

    .qcc_box {
      width: 63px;
      height: 20px;
      position: absolute;
      right: 30px;
      top: -35px;
      z-index: 30;
      cursor: pointer;

      img {
        width: 100%;
        height: 100%;
      }
    }

    .job_sum {
      color: #f56c6c;
    }

    .close {
      width: 40px;
      height: 60px;
      position: absolute;
      left: -40px;
      top: 50%;
      transform: translateY(-50%);
      background: #f5f5f5;
      border-radius: 8px 0 0 8px;
      cursor: pointer;

      .el-icon-close {
        font-size: 30px;
        color: #777;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
      }
    }
  }

  // 邀请弹窗
  ::v-deep .el-dialog__body {
    padding: 1px 0;
  }

  .code_wrapper {
    text-align: center;

    .code_img {
      width: 256px;
      height: 256px;
    }

    .code_text {
      font-size: 12px;
      color: #979797;
    }

    .tip {
      font-size: 14px;
      font-weight: 300;
      color: #f56c6c;
      background-color: #fef0f0;
      margin: 20px auto;
      padding: 10px 20px;
      text-align: center;
    }
  }
</style>
