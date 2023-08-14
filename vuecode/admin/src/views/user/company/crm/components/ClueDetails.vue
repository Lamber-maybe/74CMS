<template>
  <div>
    <div class="box">
      <el-form ref="form" :model="form" label-width="100px" :rules="rules">
        <el-form-item label="线索名称：" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="联系人：" prop="contacts">
          <el-input v-model="form.contacts" />
        </el-form-item>
        <el-form-item label="联系手机：" prop="mobile">
          <el-input v-model="form.mobile" oninput="value=value.replace(/[^0-9.]/g,'')" />
        </el-form-item>
        <el-form-item label="座机：" prop="telephone">
          <el-input v-model="form.telephone" />
        </el-form-item>
        <el-form-item label="联系微信：">
          <el-input v-model="form.weixin" />
        </el-form-item>
        <el-form-item label="公司规模：">
          <el-select v-model="form.scale" placeholder="请选择">
            <el-option v-for="item in scaleData" :key="item.id" :label="item.name" :value="item.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="所属行业：">
          <el-select v-model="form.trade" placeholder="请选择">
            <el-option v-for="item in industryScreenData" :key="item.id" :label="item.name" :value="item.id" />
          </el-select>
        </el-form-item>
        <!--        <el-form-item label="所在地区：">-->
        <!--          <el-cascader-->
        <!--            v-model="form.district"-->
        <!--            :options="regionScreenData"-->
        <!--            :props="{ checkStrictly: true }"-->
        <!--            clearable-->
        <!--          />-->
        <!--        </el-form-item>-->
        <el-form-item label="所在地区" prop="citycategory_arr">
          <el-cascader v-model="form.district" :options="options_citycategory" :show-all-levels="false" />
        </el-form-item>
        <el-form-item label="详细地址：">
          <el-input v-model="form.address" />
        </el-form-item>
        <el-form-item label="备注：">
          <el-input v-model="form.remark" style="width: 558px;" :rows="3" type="textarea" />
        </el-form-item>
        <el-form-item class="e_button">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button type="primary" @click="conversion">转化为客户</el-button>
        </el-form-item>
      </el-form>
    </div>
    <el-dialog
      :visible.sync="sameClientVisibleChild"
      append-to-body
      :title="sameClientTitle"
      :fullscreen="false"
      width="650px"
      @close="closeSameClientVisible"
    >
      <div class="sameLists">
        <div class="sameListTitle">您可以选择将本线索的跟进记录、联系人合并到以下客户：</div>
        <div :class="list.length>3?'sameItemList sameItemListScroll':'sameItemList'">
          <div
            v-for="(item,index) in list"
            :key="index"
            :class="item.select?'sameItem sameItemActive':'sameItem'"
            @click="selectItem(item)"
          >
            <div v-if="item.type==1" class="labelBox">已存在相同手机的客户</div>
            <div v-if="item.type==2" class="labelBox">已存在相同名称的客户</div>
            <div class="labelSelect" />
            <div :class="item.type==2?'selectComName company_name':'company_name'">{{ item.name }} <span
              class="contentTxt"
            >（ID：{{ item.id }}）</span></div>
            <div class="register_time first">
              <span class="time">注册时间：{{ item.addtime }}({{ item.platform }})</span>
              <span class="time">所属销售：{{ item.username }}</span>
            </div>
            <div class="register_time">
              <span class="time">企业手机：<span
                :class="item.company_mobile==form.mobile?'selectTel':''"
              >{{ item.company_mobile }}</span></span>
              <span class="time">会员手机：<span
                :class="item.member_mobile==form.mobile?'selectTel':''"
              >{{ item.member_mobile }}</span><span
                v-if="item.company_contact!=''"
              >({{ item.company_contact }})</span></span>
            </div>
          </div>
        </div>
      </div>
      <div class="btnLists">
        <el-button size="small" @click="toConversion">不合并，继续转化</el-button>
        <el-button size="small" type="primary" @click="toMerge">合并到已选客户</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  classify,
  clueAdd,
  clueDetails,
  clueEdit,
  isNameRepeat
} from '@/api/company_crm'
import {
  validMobile
} from '@/utils/validate'
import {
  verifyRepeatCompany,
  mergeClueToCompany
} from '@/api/directory'

