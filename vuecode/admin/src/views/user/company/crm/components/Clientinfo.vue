<template>
  <div class="clientInfo_wrapper">
    <el-row class="main">
      <el-col :span="12">
        <div class="clientInfo_box">
          <div class="item">
            <div class="info">
              <span class="label">客户等级：</span>
              <div class="grade">
                <div class="grade_box">
                  <div v-for="item in regionData" v-if="item.id != 6 && item.id != 7" :class="form.life_cycle_id == item.id?'grade_item action':'grade_item'" @click="gradeBtn(item.id)">{{ item.name }}</div>
                  <div :class="form.life_cycle_id == 6 || form.life_cycle_id == 7 ? 'grade_select  action' : 'grade_select'">
                    <el-select v-model="deal" style="border-color:red" class="action" placeholder="完成" @change="gradeSelect">
                      <el-option label="流失客户" value="6" />
                      <el-option class="action" label="成交客户" value="7" />
                    </el-select>
                  </div>
                </div>
              </div>
            </div>
            <!--            <div class="btn">-->
            <!--              <el-button size="small" type="primary" plain @click="handleEdit('grade')">修改</el-button>-->
            <!--            </div>-->
          </div>
          <div class="item">
            <div class="info" style="padding: 15px 0px 15px 6px; ">
              <span class="label" style="width: 108px;">客户标签：</span>
            </div>
            <div class="text overflow">
              <span v-if="details.labels != ''">
                <span v-for="item in details.lables_list" :key="item.id" class="tag">
                  {{ item.name }}
                </span>
              </span>
              <span v-else>-</span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('labels')">配置</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">企业账号：</span>
              <span class="text">{{ details.username ? details.username : '-' }}</span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="locking(details)"> {{ details.user_status == 1 ? "锁定" : "解锁" }}</el-button>
              <el-button size="small" type="primary" plain @click="handleEdit('password')">改密</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">会员手机：</span>
              <span class="text">{{ details.mobile ? details.mobile : '-' }}</span>
              <div class="dial" title="拨打会员联系号码" v-if="details.mobile != '' && details.mobile != null">
                <div class="disal_img" @click="clickDial(details.mobile, details.companyname )" />
              </div>
              <span class="smalltip">
                <i class="el-icon-info" />
                会员登录手机号
              </span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('mobile')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">企业手机：</span>
              <span class="text">{{ details.company_mobile ? details.company_mobile : '-' }}</span>
              <div class="dial" title="拨打企业联系号码" v-if="details.company_mobile != '' && details.company_mobile != null">
                <div class="disal_img" @click="clickDial(details.company_mobile, details.companyname )" />
              </div>
              <span class="smalltip">
                <i class="el-icon-info" />
                企业资料手机号
              </span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('company_mobile')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">显示状态：</span>
              <span v-if="details.is_display == 1" class="text" style="color: #67c23a;">显示中</span>
              <span v-if="details.is_display == 0" class="text" style="color: #f56c6c;">不显示</span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('is_display')">修改显示状态</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info">
              <span class="label">会员邮箱：</span>
              <span v-if="details.email != ''" class="text">
                {{ details.email }}

              </span>
              <span v-else class="text">
                -
              </span>
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('email')">修改</el-button>
            </div>
          </div>
          <div class="item">
            <div class="info" style="padding: 15px 0px 15px 6px; ">
              <span class="label" style="width: 108px;">备注信息：</span>
            </div>
            <div class="text overflow">
              {{ details.remark ? details.remark : '-' }}
            </div>
            <div class="btn">
              <el-button size="small" type="primary" plain @click="handleEdit('remark')">修改</el-button>
            </div>
          </div>
          <div class="styles">
            <div class="item" style="border: none">
              <div class="info">
                <span class="label">企业认证：</span>
                <span v-if="details.audit == 0" class="text" style="color: #e6a23c;">未审核</span>
                <span v-if="details.audit == 1" class="text" style="color: #67c23a;">已审核</span>
                <span v-if="details.audit == 2" class="text" style="color: #f56c6c;">未通过</span>
                <span v-if="details.audit == 3" class="text" style="color: #909399;">未认证</span>

              </div>
              <div class="btn">
                <el-button size="small" type="primary" plain @click="handleEdit('audit')">审核营业执照</el-button>
              </div>
            </div>
            <div :class="details.company_auth != '' ? 'item_img' : 'item_img_empty'">
              <el-image
                v-if="details.company_auth && details.company_auth.legal_person_idcard_front"
                style="width: 100px; height: 100px"
                :src="details.company_auth.legal_person_idcard_front"
                :preview-src-list="[details.company_auth.legal_person_idcard_front]"
              />
              <el-image
                v-if="details.company_auth && details.company_auth.legal_person_idcard_back"
                style="width: 100px; height: 100px"
                :src="details.company_auth.legal_person_idcard_back"
                :preview-src-list="[details.company_auth.legal_person_idcard_back]"
              />
              <el-image
                v-if="details.company_auth && details.company_auth.license"
                style="width: 100px; height: 100px"
                :src="details.company_auth.license"
                :preview-src-list="[details.company_auth.license]"
              />
              <el-image
                v-if="details.company_auth && details.company_auth.proxy"
                style="width: 100px; height: 100px"
                :src="details.company_auth.proxy"
                :preview-src-list="[details.company_auth.proxy]"
              />
            </div>
            <div v-if="details.company_auth == ''" class="empty_texts">
              <img class="certification" src="../../../../../assets/images/company/crm/zanwei.png" alt="">
              <div>暂未上传企业认证资料</div>
            </div>
          </div>
          <div class="styles">
            <div class="item" style="border: none">
              <div class="info">
                <span class="label">企业风采：</span>
              </div>
              <div class="btn">
                <el-button v-if="details.company_img && details.company_img == ''" disabled size="small" type="primary" plain>审核</el-button>
                <el-button v-else size="small" type="primary" plain @click="handleEdit('examine')">审核</el-button>
                <el-button v-if="details.company_img && details.company_img == ''" disabled size="small" type="danger" plain>删除</el-button>
                <el-button v-else size="small" type="danger" plain @click="delCompanyImg()">删除</el-button>
              </div>
            </div>
            <div :class="details.company_img != '' ? 'item_img' : 'item_img_empty'">
              <div v-for="item in details.company_img" class="company_img">
                <el-image
                  style="width: 100px; height: 100px"
                  :src="item.img"
                  :preview-src-list="[item.img]"
                />
                <div v-if="item.audit == 1" class="state" style="color: #67c23a;">审核通过</div>
                <div v-if="item.audit == 0" class="state" style="color: #e6a23c;">待审核</div>
                <div v-if="item.audit == 2" class="state" style="color: #f56c6c;">审核未通过</div>
                <div class="check" @change="checkClick(item.id)">
                  <input type="checkbox">
                </div>
              </div>
              <div v-if="details.company_img == ''" class="empty_texts">
                <img src="../../../../../assets/images/company/crm/zanwei.png" alt="">
                <div>暂未上传企业风采图片</div>
              </div>
            </div>
          </div>
        </div>
      </el-col>
      <el-col :span="12">
        <div class="package_information">
          <div class="titles">企业套餐情况</div>
          <div class="content"><span class="content_title">会员套餐：</span>{{ details.setmeal_name }}</div>
          <div class="content"><span class="content_title">套餐剩余：</span>{{ details.setmeal_deadline_day }}</div>
          <div class="content"><span class="content_title">职位并发数：</span>{{ details.jobs_meanwhile }}条</div>
          <div class="content"><span class="content_title">免费刷新职位：</span>{{ details.refresh_jobs_free_perday }}次/天</div>
          <div class="content"><span class="content_title">职聊次数：</span>{{ details.im_total }}次</div>
          <div class="content"><span class="content_title">下载简历上限：</span>{{ details.download_resume_max_perday }}份/天</div>
          <div class="content"><span class="content_title">每日职聊上限：</span>{{ details.im_max_perday }}/天</div>
          <div class="content"><span class="content_title">简历点：</span>{{ details.download_resume_point }}(套餐包)+{{ details.purchase_resume_point }}(资源包)  <span style="color: #0078ff;cursor: pointer" @click="resume">使用记录</span></div>
          <div class="content"><span class="content_title">账户积分：</span>{{ details.points }} {{ config.points_byname }} <span style="color: #0078ff;cursor: pointer" @click="integral">使用记录</span></div>
        </div>
        <div class="systeminfo_box">
          <div class="title">系统信息</div>
          <div class="items item">
            <div class="info">
              <span class="label">所属销售：</span>
              <span class="text">{{ details.admin_name ? details.admin_name : '-' }}</span>
            </div>
          </div>
          <div class="receivingRecord" @click="forward">转交记录</div>
          <!--          <div class="item items">-->
          <!--            <div class="info">-->
          <!--              <span class="label">领取方式：</span>-->
          <!--              <span class="text">{{ details.admin_name ? details.admin_name : '-' }}</span>-->
          <!--            </div>-->
          <!--          </div>-->
          <div class="items item">
            <div class="info">
              <span class="label">跟进记录：</span>
              <span class="text">{{ details.follow_count }}</span>
            </div>
          </div>
          <div class="items item">
            <div class="info">
              <span class="label">最后跟进时间：</span>
              <span class="text">{{ details.last_visit_time ? details.last_visit_time : '-' }}</span>
            </div>
          </div>
          <div class="items item">
            <div class="info">
              <span class="label">最后跟进人：</span>
              <span class="text">{{ details.last_visit_admin_name ? details.last_visit_admin_name : '-' }}</span>
            </div>
          </div>
          <div class="items item">
            <div class="info">
              <span class="label">获得客户时间：</span>
              <span class="text">{{ details.addtime ? details.addtime : '-' }}</span>
            </div>
          </div>
          <div class="items item">
            <div class="info">
              <span class="label">企业登录时间：</span>
              <span class="text">{{ details.last_login_time ? details.last_login_time : '-' }}</span>
            </div>
          </div>
        </div>
      </el-col>
    </el-row>
    <el-dialog
      title="提示"
      append-to-body
      :visible.sync="dialogVisible"
      width="30%"
    >
      <el-form ref="form" :model="form" label-width="80px" style="text-align: center">
        <div v-if="edit_type == 'grade'" class="dia_box">
          客户等级：
          <el-select v-model="form.life_cycle_id" placeholder="请选择客户等级" class="input_w">
            <el-option v-for="item in regionData" :label="item.name" :value="item.id" />
          </el-select>
        </div>
        <div v-if="edit_type == 'labels'" class="dia_box">
          客户标签：
          <el-select v-model="form.labels" multiple placeholder="请选择客户标签" class="input_w">
            <el-option
              v-for="item in tags"
              :key="item.value"
              :label="item.name"
              :value="item.value"
            />
          </el-select>
        </div>
        <div v-if="edit_type == 'password'" class="dia_box">
          修改密码：
          <el-input v-model="form.password" style="width: 45%" show-password />
        </div>
        <div v-if="edit_type == 'mobile'" class="dia_box">
          修改会员手机号：
          <el-input v-model="form.mobile" style="width: 45%" />
        </div>
        <div v-if="edit_type == 'company_mobile'" class="dia_box">
          修改企业手机号：
          <el-input v-model="form.company_mobile" style="width: 45%" />
        </div>
        <div v-if="edit_type == 'is_display'" class="dia_box">
          显示状态：
          <el-radio v-model="form.is_display" label="0">不显示</el-radio>
          <el-radio v-model="form.is_display" label="1">显示</el-radio>
        </div>
        <div v-if="edit_type == 'email'" class="dia_box">
          修改邮箱：
          <el-input v-model="form.email" style="width: 45%" />
        </div>
        <div v-if="edit_type == 'remark'" class="dia_box">
          <div class="reason">
            修改备注：
          </div>
          <el-input v-model="form.remark" maxlength="100" show-word-limit type="textarea" rows="3" style="width: 300px;" />
        </div>
        <div v-if="edit_type == 'audit'" class="dia_box">
          认证状态：
          <el-radio-group v-model="form.audit">
            <el-radio
              v-for="(item, index) in form_options_audit"
              :key="index"
              :label="index"
            >
              {{ item }}
            </el-radio>
          </el-radio-group>
          <div v-if="form.audit == 2" style="margin-top: 20px;">
            <div class="reason">原因：</div>
            <el-input v-model="setAuditReason" type="textarea" rows="3" style="width: 300px;" />
          </div>

        </div>
        <div v-if="edit_type == 'examine'" class="dia_box">
          审核状态：
          <el-radio-group v-model="form.examine">
            <el-radio
              v-for="(item, index) in options_audit"
              :key="index"
              :label="index"
            >
              {{ item }}
            </el-radio>
          </el-radio-group>
        </div>
      </el-form>

      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit(edit_type)">确 定</el-button>
      </span>
    </el-dialog>
    <el-drawer
      v-if="innerDrawer"
      size="65%"
      :modal="false"
      title="转交记录"
      :append-to-body="true"
      :before-close="handleClose"
      :visible.sync="innerDrawer"
    >
      <div class="forwardTable">
        <el-table
          :data="forwardTableData"
          style="width: 100%"
        >
          <el-table-column
            prop="username"
            label="管理员名称"
          />
          <el-table-column
            prop="create_time"
            label="操作时间"
          />
          <el-table-column
            prop="address"
            label="操作类型"
          >
            <template scope="scope">
              <span v-if="scope.row.operation_type == 1">领取</span>
              <span v-if="scope.row.operation_type == 2">释放</span>
            </template>
          </el-table-column>
        </el-table>
        <el-col :span="24" style="text-align: right;margin-top: 20px;">
          <el-pagination
            background
            :current-page="forwardTablePage.page"
            :page-sizes="[10, 15, 20, 30, 40]"
            :page-size="forwardTablePage.pagesize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="forwardTablePage.total"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </el-col>
      </div>
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose">
        <i class="el-icon-close" />
      </div>
    </el-drawer>
    <el-dialog
      :visible.sync="callDialogVisible"
      width="30%"
      append-to-body
      class="call"
      :before-close="callHandleClose"
    >
      <div class="box-content">
        <div class="content">
          <div class="title1">您还尚未开通云呼叫服务</div>
          <div class="title2">扫码添加客服</div>
          <div class="title3">快速开通呼叫业务</div>
        </div>
        <div class="code">
          <img src="../../../../../assets/images/outbound/renew.png" alt="">
        </div>
        <div style="clear:both" />
        <div class="slogan">一键发起云呼叫，自动录音，助力提升沟通效率！</div>
        <div class="advantage">
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">免硬件设备</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">录音清晰无杂音</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">外显销售手机号</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">招聘行业专用线路</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">稳定性更高</div>
          </div>
          <div class="advantage-box">
            <div class="img"><img src="../../../../../assets/images/outbound/choose.png" alt=""></div>
            <div class="title">防封强、接通率高</div>
          </div>
        </div>
      </div>
    </el-dialog>
    <el-dialog
      :visible.sync="meetDialogVisible"
      width="30%"
      class="meet"
      append-to-body
      :before-close="meetHandleClose"
    >
      <div class="box-content">
        <div id="animation" class="img">
          <!--          <img src="../../../../../assets/images/outbound/meet.png" alt="">-->
        </div>
        <div class="hu">正在呼叫客户</div>
        <div class="telephone">{{ dialPhone }}</div>
        <div class="company">{{ dialName }}</div>
        <div class="tips">请您留意手机来电接听</div>
      </div>
    </el-dialog>
  </div>
