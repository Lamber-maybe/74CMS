<template>
  <div id="app" class="my_app">
    <Meta
      v-if="base_info.fullname !== undefined"
      pagealias="resumeshow"
      :custom_data="{
				fullname: base_info.fullname,
				sex: base_info.sex_text,
				education: base_info.education_text,
				experience: base_info.experience_text,
				district: base_info.intention_district_text,
				jobcategory: base_info.intention_jobs_text,
				specialty: base_info.specialty
			}"
    />
    <Head>{{ base_info.fullname }}的简历</Head>
    <van-skeleton title avatar :row="10" :loading="mainLoading">
      <div class="box_1">
        <div class="content">
          <div class="invalid_resume" v-if="is_invalid===1">
          </div>
          <div class="up">
            <div class="avatar_box">
              <img :src="base_info.photo_img_src" :alt="base_info.fullname"/>
              <div class="gender" :class="base_info.sex == 1 ? 'male' : base_info.sex == 2 ? 'female' : ''"></div>
            </div>
            <div class="tx1">
              <div class="name">{{ base_info.fullname }}</div>
              <div class="level_ico" v-if="base_info.high_quality == 1"></div>
              <div class="clear"></div>
            </div>
            <div class="tx2">
              {{ base_info.age }}岁 · {{ base_info.experience_text }} · {{ base_info.education_text }}
            </div>
            <div class="tag" v-if="base_info.service_tag != ''">{{ base_info.service_tag }}</div>
          </div>
          <div class="mid-div">
            <div class="mid"></div>
            <div class="text">
              两周内：主动投递
              <span class="num">{{ apply_num }}</span>
              次；被下载
              <span class="num">{{ download_num }}</span>
              次；被浏览
              <span class="num">{{ base_info.click }}</span>
              次
            </div>
          </div>
          <div class="down">
            <div>
              <span>更新：{{ base_info.refreshtime }}</span>
            </div>
            <div
              class="right"
              @click="showDetail = !showDetail"
            >
              更多基本信息
            </div>
          </div>
          <div class="collect" :class="has_fav == 1 ? 'collect_act' : ''" @click="doFav">
            <!-- {{ has_fav == 1 ? "已收藏" : "收藏" }} --></div>
          <div class="share" @click="doShare"></div>
        </div>
        <div class="box_nav" v-if="$store.state.config.shortvideo_enable === '1'">
          <div class="item" :class="resumeShow === 'resume' ? 'active' : ''" @click="resumeShow = 'resume'">简历信息
          </div>
          <div class="item" :class="resumeShow === 'video' ? 'active' : ''" @click="resumeShow = 'video'">
            求职视频
            <span style="font-size:12px;">({{ videonum }})</span>
          </div>
        </div>
      </div>
      <div class="box_resume_some" v-if="resumeShow === 'resume'">
        <div class="content_wrapper">
          <!-- 无效职位标识 -->
          <img v-if="this.is_invalid!=0" class="invalid_img" src="../../assets/images/resumeshow/invalid_resume.png"/>
          <div class="box_3">
            <div class="box_head">
              <div class="txt"><span class="title">求职意向</span></div>
              <div class="right_text">{{ base_info.current_text }}</div>
            </div>
            <div class="box_content" v-if="intention_list.length > 0">
              <!-- <div
                class="tx2"
                v-for="(item, index) in intention_list"
                :key="index"
              > -->
              <!-- <div class="tx2" v-for="(item, index) in intention_list" :key="index">
                <div class="name">
                  <span>{{ item.nature_text }}</span> {{ item.category_text }}
                </div>
                <div class="intent">
                  {{ item.wage_text }}
                  {{
                    field_rule.intention.trade !== undefined &&
                    field_rule.intention.trade.is_display === 1 &&
                    item.trade_text
                      ? `，${item.trade_text}`
                      : ""
                  }}
                </div>
                <div class="intent address">
                  {{ item.district_text }}
                </div>
              </div> -->
              <div class="tx2">
                <div class="name">
                  <!-- 工作类型/职位 -->
                  <span>{{ base_info.nature_text_unique }}</span>
                  {{ base_info.intention_jobs_text }}
                </div>
                <div class="intent">
                  <!-- 薪资 -->
                  {{ base_info.wage_text_unique }}
                </div>
                <div class="intent address" style="margin-bottom: 0">
                  <!-- 地区 -->
                  {{ base_info.intention_district_full_text }}
                </div>
              </div>
            </div>
          </div>
          <div class="box_5" v-if="base_info.specialty != ''">
            <div class="box_head">
              <div class="txt"><span class="title">自我描述</span></div>
            </div>
            <div class="box_content">
              <div class="dec">{{ base_info.specialty }}</div>
            </div>
          </div>
          <!--工作经历-->
          <div class="box_7" v-if="work_list.length > 0">
            <div class="box_head">
              <div class="txt"><span class="title">工作经历</span></div>
            </div>
            <div class="box_content">
              <div class="tx1" v-for="(item, index) in work_list" :key="index">
                <div class="circle"></div>
                <div class="t1">{{ item.companyname }}</div>
                <!-- <div class="t2">
                  {{ item.starttime | monthTimeFilter }}
                  <span v-if="item.todate == 1">至今</span>
                  <span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
                </div> -->
                <div class="t3">
                  {{ item.jobname }}
                  <span>
										{{ item.starttime | monthTimeFilter }}
										<span v-if="item.todate == 1">至今</span>
										<span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
									</span>
                </div>
                <div class="t5">工作职责：</div>
                <div class="t4">{{ item.duty }}</div>
              </div>
            </div>
          </div>
          <!--教育经历-->
          <div class="box_6" v-if="education_list.length > 0">
            <div class="box_head">
              <div class="txt"><span class="title">教育经历</span></div>
            </div>
            <div class="box_content">
              <div class="tx1" v-for="(item, index) in education_list" :key="index">
                <div class="circle"></div>
                <div class="t1">
                  {{ item.school }}
                  <span class="school-time">
										{{ item.starttime | monthTimeFilter }}
										<span v-if="item.todate == 1">至今</span>
										<span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
									</span>
                </div>
                <div class="t3">
                  {{
                    item.education_text
                  }}{{
                    field_rule.education.major !== undefined && field_rule.education.major.is_display === 1 && item.major ? `，${item.major}` : ''
                  }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box_2" v-if="base_info.tag_text_arr != undefined && base_info.tag_text_arr.length > 0">
          <div class="box_head">
            <div class="txt"><span class="title">个性标签</span></div>
          </div>
          <div class="content">
            <div class="item" v-for="(tag, index) in base_info.tag_text_arr" :key="index">{{ tag }}</div>
            <div class="clear"></div>
          </div>
          <!--联系方式-->
          <div class="box_cac">
            <div class="box_head" style="padding-top: 11.5px;">
              <div class="txt"><span class="title">联系方式</span></div>
              <span class="phone_tip" v-if="show_contact == 1 && phone_protect_open && phone_protect_type == 1">
								请使用
								<span class="phone" v-text="cur_com_mobile"></span>
								的手机号拔号
							</span>
            </div>

            <div class="box_content" v-if="show_contact == 1 && !phone_protect_open">
              <div class="item phone">手机：{{ contact_info.mobile }}</div>
              <div class="item wx"
                   v-if="field_rule.contact.weixin != undefined && field_rule.contact.weixin.is_display == 1 && contact_info.weixin != ''">
                微信：{{ contact_info.weixin }}
              </div>
              <div class="item email"
                   v-if="field_rule.contact.email != undefined && field_rule.contact.email.is_display == 1 && contact_info.email != ''">
                邮箱：{{ contact_info.email }}
              </div>
              <div class="item qq"
                   v-if="field_rule.contact.qq != undefined && field_rule.contact.qq.is_display == 1 && contact_info.qq != ''">
                QQ：{{ contact_info.qq }}
              </div>
            </div>
            <div class="code_pro_wrap" v-if="show_contact == 1 && phone_protect_open">
              <img class="secret" src="../../assets/images/318.jpg"/>
              <div v-if="phone_protect_type == 1" class="pro_tip">
                1.需要使用指定号码拔打,非指定号码无法拔通; 2.隐私号码有效
                <span v-text="phone_protect_timeout"></span>
                秒,过期后需再次点击拔号
              </div>
            </div>
            <div class="contact_tip" v-if="show_contact == 0 && show_contact_note == 'need_login'"
                 @click="showLogin = true">
              <div class="tx1">您尚未登录，点击登录后可获取简历联系方式</div>
              <span class="link">
								点击登录
								<span></span>
							</span>
            </div>
            <div class="contact_tip" v-if="show_contact == 0 && show_contact_note == 'need_company_login'">
              <div class="tx1">简历联系方式</div>
              <div class="tx2">仅对企业会员开放</div>
            </div>
            <div class="contact_tip" v-if="show_contact == 0 && show_contact_note == 'need_download'"
                 @click="doDownload">
              <div class="tx1">获取简历联系方式</div>
              <span class="link">
								下载简历
								<span></span>
							</span>
            </div>
          </div>
        </div>
        <!-- <div class="form_split_10"></div> -->
        <div class="content_wrapper">
          <!--培训经历-->
          <div class="box_8"
               v-if="resume_module.training !== undefined && resume_module.training.is_display == 1 && training_list.length > 0">
            <div class="box_head">
              <div class="txt"><span class="title">培训经历</span></div>
            </div>
            <div class="box_content">
              <div class="tx1" v-for="(item, index) in training_list" :key="index">
                <div class="circle"></div>
                <div class="t1">{{ item.agency }}</div>
                <!-- <div class="t2">

                </div> -->
                <div class="t3">
                  {{ item.course }}
                  <span class="t3_time">
										{{ item.starttime | monthTimeFilter }}
										<span v-if="item.todate == 1">至今</span>
										<span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
									</span>
                </div>
                <div class="t5">培训内容：</div>
                <div class="t4">{{ item.description }}</div>
              </div>
            </div>
          </div>
          <!--项目经历-->
          <div class="box_9"
               v-if="resume_module.project !== undefined && resume_module.project.is_display == 1 && project_list.length > 0">
            <div class="box_head">
              <div class="txt"><span class="title">项目经历</span></div>
            </div>
            <div class="box_content">
              <div class="tx1" v-for="(item, index) in project_list" :key="index">
                <div class="circle"></div>
                <div class="t1">{{ item.projectname }}</div>
                <div class="t3">
                  {{ item.role }}
                  <span class="t3_time">
										{{ item.starttime | monthTimeFilter }}
										<span v-if="item.todate == 1">至今</span>
										<span v-else>至 {{ item.endtime | monthTimeFilter }}</span>
									</span>
                </div>
                <div class="t5">项目描述：</div>
                <div class="t4">{{ item.description }}</div>
              </div>
            </div>
          </div>
        </div>
        <!-- <div
          class="form_split_10"
          v-if="
            resume_module.certificate !== undefined &&
            resume_module.language !== undefined &&
            resume_module.certificate.is_display == 1 &&
            certificate_list.length > 0 &&
            resume_module.language.is_display == 1 &&
            language_list.length > 0
          "
        ></div> -->
        <div
          class="content_wrapper"
          v-if="
              (resume_module.certificate !== undefined &&
              resume_module.certificate.is_display == 1 &&
              certificate_list.length > 0)
              ||
              (resume_module.language !== undefined &&
              resume_module.language.is_display == 1 &&
              language_list.length > 0)
					"
        >
          <!--获得证书-->
          <div class="box_10"
               v-if="resume_module.certificate !== undefined && resume_module.certificate.is_display == 1 && certificate_list.length > 0">
            <div class="box_head">
              <div class="txt"><span class="title">获得证书</span></div>
            </div>
            <div class="box_content" style="padding-bottom: 7.5px">
              <div class="tx1" v-for="(item, index) in certificate_list" :key="index">{{ item.name }}</div>
              <div class="clear"></div>
              <!-- <div
                class="tx1"
                v-for="(item, index) in certificate_list"
                :key="index"
              >
                {{ item.name }}
                <div class="right_txt">
                  {{ item.obtaintime | monthTimeFilter }}
                </div>
              </div> -->
            </div>
          </div>
          <!--语言能力-->
          <div class="box_11"
               v-if="resume_module.language !== undefined && resume_module.language.is_display == 1 && language_list.length > 0">
            <div class="box_head" style="padding-top:0;">
              <div class="txt"><span class="title">语言能力</span></div>
            </div>
            <div class="box_content">
              <div class="tx1" v-for="(item, index) in language_list" :key="index">
                {{ item.language_text }} |
                <span class="right_txt">{{ item.level_text }}</span>
              </div>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        <!-- <div
          class="form_split_10"
          v-if="
            resume_module.img !== undefined &&
            resume_module.img.is_display == 1 &&
            img_list.length > 0
          "
        ></div> -->
        <div class="content_wrapper"
             v-if="resume_module.img !== undefined && resume_module.img.is_display == 1 && img_list.length > 0">
          <!--我的作品-->
          <div class="box_12">
            <div class="box_head" style="padding-top: 0">
              <div class="txt"><span class="title">我的作品</span></div>
            </div>
            <!-- <div class="box_content">
              <swiper :options="swiperOption" v-if="$store.state.swiperLoaded">
                <swiper-slide v-for="(item, index) in img_list" :key="index">
                  <img
                    :src="item.img_src"
                    alt="简历照片作品"
                    @click="previewImg(index)"
                  />
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
              </swiper>
            </div> -->
            <div class="box_content">
              <div class="swiper-duo">
                <div class="swiper-item" v-for="(item, index) in img_list" :key="index"><img :src="item.img_src" alt=""
                                                                                             @click="previewImg(index)"/>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content_wrapper" v-if="enclosure_resume.enclosure">
          <!--附件简历-->
          <div class="box_12 box_14">
            <div class="box_head" style="padding-top:0;">
              <div class="txt"><span class="title">附件简历</span></div>
            </div>
            <div class="box_content">
              <div class="resume_attachment" @click="doEnclosureDowenload()" v-if="show_contact === 0">
                {{ enclosure_resume.title }}
              </div>
              <div class="resume_attachment" v-else>
                <a :href="enclosure_resume.enclosure" :download="enclosure_resume.enclosure">{{
                    enclosure_resume.title
                  }}</a>
              </div>
            </div>
          </div>
        </div>
        <div class="content_wrapper">
          <div class="box_report">
            <div class="tx1">{{ $store.state.config.sitename }}温馨提示</div>
            <div class="tx2">
              招聘过程如遇无效、虚假简历，联系方式不实等情况，请立即举报，我们将及时处理。
              <span class="report" @click="handlerReport">立即举报</span>
            </div>
          </div>
        </div>
        <!-- <div class="form_split_10"></div> -->
        <div class="box_13">
          <div class="bottom_bar">
            <div class="item_call" @click="doTel">电话</div>
            <div :class="has_fav == 1 ? 'item_collect item_collect_ac' : 'item_collect'" @click="doFav">
              {{ has_fav == 1 ? '已收藏' : '收藏' }}
            </div>
            <div class="item_apply" v-if="show_contact === 0" @click="doDownload">下载简历</div>
            <div class="item_apply" v-else @click="doInterview">面试邀请</div>
            <div class="item_chat" @click="doMsg">在线职聊</div>
            <!-- <div class="clear"></div> -->
          </div>
        </div>
      </div>
      <div class="box_video_some" v-if="resumeShow === 'video'">
        <VideoList :videotype="2" :id="query_id" :gointype="'unitplay'"></VideoList>
      </div>
    </van-skeleton>
    <van-popup v-model="showLogin" position="right" :overlay="false" style="width: 100%; height: 100%">
      <Login :utype="1" :single_login="true" @afterLogin="afterLogin" :after_login_data="after_login_data"></Login>
    </van-popup>

    <van-dialog
      v-model="showDirectService"
      @cancel="cancels"
      title="下载简历"
      :closeOnClickOverlay="true"
      show-cancel-button
      :cancel-button-text="directServiceInfo.cancel"
      :confirm-button-text="directServiceInfo.btnCn"
      @confirm="handlerDirectService"
    >
      <div class="dialog_tip_wrapper">
        <div class="tx1" v-if="directServiceInfo.use_type == 'package'">
          很抱歉，您的简历点数不足，暂时无法下载简历建议您升级套餐或购买增值服务。
        </div>
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
        <div class="tx2" v-if="parseInt(directServiceInfo.discount) > 0 && directServiceInfo.use_type != 'package'">
          购买简历包价格低至
          <span class="red">{{ directServiceInfo.discount }}</span>
          折，
          <router-link to="/member/order/add/common?type=service&service_type=resume_package" class="blue">立即了解
          </router-link>
        </div>
      </div>
    </van-dialog>
    <van-dialog
      v-model="showUpgradePackage"
      title="下载简历"
      :closeOnClickOverlay="true"
      show-cancel-button
      cancel-button-text="取消"
      confirm-button-text="升级套餐"
      @cancel="cancels"
      @confirm="companyServiceUpgradePackage"
    >
      <div class="dialog_tip_wrapper">
        <div class="tx1">{{showUpgradePackageMsg}}</div>
      </div>
    </van-dialog>
    <van-dialog v-model="codePro.show" show-cancel-button :confirm-button-text="codePro.btnCn" @confirm="callCodePro"
                confirm-button-color="#1989fa">
      <div class="line18 m-top">拔打号码</div>
      <div class="line18 color-orange font15 bold" v-text="codePro.x"></div>
      <div class="line18 font12">
        (电话
        <span class="color-orange" v-text="codePro.timeout"></span>
        秒后失效,请尽快拔打)
      </div>
      <div v-if="phone_protect_type == 1" class="m-btm line18 font12 color-gray">
        仅支持使用
        <span v-text="codePro.a"></span>
        的手机卡拔号
      </div>
    </van-dialog>
    <van-popup v-model="showPayment" closeable position="bottom">
      <PopupPayment :amount="directServiceInfo.need_expense" @handlerSubmit="handlerDirectPay"></PopupPayment>
    </van-popup>
    <van-dialog v-model="showDetail" title="更多基本信息">
      <div class="details_pop_box">
        <div class="detail_line"
             v-if="field_rule.basic.marriage !== undefined && field_rule.basic.marriage.is_display == 1 && base_info.marriage_text">
          <div class="line_left">{{ field_rule.basic.marriage.field_cn }}：</div>
          <div class="line_right">{{ base_info.marriage_text }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line"
             v-if="field_rule.basic.height !== undefined && field_rule.basic.height.is_display == 1 && base_info.height != '' && base_info.height > 0">
          <div class="line_left">{{ field_rule.basic.height.field_cn }}：</div>
          <div class="line_right">{{ base_info.height }}CM</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line"
             v-if="field_rule.basic.major !== undefined && field_rule.basic.major.is_display == 1 && base_info.major_text">
          <div class="line_left">{{ field_rule.basic.major.field_cn }}：</div>
          <div class="line_right">{{ base_info.major_text }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line"
             v-if="field_rule.basic.householdaddress !== undefined && field_rule.basic.householdaddress.is_display == 1 && base_info.householdaddress">
          <div class="line_left">{{ field_rule.basic.householdaddress.field_cn }}：</div>
          <div class="line_right">{{ base_info.householdaddress }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line"
             v-if="field_rule.basic.residence !== undefined && field_rule.basic.residence.is_display == 1 && base_info.residence">
          <div class="line_left">{{ field_rule.basic.residence.field_cn }}：</div>
          <div class="line_right">{{ base_info.residence }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line"
             v-if="field_rule.basic.custom_field_1 !== undefined && field_rule.basic.custom_field_1.is_display == 1 && base_info.custom_field_1">
          <div class="line_left">{{ field_rule.basic.custom_field_1.field_cn }}：</div>
          <div class="line_right">{{ base_info.custom_field_1 }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line"
             v-if="field_rule.basic.custom_field_2 !== undefined && field_rule.basic.custom_field_2.is_display == 1 && base_info.custom_field_2">
          <div class="line_left">{{ field_rule.basic.custom_field_2.field_cn }}：</div>
          <div class="line_right">{{ base_info.custom_field_2 }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line"
             v-if="field_rule.basic.custom_field_3 !== undefined && field_rule.basic.custom_field_3.is_display == 1 && base_info.custom_field_3">
          <div class="line_left">{{ field_rule.basic.custom_field_3.field_cn }}：</div>
          <div class="line_right">{{ base_info.custom_field_3 }}</div>
          <div class="clear"></div>
        </div>
      </div>
    </van-dialog>
    <van-popup :lazy-render="false" v-model="showInvite" position="right" :overlay="false"
               style="width: 100%; height: 100%">
      <AddInvitation ref="child" from="detail" :apply_fullname="base_info.fullname" :resume_id="base_info.id"
                     @closePopup="closeInvitePopup"></AddInvitation>
    </van-popup>
    <van-popup :lazy-render="false" v-model="showTipoff" position="right" :overlay="false"
               style="width: 100%; height: 100%">
      <Tipoff ref="tipoff" :type="2" :target_id="base_info.id" :fullname="base_info.fullname"
              @closePopout="showTipoff = false"></Tipoff>
    </van-popup>
    <div class="alw-wx-layer" v-if="showWxLayer" @click="cancelShare"></div>
    <div class="alw-layer" v-if="showLayer" @click="cancelShare"></div>
    <SharePoster v-if="showPoster" @closePoster="closePoster" :type="'resume'" :infoid="shareid"></SharePoster>
    <van-overlay z-index="3" :show="showPoster" @click="showPoster = false"/>
    <van-popup v-model="showShare" position="bottom">
      <Share @cancelShare="cancelShare" @handleForward="handleForward" @handlePoster="handlePoster"></Share>
    </van-popup>
    <!-- <div class="generate_posters" @click="handlePoster">生成<br />海报</div> -->
    <!-- 绑定微信开始 -->
    <van-dialog v-model="bindWeixinShow" title="系统提示" :show-cancel-button="false" :show-confirm-button="true"
                @confirm="handleImCheckBind">
      <div class="bind-weixin-box">
        <div class="title-1">您当前未绑定微信，绑定后可发起聊天。</div>
        <div class="img"><img :src="scanQrcodeImg" alt=""/></div>
        <div class="title-2">使用微信扫一扫，按提示快速绑定</div>
      </div>
    </van-dialog>
    <!-- 绑定微信结束 -->
    <!-- 切换职位开始 -->
    <van-overlay :show="selectJobShow" z-index="3" :lock-scroll="false">
      <SelectJob
        @handleCommunicate="handleCommunicate"
        @handleCloseSelectJob="handleCloseSelectJob"
        :chatid="imChatid"
        :companyId="companyId"
        :isSelectJob="false"
      ></SelectJob>
    </van-overlay>
    <!-- 切花职位结束 -->
    <PaySubmit ref="paySubmit" payment="" :success-url="$route.path"></PaySubmit>
    <div class="generate_posters" :class="putAway ? 'generate_posters_all' : ''">
      <p class="poster_item" @click="handlerHomePage" v-if="!putAway">首页</p>
      <!-- <p class="poster_item copy_item" @click="handlerCopy">复制</p> -->
      <p class="poster_item hb_item" @click="handlePoster" v-if="!putAway">海报</p>
      <p class="poster_item all-item" @click="putAway = !putAway"><span class="all-item-img"
                                                                        :class="putAway ? 'all-item-img-qx' : ''"></span>
      </p>
    </div>

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
  </div>
</template>

<script>
import VideoList from '../../views/shortvideo/components/VideoListtwo'
import wxshare from '@/assets/js/share.js'
import Tipoff from '@/components/Tipoff'
import AddInvitation from '@/components/AddInvitation'
import {isWeiXin, parseTime} from '@/utils/index'
import PopupPayment from '@/components/service/PopupPayment'
import http from '@/utils/http'
import api from '@/api'
import Login from '@/components/Login'
import Share from '@/components/share/Share'
import SharePoster from '@/components/share/SharePoster'
import Vue from 'vue'
import {ImagePreview} from 'vant'
import SelectJob from '@/views/im/components/SelectJob.vue'
import {mapMutations} from 'vuex'
import PaySubmit from '@/components/service/PaySubmit'
import WeChatQrcode from '@/components/WeChatQrcode'

Vue.use(ImagePreview)
export default {
  name: 'ResumeShow',
  components: {
    VideoList,
    Login,
    PopupPayment,
    AddInvitation,
    Tipoff,
    Share,
    SharePoster,
    SelectJob,
    PaySubmit,
    WeChatQrcode
  },
  filters: {
    monthTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}')
    }
  },
  data () {
    return {
      resumeShow: 'resume',
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
      enableClick: true,
      mainLoading: true,
      query_id: '',
      is_company_login: false,
      showLogin: false,
      after_login_data: {},
      resume_module: {},
      field_rule: {
        basic: {},
        contact: {},
        intention: {},
        education: {}
      },
      base_info: {},
      intention_list: [],
      show_contact: 0,
      show_contact_note: '',
      contact_info: {},
      work_list: [],
      education_list: [],
      enclosure_resume: {},
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
            return '<span class="' + className + ' resume-show-swiper-span"></span>'
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
      shareid: 0,
      showShare: false,
      showWxLayer: false,
      showLayer: false,
      showPoster: false,
      previewImgList: [],
      page: 1,
      finished: false,
      loading: false,
      finished_text: '',
      videonum: 0,
      bindWeixinShow: false,
      jobid: 0,
      selectJobShow: false,
      imChatid: '',
      companyId: '',
      selectJobObj: {},
      // 绑定微信二维码
      scanQrcodeImg: '',
      putAway: true,
      params: {
        company_uid: '',
        job_apply_id: ''
      },
      showUpgradePackage: false,
      showUpgradePackageMsg: '',
      LoginType: 0,
      is_invalid: 1
    }
  },
  created () {
    this.query_id = this.$route.params.id
    this.LoginType = this.$store.state.LoginType
    this.is_company_login = !!(this.$store.state.LoginOrNot === true && this.$store.state.LoginType == 1)
    // 请求数据
    this.initQuery(this.$route.query)
    this.getScanQrcodeImg()
    if (this.$store.state.config.shortvideo_enable === '1') {
      this.fetchVideonum()
    }
  },
  watch: {
    bindWeixinShow (e) {
      if (e === true) {
        this.getScanQrcodeImg()
      }
    }
  },
  methods: {
    ...mapMutations(['setImShowParams', 'setimChatid']),
    handlerHomePage () {
      this.$router.push('/index')
    },
    /**
     * 绑定微信二维码
     */
    getScanQrcodeImg () {
      http.get(api.get_qrcode, {
        type: 'bind_weixin'
      }).then(res => {
        this.scanQrcodeImg = res.data
      })
    },
    callCodePro () {
      location.href = `tel:${this.codePro.x}`
    },
    async fetchData (next_method = null) {
      var request_params
      if (this.params.company_uid == '' || this.params.job_apply_id == '') {
        request_params = {
          id: this.query_id
        }
      } else {
        request_params = {
          id: this.query_id,
          company_uid: this.params.company_uid,
          job_apply_id: this.params.job_apply_id
        }
      }
      let res = await http.get(api.resumeshow, request_params)
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
        enclosure_resume,
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
        phone_protect_type,
        is_invalid
      } = {
        ...res.data
      }
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
      this.intention_list = intention_list
      this.show_contact = show_contact
      this.show_contact_note = show_contact_note
      this.contact_info = contact_info
      this.work_list = work_list
      this.education_list = education_list
      this.enclosure_resume = enclosure_resume
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
      this.is_invalid = is_invalid
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
          let res = await http.get(api.secret_phone, {
            resume_id: this.query_id
          })
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
            .catch(() => {
            })
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
          .catch(() => {
          })
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
          .catch(() => {
          })
      } else {
        // if (this.base_info.audit != 1) {
        //   this.$notify('该简历还未审核通过，不能继续此操作')
        //   return false
        // }
        http.post(api.company_index, {}).then(res => {
          var {companyinfo} = res.data

          // if (this.jobid == 0) {
          this.companyId = companyinfo.id
          //   this.selectJobShow = true
          //   return false
          // }
          http.post(api.imStart, {
            token: this.$store.state.imToken,
            resumeid: this.base_info.id,
            jobid: this.jobid
          }).then(res => {
            // disabled 不能使用功能
            // bind_weixin绑定微信
            // complete_resume完善简历
            // 空字符串 正常使用
            // choose_job 选择职位
            // pay 需要购买增值服务，触屏是快捷支付
            console.log(res)
            if (parseInt(res.code) == 200) {
              if (res.data.next == '') {
                this.setImShowParams({
                  jobname: this.selectJobObj.jobname,
                  name: this.base_info.fullname,
                  resumeid: this.base_info.id,
                  jobid: this.jobid,
                  companyId: companyinfo.id
                })
                this.setimChatid(res.data.chatid)
                this.$router.push({
                  path: '/im/' + res.data.chatid
                })
                return false
              }
              if (res.data.next == 'disabled') {
                // this.$notify({ type: 'danger', message: res.message })
                this.$dialog({
                  title: '系统提示',
                  message: res.message,
                  showConfirmButton: true
                }).then(() => {
                })
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
                    this.$router.push({
                      path: '/member/personal/resume'
                    })
                  })
                  .catch(() => {
                  })
                return false
              }
              if (res.data.next == 'bind_weixin') {
                this.bindWeixinShow = true
              }
              if (res.data.next == 'pay') {
                // 快捷支付
                this.$dialog
                  .confirm({
                    title: '系统提示',
                    message: res.message,
                    confirmButtonText: '去支付'
                  })
                  .then(() => {
                    this.$router.push({
                      path: '/member/order/add/common?type=service&service_type=im'
                    })
                  })
                  .catch(() => {
                  })
              }
              if (res.data.next == 'choose_job') {
                this.selectJobShow = true
              }
            }
          })
        })
        // this.$router.push('/im/' + this.base_info.im_userid)
      }
      //  else {
      //   this.$notify('暂时无法与当前用户进行职聊')
      //   return false
      // }
    },
    /**
     * 选择沟通职位
     * @jobItem 当前沟通职位信息
     */
    handleCommunicate (jobItem) {
      this.selectJobShow = false
      this.jobid = jobItem.id
      this.selectJobObj = jobItem
      this.doMsg()
    },
    /**
     * 是否绑定微信公众号
     */
    handleImCheckBind () {
      http.get(api.imCheckBind).then(res => {
        if (res.data != 0) {
          location.reload(true)
        }
      })
    },
    /**
     * 选择职位弹窗关闭
     */
    handleCloseSelectJob () {
      this.selectJobShow = false
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
          .catch(() => {
          })
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
          .catch(() => {
          })
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
        this.downloadResume()
      }
    },
    downloadResume () {
      const params = {
        resume_id: this.query_id
      }
      http.post(api.resumedownload, params)
        .then(res => {
          this.enableClick = true
          if (res.data.done == 0) {
            if (res.data.need_upgrade == 1) {
              this.showUpgradePackage = true
              this.showUpgradePackageMsg = res.message
            } else {
              this.showDirectService = true
              var btnCn = ''
              if (res.data.use_type == 'points') {
                btnCn = '立即兑换'
              }
              if (res.data.use_type == 'package') {
                btnCn = '购买增值包'
              }
              if (res.data.use_type == 'money') {
                btnCn = '立即支付'
              }
              this.directServiceInfo = {
                use_type: res.data.use_type,
                need_points: res.data.need_points,
                need_expense: res.data.need_expense,
                discount: res.data.discount,
                resume: this.query_id,
                btnCn: btnCn,
                cancel: res.data.use_type == 'package' ? '升级套餐' : '取消'
              }
            }
            return false
          } else {
            this.$notify({
              type: 'success',
              message: res.message
            })
            this.fetchData()
            /**
             * 【ID1000719】
             * 【新增】公众号引导弹窗场景（下载简历）
             * cy 2023-7-19
             */
            this.popupWechatQrcodeWindow('company_m_download_resume', 3)
          }
        })
        .catch(() => {
          this.enableClick = true
        })
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
          .catch(() => {
          })
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
        http.post(_api_url, params)
          .then(res => {
            this.enableClick = true
            this.$notify({
              type: 'success',
              message: res.message
            })
            if (this.has_fav === 0) {
              /**
               * 【ID1000719】
               * 【新增】公众号引导弹窗场景（收藏简历）
               * cy 2023-7-19
               */
              this.popupWechatQrcodeWindow('company_m_collect_resume', 3)
            }
            this.has_fav = this.has_fav == 1 ? 0 : 1
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
      this.shareid = this.query_id
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
      } else if (this.directServiceInfo.use_type == 'package') {
        this.$router.push('/member/order/add/common?type=service&service_type=resume_package')
      } else {
        this.showPayment = true
      }
    },
    cancels () {
      if (this.directServiceInfo.use_type == 'package') {
        this.$router.push('/member/order/add/common?type=setmeal')
      } else {
        this.showDirectService = false
      }
    },
    handlerDirectPay (payment) {
      let pay_data = {
        service_type: 'single_resume_down',
        deduct_points: this.directServiceInfo.use_type == 'points' ? this.directServiceInfo.need_points : 0,
        payment,
        resumeid: this.query_id,
        return_url: this.$store.state.config.mobile_domain + 'resume/' + this.query_id
      }
      this.$refs.paySubmit.handlerSubmit(api.company_pay_direct_service, pay_data)
      // http
      //   .post(api.company_pay_direct_service, pay_data)
      //   .then(res => {
      //     if (res.data.pay_status == 1) {
      //       this.$notify({ type: 'success', message: '支付成功' })
      //       this.fetchData(true)
      //       return false
      //     } else {
      //       this.handlerPay(res.data.parameter, payment)
      //     }
      //   })
      //   .catch(() => {})
    },
    // handlerPay (parameter, payment) {
    //   if (payment == 'wxpay') {
    //     if (isWeiXin()) {
    //       let successUrl = this.$route.path
    //       let locationUrl = this.$store.state.config.mobile_domain + 'pay/jsapi?appId=' + parameter.jsApiParameters.appId + '&timeStamp=' + parameter.jsApiParameters.timeStamp + '&nonceStr=' + parameter.jsApiParameters.nonceStr + '&package=' + parameter.jsApiParameters.package + '&signType=' + parameter.jsApiParameters.signType + '&paySign=' + parameter.jsApiParameters.paySign + '&successUrl=' + successUrl
    //       window.location.href = locationUrl
    //     } else {
    //       window.location.href = parameter
    //     }
    //   } else {
    //     window.location.href = parameter
    //   }
    // },
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
          .catch(() => {
          })
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
    },
    fetchVideonum () {
      http.get(api.shortvideo_total, {
        rid: this.query_id
      })
        .then(res => {
          this.videonum = res.data
        })
        .catch(() => {
        })
    },
    initQuery (query) {
      for (const key in this.params) {
        if (query.hasOwnProperty(key)) {
          this.params[key] = query[key]
        } else {
          this.params[key] = ''
        }
      }
      this.fetchData()
    },
    companyServiceDownloadResume () {
      this.$router.push('/member/order/add/common?type=service&service_type=resume_package')
    },
    companyServiceUpgradePackage () {
      this.$router.push('/member/order/add/common?type=setmeal')
    },
    // 下载附件简历
    doEnclosureDowenload () {
      if (this.is_company_login === false) {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
          })
          .catch(() => {
          })
      } else {
        if (this.base_info.audit != 1) {
          this.$notify('该简历还未审核通过，不能继续此操作')
          return false
        }
        if (this.show_contact_note == 'need_download') {
          this.$dialog
            .confirm({
              title: '提示',
              message: '查看简历附件需先下载简历，是否下载【' + this.base_info.fullname + '】的简历？',
              confirmButtonText: '下载'
            })
            .then(() => {
              this.downloadResume()
            })
            .catch(() => {
            })
        }
      }
    },
    // 关闭面试邀请弹窗
    closeInvitePopup(){
      this.showInvite = false
      /**
       * 【ID1000719】
       * 【新增】公众号引导弹窗场景（面试邀请）
       * cy 2023-7-19
       */
      this.popupWechatQrcodeWindow('company_m_interview_invite', 3)
    },
    // 弹出微信二维码弹框
    popupWechatQrcodeWindow(val, type) {
      this.$refs.weChatQrcodeRef.handleOpen(val, type)
    }
  }
}
</script>

