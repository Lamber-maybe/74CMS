<template>
  <div>
    <div class="list-search">
      <el-input v-model="keyword" placeholder="请输入搜索内容" class="input-with-select" @keyup.enter.native="funSearchKeyword">
        <el-select slot="prepend" v-model="key_type" placeholder="请选择" class="input-sel">
          <el-option label="公司名称" value="1" />
          <el-option label="公司ID" value="2" />
          <el-option label="会员ID" value="3" />
        </el-select>
        <el-button slot="append" icon="el-icon-search" @click="funSearchKeyword" />
      </el-input>
      <el-select v-model="status" class="status" placeholder="请选择审核状态" @change="statusChange">
        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" />
      </el-select>
    </div>
    <div class="check-box">
      <div v-if="arr.length == 0" class="empty-box">暂无数据</div>
      <div v-else class="check-list">
        <el-checkbox-group v-model="checkedCities" @change="handleCheckedCitiesChange">
          <div v-for="item in arr" class="check-item">
            <el-checkbox :label="item.id">
              <el-tooltip class="item" effect="dark" placement="top">
                <div slot="content">{{ item.companyname }}</div>
                <div class="com-name substring">{{ item.companyname }}</div>
              </el-tooltip>
            </el-checkbox>
            <div class="com-img">
              <!-- <img :src="item.img_src" alt="" /> -->
              <el-image style="width: 100px; height: 100px" fit="cover" :src="item.img_src" :preview-src-list="item.srcList" />
            </div>
            <div class="com-status">
              审核状态：
              <span v-if="item.audit == 1" class="status1">已通过</span>
              <span v-if="item.audit == 2" class="status2">未通过</span>
              <span v-if="item.audit == 0" class="status3">待审核</span>
            </div>
            <div class="com-oprate">
              <span class="text" @click="toDo('audit', item)">审核</span>
              <span class="text txt" @click="toDo('edit', item)">修改</span>
              <span class="text" @click="toDo('delete', item)">删除</span>
            </div>
          </div>
        </el-checkbox-group>
      </div>
      <div class="check-btns">
        <div>
          <el-button type="primary" size="small" @click="handleCheckAllChange()">全选/反选</el-button>
          <el-button type="primary" size="small" @click="toDo('allAduit', '')">批量审核</el-button>
        </div>
        <el-pagination
          background
          :current-page="currentPage"
          :page-sizes="[30, 60, 90]"
          :page-size="pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </div>
    </div>
    <!-- //审核弹窗 -->
    <el-dialog title="将所选企业风采设置为" :visible.sync="dialogIsDispay" width="420px">
      <div class="isDispayBox dialogIsDispayBox">
        审核状态：
        <el-radio v-model="is_audit" label="0">待审核</el-radio>
        <el-radio v-model="is_audit" label="1">已通过</el-radio>
        <el-radio v-model="is_audit" label="2">未通过</el-radio>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button size="medium" @click="dialogIsDispay = false">取 消</el-button>
        <el-button size="medium" type="primary" :disabled="loading" @click="toDo('sure', '')">确定</el-button>
      </div>
    </el-dialog>
    <!-- 修改企业风采弹窗 -->
    <el-dialog title="修改企业风采" :visible.sync="dialogIsEdit" width="375px">
      <div class="isDispayBox label-box">
        <span class="label-txt">企业：</span>
        <div>{{ com_info.companyname }}</div>
      </div>
      <div class="isDispayBox label-box">
        <span class="label-txt">上传图片：</span>
        <el-upload class="logo-uploader" :action="apiUpload" :headers="headers" :show-file-list="false" :on-success="handleSuccess" :before-upload="beforeUpload">
          <img v-if="editImg" :src="img_src_new" class="logo">
          <img v-if="styleImg" :src="com_info.img_src" class="logo">
          <!-- <i v-if="!styleImg || !editImg" class="el-icon-plus logo-uploader-icon" /> -->
          <div class="upload-txt">重新上传</div>
        </el-upload>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button size="medium" @click="cancelEdit()">取 消</el-button>
        <el-button size="medium" type="primary" :disabled="editLoading" @click="toDo('editSure', '')">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import apiArr from '@/api'
