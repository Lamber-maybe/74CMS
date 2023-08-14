<template>
  <div>
    <div class="box">
      <div v-for="(item,index) in list" :key="index" class="box-item">
        <div class="box-item-name">
          <span v-if="item.contact!=''" class="name">{{ item.contact }}</span>
          <span v-else class="name">未知联系人</span>
          <span v-if="item.is_main==1" class="label">主要</span>
        </div>
        <div class="box-item-tel">
          <span class="box-item-telnum">{{ item.mobile || '-' }}</span>
          <span class="box-item-tel-icon" />
        </div>
        <div class="box-item-contact-other">
          <div>座机：{{ item.telephone }}</div>
          <div>QQ：{{ item.qq }}</div>
          <div>邮箱：{{ item.email }}</div>
        </div>
        <div class="oprations-list">
          <span v-if="item.is_main==0" class="opration1" @click="doSomeThing('main',item)">设为主要</span>
          <span v-else />
          <div class="opration2">
            <span class="edit" @click="edit('edit',item)">修改</span>
            <span v-if="item.is_main==0" class="del" @click="doSomeThing('delete',item)">删除</span>
          </div>
        </div>
      </div>
      <div class="box-item" @click="addContact('add')">
        <div class="add-btn">
          <!-- <span class="add-icon">+</span> -->
          <i class="el-icon-circle-plus" style="color: rgb(37, 177, 73);margin-right: 3px;" />
          <span class="add-text">添加联系人</span>
        </div>
      </div>

    </div>
    <el-dialog :visible.sync="addContactVisible" append-to-body :title="title" :fullscreen="false" width="45%">
      <el-form ref="form" class="form-box" :model="form" label-width="100px" :rules="rules">
        <el-form-item class="form-item" label="姓名：" prop="contact">
          <el-input v-model="form.contact" placeholder="请输入联系人姓名" />
        </el-form-item>
        <el-form-item label="性别">
          <el-radio-group v-model="form.sex">
            <el-radio :label="0" value="0">男</el-radio>
            <el-radio :label="1" value="1">女</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item class="form-item" label="手机号：" prop="mobile">
          <el-input v-model="form.mobile" placeholder="请输入联系人手机号" oninput="value=value.replace(/[^0-9.]/g,'')" />
        </el-form-item>
        <el-form-item class="form-item" label="公司座机：" prop="telephone">
          <el-input v-model="form.telephone" placeholder="请输入联系人座机号" />
        </el-form-item>
        <el-form-item class="form-item" label="邮箱：" prop="email">
          <el-input v-model="form.email" placeholder="请输入联系人邮箱" />
        </el-form-item>
        <el-form-item class="form-item" label="QQ：" prop="qq">
          <el-input v-model="form.qq" placeholder="请输入联系人QQ" />
        </el-form-item>
        <el-form-item class="e_button">
          <el-button type="primary" :loading="submitLoading" @click="onSubmit('form')">保存</el-button>
          <el-button @click="reset">重置</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import {
  getContactList,
  setAsMainContact,
  clueAddContact,
  clueEditContact,
  deleteContact
} from '@/api/directory'
import {
  validMobile
} from '@/utils/validate'

