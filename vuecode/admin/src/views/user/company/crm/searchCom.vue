<template>
  <div class="app-container">
    <div class="tip"><p>使用企业名录导入线索，导入的线索可能会与您的已有线索重复。</p></div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <div class="surplusCountBox">
          <span>{{ title }}</span>
          <span v-if="accountBalanceTotal != ''" class="surplusCount">
            剩余可查询次数：
            <span class="count">{{ accountBalanceTotal }}次</span>
            <el-tooltip class="item" effect="dark" placement="bottom">
              <div slot="content">
                <p v-for="(item, index) in accountBalanceList" :key="index" class="tooltipItem">{{ item }}</p>
              </div>
              <i class="infoIcon el-icon-info" />
            </el-tooltip>
          </span>
          <span v-if="accountBalanceTotal == ''" class="surplusCount">
            暂无可用次数
          </span>
        </div>
      </div>
      <div class="list-search">
        <div class="filterList">
          <span class="labelText">所在地区：</span>
          <span class="unlimited" @click="unLimited('address')">不限</span>
          <el-cascader
            ref="tree"
            v-model="citycategoryScreen"
            size="small"
            placeholder="请选择地区"
            :options="citycategory"
            :props="{ checkStrictly: true }"
            :clearable="true"
            @change="screenList"
          />
        </div>
        <div class="filterList">
          <span class="labelText">营业状态：</span>
          <span class="unlimited" @click="unLimited('doBusiness')">不限</span>
          <el-checkbox-group v-model="checkList" @change="checkListChange">
            <el-checkbox label="1">在营/存续</el-checkbox>
            <el-checkbox label="4">迁入/迁出</el-checkbox>
            <el-checkbox label="2">吊销/撤销</el-checkbox>
            <el-checkbox label="3">注销</el-checkbox>
            <el-checkbox label="8">停业</el-checkbox>
            <el-checkbox label="9">其他</el-checkbox>
          </el-checkbox-group>
        </div>
        <div class="filterList">
          <span class="labelText">联系方式：</span>
          <span class="unlimited" @click="unLimited('contact')">不限</span>
          <el-checkbox-group v-model="checkContactList" @change="checkContactListChange">
            <el-checkbox label="1">有手机</el-checkbox>
            <el-checkbox label="2">有固话</el-checkbox>
            <el-checkbox label="3">有邮箱</el-checkbox>
            <el-checkbox label="4">有QQ</el-checkbox>
            <el-checkbox label="5">无联系方式</el-checkbox>
          </el-checkbox-group>
        </div>
        <div class="filterList">
          <span class="labelText">成立年限：</span>
          <span class="unlimited" @click="unLimited('date')">不限</span>
          <el-date-picker
            v-model="establishYear"
            size="small"
            type="daterange"
            value-format="yyyy-MM-DD"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            @change="timeChange"
          />
        </div>
        <div style="margin-bottom:30px;">
          <el-input
            v-model="keyword"
            style="width: 800px;margin-right: 10px;float: left;"
            placeholder="搜索关键词需至少两个字，不支持按公司、有限、集团、科技、信息、技术等词语搜索"
            class="input-with-select"
            @keyup.enter.native="funSearchKeyword"
          >
            <el-select slot="prepend" v-model="type" placeholder="请选择" class="input-sel">
              <el-option label="企业名称" value="entName" />
              <el-option label="经营范围" value="opScope" />
              <el-option label="产品" value="appName" />
            </el-select>
            <el-button slot="append" icon="el-icon-search" @click="funSearchKeyword" />
          </el-input>
          <!-- <el-button type="primary" @click="funSearchKeyword">搜索</el-button> -->
          <el-button @click="reset">重置</el-button>
        </div>
      </div>

      <div class="comTabList">
        <el-table
          ref="multipleTable"
          v-loading="loading"
          :data="tableData"
          tooltip-effect="dark"
          :header-cell-style="{ background: '#f9f9f9', 'border-right': '1px solid #e4e6eb' }"
          border
          stripe
          style="width: 100%;"
          @selection-change="handleSelectionChange"
        >
          <template slot="empty">
            <!-- <el-empty :image-size="100" description='暂无搜索内容,请先输入所需内容搜索'></el-empty> -->
            <img class="emptyImg" src="../../../../assets/images/outbound/emptyImg.png" alt="">
            <div class="emptyTxt">暂无搜索内容，请先输入所需内容搜索</div>
          </template>
          <el-table-column fixed type="selection" width="55" />
          <el-table-column prop="info" label="公司信息">
            <template slot-scope="scope">
              <div :class="scope.row.is_exist == 1 ? 'alsoSelect' : ''">
                <div class="comName">
                  {{ scope.row.companyname }}
                  <span v-if="scope.row.is_exist == 1" class="labelTxt">已导线索</span>
                </div>
                <div class="comTime">
                  <span class="comText1">成立时间：{{ scope.row.es_date }}</span>
                  <span class="comText2">营业状态：{{ scope.row.status }}</span>
                </div>
                <div class="comTime">
                  <span class="comText1">法人姓名：{{ scope.row.legalperson }}</span>
                  <span class="comText2">通讯地址: {{ scope.row.address }}</span>
                </div>
                <div class="comTime substring2">
                  <div v-if="scope.row.scope == ''">经营范围：{{ scope.row.scope }}</div>
                  <el-tooltip v-else class="item" effect="dark" placement="bottom">
                    <div slot="content">
                      <div class="toolTipTxt">{{ scope.row.scope }}</div>
                    </div>
                    <div style="display: inline-block;">经营范围：{{ scope.row.scope }}</div>
                  </el-tooltip>
                </div>
              </div>
            </template>
          </el-table-column>
          <el-table-column label="联系方式" width="180">
            <template slot-scope="scope">
              <el-button type="primary" size="mini" @click="seeContact(scope.row)">查看联系方式</el-button>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="180">
            <template slot-scope="scope">
              <el-button v-if="scope.row.is_exist == 1" class="alreadyImportBtn" type="info" size="mini">已导入线索</el-button>
              <el-button v-else type="primary" size="mini" @click="importClick(scope.row)">导入线索</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="bortton-page">
        <el-col :span="12" style="margin-top: 20px;"><el-button size="small" type="primary" @click="allImport()">导入线索</el-button></el-col>
        <el-col :span="12" style="text-align: right">
          <div class="paginationBox">
            <span class="pageChange prePage" @click="prePage()"><i class="el-icon el-icon-arrow-left" /></span>
            <span class="pageChange pageNum">{{ currentPage }}</span>
            <span v-if="next_surplus_num > 0" class="pageChange nextPage" @click="nextPage()"><i class="el-icon el-icon-arrow-right" /></span>
            <span v-else-if="next_surplus_num == 0" class="pageChange nextPage"><i class="el-icon el-icon-arrow-right" /></span>
            <span style="margin-left: 10px;font-size: 12px;">共{{ totalPage }}页</span>
          </div>
        </el-col>
      </div>
    </el-card>
    <!-- 查看联系方式 -->
    <el-dialog :visible.sync="contactVisible" title="联系方式" width="45%" :before-close="callHandleClose">
      <div class="contact_item_title">
        <span class="name"><span class="name3">姓名</span></span>
        <span class="tel"><span class="tel3">联系方式</span></span>
      </div>
      <div :class="contactList.length > 8 ? 'contact_list_box contact_list_box_scroll' : 'contact_list_box'">
        <div v-for="(item, index) in contactList" :key="index" class="contact_item">
          <span class="name">
            <span v-if="item.contact != ''" class="name1">{{ item.contact }}</span>
            <span v-else class="name2">未知联系人</span>
          </span>
          <span class="tel">
            <span v-if="item.content != ''" class="tel1">{{ item.content }}</span>
            <span v-else class="tel2">无有效联系方式</span>
          </span>
        </div>
      </div>
      <div style="text-align: center;margin-top: 25px;"><el-button size="mini" @click="contactVisible = false">关闭</el-button></div>
    </el-dialog>
    <!-- 导入线索 -->
    <el-dialog :visible.sync="importVisible" title="导入线索" width="470px" :before-close="meetHandleClose">
      <div>
        <el-form ref="form" :model="form" label-width="150px">
          <el-form-item label="选择导入位置">
            <el-radio-group v-model="form.value">
              <el-radio label="1" value="1">我的线索</el-radio>
              <el-radio label="2" value="2">线索公海</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-form>
      </div>
      <div style="text-align: center;margin-top: 25px;">
        <el-button size="mini" type="primary" :disabled="toImportDisabled" @click="toImport()">确定</el-button>
        <el-button size="mini" @click="importVisible = false">取消</el-button>
      </div>
    </el-dialog>
    <!-- 线索导入成功提示 -->
    <el-dialog :visible.sync="importSuccessVisible" title="提示" width="470px" :before-close="importSuccessClose">
      <div class="successImportWarn">
        <div class="el-message-box__status el-icon-success" />
        <div style="padding-left:32px;">
          您已成功导入
          <span class="themeColor">{{ success_num }}</span>
          条线索，
          <span style="cursor: pointer;color: #409EFF;" @click="goto()">立即查看</span>
        </div>
      </div>
      <div style="text-align: right;margin-top: 25px;">
        <el-button size="mini" @click="importSuccessVisible = false">取消</el-button>
        <el-button size="mini" type="primary" @click="importSuccess()">确定</el-button>
      </div>
    </el-dialog>
    <!-- 切换下一页提示 -->
    <el-dialog :visible.sync="nextPageVisible" title="提示" width="35%" :before-close="nextPageClose">
      <div>
        <i class="el-icon-warning" style="color: rgb(222,147,46);margin-right:5px;" />
        切换到下一页，将扣除下一页
        <span class="themeColor">{{ next_surplus_num }}</span>
        的查询次数，是否继续查看
      </div>
      <div style="text-align: right;margin-top: 25px;">
        <el-button size="mini" type="primary" @click="nextPageSuccess()">确定</el-button>
        <el-button size="mini" @click="nextPageVisible = false">取消</el-button>
      </div>
    </el-dialog>
    <!-- 搜索提示 -->
    <el-dialog :visible.sync="searchVisible" title="提示" width="475px" custom-class="customWidth" :before-close="searchVisibleClose">
      <div class="seachWarning">
        <p style="position: relative;box-sizing: border-box;padding-left: 30px;">
          <i style="position: absolute;left: 0;" class="el-message-box__status el-icon-warning" />
          本次搜索将扣除1次查询额度，是否继续搜索？
        </p>
        <p style="box-sizing: border-box;padding-left: 30px;">1.搜索完成后不支持按条件筛选，如需条件请提前设置；</p>
        <p style="box-sizing: border-box;padding-left: 30px;">2.您在30天内搜索完全相同的关键词+条件不重复扣次数。</p>
      </div>
      <div style="text-align: right;margin-top: 25px;">
        <el-button size="mini" type="primary" @click="searchSuccess()">确定</el-button>
        <el-button size="mini" @click="searchVisible = false">取消</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getCompanyClueList, getAccountBalance, importClue, queryCacheIsExists } from '@/api/directory'
