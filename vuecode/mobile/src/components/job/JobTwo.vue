<template>
  <div id="app" class="my_app">
    <Meta
      v-if="base_info.jobname !== undefined"
      pagealias="jobshow"
      :custom_data="{
        jobname: base_info.jobname,
        companyname: com_info.companyname,
        nature: base_info.nature_text,
        category: base_info.category_text,
        district: base_info.district_text,
      }"
    />
    <Head>职位详情</Head>
    <van-skeleton title avatar :row="10" :loading="mainLoading">
      <div class="box_1">
        <div
          class="collect"
          :class="has_fav == 1 ? 'active_collect' : ''"
          @click="doFav"
        >
          <div class="ico"></div>
          <!-- <div class="text">{{ has_fav == 1 ? "已收藏" : "收藏" }}</div> -->
        </div>
        <div class="share" @click="doShare">
          <div class="ico"></div>
          <!-- <div class="text">分享</div> -->
        </div>
        <div class="tx1">
          {{ base_info.jobname }}
        </div>
        <div class="tx2">
          {{ base_info.wage_text }}
          <span>更新：{{ base_info.refreshtime }}</span>
		  <img v-if="base_info.job_status!=1" class="invalid_job" src="../../assets/images/jobshow/invalid_job.png" />
        </div>
        <!-- <div class="tx3">
          {{ base_info.district_text }} · {{ base_info.experience_text }} ·
          {{ base_info.education_text }}
          <div class="time">更新：{{ base_info.refreshtime }}</div>
        </div> -->
        <!-- <div class="chat_bar">
          {{ base_info.click }}人查看
          <div class="right" @click="doMsg">在线直聊</div>
        </div> -->
      </div>
      <!-- <div class="box_2" v-if="com_info.audit == 1">
        <div class="cell">企业认证</div>
        <div class="cell">多重审核</div>
        <div class="cell">信誉担保</div>
        <div class="cell">违规严惩</div>
        <div class="auth_ico">认证</div>
        <div class="bg_box"></div>
        <div class="clear"></div>
      </div> -->
      <div class="form_split_10"></div>
      <div class="box_6">
        <div class="put">
          <span class="title">
          职位要求
          </span>
        </div>
        <div class="tx3" style="font-size: 14px">
          {{ base_info.district_text }} · {{ base_info.experience_text }} ·
          {{ base_info.education_text }}
        </div>
      </div>
      <div
        class="box_4"
        v-if="
          field_rule.basic.tag !== undefined &&
          field_rule.basic.tag.is_display == 1 &&
          base_info.tag_text_arr !== undefined &&
          base_info.tag_text_arr.length > 0
        "
      >
        <div class="put"><span class="title">职位福利</span></div>
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
      <div class="form_split_10"></div>
      <div class="box_13">
        <div class="boss_all">
          <p class="boss_tx"></p>
          <div class="boss_detail">
            <p
              v-if="
                contact_info.contact == '' || contact_info.contact == undefined
              "
            >
              人事经理
            </p>
            <p v-else>{{ contact_info.contact }}</p>
            <p @click="$router.push('/company/' + com_info.id)">
              {{ com_info.companyname }}
            </p>
          </div>
          <span class="boss_talk_icon" @click="doMsg"></span>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="box_6">
        <div class="put"><span class="title">职位描述</span></div>
        <div class="content">
          <div class="b_item">
            性质：
            <span>{{ base_info.nature_text }}</span>
          </div>
          <div
            class="b_item"
            v-if="
              base_info.amount_text &&
              field_rule.basic.amount !== undefined &&
              field_rule.basic.amount.is_display == 1
            "
          >
            人数：
            <span>{{ base_info.amount_text }}</span>
          </div>
          <div
            class="b_item"
            v-if="
              base_info.department &&
              field_rule.basic.department !== undefined &&
              field_rule.basic.department.is_display == 1
            "
          >
            部门：
            <span>{{ base_info.department }}</span>
          </div>
          <div
            class="b_item"
            v-if="
              base_info.age_text &&
              field_rule.basic.age !== undefined &&
              field_rule.basic.age.is_display == 1
            "
          >
            年龄：
            <span>{{ base_info.age_text }}</span>
          </div>
          <div
            class="b_item"
            v-if="
              base_info.custom_field_1 &&
              field_rule.basic.custom_field_1 !== undefined &&
              field_rule.basic.custom_field_1.is_display == 1
            "
          >
            {{ field_rule.basic.custom_field_1.field_cn }}：
            <span>{{ base_info.custom_field_1 }}</span>
          </div>
          <div
            class="b_item"
            v-if="
              base_info.custom_field_2 &&
              field_rule.basic.custom_field_2 !== undefined &&
              field_rule.basic.custom_field_2.is_display == 1
            "
          >
            {{ field_rule.basic.custom_field_2.field_cn }}：
            <span>{{ base_info.custom_field_2 }}</span>
          </div>
          <div
            class="b_item"
            v-if="
              base_info.custom_field_3 &&
              field_rule.basic.custom_field_3 !== undefined &&
              field_rule.basic.custom_field_3.is_display == 1
            "
          >
            {{ field_rule.basic.custom_field_3.field_cn }}：
            <span>{{ base_info.custom_field_3 }}</span>
          </div>
          <div class="clear"></div>
          <div class="tx1"><span class="title">岗位职责：</span></div>
          <div class="des">
            <span style="white-space: pre-line">{{ base_info.content }}</span>
          </div>
        </div>
      </div>
      <div class="box_3" v-if="base_info.address != ''">
        <div class="put">
          <span class="title">工作地址</span>
        </div>
        <div class="address">
          {{ base_info.address }}
        </div>
        <div class="bg">
          <div class="box" @click="locationToBdmap">
            <div class="tx1">{{ com_info.companyname }}</div>
            <div class="tx2">{{ base_info.address }}</div>
          </div>
        </div>
        <div class="distance" v-if="distance != ''">距您{{ distance }}</div>
      </div>
      <div class="box_5" style="padding-top: 0">
        <div class="put" style="padding-top:0;"><span class="title">职位动态</span></div>
        <div class="content">
          <div class="item">
            <div class="tx1">{{ watch_percent }}</div>
            <div class="tx2">企业简历查看率</div>
          </div>
          <div class="item">
            <div class="tx1">{{ last_login_time }}</div>
            <div class="tx2">企业最近登录</div>
          </div>
        </div>
      </div>
      <!-- <div class="form_split_10"></div> -->
      <div class="box_8">
        <div class="put">
          <span class="title">联系方式</span>
          <span
            class="phone_tip"
            v-if="
              show_contact == 1 && phone_protect_open && phone_protect_type == 1
            "
          >
            请使用
            <span class="phone" v-text="cur_user_mobile"></span>
            的手机号拔号
          </span>
        </div>
        <div
          class="contact_info"
          v-if="!phone_protect_open && show_contact == 1"
        >
          <div class="info_line">
            联系人：
            <span>{{ contact_info.contact }}</span>
          </div>
          <div class="info_line">
            联系手机：
            <span v-if="contact_info.is_secrecy === 1">{{ contact_info.mobile }}</span>
            <span style="color: #1787FB" v-else>企业已隐藏当前手机号</span>
          </div>
          <div
            class="info_line"
            v-if="
              field_rule.contact.telephone !== undefined &&
              field_rule.contact.telephone.is_display == 1 &&
              contact_info.telephone != ''
            "
          >
            联系固话：
            <span>{{ contact_info.telephone }}</span>
          </div>
          <div
            class="info_line"
            v-if="
              field_rule.contact.weixin !== undefined &&
              field_rule.contact.weixin.is_display == 1 &&
              contact_info.weixin != ''
            "
          >
            联系微信：
            <span>{{ contact_info.weixin }}</span>
          </div>
          <div
            class="info_line"
            v-if="
              field_rule.contact.email !== undefined &&
              field_rule.contact.email.is_display == 1 &&
              contact_info.email != ''
            "
          >
            联系邮箱：
            <span>{{ contact_info.email }}</span>
          </div>
          <div
            class="info_line"
            v-if="
              field_rule.contact.qq !== undefined &&
              field_rule.contact.qq.is_display == 1 &&
              contact_info.qq != ''
            "
          >
            联系QQ：
            <span>{{ contact_info.qq }}</span>
          </div>
        </div>
        <div
          class="code_pro_wrap"
          v-if="show_contact == 1 && phone_protect_open"
        >
          <img class="secret" src="../../assets/images/318.jpg" />
          <div v-if="phone_protect_type == 1" class="pro_tip">
            1.需要使用指定号码拔打,非指定号码无法拔通; 2.隐私号码有效<span
              v-text="phone_protect_timeout"
            ></span
            >秒,过期后需再次点击拔号
          </div>
        </div>
        <div
          class="contact_login"
          v-if="show_contact == 0 && show_contact_note == 'need_login'"
          @click="showLogin = true"
        >
          <p>您尚未登录，点击登录后可查看企业联系方式</p>
          <span class="link"
            >点击登录
            <span></span>
          </span>
        </div>
        <div
          class="contact_login contact_resume"
          v-if="show_contact == 0 && show_contact_note == 'need_personal_login'"
        >
          <div class="tx1">职位联系方式</div>
          <div class="tx2">仅对求职者会员开放</div>
        </div>
        <div
          class="contact_login contact_resume"
          v-if="show_contact == 0 && show_contact_note == 'need_resume'"
          @click="$router.push('/member/personal/index')"
        >
          <div class="tx1">您还没有简历，创建简历后可查看企业联系方式</div>
          <span class="link">创建简历<span></span></span>
        </div>
        <div
          class="contact_login contact_resume"
          v-if="show_contact == 0 && show_contact_note == 'need_apply'"
          @click="doApply"
        >
          <div class="tx1">企业要求投递简历后才可查看联系方式</div>
          <span class="link">投递简历<span></span></span>
        </div>
        <div
          class="contact_login contact_delivery"
          v-if="show_contact == 0 && show_contact_note == 'company_close'"
        >
          <div class="tx1" v-if="has_apply == 1">企业未开启查看联系方式</div>
          <div class="tx2" v-if="has_apply == 1">
            您已投递简历，请等待企业联系
          </div>
          <div class="tx1" v-if="has_apply == 0">
            企业未开启查看联系方式，请直接投递简历
          </div>
          <span class="link" v-if="has_apply == 0" @click="doApply"
            >投递简历<span></span></span>
        </div>
      </div>
      <!-- <div class="form_split_10"></div> -->
      <div class="box_9">
        <div class="put">
          <span class="title">竞争力分析</span>
          <div
            class="right_arrow"
            v-if="is_personal_login === true"
            @click="openCompetitive"
          >查看匹配度</div>
        </div>
        <div class="doubt">
          <div
            class="content"
            v-if="is_personal_login === false"
            @click="showLogin = true"
          >
            <span>登录</span>
            后查看你的匹配度
          </div>
        </div>
        <div class="p_con">
          <div class="self_content" :class="'level_' + match_level">
            <div class="here">
              <span v-if="match_level == 1">你在这里</span>
              <span v-if="match_level == 1" class="dao_3"></span>
            </div>
            <div class="here">
              <span v-if="match_level == 2">你在这里 </span>
              <span v-if="match_level == 2" class="dao_3"></span>
            </div>
            <div class="here">
              <span v-if="match_level == 3">你在这里</span>
              <span v-if="match_level == 3" class="dao_3"></span>
            </div>
            <div class="here">
              <span v-if="match_level == 4">你在这里</span>
              <span v-if="match_level == 4" class="dao_3"></span>
            </div>
          </div>
          <div class="self_content" :class="'level_' + match_level">
            <span class="level_img level_img_1"></span>
            <span class="level_img level_img_2"></span>
            <span class="level_img level_img_3"></span>
            <span class="level_img_big level_img_big_4"></span>
            <!-- <div class="sp_block s1"></div>
            <div class="sp_block s2"></div>
            <div class="sp_block s3"></div> -->
            <!-- <div class="percent"></div> -->
            <!-- <div class="cir_block" v-if="is_personal_login === true"></div> -->
            <!-- <div class="percent_text t1">一般</div>
            <div class="percent_text t2">良好</div>
            <div class="percent_text t3">优秀</div> -->
          </div>
          <div class="self_content" :class="'level_' + match_level">
            <div class="percent_text t1">一般</div>
            <div class="percent_text t2">良好</div>
            <div class="percent_text t3">优秀</div>
            <div class="percent_text t4">特别优秀</div>
          </div>
        </div>
      </div>
      <div class="form_split_10"></div>

      <div class="box_10">
        <div class="put"><span class="title">公司信息</span></div>
        <div class="info" @click="$router.push('/company/' + com_info.id)">
          <div class="up">
            <div class="logo_box">
              <img :src="com_info.logo_src" :alt="com_info.companyname" />
            </div>
            <div class="tx1">
              <div class="name">{{ com_info.companyname }}</div>
              <div class="auth_ico" v-if="com_info.audit == 1"></div>
              <div class="crw_ico" v-if="com_info.setmeal_icon != ''">
                <img :src="com_info.setmeal_icon" alt="" />
              </div>
              <div class="clear"></div>
            </div>
            <!-- <div class="tx2">{{ com_info.district_text }}</div> -->
            <div class="tx3">
              {{ com_info.nature_text }} · {{ com_info.scale_text }} ·
              {{ com_info.trade_text }}
            </div>
          </div>
          <div class="down">
            <!-- 热招：
            <span class="link">{{ com_info.first_jobname }}</span>
            等{{ com_info.jobnum }}个岗位 -->
          </div>
        </div>
        <div class="box_7">
          <div class="tx1">{{ $store.state.config.sitename }}温馨提示</div>
          <div class="tx2">
            求职过程请勿缴纳费用，谨防诈骗！如遇无效、虚假、诈骗信息，请立即举报，我们将及时处理
            <span class="report" @click="handlerReport">立即举报</span>
          </div>
        </div>
      </div>

      <!-- <div class="box_sp" v-if="similar != undefined && similar.length > 0">
        -这还有一些相似职位-
      </div> -->
      <div class="form_split_10"></div>
      <div class="box_11" v-if="similar != undefined && similar.length > 0">
        <div class="put"><span class="title">相似职位</span></div>
        <div
          v-for="(item, index) in similar"
          :key="index"
          @click="toDetail(item.id)"
        >
          <div class="list">
            <div class="tx1">
              <div class="name">{{ item.jobname }}</div>
              <div class="worry_ico" v-if="item.emergency == 1">急</div>
              <div class="clear"></div>
              <div class="wage">{{ item.wage_text }}</div>
            </div>
            <div class="tx2">
              {{ item.education_text }} · {{ item.experience_text }} ·
              {{ item.district_text }}
            </div>
            <div class="time">{{ item.refreshtime }}</div>
            <div class="tag_wrapper" v-if="item.tag_text_arr">
              <div
                class="tag_item"
                v-for="(tag, ind) in item.tag_text_arr"
                :key="ind"
              >
                {{ tag }}
              </div>
              <div class="clear"></div>
            </div>
            <div class="company">
              <div class="name">{{ item.companyname }}</div>
              <div class="auth_ico" v-if="item.company_audit == 1"></div>
              <div class="crw_ico" v-if="item.setmeal_icon != ''">
                <img :src="item.setmeal_icon" alt="" />
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
      <Subscribe></Subscribe>
      <div class="box_12">
        <div class="bottom_bar">
          <div class="item_call" @click="doTel">电话</div>
          <div
            :class="
              has_fav == 1 ? 'item_collect item_collect_ac' : 'item_collect'
            "
            @click="doFav"
          >
            {{ has_fav == 1 ? "已收藏" : "收藏" }}
          </div>
          <div
            class="item_apply"
            :class="has_apply == 1 ? 'item_apply_already' : ''"
            @click="doApply"
          >
            {{ has_apply == 1 ? "已投递" : "投递简历" }}
          </div>
          <div class="item_chat" @click="doMsg">在线职聊</div>
          <div class="clear"></div>
        </div>
      </div>
    </van-skeleton>
    <baidu-map
      class="bm-view"
      :ak="$store.state.config.map_ak"
      @ready="handlerMap"
    ></baidu-map>
    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width: 100%; height: 100%"
    >
      <Login
        :utype="2"
        :single_login="true"
        @afterLogin="afterLogin"
        :after_login_data="after_login_data"
        :goback_custom="true"
        @gobackCustomMethod="closeLogin"
      ></Login>
    </van-popup>
    <van-popup
      v-model="showCompetitive"
      position="right"
      :overlay="false"
      style="width: 100%; height: 100%"
    >
      <JobCompetitive
        :info="competitive_data"
        @closeCompetitive="closeCompetitive"
      ></JobCompetitive>
    </van-popup>
    <van-popup
      :lazy-render="false"
      v-model="showTipoff"
      position="right"
      :overlay="false"
      style="width: 100%; height: 100%"
    >
      <Tipoff
        ref="tipoff"
        :type="1"
        :target_id="base_info.id"
        :jobname="base_info.jobname"
        @closePopout="showTipoff = false"
      ></Tipoff>
    </van-popup>
    <div class="alw-wx-layer" v-if="showWxLayer" @click="cancelShare"></div>
    <div class="alw-layer" v-if="showLayer" @click="cancelShare"></div>
    <SharePoster
      v-if="showPoster"
      @closePoster="closePoster"
      :type="'job'"
      :infoid="shareid"
    ></SharePoster>
    <van-overlay z-index="3" :show="showPoster" @click="showPoster = false" />
    <van-popup v-model="showShare" position="bottom">
      <Share
        @cancelShare="cancelShare"
        @handleForward="handleForward"
        @handlePoster="handlePoster"
      ></Share>
    </van-popup>
    <van-dialog
      v-model="codePro.show"
      show-cancel-button
      :confirm-button-text="codePro.btnCn"
      @confirm="callCodePro"
      confirm-button-color="#1989fa"
    >
      <div class="line18 m-top">拔打号码</div>
      <div class="line18 color-orange font15 bold" v-text="codePro.x"></div>
      <div class="line18 font12">
        (电话<span class="color-orange" v-text="codePro.timeout"></span
        >秒后失效,请尽快拔打)
      </div>
      <div
        v-if="phone_protect_type == 1"
        class="m-btm line18 font12 color-gray"
      >
        仅支持使用<span v-text="codePro.a"></span>的手机卡拔号
      </div>
    </van-dialog>
    <div
      class="return_list"
      v-if="isRetrunBtn != null"
      @click="$router.push('/campus/job')"
    >
      返回列表
    </div>
    <!-- <div class="click_home_page" @click="handlerHomePage">返回<br />首页</div>
    <div class="click_copy" @click="handlerCopy">一键<br />复制</div>
    <div class="generate_posters" @click="handlePoster">生成<br />海报</div> -->
    <!-- <div class="generate_posters" v-if="!putAway">
      <p class="poster_item" @click="handlerHomePage">首页</p>
      <p class="poster_item copy_item" @click="handlerCopy">复制</p>
      <p class="poster_item hb_item" @click="handlePoster">海报</p>
      <p class="poster_item all-item" @click="putAway = !putAway">
        <img src="../../assets/images/company/qx.png" alt="" />
      </p>
    </div> -->
    <div
      class="generate_posters"
      :class="putAway ? 'generate_posters_all' : ''"
    >
      <p class="poster_item" @click="handlerHomePage" v-if="!putAway">首页</p>
      <p class="poster_item copy_item" @click="handlerCopy" v-if="!putAway">
        复制
      </p>
      <p class="poster_item hb_item" @click="handlePoster" v-if="!putAway">
        海报
      </p>
      <p class="poster_item all-item" @click="putAway = !putAway">
        <span
          class="all-item-img"
          :class="putAway ? 'all-item-img-qx' : ''"
        ></span>
      </p>
    </div>
    <!-- 绑定微信开始 -->
    <van-dialog
      v-model="bindWeixinShow"
      title="系统提示"
      :show-cancel-button="false"
      :show-confirm-button="true"
      @confirm="handleImCheckBind"
    >
      <div class="bind-weixin-box">
        <div class="title-1">您当前未绑定微信，绑定后可发起聊天。</div>
        <div class="img">
          <img :src="scanQrcodeImg" alt="" />
        </div>
        <div class="title-2">使用微信扫一扫，按提示快速绑定</div>
      </div>
    </van-dialog>
    <!-- 绑定微信结束 -->

  </div>
