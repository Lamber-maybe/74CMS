<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ title }}</span>
      </div>
      <div class="list-search">
        <el-input
          v-model="keyword"
          style="width: 440px;"
          placeholder="请输入搜索内容"
          class="input-with-select"
          @keyup.enter.native="funSearchKeyword"
        >
          <el-select slot="prepend" v-model="key_type" placeholder="请选择" class="input-sel">
            <el-option label="线索名称" value="1" />
            <el-option label="手机号码" value="2" />
          </el-select>
          <el-button slot="append" icon="el-icon-search" @click="funSearchKeyword" />
        </el-input>
        <div style="float:right;z-index: 1;position: relative;">
          <!--        <el-button size="small" type="primary" @click="reset('all')">-->
          <!--          重置筛选-->
          <!--        </el-button>-->
          <el-button size="small" type="primary" @click="goto('/user/company/crm/wholeAdd')">
            新增线索
          </el-button>
        </div>
      </div>
      <div
        v-if="saleFilter !='' || industryFilter !='' || regionFilter !='' || createdByFilter !='' || collectionFilter!='' || followCountFilter != ''"
        class="filterCriteria"
      >
        <div style="float:left;display: inline-block;margin-top: 6px;">已选条件：</div>
        <div style="float:left;display: inline-block;width: 80%">
          <!--          saleFilter: '',-->
          <!--          industryFilter: '',-->
          <!--          regionFilter: '',-->
          <!--          createdByFilter: ''-->
          <div v-if="saleFilter !=''" class="selected">
            <div class="name">所属销售：{{ saleFilter.name }}</div>
            <div class="closes" @click="reset(saleFilter.field)">
              <i class="el-icon-close" />
            </div>
          </div>
          <div v-if="industryFilter !=''" class="selected">
            <div class="name">客户行业：{{ industryFilter.name }}</div>
            <div class="closes" @click="reset(industryFilter.field)">
              <i class="el-icon-close" />
            </div>
          </div>
          <div v-if="regionFilter !=''" class="selected">
            <div class="name">所在地区：{{ regionFilter.name }}</div>
            <div class="closes" @click="reset(regionFilter.field)">
              <i class="el-icon-close" />
            </div>
          </div>
          <div v-if="createdByFilter !=''" class="selected">
            <div class="name">创建人：{{ createdByFilter.name }}</div>
            <div class="closes" @click="reset(createdByFilter.field)">
              <i class="el-icon-close" />
            </div>
          </div>
          <div v-if="collectionFilter !=''" class="selected">
            <div class="name">领取时间：{{ collectionFilter.name }}</div>
            <div class="closes" @click="reset(collectionFilter.field)">
              <i class="el-icon-close" />
            </div>
          </div>
          <div v-if="followCountFilter !=''" class="selected">
            <div class="name">跟进次数：{{ followCountFilter.name }}</div>
            <div class="closes" @click="reset(followCountFilter.field)">
              <i class="el-icon-close" />
            </div>
          </div>
        </div>
        <div
          style="float:right;display: inline-block;margin-top: 6px;color:#409eff;font-size: 13px;"
          @click="reset('all')"
        >
          <i class="el-icon-delete" /> 清空条件
        </div>
        <div style="clear:both;" />
      </div>
      <div style="position: relative;padding-top: 15px">
        <span class="checkboxName">ID</span>
        <div
          :class="saleFilter !='' || industryFilter !='' || regionFilter !='' || createdByFilter !='' || collectionFilter != '' || followCountFilter != ''? 'setField_s' : 'setField'"
        >
          <el-popover v-model="visiblepop" placement="bottom-start" width="220" trigger="manual">
            <div class="setField_h">
              <div v-for="item in fieldData" class="setFields">
                <input
                  v-if="item.select == true"
                  type="checkbox"
                  :name="item.name"
                  :value="item.field"
                  checked
                  @change="select(item.field)"
                >
                <input
                  v-if="item.select == false"
                  type="checkbox"
                  :name="item.name"
                  :value="item.field"
                  @change="select(item.field)"
                >
                <span style="margin-left: 10px;">{{ item.name }}</span>
              </div>
            </div>
            <el-button slot="reference" :class="menu_icon == 'menu'?'field_s':'field_s_select'" @click="menu">
              <img v-if="menu_icon == 'menu'" src="../../../../assets/images/company/crm/menu.png" alt="">
              <img v-if="menu_icon == 'menu_select'" src="../../../../assets/images/company/crm/menu1.png" alt="">
            </el-button>
            <div style="margin-top:10px;margin-right: 10px;">
              <el-button class="filterOperation" type="text" size="small" @click="setFieldClose()">关闭</el-button>
              <el-button class="filterOperation" type="text" size="small" @click="setFieldConfirm()">确认</el-button>
            </div>
          </el-popover>
        </div>
        <el-table
          ref="multipleTable"
          :v-loading="loading"
          :data="tableData"
          tooltip-effect="dark"
          :header-cell-style="{background:'#f9f9f9', 'border-right': '1px solid #e4e6eb'}"
          border
          stripe
          :height="tabelHeight"
          style="width: 100%;"
          @sort-change="sortTable"
          @selection-change="handleSelectionChange"
          @cell-mouse-enter="cellEnter"
          @cell-mouse-leave="cellLeave"
          @select-all="cellEnter"
        >
          <!--          <el-table-column fixed type="selection" width="55" />-->
          <el-table-column type="selection" width="80" align="left">
            <template #default="{ row, $index }">
              <div
                v-if="columnCheckedId == row.id || checkedList[$index]"
                @click.stop
              >
                <el-checkbox
                  v-model="checkedList[$index]"
                  @change="cellCheckbox(row, $index)"
                />
              </div>
              <span v-else>{{ row.id }}</span>
            </template>
          </el-table-column>
          <el-table-column fixed type="text" width="200" label="线索名称">
            <template slot-scope="scope">
              <div>
                <span v-if="scope.row.name == ''"> - </span>
                <span v-else> {{ scope.row.name }} </span>
                <span v-if="scope.row.tripartite_id!=''" class="mlLabel">名录</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == true && items.select == true"
            fixed
            :sortable="items.is_sortable"
            :prop="items.field"
            :width="items.width"
          >
            <template slot="header" slot-scope="scope">
              <div
                :class="items.is_sortable == 'custom' ? 'sotrTime':''"
                @mouseenter="locks(items.field)"
                @mouseleave="locks(items.field)"
              >
                <span>{{ items.name }}</span>
                <el-popover placement="bottom" trigger="hover">
                  <!-- 销售-->
                  <div v-if="items.field == 'admin_username'">
                    <el-checkbox-group style="height: 250px;">
                      <div v-for="item in saleScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="saleScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 销售-->
                  <!-- 行业-->
                  <div v-if="items.field == 'trade'">
                    <el-checkbox-group>
                      <div v-for="item in industryScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="industryScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 行业-->
                  <!-- 地区-->
                  <div v-if="items.field == 'district'">
                    <el-cascader-panel v-model="regionScreen" :props="props" clearable :options="regionScreenData" />
                  </div>
                  <!-- 地区-->
                  <!-- 领取时间-->
                  <div v-if="items.field == 'collection_time'">
                    <el-checkbox-group>
                      <div v-for="item in collectionScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="collectionScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 领取时间-->
                  <!-- 跟进次数-->
                  <div v-if="items.field == 'follow_count'">
                    <el-checkbox-group>
                      <div v-for="item in followCountScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="followCountScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 跟进次数-->
                  <!-- 创建人-->
                  <div v-if="items.field == 'creat_username'">
                    <el-checkbox-group>
                      <div v-for="item in createdByScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="createdByScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置
                    </el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div
                    v-if="items.field == 'admin_username' || items.field == 'trade' || items.field == 'district' || items.field == 'creat_username' || items.field == 'collection_time' || items.field == 'follow_count'"
                    slot="reference"
                    class="drop_down"
                  />
                  <!-- 创建人-->
                </el-popover>
                <div
                  v-if="items.is_lock_display == true"
                  slot="reference"
                  class="unlock_display"
                  @click="locking(items.field)"
                />
                <div
                  v-if="items.is_lock_display == false"
                  slot="reference"
                  class="unlock"
                  @click="locking(items.field)"
                />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'admin_username'">
                <span v-if="scope.row.admin_username == ''"> - </span>
                <span v-else> {{ scope.row.admin_username }} </span>
              </div>
              <div v-if="items.field == 'contacts'">
                <span v-if="scope.row.contacts == ''"> - </span>
                <span v-else> {{ scope.row.contacts }} </span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="scope.row.mobile == '' || scope.row.mobile == null"> - </span>
                <span v-else>
                  {{ scope.row.mobile }}
                  <div class="dial" title="拨打联系人手机号">
                    <div class="disal_img" @click="clickDial(scope.row.mobile, scope.row.name )" />
                  </div>
                </span>
              </div>
              <div v-if="items.field == 'weixin'">
                <span v-if="scope.row.weixin == ''"> - </span>
                <span v-else> {{ scope.row.weixin }} </span>
              </div>
              <div v-if="items.field == 'collection_time'">
                <span v-if="scope.row.collection_time == ''"> - </span>
                <span v-else> {{ scope.row.collection_time }} </span>
              </div>
              <div v-if="items.field == 'trade'">
                <span v-if="scope.row.trade == ''"> - </span>
                <span v-else> {{ scope.row.trade }} </span>
              </div>
              <div v-if="items.field == 'district'">
                <span v-if="scope.row.district == ''"> - </span>
                <span v-else> {{ scope.row.district }} </span>
              </div>
              <div v-if="items.field == 'address'">
                <span v-if="scope.row.address == ''"> - </span>
                <span v-else> {{ scope.row.address }} </span>
              </div>
              <div v-if="items.field == 'updatetime'">
                <span v-if="scope.row.updatetime == ''"> - </span>
                <span v-else> {{ scope.row.updatetime }} </span>
              </div>
              <div v-if="items.field == 'follow_count'">
                <span v-if="scope.row.follow_count == ''"> 0 </span>
                <span v-else> {{ scope.row.follow_count }} </span>
              </div>
              <div v-if="items.field == 'creat_username'">
                <span v-if="scope.row.creat_username == ''"> - </span>
                <span v-else> {{ scope.row.creat_username }} </span>
              </div>
              <div v-if="items.field == 'remark'">
                <span v-if="scope.row.remark == ''"> - </span>
                <span v-else> {{ scope.row.remark }} </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == false && items.select == true"
            :prop="items.field"
            :sortable="items.is_sortable"
            :width="items.width"
          >
            <template slot="header" slot-scope="scope">
              <div
                :class="items.is_sortable == 'custom' ? 'sotrTime':''"
                @mouseenter="locks(items.field)"
                @mouseleave="locks(items.field)"
              >
                <span>{{ items.name }}</span>
                <el-popover placement="bottom" trigger="hover">
                  <!-- 销售-->
                  <div v-if="items.field == 'admin_username'">
                    <el-checkbox-group>
                      <div v-for="item in saleScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="saleScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 销售-->
                  <!-- 行业-->
                  <div v-if="items.field == 'trade'">
                    <el-checkbox-group>
                      <div v-for="item in industryScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="industryScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 行业-->
                  <!-- 地区-->
                  <div v-if="items.field == 'district'">
                    <el-cascader-panel v-model="regionScreen" :props="props" clearable :options="regionScreenData" />
                  </div>
                  <!-- 地区-->
                  <!-- 创建人-->
                  <div v-if="items.field == 'creat_username'">
                    <el-checkbox-group>
                      <div v-for="item in createdByScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="createdByScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 创建人-->
                  <!-- 领取时间-->
                  <div v-if="items.field == 'collection_time'">
                    <el-checkbox-group>
                      <div v-for="item in collectionScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="collectionScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 领取时间-->
                  <!-- 跟进次数-->
                  <div v-if="items.field == 'follow_count'">
                    <el-checkbox-group>
                      <div v-for="item in followCountScreenData" class="screenStyle">
                        <el-radio :key="item.id" v-model="followCountScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 跟进次数-->
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置
                    </el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div
                    v-if="items.field == 'creat_username' || items.field == 'admin_username' || items.field == 'trade' || items.field == 'district' || items.field == 'collection_time' || items.field == 'follow_count'"
                    slot="reference"
                    class="drop_down"
                  />
                  <!-- 创建人-->
                </el-popover>
                <div
                  v-if="items.is_lock_display == true"
                  slot="reference"
                  class="lock_display"
                  @click="locking(items.field)"
                />
                <div
                  v-if="items.is_lock_display == false"
                  slot="reference"
                  class="lock"
                  @click="locking(items.field)"
                />
              </div>

            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'admin_username'">
                <span v-if="scope.row.admin_username == ''"> - </span>
                <span v-else> {{ scope.row.admin_username }} </span>
              </div>
              <div v-if="items.field == 'contacts'">
                <span v-if="scope.row.contacts == ''"> - </span>
                <span v-else> {{ scope.row.contacts }} </span>
              </div>
              <div v-if="items.field == 'mobile'">
                <span v-if="(scope.row.mobile == '' || scope.row.mobile == null) && (scope.row.telephone == '' || scope.row.telephone == null)"> - </span>
                <span v-else-if="scope.row.mobile != ''">
                  {{ scope.row.mobile }}
                  <div class="dial" title="拨打联系人手机号">
                    <div class="disal_img" @click="clickDial(scope.row.mobile, scope.row.name )" />
                  </div>
                </span>
                <span v-else>
                  {{ scope.row.telephone }}
                  <div class="dial" title="拨打联系人手机号">
                    <div class="disal_img" @click="clickDial(scope.row.telephone, scope.row.name )" />
                  </div>
                </span>
              </div>
              <div v-if="items.field == 'weixin'">
                <span v-if="scope.row.weixin == ''"> - </span>
                <span v-else> {{ scope.row.weixin }} </span>
              </div>
              <div v-if="items.field == 'collection_time'">
                <span v-if="scope.row.collection_time == ''"> - </span>
                <span v-else> {{ scope.row.collection_time }} </span>
              </div>
              <div v-if="items.field == 'trade'">
                <span v-if="scope.row.trade == ''"> - </span>
                <span v-else> {{ scope.row.trade }} </span>
              </div>
              <div v-if="items.field == 'district'">
                <span v-if="scope.row.district == ''"> - </span>
                <span v-else> {{ scope.row.district }} </span>
              </div>
              <div v-if="items.field == 'address'">
                <span v-if="scope.row.address == ''"> - </span>
                <span v-else> {{ scope.row.address }} </span>
              </div>
              <div v-if="items.field == 'updatetime'">
                <span v-if="scope.row.updatetime == ''"> - </span>
                <span v-else> {{ scope.row.updatetime }} </span>
              </div>
              <div v-if="items.field == 'follow_count'">
                <span v-if="scope.row.follow_count == ''"> 0 </span>
                <span v-else> {{ scope.row.follow_count }} </span>
              </div>
              <div v-if="items.field == 'creat_username'">
                <span v-if="scope.row.creat_username == ''"> - </span>
                <span v-else> {{ scope.row.creat_username }} </span>
              </div>
              <div v-if="items.field == 'remark'">
                <span v-if="scope.row.remark == ''"> - </span>
                <span v-else> {{ scope.row.remark }} </span>
              </div>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="140" fixed="right">
            <template slot-scope="scope">
              <el-button class="operation" type="text" size="small" @click="see(scope.row.id)">查看</el-button>
              <el-button
                v-if="list_type == 2"
                class="operation"
                type="text"
                size="small"
                @click="release(scope.row.id)"
              >释放</el-button>
              <el-button v-if="list_type == 2" class="operation" type="text" size="small" @click="see(scope.row.id)">跟进
              </el-button>
              <el-button
                v-if="list_type == 1"
                class="operation"
                type="text"
                size="small"
                @click="funAuditBatch('single',scope.row.id)"
              >领取</el-button>
              <el-button
                v-if="list_type == 1"
                class="operation"
                type="text"
                size="small"
                @click="wholeDel('single',scope.row.id)"
              >删除</el-button>
            </template>
          </el-table-column>

        </el-table>
      </div>
      <div class="bortton-page">
        <el-col :span="12">
          <el-button size="small" type="primary" @click="choose">
            全选/反选
          </el-button>
          <el-button v-if="list_type == 1" size="small" type="primary" @click="funAuditBatch('all')">
            领取
          </el-button>
          <el-button v-if="list_type == 1 " size="small" type="primary" @click="wholeDel('all')">
            删除
          </el-button>
          <el-button size="small" type="primary" @click="funExport">
            导出
          </el-button>
        </el-col>
        <el-col :span="12" style="text-align: right">
          <el-pagination
            background
            destroy-on-close
            :current-page="currentPage"
            :page-sizes="[10, 20, 50, 100]"
            :page-size="pagesize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </el-col>
      </div>
    </el-card>
    <el-drawer
      v-if="drawer"
      size="75%"
      :show-close="false"
      :with-header="false"
      :wrapper-closable="false"
      :visible.sync="drawer"
      :before-close="handleClose"
    >
      <show :row-id="rowId" @hideDetail="hideDetail" />
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
    <div class="call">
      <el-dialog :visible.sync="callDialogVisible" width="30%" :before-close="callHandleClose">
        <div class="box-content">
          <div class="content">
            <div class="title1">您还尚未开通云呼叫服务</div>
            <div class="title2">扫码添加客服</div>
            <div class="title3">快速开通呼叫业务</div>
          </div>
          <div class="code">
            <img src="../../../../assets/images/outbound/renew.png" alt="">
          </div>
          <div style="clear:both" />
          <div class="slogan">一键发起云呼叫，自动录音，助力提升沟通效率！</div>
          <div class="advantage">
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">免硬件设备</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">录音清晰无杂音</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">外显销售手机号</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">招聘行业专用线路</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">稳定性更高</div>
            </div>
            <div class="advantage-box">
              <div class="img"><img src="../../../../assets/images/outbound/choose.png" alt=""></div>
              <div class="title">防封强、接通率高</div>
            </div>
          </div>
        </div>
      </el-dialog>
    </div>
    <div class="meet">
      <el-dialog :visible.sync="meetDialogVisible" width="30%" :before-close="meetHandleClose">
        <div class="box-content">
          <div id="animation" class="img">
            <!--            <img src="../../../../assets/images/outbound/meet.png" alt="">-->
          </div>
          <div class="hu">正在呼叫客户</div>
          <div class="telephone">{{ dialPhone }}</div>
          <div class="company">{{ dialName }}</div>
          <div class="tips">请您留意手机来电接听</div>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import show from '@/views/user/company/crm/components/Show'