import citycategory from '@/utils/addressList'

export default {
  name: 'SearchCom',
  components: {},
  data() {
    return {
      key_type: '1',
      loading: false,
      keyword: '',
      drawer: false,
      regionScreenData: [],
      industryScreenData: [],
      pagesize: 10,
      total: 0,
      totalPage: 0,
      currentPage: 1,
      next_surplus_num: 0,
      tableData: [],
      form: {
        value: '1'
      },
      contactList: [],
      title: '搜索企业',
      establishYear: [],
      currentNav: '',
      rowId: 0,
      contactVisible: false,
      importVisible: false,
      importSuccessVisible: false,
      nextPageVisible: false,
      searchVisible: false,
      checkList: [],
      checkContactList: [],
      addressList: {
        province: '',
        province_name: '',
        city: '',
        city_name: '',
        district: '',
        district_name: ''
      },
      type: 'entName',
      accountBalanceList: [],
      accountBalanceTotal: '',
      import_clue_list: [],
      success_num: 0,
      citycategory: [],
      citycategoryScreen: '',
      toImportDisabled: false
    }
  },
  computed: {
    clueTime() {
      var clueTime = this.$route.query.time
      return clueTime
    }
  },
  watch: {
    clueTime() {
      var clue_type = localStorage.getItem('clue_type')
      var clue_id = localStorage.getItem('clue_id')
      if (clue_type != '' && clue_type == 'follow') {
        this.drawer = true
        this.rowId = clue_id
        localStorage.setItem('clue_type', '')
        localStorage.setItem('clue_id', '')
      }
    }
  },
  mounted() {
    this.getAccountBalance()
    this.citycategory = citycategory.citycategory
  },
  created() {},
  methods: {
    initData() {
      this.loading = true
      const params = {
        page: this.currentPage,
        contact: this.checkContactList == '' ? '' : this.checkContactList.join(','),
        ent_status: this.checkList == '' ? '' : this.checkList.join(','),
        starttime: this.establishYear == '' ? '' : this.establishYear[0],
        endtime: this.establishYear == '' ? '' : this.establishYear[1],
        keyword: this.keyword,
        type: this.type,
        province: this.addressList.province,
        province_name: this.addressList.province_name,
        city: this.addressList.city,
        city_name: this.addressList.city_name,
        district: this.addressList.district,
        district_name: this.addressList.district_name
      }
      getCompanyClueList(params)
        .then(res => {
          this.tableData = res.data.items
          this.total = res.data.totalCount
          this.totalPage = res.data.totalPage
          this.currentPage = res.data.page
          this.next_surplus_num = res.data.next_surplus_num
          this.getAccountBalance()
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    getAccountBalance() {
      getAccountBalance().then(res => {
        this.accountBalanceTotal = res.data.total
        this.accountBalanceList = res.data.list
      })
    },
    seeContact(row) {
      if (row.contacts.length > 0) {
        this.contactList = row.contacts
        this.contactVisible = true
      } else {
        this.$message.error('无联系方式！')
      }
    },
    importClick(row) {
      this.import_clue_list.push(row)
      this.importVisible = true
    },
    toImport() {
      this.toImportDisabled = true
      const params = {
        place: parseInt(this.form.value),
        clue_list: this.import_clue_list
      }
      importClue(params).then(res => {
        this.initData()
        this.success_num = res.data.success_num
        this.import_clue_list = []
        this.importVisible = false
        this.importSuccessVisible = true
        this.toImportDisabled = false
        // this.$confirm('您已成功导入 ' + res.data.success_num + ' 条线索',
        //     '提示', {
        //       confirmButtonText: '立即查看',
        //       cancelButtonText: '取消',
        //       type: 'success'
        //     })
        //   .then(() => {
        //     let url = ''
        //     if (this.form.value == 1) {
        //       url = this.$router.resolve({
        //         name: '/user/company/crm/my'
        //       })
        //     } else if (this.form.value == 2) {
        //       url = this.$router.resolve({
        //         name: '/user/company/crm/internationalWaters'
        //       })
        //     }
        //     window.open(url.href, '_blank')
        //   })
        //   .catch(() => {})
      })
    },
    importSuccess() {
      this.importVisible = false
      this.importSuccessVisible = false
    },
    allImport() {
      if (this.import_clue_list.length == 0) {
        this.$message.error('请选择要导入的内容！')
      } else {
        this.importVisible = true
      }
    },
    prePage() {
      if (this.currentPage > 1) {
        this.currentPage--
        this.initData()
      }
    },
    getQueryCacheIsExists() {
      const params = {
        page: this.currentPage + 1,
        contact: this.checkContactList == '' ? '' : this.checkContactList.join(','),
        ent_status: this.checkList == '' ? '' : this.checkList.join(','),
        starttime: this.establishYear == '' ? '' : this.establishYear[0],
        endtime: this.establishYear == '' ? '' : this.establishYear[1],
        keyword: this.keyword,
        type: this.type,
        province: this.addressList.province,
        province_name: this.addressList.province_name,
        city: this.addressList.city,
        city_name: this.addressList.city_name,
        district: this.addressList.district,
        district_name: this.addressList.district_name
      }
      queryCacheIsExists(params).then(res => {
        if (res.data.is_exists == 0) {
          this.$confirm('切换下一页，将扣除1次查询额度，是否继续查看？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          })
            .then(() => {
              this.currentPage++
              this.initData()
            })
            .catch(() => {})
        } else if (res.data.is_exists == 1) {
          this.currentPage++
          this.initData()
        }
      })
    },
    nextPage() {
      this.getAccountBalance()
      this.getQueryCacheIsExists()
    },
    nextPageSuccess() {
      this.nextPageVisible = false
      this.currentPage++
      this.initData()
    },
    checkListChange(val) {
      // if (this.keyword != '') {
      //   this.searchVisible = true
      //   this.currentPage = 1
      // }
    },
    screenList(e) {
      if (e.length > 0) {
        const path = this.$refs.tree.getCheckedNodes()[0].path
        const pathLabels = this.$refs.tree.getCheckedNodes()[0].pathLabels
        if (path.length == 1) {
          this.addressList = {
            province: path[0],
            province_name: pathLabels[0],
            city: '',
            city_name: '',
            district: '',
            district_name: ''
          }
        } else if (path.length == 2) {
          this.addressList = {
            province: path[0],
            province_name: pathLabels[0],
            city: path[1],
            city_name: pathLabels[1],
            district: '',
            district_name: ''
          }
        } else if (path.length == 3) {
          this.addressList = {
            province: path[0],
            province_name: pathLabels[0],
            city: path[1],
            city_name: pathLabels[1],
            district: path[2],
            district_name: pathLabels[2]
          }
        }
      } else {
        this.addressList = {
          province: '',
          province_name: '',
          city: '',
          city_name: '',
          district: '',
          district_name: ''
        }
      }
      this.currentPage = 1
      // if (this.keyword != '') {
      //   this.searchVisible = true
      //   this.currentPage = 1
      // }
    },
    checkContactListChange(val) {
      // if (this.keyword != '') {
      //   this.searchVisible = true
      //   this.currentPage = 1
      // }
    },
    timeChange(val) {
      // if (this.keyword != '') {
      //   this.searchVisible = true
      //   this.currentPage = 1
      // }
    },
    unLimited(type) {
      if (type == 'doBusiness') {
        this.checkList = []
      } else if (type == 'contact') {
        this.checkContactList = []
      } else if (type == 'date') {
        this.establishYear = []
      } else if (type == 'address') {
        this.citycategoryScreen = ''
        this.addressList = {
          province: '',
          province_name: '',
          city: '',
          city_name: '',
          district: '',
          district_name: ''
        }
      }
    },
    funSearchKeyword() {
      if (this.keyword != '') {
        this.searchVisible = true
        // const h = this.$createElement;
        // this.$confirm('提示',
        //     '提示', {
        //       message: h('div', [
        //         h('p', '本次搜索将扣除1次查询额度，是否继续搜索？'),
        //         h('p', '1.搜索完成后不支持按条件筛选，如需条件请提前设置；'),
        //         h('p', '2.您在30天内搜索完全相同的关键词+条件不重复扣次数。')
        //       ]),
        //       confirmButtonText: '确定',
        //       cancelButtonText: '取消',
        //       type: 'warning'
        //     })
        //   .then(() => {
        //     this.searchVisible = false
        //     this.currentPage = 1
        //     this.initData()
        //   })
        //   .catch(() => {})
      } else {
        this.$message.error('请输入关键词！')
      }
    },
    searchSuccess() {
      this.searchVisible = false
      this.currentPage = 1
      this.initData()
    },
    goto() {
      this.importSuccessVisible = false
      if (this.form.value == 1) {
        const pathInfo = this.$router.resolve({
          path: '/user/company/crm/my',
          query: {}
        })
        window.open(pathInfo.href, '_blank')
      } else if (this.form.value == 2) {
        const pathInfo = this.$router.resolve({
          path: '/user/company/crm/internationalWaters',
          query: {}
        })
        window.open(pathInfo.href, '_blank')
      }
    },
    // 全选
    choose() {
      this.tableData.forEach(row => {
        this.$refs.multipleTable.toggleRowSelection(row)
      })
    },
    handleSelectionChange(val) {
      var id_arr = []
      val.forEach(row => {
        id_arr.push(row)
      })
      this.import_clue_list = id_arr
    },
    reset(field) {
      this.keyword = ''
      this.checkList = []
      this.checkContactList = []
      this.establishYear = []
      this.citycategoryScreen = ''
      this.addressList = {
        province: '',
        province_name: '',
        city: '',
        city_name: '',
        district: '',
        district_name: ''
      }
    },
    callHandleClose() {
      this.contactVisible = false
    },
    meetHandleClose() {
      this.importVisible = false
    },
    importSuccessClose() {
      this.importSuccessVisible = false
    },
    nextPageClose() {
      this.nextPageVisible = false
    },
    searchVisibleClose() {
      this.searchVisible = false
    }
  }
}
</script>

<style scoped lang="scss">
.dial {
  position: relative;
  top: 2px;
  width: 14px;
  height: 14px;
  display: inline-block;

  .disal_img {
    cursor: pointer;
    width: 14px;
    height: 14px;
    background: url('../../../../assets/images/outbound/dial.png') no-repeat center;
  }
}

.list-search {
  z-index: 1;
  position: relative;

  .filterList {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: start;
    margin-bottom: 25px;

    ::v-deep .el-checkbox-group {
      overflow-y: initial;
      height: auto;
    }

    .labelText {
      font-size: 14px;
      color: #909399;
    }

    .unlimited {
      display: inline-block;
      width: 50px;
      height: 23px;
      line-height: 23px;
      text-align: center;
      color: #409eff;
      border: 1px solid #409eff;
      font-size: 14px;
      border-radius: 4px;
      margin: 0 25px 0 3px;
      cursor: pointer;
    }
  }
}

.surplusCountBox {
  display: flex;
  justify-content: space-between;
  align-items: center;

  .surplusCount {
    font-size: 14px;
    color: #666;

    .count {
      color: #409eff;
      margin-right: 3px;
    }

    .infoIcon {
      margin-left: 5px;
    }
  }
}

.comTabList {
  .comName {
    font-size: 18px;
    color: #409eff;
    margin-bottom: 10px;

    .labelTxt {
      color: #909399;
      font-size: 12px;
      border: 1px solid #909399;
      border-radius: 8px;
      display: inline-block;
      width: 62px;
      text-align: center;
      height: 18px;
      line-height: 18px;
      margin-left: 10px;
    }
  }

  .comTime {
    font-size: 14px;
    color: #606266;

    .comText1 {
      display: inline-block;
      min-width: 200px;
    }
  }

  .alsoSelect {
    .comName {
      color: #909399;
    }

    .comTime {
      color: #909399;
    }
  }

  .emptyImg {
    display: block;
    margin: 0 auto;
    width: 102px;
    height: 229px;
  }

  .emptyTxt {
    color: #999999;
  }
}

.contact_item_title {
  width: 100%;
  border: 1px solid #ebeef5;
  border-bottom: none;

  .name {
    display: inline-block;
    width: 180px;
    height: 56px;
    line-height: 56px;
    border-right: 1px solid #ebeef5;
    box-sizing: border-box;
    padding-left: 50px;

    .name3 {
      color: #909399;
    }
  }

  .tel {
    display: inline-block;
    width: calc(100% - 180px);
    height: 56px;
    line-height: 56px;
    box-sizing: border-box;
    padding-left: 50px;

    .tel3 {
      color: #909399;
    }
  }
}

.paginationBox {
  width: 100%;
  text-align: right;
  margin: 20px 0 30px;

  .pageChange {
    background-color: #f4f4f5;
    border-radius: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 30px;

    &.prePage {
      cursor: pointer;
    }

    &.pageNum {
      background: #409eff;
      margin: 0 10px;
      color: #fff;
    }

    &.nextPage {
      cursor: pointer;
    }
  }
}

.contact_list_box {
  width: 100%;
  border: 1px solid #ebeef5;
  &.contact_list_box_scroll {
    max-height: 450px;
    overflow-y: scroll;
  }

  .contact_item {
    border-bottom: 1px solid #ebeef5;
    font-size: 14px;
    color: #333;

    &:last-child {
      border-bottom: none;
    }

    .name {
      display: inline-block;
      width: 180px;
      height: 56px;
      line-height: 56px;
      border-right: 1px solid #ebeef5;
      box-sizing: border-box;
      padding-left: 50px;

      .name2 {
        color: #999999;
      }
    }

    .tel {
      display: inline-block;
      width: calc(100% - 180px);
      height: 56px;
      line-height: 56px;
      box-sizing: border-box;
      padding-left: 50px;

      .tel2 {
        color: #999999;
      }
    }
  }
}

.alreadyImportBtn {
  border-color: #dcdfe6;
  background: #dcdfe6;
  color: #909399;
}

.successImportWarn {
  ::v-deep .el-message-box__status {
    top: 44%;
  }
}

.seachWarning {
  p {
    box-sizing: border-box;
    padding-left: 15px;
  }
}

.themeColor {
  color: #409eff;
  margin: 0 2px;
}

.substring2 {
  display: -webkit-box;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
}
::v-deep .el-loading-mask {
  z-index: 1 !important;
}

.toolTipTxt {
  display: inline-block;
  max-width: 800px;
  line-height: 1.5;
  font-size:14px;
}
</style>