export default {
  name: 'ClueDetails',
  props: ['clue_id', 'details'],
  data() {
    // var validateContactMobile = (rule, value, callback) => {
    //   if (!validMobile(value)) {
    //     callback(new Error('请输入正确的手机号'))
    //   } else {
    //     callback()
    //   }
    // }
    return {
      rules: {
        contact: [{
          required: true,
          message: '请输入联系人姓名',
          trigger: 'blur'
        }]
        // mobile: [{
        //     required: true,
        //     message: '请输入联系电话',
        //     trigger: 'blur'
        //   },
        //   {
        //     validator: validateContactMobile,
        //     trigger: 'blur'
        //   }
        // ]
      },
      form: {
        'contact': '',
        'sex': '',
        'mobile': '',
        'telephone': '',
        'email': '',
        'qq': ''
      },
      submitLoading: false,
      scaleData: [],
      options_citycategory: [],
      industryScreenData: [],
      list: [],
      addContactVisible: false,
      title: '添加联系人',
      oprationVal: 'add'
    }
  },
  created() {
    this.getContactList()
  },
  methods: {
    getContactList() {
      getContactList({
        clue_id: this.clue_id
      }).then(res => {
        if (res.code == 200) {
          this.list = res.data
          this.copyList = res.data
        } else {
          this.$message.error(res.message)
        }
      })
    },
    addContact(type) {
      this.oprationVal = type
      this.form = {
        'contact': '',
        'sex': '',
        'mobile': '',
        'telephone': '',
        'email': '',
        'qq': ''
      }
      this.addContactVisible = true
    },
    edit(type, item) {
      this.oprationVal = type
      this.form = item
      this.addContactVisible = true
    },
    doSomeThing(type, item) {
      const valTxt = type == 'main' ? '确定将此联系人设为主要联系人吗？' : '确定删除该联系人吗？'
      const cancelTxt = type == 'main' ? '已取消设为主要' : '已取消删除'
      const params = {
        clue_id: this.clue_id,
        contact_id: item.contact_id
      }
      this.$confirm(valTxt, '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        if (type == 'main') {
          setAsMainContact(params).then(res => {
            this.$message.success(res.message)
            this.getContactList()
          }).catch(() => {

          })
        } else if (type == 'delete') {
          deleteContact(params).then(res => {
            this.$message.success(res.message)
            this.getContactList()
          }).catch(() => {

          })
        }
      }).catch(() => {
        this.$message({
          type: 'info',
          message: cancelTxt
        })
      })
    },
    conversion() {
      this.$emit('conversion')
    },
    reset() {
      if (this.oprationVal == 'add') {
        this.form = {
          'contact': '',
          'sex': '',
          'mobile': '',
          'telephone': '',
          'email': '',
          'qq': ''
        }
      } else if (this.oprationVal == 'edit') {
        this.form = {
          'contact': '',
          'sex': '',
          'mobile': '',
          'telephone': '',
          'email': '',
          'qq': ''
        }
      }
    },
    onSubmit(formName) {
      const oprationVal = this.oprationVal
      if (this.submitLoading === true) {
        return false
      }
      if (!this.form.mobile && !this.form.telephone) {
        this.$message.error('请填写手机号或公司座机二选一')
        return false
      }
      this.submitLoading = true
      const insertData = {
        ...this.form
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.clue_id = this.clue_id
          if (oprationVal == 'add') {
            clueAddContact(insertData).then(res => {
              if (res.code == 200) {
                this.addContactVisible = false
                this.$message.success(res.message)
                this.form = {
                  'contact': '',
                  'sex': '',
                  'mobile': '',
                  'telephone': '',
                  'email': '',
                  'qq': ''
                }
                this.getContactList()
              } else {
                this.$message.error(res.message)
              }
              this.submitLoading = false
            }).catch(() => {
              this.submitLoading = false
            })
          } else if (oprationVal == 'edit') {
            clueEditContact(insertData).then(res => {
              if (res.code == 200) {
                this.addContactVisible = false
                this.$message.success(res.message)
                this.form = {
                  'contact': '',
                  'sex': '',
                  'mobile': '',
                  'telephone': '',
                  'email': '',
                  'qq': ''
                }
                this.getContactList()
              } else {
                this.$message.error(res.message)
              }
              this.submitLoading = false
            }).catch(() => {
              this.submitLoading = false
            })
          }
        } else {
          this.submitLoading = false
          return false
        }
      })
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

    .box-item {
      width: 320px;
      border-radius: 6px;
      border: 1px solid #e6eaf2;
      float: left;
      min-height: 225px;
      margin-bottom: 15px;
      margin-right: 15px;

      &:last-child {
        cursor: pointer;
      }

      &:first-child {
        cursor: pointer;
      }

      .box-item-name {
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-sizing: border-box;
        padding: 25px 20px 13px;
        min-height: 60px;

        .name {
          font-size: 18px;
          color: #333333;
        }

        .label {
          color: #fff;
          background: #FF6000;
          border-radius: 10px;
          text-align: center;
          line-height: 20px;
          height: 20px;
          width: 50px;
          font-size: 13px;
        }
      }

      .oprations-list {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgb(240, 240, 240);
        font-size: 13px;
        padding: 15px 20px 13px;
        height: 58px;

        .opration1 {
          display: inline-block;
          width: 75px;
          height: 30px;
          line-height: 30px;
          text-align: center;
          border-radius: 4px;
          color: #409EFF;
          border: 1px solid #409EFF;
          cursor: pointer;
        }

        .opration2 {

          .edit {
            background: url('../../../../../assets/images/outbound/edit-contact.png') left center no-repeat;
            background-size: 13px 13px;
            height: 13px;
            padding-left: 20px;
            cursor: pointer;
          }

          .del {
            background: url('../../../../../assets/images/outbound/del-contact.png') left center no-repeat;
            background-size: 13px 13px;
            height: 13px;
            padding-left: 20px;
            margin-left: 26px;
            cursor: pointer;
          }
        }
      }

      .box-item-tel {
        color: #252428;
        font-size: 13px;
        padding: 0 0 6px;
        border-bottom: 1px dashed #f0f0f0;
        width: calc(100% - 40px);
        margin: 0 auto;

        .box-item-telnum {
          background: url('../../../../../assets/images/outbound/dial.png') right center no-repeat;
          background-size: 12px 12px;
          padding-right: 18px;
          font-weight: bold;
        }
      }

      .box-item-contact-other {
        color: rgb(110, 110, 110);
        font-size: 14px;
        padding: 13px 20px;

        div {
          margin-bottom: 3px;
        }
      }

      .add-btn {
        width: 100%;
        margin: 30% auto 0;
        display: flex;
        align-items: center;
        justify-content: center;

        .add-icon {
          background: rgb(37, 177, 73);
          width: 13px;
          height: 13px;
          display: inline-block;
          border-radius: 50%;
          color: #fff;
          font-size: 12px;
          text-align: center;
          margin-right: 10px;
        }

        .add-text {
          font-size: 14px;
        }
      }
    }
  }

  .form-box {
    width: 80%;
    margin: 30px auto;

    .form-item {
      margin-bottom: 30px;
    }
  }
</style>