<style lang="scss">
.generate_posters {
  position: fixed;
  z-index: 1;
  right: 15px;
  bottom: 80px;
  background: #e0eeff;
  border-radius: 45px;
  width: 154px;
  height: 45px;
  transition: width 0.5s;
  -moz-transition: width 0.5s;
  /* Firefox 4 */
  -webkit-transition: width 0.5s;
  /* Safari and Chrome */
  -o-transition: width 0.5s;
  /* Opera */
  line-height: 58px;
  padding-top: 0;
  padding-left: 10px;

  .poster_item {
    z-index: 2;
    width: 33%;
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
      background: url('../../assets/images/company/qx.png') 0 no-repeat;
      background-size: 100% 100%;
      margin-top: 8px;
    }

    .all-item-img-qx {
      width: 25px;
      height: 25px;
      background: url('../../assets/images/company/more_icon.png') 0 no-repeat;
      background-size: 100% 100%;
      margin-top: 7px;
    }
  }
}

.generate_posters_all {
  width: 45px;
}

.box_report {
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
    background: url('../../assets/images/jobshow/warn_icon.png') 0 center no-repeat;
    background-size: 12px;
  }

  width: 100%;
  background-color: #f8f9fa;
  position: relative;
  padding: 12px;
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
  overflow: hidden;
}

