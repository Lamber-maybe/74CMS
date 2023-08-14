<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>场景码列表</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="searchForm.type"
          class="list-options"
          placeholder="不限页面"
          @change="funSearch"
        >
          <el-option label="不限页面" value="" />
          <el-option
            v-for="(item, index) in options_type"
            :key="index"
            :label="item.name"
            :value="item.alias"
          />
        </el-select>
        <el-select
          v-model="searchForm.platform"
          class="list-options"
          placeholder="不限类型"
          @change="funSearch"
        >
          <el-option label="不限类型" value="" />
          <el-option
            v-for="(item, index) in options_platform"
            :key="index"
            :label="item.name"
            :value="item.value"
          />
        </el-select>
        <el-select
          v-model="searchForm.status"
          class="list-options"
          placeholder="不限有效状态"
          @change="funSearch"
        >
          <el-option label="不限有效状态" value="" />
          <el-option label="有效" value="1" />
          <el-option label="已过期" value="0" />
        </el-select>
        <el-input
          v-model="searchForm.keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
        >
          <el-select
            slot="prepend"
            v-model="searchForm.key_type"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="场景码名称" value="1" />
          </el-select>
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="funSearchKeyword"
          />
        </el-input>
      </div>
      <div class="spaceline" />
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="名称" prop="title" min-width="150" />
        <el-table-column align="center" label="页面" prop="type_cn" min-width="100" />
        <el-table-column align="center" label="到期时间" min-width="150">
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span v-if="scope.row.deadline>0">{{ scope.row.deadline | timeFilter }}</span>
            <span v-else>永久有效</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="类型" prop="platform_cn" min-width="120" />
        <el-table-column align="center" label="扫码次数" prop="total_scan" min-width="80" />
        <el-table-column align="center" label="关注人数" prop="total_subscribe" min-width="80" />
        <el-table-column align="center" label="注册量" prop="total_reg" min-width="80" />
        <el-table-column align="center" label="是否有效" min-width="100">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.deadline>0" :type="scope.row.status|colorFilter">{{ scope.row.status==1?'有效':'已过期' }}</el-tag>
            <el-tag v-else type="success">有效</el-tag>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="250">
          <template slot-scope="scope">
            <el-button
              size="small"
              @click="funShowQrcode(scope.row)"
            >
              场景码
            </el-button>
            <el-button
              size="small"
              type="danger"
              @click="funDelete(scope.row)"
            >
              删除
            </el-button>
            <el-button
              v-if="scope.row.deadline==0 || scope.row.status == 1"
              size="small"
              type="primary"
              @click="copyUrl(scope.row)"
            >
              复制链接
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button
            size="small"
            type="primary"
            @click="dialogFormVisible=true"
          >
            添加场景码
          </el-button>
        </el-col>
        <el-col :offset="8" :span="16" style="text-align: right;">
          <el-pagination
            background
            :current-page="currentPage"
            :page-sizes="[10,15, 20, 30, 40]"
            :page-size="pagesize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </el-col>
      </el-row>
    </el-card>
    <el-dialog
      v-if="showQrcode"
      :title="qrcodeTitle"
      :visible.sync="showQrcode"
      width="15%"
      :close-on-click-modal="false"
      style="text-align:center;"
      @close="showQrcode=false"
    >
      <div style="margin-bottom:10px;"><img :src="qrcodeSrc" width="200" height="200"></div>
      <el-button type="primary" @click="funDownload(qrcodeId)">下载</el-button>
    </el-dialog>
    <el-dialog
      v-if="dialogFormVisible"
      title="添加场景码"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <diaform
        @setDialogFormVisible="closeDialog"
        @pageReload="fetchData"
      />
    </el-dialog>
  </div>
</template>

<script>
import diaform from './form.vue'
import { sceneQrcodeList, sceneQrcodeDelete, sceneQrcodePlatformList, sceneQrcodeTypeList } from '@/api/scene_qrcode'
import { parseTime } from '@/utils/index'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  components: {
    diaform
  },
  filters: {
    colorFilter(val) {
      if (val == 1) {
        return 'success'
      } else {
        return 'danger'
      }
    },
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data() {
    return {
      searchForm: {
        type: '',
        platform: '',
        status: '',
        keyword: '',
        key_type: '1'
      },
      dialogFormVisible: false,
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      showQrcode: false,
      qrcodeSrc: '',
      qrcodeTitle: '',
      qrcodeId: 0,
      options_platform: [],
      options_type: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    fetchData() {
      this.listLoading = true
      const param = {
        page: this.currentPage,
        pagesize: this.pagesize,
        type: this.searchForm.type,
        platform: this.searchForm.platform,
        status: this.searchForm.status,
        keyword: this.searchForm.keyword,
        key_type: this.searchForm.key_type
      }
      sceneQrcodeList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
      sceneQrcodePlatformList({})
        .then(response => {
          this.options_platform = response.data
        })
        .catch(() => {})
      sceneQrcodeTypeList({})
        .then(response => {
          this.options_type = response.data
        })
        .catch(() => {})
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    funSearch() {
      this.fetchData()
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    funAdd() {
      this.dialogFormVisible = true
    },
    funDelete(row) {
      var that = this
      that
        .$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: row.id
          }
          sceneQrcodeDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    copyUrl (row) {
      var oInput = document.createElement('input')
      oInput.value = row.copy_url
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$message({ type: 'success', message: '复制成功' })
    },
    funShowQrcode(row){
      this.qrcodeId = row.id
      this.qrcodeTitle = row.title
      this.qrcodeSrc = row.qrcode_src
      this.showQrcode = true
    },
    funDownload(id){
      location.href = window.global.RequestBaseUrl + apiArr.sceneQrcodeDownload + '?admintoken=' + getToken() + '&id=' + id
    }
  }
}
</script>

<style scoped>
.submiting-block {
  width: 100%;
  text-align: center;
}
.submiting-block p {
  width: 350px;
  margin: 20px auto;
  color: #666;
}
</style>
