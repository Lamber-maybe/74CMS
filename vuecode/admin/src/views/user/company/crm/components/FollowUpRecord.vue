<template>
  <div v-loading="loading" class="followup_wrapper">
    <el-row>
      <el-col class="write" :span="12">
        <div class="info">
          <el-form ref="followUp" label-width="100px" :rules="rules" :model="followUp">
            <el-form-item class="label_title" label="跟进方式：" prop="mode">
              <el-select v-model="followUp.mode" :disabled="disabled_state">
                <el-option v-for="(item,index) in visitStatusList" :key="index" :label="item.label" :value="item.value" />
              </el-select>
            </el-form-item>
            <el-form-item label="联系人：">
              <el-select v-if="currentNav == 'allClient' || currentNav == 'myClient' || currentNav == 'pubilceClient' || currentNav == 'wholeClue' || currentNav == 'wholeMy' || currentNav == 'wholeInternationalWaters'" v-model="followUp.contacts" placeholder="请选择" :disabled="disabled_state">
                <el-option
                  v-for="item in contactsData"
                  :key="item.value"
                  :label="item.name"
                  :value="item.value"
                  @click.native="contactsClick(item)"
                />
              </el-select>
              <el-input
                v-else
                :value="followUp.link_man+' | '+ followUp.link_mobile"
                placeholder="请输入内容"
                :disabled="true"
              />
            </el-form-item>
            <el-form-item label="下次跟进：">
              <el-date-picker
                v-model="followUp.next_time"
                :disabled="disabled_state"
                :picker-options="pickerOptions"
                style="width: 100%;"
                type="datetime"
                placeholder="选择日期时间"
              />
            </el-form-item>
            <el-form-item label="跟进结果：" prop="result">
              <el-input v-model="followUp.result" :rows="11" type="textarea" :disabled="disabled_state" maxlength="250" show-word-limit />
            </el-form-item>
            <el-form-item label="添加图片：">
              <el-upload
                ref="uploadFile"
                :disabled="disabled_state"
                :headers="headerObj"
                :on-remove="(file, fileList)=>{removeImg(file, fileList)}"
                class="upload-demo"
                drag
                :action="upload_url"
                multiple
                :on-success="handleAvatarSuccess"
                :before-upload="beforeAvatarUpload"
              >
                <i class="el-icon-upload" />
                <div class="el-upload__text">将图片拖到此处，或<em>点击上传</em></div>
                <div class="el-upload__text">图片上传仅支持jpg/png格式</div>
              </el-upload>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" :disabled="disabled_state" @click.native="conserveVisitLog('followUp')">保存跟进记录</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-col>
      <el-col class="history" :span="12">
        <div class="title">
          <span class="text">历史跟进</span>
          <div class="total">
            共有
            <span class="sum">{{ total }}条</span>
            跟进
          </div>
        </div>
        <div class="history_list">
          <div v-for="item in record" class="history_item">
            <div class="user">{{ item.usernamelastTwoChars }}</div>
            <div class="info">
              <div class="followup">
                <div class="text">
                  <span class="color">{{ item.username }}</span>
                  {{ item.mode }}
                  <span class="color">{{ item.name }}</span>
                </div>
                <div class="text_right">
                  <!--                  <div class="edit">-->
                  <!--                    <span @click="handleDialog('add',item)">-->
                  <!--                      <i class="el-icon-circle-plus" />-->
                  <!--                      追加-->
                  <!--                    </span>-->
                  <!--                  </div>-->
                  <span class="time">{{ item.create_time }}</span>
                </div>
              </div>
              <div class="content">
                <!-- 【优化】跟进结果支持换行显示 -->
                <pre>{{item.result}}</pre>
                <!--                <p v-html="item.result ? item.result : item.big_result" />-->
                <div v-if="item.enclosure != '' && item.enclosure.length > 0">
                  <!--                  <div-->
                  <!--                    v-for="items in item.enclosure"-->
                  <!--                    class="img"-->
                  <!--                    :style="{ 'background-image': `url(${items})`,height:'80px',width:'80px',-->
                  <!--                              'background-repeat': 'no-repeat',-->
                  <!--                              'background-position': 'center center',-->
                  <!--                              'background-size': 'cover'}"-->
                  <!--                  />-->
                  <div v-for="items in item.enclosure" class="img">
                    <el-image
                      cover
                      style="width: 100px; height: 100px"
                      :src="items"
                      :preview-src-list="[items]"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-if="listParams.total >=10" class="pagination">
          <el-pagination
            background
            :current-page="listParams.page"
            :page-sizes="[10, 20, 50, 100]"
            layout="total, sizes, prev, pager, next, jumper"
            :page-size="listParams.page_size"
            :total="total"
            @size-change="handlePageSize"
            @current-change="handleCurrentSize"
          />
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { addVisit, followUpList, contactList, clueContactList } from '@/api/company_crm'
import { getToken } from '@/utils/auth'

