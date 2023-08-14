<template>
  <div id="app" class="my_app" v-wechat-title="this.pageTitle">
    <Head>{{ base_info.fullname }}的简历</Head>
    <van-skeleton title avatar :row="10" :loading="mainLoading">
      <div class="box_1">
        <div class="content">
          <div class="up">
            <div class="avatar_box">
              <img :src="base_info.photo_img_src" :alt="base_info.fullname" />
              <div
                class="gender "
                :class="base_info.sex == 1 ? 'male' : 'female'"
              ></div>
            </div>
            <div class="tx1">
              <div class="name">{{ base_info.fullname }}</div>
              <div class="level_ico" v-if="base_info.high_quality == 1"></div>
              <div class="clear"></div>
            </div>
            <div class="tx2">
              {{ base_info.age }}岁 · {{ base_info.experience_text }} ·
              {{ base_info.education_text }}
            </div>
            <div class="tag" v-if="base_info.service_tag != ''">
              {{ base_info.service_tag }}
            </div>
          </div>
          <div class="mid">
            两周内：主动投递
            <span class="num">{{ apply_num }}</span>
            次；被下载
            <span class="num">{{ download_num }}</span>
            次；被浏览
            <span class="num">{{ base_info.click }}</span>
            次
          </div>
          <div class="down">
            {{ base_info.refreshtime }} 更新
            <div
              class="right"
              @click="showDetail = !showDetail"
              v-if="moreDetailBtn"
            >
              更多基本信息
            </div>
          </div>
          <div class="collect" @click="doFav">
            {{ has_fav == 1 ? "已收藏" : "收藏" }}
          </div>
          <div class="share" @click="doShare">分享</div>
        </div>
      </div>
      <div
        class="box_2"
        v-if="
          base_info.tag_text_arr != undefined &&
            base_info.tag_text_arr.length > 0
        "
      >
        <div class="box_head"><div class="txt">个性标签</div></div>
        <div class="content">
          <div
            class="item"
            v-for="(tag, index) in base_info.tag_text_arr"
            :key="index"
          >
            {{ tag }}
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="content_wrapper">
        <div class="box_3">
          <div class="box_head">
            <div class="txt">求职意向</div>
            <div class="right_text">{{base_info.current_text}}</div>
          </div>
          <div class="box_content">
            <div
              class="tx2"
              v-for="(item, index) in intention_list"
              :key="index"
            >
              <div class="name">
                [{{ item.district_text }}] {{ item.category_text }}
              </div>
              <div class="intent">
                {{ item.wage_text }}，{{ item.nature_text }}
                {{ field_rule.intention.trade!==undefined && field_rule.intention.trade.is_display === 1 && item.trade_text ? `，${item.trade_text}` : '' }}
              </div>
            </div>
          </div>
        </div>
        <!--联系方式-->
        <div class="box_cac">
          <div class="box_head"><div class="txt">联系方式</div><span class="phone_tip" v-if="show_contact == 1 && phone_protect_open && phone_protect_type==1">请使用 <span class="phone" v-text="cur_com_mobile">}</span> 的手机号拔号</span></div>

          <div class="box_content" v-if="show_contact == 1 && !phone_protect_open">
            <div class="item phone">手机：{{ contact_info.mobile }}</div>
            <div
              class="item wx"
              v-if="
                field_rule.contact.weixin != undefined &&
                  field_rule.contact.weixin.is_display == 1 &&
                  contact_info.weixin != ''
              "
            >
              微信：{{ contact_info.weixin }}
            </div>
            <div
              class="item email"
              v-if="
                field_rule.contact.email != undefined &&
                  field_rule.contact.email.is_display == 1 &&
                  contact_info.email != ''
              "
            >
              邮箱：{{ contact_info.email }}
            </div>
            <div
              class="item qq"
              v-if="
                field_rule.contact.qq != undefined &&
                  field_rule.contact.qq.is_display == 1 &&
                  contact_info.qq != ''
              "
            >
              QQ：{{ contact_info.qq }}
            </div>
          </div>
          <div class="code_pro_wrap" v-if="show_contact == 1 && phone_protect_open">
            <img class="secret" src="../assets/images/318.jpg"/>
            <div v-if="phone_protect_type==1" class="pro_tip">1.需要使用指定号码拔打,非指定号码无法拔通; 2.隐私号码有效<span v-text="phone_protect_timeout"></span>秒,过期后需再次点击拔号</div>
          </div>

          <div
            class="contact_tip"
            v-if="show_contact == 0 && show_contact_note == 'need_login'"
            @click="showLogin = true"
          >
            <div class="tx1">您尚未登录</div>
            <div class="tx2">
              <span class="link">点击登录</span>后可获取简历联系方式
            </div>
          </div>
          <div
            class="contact_tip"
            v-if="
              show_contact == 0 && show_contact_note == 'need_company_login'
            "
          >
            <div class="tx1">简历联系方式</div>
            <div class="tx2">
              仅对企业会员开放
            </div>
          </div>
          <div
            class="contact_tip"
            v-if="show_contact == 0 && show_contact_note == 'need_download'"
            @click="doDownload"
          >
            <div class="tx1"><span class="link">下载简历</span></div>
            <div class="tx2">获取简历联系方式</div>
          </div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="content_wrapper">
        <!--自我描述-->
        <div class="box_5" v-if="base_info.specialty != ''">
          <div class="box_head"><div class="txt">自我描述</div></div>
          <div class="box_content">
            <div class="dec">
              {{ base_info.specialty }}
            </div>
          </div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="content_wrapper">
        <!--教育经历-->
        <div class="box_6" v-if="education_list.length > 0">
          <div class="box_head"><div class="txt">教育经历</div></div>
          <div class="box_content">
            <div
              class="tx1"
              v-for="(item, index) in education_list"
              :key="index"
            >
              <div class="t1">{{ item.school }}</div>
              <div class="t2">
                {{ item.starttime | monthTimeFilter }}
                <span v-if="item.todate == 1">至今</span>
                <span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
              </div>
              <div class="t3">{{ item.education_text }}{{ field_rule.education.major!==undefined && field_rule.education.major.is_display === 1 && item.major ? `，${item.major}` : '' }}</div>
            </div>
          </div>
        </div>
        <!--工作经历-->
        <div class="box_7" v-if="work_list.length > 0">
          <div class="box_head"><div class="txt">工作经历</div></div>
          <div class="box_content">
            <div class="tx1" v-for="(item, index) in work_list" :key="index">
              <div class="t1">{{ item.companyname }}</div>
              <div class="t2">
                {{ item.starttime | monthTimeFilter }}
                <span v-if="item.todate == 1">至今</span>
                <span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
              </div>
              <div class="t3">{{ item.jobname }}</div>
              <div class="t4">
                {{ item.duty }}
              </div>
            </div>
          </div>
        </div>
        <!--培训经历-->
        <div
          class="box_8"
          v-if="
            resume_module.training!==undefined && resume_module.training.is_display == 1 && training_list.length > 0
          "
        >
          <div class="box_head"><div class="txt">培训经历</div></div>
          <div class="box_content">
            <div
              class="tx1"
              v-for="(item, index) in training_list"
              :key="index"
            >
              <div class="t1">{{ item.agency }}</div>
              <div class="t2">
                {{ item.starttime | monthTimeFilter }}
                <span v-if="item.todate == 1">至今</span>
                <span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
              </div>
              <div class="t3">{{ item.course }}</div>
              <div class="t4">
                {{ item.description }}
              </div>
            </div>
          </div>
        </div>
        <!--项目经历-->
        <div
          class="box_9"
          v-if="
            resume_module.project !== undefined && resume_module.project.is_display == 1 && project_list.length > 0
          "
        >
          <div class="box_head"><div class="txt">项目经历</div></div>
          <div class="box_content">
            <div class="tx1" v-for="(item, index) in project_list" :key="index">
              <div class="t1">{{ item.projectname }}</div>
              <div class="t2">
                {{ item.starttime | monthTimeFilter }}
                <span v-if="item.todate == 1">至今</span>
                <span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
              </div>
              <div class="t3">{{ item.role }}</div>
              <div class="t4">
                {{ item.description }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div
        class="form_split_10"
        v-if="
        resume_module.certificate!==undefined && resume_module.language!==undefined &&
          resume_module.certificate.is_display == 1 &&
            certificate_list.length > 0 &&
            resume_module.language.is_display == 1 &&
            language_list.length > 0
        "
      ></div>
      <div
        class="content_wrapper"
        v-if="
          resume_module.certificate!==undefined && resume_module.language!==undefined &&
          resume_module.certificate.is_display == 1 &&
            certificate_list.length > 0 &&
            resume_module.language.is_display == 1 &&
            language_list.length > 0
        "
      >
        <!--获得证书-->
        <div
          class="box_10"
          v-if="
          resume_module.certificate!==undefined &&
            resume_module.certificate.is_display == 1 &&
              certificate_list.length > 0
          "
        >
          <div class="box_head"><div class="txt">获得证书</div></div>
          <div class="box_content">
            <div
              class="tx1"
              v-for="(item, index) in certificate_list"
              :key="index"
            >
              {{ item.name }}
              <div class="right_txt">
                {{ item.obtaintime | monthTimeFilter }}
              </div>
            </div>
          </div>
        </div>
        <!--语言能力-->
        <div
          class="box_11"
          v-if="
          resume_module.language!==undefined &&resume_module.language.is_display == 1 && language_list.length > 0
          "
        >
          <div class="box_head"><div class="txt">语言能力</div></div>
          <div class="box_content">
            <div
              class="tx1"
              v-for="(item, index) in language_list"
              :key="index"
            >
              {{ item.language_text }}，{{ item.level_text }}
            </div>
          </div>
        </div>
      </div>
      <div
        class="form_split_10"
        v-if="resume_module.img!==undefined &&resume_module.img.is_display == 1 && img_list.length > 0"
      ></div>
      <div
        class="content_wrapper"
        v-if="resume_module.img!==undefined &&resume_module.img.is_display == 1 && img_list.length > 0"
      >
        <!--我的作品-->
        <div class="box_12">
          <div class="box_head"><div class="txt">我的作品</div></div>
          <div class="box_content">
            <swiper :options="swiperOption">
              <swiper-slide v-for="(item, index) in img_list" :key="index">
                <img :src="item.img_src" alt="简历照片作品" @click="previewImg(index)" />
              </swiper-slide>
              <div class="swiper-pagination" slot="pagination"></div>
            </swiper>
          </div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="content_wrapper">
        <div class="box_report">
          <div class="tx1">如遇无效虚假简历信息，请立即举报！</div>
          <div class="tx2">招聘过程中，若联系方式、求职状态不实请反馈。</div>
          <div class="tx3" @click="handlerReport">举报</div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="box_13">
        <div class="bottom_bar">
          <div class="item_call" @click="doTel">电话</div>
          <div class="item_chat" @click="doMsg">聊天</div>
          <div class="item_apply" v-if="show_contact===0" @click="doDownload">下载简历</div>
          <div class="item_apply" v-else @click="doInterview">面试邀请</div>
          <div class="clear"></div>
        </div>
      </div>
    </van-skeleton>
    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <Login
        :utype="1"
        :single_login="true"
        @afterLogin="afterLogin"
        :after_login_data="after_login_data"
      ></Login>
    </van-popup>

    <van-dialog
      v-model="showDirectService"
      title="下载简历"
      show-cancel-button
      :confirm-button-text="directServiceInfo.btnCn"
      @confirm="handlerDirectService"
    >
      <div class="dialog_tip_wrapper">
        <div class="tx1" v-if="directServiceInfo.use_type == 'points'">
          你的下载简历点数不足，下载该简历需要支付
          <span class="red">{{ directServiceInfo.need_points }}</span>
          {{ $store.state.config.points_byname }}。
        </div>
        <div class="tx1" v-if="directServiceInfo.use_type == 'money'">
          你的下载简历点数不足，下载该简历需要支付
          <span class="red">{{ directServiceInfo.need_expense }}</span>
          元。
        </div>
        <div class="tx2" v-if="parseInt(directServiceInfo.discount) > 0">
          购买简历包价格低至<span class="red">{{
            directServiceInfo.discount
          }}</span
          >折，<router-link
            to="/member/order/add/common?type=service&service_type=resume_package"
            class="blue"
            >立即了解</router-link
          >
        </div>
      </div>
    </van-dialog>
    <van-dialog v-model="codePro.show" show-cancel-button :confirm-button-text="codePro.btnCn" @confirm="callCodePro" confirm-button-color="#1989fa">
      <div class="line18 m-top">拔打号码</div>
      <div class="line18 color-orange font15 bold" v-text="codePro.x"></div>
      <div class="line18 font12">(电话<span class="color-orange" v-text="codePro.timeout"></span>秒后失效,请尽快拔打)</div>
      <div v-if="phone_protect_type==1" class="m-btm line18 font12 color-gray">仅支持使用<span v-text="codePro.a"></span>的手机卡拔号</div>
    </van-dialog>
    <van-popup v-model="showPayment" closeable position="bottom">
      <PopupPayment
        :amount="directServiceInfo.need_expense"
        @handlerSubmit="handlerDirectPay"
      ></PopupPayment>
    </van-popup>
    <van-dialog v-model="showDetail" title="更多基本信息">
      <div class="details_pop_box">
        <div
          class="detail_line"
          v-if="
            field_rule.basic.marriage !== undefined &&
              field_rule.basic.marriage.is_display == 1 &&
              base_info.marriage_text
          "
        >
          <div class="line_left">
            {{ field_rule.basic.marriage.field_cn }}：
          </div>
          <div class="line_right">
            {{ base_info.marriage_text }}
          </div>
          <div class="clear"></div>
        </div>
        <div
          class="detail_line"
          v-if="field_rule.basic.height !== undefined && field_rule.basic.height.is_display == 1 && base_info.height!='' && base_info.height>0"
        >
          <div class="line_left">{{ field_rule.basic.height.field_cn }}：</div>
          <div class="line_right">
            {{ base_info.height }}CM
          </div>
          <div class="clear"></div>
        </div>
        <div
          class="detail_line"
          v-if="
            field_rule.basic.major !== undefined &&
              field_rule.basic.major.is_display == 1 &&
              base_info.major_text
          "
        >
          <div class="line_left">{{ field_rule.basic.major.field_cn }}：</div>
          <div class="line_right">
            {{ base_info.major_text }}
          </div>
          <div class="clear"></div>
        </div>
        <div
          class="detail_line"
          v-if="
            field_rule.basic.householdaddress !== undefined &&
              field_rule.basic.householdaddress.is_display == 1 &&
              base_info.householdaddress
          "
        >
          <div class="line_left">
            {{ field_rule.basic.householdaddress.field_cn }}：
          </div>
          <div class="line_right">
            {{ base_info.householdaddress }}
          </div>
          <div class="clear"></div>
        </div>
        <div
          class="detail_line"
          v-if="
            field_rule.basic.residence !== undefined &&
              field_rule.basic.residence.is_display == 1 &&
              base_info.residence
          "
        >
          <div class="line_left">
            {{ field_rule.basic.residence.field_cn }}：
          </div>
          <div class="line_right">
            {{ base_info.residence }}
          </div>
          <div class="clear"></div>
        </div>
        <div
          class="detail_line"
          v-if="
            field_rule.basic.custom_field_1 !== undefined &&
              field_rule.basic.custom_field_1.is_display == 1 &&
              base_info.custom_field_1
          "
        >
          <div class="line_left">
            {{ field_rule.basic.custom_field_1.field_cn }}：
          </div>
          <div class="line_right">
            {{ base_info.custom_field_1 }}
          </div>
          <div class="clear"></div>
        </div>
        <div
          class="detail_line"
          v-if="
            field_rule.basic.custom_field_2 !== undefined &&
              field_rule.basic.custom_field_2.is_display == 1 &&
              base_info.custom_field_2
          "
        >
          <div class="line_left">
            {{ field_rule.basic.custom_field_2.field_cn }}：
          </div>
          <div class="line_right">
            {{ base_info.custom_field_2 }}
          </div>
          <div class="clear"></div>
        </div>
        <div
          class="detail_line"
          v-if="
            field_rule.basic.custom_field_3 !== undefined &&
              field_rule.basic.custom_field_3.is_display == 1 &&
              base_info.custom_field_3
          "
        >
          <div class="line_left">
            {{ field_rule.basic.custom_field_3.field_cn }}：
          </div>
          <div class="line_right">
            {{ base_info.custom_field_3 }}
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </van-dialog>
    <van-popup
      :lazy-render="false"
      v-model="showInvite"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <AddInvitation
        ref="child"
        from="detail"
        :apply_fullname="base_info.fullname"
        :resume_id="base_info.id"
        @closePopup="showInvite = false"
      ></AddInvitation>
    </van-popup>
    <van-popup
      :lazy-render="false"
      v-model="showTipoff"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <Tipoff ref="tipoff" :type="2" :target_id="base_info.id" :fullname="base_info.fullname" @closePopout="showTipoff = false"></Tipoff>
    </van-popup>
    <div class="alw-wx-layer" v-if="showWxLayer" @click="cancelShare"></div>
    <div class="alw-layer" v-if="showLayer" @click="cancelShare"></div>
    <SharePoster v-if="showPoster" @closePoster="closePoster" :type="'resume'" :info="shareInfo"></SharePoster>
    <van-overlay z-index="3" :show="showPoster" @click="showPoster=false"/>
    <van-popup v-model="showShare" position="bottom">
      <Share @cancelShare="cancelShare"
              @handleForward="handleForward"
              @handlePoster="handlePoster"></Share>
    </van-popup>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import Tipoff from '@/components/Tipoff'
import AddInvitation from '@/components/AddInvitation'
import { isWeiXin, parseTime } from '@/utils/index'
import PopupPayment from '@/components/service/PopupPayment'
import http from '@/utils/http'
import api from '@/api'
import Login from '@/components/Login'
import Share from '@/components/share/Share'
import SharePoster from '@/components/share/SharePoster'
import Vue from 'vue'
import { ImagePreview } from 'vant'
Vue.use(ImagePreview)
export default {
  name: 'ResumeShow',
  components: {
    Login,
    PopupPayment,
    AddInvitation,
    Tipoff,
    Share,
    SharePoster
  },
  filters: {
    monthTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}')
    }
  },
  data () {
    return {
      codePro: {
        show: false,
        x: '',
        timeout: 0,
        a: '',
        btnCn: '立即拔打'
      },
      showTipoff: false,
      showInvite: false,
      moreDetailBtn: false,
      showDetail: false,
      showPayment: false,
      showDirectService: false,
      directServiceInfo: {},
      pageTitle: '',
      enableClick: true,
      mainLoading: true,
      query_id: '',
      is_company_login: false,
      showLogin: false,
      after_login_data: {},
      resume_module: {},
      field_rule: { basic: {}, contact: {}, intention: {}, education: {} },
      base_info: {},
      intention_list: [],
      show_contact: 0,
      show_contact_note: '',
      contact_info: {},
      work_list: [],
      education_list: [],
      project_list: [],
      training_list: [],
      language_list: [],
      certificate_list: [],
      img_list: [],
      apply_num: 0,
      download_num: 0,
      has_fav: 0,
      phone_protect_open: false,
      phone_protect_timeout: 0,
      phone_protect_type: 0,
      cur_com_mobile: '',
      swiperOption: {
        pagination: {
          el: '.swiper-pagination',
          renderBullet: function (index, className) {
            return (
              '<span class="' + className + ' resume-show-swiper-span"></span>'
            )
          },
          bulletActiveClass: 'resume-show-swiper-bullet-active'
        },
        autoplay: true,
        slidesPerView: 3,
        slidesPerGroup: 3,
        freeMode: true,
        speed: 600,
        watchOverflow: true
      },
      shareInfo: {},
      showShare: false,
      showWxLayer: false,
      showLayer: false,
      showPoster: false,
      previewImgList: []
    }
  },
  created () {
    this.query_id = this.$route.params.id
    this.is_company_login =
      !!(this.$store.state.LoginOrNot === true && this.$store.state.LoginType == 1)
    // 请求数据
    this.fetchData()
  },
  methods: {
    callCodePro () {
      location.href = `tel:${this.codePro.x}`
    },
    async fetchData (next_method = null) {
      const params = {
        id: this.query_id
      }
      let res = await http.get(api.resumeshow, params)
      const {
        resume_module,
        field_rule,
        base_info,
        intention_list,
        show_contact,
        show_contact_note,
        contact_info,
        work_list,
        education_list,
        project_list,
        training_list,
        language_list,
        certificate_list,
        img_list,
        apply_num,
        download_num,
        has_fav,
        phone_protect_open,
        phone_protect_timeout,
        cur_com_mobile,
        phone_protect_type
      } = { ...res.data }
      this.resume_module = resume_module
      this.field_rule = field_rule
      this.base_info = base_info
      // 判断是否显示更多基本信息按钮
      for (const key in this.field_rule.basic) {
        let item = this.field_rule.basic[key]
        if (item.is_display == 1 && this.base_info[key]) {
          this.moreDetailBtn = true
          break
        }
      }
      this.pageTitle =
        this.base_info.fullname +
        '的简历 - ' +
        this.$store.state.config.sitename
      this.intention_list = intention_list
      this.show_contact = show_contact
      this.show_contact_note = show_contact_note
      this.contact_info = contact_info
      this.work_list = work_list
      this.education_list = education_list
      this.project_list = project_list
      this.training_list = training_list
      this.language_list = language_list
      this.certificate_list = certificate_list
      this.img_list = img_list
      this.apply_num = apply_num
      this.download_num = download_num
      this.has_fav = has_fav
      this.phone_protect_open = phone_protect_open
      this.phone_protect_timeout = phone_protect_timeout
      this.cur_com_mobile = cur_com_mobile
      this.phone_protect_type = phone_protect_type
      let wechatShareInfo = {
        fullname: base_info.fullname,
        sex: base_info.sex == 1 ? '男' : '女',
        age: base_info.age + '岁',
        education: base_info.education_text,
        experience: base_info.experience_text,
        intention_jobs: base_info.intention_jobs_text,
        imgUrl: base_info.photo_img_src
      }
      wxshare(wechatShareInfo, 'resumeshow', location.href)
      this.mainLoading = false
      if (next_method !== null) {
        this[next_method]()
      }
      this.previewImgList = this.img_list.map(function (item) {
        return item.img_src
      })
    },
    async doTel () {
      if (this.show_contact == 1) {
        if (this.phone_protect_open) {
          let res = await http.get(api.secret_phone, {resume_id: this.query_id})
          const {code, message, data} = res
          if (code == 200) {
            this.codePro.x = data.x
            this.codePro.timeout = data.timeout
            this.codePro.a = data.a
            this.codePro.show = true
            let that = this
            this.$nextTick(() => {
              let tmh = null
              let tm = function () {
                if (that.codePro.show && that.codePro.timeout > 0) {
                  that.codePro.timeout--
                  tmh = setTimeout(tm, 1000)
                } else {
                  that.codePro.show = false
                  clearTimeout(tmh)
                }
              }
              setTimeout(tm, 1000)
            })
          } else {
            this.$message.error(message)
          }
        } else {
          this.$dialog
            .confirm({
              title: '提示',
              message: '即将拨打号码：' + this.contact_info.mobile,
              confirmButtonText: '确定拨打'
            })
            .then(() => {
              window.location.href = `tel:${this.contact_info.mobile}`
            })
            .catch(() => {})
        }
      } else if (this.is_company_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doTel'
            }
          })
          .catch(() => {})
      } else {
        if (this.show_contact_note == 'need_download') {
          this.$notify('请先下载简历')
        }
      }
    },
    doMsg () {
      if (this.is_company_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doMsg'
            }
          })
          .catch(() => {})
      } else if (this.base_info.im_userid) {
        if (this.base_info.audit != 1) {
          this.$notify('该简历还未审核通过，不能继续此操作')
          return false
        }
        this.$router.push('/im/imshow/' + this.base_info.im_userid)
      } else {
        this.$notify('暂时无法与当前用户进行职聊')
        return false
      }
    },
    doInterview () {
      if (this.is_company_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doInterview'
            }
          })
          .catch(() => {})
      } else {
        if (this.base_info.audit != 1) {
          this.$notify('该简历还未审核通过，不能继续此操作')
          return false
        }
        this.showInvite = true
        this.$refs.child.initCB()
      }
    },
    doDownload () {
      if (this.is_company_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doDownload'
            }
          })
          .catch(() => {})
      } else {
        if (this.enableClick === false) {
          return false
        }
        this.enableClick = false
        if (this.base_info.audit != 1) {
          this.enableClick = true
          this.$notify('该简历还未审核通过，不能继续此操作')
          return false
        }
        const params = {
          resume_id: this.query_id
        }
        http
          .post(api.resumedownload, params)
          .then(res => {
            this.enableClick = true
            if (res.data.done == 0) {
              this.showDirectService = true
              this.directServiceInfo = {
                use_type: res.data.use_type,
                need_points: res.data.need_points,
                need_expense: res.data.need_expense,
                discount: res.data.discount,
                resume: this.query_id,
                btnCn: res.data.use_type == 'points' ? '立即兑换' : '立即支付'
              }
              return false
            } else {
              this.$notify({ type: 'success', message: res.message })
              this.fetchData()
            }
          })
          .catch(() => {
            this.enableClick = true
          })
      }
    },
    doFav () {
      if (this.is_company_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doFav'
            }
          })
          .catch(() => {})
      } else {
        if (this.enableClick === false) {
          return false
        }
        this.enableClick = false
        if (this.base_info.audit != 1) {
          this.enableClick = true
          this.$notify('该简历还未审核通过，不能继续此操作')
          return false
        }
        const params = {
          resume_id: this.query_id
        }
        let _api_url = this.has_fav == 1 ? api.resumefav_cancel : api.resumefav
        http
          .post(_api_url, params)
          .then(res => {
            this.enableClick = true
            this.has_fav = this.has_fav == 1 ? 0 : 1
            this.$notify({ type: 'success', message: res.message })
          })
          .catch(() => {
            this.enableClick = true
          })
      }
    },
    doShare () {
      this.showShare = true
    },
    cancelShare () {
      this.showShare = false
      this.showWxLayer = false
      this.showLayer = false
    },
    handleForward () {
      const agent = navigator.userAgent.toLowerCase()
      if (agent.indexOf('micromessenger') < 0) {
        setTimeout(() => {
          this.showLayer = true
        }, 150)
      } else {
        setTimeout(() => {
          this.showWxLayer = true
        }, 150)
      }
    },
    handlePoster () {
      this.shareInfo = {
        id: this.query_id,
        photo: this.base_info.photo_img_src,
        fullname: this.base_info.fullname,
        age: this.base_info.age,
        education: this.base_info.education_text,
        experience: this.base_info.experience_text,
        intention_jobs: this.base_info.intention_jobs_text,
        intention_district: this.base_info.intention_district_text,
        current: this.base_info.current_text
      }
      this.showPoster = true
    },
    closePoster () {
      this.showPoster = false
    },
    afterLogin (data) {
      this.showLogin = false
      this.is_company_login = true
      let method = null
      if (data.method != undefined) {
        method = data.method
      }
      this.fetchData(method)
    },
    handlerDirectService () {
      if (this.directServiceInfo.use_type == 'points') {
        this.handlerDirectPay('points')
      } else {
        this.showPayment = true
      }
    },
    handlerDirectPay (payment) {
      let openid = localStorage.getItem('weixinOpenid')
      let pay_data = {
        service_type: 'single_resume_down',
        deduct_points:
          this.directServiceInfo.use_type == 'points'
            ? this.directServiceInfo.need_points
            : 0,
        payment,
        resumeid: this.query_id,
        return_url: this.$store.state.config.mobile_domain + 'resume/' + this.query_id,
        openid: openid
      }
      http
        .post(api.company_pay_direct_service, pay_data)
        .then(res => {
          if (res.data.pay_status == 1) {
            this.$notify({ type: 'success', message: '支付成功' })
            this.fetchData(true)
            return false
          } else {
            this.handlerPay(res.data.parameter, payment)
          }
        })
        .catch(() => {})
    },
    handlerPay (parameter, payment) {
      if (payment == 'wxpay') {
        if (isWeiXin()) {
          let successUrl = this.$route.path
          this.$router.push({name: 'JsapiPay', params: {jsApiParameters: parameter.jsApiParameters, successUrl: successUrl}})
        } else {
          window.location.href = parameter
        }
      } else {
        window.location.href = parameter
      }
    },
    handlerReport () {
      if (this.is_company_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'handlerReport'
            }
          })
          .catch(() => {})
      } else {
        if (this.base_info.audit != 1) {
          this.$notify('该简历还未审核通过，不能继续此操作')
          return false
        }
        this.$refs.tipoff.initCB()
        this.showTipoff = true
      }
    },
    // 预览作品
    previewImg (index) {
      ImagePreview({
        images: this.previewImgList,
        showIndex: true,
        loop: false,
        startPosition: index,
        closeOnPopstate: true,
        closeable: true
      })
    }
  }
}
</script>

