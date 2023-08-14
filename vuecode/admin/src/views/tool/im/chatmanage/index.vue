<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>会话管理</span>
      </div>

      <div class="spaceline" />
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="会话编号" prop="chat_id" width="280" />
        <el-table-column label="企业" prop="companyname" width="280" />
        <el-table-column label="个人" prop="fullname" width="280" />
        <el-table-column label="总消息数" prop="total" width="100" align="center" />
        <el-table-column label="发起会话时间" prop="addtime" class="mini" align="center">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="最后聊天时间" prop="endtime" class="mini" align="center">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.endtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" width="120">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funDetail(scope.$index, scope.row)"
            >
              详情
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8" />
        <el-col v-if="total>pagesize" :span="16" style="text-align: right;">
          <el-pagination
            background
            :current-page="currentPage"
            :page-sizes="[10, 20, 50, 100]"
            :page-size="pagesize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </el-col>
      </el-row>
    </el-card>

    <el-drawer
      title="聊天记录"
      :visible.sync="drawer"
      :direction="direction"
      :before-close="handleClose"
      size="40%"
    >
      <MessageList v-if="drawer" ref="messagelist" :chat_starttime="chat_starttime" :chat_id="chat_id" :messagelist_height="messagelist_height" />
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
  </div>
</template>

<script>
import { imChatmanageList } from '@/api/im'
import { parseTime } from '@/utils/index'
import { memberIm } from '@/api/member'
import MessageList from './components/MessageList'

export default {
  filters: {
    timeFilter(timestamp) {
      if (timestamp === null){
        timestamp = 0
      }
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    MessageList
  },
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      key_type: '1',
      drawer: false,
      direction: 'rtl',
      messagelist_height: '',
      chat_starttime: '',
      chat_id: 0,
      keyword: ''
    }
  },
  watch: {
    alert_warning(){
      this.initMessageListHeight()
    }
  },
  mounted() {
    this.initMessageListHeight()
    this.timer = setInterval(this.ping, 30000)
  },
  created() {
    this.fetchData()
  },
  methods: {
    handleSizeChange: function(val){
      this.pagesize = val
      this.fetchData()
    },
    funDetail (index, row) {
      this.drawer = true
      this.chat_id = row.chat_id
      this.$nextTick(() => {
        console.log(this.$refs.messagelist)
        this.$refs.messagelist.getMessageList(this.chat_id)
      })
    },
    sendReturnReceiptAll(){
      var msgObj = {
        controller: 'SendReturnReceipt',
        action: 'all',
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid
        }
      }
      var msgStr = JSON.stringify(msgObj)
      window.ws.send(msgStr)
    },
    handleCurrentChange: function(val){
      this.currentPage = parseInt(val)
      this.fetchData()
    },
    initMessageListHeight(){
      /* if (this.$store.state.LoginType == 1){
        if (this.alert_warning === true){
          this.messagelist_height = '422px'
        } else {
          this.messagelist_height = '474px'
        }
      } else {
        if (this.alert_warning === true){
          this.messagelist_height = '466px'
        } else {
          this.messagelist_height = '518px'
        }
      }*/
      this.messagelist_height = '800px'
    },
    fetchData() {
      this.listLoading = true
      const param = {
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      imChatmanageList(param, 'get')
        .then(response => {
          this.listLoading = false
          this.list = response.data.items
          this.total = response.data.total
          this.currentPage = parseInt(response.data.currentPage)
          this.pagesize = response.data.pagesize
        })
        .catch(() => { })
    },
    funIm(row) {
      const tips = '确定将该会员解除禁聊吗？'
      const disable_im = 0
      this
        .$confirm(tips, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: row.uid,
            disable_im
          }
          memberIm(param).then(response => {
            this.$message.success(response.message)
            this.fetchData()
            return true
          })
        })
        .catch(() => { })
    },
    funSearch() {
      this.fetchData()
    },
    handleClose() {
      this.drawer = false
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    }
  }
}
</script>
<style lang="scss" scoped>
::v-deep .el-drawer{
  overflow: initial;
}
::v-deep .el-drawer__header span{
  outline: none;
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
</style>