.box_13 {
  .bottom_bar {
    .item_apply {
      // float: left;
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

    .item_chat {
      flex-shrink: 0;
      // float: left;
      border-radius: 5px;
      width: 100px;
      height: 43px;
      text-align: center;
      font-size: 15px;
      color: #ffffff;
      background-size: 17px;
      position: relative;
      line-height: 43px;
      margin-right: 12px;
      background: #167eff;

      &::after {
        position: absolute;
        box-sizing: border-box;
        content: ' ';
        pointer-events: none;
        right: 0;
        top: 0;
        left: 0;
        // border-bottom: 0.026667rem solid #f3f3f3;
      }
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
      background: url('../../assets/images/calling_ico.svg') center 9px no-repeat;
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
      background: url('../../assets/images/jobshow/sc.png') center 9px no-repeat;
      background-size: 17px;

      &.item_collect_ac {
        background: url('../../assets/images/jobshow/sc_act.png') center 9px no-repeat;
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

    &::after {
      position: absolute;
      box-sizing: border-box;
      content: ' ';
      pointer-events: none;
      right: 0;
      top: 0;
      left: 0;
      //border-bottom: 0.026667rem solid #f3f3f3;
    }
  }

  position: relative;
  width: 100%;
  height: 63px;
  background-color: #ffffff;
}

.box_12 {
  width: 100%;
  padding-bottom: 17px;
  //border-bottom: 1px solid #f5f5f5;

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

    .swiper-duo {
      overflow-x: auto;
      overflow-y: hidden;
      height: 90px;
      width: 100%;
      white-space: nowrap;

      .swiper-item {
        display: inline-block;
        width: 130px;
        height: 90px;
        margin-right: 10px;

        img {
          display: inline-block;
          width: 100%;
          height: 100%;
          border-radius: 3px;
        }
      }

      .swiper-item:last-child {
        margin-right: 0;
      }
    }
  }
}

.box_14 {
  .resume_attachment {
    color: #1e50a2;
    font-size: 14px;
    background: url('../../assets/images/resumeshow/resumeAttachment.png') left center no-repeat;
    background-size: 16px 18px;
    width: 100%;
    box-sizing: border-box;
    padding-left: 24px;
    cursor: pointer;
    height: 20px;
    line-height: 22px;
  }
}

.box_11 {
  width: 100%;
  //border-bottom: 1px solid #f5f5f5;

  .box_content {
    .tx1 {
      .right_txt {
        color: #999999;
      }

      &:not(:last-child) {
        margin-bottom: 14px;
      }

      // font-size: 15px;
      // color: #333333;
      // position: relative;
      float: left;
      font-size: 13px;
      color: #595959;
      background-color: #f1f1f1;
      padding: 3px 8px;
      border-radius: 3px;
      margin: 0 10px 5px 0;
    }

    padding-bottom: 7px;
  }
}

.box_10 {
  width: 100%;
  //border-bottom: 1px solid #f5f5f5;

  .box_content {
    .tx1 {
      float: left;
      font-size: 13px;
      color: #595959;
      background-color: #f1f1f1;
      padding: 3px 8px;
      border-radius: 3px;
      margin: 0 10px 14px 0;

      &::last-child {
        margin-bottom: 0;
      }

      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #666666;
      }
    }

    padding-bottom: 21.5px;
  }
}

.box_9 {
  width: 100%;
  //border-bottom: 1px solid #f5f5f5;

  .box_content {
    .tx1 {
      // &::before {
      //   content: " ";
      //   position: absolute;
      //   left: 4px;
      //   top: 6px;
      //   width: 4px;
      //   height: 4px;
      //   border-radius: 100%;
      //   border: 2px solid #3388ff;
      //   background-color: #ffffff;
      // }
      // &::after {
      //   content: " ";
      //   position: absolute;
      //   left: 7px;
      //   top: 16px;
      //   height: 93%;
      //   border-right: 1px solid #f5f5f5;
      // }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
        text-align: justify;
      }

      .t3 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;

        .t3_time {
          font-size: 12px;
          float: right;
          color: 999;
          margin-top: 3px;
          color: #999;
        }
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
        color: #222222;
        font-weight: 700;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
      }

      .t5 {
        color: #999;
        font-size: 14px;
      }

      &:not(:last-child) {
        margin-bottom: 15px;
      }

      border-left: 0.026667rem dashed #8abeff;
      border-bottom: 0.026667rem dashed #8abeff;
      padding: 0 15px 8px;
      position: relative;
    }

    padding-bottom: 21.5px;
  }
}