export default {
  name: 'ClueDetails',
  props: ['clue_id', 'details', 'sameClientVisible', 'componeyList', 'comId'],
  data() {
    var validateContactMobile = (rule, value, callback) => {
      if (!validMobile(value)) {
        callback(new Error('请输入正确的手机号'))
      } else {
        callback()
      }
    }
    var validatename = (rule, value, callback) => {
      const param = {
        name: value,
        id: this.clue_id
      }
      isNameRepeat(param).then(response => {
        if (response.data.sys_configs == 0) {
          if (response.data.clue_count > 0) {
            callback(new Error('线索名称已重复'))
          } else {
            callback()
          }
        } else {
          callback()
        }
      })
    }
    return {
      rules: {
        name: [{
          required: true,
          message: '请输入线索名称',
          trigger: 'blur'
        },
        {
          max: 20,
          message: '长度在 0 到 20 个字符',
          trigger: 'blur'
        },
        {
          validator: validatename,
          trigger: 'blur'
        }
        ],
        contacts: [{
          required: true,
          message: '请输入联系人',
          trigger: 'blur'
        },
        {
          max: 6,
          message: '长度在 0 到 6 个字符',
          trigger: 'blur'
        }
        ]
      },
      form: {
        'name': '',
        'contacts': '',
        'mobile': '',
        'weixin': '',
        'scale': '',
        'industry': '',
        'region': '',
        'address': '',
        'trade': '',
        'remark': '',
        'district': [],
        'telephone': ''
      },
      submitLoading: false,
      scaleData: [],
      options_citycategory: [],
      industryScreenData: [],
      sameClientTitle: '检测到已存在相似的客户',
      list: this.componeyList,
      sameClientVisibleChild: this.sameClientVisible,
      company_id: ''
    }
  },
  watch: {
    sameClientVisible(val) {
      this.sameClientVisibleChild = val
    },
    componeyList(val) {
      this.list = val
      this.company_id = this.list[0].id
    },
    details(val){
      for (const key in val) {
        this.form[key] = val[key]
      }
    }
  },
  created() {
    this.classify()
    this.company_id = this.comId
  },
  methods: {
    classify() {
      classify({
        'type': 'citycategory,trade,companyScale'
      }).then(res => {
        this.options_citycategory = [...res.data.citycategory]
        this.options_citycategory = this.options_citycategory.map((item) => {
          if (item.children.length) {
            let level2Array = item.children
            level2Array = level2Array.map((level2Item) => {
              if (level2Item.children.length) {
                return {
                  label: level2Item.label,
                  value: level2Item.value,
                  children: level2Item.children
                }
              } else {
                return {
                  label: level2Item.label,
                  value: level2Item.value
                }
              }
            })
            return {
              value: item.value,
              label: item.label,
              children: level2Array
            }
          } else {
            return {
              value: item.value,
              label: item.label
            }
          }
        })
        this.industryScreenData = res.data.trade
        this.scaleData = res.data.companyScale
      }).catch(() => {

      })
    },
    getVerifyRepeatCompany() {
      verifyRepeatCompany({
        'clue_id': this.clue_id
      }).then(res => {
        if (res.data == '') {
          this.$router.push('/user/company/add?id=' + this.details.id)
        } else {
          this.list = res.data
          for (const i in this.list) {
            this.list[i].select = false
          }
          this.list[0].select = true
          this.company_id = this.list[0].id
          if (this.list.length > 0) {
            this.sameClientVisibleChild = true
          }
        }
      })
    },
    conversion() {
      this.getVerifyRepeatCompany()
    },
    toConversion() {
      this.sameClientVisibleChild = false
      this.$router.push('/user/company/add?id=' + this.details.id)
      // this.$emit('conversion')
    },
    toMerge() {
      if (this.company_id == '') {
        this.$message.error('请选择要合并的客户!')
      } else {
        mergeClueToCompany({
          clue_id: this.clue_id,
          company_id: this.company_id
        }).then(res => {
          this.$message.success(res.message)
          this.sameClientVisibleChild = false
          this.$emit('hideDetail')
        })
      }
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      if (this.mobile == '' && this.telephone == '') {
        this.$message.error('请填写联系手机或座机二选一')
        return false
      }
      this.submitLoading = true
      const that = this
      const insertData = {
        ...this.form
      }
      insertData.clue_id = this.clue_id
      if (insertData.district.length == 0) {
        insertData.district1 = 0
        insertData.district2 = 0
        insertData.district3 = 0
        insertData.district = 0
      }
      if (insertData.district.length == 1) {
        insertData.district1 = insertData.district[0]
        insertData.district = insertData.district[0]
        insertData.district2 = 0
        insertData.district3 = 0
      }
      if (insertData.district.length == 2) {
        insertData.district1 = insertData.district[0]
        insertData.district2 = insertData.district[1]
        insertData.district3 = 0
        insertData.district = insertData.district[1]
      }
      if (insertData.district.length == 3) {
        insertData.district1 = insertData.district[0]
        insertData.district2 = insertData.district[1]
        insertData.district3 = insertData.district[2]
        insertData.district = insertData.district[2]
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          clueEdit(insertData).then(res => {
            if (res.code == 200) {
              this.$message.success(res.message)
              this.$emit('clueDetails')
            } else {
              this.$message.error(res.message)
            }
            that.submitLoading = false
          }).catch(() => {
            that.submitLoading = false
          })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    selectItem(item) {
      for (const i in this.list) {
        this.list[i].select = false
        if (item.unique_id == this.list[i].unique_id) {
          const changeData = this.list[i]
          changeData.select = true
          this.company_id = this.list[i].id
          this.$set(this.list, i, changeData)
        }
      }
    },
    closeSameClientVisible() {
      this.sameClientVisibleChild = false
      this.$emit('changeSameClient', false)
      this.company_id = ''
    }
  }
}
</script>

<style lang="scss" scoped>
  .e_button {
    margin-top: 35px;
  }

  ::v-deep .el-form-item {
    margin-bottom: 20px;
  }

  ::v-deep .el-form-item__label {
    font-size: 14px;
    color: #606266;
    font-weight: normal;
  }

  .step_button {
    display: inline-block;
    margin-left: 20px;

    span {
      color: rgb(30, 136, 229);
      cursor: pointer;
    }
  }

  ::v-deep .el-button {
    width: 130px;
  }

  ::v-deep .el-input {
    display: inline-block;
    width: 100%;
  }

  .box {
    margin: 26px 30px;
    overflow-x: hidden;
    height: calc(100vh - 194px - 52px);
    overflow-y: auto;

    ::v-deep .el-input__inner {
      width: 558px;
    }
  }

  .sameLists {
    .sameListTitle {
      color: #333333;
      font-size: 14px;
      margin-bottom: 12px;
    }

    .sameItemList {
      height: 420px;
      &.sameItemListScroll{
        overflow-y: scroll;
      }

      .sameItem {
        border: 1px solid #E9F4FF;
        position: relative;
        padding: 15px 14px;
        margin-bottom: 15px;
        cursor: pointer;

        .labelBox {
          color: #409EFF;
          font-size: 12px;
          text-align: center;
          width: 145px;
          height: 25px;
          line-height: 25px;
          background: url('../../../../../assets/images/company/crm/sameClientIcon2.png') left center no-repeat;
          background-size: 100% 100%;
          position: absolute;
          right: 0;
          top: 0;
        }

        .labelSelect {
          display: none;
        }

        .company_name {
          color: #333333;
          font-size: 14px;
          margin-bottom: 16px;
          font-weight: bold;

          &.selectComName {
            color: #409EFF;
          }

          .contentTxt {
            color: #999999;
          }
        }

        .register_time {
          color: #999;
          font-size: 14px;

          &.first {
            margin-bottom: 8px;
          }

          .time {
            display: inline-block;
            width: 50%;
          }

          .selectTel {
            color: #409EFF;
          }
        }
      }
      .sameItemActive {
        border: 1px solid #409EFF;

        .labelBox {
          color: #fff;
          font-size: 12px;
          text-align: center;
          width: 145px;
          height: 25px;
          line-height: 25px;
          background: url('../../../../../assets/images/company/crm/sameClientIcon3.png') left center no-repeat;
          background-size: 100% 100%;
          position: absolute;
          right: 0;
          top: 0;
        }

        .labelSelect {
          display: block;
          width: 20px;
          height: 20px;
          background: url('../../../../../assets/images/company/crm/sameClientIcon1.png') left center no-repeat;
          background-size: 100% 100%;
          position: absolute;
          right: 0;
          bottom: 0;
        }
      }
    }
  }

  .btnLists {
    text-align: center;
  }
</style>
