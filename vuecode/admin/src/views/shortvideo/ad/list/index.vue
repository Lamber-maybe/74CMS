<template>
  <div class="app-container">
    <div>
      <div v-if="0" slot="header" class="clearfix">
        <span>广告管理</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="platform"
          placeholder="不限平台"
          :loading="adCategoryLoading"
          class="list-options"
          @change="funPlatform"
        >
          <el-option label="不限平台" value="" />
          <el-option
            v-for="(item, index) in adPlatform"
            :key="index"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <el-select
          v-model="cid"
          placeholder="不限广告位"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限广告位" value="0" />
          <el-option
            v-for="(item, index) in adCategory"
            :key="index"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <el-select
          v-model="is_display"
          placeholder="不限显示状态"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限显示状态" value="" />
          <el-option label="显示" value="1" />
          <el-option label="隐藏" value="0" />
        </el-select>
        <el-select
          v-model="settr"
          placeholder="不限到期时间"
          class="list-options"
          @change="funSearch"
        >
          <el-option label="不限到期时间" value="" />
          <el-option label="已经到期" value="1" />
          <el-option label="三天内" value="3" />
          <el-option label="一周内" value="7" />
        </el-select>
        <el-input
          v-model="keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
          @keyup.enter.native="funSearchKeyword"
        >
          <el-select
            slot="prepend"
            v-model="key_type"
            placeholder="请选择"
            class="input-sel"
          >
            <el-option label="标题" value="1" />
            <el-option label="广告ID" value="2" />
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
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="42" />
        <el-table-column label="广告标题" show-overflow-tooltip min-width="200">
          <template slot-scope="scope">
            <el-popover placement="right" trigger="hover">
              <img
                :src="scope.row.imageurl"
                style="max-width: 500px; max-height: 400px"
              >
              <span slot="reference">
                {{ scope.row.title }}
                <i class="el-icon-picture" />
              </span>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="所属平台" min-width="90">
          <template slot-scope="scope">
            {{ scope.row.platform }}
          </template>
        </el-table-column>
        <el-table-column label="所属广告位" min-width="160">
          <template slot-scope="scope">
            {{ scope.row.cname }}
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="created_at"
          label="开始时间"
          min-width="160"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span>{{ scope.row.starttime | timeFilter }}</span>
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="created_at"
          label="到期时间"
          min-width="160"
        >
          <template slot-scope="scope">
            <i class="el-icon-time" />
            <span v-if="scope.row.deadline != 0">
              {{ scope.row.deadline | timeFilter }}
            </span>
            <span v-else>无限期</span>
          </template>
        </el-table-column>
        <el-table-column label="排序" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.sort_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="是否显示" min-width="80">
          <template slot-scope="scope">
            <el-tag :type="scope.row.is_display | displayFilter">
              {{ scope.row.is_display == 1 ? "显示" : "隐藏" }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column fixed="right" label="操作" min-width="150">
          <template slot-scope="scope">
            <el-button
              size="small"
              type="primary"
              @click="funEdit(scope.$index, scope.row)"
            >
              编辑
            </el-button>
            <el-button
              size="small"
              type="danger"
              @click="funDelete(scope.$index, scope.row)"
            >
              删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="spaceline" />
      <el-row :gutter="20">
        <el-col :span="8">
          <el-button size="small" type="primary" @click="goTo">
            添加广告
          </el-button>
          <el-button size="small" type="danger" @click="funDeleteBatch">
            删除所选
          </el-button>
        </el-col>
        <el-col :span="16" style="text-align: right">
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
    </div>
  </div>
</template>

<script>
import { adList, adDelete, adCategoryList } from '@/api/shortvideo'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    displayFilter(is_display) {
      const displayMap = {
        1: 'success',
        0: 'info'
      }
      return displayMap[is_display]
    },
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data() {
    return {
      tableIdarr: [],
      list: null,
      listLoading: true,
      total: 0,
      currentPage: 1,
      pagesize: 10,
      adPlatform: [],
      adCategory: [],
      adCategoryLoading: true,
      settr: '',
      is_display: '',
      platform: '',
      cid: '0',
      key_type: '1',
      keyword: ''
    }
  },
  created() {
    this.fetchData()
    this.fetchCategoryData()
  },
  methods: {
    funPlatform(e) {
      if (e == 0) {
        this.adCategory = []
        this.cid = '0'
      } else {
        for (const key in this.adPlatform) {
          if (this.adPlatform[key]['value'] == e) {
            this.adCategory = this.adPlatform[key]['children']
            break
          }
        }
        this.cid = '0'
      }
      this.fetchData()
    },
    fetchData() {
      this.listLoading = true
      const param = {
        settr: this.settr,
        is_display: this.is_display,
        platform: this.platform,
        cid: this.cid,
        key_type: this.key_type,
        keyword: this.keyword,
        page: this.currentPage,
        pagesize: this.pagesize
      }
      adList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.total = response.data.total
        this.currentPage = response.data.current_page
        this.pagesize = response.data.pagesize
      })
    },
    fetchCategoryData() {
      adCategoryList().then(response => {
        this.adPlatform = response.data
        this.adCategoryLoading = false
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
    funSearch() {
      this.fetchData()
    },
    funSearchKeyword() {
      this.cid = '0'
      this.currentPage = 1
      this.fetchData()
    },
    funEdit(index, row) {
      this.$router.push({
        path: '/shortvideo/ad/list/edit',
        query: {
          id: row.id
        }
      })
    },
    funDelete(index, row) {
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
          adDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => { })
    },
    funDeleteBatch() {
      var that = this
      if (that.tableIdarr.length == 0) {
        that.$message.error('请选择要删除的数据')
        return false
      }
      that
        .$confirm('此操作将永久删除选中的数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            id: that.tableIdarr
          }
          adDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => { })
    },
    handleSelectionChange(idlist) {
      this.tableIdarr = []
      if (idlist.length > 0) {
        for (const item of idlist) {
          this.tableIdarr.push(item.id)
        }
      }
    },
    goTo() {
      this.$router.push('/shortvideo/ad/list/add')
    }
  }
}
</script>
