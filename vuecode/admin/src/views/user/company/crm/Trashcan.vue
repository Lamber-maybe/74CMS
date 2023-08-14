<template>
  <div class="trashcan_wrapper">
    <el-card>
      <div slot="header">
        回收站
      </div>
      <div class="list-search">
        <el-input
          v-model="params.keyword"
          placeholder="请输入搜索内容"
          class="input-with-select"
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
          v-loading="loading"
          border
          :data="trashcanList"
          :header-cell-style="{background:'#f9f9f9', 'border-right': '1px solid #e4e6eb'}"
          :cell-style="{'border-right': '1px solid #e4e6eb'}"
          style="width: 100%"
          @selection-change="handleSelectionChange"
        >
          <!--          <el-table-column-->
          <!--            type="selection"-->
          <!--            width="42"-->
          <!--          />-->
          <el-table-column prop="title" label="企业名称" min-width="150">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.companyname ? scoped.row.companyname : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="账号联系方式" min-width="150">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.mobile }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="客户等级" min-width="120">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.life_cycle_name ? scoped.row.life_cycle_name : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="联系人" min-width="120">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.contact ? scoped.row.contact : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="联系方式" min-width="150">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.mobile ?scoped.row.mobile : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="在招职位" min-width="150">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.jobs_num ?scoped.row.jobs_num : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="收到简历" min-width="150">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.job_apply_count ?scoped.row.job_apply_count : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="企业套餐" min-width="150">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.setmeal_name ?scoped.row.setmeal_name : '-' }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="刷新时间" min-width="150">
            <template slot-scope="scoped">
              <div v-if="scoped.row.refreshtime != 0">
                {{ scoped.row.refreshtime | timeFilter }}
              </div>
              <div v-else>
                -
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="注册时间" min-width="150">
            <template slot-scope="scoped">
              <div v-if="scoped.row.reg_time != 0">
                {{ scoped.row.reg_time | timeFilter }}
              </div>
              <div v-else>
                -
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="title" label="最后跟进" min-width="150">
            <template slot-scope="scoped">
              <div v-if="scoped.row.last_visit_time != 0">
                {{ scoped.row.last_visit_time | timeFilter }}
              </div>
              <div v-else>
                -
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="deletetime" label="删除时间" min-width="150">
            <template slot-scope="scoped">
              <div v-if="scoped.row.create_time != 0">
                {{ scoped.row.create_time | timeFilter }}
              </div>
              <div v-else>
                -
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="deletetime" label="操作账号" min-width="150">
            <template slot-scope="scoped">
              <div>
                {{ scoped.row.operate_admin }}
              </div>
            </template>
          </el-table-column>
          <el-table-column fixed="right" label="操作" min-width="220">
            <template slot-scope="scoped">
              <!--              <el-button type="primary" size="small" @click.native="openPopup(scoped.row)">查看</el-button>-->
              <el-button size="small" type="primary" @click="handleReduction(scoped.row.recycle_bin_id)">还原</el-button>
              <el-button size="small" type="danger" @click="funDelete(scoped.row.recycle_bin_id)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="spaceline" />
        <el-row :gutter="20">
          <!--          <el-col :span="8">-->
          <!--            <el-button size="small" type="danger" @click="funDeleteBatch">-->
          <!--              删除所选-->
          <!--            </el-button>-->
          <!--            <el-button size="small" type="primary" @click="reductionBatch">-->
          <!--              还原所选-->
          <!--            </el-button>-->
          <!--          </el-col>-->
          <el-col :span="24" style="text-align: right">
            <el-pagination
              background
              :current-page="params.page"
              :page-sizes="[10, 15, 20, 30, 40]"
              :page-size="params.page_size"
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
import { recycleBinList, recycleBinReturn, recycleBinDel } from '@/api/company_crm'
import { parseTime } from '@/utils'

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
      // 请求参数
      params: {
        page_size: 10,
        page: 1,
        keyword: ''
      },
      // 列表数据
      trashcanList: [],
      // 总条数
      total: 0,
      tableIdarr: [],
      configAry: [],
      uid_arr: [],
      company_id_arr: []
    }
  },
  created() {
    this.getDataList()
  },
  methods: {
    funSearchKeyword(){
      this.getDataList()
    },
    handleReduction(id) {
      this.$confirm('确定将此企业还原？', '提示', {
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
      this.$confirm('确定将此企业永久删除？', '提示', {
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
    getDataList(){
      this.loading = true
      recycleBinList(this.params).then(response => {
        if (response.data.length <= 0){
          this.total = 0
          this.params.now_page = 1
          this.trashcanList = []
          this.loading = false
        } else {
          this.total = response.data.pages.record_num
          this.params.now_page = response.data.pages.now_page
          this.trashcanList = response.data.rows
          this.loading = false
        }
      })
    },
    handleSelectionChange(val) {
      var uid_arr = []
      var company_id_arr = []
      val.forEach(row => {
        uid_arr.push(row.uid)
        company_id_arr.push(row.id)
      })
      this.uid_arr = uid_arr
      this.company_id_arr = company_id_arr
    },
    handleSizeChange(val){
      this.params.page_size = val
      this.getDataList()
    },
    handleCurrentChange(val){
      this.params.page = val
      this.getDataList()
    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep .el-table__body-wrapper::-webkit-scrollbar {
  width: 10px; // 横向滚动条
  height: 10px; // 纵向滚动条 必写
}
::v-deep .el-table__body-wrapper::-webkit-scrollbar-thumb {
  background-color: #c1c7d0;
  border-radius: 2px;
}
.list-search .input-with-select{
  margin-bottom: 20px;
  width: 265px;
  float: none;
}
.trashcan_wrapper{
padding: 20px;
.ipt_width {
width: 400px;
margin-right: 10px;
}
.pagination{
text-align: right;
padding: 15px 0 0;
}
.search_slect{
width: 100px;
}
}
.title{
cursor: pointer;
color:#4a88f7;
}
</style>