import {
  clueRelease,
  clueReceive,
  clueDelete,
  crmCustomList,
  clueList,
  clueAdminLists,
  classify,
  crmCustomListEdit,
  clueExport
} from '@/api/company_crm'
import {
  export_json_to_excel
} from '@/excel/Export2Excel'
import {
  outboundCall
} from '@/api/outbound'

export default {
  name: 'Whole',
  components: {
    show
  },
  data() {
    return {
      key_type: '1',
      loading: false,
      props: {
        multiple: false,
        checkStrictly: true
      },
      visiblepop: false,
      menu_icon: 'menu',
      keyword: '',
      drawer: false,
      fieldData: [],
      createdByScreen: '',
      createdByScreenData: [],
      regionScreen: '',
      regionScreenData: [],
      industryScreen: '',
      industryScreenData: [],
      saleScreen: '',
      saleScreenData: [],
      pagesize: 10,
      total: 0,
      currentPage: 1,
      multipleSelection: [],
      exportData: [],
      tableData: [],
      columnCheckedId: '',
      multipleSelectionNew: [], // table多选数据
      checkedList: [], // table多选选中数据
      title: '',
      currentNav: '',
      list_type: 0,
      rowId: 0,
      // 拖拽参数
      draggableOption: {
        sort: false,
        scroll: true,
        disabled: true
      },
      export_field: [],
      export_name: [],
      callDialogVisible: false,
      meetDialogVisible: false,
      dialPhone: '',
      dialName: '',
      sort_type: '',
      sort: '',
      saleFilter: '',
      industryFilter: '',
      regionFilter: '',
      createdByFilter: '',
      collectionScreenData: [{
        'id': 1,
        'name': '今日新增'
      },
      {
        'id': 2,
        'name': '本周新增'
      },
      {
        'id': 3,
        'name': '本月新增'
      }
      ],
      collectionScreen: '',
      collectionFilter: '',
      followCountScreenData: [{
        'id': 1,
        'name': '跟进0次'
      },
      {
        'id': 2,
        'name': '跟进1次'
      },
      {
        'id': 3,
        'name': '跟进2次'
      },
      {
        'id': 4,
        'name': '跟进3次及以上'
      }
      ],
      followCountScreen: '',
      followCountFilter: '',
      tabelHeight: 0
    }
  },
  computed: {
    clueTime() {
      var clueTime = this.$route.query.time
      return clueTime
    }
  },
  watch: {
    clueTime(){
      var clue_type = localStorage.getItem('clue_type')
      var clue_id = localStorage.getItem('clue_id')
      if (clue_type != '' && clue_type == 'follow'){
        this.drawer = true
        this.rowId = clue_id
        localStorage.setItem('clue_type', '')
        localStorage.setItem('clue_id', '')
      }
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
      const docHeight = document.documentElement.clientHeight
      this.tabelHeight = docHeight - 316 - 60
    })
  },
  created() {
    this.currentNav = this.$route.name
    if (this.currentNav == 'wholeClue'){
      this.title = '全部线索'
      this.list_type = 0
    } else if (this.currentNav == 'wholeMy'){
      this.title = '我的线索'
      this.list_type = 2
    } else if (this.currentNav == 'wholeInternationalWaters'){
      this.title = '线索公海'
      this.list_type = 1
    }
    this.clueList()
    this.crmCustomList()
    this.clueAdminLists()
    this.classify()
    var clue_type = localStorage.getItem('clue_type')
    var clue_id = localStorage.getItem('clue_id')
    if (clue_type != '' && clue_type == 'follow'){
      this.drawer = true
      this.rowId = clue_id
      localStorage.setItem('clue_type', '')
      localStorage.setItem('clue_id', '')
    }
  },
  methods: {
    sortTable({
      column,
      order
    }) {
      if (order == 'ascending') {
        this.sortType = 'asc'
      } else if (order == 'descending'){
        this.sortType = 'desc'
      } else {
        this.sortType = ''
      }
      this.sort_type = column.property
      this.sort = this.sortType
      this.clueList()
    },
    classify() {
      classify({
        'type': 'citycategory,trade,companyScale'
      }).then(res => {
        this.regionScreenData = res.data.citycategory
        this.industryScreenData = res.data.trade
      }).catch(() => {

      })
    },
    clueAdminLists(){
      clueAdminLists().then(res => {
        this.saleScreenData = res.data
        this.createdByScreenData = res.data
      }).catch(() => {

      })
    },
    clueList(){
      this.loading = true
      // 新增线索
      if (localStorage.getItem('collectionScreen')) {
        const collectionScreenData = localStorage.getItem('collectionScreen')
        this.collectionScreen = parseInt(collectionScreenData)
        this.collectionFilter = {
          name: collectionScreenData == '1' ? '今日新增' : (collectionScreenData == '2' ? '本周新增' : '本月新增'),
          field: 'collection_time'
        }
        localStorage.setItem('collectionScreen', '')
      }
      // 线索跟进
      if (localStorage.getItem('followCountScreen')) {
        const followCountScreenData = localStorage.getItem('followCountScreen')
        this.followCountScreen = parseInt(followCountScreenData)
        this.followCountFilter = {
          name: followCountScreenData == '1' ? '跟进0次' : (followCountScreenData == '2' ? '跟进1次的' : (
            followCountScreenData == '3' ? '跟进2次' : '跟进3次及以上')),
          field: 'follow_count'
        }
        localStorage.setItem('followCountScreen', '')
      }
      clueList({
        'page': this.currentPage,
        'pagesize': this.pagesize,
        'list_type': this.list_type,
        'key_type': this.key_type,
        'keyword': this.keyword,
        'trade': this.industryScreen,
        'district': this.regionScreen,
        'creat_id': this.createdByScreen,
        'admin_id': this.saleScreen,
        'sort_type': this.sort_type,
        'sort': this.sort,
        'collection': this.collectionScreen,
        'follow_count': this.followCountScreen
      })
        .then(res => {
          this.tableData = res.data.items
          this.total = res.data.total
          this.currentPage = res.data.current_page
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
    },
    setFieldClose(){
      this.menu_icon = 'menu'
      this.visiblepop = false
    },
    setFieldConfirm(){
      var list_type = this.list_type
      var list_type_z = 0
      if (list_type == 0){
        list_type_z = 1
      }
      if (list_type == 1){
        list_type_z = 2
      }
      if (list_type == 2){
        list_type_z = 3
      }
      crmCustomListEdit({
        'menu': list_type_z,
        'type': 1,
        'value': JSON.stringify(this.fieldData)
      }, 'post')
        .then(res => {
          if (res.code == 200){
            this.$message.success(res.message)
          } else {
            this.$message.error(res.message)
          }
        }).catch(() => {

        })
    },
    crmCustomList(){
      var list_type = this.list_type
      var list_type_z = 0
      if (list_type == 0){
        list_type_z = 1
      }
      if (list_type == 1){
        list_type_z = 2
      }
      if (list_type == 2){
        list_type_z = 3
      }
      crmCustomList({
        'menu': list_type_z,
        'type': 1
      })
        .then(res => {
          this.fieldData = JSON.parse(res.data)
          for (var i = 0; i <= this.fieldData.length - 1; i++){
            if (this.fieldData[i].field == 'collection_time'){
              this.fieldData[i].is_sortable = 'custom'
            } else {
              this.fieldData[i].is_sortable = false
            }
            this.export_name.push(this.fieldData[i].name)
            this.export_field.push(this.fieldData[i].field)
          }
        }).catch(() => {

        })
    },
    locking(field){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_locking == true){
            this.fieldData[i].is_locking = false
            this.fieldData[i].is_lock_display = false
            this.fieldData[i].icon = 'el-icon-lock'
          } else {
            this.fieldData[i].icon = 'el-icon-unlock'
            this.fieldData[i].is_locking = true
            this.fieldData[i].is_lock_display = false
          }
        }
      }
      this.setFieldConfirm()
    },
    select(field){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].select == true){
            this.fieldData[i].select = false
          } else {
            this.fieldData[i].select = true
          }
        }
      }
    },
    menu(){
      if (this.menu_icon == 'menu') {
        this.visiblepop = true
        this.menu_icon = 'menu_select'
      } else {
        this.visiblepop = false
        this.menu_icon = 'menu'
      }
    },
    confirm(type){
      if (type != 'reset'){
        this.tabelHeight = 'calc(100vh - 446px)'
        this.$nextTick(() => {
          this.$refs.multipleTable.doLayout()
        })
      }
      if (this.saleScreen != ''){
        for (var i = 0; i <= this.saleScreenData.length - 1; i++){
          if (this.saleScreen == this.saleScreenData[i].id){
            this.saleFilter = {
              'field': 'admin_username',
              'name': this.saleScreenData[i].name
            }
          }
        }
      } else {
        this.saleFilter = ''
      }
      if (this.industryScreen != ''){
        for (var i = 0; i <= this.industryScreenData.length - 1; i++){
          if (this.industryScreen == this.industryScreenData[i].id){
            this.industryFilter = {
              'field': 'trade',
              'name': this.industryScreenData[i].name
            }
          }
        }
      } else {
        this.industryFilter = ''
      }
      if (this.regionScreen != ''){
        // for (var i = 0; i <= this.regionScreenData.length - 1; i++){
        //   if (this.regionScreen[0] == this.regionScreenData[i].value){
        //     this.regionFilter = {
        //       'field': 'district', 'name': this.regionScreenData[i].name
        //     }
        //   }
        // }
        if (this.regionScreen.length == 1){
          for (var i = 0; i <= this.regionScreenData.length - 1; i++){
            if (this.regionScreen[0] == this.regionScreenData[i].value){
              this.regionFilter = {
                'field': 'district',
                'name': this.regionScreenData[i].label
              }
            }
          }
        }
        if (this.regionScreen.length == 2){
          for (var i = 0; i <= this.regionScreenData.length - 1; i++){
            for (var a = 0; a <= this.regionScreenData[i].children.length - 1; a++){
              if (this.regionScreen[1] == this.regionScreenData[i].children[a].value){
                this.regionFilter = {
                  'field': 'district',
                  'name': this.regionScreenData[i].children[a].label
                }
              }
            }
          }
        }
        if (this.regionScreen.length == 3){
          for (var i = 0; i <= this.regionScreenData.length - 1; i++){
            for (var a = 0; a <= this.regionScreenData[i].children.length - 1; a++){
              for (var b = 0; b <= this.regionScreenData[i].children[a].children.length - 1; b++){
                if (this.regionScreen[2] == this.regionScreenData[i].children[a].children[b].value){
                  this.regionFilter = {
                    'field': 'district',
                    'name': this.regionScreenData[i].children[a].children[b].label
                  }
                }
              }
            }
          }
        }
      } else {
        this.regionFilter = ''
      }
      if (this.createdByScreen != ''){
        for (var i = 0; i <= this.createdByScreenData.length - 1; i++){
          if (this.createdByScreen == this.createdByScreenData[i].id){
            this.createdByFilter = {
              'field': 'creat_username',
              'name': this.createdByScreenData[i].name
            }
          }
        }
      } else {
        this.createdByFilter = ''
      }
      if (this.collectionScreen != '') {
        for (var i = 0; i <= this.collectionScreenData.length - 1; i++) {
          if (this.collectionScreen == this.collectionScreenData[i].id) {
            this.collectionFilter = {
              'field': 'collection_time',
              'name': this.collectionScreenData[i].name
            }
          }
        }
      } else {
        this.collectionFilter = ''
      }
      if (this.followCountScreen != '') {
        for (var i = 0; i <= this.followCountScreenData.length - 1; i++) {
          if (this.followCountScreen == this.followCountScreenData[i].id) {
            this.followCountFilter = {
              'field': 'follow_count',
              'name': this.followCountScreenData[i].name
            }
          }
        }
      } else {
        this.followCountFilter = ''
      }
      this.currentPage = 1
      this.clueList()
    },
    see(value){
      this.drawer = true
      this.rowId = value
    },
    handleClose(){
      this.clueList()
      this.drawer = false
    },
    hideDetail(){
      this.clueList()
      this.drawer = false
    },
    handleSizeChange(val){
      this.pagesize = val
      this.clueList()
    },
    handleCurrentChange(val){
      this.currentPage = val
      this.clueList()
    },
    release(id) {
      this.$confirm('您确认释放该线索？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        clueRelease({
          clue_id: id
        }).then(response => {
          if (response.code == 200) {
            this.$message.success(response.message)
          } else {
            this.$message.error(response.message)
          }
          this.clueList()
        })
      }).catch(() => {})
    },
    funAuditBatch(type, id){
      if (type == 'all'){
        var that = this
        if (that.multipleSelection.length == 0) {
          that.$message.error('请选择要领取的线索')
          return false
        }
        var ids = that.multipleSelection
      } else {
        var ids = id
      }
      this.$confirm('您确认要领取此线索？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        clueReceive({
          clue_id: ids
        }).then(response => {
          if (response.code == 200) {
            this.$message.success(response.message)
          } else {
            this.$message.error(response.message)
          }
          this.clueList()
        })
      }).catch(() => {})
    },
    wholeDel(type, id){
      if (type == 'all'){
        var that = this
        if (that.multipleSelection.length == 0) {
          that.$message.error('请选择要删除的线索')
          return false
        }
        var ids = that.multipleSelection
      } else {
        var ids = id
      }
      this.$confirm('您确认要删除此线索?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        clueDelete({
          clue_id: ids
        }).then(response => {
          if (response.code == 200) {
            this.$message.success(response.message)
          } else {
            this.$message.error(response.message)
          }
          this.clueList()
        })
      }).catch(() => {

      })
    },
    funExport() {
      var that = this
      /**
       * 【ID1000447】
       * 【bug】子管理员没有导出权限，客户列表可以导出
       * yx - 2022.11.30
       * [新增]:
       * that.$store.state.user.access_export == 0
       * 判断
       */
      if (that.$store.state.user.access_export == 0) {
        that.$message.error('当前管理员没有导出权限')
        return false
      }
      if (that.multipleSelection.length == 0) {
        that.$message.error('请选择要导出的线索')
        return false
      }
      that.exportExcel(this.exportData)
    },
    exportExcel(list) {
      require.ensure([], () => {
        const { export_json_to_excel } = require('@/excel/Export2Excel')
        const tHeader = this.export_name // 上面设置Excel的表格第一行的标题
        /**
         * 【ID1000513】
         * 【bug】客户/线索列表底部导出后，导出的信息没有名称
         * yx - 2023.01.31
         * 【解决-新增】
         * 缺少表头及对应字段：tHeader.unshift('线索名称')
         * 缺少数据字段：filterVal.unshift('name')
         */
        tHeader.unshift('线索名称')
        const filterVal = this.export_field
        filterVal.unshift('name')
        const data = this.formatJson(filterVal, list)
        export_json_to_excel(
          tHeader,
          data,
          '线索导出'
        )
      })
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]))
    },
    funSearchKeyword(){
      this.currentPage = 1
      this.clueList()
    },
    goto(target) {
      this.$router.push(target)
    },
    locks(field){
      for (var i = 0; i <= this.fieldData.length - 1; i++){
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_lock_display == true){
            this.fieldData[i].is_lock_display = false
          } else {
            this.fieldData[i].is_lock_display = true
          }
        }
      }
    },
    choose(){
      this.tableData.forEach(row => {
        this.$refs.multipleTable.toggleRowSelection(row)
      })
    },
    handleSelectionChange(val) {
      var id_arr = []
      val.forEach(row => {
        id_arr.push(row.id)
      })
      this.exportData = val
      this.multipleSelection = id_arr

      this.multipleSelectionNew = val
      if (this.multipleSelectionNew.length == this.tableData.length) {
        this.multipleSelectionNew.forEach((item, index) => {
          this.checkedList[index] = true
        })
      }
      if (this.multipleSelectionNew.length == 0) {
        this.checkedList = []
      }
      this.$forceUpdate()
      this.$emit('selectList', this.multipleSelectionNew)
    },
    // 鼠标移入表格当前行
    cellEnter(row, column, cell, event) {
      this.columnCheckedId = row.id
    },
    // 鼠标移出表格当前行
    cellLeave(row, column, cell, event) {
      this.columnCheckedId = ''
    },
    // 选中与否塞数据
    cellCheckbox(row, index) {
      if (this.checkedList[index]) {
        this.$refs.multipleTable.toggleRowSelection(row, true)
      } else {
        this.$refs.multipleTable.toggleRowSelection(row, false)
      }
    },
    reset(field){
      this.tabelHeight = 'calc(100vh - 372px)'
      if (field == 'all'){
        this.saleScreen = []
        this.industryScreen = []
        this.regionScreen = []
        this.createdByScreen = []
        this.key_type = '1'
        this.keyword = ''
        this.collectionScreen = ''
        this.followCountScreen = ''
      }
      if (field == 'admin_username'){
        this.saleScreen = []
      }
      if (field == 'trade'){
        this.industryScreen = []
      }
      if (field == 'district'){
        this.regionScreen = []
      }
      if (field == 'creat_username'){
        this.createdByScreen = []
      }
      if (field == 'collection_time') {
        this.collectionScreen = ''
      }
      if (field == 'follow_count') {
        this.followCountScreen = ''
      }
      this.confirm('reset')
    },
    clickDial(phone, name){
      this.$confirm('是否对【' + name + '】' + phone + ' 发起呼叫？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        outboundCall({
          'mobile': phone
        }).then(res => {
          if (res.code == 200) {
            this.meetDialogVisible = true
            this.dialPhone = phone
            this.dialName = name
          }
          if (res.code == 4040){
            this.callDialogVisible = true
          }
        }).catch((res) => {})
      }).catch(() => {

      })
    },
    callHandleClose(){
      this.callDialogVisible = false
    },
    meetHandleClose(){
      this.meetDialogVisible = false
    }
  }
}
</script>