</template>

<script>
import Vue from 'vue'
import wxshare from '@/assets/js/share.js'
import Subscribe from '@/components/Subscribe'
import Tipoff from '@/components/Tipoff'
import { countDistance } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Login from '@/components/Login'
import JobCompetitive from '@/components/JobCompetitive'
import Share from '@/components/share/Share'
import SharePoster from '@/components/share/SharePoster'
import { mapMutations } from 'vuex'
let isSpider = new RegExp(
  '^(Baiduspider|YisouSpider|Sogou|Googlebot|Sosospider|bingbot|360Spider)'
).test(navigator.userAgent)
Vue.component('BaiduMap', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/map/Map.vue'], resolve)
  }
})
export default {
  name: 'JobShow',
  components: {
    Login,
    JobCompetitive,
    Tipoff,
    Subscribe,
    Share,
    SharePoster
  },
  data () {
    return {
      jobSearchGroupData: [],
      codePro: {
        show: false,
        x: '',
        timeout: 0,
        a: '',
        btnCn: '立即拔打'
      },
      isRetrunBtn: null,
      showTipoff: false,
      mainLoading: true,
      query_id: '',
      showLogin: false,
      is_personal_login: false,
      showCompetitive: false,
      base_info: {},
      field_rule: { basic: {}, contact: {} },
      show_contact: 0,
      show_contact_note: '',
      contact_info: {},
      watch_percent: '',
      last_login_time: '',
      com_info: {},
      similar: [],
      match_level: 0,
      distance: '',
      current_lat: 0,
      current_lng: 0,
      BMap: null,
      has_fav: 0,
      has_apply: 0,
      after_login_data: {},
      competitive_data: {},
      shareid: 0,
      showShare: false,
      showWxLayer: false,
      showLayer: false,
      showPoster: false,
      cur_user_mobile: '',
      phone_protect_open: false,
      phone_protect_timeout: 0,
      phone_protect_type: 0,
      shortUrl: '',
      // 绑定微信
      bindWeixinShow: false,
      // 绑定微信二维码
      scanQrcodeImg: '',
      putAway: true
    }
  },
  created () {
    this.query_id = this.$route.params.id
    this.isRetrunBtn = this.$route.query.isRetrunBtn
    this.is_personal_login =
      this.$store.state.LoginOrNot === true &&
      this.$store.state.LoginType === 2
    // 请求数据
    this.fetchData()
  },
  watch: {
    $route (to, from) {
      this.query_id = to.params.id
      // 请求数据
      this.fetchData()
      document.body.scrollTop = document.documentElement.scrollTop = 0
    },
    bindWeixinShow (e) {
      if (e === true) {
        this.getScanQrcodeImg()
      }
    }
  },
  mounted () {},
  methods: {
    ...mapMutations(['setImShowParams', 'setimChatid']),
    /**
     * 绑定微信二维码
     */
    getScanQrcodeImg () {
      http.get(api.get_qrcode, { type: 'bind_weixin' }).then((res) => {
        this.scanQrcodeImg = res.data
      })
    },

    handlerHomePage () {
      this.$router.push('/index')
    },
    // 一键复制
    async handlerCopy () {
      let that = this
      let copy = () => {
        let copyMessage = `${this.com_info.companyname}
招聘：${this.base_info.jobname}
要求：工作经验${this.base_info.experience_text}、学历要求${this.base_info.education_text}
工资：${this.base_info.wage_text}
查看联系方式：${this.shortUrl}
-招聘求职就上${this.$store.state.config.sitename}-`
        this.$copyText(copyMessage).then(
          function (e) {
            that.$notify({ type: 'success', message: '内容已复制到剪切板！' })
          },
          function (e) {
            that.$notify({ type: 'error', message: '抱歉，复制失败！' })
          }
        )
      }
      if (!this.shortUrl) {
        const params = {
          jobId: this.query_id
        }
        let res = await http.get('/home/short_url/genJobShow', params)
        if (res.code == 200) {
          this.shortUrl = res.data
          copy()
        }
      } else {
        copy()
      }
    },
    toDetail (id) {
      this.$router.push('/job/' + id)
    },
    handlerMap ({ BMap, map }) {
      this.BMap = BMap
    },
    getPosition (map_lat, map_lng) {
      if (!this.BMap) {
        return
      }
      let BMap = this.BMap
      let that = this
      var geolocation = new BMap.Geolocation()
      geolocation.getCurrentPosition(
        function (r) {
          if (this.getStatus() === BMAP_STATUS_SUCCESS) {
            that.current_lat = r.point.lat
            that.current_lng = r.point.lng
            if (
              that.current_lat > 0 &&
              that.current_lng > 0 &&
              map_lat > 0 &&
              map_lng > 0
            ) {
              that.distance = countDistance(
                that.current_lat,
                that.current_lng,
                map_lat,
                map_lng
              )
            }
          }
        },
        { enableHighAccuracy: true }
      )
    },
    getCompetitiveness () {
      if (this.is_personal_login === true) {
        http
          .get(api.competitiveness, { id: this.query_id })
          .then((res) => {
            if (res.data.length == 0) {
              this.match_level = 3
              this.is_personal_login = false
            } else {
              this.match_level = res.data.match_level
              this.competitive_data = res.data
            }
          })
          .catch(() => {})
      } else {
        this.match_level = 0
      }
    },
    async fetchData (next_method = null) {
      const params = {
        id: this.query_id
      }
      let res = await http.get(api.jobshow, params)
      const {
        base_info,
        field_rule,
        show_contact,
        show_contact_note,
        contact_info,
        watch_percent,
        last_login_time,
        com_info,
        similar,
        has_fav,
        has_apply,
        cur_user_mobile,
        phone_protect_open,
        phone_protect_timeout,
        phone_protect_type
      } = { ...res.data }
      this.field_rule = field_rule
      this.base_info = base_info
      this.show_contact = show_contact
      this.show_contact_note = show_contact_note
      this.contact_info = contact_info
      this.com_info = com_info
      this.watch_percent = watch_percent
      this.last_login_time = last_login_time
      this.similar = similar
      this.has_fav = has_fav
      this.has_apply = has_apply
      this.phone_protect_open = phone_protect_open
      this.cur_user_mobile = cur_user_mobile
      this.phone_protect_timeout = phone_protect_timeout
      this.phone_protect_type = phone_protect_type
      let wechatShareInfo = {
        jobname: base_info.jobname,
        wage: base_info.wage_text,
        companyname: this.com_info.companyname,
        district: this.base_info.district_text,
        imgUrl: this.com_info.logo_src
      }
      wxshare(wechatShareInfo, 'jobshow', location.href)
      this.getPosition(this.base_info.map_lat, this.base_info.map_lng)
      this.getCompetitiveness()
      this.mainLoading = false
      if (next_method !== null) {
        this[next_method]()
      }
    },
    callCodePro () {
      location.href = `tel:${this.codePro.x}`
    },
    async doTel () {
      if (this.show_contact === 1) {
        if (this.phone_protect_open) {
          let res = await http.get(api.secret_phone, { job_id: this.query_id })
          const { code, message, data } = res
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
          if (this.contact_info.is_secrecy != 1) {
            if (this.contact_info.telephone != '') {
              this.$dialog
                .confirm({
                  title: '提示',
                  message: '即将拨打号码：' + this.contact_info.telephone,
                  confirmButtonText: '确定拨打'
                })
                .then(() => {
                  window.location.href = `tel:${this.contact_info.telephone}`
                })
                .catch(() => {})
              return
            } else {
              this.$notify('企业已关闭手机号显示，请投递简历等待联系')
              return
            }
          }
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
      } else if (this.is_personal_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录求职者账号',
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
        if (this.show_contact_note === 'need_resume') {
          this.$notify('您还没有简历，创建简历后可拨打企业电话')
        } else if (this.show_contact_note === 'need_apply') {
          this.$notify('您还没有投递简历，请投递简历后拨打企业电话')
        } else if (this.show_contact_note === 'company_close') {
          if (this.has_apply == 1) {
            this.$notify('您已投递简历，请等待企业联系')
          } else {
            this.$notify('企业未公开联系方式，请直接投递简历')
          }
        }
      }
    },
    handleImCheckBind () {
      http.get(api.imCheckBind).then((res) => {
        if (res.data != 0) {
          location.reload(true)
        }
      })
    },
    doMsg () {
      if (this.is_personal_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录求职者账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doMsg'
            }
          })
          .catch(() => {})
      } else {
        // if (this.base_info.audit != 1) {
        //   this.$notify('该简历还未审核通过，不能继续此操作')
        //   return false
        // }
        http
          .post(api.imStart, {
            token: this.$store.state.imToken,
            jobid: this.query_id
          })
          .then((res) => {
            // disabled 不能使用功能
            // bind_weixin绑定微信
            // complete_resume完善简历
            // 空字符串 正常使用
            // choose_job 选择职位
            // pay 需要购买增值服务，触屏是快捷支付
            if (parseInt(res.code) == 200) {
              if (res.data.next == '') {
                this.setImShowParams({
                  jobname: this.com_info.first_jobname,
                  name: this.com_info.companyname,
                  resumeid: 0,
                  jobid: this.base_info.id,
                  companyId: this.com_info.id
                })
                this.setimChatid(res.data.chatid)
                this.$router.push({ path: '/im/' + res.data.chatid })
                return false
              }
              if (res.data.next == 'disabled') {
                // this.$notify({ type: 'danger', message: res.message })
                this.$dialog({
                  title: '系统提示',
                  message: res.message,
                  showConfirmButton: true
                }).then(() => {})
                return false
              }
              if (res.data.next == 'complete_resume') {
                this.$dialog
                  .confirm({
                    title: '系统提示',
                    message: res.message,
                    confirmButtonText: '去完善简历',
                    showCancelButton: true
                  })
                  .then(() => {
                    this.$router.push({ path: '/member/personal/resume' })
                  })
                  .catch(() => {})
                return false
              }
              if (res.data.next == 'bind_weixin') {
                this.bindWeixinShow = true
              }
            }
          })
      }
      // else {
      //   this.$notify('暂时无法与当前用户直聊')
      //   return false
      // }
    },
    doApply () {
      if (this.$store.state.LoginOrNot === true) {
        if (this.$store.state.LoginType !== 2) {
          this.$dialog
            .confirm({
              title: '提示',
              message: '当前操作需要登录求职者账号',
              confirmButtonText: '去登录'
            })
            .then(() => {
              this.showLogin = true
              this.after_login_data = {
                method: 'doApply'
              }
            })
            .catch(() => {})
        } else {
          if (this.has_apply != 1) {
            const params = {
              jobid: this.query_id
            }
            http
              .post(api.jobapply, params)
              .then((res) => {
                this.$notify({ type: 'success', message: res.message })
                this.fetchData()
              })
              .catch(() => {})
          }
        }
      } else {
        // 快速注册简历
        this.$router.push({
          path: '/member/regquick',
          query: {
            id: this.query_id
          }
        })
      }
    },
    doFav () {
      if (this.is_personal_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录求职者账号',
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
        const params = {
          jobid: this.query_id
        }
        let _api_url = this.has_fav === 1 ? api.jobfav_cancel : api.jobfav
        http
          .post(_api_url, params)
          .then((res) => {
            this.has_fav = this.has_fav === 1 ? 0 : 1
            this.$notify({ type: 'success', message: res.message })
          })
          .catch(() => {})
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
      this.shareid = this.query_id
      this.showPoster = true
    },
    closePoster () {
      this.showPoster = false
    },
    openCompetitive () {
      this.showCompetitive = true
    },
    closeCompetitive () {
      this.showCompetitive = false
    },
    afterLogin (data) {
      this.showLogin = false
      this.is_personal_login = true
      let method = null
      if (data.method !== undefined) {
        method = data.method
      }
      this.fetchData(method)
    },
    closeLogin () {
      this.showLogin = false
    },
    locationToBdmap () {
      if (!this.base_info.map_lat || !this.base_info.map_lng) {
        return false
      }
      let url =
        'http://api.map.baidu.com/marker?location=' +
        this.base_info.map_lat +
        ',' +
        this.base_info.map_lng +
        '&title=' +
        this.com_info.companyname +
        '&content=' +
        this.base_info.address +
        '&output=html'
      window.location.href = url
    },
    handlerReport () {
      if (this.is_personal_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录求职者账号',
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
        this.$refs.tipoff.initCB()
        this.showTipoff = true
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.click_copy {
  position: fixed;
  z-index: 1;
  width: 41px;
  height: 41px;
  border-radius: 999px;
  background-color: rgba(0, 0, 0, 0.7);
  right: 15px;
  bottom: 150px;
  font-size: 12px;
  color: #ffffff;
  line-height: 14px;
  text-align: center;
  padding-top: 7px;
}
.click_home_page {
  position: fixed;
  z-index: 1;
  width: 41px;
  height: 41px;
  border-radius: 999px;
  margin-top: 10px;
  background-color: rgba(0, 0, 0, 0.7);
  right: 15px;
  bottom: 200px;
  font-size: 12px;
  color: #ffffff;
  line-height: 14px;
  text-align: center;
  padding-top: 7px;
}
.generate_posters {
  position: fixed;
  z-index: 1;
  right: 15px;
  bottom: 80px;
  background: #e0eeff;
  border-radius: 45px;
  width: 196px;
  height: 45px;
  transition: width 0.5s;
  -moz-transition: width 0.5s; /* Firefox 4 */
  -webkit-transition: width 0.5s; /* Safari and Chrome */
  -o-transition: width 0.5s; /* Opera */
  line-height: 58px;
  padding-top: 0;
  padding-left: 10px;
  .poster_item {
    z-index: 2;
    width: 25%;
    height: 100%;
    font-size: 12px;
    text-align: center;
    border-radius: 45px;
    float: left;
    background: url(../../assets/images/company/index_rev.png) center 8px no-repeat;
    background-size: 14px;
    color: #595959;
    overflow: hidden;
    box-sizing: border-box;
    padding-top: 2px;
    span {
      display: inline-block;
      width: 100%;
      text-align: center;
      font-size: 12px;
      color: #595959;
    }
  }
  .copy_item {
    background: url(../../assets/images/company/copy_icon.png) 18px 8px no-repeat;
    background-size: 14px;
  }
  .hb_item {
    background: url(../../assets/images/company/hb_share.png) 18px 8px no-repeat;
    background-size: 14px;
  }
  .all-item {
    position: fixed;
    right: 15px;
    background: linear-gradient(to bottom, #2185ff, #0f72eb);
    box-shadow: 0 5px 5px rgba(19, 118, 240, 0.3);
    font-size: 20px;
    text-align: center;
    width: 45px;
    height: 45px;
    line-height: 45px;
    color: #ffffff;
    .all-item-img {
      display: inline-block;
      width: 25px;
      height: 25px;
      background: url("../../assets/images/company/qx.png") 0 no-repeat;
      background-size: 100% 100%;
      margin-top: 8px;
    }
    .all-item-img-qx {
      width: 25px;
      height: 25px;
      background: url("../../assets/images/company/more_icon.png") 0 no-repeat;
      background-size: 100% 100%;
      margin-top: 7px;
    }
  }
}
.generate_posters_all {
  width: 45px;
}

.my_app {
  padding-bottom: 16px;
}

.box_12 {
  .bottom_bar {
    .item_apply {
      height: 43px;
      line-height: 43px;
      text-align: center;
      background-color: #ff6b08;
      color: #ffffff;
      font-size: 15px;
      width: 100px;
      margin-right: 15px;
      border-radius: 5px;
      flex-shrink: 0;
    }
    .item_apply_already {
      background: rgb(206, 206, 206);
    }
    .item_chat {
      flex-shrink: 0;
      border-radius: 5px;
      width: 100px;
      height: 43px;
      text-align: center;
      font-size: 15px;
      color: #ffffff;
      position: relative;
      line-height: 43px;
      margin-right: 12px;
      background: #167eff;
      // &::after {
      //   position: absolute;
      //   box-sizing: border-box;
      //   content: " ";
      //   pointer-events: none;
      //   right: 0;
      //   top: 0;
      //   left: 0;
      //   border-bottom: 0.026667rem solid #f3f3f3;
      // }
    }
    .item_call {
      flex-shrink: 0;
      // float: left;
      width: 60px;
      height: 46px;
      padding: 32px 0 4px;
      text-align: center;
      font-size: 10px;
      color: #333333;
      background: url("../../assets/images/calling_ico.svg") center 9px no-repeat;
      background-size: 17px;
      position: relative;
    }
    .item_collect {
      flex-shrink: 0;
      width: 60px;
      height: 46px;
      padding: 32px 0 4px;
      text-align: center;
      font-size: 10px;
      color: #333333;
      position: relative;
      background: url("../../assets/images/jobshow/sc.png") center 9px no-repeat;
      background-size: 17px;
      &.item_collect_ac {
        background: url("../../assets/images/company/like_act.png") center 9px
          no-repeat;
        background-size: 17px;
      }
    }
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    z-index: 3;
    height: 63px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  position: relative;
  height: 63px;
  width: 100%;
  background-color: #ffffff;
  &::after {
    position: absolute;
    box-sizing: border-box;
    content: " ";
    pointer-events: none;
    right: 0;
    top: 0;
    left: 0;
    //border-bottom: 0.026667rem solid #f3f3f3;
  }
}

.box_11 {
  .put{
    padding:16.5px 16.5px 1px 16.5px;
  }
  .list {
    .top {
      position: absolute;
      right: -25px;
      top: -25px;
      width: 50px;
      height: 50px;
      background-color: #feae41;
      color: #ffffff;
      font-size: 10px;
      font-weight: bold;
      text-align: center;
      transform: rotateZ(45deg);
      padding-top: 36px;
    }

    .company {
      .crw_ico {
        float: left;
        margin-left: 6px;
        width: 14px;
        height: 18px;
        position: relative;

        img {
          position: absolute;
          left: 0;
          top: 4px;
          width: 14px;
          height: 12px;
          border: 0;
        }
      }

      .auth_ico {
        float: left;
        margin-left: 6px;
        width: 35px;
        height: 19px;
        background: url("../../assets/images/jobs_list_auth_ico.png") 0 center
          no-repeat;
        background-size: 100% 12px;
      }

      .name {
        float: left;
        max-width: 300px;
        font-size: 13px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }

      width: 100%;
      border-top: 1px solid #f3f3f3;
      padding: 12px 0;
    }

    .tag_wrapper {
      .tag_item {
        float: left;
        font-size: 10px;
        color: #8096a3;
        background-color: #e9f8ff;
        border-radius: 3px;
        padding: 3px 5px;

        &:not(:first-child) {
          margin-left: 5px;
        }
      }

      width: 100%;
      padding-bottom: 10px;
    }

    .tx2 {
      font-size: 14px;
      color: #666666;
      padding: 0 60px 0 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      position: relative;
      margin-bottom: 8px;
    }

    .tx1 {
      .wage {
        position: absolute;
        right: 0;
        top: 19px;
        font-size: 14px;
        color: #ff5d24;
        font-weight: bold;
      }

      .worry_ico {
        font-size: 10px;
        color: #ffffff;
        margin-top: 3px;
        padding: 1px 3px;
        border-radius: 3px;
        background-color: #ff8b82;
        float: left;
      }

      .name {
        font-size: 16px;
        color: #333333;
        font-weight: bold;
        max-width: 240px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        float: left;
        margin-right: 6px;
      }

      position: relative;
      padding-top: 17px;
      margin-bottom: 8px;
    }

    .time {
      position: absolute;
      right: 17px;
      top: 48px;
      font-size: 13px;
      color: #999999;
    }

    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding: 0 17px;
    overflow: hidden;
  }

  width: 100%;
}

.box_sp {
  width: 100%;
  padding: 12px 0;
  text-align: center;
  background-color: #f3f3f3;
  font-size: 12px;
  color: #999999;
}

.box_10 {
  .info {
    .down {
      &::after {
        position: absolute;
        right: 7px;
        top: 25px;
        width: 7px;
        height: 7px;
        border-top: 1px solid #999999;
        border-right: 1px solid #999999;
        transform: rotate(45deg);
        content: "";
      }

      .link {
        color: #1787fb;
      }

      // position: relative;
      // padding: 12.5px 25px 12.5px 0;
      font-size: 13px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      border-top: 1px dotted #f8f8f8;
      height: 8px;
    }

    .up {
      .tx3 {
        font-size: 14px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 17px;
      }

      .tx2 {
        font-size: 13px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 7px;
      }

      .tx1 {
        margin-bottom: 9px;

        .crw_ico {
          float: left;
          margin-left: 6px;
          width: 14px;
          height: 22px;
          position: relative;

          img {
            position: absolute;
            left: 0;
            top: 2px;
            width: 15px;
            height: 15px;
            border: 0;
          }
        }

        .auth_ico {
          float: left;
          margin-left: 6px;
          width: 45px;
          height: 20px;
          background: url("../../assets/images/jobshow/rz.png") 0 center no-repeat;
          background-size: 100%;
        }

        .name {
          float: left;
          font-size: 16px;
          font-weight: bold;
          color: #333333;
          max-width: 220px;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
        }
      }

      .logo_box {
        position: absolute;
        left: 2px;
        top: 0;
        width: 60px;
        height: 60px;
        overflow: hidden;

        img {
          width: 60px;
          height: 60px;
          border: 0;
        }
      }

      position: relative;
      padding-left: 75px;
    }

    width: 100%;
    background-color: #ffffff;
    position: relative;
  }

  width: 100%;
  background-color: #ffffff;
  padding: 0 16px 21.5px;
}

.box_9 {
  .p_con {
    padding: 10px 0 21.5px;
  }

  .self_content {
    .here {
      flex: 1;
      font-size: 12px;
      color: #ffffff;
      text-align: center;
      position: relative;
      span {
        display: inline-block;
        width: 80%;
        background: #167eff;
        border-radius: 25px;
        text-align: center;
        height: 25px;
        line-height: 25px;
        font-size: 12px;
      }
      .dao_3 {
        width: 0;
        height: 0;
        border-left: 0.133333rem solid transparent;
        border-right: 0.133333rem solid transparent;
        border-bottom: 0.133333rem solid #167eff;
        position: absolute;
        right: 44%;
        bottom: -3px;
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
        background: transparent;
      }
    }

    .percent_text {
      font-size: 13px;
      color: #666666;
      flex: 1;
      text-align: center;
      margin-top: 5px;
    }

    .level_img {
      display: inline-flex;
      height: 60px;
      background: url("../../assets/images/jobshow/wxz.png") 0 bottom no-repeat;
      background-size: 100%;
      float: left;
      flex: 1;
    }

    .level_img_big {
      display: inline-flex;
      background: url("../../assets/images/jobshow/wxz2.png") 0 bottom no-repeat;
      background-size: 100%;
      float: left;
      flex: 1;
    }

    &.level_4 {
      .t3 {
        color: #167eff;
      }

      .cir_block {
        left: 260px;
      }

      .level_img_big_4 {
        background: url("../../assets/images/jobshow/xz2.png") 0 bottom no-repeat;
        background-size: 100%;
      }
    }

    &.level_3 {
      .t3 {
        color: #167eff;
      }

      .cir_block {
        left: 260px;
      }

      .level_img_3 {
        background: url("../../assets/images/jobshow/xz1.png") 0 bottom no-repeat;
        background-size: 100%;
      }
    }

    &.level_2 {
      .t2 {
        color: #167eff;
      }

      .cir_block {
        left: 160px;
      }

      .level_img_2 {
        background: url("../../assets/images/jobshow/xz1.png") 0 bottom no-repeat;
        background-size: 100%;
      }
    }

    &.level_1 {
      .t1 {
        color: #167eff;
      }

      .cir_block {
        left: 55px;
      }

      .level_img_1 {
        background: url("../../assets/images/jobshow/xz1.png") 0 bottom no-repeat;
        background-size: 100%;
      }
    }

    .cir_block {
      position: absolute;
      width: 15px;
      height: 15px;
      border-radius: 100%;
      background-color: #ffffff;
      box-shadow: 0 0 5px #c2c2c2;
      z-index: 3;
      top: -3px;
    }

    .sp_block {
      &.s3 {
        left: 262px;
        top: 0;
      }

      &.s2 {
        left: 167px;
        top: 0;
      }

      &.s1 {
        left: 62px;
        top: 0;
      }

      position: absolute;
      width: 2px;
      height: 100%;
      background-color: #ffffff;
      z-index: 2;
    }

    .percent {
      position: absolute;
      left: 0;
      top: 0;
      border-radius: 5px;
      height: 100%;
    }

    position: relative;
    width: 325px;
    margin: 0 auto;
    display: flex;
  }

  .doubt {
    .content {
      // &::after {
      //   position: absolute;
      //   left: 0;
      //   top: 50%;
      //   transform: translate(0, -50%);
      //   width: 13px;
      //   height: 13px;
      //   border: 1px solid #ffffff;
      //   border-radius: 100%;
      //   background: url("../../assets/images/doubt_ico.svg") 0 no-repeat;
      //   background-size: 13px;
      //   content: " ";
      //   box-shadow: 0 0 3px #eae7e7;
      // }

      display: inline-block;
      position: relative;
      padding: 10px 10px 13px 10px;
      font-size: 12px;
      color: #ffffff;
      background: url("../../assets/images/jobshow/wdl.png") 0 no-repeat;
      background-size: 100%;
      span {
        border-bottom: 1px solid #ffffff;
      }
    }

    text-align: center;
  }

  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
  position: relative;
}

.box_8 {
  .contact_delivery {
    .tx2 {
      padding-bottom: 15.5px;
    }

    .tx1 {
      // padding-top: 16px;
      // margin-bottom: 3px;
    }

    .link {
      color: #1787fb;
    }

    text-align: center;
    font-size: 14px;
    color: #333333;
    // box-shadow: 0 0 8px #c2c2c2;
    border-radius: 7px;
  }

  .contact_resume {
    .tx2 {
      padding-bottom: 15.5px;
    }

    .tx1 {
      // padding-top: 16px;
      // margin-bottom: 3px;
    }

    .link {
      color: #1787fb;
    }

    text-align: center;
    font-size: 14px;
    color: #333333;
    // box-shadow: 0 0 8px #c2c2c2;
    border-radius: 7px;
  }

  .contact_login {
    .link {
      display: inline-block;
      color: #167eff;
      background: #ffffff;
      width: 90px;
      text-align: center;
      height: 29px;
      line-height: 29px;
      position: relative;
      border-radius: 29px;
      font-size: 13px;
      padding-right: 11px;
      margin-top: 15px;
      span {
        display: inline-block;
        width: 10px;
        height: 10px;
        background: url("../../assets/images/jobshow/return.png") 0 no-repeat;
        background-size: 0.32rem;
        position: absolute;
        top: 9px;
        right: 13px;
      }
    }

    padding: 20px 0;
    text-align: center;
    font-size: 14px;
    color: #0c317f;
    // box-shadow: 0 0 8px #c2c2c2;
    border-radius: 7px;
    // background: linear-gradient(170deg, #f2f8ff, #cbe8fe, #d0e5ff);
    background: linear-gradient(170deg, #CBE8FE, #F2F8FF, #D0E5FF);
  }

  .contact_info {
    .info_line {
      &:last-child {
        padding-bottom: 0;
      }

      margin-bottom: 9px;
      font-size: 14px;
      color: #999999;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;

      span {
        color: #666666;
      }
    }
  }

  width: 100%;
  background-color: #ffffff;
  position: relative;
  padding: 0 16px 0;
}

.box_7 {
  .report {
    // position: absolute;
    // right: 0;
    // top: 50%;
    transform: translate(0, -50%);
    // padding-top: 27px;
    // width: 55px;
    // text-align: center;
    // background: url("../../assets/images/report_ico.png") center 0 no-repeat;
    // background-size: 19px 23px;
    font-size: 11px;
    color: #ff5d24;
    border-left: 1px solid #f3f3f3;
  }

  .tx2 {
    font-size: 12px;
    color: #999999;
  }

  .tx1 {
    font-size: 13px;
    color: #222222;
    margin-bottom: 5px;
    padding-left: 15px;
    box-sizing: border-box;
    background: url("../../assets/images/jobshow/warn_icon.png") 0 center no-repeat;
    background-size: 12px;
  }

  width: 100%;
  background-color: #f8f9fa;
  position: relative;
  padding: 12px;
}

.box_6 {
  .content {
    .des {
      font-size: 14px;
      color: #666666;
      line-height: 1.7;
      word-break: break-all;
    }

    .tx1 {
      font-size: 14px;
      color: #666666;
      margin-bottom: 2px;
    }

    .b_item {
      float: left;
      width: 50%;
      vertical-align: top;
      font-size: 14px;
      color: #999999;
      margin-bottom: 9px;

      span {
        color: #666666;
      }
    }
  }

  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
}

.box_5 {
  .content {
    .item {
      &:not(:last-child)::after {
        content: " ";
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        width: 1px;
        height: 25px;
        background-color: #f3f3f3;
      }

      .tx2 {
        font-size: 13px;
        color: #999999;
      }

      .tx1 {
        font-size: 17px;
        font-weight: bold;
        color: #167EFF;
        margin-bottom: 4.5px;
      }

      //   -webkit-box-flex: 1;
      // -ms-flex: 1;
      // /* flex: 1; */
      position: relative;
      text-align: center;
      background: #f8f9fa;
      width: 36%;
      padding: 10px 0;
      border-radius: 5px;
      float: left;
      margin-left: 30px;
    }

    .item:nth-child(2) {
      float: right;
      margin-left: 0;
      margin-right: 30px;
    }

    // border-bottom: 1px solid #f5f5f5;
    padding: 0;
    overflow: hidden;
  }

  .put {
    // border-top: 1px solid #f5f5f5;
  }

  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
}

.box_4 {
  .content {
    .item {
      float: left;
      font-size: 13px;
      color: #595959;
      background-color: #f1f1f1;
      padding: 3px 8px;
      border-radius: 3px;
      margin: 0 10px 10px 0;
    }
  }

  width: 100%;
  background-color: #ffffff;
  padding: 0 0 16.5px 16px;
}

.box_3 {
  .bg {
    .box {
      &::after {
        position: absolute;
        right: 27px;
        top: 20px;
        width: 8px;
        height: 8px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }

      .tx2 {
        font-size: 12px;
        color: #767676;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }

      .tx1 {
        font-size: 14px;
        font-weight: bold;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }

      width: 285px;
      height: 58px;
      margin: 0 auto;
      padding: 6px 40px 7px 68px;
      position: relative;
      border-radius: 53px;
      background: url("../../assets/images/company/tc.png") 0 center no-repeat;
      background-size: 100%;
    }

    width: 100%;
    height:140px;
    background: url("../../assets/images/company/dtbj.png") 0 no-repeat;
    background-size: 100% 140px;
    padding-top: 44px;
  }
  .distance {
    padding-left: 15px;
    transform: translate(0, -50%);
    font-size: 12px;
    color: #595959;
    background: url("../../assets/images/company/address_icon2.png") 0 5px
      no-repeat;
    background-size: 12px;
    height: 25px;
    line-height: 22px;
    margin-top: 26px;
  }
  .address {
    // padding-left: 20px;
    line-height: 1.8;
    font-size: 14px;
    color: #767676;
    word-break: break-all;
    margin-bottom: 8px;
    // background: url("../../assets/images/address_ico_blue.svg") 0 5px no-repeat;
    // background-size: 13px;
  }

  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
}

.put {
  padding: 20px 0 21.5px;
  font-size: 18px;
  font-weight: bold;
  color: #333333;
  position: relative;
  .title {
    background: url("../../assets/images/company/border_bg.png") 0 bottom no-repeat;
    background-size: 100% 3px;
  }

  .right_arrow {
    &::after {
      position: absolute;
      right: 1px;
      top: 6px;
      width: 6px;
      height: 6px;
      border-top: 1px solid #1787fb;
      border-right: 1px solid #1787fb;
      transform: rotate(45deg);
      content: " ";
    }

    position: absolute;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 12px;
    color: #1787fb;
    padding-right: 10px;
    font-weight: initial;
  }
}

.box_2 {
  .bg_box {
    position: absolute;
    left: -20px;
    top: 2px;
    width: 50px;
    height: 100%;
    background: linear-gradient(to right, #3b9dfe, #6fc9ff);
    z-index: 1;
    transform: rotate(35deg);
  }

  .auth_ico {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translate(0, -50%);
    padding-top: 13px;
    font-size: 10px;
    color: #ffffff;
    z-index: 2;
    background: url("../../assets/images/job_show_auth_ico.png") center 0 no-repeat;
    background-size: 13px;
  }

  .cell {
    float: left;
    font-size: 12px;
    color: #5f82a7;
    padding: 9px 15px 9px 17px;
    background: url("../../assets/images/done_ico_blue.svg") 0 center no-repeat;
    background-size: 12px;

    &:last-child {
      padding-right: 0;
    }
  }

  position: relative;
  width: 100%;
  background-color: #eef9ff;
  overflow: hidden;
  padding-left: 50px;
}

.box_1 {
  .chat_bar {
    .right {
      &::after {
        position: absolute;
        right: 0;
        top: 5px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #1787fb;
        border-right: 1px solid #1787fb;
        transform: rotate(45deg);
        content: " ";
      }

      position: absolute;
      right: 2px;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 12px;
      color: #1787fb;
      padding-right: 11px;
    }

    position: relative;
    font-size: 12px;
    color: #999999;
    padding: 12px 0 12px 17px;
    border-top: 1px solid #f5f5f5;
    background: url("../../assets/images/hot_point_ico.svg") 0 center no-repeat;
    background-size: 15px;
  }

  .tx3 {
    .time {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 12px;
      color: #999999;
    }

    font-size: 14px;
    color: #666666;
    padding: 0 80px 0 0;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    margin-bottom: 17px;
  }

  .tx2 {
    font-size: 18px;
    font-weight: bold;
    color: #ff5d24;
    margin-bottom: 8.5px;
		position: relative;
		.invalid_job{
			display: block;
			width: 79px;
			height: 47px;
			position: absolute;
			right: 55px;
			    top: -24px;
		}
		span {
		  font-size: 12px;
		  color: #999999;
		  float: right;
		  font-weight: normal;
		  padding-top: 7px;
		}
  }

  .tx1 {
    max-width: 85%;
    margin-bottom: 5.5px;
    position: relative;
    font-size: 19px;
    font-weight: bold;
    color: #333333;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .share {
    .text {
      font-size: 12px;
      color: #999999;
    }

    .ico {
      width: 16px;
      height: 16px;
      margin: 0 auto 2.5px;
      background: url("../../assets/images/jobshow/share.png") 0 no-repeat;
      background-size: 16px;
    }

    position: absolute;
    top: 30px;
    right: 17px;
    z-index: 1;
  }

  .collect {
    .text {
      font-size: 12px;
      color: #999999;
    }

    .ico {
      width: 16px;
      height: 16px;
      margin: 0 auto 2.5px;
      position: relative;
      border-radius: 100%;
      background: url("../../assets/images/jobshow/sc.png") 0 no-repeat;
      background-size: 16px;
    }

    position: absolute;
    top: 30px;
    right: 50px;
    z-index: 1;
  }
  .active_collect {
    .ico {
      background: url("../../assets/images/company/like_act.png") 0 no-repeat;
      background-size: 16px;
    }
  }
  width: 100%;
  background-color: #ffffff;
  position: relative;
  padding: 26px 16px 20px;
  border-top: 1px solid #f3f3f3;
}

.box_13 {
  width: 100%;
  background-color: #ffffff;
  padding: 21.5px 16px;
  .boss_all {
    overflow: hidden;
    position: relative;
    .boss_tx {
      float: left;
      width: 50px;
      height: 50px;
      background: url(../../assets/images/jobshow/boss_tx.png) 0 no-repeat;
      background-size: 100%;
    }
    .boss_detail {
      width: 80%;
      padding: 6px 0 0 62px;
      p:nth-child(1) {
        color: #282828;
        font-size: 16px;
        font-weight: bold;
      }
      p:nth-child(2) {
        color: #808080;
        font-size: 13px;
      }
    }
    .boss_talk_icon {
      display: inline-block;
      width: 45px;
      height: 45px;
      position: absolute;
      top: 5px;
      right: 20px;
      background: url(../../assets/images/jobshow/lt.png) 0 no-repeat;
      background-size: 100%;
    }
  }
}

.phone_tip {
  display: inline-block;
  position: absolute;
  right: 0;
  top: 50%;
  transform: translate(0, -50%);
  color: #666;
  font-size: 12px;

  .phone {
    color: #ff5d24;
  }
}

.code_pro_wrap {
  .secret {
    width: 100%;
  }

  .pro_tip {
    margin-top: 0.08rem;
    color: #888;
    font-size: 12px;
  }
}

.orange-phone {
  color: #ff5d24;
  font-weight: bold;
}

.font12 {
  font-size: 12px;
}

.font15 {
  font-size: 15px;
}

.line18 {
  line-height: 25px;
  text-align: center;
}

.color-orange {
  color: #ff5d24;
}

.m-top {
  margin-top: 25px;
}

.m-btm {
  margin-bottom: 20px;
}

.bold {
  font-weight: bold;
}

.return_list {
  width: 80px;
  height: 30px;
  line-height: 30px;
  background: rgba(0, 0, 0, 0.5);
  border-radius: 30px 0 0 30px;
  color: #fff;
  text-align: center;
  position: fixed;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  font-size: 14px;
}

// 绑定微信开始
.bind-weixin-box {
  display: flex;
  flex-direction: column;
  text-align: center;
  padding: 10px 0;
  .title-1 {
    color: #646566;
    font-size: 14px;
  }
  .img {
    width: 111px;
    height: 111px;
    margin: 13px auto 10px;
    img {
      width: 100%;
      height: 100%;
    }
  }
  .title-2 {
    color: #999999;
    font-size: 13px;
  }
}
//绑定微信结束
</style>
