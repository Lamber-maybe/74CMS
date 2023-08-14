<template>
  <div class="app-container">
    <el-card v-loading="loading" class="box-card">
      <div slot="header" class="clearfix">
        <span>企业风采</span>
      </div>
      <div class="list-search">
        <el-select
          v-model="audit"
          placeholder="不限审核状态"
          @change="funSearch"
        >
          <el-option label="不限审核状态" value="" />
          <el-option
            v-for="(item, index) in options_audit"
            :key="index"
            :label="item"
            :value="index"
          />
        </el-select>
      </div>
      <div class="spaceline" />
      <div class="clearfix" />
      <div v-if="list.length > 0">
        <el-card
          v-for="(item, index) in list"
          :key="index"
          class="img-content-box"
          :body-style="{ padding: '0px' }"
        >
          <div class="imgbox">
            <img
              :src="
                item.img_src != ''
                  ? item.img_src
                  : 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png'
              "
              class="image"
            >
          </div>
          <div style="padding: 14px;">
            <span class="note">{{ item.title }}</span>
            <div class="bottom clearfix">
              <span class="time">{{ item.companyname }}</span>
              <span class="time">{{ item.addtime | timeFilter }}</span>
              <div class="clearfix" />
              <span class="audit" :class="item.audit | auditFilter">
                {{ options_audit[item.audit] }}
              </span>
              <el-dropdown class="botton">
                <span class="el-dropdown-link">
                  操作
                  <i class="el-icon-arrow-down el-icon--right" />
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item @click.native="fun_audit(index, item)">
                    审核
                  </el-dropdown-item>
                  <el-dropdown-item @click.native="fun_delete(index, item)">
                    删除
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </div>
          </div>
        </el-card>
        <div class="clearfix" />
        <div class="spaceline" />
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
      </div>
      <div v-else class="empty">
        暂无数据
      </div>
    </el-card>
    <el-dialog
      title="将所选图片设置为"
      :visible.sync="dialogFormVisible"
      width="25%"
    >
      <el-radio-group
        v-model="setAuditVal"
        style="margin-top:10px;margin-left:10px;"
      >
        <el-radio
          v-for="(item, index) in options_audit"
          :key="index"
          :label="index"
        >
          {{ item }}
        </el-radio>
      </el-radio-group>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_audit">
          确 定
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import {
  companyImgList,
  companyImgAudit,
  companyImgDelete
} from '@/api/company'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    },
    auditFilter(audit) {
      switch (audit) {
        case 0:
          return 'font_warning'
        case 1:
          return 'font_success'
        case 2:
          return 'font_danger'
        default:
          return ''
      }
    }
  },
  data() {
    return {
      loading: false,
      auditSubmitLoading: false,
      setAuditVal: 0,
      auditId: 0,
      dialogFormVisible: false,
      audit: '',
      options_audit: [],
      list: [],
      total: 0,
      currentPage: 1,
      pagesize: 10
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.loading = true
      getClassify({
        type: 'companyImgAudit'
      })
        .then(response => {
          const options_audit_arr = [...response.data]
          options_audit_arr.forEach(el => {
            this.options_audit[el.id] = el.name
          })
          const params = {
            audit: this.audit,
            page: this.currentPage,
            pagesize: this.pagesize
          }
          return companyImgList(params)
        })
        .then(response => {
          this.list = [...response.data.items]
          this.total = response.data.total
          this.currentPage = response.data.current_page
          this.pagesize = response.data.pagesize
          this.loading = false
        })
    },
    funSearch() {
      this.fetchData()
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.fetchData()
    },
    closeDialog() {
      this.dialogFormVisible = false
    },
    fun_audit(index, row) {
      this.setAuditVal = row.audit
      this.auditId = row.id
      this.dialogFormVisible = true
    },
    fun_delete(index, row) {
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
          companyImgDelete(param).then(response => {
            that.$message.success(response.message)
            that.fetchData()
            return true
          })
        })
        .catch(() => {})
    },
    fun_set_audit() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      const params = {
        id: this.auditId,
        audit: this.setAuditVal
      }
      companyImgAudit(params, 'post').then(response => {
        if (response.code == 200) {
          this.$message.success(response.message)
          this.auditSubmitLoading = false
          this.fetchData()
          this.closeDialog()
          return true
        } else {
          this.auditSubmitLoading = false
          this.$message.error(response.message)
          return false
        }
      })
    }
  }
}
</script>
<style scoped>
.img-content-box {
  width: 237px;
  margin-right: 10px;
  float: left;
  margin-bottom:10px;
}
.audit {
  font-size: 13px;
}
.time {
  font-size: 13px;
  color: #999;
  margin-bottom: 8px;
  display: block;
}
.imgbox {
  width: 100%;
  display: block;
  height: 237px;
  border-bottom: 1px solid #e3e3e3;
  background-color: #999;
  overflow:hidden;
}
.bottom {
  margin-top: 13px;
  line-height: 12px;
}
.botton {
  padding: 0;
  float: right;
}
.image {
  width: 100%;
  display: block;
}
.empty {
  width: 100%;
  height: 200px;
  text-align: center;
  color: #c1c1c1;
  line-height: 200px;
}
.clearfix:before,
.clearfix:after {
  display: table;
  content: '';
}

.clearfix:after {
  clear: both;
}
</style>