.box_8 {
  width: 100%;
  //border-bottom: 1px solid #f5f5f5;

  .box_content {
    .tx1 {
      // &::before {
      //   content: " ";
      //   position: absolute;
      //   left: 4px;
      //   top: 6px;
      //   width: 4px;
      //   height: 4px;
      //   border-radius: 100%;
      //   border: 2px solid #3388ff;
      //   background-color: #ffffff;
      // }
      // &::after {
      //   content: " ";
      //   position: absolute;
      //   left: 7px;
      //   top: 16px;
      //   height: 93%;
      //   border-right: 1px solid #f5f5f5;
      // }
      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
        text-align: justify;
      }

      .t3 {
        font-size: 14px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;

        .t3_time {
          font-size: 12px;
          float: right;
          color: 999;
          margin-top: 3px;
          color: #999;
        }
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

      .t5 {
        color: #999;
        font-size: 14px;
      }

      &:not(:last-child) {
        margin-bottom: 15px;
      }

      border-left: 0.026667rem dashed #8abeff;
      border-bottom: 0.026667rem dashed #8abeff;
      padding: 0 15px 8px;
      position: relative;
    }

    padding-bottom: 17.5px;
  }
}

.box_7 {
  width: 100%;
  //border-bottom: 1px solid #f5f5f5;

  .box_content {
    .tx1 {
      // &::before {
      //   content: " ";
      //   position: absolute;
      //   left: -4px;
      //   top: 0px;
      //   width: 5px;
      //   height: 5px;
      //   border-radius: 100%;
      //   border: 1px solid #3388ff;
      //   background-color: #ffffff;
      // }
      // &::after {
      //   content: " ";
      //   position: absolute;
      //   left: 7px;
      //   top: 16px;
      //   height: 95%;
      //   border-right: 0.026667rem dashed #8abeff;
      // }
      .t5 {
        font-size: 14px;
        color: #999999;
      }

      .t4 {
        line-height: 1.8;
        font-size: 12px;
        color: #666666;
        word-break: normal;
        word-wrap: break-word;
        text-align: justify;
      }

      .t3 {
        font-size: 14px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 5px;

        span {
          float: right;
          font-size: 12px;
          color: #999;
        }
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
        font-size: 16px;
        color: #222222;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 10.5px;
        font-weight: bold;
      }

      &:not(:last-child) {
        margin-bottom: 15px;
      }

      padding: 0 15px 8px;
      position: relative;
      box-sizing: border-box;
      border-bottom: 1px dashed #8abeff;
      border-left: 1px dashed #8abeff;
    }
  }
}

