<template>
  <div class="trashcan_wrapper">
    <el-card>
      <div slot="header">
        回收站
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          style="width: 282px"
          placeholder="请输入简历名称"
          class="input-with-select"
          @keyup.enter.native="funSearchKeyword"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="funSearchKeyword"
          />
        </el-input>
      </div>
      <div>
        <el-table
          ref="multipleTable"
          v-loading="loading"
          border
          :data="tableData"
          :height="tabelHeight"
          :header-cell-style="{background:'#f9f9f9', 'border-right': '1px solid #e4e6eb'}"
          :cell-style="{'border-right': '1px solid #e4e6eb'}"
          style="width: 100%"
        >
          <el-table-column prop="title" label="id" min-width="80">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.rid ? scoped.row.rid : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="姓名" min-width="150">
            <template slot-scope="scope">
              <div class="list_name_box_self">
                <span class="list_name_item">
                  <img class="head_portrait" :src="scope.row.photo_img_src" alt="">
                </span>
                &nbsp;&nbsp;
                <span class="list_name_item">
                  {{ scope.row.fullname ? scope.row.fullname : '-' }}
                  <img v-if="scope.row.is_bind != 0" class="chat_icon" src="../../../../assets/images/urm/2-1.png" alt="">
                </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="简历联系方式" min-width="130">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.contact_mobile ?scoped.row.contact_mobile : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="学历" min-width="90">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.education_cn ?scoped.row.education_cn : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="经验" min-width="90">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.experience_cn ?scoped.row.experience_cn : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="完整度" min-width="150">
            <template slot-scope="scope">
              <span v-if="scope.row.complete_percent == ''"> - </span>
              <!--                <span v-else> {{ scope.row.complete_percent }}% </span>-->
              <div class="complete_percent">
                <span v-if="scope.row.complete_percent < 45" style="color:#f51e1e">
                  <el-progress color="#f51e1e" :percentage="scope.row.complete_percent" />
                </span>
                <span v-if="scope.row.complete_percent < 60 && scope.row.complete_percent >= 45" style="color:#fb3c11">
                  <el-progress color="#fb3c11" :percentage="scope.row.complete_percent" />
                </span>
                <span v-if="scope.row.complete_percent < 80 && scope.row.complete_percent >= 60" style="color:#1db75a">
                  <el-progress color="#1db75a" :percentage="scope.row.complete_percent" />
                </span>
                <span v-if="scope.row.complete_percent <= 100 && scope.row.complete_percent >= 80" style="color:#409eff">
                  <el-progress color="#409eff" :percentage="scope.row.complete_percent" />
                </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="等级" min-width="90">
            <template slot-scope="scoped">
              <span v-if="scoped.row.high_quality == 1">优质简历</span>
              <span v-if="scoped.row.high_quality == 0">普通简历</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="投递数" min-width="90">
            <template slot-scope="scoped">
              <span>{{ scoped.row.delivery_num ?scoped.row.delivery_num : '0' }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="被下载数" min-width="90">
            <template slot-scope="scoped">
              <span>{{ scoped.row.downloaded ?scoped.row.downloaded : '0' }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="被查看" min-width="90">
            <template slot-scope="scoped">
              <span>{{ scoped.row.viewed ?scoped.row.viewed : '0' }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="被面邀" min-width="90">
            <template slot-scope="scoped">
              <span>{{ scoped.row.invitation ?scoped.row.invitation : '0' }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="审核状态" min-width="120">
            <template slot-scope="scoped">
              <span v-if="scoped.row.audit == 0">待审核</span>
              <span v-if="scoped.row.audit == 1">已审核</span>
              <span v-if="scoped.row.audit == 2">已拒绝</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="刷新时间" min-width="150">
            <template slot-scope="scoped">
              <span v-if="scoped.row.refreshtime == null || scoped.row.refreshtime == ''">-</span>
              <span v-else>{{ scoped.row.refreshtime | timeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="登录时间" min-width="150">
            <template slot-scope="scoped">
              <span v-if="scoped.row.last_login_time == null || scoped.row.last_login_time == ''">-</span>
              <span v-else>{{ scoped.row.last_login_time | timeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="注册时间" min-width="150">
            <template slot-scope="scoped">
              <span v-if="scoped.row.reg_time == null || scoped.row.reg_time == ''">-</span>
              <span v-else>{{ scoped.row.reg_time | timeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="联系状态" min-width="120">
            <template slot-scope="scoped">
              <div v-if="scoped.row.is_status_phone == 1" class="contact_status" title="未电话联系，点击切换状态">
                <img style="width: 100%;height: 100%;" src="../../../../assets/images/urm/1.png" alt="">
              </div>
              <div v-if="scoped.row.is_status_phone == 2" class="contact_status" title="已电话联系，点击切换状态">
                <img style="width: 100%;height: 100%;" src="../../../../assets/images/urm/1-1.png" alt="">
              </div>
              <div v-if="scoped.row.is_status_weixin == 1" class="contact_status" title="未微信联系，点击切换状态">
                <img style="width: 100%;height: 100%;" src="../../../../assets/images/urm/2.png" alt="">
              </div>
              <div v-if="scoped.row.is_status_weixin == 2" class="contact_status" title="已电话联系，点击切换状态">
                <img style="width: 100%;height: 100%;" src="../../../../assets/images/urm/2-1.png" alt="">
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="会员联系方式" min-width="120">
            <template slot-scope="scoped">
              <span>{{ scoped.row.member_mobile ?scoped.row.member_mobile : '-' }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="跟进次数" min-width="120">
            <template slot-scope="scoped">
              <span>{{ scoped.row.follow_num ?scoped.row.follow_num : '0' }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="最后跟进时间" min-width="120">
            <template slot-scope="scoped">
              <span v-if="scoped.row.final_follow == null || scoped.row.final_follow == ''">-</span>
              <span v-else>{{ scoped.row.final_follow | timeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="deletetime" label="简历备注" min-width="120">
            <template slot-scope="scoped">
              <span>{{ scoped.row.remark ?scoped.row.remark : '-' }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="deletetime" label="删除时间" min-width="150">
            <template slot-scope="scoped">
              <span>{{ scoped.row.operate_time | timeFilter }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="deletetime" label="操作账号" min-width="120">
            <template slot-scope="scoped">
              <span>{{ scoped.row.operate_admin ?scoped.row.operate_admin : '-' }}</span>
            </template>
          </el-table-column>
          <el-table-column fixed="right" label="操作" min-width="220">
            <template slot-scope="scoped">
              <el-button size="small" type="primary" @click="handleReduction(scoped.row.id)">还原</el-button>
              <el-button size="small" type="danger" @click="funDelete(scoped.row.id)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="spaceline" />
        <el-row :gutter="20">
          <el-col :span="24" style="text-align: right">
            <el-pagination
              background
              :current-page="currentPage"
              :page-sizes="[10, 15, 20, 30, 40]"
              :page-size="pagesize"
              layout="total, sizes, prev, pager, next, jumper"
              :total="total"
              @size-change="handleSizeChange"
              @current-change="handleCurrentChange"
            />
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
import { parseTime } from '@/utils'
import { recycleBinList, recycleBinDel, recycleBinReturn } from '@/api/resume_urm'

export default {
  name: 'Trashcan',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data () {
    return {
      loading: false,
      tableData: [],
      currentPage: 1,
      pagesize: 10,
      total: 0,
      keyword: '',
      tabelHeight: 0
    }
  },
  updated(){
    this.$nextTick(() => {
      this.$refs.multipleTable.doLayout()
      if (!this.loading){
        setTimeout(() => {
          var classStr = this.$refs.multipleTable.$el.className
          var classAry = classStr.split(' ')
          var a = document.querySelectorAll('.el-table__fixed-right')[0]
          var b = document.querySelectorAll('.el-table__empty-block')[0]
          if (b != undefined){
            b.style.width = 100 + '%'
          }
          var isClass = classAry.find(function(value, index, arr){
            return value == 'el-table--scrollable-y'
          })
          if (isClass){
            a.style.right = 10 + 'px'
          } else {
            a.style.right = 0 + 'px'
          }
        }, 100)
      }
    })
  },
  mounted(){
    this.$nextTick(() => {
      var docHeight = document.documentElement.clientHeight
      this.tabelHeight = docHeight - 316 - 60
    })
  },
  created() {
    this.getDataList()
  },
  methods: {
    funSearchKeyword(){
      this.currentPage = 1
      this.getDataList()
    },
    getDataList(){
      this.loading = true
      recycleBinList({ 'pagesize': 10, 'page': this.currentPage, 'keyword': this.keyword }).then(response => {
        if (response.data.length <= 0){
          this.tableData = []
          this.currentPage = 1
          this.total = 0
          this.loading = false
        } else {
          this.tableData = response.data.items
          this.currentPage = response.data.current_page
          this.total = response.data.total
          this.loading = false
        }
      })
    },
    handleReduction(id){
      this.$confirm('确定将此简历还原？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        recycleBinReturn({ 'recycle_bin_id': id }).then(response => {
          if (response.code == 200) {
            this.$message({ type: 'success', message: response.message })
            this.getDataList()
          } else {
            this.$message({ type: 'error', message: response.message })
          }
        })
      }).catch(() => {

      })
    },
    funDelete(id){
      this.$confirm('确定将此简历永久删除？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        recycleBinDel({ 'recycle_bin_id': id })
          .then(response => {
            if (response.code == 200) {
              this.$message({ type: 'success', message: response.message })
              this.getDataList()
            } else {
              this.$message({ type: 'error', message: response.message })
            }
          })
      }).catch(() => {

      })
    },
    handleSizeChange(size){
      this.pagesize = size
      this.getDataList()
    },
    handleCurrentChange(page){
      this.currentPage = page
      this.getDataList()
    }
  }
}
</script>

<style scoped lang="scss">
.complete_percent{
  ::v-deep .el-progress__text{
    color:unset;
  }
}
.contact_status{
  display: inline-block;
  margin-right: 10px;
  width: 16px;
  height:16px;
}
.dial{
  position: relative;
  top:2px;
  width: 14px;
  height: 14px;
  display: inline-block;
  .disal_img{
    cursor:pointer;
    width: 14px;
    height: 14px;
    background: url('../../../../assets/images/outbound/dial.png') no-repeat center;
  }
}
.meet{
  .box-content{
    .img{
      width: 150px;
      height:150px;
      text-align: center;
      margin-top: 40px;
      margin:0 auto;
      background: url('../../../../assets/images/outbound/meet.png') no-repeat center;
    }
    .hu{
      text-align: center;
      margin-top: 30px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #949494;
    }
    .telephone{
      text-align: center;
      font-size: 30px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      margin-top: 26px;
    }
    .company{
      text-align: center;
      margin-top:25px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #666666;
    }
    .tips{
      text-align: center;
      height: 80px;
      line-height: 80px;
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #959595;
    }
  }
}
.meet{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
.call{
  .box-content{
    padding: 20px 30px 35px 30px;
    .advantage{
      .advantage-box{
        display: inline-block;
        width: 170px;
        .title{
          display: inline-block;
          float:left;
        }
        .img{
          display: inline-block;
          float:left;
          margin-right: 8px;
        }
      }
      margin-top: 25px;
    }
    .slogan{
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #FF550A;
      margin-top:24px;
    }
    .code{
      display: inline-block;
      margin-right: 30px;
      float: right;
      width: 117px;
      height: 117px;
      background: #FFFFFF;
      box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.14);
      border-radius: 8px;
      padding: 10px 10px;
      img{
        width: 100%;
        height: 100%;
      }
    }
    .content{
      display: inline-block;
      float: left;
      .title1{
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FF550A;
      }
      .title2{
        margin-top: 18px;
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #555555;
      }
      .title3{
        width: 133px;
        height: 24px;
        background: #FF6929;
        border-radius: 3px;
        text-align: center;
        line-height: 24px;
        font-size: 14px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #FFFFFF;
        margin-top: 23px;
      }
    }
  }
}
.call {
  ::v-deep .el-dialog__body {
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }

  ::v-deep .el-dialog {
    border-radius: 16px;
  }
}
.sotrTime {
  display: inline-block;
  width: 85%;
}

::v-deep .el-popover {
  position: fixed;
}

::v-deep .el-checkbox-group {
  height: 250px;
  overflow-y: scroll;
}
.list-search {
  z-index: 1;
  position: relative;
}

.filterOperation {
  float: right;
  margin-right: 5px;
  color: #fff;
  background-color: #409EFF;;
  padding: 4px 6px;
  font-size: 12px;
  border-radius: 3px;
}

::v-deep .el-table__body-wrapper::-webkit-scrollbar {
  width: 10px; // 横向滚动条
  height: 10px; // 纵向滚动条 必写
}

::v-deep .el-table__body-wrapper::-webkit-scrollbar-thumb {
  background-color: #c1c7d0;
  border-radius: 2px;
}

.unlock_display {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/unlock.png") no-repeat center center;

  &:hover {
    background: url("../../../../assets/images/company/crm/unlock1.png") no-repeat center center;
  }
}

.unlock {
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/unlock.png") no-repeat center center;

  &:hover {
    background: url("../../../../assets/images/company/crm/unlock1.png") no-repeat center center;
  }
}

.lock_display {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/lock.png") no-repeat center center;

  &:hover {
    background: url("../../../../assets/images/company/crm/lock1.png") no-repeat center center;
  }
}

.lock {
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/lock.png") no-repeat center center;

  &:hover {
    background: url("../../../../assets/images/company/crm/lock1.png") no-repeat center center;
  }
}

.drop_down {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url("../../../../assets/images/company/crm/drop-down.png") no-repeat center center;

  &:hover {
    background: url("../../../../assets/images/company/crm/drop-down1.png") no-repeat center center;
  }
}

.checkboxBtn {
  ::v-deep .el_button {
    padding: 4px 6px;
    font-size: 14px;
    border-radius: 3px;
  }
}

.field_s_select {
  background-color: #344563;
  border: 1px solid transparent;
  padding: 16px 10px;
}

.field_s {
  background: #f4f5f7;
  border: 1px solid transparent;
  padding: 16px 10px;
}

.operation {
  font-size: 14px;
}

::v-deep .el-drawer {
  overflow: initial;
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

.setField_h {
  height: 250px;
  overflow: auto;
}

.setFields {
  margin: 15px 0px;
  font-size: 14px;
}

.setField {
  top: 60px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}

.screenStyle {
  margin: 10px 0px;
}

.bortton-page {
  margin-top: 20px;
  padding-bottom: 40px;
}

.input-with-select {
  float: left;
  margin-bottom: 20px;
}
.filterCriteria{
  border: 1px dashed #EEEEEE;
  font-size: 13px;
  color: #999999;
  margin-top: 62px;
  margin-bottom: 16px;
  padding:15px 16px 15px 16px;
  .selected{
    display: inline-block;
    font-size: 12px;
    color: #e6a23c;
    height: 26px;
    line-height: 26px;
    padding: 0 7px 0 7px;
    border: 1px solid #e6a23c;
    position: relative;
    margin-right: 10px;
    margin-bottom: 6px;
    border-radius: 3px;
    .name{
      display: inline-block;
    }
    .closes{
      display: inline-block;
      margin-left: 10px;
    }
  }
}
.setField_s {
  top: 0px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}
.list_name_box_self {
  display: flex;
  align-items: center;
  justify-content: left;

  .list_name_item {
    display: flex;
    align-items: center;

    .chat_icon {
      display: block;
      width: 15px;
      height: 15px;
      margin-left: 6px;
    }

    .head_portrait {
      width: 20px;
      height: 20px;
      border-radius: 30px;
    }
  }
}
#animation {
  animation:pulse 1s .2s ease both infinite;
}
@-webkit-keyframes pulse {
  0% {
    -webkit-transform:scale(1)
  }
  50% {
    -webkit-transform:scale(1.1)
  }
  100% {
    -webkit-transform:scale(1)
  }
}
@-moz-keyframes pulse {
  0% {
    -moz-transform:scale(1)
  }
  50% {
    -moz-transform:scale(1.1)
  }
  100% {
    -moz-transform:scale(1)
  }
}
</style>