export default {
  name: 'FollowUpRecord',
  props: ['uid', 'clue_id', 'contacts', 'mobile'],
  data () {
    return {
      loading: false,
      disabled_state: false,
      listParams: {
        page: 1,
        page_size: 10,
        total: 0

      },
      pickerOptions: {
        disabledDate(time){
          return time.getTime() < Date.now() - 8.64e7
        }
      },
      contactsData: [],
      rules: {
        mode: [
          { required: true, message: '请选择跟进方式', trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '请输入联系电话', trigger: 'blur' }
        ],
        result: [
          { required: true, message: '请输入跟进结果', trigger: 'blur' }
        ]
      },
      followUp: {
        utype: 0,
        type: '',
        contacts: '',
        link_man: '',
        link_mobile: '',
        mode: '',
        next_time: '',
        result: '',
        enclosure: []
      },
      visitStatusList: [
        { label: '当面拜访', value: '1' },
        { label: '电话拜访', value: '2' },
        { label: '网络拜访', value: '3' },
        { label: '其它拜访', value: '0' }
      ], // 跟进方式列表
      addParams: { // 追加参数
        id: '',
        result: ''
      },
      addListId: '', // 追加id
      record: [],
      total: 0,
      upload_url: '',
      headerObj: '',
      currentNav: '',
      clue_ids: 0,
      user_id: 0
    }
  },
  watch: {
    contacts(value){
      this.followUp.link_man = value
    },
    mobile(value){
      this.followUp.link_mobile = value
    },
    uid(value){
      if (this.currentNav != 'allClient' || this.currentNav != 'myClient' || this.currentNav != 'pubilceClient'){
        this.contactList(value)
        this.followUpList(value)
      }
      this.user_id = value
    },
    clue_id(value){
      this.clue_ids = value
      if (this.currentNav == 'wholeClue' || this.currentNav == 'wholeMy' || this.currentNav == 'wholeInternationalWaters'){
        this.followUpList()
        this.clueContactList()
      }
    }
  },
  created () {
    this.currentNav = this.$route.name
    if (this.currentNav == 'wholeClue' || this.currentNav == 'wholeInternationalWaters' || this.currentNav == 'allClient' || this.currentNav == 'pubilceClient'){
      this.disabled_state = true
    }
    if (this.currentNav == 'wholeClue' || this.currentNav == 'wholeMy' || this.currentNav == 'wholeInternationalWaters'){
      this.followUp.type = 1
    } else {
      this.followUp.type = 2
      this.followUp.utype = 1
    }
    this.upload_url = window.global.RequestBaseUrl + '/upload/index'
    this.headerObj = {
      'admintoken': getToken()
    }
  },
  mounted() {
    if (this.currentNav == 'wholeClue' || this.currentNav == 'wholeMy' || this.currentNav == 'wholeInternationalWaters'){
      if (this.clue_id){
        this.followUpList()
        this.clueContactList()
      }
    }
    if (this.currentNav != 'allClient' || this.currentNav != 'myClient' || this.currentNav != 'pubilceClient'){
      if (this.uid){
        this.contactList(this.uid)
        this.followUpList(this.uid)
        /**
         * 【ID1000548】
         * 特定情况下，CRM客户跟进记录无法入库
         * yx - 2023.02.17
         * [新增]:
         * if (this.user_id === undefined || this.user_id === 0){
         *           this.user_id = this.uid
         * }
         */
        if (this.user_id === undefined || this.user_id === 0) {
          this.user_id = this.uid
        }
      }
    }
  },
  methods: {
    handlePageSize(val){
      this.listParams.page_size = val
      this.followUpList()
    },
    handleCurrentSize(val){
      this.listParams.page = val
      this.followUpList()
    },
    contactsClick(item){
      var name = item.name.split(' | ')
      this.followUp.link_man = name[0]
      this.followUp.link_mobile = name[1]
    },
    contactList(uid){
      contactList({ 'uid': uid })
        .then(res => {
          if (res.data.length > 0){
            var contactName = ''
            for (var i = 0; i <= res.data.length - 1; i++){
              /**
               * 【ID1000430】
               * 【优化】客户跟进，联系人默认调出-企业联系方式
               * yx - 2022.11.21
               * [新增]：
               * if判断，绑定v-model
               */
              if (res.data[i].type == 'company'){
                this.followUp.contacts = res.data[i].auto_id
                this.followUp.link_man = res.data[i].contact
                this.followUp.link_mobile = res.data[i].mobile
                if (res.data[i].mobile == '') {
                  this.followUp.link_mobile = res.data[i].telephone
                }
              }
              // 手机号为空时用固话 edc 2022-12-7
              contactName = res.data[i].contact + ' | ' + res.data[i].mobile
              if (res.data[i].mobile == '') {
                contactName = res.data[i].contact + ' | ' + res.data[i].telephone
              }
              this.contactsData.push({ 'name': contactName, 'value': res.data[i].auto_id })
            }
          }
        }).catch(() => {

        })
    },
    clueContactList(){
      this.contactsData = []
      clueContactList({ 'clue_id': this.clue_id })
        .then(res => {
          if (res.data.length > 0){
            var contactName = ''
            for (var i = 0; i <= res.data.length - 1; i++){
              // 手机号为空时用固话 edc 2022-12-7
              contactName = res.data[i].contact + ' | ' + res.data[i].mobile
              if (res.data[i].mobile == '') {
                contactName = res.data[i].contact + ' | ' + res.data[i].telephone
              }
              this.contactsData.push({ 'name': contactName, 'value': res.data[i].contact_id })
            }
            this.followUp.contacts = res.data[0].contact_id
            this.followUp.link_man = res.data[0].contact
            this.followUp.link_mobile = res.data[0].mobile
            if (res.data[0].mobile == '') {
              this.followUp.link_mobile = res.data[0].telephone
            }
          }
        }).catch(() => {

        })
    },
    followUpList(uid){
      var data = ''
      if (this.currentNav == 'allClient' || this.currentNav == 'myClient' || this.currentNav == 'pubilceClient'){
        if (this.clue_id != 0){
          data = { 'clue_id': this.clue_ids, 'uid': uid, 'page': this.listParams.page, 'pagesize': this.listParams.page_size }
        } else {
          data = { 'uid': uid, 'page': this.listParams.page, 'pagesize': this.listParams.page_size }
        }
      } else {
        data = { 'clue_id': this.clue_id, 'page': this.listParams.page, 'pagesize': this.listParams.page_size }
      }
      this.loading = true
      followUpList(data)
        .then(res => {
          if (res.data.items.length > 0){
            this.record = res.data.items
            for (var i = 0; i <= this.record.length - 1; i++){
              this.record[i].enclosure = this.record[i].enclosure.split(',')
              // 【优化】后台线索跟进客户跟进 -- 历史跟进记录头像最多取2个字(倒截两位)
              let str = this.record[i].username
              let lastTwoChars;
              if (/^[\u4e00-\u9fa5]+$/.test(str)) { // 检查字符串是否为汉字
                lastTwoChars = str.slice(-2); // 截取后两位
              } else {
                lastTwoChars = str.length >= 5 ? str.slice(-5) : str; // 非汉字字符串截取后边5位数
              }
              this.record[i].usernamelastTwoChars = lastTwoChars
            }
            this.total = res.data.pages.record_num
            this.listParams.total = res.data.pages.record_num
            this.listParams.page = res.data.pages.now_page
          }
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    conserveVisitLog(formName){
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const followUp = { ...this.followUp }
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (this.currentNav == 'allClient' || this.currentNav == 'myClient' || this.currentNav == 'pubilceClient'){
            followUp.clue_id = this.clue_ids
            followUp.uid = this.user_id
          } else {
            followUp.clue_id = this.clue_id
          }
          if (followUp.enclosure != ''){
            followUp.enclosure = followUp.enclosure.join(',')
          } else {
            followUp.enclosure = ''
          }
          followUp.next_time = new Date(followUp.next_time).getTime() / 1000
          addVisit(followUp)
            .then(res => {
              if (res.code == 200){
                this.$message.success(res.message)
                if (this.currentNav == 'allClient' || this.currentNav == 'myClient' || this.currentNav == 'pubilceClient'){
                  this.followUpList(this.user_id)
                  this.$emit('companyDetails')
                } else {
                  this.followUpList()
                  this.$emit('clueDetails')
                }
              } else {
                this.$message.error(res.message)
              }
              this.followUp.mode = ''
              this.followUp.next_time = ''
              this.followUp.result = ''
              this.followUp.enclosure = []
              this.$refs.uploadFile.clearFiles()
              this.submitLoading = false
            }).catch(() => {
              this.submitLoading = false
            })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    handleAvatarSuccess(res, file) {
      URL.createObjectURL(file.raw)
      if (res.data){
        this.followUp.enclosure.push(res.data.file_url)
      }
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === 'image/jpeg'
      const isPNG = file.type === 'image/png'
      const isLt2M = file.size / 1024 / 1024 < 2
      if (this.followUp.enclosure.length >= 6){
        this.$message.error('图片最多可以上传6张')
        return false
      }
      if (!isJPG && !isPNG) {
        this.$message.error('上传图片只能是 JPG / PNG 格式!')
      }
      if (!isLt2M) {
        this.$message.error('上传图片大小不能超过 2MB!')
      }
      return isJPG && isLt2M || isPNG
    },
    removeImg(file, filelist){
      var enclosure = this.followUp.enclosure
      for (var i = 0; i <= enclosure.length - 1; i++){
        if (enclosure[i] == file.response.data.file_url){
          this.$delete(enclosure, i)
        }
      }

      this.followUp.enclosure = enclosure
    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep .el-upload__text{
  height: 25px;
}
::v-deep .upload-demo{
  width: 100%;
}
::v-deep .el-upload{
  width: 100%;
}
::v-deep .el-upload-dragger{
  width: 100%;
}
::v-deep .el-col-12{
  height: 100vh;
}
::v-deep .el-form-item{
  margin-bottom: 20px;
}
::v-deep .el-form-item__label{
  font-size: 14px;
  color: #606266;
  font-weight:normal;
}
p{
  margin: 0;
  padding: 0;
  line-height: 24px;
}
.followup_wrapper{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  background: #fff;
  margin-top: 1px;
}
::v-deep .el-dialog__body{
  padding: 0;
}
.write{
  padding-top: 15px;
  border-right: 1px solid #ebeef5;
  .info{
    padding: 10px 30px;
    .el-select{
      width: 100%;
    }
    .customer{
      display: inline-block;
      padding: 1px 4px;
      height: 21px;
      border-radius: 2px;
      text-align: center;
      line-height: 21px;
      font-size: 12px;
      margin-right: 10px;
    }
    .add_tag{
      padding: 3px 10px;
      font-size: 12px;
    }
    .rule_tag{
      margin-left: 10px;
    }
  }
}
.history{
  .title{
    height: 50px;
    line-height: 50px;
    font-size: 14px;
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
    border-bottom: 1px solid #ebeef5;
    .text{
      color: #333;
      font-weight: 600;
    }
    .total{
      color: #666;
      font-weight: 400;
      .sum{
        color: #6e86b1;
      }
    }
  }
  .history_list{
    padding: 0 20px;
    height: 600px;
    overflow-x: auto;
    &::-webkit-scrollbar{
      width: 8px;
      background: #fff;
    }
    &::-webkit-scrollbar-thumb{
      width: 8px;
      height: 30px;
      border-radius: 10px;
      background: #DEDEE4;
    }
    .history_item{
      display: flex;
      border-bottom: 1px dotted #dcdfe6;
      padding: 20px 0;
      width: 100%;
      .user{
        width: 40px;
        height:40px ;
        display: flex;
        border-radius: 50%;
        background: #4a88f7;
        justify-content: center;
        align-items: center;
        margin: 0 20px 20px 0;
        color: #fff;
      }
      .info{
        flex: 1;
        .followup{
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 16px;
          padding-top: 6px;
          &:hover .edit{
            display: block;
          }
          .text{
            color: #333;
            font-size: 15px;
            flex: 1;
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space:nowrap;
            .color{
              color: #6e86b1;
              font-weight: 600;
            }
          }
          .text_right{
            display: flex;
          }
          .edit{
            display: none;
            margin-right: 15px;
            height: 14px;
            font-size: 14px;
            font-weight: 400;
            color: #0078ff;
            cursor: pointer;
          }
          .time{
            font-size: 14px;
            color: #999;
          }
        }
        .content{
          background-color: #f7f7f7;
          font-size: 14px;
          color: #333;
          padding: 16px;
          font-weight: 400;
          .img {
            display: inline-block;
            margin: 20px 10px 0px 0px;
          }
        }
      }
    }
  }
  .pagination{
    text-align: center;
    padding: 15px 20px 15px 0;
  }
}
.dialog_footer{
  width: 100%;
  display: inline-block;
  text-align: left;
  padding-left: 83px;
}
.dialog_footer2{
  width: 100%;
  display: inline-block;
  text-align: left;
}
</style>