.box_6 {
  width: 100%;
  //border-bottom: 1px solid #f5f5f5;

  .box_content {
    .tx1 {
      // &::before {
      //   content: "";
      //   position: absolute;
      //   left: 4px;
      //   top: 6px;
      //   width: 4px;
      //   height: 4px;
      //   border-radius: 100%;
      //   border: 2px solid #3388ff;
      //   background-color: #ffffff;
      // }
      // &::after {
      //   content: " ";
      //   position: absolute;
      //   left: 7px;
      //   top: 16px;
      //   height: 93%;
      //   border-right: 1px solid #f5f5f5;
      // }
      border-left: 1px dashed #8abeff;
      border-bottom: 1px dashed #8abeff;

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

        .school-time {
          float: right;
          font-size: 12px;
          color: #999;
          margin-top: 3px;
        }
      }

      &:not(:last-child) {
        margin-bottom: 15px;
      }

      padding: 0 15px 8px;
      position: relative;
    }
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
      text-align: justify;
    }
  }
}

.box_cac {
  padding-right: 16px;
  width: 100%;

  .phone_tip {
    display: inline-block;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
    color: #666;
    font-size: 12px;

    .phone {
      color: #ffa54e;
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

  .box_content {
    .item {
      // &.phone {
      //   background: url("../../assets/images/cac_phone_ico.svg") 2px center
      //     no-repeat;
      //   background-size: 16px;
      // }
      // &.wx {
      //   background: url("../../assets/images/cac_wx_ico.svg") 0 center no-repeat;
      //   background-size: 16px;
      // }
      // &.email {
      //   background: url("../../assets/images/cac_email_ico.svg") 0 center no-repeat;
      //   background-size: 16px;
      // }
      // &.qq {
      //   background: url("../../assets/images/cac_qq_ico.svg") 0 center no-repeat;
      //   background-size: 16px;
      // }
      font-size: 15px;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding: 0 20px 0 0;
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
      padding-top: 25px;
      margin-bottom: 3px;
    }

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
      margin-top: 8px;

      span {
        display: inline-block;
        width: 10px;
        height: 10px;
        background: url('../../assets/images/jobshow/return.png') 0 no-repeat;
        background-size: 0.32rem;
        position: absolute;
        top: 9px;
        right: 13px;
        border: none;
      }
    }

    padding: 3px 16px 20px 0;
    text-align: center;
    font-size: 14px;
    color: #0c317f;
    // box-shadow: 0 0 8px #c2c2c2;
    border-radius: 7px;
    background: linear-gradient(170deg, #f2f8ff, #cbe8fe, #d0e5ff);
  }
}

.box_3 {
  .tx2 {
    &:not(:first-child) {
      margin-top: 13.5px;
    }

    .intent {
      font-size: 12px;
      color: #ff6b08;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      background: url('../../assets/images/resumeshow/money.png') 0 center no-repeat;
      background-size: 12px 12px;
      box-sizing: border-box;
      padding-left: 20px;
      margin: 10px 0;
    }

    .address {
      background: url('../../assets/images/resumeshow/address.png') 0 3px no-repeat;
      background-size: 12px 12px;
      color: #595959;
      white-space: initial;
    }

    .name {
      font-size: 15px;
      color: #222222;
      //white-space: nowrap;
      //text-overflow: ellipsis;
      white-space: initial;
      margin-bottom: 5.5px;
      font-weight: bold;

      span {
        font-size: 12px;
        color: #34ba86;
        border: 1px solid #34ba86;
        padding: 1px 4px;
        border-radius: 5px;
        margin-right: 8px;
      }
    }
  }

  .box_content {
    // padding-bottom: 17.5px;
  }

  width: 100%;
  // border-bottom: 1px solid #f5f5f5;
  // border-top: 1px solid #f5f5f5;
}

.content_wrapper {
  width: 100%;
  background-color: #ffffff;
  padding: 0 17px;

  .invalid_img {
    display: block;
    width: 79px;
    height: 47px;
    position: absolute;
    right: 45px;
    top: 330px;
    z-index: 2;
  }
}

.box_nav {
  .item {
    &.active {
      &::after {
        content: ' ';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translate(-50%, 0);
        width: 18px;
        height: 2.5px;
        background-color: #1787fb;
        border-radius: 3px;
      }

      color: #1787fb;
    }

    flex: 1;
    position: relative;
    padding: 15px 0;
    text-align: center;
    font-size: 17px;
    font-weight: bold;
    color: #333333;
  }

  width: 100%;
  background-color: #ffffff;
  display: flex;
  box-shadow: 0 1px 5px #f1eded;
  z-index: 1;
  position: relative;
  margin-top: 10px;
  border-radius: 21px 21px 0 0;
}

.box_2 {
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
  padding: 0 0 0 17px;
}

.box_content {
  .tx1 {
    .circle {
      width: 20px;
      height: 20px;
      background: #fff url('../../assets/images/resumeshow/circle.png') 0 bottom no-repeat;
      background-size: 10px 10px;
      position: absolute;
      top: -4px;
      left: -5px;
    }
  }
}

.box_head {
  .txt {
    font-size: 18px;
    color: #333333;
    font-weight: bold;

    .title {
      background: url('../../assets/images/company/border_bg.png') 0 bottom no-repeat;
      background-size: 100% 3px;
    }
  }

  .right_text {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 13px;
    color: #167eff;
    background: #edf5ff;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 12px;
  }

  position: relative;
  width: 100%;
  padding: 20px 0 21.5px;
}

.box_1 {
  .content {
    .invalid_resume {
      position: absolute;
      right: 0;
      top: 0;
      width: 130px;
      height: 100%;
      z-index: 10;
    }

    .share {
      width: 20px;
      height: 20px;
      position: absolute;
      right: 16px;
      top: 18px;
      font-size: 12px;
      color: #999999;
      border-radius: 7px;
      background: url('../../assets/images/company/share.png') 0 no-repeat;
      background-size: 100% 100%;
    }

    .collect {
      width: 20px;
      height: 20px;
      position: absolute;
      right: 53px;
      top: 17px;
      font-size: 12px;
      color: #999999;
      border-radius: 7px;
      background: url('../../assets/images/company/like.png') 0 no-repeat;
      background-size: 100% 100%;
    }

    .collect_act {
      background: url('../../assets/images/company/like_act.png') 0 no-repeat;
      background-size: 100% 100%;
    }

    .down {
      .right {
        &::after {
          position: absolute;
          right: 2px;
          top: 11px;
          width: 6px;
          height: 6px;
          border-top: 1px solid #fff;
          border-right: 1px solid #fff;
          transform: rotate(45deg);
          content: ' ';
        }

        position: absolute;
        right: 2px;
        top: 68%;
        transform: translate(0, -50%);
        font-size: 12px;
        color: #fff;
        padding: 5px 11px 5px 0;
      }

      position: relative;
      padding: 12.5px 2px 2px;
      font-size: 12px;
      color: #999999;
      // border-top: 1px solid #f8f8f8;
    }

    div.mid-div {
      position: relative;
      margin: 18px 0 0 0;

      .mid {
        padding: 8px;
        font-size: 12px;
        background: linear-gradient(90deg, #ff6b08, rgba(255, 255, 255, 0));
        opacity: 0.2;
        border-radius: 0px 28px 28px 28px;
        height: 36px;
      }

      .text {
        .num {
          color: #ff6600;
        }

        width: 100%;
        height: 100%;
        padding: 8px;
        color: #ffffff;
        font-size: 12px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        position: absolute;
        top: 0;
        left: 0;
      }
    }

    .up {
      .tag {
        padding: 1px 5px 1px 17px;
        border-radius: 3px;
        color: #ffffff;
        font-size: 10px;
        width: fit-content;
        margin-top: 9px;
        background: #ffa57d url('../../assets/images/fab_ico.svg') 5px center no-repeat;
        background-size: 10px;
      }

      .tx2 {
        font-size: 15px;
        // padding-right: 20px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        color: #dcdcdc;

        span {
          color: #8a90b4;
          font-size: 12px;
          float: right;
          margin-top: 5px;
        }
      }

      .tx1 {
        margin-bottom: 8px;

        .level_ico {
          float: left;
          margin-left: 10px;
          width: 45px;
          height: 27px;
          background: url('../../assets/images/resumeshow/yz.png') 0 center no-repeat;
          background-size: 45px 18px;
        }

        .name {
          float: left;
          font-size: 19px;
          font-weight: bold;
          color: #ffffff;
        }
      }

      .avatar_box {
        .gender {
          &.female {
            background: #ff8d65 url('../../assets/images/female_ico.svg') center no-repeat;
            background-size: 9px;
          }

          &.male {
            background: #4fa5fa url('../../assets/images/male_ico.svg') center no-repeat;
            background-size: 9px;
          }

          position: absolute;
          right: 0;
          top: 0;
          bottom: 2px;
          width: 13px;
          height: 13px;
          border-radius: 100%;
        }

        img {
          width: 55px;
          height: 55px;
          border: 0;
          border-radius: 100%;
        }

        position: absolute;
        left: 0;
        top: 17px;
        width: 55px;
        height: 55px;
        overflow: hidden;
      }

      position: relative;
      padding: 17px 0 0 70px;
    }

    position: relative;
    width: 350px;
    margin: 0 auto;
    padding: 0 3px;
    border-radius: 7px;
  }

  width: 100%;
  background: url('../../assets/images/company/bj.png') no-repeat;
  background-size: 100%;
  padding: 20px 0 1px;
}

.orange-phone {
  color: #ffa54e;
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
  color: #ffa54e;
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