</template>
<script>

import { classify, lifeCycle, updateCompany, getAllCrmTags, crmClueRelease } from '@/api/company_crm'
import { companyImgDelete } from '@/api/company'
import { memberLock } from '@/api/member'
import { validMobile } from '@/utils/validate'
import { outboundCall } from '@/api/outbound'
export default {
  name: 'Clientinfo',
  props: ['details', 'uid'],
  data () {
    var validateContactMobile = (rule, value, callback) => {
      if (!validMobile(value)) {
        callback(new Error('请输入正确的手机号'))
      } else {
        callback()
      }
    }
    return {
      forwardTableData: [],
      forwardTablePage: {
        page: 1,
        pagesize: 10,
        total: 0
      },
      innerDrawer: false,
      rules: {
        mobile: [
          { required: true, message: '请输入联系电话', trigger: 'blur' },
          { validator: validateContactMobile, trigger: 'blur' }
        ]
      },
      setAuditReason: '',
      form_options_audit: [],
      deal: '',
      form: {
        life_cycle_id: '',
        labels: [],
        password: '',
        mobile: '',
        company_mobile: '',
        is_display: '',
        email: '',
        remark: '',
        audit: 1,
        company_img: [],
        examine: 1
      },
      edit_type: '',
      dialogVisible: false,
      dialogTilteText: '', // 弹窗标题
      dialogWidth: '', // 弹窗宽度
      isDialog: false, // 弹窗展示状态
      currentDialog: '', // 当前展示那个弹窗组件
      isAddress: true, // 是否修改公司地址
      isRemark: true,
      lableAry: [], // 当前客户标签
      editAddrParams: { // 修改公司地址
        id: '',
        addr: ''
      },
      remarkParams: {
        cid: '',
        remark: ''
      },
      labelPparams: { // 配置标签
        labels: []
      },
      editTitleParams: { // 修改名称
        id: '',
        title: ''
      },
      bindParams: { // 绑定参数
        id: '',
        comid: ''
      },
      regionData: [],
      options_audit: [],
      tags: [],
      details_list: '',
      callDialogVisible: false,
      meetDialogVisible: false,
      dialPhone: '',
      dialName: ''
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.details_list = this.details
    this.form.life_cycle_id = this.details.life_cycle_id
    if (this.form.life_cycle_id >= 6){
      this.deal = this.form.life_cycle_id.toString()
    } else {
      this.deal = ''
    }
    this.form.mobile = this.details.mobile
    if (this.details.labels != ''){
      this.form.labels = this.details.labels.split(',')
      if (this.form.labels.length > 0){
        for (var i = 0; i <= this.form.labels.length - 1; i++){
          this.form.labels[i] = parseInt(this.form.labels[i])
        }
      }
    }
    this.form.is_display = this.details.is_display.toString()
    this.form.email = this.details.email
    this.form.remark = this.details.remark
    this.form.company_mobile = this.details.company_mobile
    this.forwardTableDataList(this.uid)
    this.getAllCrmTags()
    this.lifeCycle()
    this.classify()
  },
  methods: {
    handleSizeChange(val){
      this.forwardTablePage.pagesize = val
      this.forwardTableDataList(this.uid)
    },
    handleCurrentChange(val) {
      this.forwardTablePage.page = val
      this.forwardTableDataList(this.uid)
    },
    forwardTableDataList(uid){
      crmClueRelease({ 'uid': uid, 'page': this.forwardTablePage.page, 'pagesize': this.forwardTablePage.pagesize }).then(res => {
        if (res.data.rows){
          this.forwardTableData = res.data.rows
          this.forwardTablePage.page = res.data.pages.now_page
          this.forwardTablePage.total = res.data.pages.record_num
        } else {
          this.forwardTableData = []
        }
      })
    },
    forward(){
      this.innerDrawer = true
    },
    handleClose() {
      this.innerDrawer = false
    },
    integral(){
      this.$emit('integral')
    },
    resume(){
      this.$emit('resume')
    },
    getAllCrmTags() {
      getAllCrmTags().then(res => {
        for (var i = 0; i <= res.data.length - 1; i++){
          this.tags.push({ 'name': res.data[i].name, 'value': res.data[i].id })
        }
      }).catch(() => {

      })
    },
    locking(row) {
      var that = this
      let tip = ''
      let status = ''
      if (row.user_status == 1) {
        tip = '确定锁定该会员吗？'
        status = 0
      } else {
        tip = '确定解锁该会员吗？'
        status = 1
      }
      that
        .$confirm(tip, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: row.uid,
            status
          }
          memberLock(param).then(response => {
            that.$message.success(response.message)
            this.$emit('companyDetails')
            return true
          })
        })
        .catch(() => { })
    },
    delCompanyImg(){
      if (this.form.company_img.length <= 0) {
        this.$message.error('请选择删除的风采')
        return false
      }
      this.$confirm('确定删除该企业风采', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        companyImgDelete({ 'id': this.form.company_img }).then(res => {
          this.$message.success(res.message)
          this.$emit('companyDetails')
          this.dialogVisible = false
        }).catch(() => {

        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    checkClick(id){
      if (this.form.company_img.indexOf(id) == -1){
        this.form.company_img.push(id)
      } else {
        this.form.company_img.splice(this.form.company_img.indexOf(id), 1)
      }
    },
    gradeSelect(){
      this.form.life_cycle_id = this.deal
      this.submit('grade')
    },
    gradeBtn(id){
      this.form.life_cycle_id = id
      this.submit('grade')
    },
    submit(type){
      var data = ''
      if (type == 'grade'){
        data = { 'life_cycle_id': this.form.life_cycle_id, 'uid': this.uid }
      }
      if (type == 'labels'){
        data = {
          'labels': this.form.labels.join(','),
          'uid': this.uid
        }
      }
      if (type == 'mobile'){
        if (this.form.mobile == ''){
          this.$message.error('手机号码不能为空')
          return false
        }
        data = { 'mobile': this.form.mobile, 'uid': this.uid }
      }
      if (type == 'is_display'){
        data = { 'is_display': this.form.is_display, 'uid': this.uid }
      }
      if (type == 'email'){
        data = { 'email': this.form.email, 'uid': this.uid }
      }
      if (type == 'remark'){
        if (this.form.remark == ''){
          this.$message.error('请填写备注信息')
          return false
        }
        data = { 'remark': this.form.remark, 'uid': this.uid }
      }
      if (type == 'company_mobile'){
        if (this.form.company_mobile == ''){
          this.$message.error('手机号码不能为空')
          return false
        }
        data = { 'company_mobile': this.form.company_mobile, 'uid': this.uid }
      }
      if (type == 'password'){
        if (this.form.password == ''){
          this.$message.error('密码不能为空')
          return false
        }
        data = { 'password': this.form.password, 'uid': this.uid }
      }
      if (type == 'audit'){
        data = { 'audit': this.form.audit, 'reason': this.setAuditReason, 'uid': this.uid }
      }
      if (type == 'examine'){
        data = { 'examine': this.form.examine, 'company_img': this.form.company_img, 'uid': this.uid }
      }
      updateCompany(data).then(res => {
        this.$message.success(res.message)
        this.$emit('companyDetails')
        this.dialogVisible = false
      }).catch(() => {

      })
    },
    handleEdit(type){
      if (type == 'examine'){
        if (this.form.company_img.length <= 0) {
          this.$message.error('请选择审核的风采')
          return false
        }
      }
      this.edit_type = type
      this.dialogVisible = true
    },
    lifeCycle(){
      lifeCycle().then(res => {
        for (var i = 0; i <= res.data.length - 1; i++){
          this.regionData.push({
            'name': res.data[i].name,
            'id': res.data[i].id
          })
        }
      }).catch(() => {

      })
    },
    classify(){
      classify({ 'type': 'companyAudit,companyImgAudit' }).then(res => {
        const options_audit_arr = [...res.data.companyAudit]
        const authenticationScreenData = []
        options_audit_arr.forEach(el => {
          authenticationScreenData[el.id] = el.name
        })
        this.form_options_audit = [...authenticationScreenData]
        res.data.companyImgAudit.forEach(el => {
          this.options_audit[el.id] = el.name
        })
      }).catch(() => {

      })
    },
    clickDial(phone, name){
      var company_name = ''
      if (name == '' || name == null){
        company_name = '未完善企业资料'
      } else {
        company_name = name
      }
      this.$confirm('是否对【' + company_name + '】' + phone + ' 发起呼叫？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        outboundCall(
          { 'mobile': phone }
        ).then(res => {
          if (res.code == 200){
            this.meetDialogVisible = true
            this.dialPhone = phone
            this.dialName = name
          }
          if (res.code == 4040){
            this.callDialogVisible = true
          }
        }).catch((res) => {
        })
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

<style lang="scss" scoped>
.dial{
  position: relative;
  top:2px;
  left: 4px;
  width: 14px;
  height: 14px;
  display: inline-block;
  .disal_img{
    cursor:pointer;
    width: 14px;
    height: 14px;
    background: url('../../../../../assets/images/outbound/dial.png') no-repeat center;
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
      background: url('../../../../../assets/images/outbound/meet.png') no-repeat center;
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
.call{
  ::v-deep .el-dialog__body{
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog{
    border-radius: 16px;
  }
}
.forwardTable{
  padding:20px;
}
::v-deep .el-drawer{
  overflow: visible;
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
.receivingRecord{
  font-size: 14px;
  padding:8px;
  position: absolute;
  right: 46px;
  top: 59px;
  color: #409EFF;
  cursor:pointer;
  background: #ecf5ff;
  border: 1px solid  #b3d8ff;
}
.certification{
  width: 120px;
  height: 120px;
  margin-right: 15px;
  margin-top: 10px;
}
.empty_texts{
  margin-bottom: 20px;
  height:100%;
  text-align: center;
  font-size: 14px;
  color:#d1cfcf;
  line-height: 30px;
}
.email{
  margin-top: 20px;
  .emailTitle{
    display: inline-block;
  }
}
.emailBind{
  .emailBindTitle{
    display: inline-block;
  }
}
.overflow{
  display: inline-block;
  width: 74%;
  margin-bottom: 20px;
  padding-top: 17px;
  font-size: 14px;
  word-break: break-all;
  word-wrap: break-word;
}
.grade{
  display: inline-block;
  .grade_box{
    display: inline-block;
  }
  .grade_select{
    display: inline-block;
    width: 100px;
    ::v-deep .el-input__inner{
      height: 32px;
      line-height: 33px;
      font-size: 12px;
      border: 1px solid #ededed;
    }
    ::v-deep .el-input__suffix{
      height: 32px;
    }
    ::v-deep .el-input__icon {
      height: 33px;
      line-height: 32px;
    }
    &.action{
      border-color: #338aff;
      color: #338aff;
    }
  }
  .grade_item{
    cursor: pointer;
    padding:5px 10px;
    border: 1px solid #ededed;
    border-radius: 2px;
    font-size: 12px;
    display: inline-block;
    &:hover{
      border-color: #338aff;
      color: #338aff;
    }
    &.action{
      border-color: #338aff;
      color: #338aff;
    }
  }
}
.reason{
  display: inline-block;
  min-height: 60px;
}
.company_img{
  width: 100px;
  margin-right: 15px;
  height: 125px;
  position: relative;
  display: inline-block;
  .state{
    width: 100px;
    position: absolute;
    height:25px;
    font-size: 14px;
    text-align: center;
    line-height: 25px;
    bottom:15px;
    color: white;
    background: rgba(0,0,0,0.6);
  }
  .icon{
    position: absolute;
    top: 9px;
    right: -1px;
  }
  .check{
    position: absolute;
    top: 12px;
    left: 3px;
  }
}
.dia_box{
  margin: 20px 0px;
}

.text_width{
    padding: 0;
}

</style>
<style lang="scss" scoped>
.smalltip i {
   margin-right: 0px;
}
.smalltip{
  color:#c9c9c9;
  font-size: 12px;
}
.styles{
  border-bottom: 1px dotted #ebeef5;
}
::v-deep .el-col-12{
  border-right: 1px solid #ebeef5;
  }
  .content_title{
    color: #999;
  }
.package_information{
  margin: 10px 20px 0px 20px;
  box-shadow: 0 2px 12px 0 #d2e1f2;
  background: #fafcff;
  font-size: 14px;
  padding:15px;
  border: 1px solid #d2e1f2;
  border-radius: 5px;
  .titles{
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    line-height: 50px;
    padding: 0 20px;
  }
  .content{
    width: 45%;
    margin-left: 20px;
    display: inline-block;
    margin-bottom: 15px;
  }
}
::v-deep .el-image{
    margin-right: 15px;
    margin-top: 10px;
}
.item_img_empty{
  margin-bottom: 20px;
  img{
    width: 120px;
    height:120px;
    margin-right: 15px;
    margin-top: 10px;
  }
}
.item_img{
  margin-bottom: 20px;
  margin-left: 116px;
  img{
    width: 120px;
    height:120px;
    margin-right: 15px;
    margin-top: 10px;
  }
}
::v-deep .el-dialog__body{
  padding: 0px;
}
::v-deep .el-dialog__footer{
  padding: 10px 15px 30px;
}
.clientInfo_wrapper{
  height: calc(100vh - 194px - 52px);
  overflow-y: auto;
  padding: 10px 0;
  .title{
    font-size: 14px;
    font-weight: 600;
    line-height: 50px;
    padding: 0 6px;
    border-bottom: 1px dotted #ebeef5;
  }
  .tag{
    margin-top: 5px;
    background: #ecf5ff;
    border-color: #b3d8ff;
    display: inline-block;
    padding: 0 10px;
    height: 24px;
    border-radius: 2px;
    text-align: center;
    line-height: 24px;
    font-size: 12px;
    font-weight: 400;
    margin-right: 10px;
  }
  .clientInfo_box{
    padding:  0 30px;
  }
  .systeminfo_box{
    position: relative;
    padding:  0 30px;
  }
  .items{
    border-bottom: 1px solid #ebeef5;
  }
  .item{
    border-bottom: 1px dotted #ebeef5;
    display: flex;
    justify-content: space-between;
    .img{
      width: 250px;
      height:250px;
    }
    .info_width{
      width: 85%;
    }
    .info{
      display: flex;
      line-height: 20px;
      padding: 15px 6px;
      .auth{
        font-size: 14px;
        font-weight: 500;
        .icon_1 {
          color: #6EACE2;
        }
      }
      .clientinfo_type_1{
        color: #fe6135;
      }
      .label{
        width: 110px;
        color: #999;
        font-size: 14px;
        display: flex;
        align-items: center;
      }
      .text_width{
        width: 85%;
        .el-input{
          width: 100%;
        }
        .el-input__inner{
          padding: 0;
        }
      }
      .text{
        color: #000;
        font-size: 14px;
        .el-input{
          height: 28px;
        }
      }
    }
    .btn{
      display: flex;
      align-items: center;
    }
  }
}
::v-deep .el-input.is-disabled .el-input__inner{
  color: #606266;
}
::v-deep .el-button--small{
  padding: 8px 12px;
}
.el-dropdown-menu{
  width: 570px;
}
.dialog_footer{
  width: 100%;
  display: inline-block;
  text-align: left;
  padding-left: 85px;
}
.d_footer_pd{
  padding-left: 120px;
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