<style scoped lang="scss">
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
    padding: 0 0 0 0;
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
::v-deep .caret-wrapper {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

::v-deep .el-popover {
  position: fixed;
}

::v-deep .el-checkbox-group {
  max-height: 250px;
  overflow-y: scroll;
}
.list-search {
  z-index: 1;
  position: relative;
  overflow: hidden;
}

.filterOperation {
  float: right;
  margin-right: 5px;
  color: #fff;
  background-color: #409EFF;
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
  margin: 15px 0;
  font-size: 14px;
}

.setField {
  top: 15px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}
.checkboxName{
  position: absolute;
  top: 15px;
  left: 30px;
  z-index: 9;
  font-size: 14px;
  color: #909399;
  font-weight: bold;
  height: 48px;
  line-height: 48px;
}

.screenStyle {
  margin: 10px 0;
}

.bortton-page {
  margin-top: 20px;
  padding-bottom: 40px;
}

.input-with-select {
  float: left;
}
.filterCriteria{
  border: 1px dashed #EEEEEE;
  font-size: 13px;
  color: #999999;
  margin-top: 15px;
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
  top: 15px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}
.mlLabel {
  color: #409EFF;
  border: 1px solid #409EFF;
  display: inline-block;
  width: 35px;
  text-align: center;
  height: 16px;
  line-height: 14px;
  font-size: 12px;
  border-radius: 4px;
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
