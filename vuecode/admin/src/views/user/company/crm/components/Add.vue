<template>
  <div class="app-container">
    <el-divider content-position="left">新增线索</el-divider>
    <div class="table-s">
      <el-form ref="form" :model="form" label-width="120px" :rules="rules">
        <el-form-item label="线索名称：" prop="name">
          <el-input v-model="form.name" @input="inputChange" />
          <span v-if="verifyNameDescVis">
            <span class="verifyNameDesc">
              <i class="el-icon-info" style="color: #FF0000;" />
              线索名称已存在,
            </span>
            <span class="verifyNameSee" @click="verifyNameSee()">立即查看</span>
          </span>
        </el-form-item>
        <el-form-item v-if="contact_list.length > 0">
          <div :class="contact_list.length > 6 ? 'contactListStyle contactListStyleScroll' : 'contactListStyle'">
            <div
              v-for="(item, index) in contact_list"
              :key="index"
              :class="
                item.inputHover ? 'contactItem contactItemActive' : item.editClass ? 'contactItem editClassStyle' : item.is_main == 1 ? 'contactItem mainClassStyle' : 'contactItem'
              "
              data-id="2"
              @mouseout="handleOut($event)"
              @mouseover="handleOver($event, item)"
            >
              <div v-if="item.is_main == 0" class="contactInfo">
                <span class="toMain" @click="toDoMain(item)">设为主要</span>
                <span class="name">{{ item.contact }}</span>
                <span class="mobile">{{ item.mobile }}</span>
              </div>
              <div v-if="item.is_main == 1" class="contactInfo">
                <span class="toMain isMain">主要</span>
                <span class="name">{{ item.contact }}</span>
                <span class="mobile" v-if="item.mobile != ''">{{ item.mobile }}</span>
                <span class="mobile" v-else>{{ item.telephone }}</span>
              </div>
              <div class="contactIcon">
                <img class="edit editSelect" src="../../../../../assets/images/company/crm/edit.png" @click="editContact(item)">
                <img class="edit editNoSelect" src="../../../../../assets/images/company/crm/editNoselect.png" alt="" @click="editContact(item)">
                <img v-if="!item.editClass && item.is_main == 0" class="close" src="../../../../../assets/images/company/crm/close.png" alt="" @click="delectContact(item)">
                <img v-if="item.editClass && item.is_main == 0" class="close" src="../../../../../assets/images/company/crm/closeSelect.png" alt="" @click="delectContact(item)">
              </div>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="联系人：" prop="contact"><el-input v-model="form.contact" /></el-form-item>
        <el-form-item label="联系电话：" prop="mobile">
          <div style="position: relative;">
            <el-input v-model="form.mobile" oninput="value=value.replace(/[^0-9.]/g,'')" />
            <el-button size="mini" style="margin-left: 10px;" @click="check()">查重</el-button>
          </div>
        </el-form-item>
        <el-form-item label="座机：" prop="telephone"><el-input v-model="form.telephone" /></el-form-item>
        <el-form-item>
          <div v-if="addContactBtn" class="el-input addContact" @click="addContact()">
            <img class="addIcon" src="../../../../../assets/images/company/crm/tianjia.png" alt="">
            添加联系人
          </div>
          <div v-if="!addContactBtn" class="el-input addContact editContactBtn">
            <span class="editBtn" @click="editItemContact()">修改</span>
            <span class="cancelBtn" @click="cancelEditItemContact()">取消</span>
          </div>
        </el-form-item>
        <el-form-item label="联系微信："><el-input v-model="form.weixin" /></el-form-item>
        <el-form-item label="所在地区" prop="citycategory_arr"><el-cascader v-model="form.district" :options="options_citycategory" :show-all-levels="false" /></el-form-item>
        <!--        <el-form-item label="所在地区：">-->
        <!--          <el-cascader-->
        <!--            v-model="form.district"-->
        <!--            :options="regionScreenData"-->
        <!--            :props="{ checkStrictly: true }"-->
        <!--            clearable-->
        <!--          />-->
        <!--        </el-form-item>-->
        <el-form-item label="详细地址："><el-input v-model="form.address" /></el-form-item>
        <el-form-item label="备注"><el-input v-model="form.remark" /></el-form-item>
        <el-form-item label="企业规模">
          <el-select v-model="form.scale" placeholder="请选择"><el-option v-for="item in scaleData" :key="item.id" :label="item.name" :value="item.id" /></el-select>
        </el-form-item>
        <el-form-item label="客户行业">
          <el-select v-model="form.trade" placeholder="请选择"><el-option v-for="item in industryScreenData" :key="item.id" :label="item.name" :value="item.id" /></el-select>
        </el-form-item>
        <el-form-item label="所属销售">
          <el-radio-group v-model="form.sale">
            <el-radio label="1">我的线索</el-radio>
            <el-radio label="0">公共线索</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item><el-button type="primary" :loading="submitLoading" @click="onSubmit('form')">立即创建</el-button></el-form-item>
      </el-form>
    </div>
    <!-- 线索名称重复 -->
    <el-dialog :visible.sync="checkResultVisbile" width="30%" :before-close="callHandleClose">
      <div :class="clueVisList.length > 5 ? 'checkResult checkResultScroll' : 'checkResult'">
        <div class="checkResultItem">
          <div class="checkResultItemMarginList">
            <div v-for="(item, index) in clueVisList" :key="index" class="checkResultItemMargin">
              <div v-if="item.type == 1" class="checkTitle">已有同名线索</div>
              <div v-if="item.type == 2" class="checkTitle">已有同名客户</div>
              <div v-if="item.type == 1" class="checkName">线索名称：{{ item.name }}（ID：{{ item.id }}）</div>
              <div v-if="item.type == 2" class="checkName">客户名称：{{ item.name }}（ID：{{ item.id }}）</div>
              <div v-if="item.username != ''" class="checkPeo">所属销售：{{ item.username }}</div>
              <div v-else class="checkPeo">所属销售：暂未分配</div>
            </div>
          </div>
          <div style="text-align: right;"><el-button type="primary" size="small" @click="callHandleClose">确定</el-button></div>
        </div>
      </div>
    </el-dialog>
    <!-- 手机号查重 -->
    <el-dialog :visible.sync="checkContactResultVisbile" width="550px" :before-close="callHandleClose">
      <div class="checkResult checkContactResult">
        <div class="checkResultItem">
          <div v-for="(item, index) in contactVisList" :key="index" class="checkResultItemMargin">
            <div v-if="item.type == 1" class="checkTitle">同手机号线索</div>
            <div v-if="item.type == 2" class="checkTitle">同手机号客户</div>
            <div class="checkName" v-if="item.type == 1">线索名称：{{ item.name }}（ID：{{ item.id }}）</div>
            <div class="checkName" v-if="item.type == 2">客户名称：{{ item.name }}（ID：{{ item.id }}）</div>
            <div class="checkPeo">所属销售：{{ item.username || '暂未分配' }}</div>
          </div>
          <div style="text-align: right;"><el-button type="primary" size="small" @click="callHandleClose">确定</el-button></div>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { classify, clueAdd, isNameRepeat } from '@/api/company_crm'