<style lang="scss">
  .box_report {
    position: relative;padding:17px 0;
    &::after {
      content: '';position: absolute;right: 55px;top: 50%;height: 46px;border-left: 1PX solid #f3f3f3;margin-top: -23px;
    }
    .tx3 {
      position: absolute;right: 0;top: 0;width: 45px;height: 100%;padding-top: 45px;color: #ff6600;font-size: 12px;text-align: center;
      background: url("../assets/images/report_ico_js.png") center 14px no-repeat;background-size: 19px 23px;
    }
    .tx1 {
      font-size: 13px;color: #ff6600;margin-bottom: 5px;
    }
    .tx2 {
      font-size: 12px;color: #666;
    }
  }
.resume-show-swiper-span {
  width: 6px;
  height: 6px;
  border-radius: 100%;
}
.resume-show-swiper-bullet-active {
  background-color: #1787fb;
  border-radius: 6px;
  opacity: 1;
}
</style>

<style lang="scss" scoped>
.details_pop_box {
  max-height: 300px;
  background: #ffffff;
  overflow-x: hidden;
  overflow-y: auto;
  font-size: 13px;
  padding: 20px;
  .detail_desc {
    width: 100%;
    background-color: #f5f5f5;
    line-height: 1.5;
    color: #666666;
    padding: 8px 20px;
    margin-top: 10px;
  }
  .detail_line {
    line-height: 1.5;
    margin-bottom: 5px;
    .line_left {
      float: left;
      color: #999999;
      text-align: right;
      width: 80px;
      line-height: 1.5;
    }
    .line_right {
      float: left;
      color: #333333;
      word-break: break-all;
      width: 190px;
      text-align: left;
      line-height: 1.5;
      .a_black {
        color: #333333;
        text-decoration: none;
      }
      .a_blue {
        color: #1887fb;
        text-decoration: none;
      }
    }
  }
}
.my_app {
  padding-bottom: 45px;
}
.box_13 {
  .bottom_bar {
    .item_apply {
      float: left;
      padding: 12px 0;
      height: 100%;
      text-align: center;
      background-color: #1787fb;
      color: #ffffff;
      font-size: 18px;
      width: 255px;
    }
    .item_chat {
      float: left;
      width: 60px;
      height: 100%;
      padding: 32px 0 4px;
      text-align: center;
      font-size: 10px;
      color: #333333;
      background: url("../assets/images/chat_ico.svg") center 9px no-repeat;
      background-size: 17px;
      position: relative;
      &::after {
        position: absolute;
        box-sizing: border-box;
        content: " ";
        pointer-events: none;
        right: 0;
        top: 0;
        left: 0;
        border-bottom: 0.026667rem solid #f3f3f3;
      }
    }
    .item_call {
      float: left;
      width: 60px;
      height: 100%;
      padding: 32px 0 4px;
      text-align: center;
      font-size: 10px;
      color: #333333;
      background: url("../assets/images/calling_ico.svg") center 9px no-repeat;
      background-size: 17px;
      position: relative;
      &::after {
        position: absolute;
        box-sizing: border-box;
        content: " ";
        pointer-events: none;
        right: 0;
        top: 0;
        left: 0;
        border-bottom: 0.026667rem solid #f3f3f3;
      }
    }
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    z-index: 3;
    height: 50px;
    width: 100%;
  }
  position: relative;
  width: 100%;
  height: 50px;
  background-color: #ffffff;
}
.box_12 {
  width: 100%;
  .swiper-pagination {
    bottom: 0px;
  }
  .swiper-slide {
    text-align: center;
  }
  .box_content {
    img {
      width: 100px;
      height: 100px;
      border: 0;
      border-radius: 3px;
      display: inline-block;
      vertical-align: top;
      margin-bottom: 23px;
    }
    padding-bottom: 10px;
    width: 100%;
  }
}
.box_11 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      .right_txt {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #666666;
      }
      &:not(:last-child) {
        margin-bottom: 14px;
      }
      font-size: 15px;
      color: #333333;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_10 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #666666;
      }
      &:not(:last-child) {
        margin-bottom: 14px;
      }
      font-size: 15px;
      color: #333333;
      position: relative;
    }
    padding-bottom: 17px;
  }
}
.box_9 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: " ";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      &:not(:last-child) {
        margin-bottom: 15px;
      }
      padding: 0 25px;
      position: relative;
    }
    padding-bottom: 17.5px;
  }
}
.box_8 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: " ";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      &:not(:last-child) {
        margin-bottom: 15px;
      }
      padding: 0 25px;
      position: relative;
    }
    padding-bottom: 17.5px;
  }
}
.box_7 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: " ";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      &:not(:last-child) {
        margin-bottom: 15px;
      }
      padding: 0 25px;
      position: relative;
    }
    padding-bottom: 17.5px;
  }
}
.box_6 {
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  .box_content {
    .tx1 {
      &::before {
        content: "";
        position: absolute;
        left: 4px;
        top: 6px;
        width: 4px;
        height: 4px;
        border-radius: 100%;
        border: 2px solid #3388ff;
        background-color: #ffffff;
      }
      &::after {
        content: " ";
        position: absolute;
        left: 7px;
        top: 16px;
        height: 93%;
        border-right: 1px solid #f5f5f5;
      }
      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .t2 {
        font-size: 12px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 6.5px;
      }
      .t1 {
        font-size: 15px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }
      &:not(:last-child) {
        margin-bottom: 15px;
      }
      padding: 0 25px;
      position: relative;
    }
    padding-bottom: 17.5px;
  }
}
.box_5 {
  width: 100%;
  .box_content {
    .tag {
      margin-bottom: 10px;
      .van-tag + .van-tag {
        margin-left: 5px;
      }
    }
    .dec {
      line-height: 1.8;
      font-size: 13px;
      color: #666666;
      word-break: break-all;
    }
    padding-bottom: 20px;
  }
}
.box_cac {
  .phone_tip{
    display:inline-block;position: absolute;right:0;top:50%;transform:translate(0, -50%);color:#666;font-size:12px;
    .phone{color:#ffa54e;}
  }
  .code_pro_wrap{
    .secret{width:100%;}
    .pro_tip{margin-top: .08rem;color: #888;font-size:12px;}
  }
  .box_content {
    .item {
      &.phone {
        background: url("../assets/images/cac_phone_ico.svg") 2px center
          no-repeat;
        background-size: 16px;
      }
      &.wx {
        background: url("../assets/images/cac_wx_ico.svg") 0 center no-repeat;
        background-size: 16px;
      }
      &.email {
        background: url("../assets/images/cac_email_ico.svg") 0 center no-repeat;
        background-size: 16px;
      }
      &.qq {
        background: url("../assets/images/cac_qq_ico.svg") 0 center no-repeat;
        background-size: 16px;
      }
      font-size: 15px;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding: 0 20px 0 23px;
      word-break: break-all;
      &:not(:last-child) {
        margin-bottom: 8px;
      }
    }
  }
  .contact_tip {
    .tx2 {
      padding-bottom: 15.5px;
    }
    .tx1 {
      padding-top: 16px;
      margin-bottom: 3px;
    }
    .link {
      color: #1787fb;
    }
    text-align: center;
    font-size: 14px;
    color: #333333;
    box-shadow: 0 0 8px #c2c2c2;
    border-radius: 7px;
  }
  width: 100%;
  padding-bottom: 17px;
  border-bottom: 1px solid #f5f5f5;
}
.box_3 {
  .tx2 {
    &:not(:first-child) {
      margin-top: 13.5px;
    }
    .intent {
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .name {
      font-size: 15px;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      margin-bottom: 5.5px;
    }
  }
  .box_content {
    padding-bottom: 17.5px;
  }
  width: 100%;
  border-bottom: 1px solid #f5f5f5;
  border-top: 1px solid #f5f5f5;
}
.content_wrapper {
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;
}
.box_2 {
  .content {
    .item {
      float: left;
      font-size: 13px;
      color: #8096a3;
      padding: 8px 15px;
      background-color: #e9f8ff;
      border-radius: 33px;
      margin-bottom: 9px;
      margin-right: 9px;
    }
    padding-bottom: 10px;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 0 0 17px;
}
.box_head {
  .txt {
    font-size: 18px;
    color: #333333;
    font-weight: bold;
  }
  .right_text {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 13px;
    color: #999;
  }
  position: relative;
  width: 100%;
  padding: 21.5px 0;
}
.box_1 {
  .content {
    .share {
      position: absolute;
      right: 16px;
      top: 18px;
      font-size: 12px;
      color: #999999;
      padding: 4px 8px 4px 25px;
      border-radius: 7px;
      background: #f9f9f9 url("../assets/images/com_show_share_ico.png") 8px
        center no-repeat;
      background-size: 12px;
    }
    .collect {
      position: absolute;
      right: 80px;
      top: 18px;
      font-size: 12px;
      color: #999999;
      padding: 4px 8px 4px 25px;
      border-radius: 7px;
      background: #f9f9f9 url("../assets/images/com_show_col_ico.png") 8px
        center no-repeat;
      background-size: 12px;
    }
    .down {
      .right {
        &::after {
          position: absolute;
          right: 2px;
          top: 11px;
          width: 6px;
          height: 6px;
          border-top: 1px solid #666666;
          border-right: 1px solid #666666;
          transform: rotate(45deg);
          content: " ";
        }
        position: absolute;
        right: 2px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 12px;
        color: #999999;
        padding: 5px 11px 5px 0;
      }
      position: relative;
      padding: 12.5px 2px;
      font-size: 12px;
      color: #999999;
      border-top: 1px solid #f8f8f8;
    }
    .mid {
      .num {
        color: #ff6600;
      }
      padding: 13px 0;
      font-size: 12px;
      color: #999999;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .up {
      .tag {
        padding: 1px 5px 1px 17px;
        border-radius: 3px;
        color: #ffffff;
        font-size: 10px;
        width: fit-content;
        margin-top: 9px;
        background: #ffa57d url("../assets/images/fab_ico.svg") 5px center
          no-repeat;
        background-size: 10px;
      }
      .tx2 {
        font-size: 15px;
        padding-right: 20px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        color: #666666;
      }
      .tx1 {
        margin-bottom: 8px;
        .level_ico {
          float: left;
          margin-left: 10px;
          width: 36px;
          height: 27px;
          background: url("../assets/images/resume_list_level_ico.png") 0 center
            no-repeat;
          background-size: 36px 15px;
        }
        .name {
          float: left;
          font-size: 19px;
          font-weight: bold;
          color: #333333;
        }
      }
      .avatar_box {
        .gender {
          &.female {
            background: #ff8d65 url("../assets/images/female_ico.svg") center
              no-repeat;
            background-size: 9px;
          }
          &.male {
            background: #4fa5fa url("../assets/images/male_ico.svg") center
              no-repeat;
            background-size: 9px;
          }
          position: absolute;
          right: 0;
          bottom: 2px;
          width: 13px;
          height: 13px;
          border-radius: 100%;
        }
        img {
          width: 49px;
          height: 49px;
          border: 0;
          border-radius: 100%;
        }
        position: absolute;
        left: 0;
        top: 30px;
        width: 49px;
        height: 49px;
      }
      position: relative;
      padding: 31px 0 0 61px;
    }
    position: relative;
    width: 350px;
    margin: 0 auto;
    box-shadow: 0 0 5px #c2c2c2;
    padding: 0 17px;
    border-radius: 7px;
  }
  width: 100%;
  background-color: #ffffff;
  border-top: 1px solid #f3f3f3;
  padding-top: 20px;
}
.orange-phone{color:#ffa54e;font-weight:bold;}
.font12{
  font-size:12px;
}
.font15{
  font-size:15px;
}
.line18{
  line-height:25px;
  text-align:center;
}
.color-orange{
  color:#ffa54e
}
.m-top{margin-top:25px;}
.m-btm{margin-bottom:20px;}
.bold{font-weight:bold;}
</style>