import { allCustomersImg, companyImgAudit, companyImgDelete, companyImgEdit } from '@/api/company_crm'
export default {
  name: 'CompanyStyleReview',
  props: ['listType'],
  data() {
    return {
      headers: { admintoken: getToken() },
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      key_type: '1',
      keyword: '',
      status: '',
      checkedCities: [],
      currentPage: 1,
      pagesize: 30,
      total: 0,
      options: [
        {
          value: '',
          label: '不限审核状态'
        },
        {
          value: 0,
          label: '待审核'
        },
        {
          value: 2,
          label: '未通过'
        },
        {
          value: 1,
          label: '已通过'
        }
      ],
      checkAll: false,
      isIndeterminate: true,
      arr: [],
      dialogIsDispay: false,
      is_audit: '1',
      com_id: '',
      loading: false,
      dialogIsEdit: false,
      editLoading: false,
      styleImgUrl: '',
      styleImg: false,
      fileupload_size: '',
      fileupload_ext: '',
      com_info: {},
      img_src_new: '',
      editImg: false,
      is_all: false,
      srcList: []
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.initData()
  },
  methods: {
    initData() {
      const params = {
        list_type: this.listType,
        audit: this.status,
        page: this.currentPage,
        pagesize: this.pagesize,
        key_type: this.key_type,
        keyword: this.keyword
      }
      allCustomersImg(params).then(res => {
        if (res.code == 200) {
          this.arr = res.data.items
          this.total = res.data.total
          this.arr.forEach(item => {
            item.srcList = []
            item.srcList.push(item.img_src)
          })
        }
      })
    },
    handleSuccess(res, file) {
      if (res.code == 200) {
        this.styleImg = false
        this.editImg = true
        this.img_src_new = res.data.file_url
        this.file_id = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('上传文件格式不允许')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.pagesize = 30
      this.initData()
    },
    statusChange() {
      this.currentPage = 1
      this.pagesize = 30
      this.initData()
    },
    handleCheckedCitiesChange(e) {},
    handleCheckAllChange() {
      this.checkAll = !this.checkAll
      if (this.checkAll) {
        this.arr.forEach(item => {
          this.checkedCities.push(item.id)
        })
      } else {
        this.checkedCities = []
      }
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.initData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.initData()
    },
    toDo(val, item) {
      switch (val) {
        case 'audit':
          this.dialogIsDispay = true
          this.com_id = item.id
          this.is_audit = item.audit == 0 ? '0' : item.audit == 1 ? '1' : '2'
          this.checkedCities = []
          this.checkedCities.push(item.id)
          break
        case 'sure':
          this.exmineOprate()
          break
        case 'edit':
          this.com_id = item.id
          this.com_info = item
          this.dialogIsEdit = true
          this.styleImg = true
          this.checkedCities = []
          this.checkedCities.push(item.id)
          break
        case 'delete':
          this.com_id = item.id
          this.com_info = item
          this.checkedCities = []
          this.checkedCities.push(item.id)
          this.deleteOprate()
          break
        case 'editSure':
          this.editSureOprate()
          break
        case 'allAduit':
          this.is_audit = ''
          if (this.checkedCities.length == 0) {
            this.$message.error('请选择数据！')
            return false
          } else {
            this.dialogIsDispay = true
            this.is_all = true
          }
          break
        default:
          break
      }
    },
    cancelEdit() {
      this.editImg = false
      this.img_src_new = ''
      this.dialogIsEdit = false
    },
    deleteOprate() {
      this.$confirm('确定删除 ' + this.com_info.companyname + ' 企业风采吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          companyImgDelete({ id: this.com_id }).then(res => {
            if (res.code == 200) {
              this.$message.success(res.message)
              this.initData()
            }
          })
        })
        .catch(() => {})
    },
    exmineOprate() {
      this.loading = true
      const params = {
        id: this.is_all || this.checkedCities.length > 0 ? this.checkedCities : this.com_id,
        audit: parseInt(this.is_audit)
      }
      companyImgAudit(params).then(res => {
        if (res.code == 200) {
          this.loading = false
          this.dialogIsDispay = false
          this.$message.success(res.message)
          this.is_audit = ''
          this.checkedCities = []
          this.initData()
        } else {
          this.loading = false
        }
      })
    },
    editSureOprate() {
      this.editLoading = true
      companyImgEdit({
        id: this.com_id,
        img: this.file_id
      }).then(res => {
        if (res.code == 200) {
          this.editLoading = false
          this.dialogIsEdit = false
          this.editImg = false
          this.img_src_new = ''
          this.$message.success(res.message)
          this.initData()
        } else {
          this.editLoading = false
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.list-search {
  .input-with-select {
    float: left;
  }
  .status {
    margin-left: 15px;
  }
}
.check-box {
  padding: 15px 0 0;
  .empty-box {
    min-height: 500px;
    line-height: 500px;
    text-align: center;
    font-size: 14px;
    color: #666;
  }
  .check-list {
    margin-bottom: 15px;
    .check-item {
      width: 170px;
      height: 230px;
      border: 1px solid #dcdfe6;
      border-radius: 4px;
      position: relative;
      .com-name {
        color: #333;
        font-size: 13px;
        text-align: center;
        margin: 15px 0;
        padding: 0 31px;
        width: 170px;
        box-sizing: border-box;
        font-weight: bold;
      }
      .com-img {
        width: 100px;
        height: 100px;
        margin: 0 auto 10px;
        img {
          display: block;
          width: 100%;
          height: 100%;
          border-radius: 4px;
        }
      }
      .com-status {
        font-size: 12px;
        color: #999;
        text-align: center;
        .status1 {
          color: #00c600;
        }
        .status2 {
          color: #e50101;
        }
        .status3 {
          color: #ff5400;
        }
      }
      .com-oprate {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 40px;
        background: rgba(245, 247, 250, 0.63);
        width: 100%;
        position: absolute;
        bottom: 0;
        left: 0;
        .text {
          display: block;
          width: 33.3%;
          text-align: center;
          font-size: 12px;
          cursor: pointer;
          &.txt {
            position: relative;
            &::before {
              content: '';
              display: block;
              width: 1px;
              height: 12px;
              border-right: 1px solid #666;
              position: absolute;
              top: 1px;
              left: 0;
            }
            &::after {
              content: '';
              display: block;
              width: 1px;
              height: 12px;
              border-right: 1px solid #666;
              position: absolute;
              top: 1px;
              right: 0;
            }
          }
        }
      }
    }
    ::v-deep .el-checkbox {
      position: relative;
    }
    ::v-deep .el-checkbox__input {
      position: absolute;
      top: 17px;
      left: 15px;
    }
    ::v-deep .el-checkbox__label {
      padding-left: 0;
    }
    ::v-deep .el-checkbox__input.is-checked + .el-checkbox__label {
      color: #333;
    }
  }
  .check-btns {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
}
// dialog弹窗页面居中
::v-deep .el-dialog {
  display: flex;
  flex-direction: column;
  margin: 0 !important;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  // 内容最大可见度
  max-height: 90vh;
}

::v-deep .el-dialog .el-dialog__body {
  max-height: 85% !important;
  min-height: 70%;
  overflow-y: auto;
  flex: 1;
}
.label-box {
  display: flex;
  justify-content: flex-start;
  margin-top: 15px;
  box-sizing: border-box;
  .label-txt {
    display: block;
    width: 100px;
    text-align: right;
    padding-right: 10px;
  }
}
.logo-uploader {
  width: 120px;
  height: 120px;
  border: 1px dashed #999;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  .logo {
    display: block;
    width: 100%;
    height: 100%;
  }
  .upload-txt {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 25px;
    font-size: 12px;
    line-height: 25px;
    text-align: center;
    color: #d5cece;
    background: rgba(0, 0, 0, 0.4);
    z-index: 99;
  }
}
::v-deep .el-upload {
  display: inline-block;
  text-align: center;
  cursor: pointer;
  outline: 0;
  width: 100%;
  height: 100%;
  line-height: 120px;
}
::v-deep .el-checkbox-group {
  display: grid;
  justify-content: space-between;
  grid-template-columns: repeat(auto-fill, 170px);
  grid-gap: 10px;
}
.dialogIsDispayBox {
  margin-top: 15px;
}
</style>