import { verifyRepeatClueName, verifyRepeatMobile } from '@/api/directory'
import { validMobile } from '@/utils/validate'
import { memberCheckUnique } from '@/api/member'

export default {
  name: 'Add',
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
        id: 0
      }
      isNameRepeat(param).then(response => {
        if (response.data.sys_configs == 0) {
          if (response.data.clue_count > 0) {
            callback(new Error('系统监测到已存在同名的线索，请确认是否重复'))
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
        name: [
          {
            required: true,
            message: '请输入线索名称',
            trigger: 'blur'
          },
          {
            max: 20,
            message: '长度在 1 到 20 个汉字',
            trigger: 'blur'
          },
          {
            validator: validatename,
            trigger: 'blur'
          }
        ]
      },
      submitLoading: false,
      form: {
        sale: '1',
        name: '',
        contact: '',
        mobile: '',
        weixin: '',
        district: '',
        address: '',
        remark: '',
        trade: '',
        company_size: '',
        telephone: ''
      },
      scaleData: [],
      options_citycategory: [],
      industryScreenData: [],
      checkResultVisbile: false,
      checkContactResultVisbile: false,
      contactVisList: [],
      verifyNameDescVis: false,
      clueVisList: [],
      contact_list: [],
      addContactBtn: true,
      currentContactIndex: 0,
      editContactModule: false
    }
  },
  created() {
    this.classify()
  },
  methods: {
    contains(arr, val) {
      for (var i = 0; i < arr.length; i++) {
        if (arr[i] === val) {
          return true
        }
      }
      return false
    },
    onSubmit(formName) {
      /*
       * 【bug】出现提示后，添加联系人无法保存
       * 【bug】添加线索重复点击问题
       * zch 2022.12.12
       * */
      this.submitLoading = true
      const that = this
      const insertData = {
        ...this.form
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (this.contact_list.length == 0) {
            this.submitLoading = false
            this.$message.error('请添加联系人！')
          } else {
            if (this.form.contact != '' && this.form.mobile != '' && !this.editContactModule) {
              const obj = {
                contact: this.form.contact,
                mobile: this.form.mobile,
                inputHover: false,
                editClass: false,
                is_main: 0
              }
              this.contact_list.push(obj)
            }
            insertData.contact_list = this.contact_list
            clueAdd(insertData)
              .then(res => {
                if (res.code == 200) {
                  this.$message.success(res.message)
                  if (this.form.sale == '0') {
                    setTimeout(function() {
                      that.$router.push('/user/company/crm/internationalWaters')
                    }, 1500)
                  } else {
                    setTimeout(function() {
                      that.$router.push('/user/company/crm/my')
                    }, 1500)
                  }
                } else {
                  this.$message.error(res.message)
                  that.submitLoading = false
                }
              })
              .catch(() => {
                that.submitLoading = false
              })
          }
        } else {
          this.isSubmit = false
          this.submitLoading = false
          return false
        }
      })
    },
    classify() {
      classify({
        type: 'citycategory,trade,companyScale'
      })
        .then(res => {
          this.options_citycategory = [...res.data.citycategory]
          this.options_citycategory = this.options_citycategory.map(item => {
            if (item.children.length) {
              let level2Array = item.children
              level2Array = level2Array.map(level2Item => {
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
        })
        .catch(() => {})
    },
    check() {
      if (this.form.mobile.length == 11) {
        const params = {
          mobile: this.form.mobile
        }
        verifyRepeatMobile(params).then(res => {
          if (res.code == 200) {
            if (res.data.length > 0) {
              this.contactVisList = res.data
              this.checkContactResultVisbile = true
            } else {
              this.$message.success('未查到重复的电话！')
            }
          }
        })
      } else {
        this.$message.error('请输入正确的手机号！')
      }
    },
    closeContact() {
      this.checkContactResultVisbile = false
    },
    handleOver(e, item) {
      if (!this.contact_list.some(item => item.editClass == true)) {
        for (const i in this.contact_list) {
          if (!this.contact_list[i].editClass && this.contact_list[i].is_main == 0) {
            this.contact_list[i].inputHover = false
            if (item.mobile == this.contact_list[i].mobile) {
              this.contact_list[i].inputHover = true
            }
          }
        }
      }
    },
    editContact(item) {
      this.form.contact = item.contact
      this.form.mobile = item.mobile
      this.form.telephone = item.telephone
      this.addContactBtn = false
      this.editContactModule = true
      for (const i in this.contact_list) {
        this.contact_list[i].editClass = false
        if (item.mobile == this.contact_list[i].mobile) {
          this.contact_list[i].editClass = true
          this.currentContactIndex = i
        }
      }
    },
    cancelEditItemContact() {
      this.form.contact = ''
      this.form.mobile = ''
      this.form.telephone = ''
      this.addContactBtn = true
      this.editContactModule = false
      for (const i in this.contact_list) {
        this.contact_list[i].editClass = false
      }
    },
    editItemContact() {
      for (const i in this.contact_list) {
        this.contact_list[i].editClass = false
        this.contact_list[i].inputHover = false
        if (this.currentContactIndex == i) {
          this.contact_list[i].contact = this.form.contact
          this.contact_list[i].mobile = this.form.mobile
          this.contact_list[i].telephone = this.form.telephone
        }
      }
      this.form.contact = ''
      this.form.mobile = ''
      this.form.telephone = ''
      this.addContactBtn = true
      this.editContactModule = false
    },
    delectContact(item) {
      this.$confirm('确定删除此联系人吗？', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        for (const i in this.contact_list) {
          if (item.mobile == this.contact_list[i].mobile) {
            this.contact_list.splice(i, 1)
          }
        }
        this.form.contact = ''
        this.form.mobile = ''
      }).catch(() => {
      })
    },
    handleOut() {
      for (const i in this.contact_list) {
        this.contact_list[i].inputHover = false
      }
    },
    // 校验相同线索
    inputChange(e) {
      const params = {
        clue_name: e,
        is_result: 0
      }
      if (params.clue_name != '') {
        verifyRepeatClueName(params).then(res => {
          if (res.code == 200) {
            if (res.data.is_repeat == 1) {
              this.verifyNameDescVis = true
            } else if (res.data == '') {
              this.verifyNameDescVis = false
              this.checkResultVisbile = false
            }
          }
        })
      }
    },
    // 查看同名线索
    verifyNameSee() {
      const params = {
        clue_name: this.form.name,
        is_result: 1
      }
      verifyRepeatClueName(params).then(res => {
        if (res.code == 200) {
          this.checkResultVisbile = true
          this.clueVisList = res.data
        } else {
          this.$message.error(res.message)
        }
      })
    },
    // 添加联系人
    addContact() {
      if (this.form.contact == '') {
        this.$message.error('请输入联系人姓名！')
      } else if (this.form.contact.length > 6) {
        this.$message.error('联系人姓名长度在 1 到 6 个汉字！')
      } else if (this.form.mobile == '' && this.form.telephone == '') {
        this.$message.error('请输入联系人电话或座机二选一！')
      } else if (this.form.mobile != '' && this.form.mobile.length != 11) {
        this.$message.error('请输入正确的电话格式！')
      } else {
        for (const i in this.contact_list) {
          if (this.contact_list[i].mobile == this.form.mobile) {
            this.$message.error('联系人列表已有此电话！')
            return
          }
        }
        const obj = {
          contact: this.form.contact,
          mobile: this.form.mobile,
          telephone: this.form.telephone,
          inputHover: false,
          editClass: false,
          is_main: 0
        }
        console.log(obj)
        if (this.contact_list.length == 0) {
          obj.is_main = 1
        }
        this.contact_list.push(obj)
        this.form.contact = ''
        this.form.mobile = ''
        this.form.telephone = ''
      }
    },
    // 设为主要联系人
    toDoMain(item) {
      let obj = {}
      for (const i in this.contact_list) {
        this.contact_list[i].is_main = 0
        if (this.contact_list[i].mobile == item.mobile) {
          this.contact_list[i].is_main = 1
          obj = this.contact_list[i]
          this.contact_list.splice(i, 1)
        }
      }
      this.contact_list.unshift(obj)
    },
    callHandleClose() {
      this.checkResultVisbile = false
      this.checkContactResultVisbile = false
    }
  }
}
</script>

<style scoped lang="scss">
.table-s {
  margin-left: 50px;

  .verifyNameDesc {
    color: #ff0000;
    font-size: 14px;
    margin: 0 10px;
  }

  .verifyNameSee {
    display: inline-block;
    color: #409eff;
    font-size: 14px;
    height: 23px;
    line-height: 23px;
    cursor: pointer;
  }

  .addContact {
    text-align: center;
    color: #419eff;
    border: 1px dashed #419eff;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    cursor: pointer;

    .addIcon {
      display: inline-block;
      width: 14px;
      height: 14px;
      margin-right: 8px;
    }
  }

  .checkContactResult {
    right: -290px;
  }

  .contactListStyle {
    width: 479px;
    background: #ffffff;
    box-shadow: 0px 2px 4px 0px rgba(222, 222, 222, 0.35);
    border-radius: 6px;
    padding: 10px 20px;
    &.contactListStyleScroll {
      overflow-y: scroll;
      max-height: 244px;
    }

    .contactItem {
      width: 397px;
      height: 33px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 14px;
      border: 1px solid #ebeef5;
      border-radius: 4px;
      box-sizing: border-box;
      padding: 0 15px 0 10px;
      margin-bottom: 13px;
      cursor: pointer;

      &.contactItemActive {
        background: #fff7f3;
      }

      &:last-child {
        margin-bottom: 0;
      }

      .contactInfo {
        color: #5b5d62;
        height: 100%;
        line-height: 33px;
        width: 90%;

        .mobile {
          position: relative;
          margin-left: 10px;
          padding-left: 10px;

          &:before {
            content: '';
            display: inline-block;
            width: 1px;
            height: 14px;
            background: #5b5d62;
            position: absolute;
            top: 1px;
            left: 0;
          }
        }

        .toMain {
          // display: inline-block;
          width: 60px;
          height: 17px;
          line-height: 17px;
          text-align: center;
          color: #ff732e;
          background: rgba(255, 115, 46, 0.2);
          border-radius: 4px;
          font-size: 12px;
          margin-right: 10px;
          display: none;
        }

        .isMain {
          background: #ff732e;
          color: #fff;
          width: 36px;
        }
      }

      .contactIcon {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: end;
        width: 10%;

        .edit,
        .close {
          display: inline-block;
          width: 18px;
          height: 18px;
          cursor: pointer;
        }

        .edit {
          // margin-right: 14px;
          width: 14px;
          height: 14px;
          display: none;
          z-index: 999;
        }
        .close {
          margin-left: 14px;
        }
      }
    }

    .contactItemActive {
      .contactInfo {
        .toMain {
          display: inline-block;
        }
      }

      .contactIcon {
        .editNoSelect {
          display: none;
        }
        .editSelect {
          display: inline-block;
          z-index: 999;
          // margin-right: 14px;
        }
      }
    }

    .editClassStyle {
      border: 1px solid #ff732e;

      .contactInfo {
        .name,
        .mobile {
          color: #ff732e;

          &:before {
            background: #ff732e;
          }
        }
      }

      .contactIcon {
        .editSelect {
          display: inline-block;
          z-index: 999;
          // margin-right: 14px;
        }
        .editNoSelect {
          display: none;
        }
      }
    }

    .mainClassStyle {
      .contactInfo {
        .toMain {
          display: inline-block;
        }
      }

      .contactIcon {
        .editNoSelect {
          display: inline-block;
          z-index: 999;
          // margin-right: 3px;
        }
        .editSelect {
          display: none;
        }
      }
    }
  }

  .editContactBtn {
    font-size: 14px;
    border: none;
    justify-content: right;

    .editBtn {
      border: 1px solid #ff732e;
      color: #ff732e;
      background: #fef4f0;
      width: 70px;
      border-radius: 4px;
    }

    .cancelBtn {
      border: 1px dashed #f6f7f9;
      color: #999999;
      background: #f8f9fa;
      width: 70px;
      border-radius: 4px;
      margin-left: 10px;
    }
  }
}

.checkResult {
  background: #ffffff;
  box-sizing: border-box;
  padding: 15px 23px 0;
  border-radius: 4px;
  &.checkResultScroll {
    max-height: 500px;
    overflow-y: scroll;
  }

  .checkResultItem {
    color: #333333;

    .checkTitle {
      font-size: 16px;
      height: 28px;
    }

    .checkName,
    .checkPeo {
      font-size: 14px;
      height: 25px;
    }

    .closeIconNew {
      position: absolute;
      right: 20px;
      top: 20px;
      cursor: pointer;
    }

    .checkResultItemMargin {
      margin-bottom: 25px;
    }
  }

  .hoverClass {
    color: red;
  }
}

.displayBlock {
  display: block;
}

.displayNone {
  display: none;
}

::v-deep .el-input {
  width: 450px;
}
</style>
