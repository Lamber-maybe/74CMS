<template>
  <div class="app-container">
    <el-card class="box-card">
      <!--      <div slot="header" class="clearfix">-->
      <!--        <span>管理员日志</span>-->
      <!--        <el-button size="small" type="primary" plain @click="delLogVisible">删除日志</el-button>-->
      <!--      </div>-->
      <div slot="header" class="position">
        <span>管理员日志</span>
        <div class="topRight">
          <el-button size="small" type="primary" plain @click="delLogVisible">清理日志</el-button>
        </div>
      </div>
      <div class="list-search clearfix">
        <div class="filter-item">
          <span class="label">管理员：</span>
          <el-select v-model="admin_id" size="small" placeholder="请选择" @change="funSearch">
            <el-option label="全部" value="">全部</el-option>
            <el-option v-for="(item,index) in adminlist" :key="index" :label="item.username" :value="item.id">{{ item.username }}</el-option>
          </el-select>
        </div>
        <div class="filter-item">
          <span class="label">操作类型：</span>
          <el-select v-model="type" size="small" placeholder="请选择" @change="funSearch">
            <el-option label="全部" value="">全部</el-option>
            <el-option v-for="(item,index) in typeList" :key="index" :label="item" :value="index">{{ item }}</el-option>
          </el-select>
        </div>
        <div class="filter-item">
          <el-input
            v-model="keyword"
            placeholder="请输入搜索内容"
            class="input-with-select"
            size="small"
            style="width:200px"
            @keyup.enter.native="fetchData(true)"
          >
            <el-button
              slot="append"
              icon="el-icon-search"
              @click="fetchData(true)"
            />
          </el-input>
        </div>
      </div>
      <div class="spaceline" />
      <el-table
        v-loading="listLoading"
        :data="list"
        element-loading-text="Loading"
        fit
        highlight-current-row
      >
        <el-table-column label="时间" width="160">
          <template slot-scope="scope">
            <span>{{ scope.row.addtime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" prop="content" />
        <el-table-column label="类型" prop="type_name" width="100" />
        <el-table-column label="管理员" prop="admin_name" width="100" />
        <el-table-column label="IP" prop="ip" width="150" />
        <el-table-column label="IP归属地" prop="ip_addr" width="120" />
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8" />
        <el-col :offset="8" :span="16" style="text-align: right;">
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
    <el-dialog
      title="清理日志"
      :visible.sync="delVisible"
      width="30%"
    >
      <el-form class="common-form" label-width="80px">
        <el-form-item label="时间段">
          <el-radio-group v-model="delDays">
            <el-radio :label="1">一个月前</el-radio>
            <el-radio :label="3">三个月前</el-radio>
            <el-radio :label="6">半年前</el-radio>
            <el-radio :label="12">一年前</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="delVisible = false">取 消</el-button>
        <el-button type="primary" :loading="delDisabled" @click="delAdminLog"> 确 定 </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { adminLogList, adminAllList, adminLogTypeAll, delAdminLog } from '@/api/admin'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      type: '',
      admin_id: '',
      keyword: '',
      adminlist: [],
      typeList: [],
      delVisible: false,
      delDisabled: false,
      delDays: 0
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    funSearch() {
      this.currentPage = 1
      this.fetchData()
    },
    funSearchKeyword() {
      this.currentPage = 1
      this.fetchData()
    },
    fetchData() {
      this.listLoading = true
      adminAllList({}).then(response => {
        this.adminlist = response.data
      })
      adminLogTypeAll({}).then(response => {
        this.typeList = response.data
      })
      const param = {
        keyword: this.keyword,
        admin_id: this.admin_id,
        type: this.type,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      adminLogList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    delLogVisible() {
      this.delVisible = true
    },
    delAdminLog() {
      if (this.delDays === 0){
        this.$message.error('请选择时间段')
        return false
      }
      this.delDisabled = true
      this.$confirm('删除管理员日志后无法找回，是否确定删除', '警告提示：', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          delAdminLog({ days: this.delDays })
            .then(response => {
              this.$message.success(response.message)
              this.delVisible = false
              this.delDisabled = false
              this.fetchData()
              return true
            })
            .catch(() => {
              this.delDisabled = false
            })
        })
        .catch(() => {
          this.delDisabled = false
        })
    }
  }
}
</script>
<style scoped lang="scss">
.list-search {
	width: 100%;
	.filter-item {
		float: left;
    margin-right:10px;
		.label {
			color: #666;
			font-size: 14px;
		}
	}
	.el-select.list-options {
		margin-right: 10px;
		width: 150px;
	}
	.el-input {
		margin-right: 10px;
		width: 150px;
	}
}
.position{
  position: relative;
}
.topRight{
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  right: 20px;
}
</style>
