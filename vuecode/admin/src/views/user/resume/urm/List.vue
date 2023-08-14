<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix"><span>全部简历</span></div>
      <div style="float:right;z-index: 8;position: relative;"><el-button size="small" type="primary" @click="goto('/user/resume/add')">新增简历</el-button></div>
      <div class="list-search">
        <el-input v-model="keyword" placeholder="请输入搜索内容" class="input-with-select" @keyup.enter.native="funSearchKeyword">
          <el-select slot="prepend" v-model="key_type" placeholder="请选择" class="input-sel">
            <el-option label="简历名称" value="1" />
            <el-option label="简历联系方式" value="3" />
            <el-option label="会员联系方式" value="2" />
            <el-option label="简历ID" value="4" />
          </el-select>
          <el-button slot="append" icon="el-icon-search" @click="funSearchKeyword" />
        </el-input>
        <div class="block">
          <span class="list-screen">
            <el-cascader
              v-model="citycategoryScreen"
              placeholder="请选择意向地区"
              :options="citycategory"
              :props="{ checkStrictly: true }"
              :clearable="true"
              @change="screenList"
            />
          </span>
          <span class="list-screen">
            <el-cascader
              v-model="jobcategoryScreen"
              :clearable="true"
              placeholder="请选择意向职位"
              :options="jobcategory"
              :props="{ checkStrictly: true }"
              :show-all-levels="false"
              @change="screenList"
            />
          </span>
          <span class="list-screen">
            <el-select v-model="platformScreen" :clearable="true" placeholder="请选择注册来源" @change="screenList">
              <el-option v-for="item in platform" :key="item.id" :label="item.name" :value="item.id" />
            </el-select>
          </span>
        </div>
      </div>
      <div
        v-if="
          educationFilter != '' ||
            experienceFilter != '' ||
            highQualityFilter != '' ||
            deliveryNumFilter != '' ||
            downloadedFilter != '' ||
            viewedFilter != '' ||
            invitationFilter != '' ||
            auditFilter != '' ||
            worksFilter != '' ||
            enclosureResumeFilter != ''
        "
        class="filterCriteria"
      >
        <div style="float:left;display: inline-block;margin-top: 6px;">已选条件：</div>
        <div style="float:left;display: inline-block;width: 80%">
          <div v-if="educationFilter != ''" class="selected">
            <div class="name">学历：{{ educationFilter.name }}</div>
            <div class="closes" @click="reset(educationFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="experienceFilter != ''" class="selected">
            <div class="name">经验：{{ experienceFilter.name }}</div>
            <div class="closes" @click="reset(experienceFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="highQualityFilter != ''" class="selected">
            <div class="name">等级：{{ highQualityFilter.name }}</div>
            <div class="closes" @click="reset(highQualityFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="deliveryNumFilter != ''" class="selected">
            <div class="name">简历投递：{{ deliveryNumFilter.name }}</div>
            <div class="closes" @click="reset(deliveryNumFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="downloadedFilter != ''" class="selected">
            <div class="name">被下载：{{ downloadedFilter.name }}</div>
            <div class="closes" @click="reset(downloadedFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="viewedFilter != ''" class="selected">
            <div class="name">被查看：{{ viewedFilter.name }}</div>
            <div class="closes" @click="reset(viewedFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="invitationFilter != ''" class="selected">
            <div class="name">被面邀：{{ invitationFilter.name }}</div>
            <div class="closes" @click="reset(invitationFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="auditFilter != ''" class="selected">
            <div class="name">审核状态：{{ auditFilter.name }}</div>
            <div class="closes" @click="reset(auditFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="worksFilter != ''" class="selected">
            <div class="name">简历作品：{{ worksFilter.name }}</div>
            <div class="closes" @click="reset(worksFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="contactStatusFilter != ''" class="selected">
            <div class="name">联系状态：{{ contactStatusFilter.name }}</div>
            <div class="closes" @click="reset(contactStatusFilter.field)"><i class="el-icon-close" /></div>
          </div>
          <div v-if="enclosureResumeFilter != ''" class="selected">
            <div class="name">附件：{{ enclosureResumeFilter.name }}</div>
            <div class="closes" @click="reset(enclosureResumeFilter.field)"><i class="el-icon-close" /></div>
          </div>
        </div>
        <div style="float:right;display: inline-block;margin-top: 6px;color:#409eff;font-size: 13px;" @click="reset('all')">
          <i class="el-icon-delete" />
          清空条件
        </div>
        <div style="clear:both;" />
      </div>
      <div style="position: relative;">
        <div
          :class="
            educationFilter != '' ||
              experienceFilter != '' ||
              highQualityFilter != '' ||
              deliveryNumFilter != '' ||
              downloadedFilter != '' ||
              viewedFilter != '' ||
              invitationFilter != '' ||
              auditFilter != '' ||
              worksFilter != '' ||
              contactStatusFilter != '' ||
              citycategoryFilter != '' ||
              enclosureResumeFilter != ''
              ? 'setField_s'
              : 'setField'
          "
        >
          <el-popover v-model="visiblepop" placement="bottom-start" width="220" trigger="manual">
            <div class="setField_h">
              <div v-for="item in fieldData" class="setFields">
                <input v-if="item.select == true" type="checkbox" :name="item.name" :value="item.field" checked @change="select(item.field)">
                <input v-if="item.select == false" type="checkbox" :name="item.name" :value="item.field" @change="select(item.field)">
                <span style="margin-left: 10px;">{{ item.name }}</span>
              </div>
            </div>
            <el-button slot="reference" :class="menu_icon == 'menu' ? 'field_s' : 'field_s_select'" @click="menu">
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
          v-loading="loading"
          :data="tableData"
          tooltip-effect="dark"
          :header-cell-style="{ background: '#f9f9f9', 'border-right': '1px solid #e4e6eb' }"
          border
          stripe
          :height="tabelHeight"
          style="width: 100%;"
          @sort-change="sortTable"
          @selection-change="handleSelectionChange"
        >
          <el-table-column fixed type="selection" width="55" />
          <el-table-column
            v-for="items in fieldData"
            v-if="items.is_locking == true && items.select == true"
            fixed
            :sortable="items.is_sortable"
            :prop="items.field"
            :width="items.width"
          >
            <template slot="header" slot-scope="scope">
              <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover placement="bottom" trigger="hover">
                  <!-- 学历-->
                  <div v-if="items.field == 'education_cn'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in educationData" class="screenStyle">
                        <el-radio :key="item.id" v-model="educationScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 学历-->
                  <!-- 经验-->
                  <div v-if="items.field == 'experience_cn'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in experienceData" class="screenStyle">
                        <el-radio :key="item.id" v-model="experienceScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 经验-->
                  <!-- 等级-->
                  <div v-if="items.field == 'high_quality'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in highQualityData" class="screenStyle">
                        <el-radio :key="item.id" v-model="highQualityScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 等级-->
                  <!-- 投递数-->
                  <!--                  <div v-if="items.field == 'delivery_num'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in deliveryNumData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="deliveryNumScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 投递数-->
                  <!-- 被下载-->
                  <!--                  <div v-if="items.field == 'downloaded'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in downloadedData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="downloadedScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 被下载-->
                  <!-- 被查看-->
                  <!--                  <div v-if="items.field == 'viewed'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in viewedData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="viewedScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 被查看-->
                  <!-- 被面邀-->
                  <!--                  <div v-if="items.field == 'invitation'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in invitationData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="invitationScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 被面邀-->
                  <!-- 审核状态-->
                  <div v-if="items.field == 'audit'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in auditData" class="screenStyle">
                        <el-radio :key="item.id" v-model="auditScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 审核状态-->
                  <!-- 联系状态 -->
                  <div v-if="items.field == 'contact_status'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in contactStatusData" class="screenStyle">
                        <el-radio :key="item.id" v-model="contactStatusScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 联系状态-->
                  <!-- 附件简历-->
                  <div v-if="items.field == 'enclosure_resume'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in enclosureResumeData" class="screenStyle">
                        <el-radio :key="item.id" v-model="enclosureResumeScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 附件简历-->
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div
                    v-if="
                      items.field == 'education_cn' ||
                        items.field == 'experience_cn' ||
                        items.field == 'high_quality' ||
                        // items.field == 'delivery_num' ||
                        // items.field == 'downloaded' ||
                        // items.field == 'viewed' ||
                        // items.field == 'invitation' ||
                        items.field == 'audit' ||
                        // items.field == 'works' ||
                        items.field == 'contact_status' ||
                        items.field == 'enclosure_resume'
                    "
                    slot="reference"
                    class="drop_down"
                  />
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="unlock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="unlock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''">-</span>
                <span v-else>{{ scope.row.id }}</span>
              </div>
              <div v-if="items.field == 'photo_img_src'">
                <span><img style="width: 50px;height:50px;border-radius: 30px;" :src="scope.row.photo_img_src" alt=""></span>
              </div>
              <div v-if="items.field == 'fullname'">
                <span v-if="scope.row.fullname == ''">-</span>
                <span v-else>
                  <el-link :href="scope.row.link" target="_blank" type="primary" class="text" :underline="false">{{ scope.row.fullname }}</el-link>
                </span>
              </div>
              <div v-if="items.field == 'is_bind'">
                <span v-if="scope.row.is_bind == 0">未绑定</span>
                <span v-else>已绑定</span>
              </div>
              <div v-if="items.field == 'contact_mobile'">
                <span v-if="scope.row.contact_mobile == '' || scope.row.contact_mobile == null">-</span>
                <span v-else>
                  {{ scope.row.contact_mobile }}
                  <div class="dial" title="拨打简历手机号"><div class="disal_img" @click="clickDial(scope.row.contact_mobile, scope.row.fullname)" /></div>
                </span>
              </div>
              <div v-if="items.field == 'member_mobile'">
                <span v-if="scope.row.member_mobile == ''">-</span>
                <span v-else>
                  {{ scope.row.member_mobile }}
                  <div class="dial" title="拨打会员手机号"><div class="disal_img" @click="clickDial(scope.row.member_mobile, scope.row.fullname)" /></div>
                </span>
              </div>
              <div v-if="items.field == 'education_cn'">
                <span v-if="scope.row.education_cn == ''">-</span>
                <span v-else>{{ scope.row.education_cn }}</span>
              </div>
              <div v-if="items.field == 'experience_cn'">
                <span v-if="scope.row.experience_cn == ''">-</span>
                <span v-else>{{ scope.row.experience_cn }}</span>
              </div>
              <div v-if="items.field == 'complete_percent'">
                <span v-if="scope.row.complete_percent == ''">-</span>
                <!--                <span v-else> {{ scope.row.complete_percent }}% </span>-->
                <div class="complete_percent">
                  <span v-if="scope.row.complete_percent < 45" style="color:#f51e1e"><el-progress color="#f51e1e" :percentage="scope.row.complete_percent" /></span>
                  <span v-if="scope.row.complete_percent < 60 && scope.row.complete_percent >= 45" style="color:#fb3c11">
                    <el-progress color="#fb3c11" :percentage="scope.row.complete_percent" />
                  </span>
                  <span v-if="scope.row.complete_percent < 80 && scope.row.complete_percent >= 60" style="color:#1db75a">
                    <el-progress color="#1db75a" :percentage="scope.row.complete_percent" />
                  </span>
                  <span v-if="scope.row.complete_percent <= 100 && scope.row.complete_percent >= 80" style="color:#409eff">
                    <el-progress color="#409eff" :percentage="scope.row.complete_percent" />
                  </span>
                </div>
              </div>
              <div v-if="items.field == 'high_quality'">
                <span v-if="scope.row.high_quality == ''">普通</span>
                <span v-else>优质</span>
              </div>
              <div v-if="items.field == 'delivery_num'">
                <span v-if="scope.row.delivery_num == ''">0</span>
                <span v-else>{{ scope.row.delivery_num }}</span>
              </div>
              <div v-if="items.field == 'downloaded'">
                <span v-if="scope.row.downloaded == ''">0</span>
                <span v-else>{{ scope.row.downloaded }}</span>
              </div>
              <div v-if="items.field == 'viewed'">
                <span v-if="scope.row.viewed == ''">0</span>
                <span v-else>{{ scope.row.viewed }}</span>
              </div>
              <div v-if="items.field == 'invitation'">
                <span v-if="scope.row.invitation == ''">0</span>
                <span v-else>{{ scope.row.invitation }}</span>
              </div>
              <div v-if="items.field == 'audit'">
                <span v-if="scope.row.audit == 0">待审核</span>
                <span v-if="scope.row.audit == 1">已审核</span>
                <span v-if="scope.row.audit == 2">已拒绝</span>
              </div>
              <div v-if="items.field == 'works'">
                <span v-if="scope.row.works == ''">0</span>
                <span v-else>{{ scope.row.works }}</span>
              </div>
              <div v-if="items.field == 'refreshtime'">
                <span v-if="scope.row.refreshtime == ''">-</span>
                <span v-else>{{ scope.row.refreshtime | timeFilter }}</span>
              </div>
              <div v-if="items.field == 'last_login_time'">
                <span v-if="scope.row.last_login_time == ''">-</span>
                <span v-else>{{ scope.row.last_login_time | timeFilter }}</span>
              </div>
              <div v-if="items.field == 'reg_time'">
                <span v-if="scope.row.register_time == ''">-</span>
                <span v-else>{{ scope.row.reg_time | timeFilter }}</span>
              </div>
              <div v-if="items.field == 'contact_status'" style="text-align: center">
                <el-tooltip v-if="scope.row.is_status_phone == 1" class="item" effect="dark" content="未电话联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/1.png" alt="" @click="setContactStatus('phone', scope.row.id)">
                </el-tooltip>
                <el-tooltip v-if="scope.row.is_status_phone == 2" class="item" effect="dark" content="已电话联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/1-1.png" alt="" @click="setContactStatus('phone', scope.row.id)">
                </el-tooltip>
                <el-tooltip v-if="scope.row.is_status_weixin == 1" class="item" effect="dark" content="未微信联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/2.png" alt="" @click="setContactStatus('weixin', scope.row.id)">
                </el-tooltip>
                <el-tooltip v-if="scope.row.is_status_weixin == 2" class="item" effect="dark" content="已微信联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/2-1.png" alt="" @click="setContactStatus('weixin', scope.row.id)">
                </el-tooltip>
              </div>
              <div v-if="items.field == 'follow_num'">
                <span v-if="scope.row.follow_num == ''">0</span>
                <span v-else>{{ scope.row.follow_num }}</span>
              </div>
              <div v-if="items.field == 'final_follow'">
                <span v-if="scope.row.final_follow == ''">-</span>
                <span v-else>{{ scope.row.final_follow }}</span>
              </div>
              <div v-if="items.field == 'remark'">
                <span v-if="scope.row.remark == ''">-</span>
                <span v-else>{{ scope.row.remark }}</span>
              </div>
              <div v-if="items.field == 'enclosure_resume'">
                <span v-if="scope.row.enclosure_resume == ''">-</span>
                <span v-if="scope.row.enclosure_resume!=''&&is_check==1" style="cursor:pointer;color:#409EFF;" @click="seeResumeAttachment(scope.row)">查看</span>
                <span v-if="scope.row.enclosure_resume!=''&&is_check==0"><a style="cursor:pointer;color:#409EFF;" :href="scope.row.enclosure_resume" :download="scope.row.enclosure_resume">下载</a></span>
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
              <div :class="items.is_sortable == 'custom' ? 'sotrTime' : ''" @mouseenter="locks(items.field)" @mouseleave="locks(items.field)">
                <span>{{ items.name }}</span>
                <el-popover placement="bottom" trigger="hover">
                  <!-- 学历-->
                  <div v-if="items.field == 'education_cn'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in educationData" class="screenStyle">
                        <el-radio :key="item.id" v-model="educationScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 学历-->
                  <!-- 经验-->
                  <div v-if="items.field == 'experience_cn'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in experienceData" class="screenStyle">
                        <el-radio :key="item.id" v-model="experienceScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 经验-->
                  <!-- 等级-->
                  <div v-if="items.field == 'high_quality'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in highQualityData" class="screenStyle">
                        <el-radio :key="item.id" v-model="highQualityScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 等级-->
                  <!-- 投递数-->
                  <!--                  <div v-if="items.field == 'delivery_num'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in deliveryNumData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="deliveryNumScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 投递数-->
                  <!-- 被下载-->
                  <!--                  <div v-if="items.field == 'downloaded'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in downloadedData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="downloadedScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 被下载-->
                  <!-- 被查看-->
                  <!--                  <div v-if="items.field == 'viewed'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in viewedData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="viewedScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 被查看-->
                  <!-- 被面邀-->
                  <!--                  <div v-if="items.field == 'invitation'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in invitationData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="invitationScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 被面邀-->
                  <!-- 审核状态-->
                  <div v-if="items.field == 'audit'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in auditData" class="screenStyle">
                        <el-radio :key="item.id" v-model="auditScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 审核状态-->
                  <!-- 简历作品 -->
                  <!--                  <div v-if="items.field == 'works'" class="screen_s">-->
                  <!--                    <el-checkbox-group>-->
                  <!--                      <div v-for="item in worksData" class="screenStyle">-->
                  <!--                        <el-radio :key="item.id" v-model="worksScreen" :label="item.id">{{ item.name }}</el-radio>-->
                  <!--                      </div>-->
                  <!--                    </el-checkbox-group>-->
                  <!--                  </div>-->
                  <!-- 简历作品-->
                  <!-- 联系状态 -->
                  <div v-if="items.field == 'contact_status'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in contactStatusData" class="screenStyle">
                        <el-radio :key="item.id" v-model="contactStatusScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 联系状态-->
                  <!-- 附件简历-->
                  <div v-if="items.field == 'enclosure_resume'" class="screen_s">
                    <el-checkbox-group>
                      <div v-for="item in enclosureResumeData" class="screenStyle">
                        <el-radio :key="item.id" v-model="enclosureResumeScreen" :label="item.id">{{ item.name }}</el-radio>
                      </div>
                    </el-checkbox-group>
                  </div>
                  <!-- 附件简历-->
                  <div style="margin-top: 10px;">
                    <el-button class="filterOperation" type="text" size="small" @click="reset(items.field)">重置</el-button>
                    <el-button class="filterOperation" type="text" size="small" @click="confirm()">确认</el-button>
                  </div>
                  <div
                    v-if="
                      items.field == 'education_cn' ||
                        items.field == 'experience_cn' ||
                        items.field == 'high_quality' ||
                        // items.field == 'delivery_num' ||
                        // items.field == 'downloaded' ||
                        // items.field == 'viewed' ||
                        // items.field == 'invitation' ||
                        items.field == 'audit' ||
                        // items.field == 'works' ||
                        items.field == 'contact_status' ||
                        items.field == 'enclosure_resume'
                    "
                    slot="reference"
                    class="drop_down"
                  />
                </el-popover>
                <div v-if="items.is_lock_display == true" slot="reference" class="lock_display" @click="locking(items.field)" />
                <div v-if="items.is_lock_display == false" slot="reference" class="lock" @click="locking(items.field)" />
              </div>
            </template>
            <template slot-scope="scope">
              <div v-if="items.field == 'id'">
                <span v-if="scope.row.id == ''">-</span>
                <span v-else>{{ scope.row.id }}</span>
              </div>
              <div v-if="items.field == 'photo_img_src'">
                <span><img style="width: 50px;height:50px;border-radius: 30px;" :src="scope.row.photo_img_src" alt=""></span>
              </div>
              <div v-if="items.field == 'fullname'">
                <span v-if="scope.row.fullname == ''">-</span>
                <span v-else>
                  <el-link :href="scope.row.link" target="_blank" type="primary" class="text" :underline="false">{{ scope.row.fullname }}</el-link>
                </span>
              </div>
              <div v-if="items.field == 'is_bind'">
                <span v-if="scope.row.is_bind == 0">未绑定</span>
                <span v-else>已绑定</span>
              </div>
              <div v-if="items.field == 'contact_mobile'">
                <span v-if="scope.row.contact_mobile == '' || scope.row.contact_mobile == null">-</span>
                <span v-else>
                  {{ scope.row.contact_mobile }}
                  <div class="dial" title="拨打简历手机号"><div class="disal_img" @click="clickDial(scope.row.contact_mobile, scope.row.fullname)" /></div>
                </span>
              </div>
              <div v-if="items.field == 'member_mobile'">
                <span v-if="scope.row.member_mobile == ''">-</span>
                <span v-else>
                  {{ scope.row.member_mobile }}
                  <div class="dial" title="拨打会员手机号"><div class="disal_img" @click="clickDial(scope.row.member_mobile, scope.row.fullname)" /></div>
                </span>
              </div>
              <div v-if="items.field == 'education_cn'">
                <span v-if="scope.row.education_cn == ''">-</span>
                <span v-else>{{ scope.row.education_cn }}</span>
              </div>
              <div v-if="items.field == 'experience_cn'">
                <span v-if="scope.row.experience_cn == ''">-</span>
                <span v-else>{{ scope.row.experience_cn }}</span>
              </div>
              <div v-if="items.field == 'complete_percent'">
                <span v-if="scope.row.complete_percent == ''">-</span>
                <!--                <span v-else> {{ scope.row.complete_percent }}% </span>-->
                <div class="complete_percent">
                  <span v-if="scope.row.complete_percent < 45" style="color:#f51e1e"><el-progress color="#f51e1e" :percentage="scope.row.complete_percent" /></span>
                  <span v-if="scope.row.complete_percent < 60 && scope.row.complete_percent >= 45" style="color:#fb3c11">
                    <el-progress color="#fb3c11" :percentage="scope.row.complete_percent" />
                  </span>
                  <span v-if="scope.row.complete_percent < 80 && scope.row.complete_percent >= 60" style="color:#1db75a">
                    <el-progress color="#1db75a" :percentage="scope.row.complete_percent" />
                  </span>
                  <span v-if="scope.row.complete_percent <= 100 && scope.row.complete_percent >= 80" style="color:#409eff">
                    <el-progress color="#409eff" :percentage="scope.row.complete_percent" />
                  </span>
                </div>
              </div>
              <div v-if="items.field == 'high_quality'">
                <span v-if="scope.row.high_quality == ''">普通</span>
                <span v-else>优质</span>
              </div>
              <div v-if="items.field == 'delivery_num'">
                <span v-if="scope.row.delivery_num == ''">0</span>
                <span v-else>{{ scope.row.delivery_num }}</span>
              </div>
              <div v-if="items.field == 'downloaded'">
                <span v-if="scope.row.downloaded == ''">0</span>
                <span v-else>{{ scope.row.downloaded }}</span>
              </div>
              <div v-if="items.field == 'viewed'">
                <span v-if="scope.row.viewed == ''">0</span>
                <span v-else>{{ scope.row.viewed }}</span>
              </div>
              <div v-if="items.field == 'invitation'">
                <span v-if="scope.row.invitation == ''">0</span>
                <span v-else>{{ scope.row.invitation }}</span>
              </div>
              <div v-if="items.field == 'audit'">
                <span v-if="scope.row.audit == 0">待审核</span>
                <span v-if="scope.row.audit == 1">已审核</span>
                <span v-if="scope.row.audit == 2">已拒绝</span>
              </div>
              <div v-if="items.field == 'works'">
                <span v-if="scope.row.works == ''">0</span>
                <span v-else>{{ scope.row.works }}</span>
              </div>
              <div v-if="items.field == 'refreshtime'">
                <span v-if="scope.row.refreshtime == ''">-</span>
                <span v-else>{{ scope.row.refreshtime | timeFilter }}</span>
              </div>
              <div v-if="items.field == 'last_login_time'">
                <span v-if="scope.row.last_login_time == ''">-</span>
                <span v-else>{{ scope.row.last_login_time | timeFilter }}</span>
              </div>
              <div v-if="items.field == 'reg_time'">
                <span v-if="scope.row.register_time == ''">-</span>
                <span v-else>{{ scope.row.reg_time | timeFilter }}</span>
              </div>
              <div v-if="items.field == 'contact_status'" style="text-align: center">
                <el-tooltip v-if="scope.row.is_status_phone == 1" class="item" effect="dark" content="未电话联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/1.png" alt="" @click="setContactStatus('phone', scope.row.id)">
                </el-tooltip>
                <el-tooltip v-if="scope.row.is_status_phone == 2" class="item" effect="dark" content="已电话联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/1-1.png" alt="" @click="setContactStatus('phone', scope.row.id)">
                </el-tooltip>
                <el-tooltip v-if="scope.row.is_status_weixin == 1" class="item" effect="dark" content="未微信联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/2.png" alt="" @click="setContactStatus('weixin', scope.row.id)">
                </el-tooltip>
                <el-tooltip v-if="scope.row.is_status_weixin == 2" class="item" effect="dark" content="已微信联系，点击切换状态" placement="top-start">
                  <img class="contact_status" src="../../../../assets/images/urm/2-1.png" alt="" @click="setContactStatus('weixin', scope.row.id)">
                </el-tooltip>
              </div>
              <div v-if="items.field == 'follow_num'">
                <span v-if="scope.row.follow_num == ''">0</span>
                <span v-else>{{ scope.row.follow_num }}</span>
              </div>
              <div v-if="items.field == 'final_follow'">
                <span v-if="scope.row.final_follow == ''">-</span>
                <span v-else>{{ scope.row.final_follow }}</span>
              </div>
              <div v-if="items.field == 'remark'">
                <span v-if="scope.row.remark == ''">-</span>
                <span v-else>{{ scope.row.remark }}</span>
              </div>
              <div v-if="items.field == 'enclosure_resume'">
                <span v-if="scope.row.enclosure_resume == ''">-</span>
                <span v-if="scope.row.enclosure_resume!=''&&is_check==1" style="cursor:pointer;color:#409EFF;" @click="seeResumeAttachment(scope.row)">查看</span>
                <span v-if="scope.row.enclosure_resume!=''&&is_check==0"><a style="cursor:pointer;color:#409EFF;" :href="scope.row.enclosure_resume" :download="scope.row.enclosure_resume">下载</a></span>
              </div>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="180" fixed="right">
            <template slot-scope="scope">
              <el-button class="operation" type="text" size="small" @click="see(scope.row.id)">查看</el-button>
              <el-button class="operation" type="text" size="small" @click="see(scope.row.id)">跟进</el-button>
              <el-button class="operation" type="text" size="small" @click="funAuditBatch('single', scope.row.id)">审核</el-button>
              <el-button class="operation" type="text" size="small" @click="operationResumeDel('single', scope.row.id)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="bortton-page">
        <el-col :span="10">
          <el-button size="small" type="primary" @click="choose">全选/反选</el-button>
          <el-button size="small" type="primary" @click="funRefresh()">刷新简历</el-button>
          <el-button size="small" type="primary" @click="funAuditBatch('all')">审核简历</el-button>
          <el-button size="small" type="primary" @click="funLevelBatch('all')">简历等级</el-button>
          <!--          <el-button size="small" type="primary" @click="wholeDel('all')">-->
          <!--            删除-->
          <!--          </el-button>-->
          <el-button size="small" type="primary" @click="openDialog">导入简历</el-button>
        </el-col>
        <el-col :span="14" style="text-align: right">
          <el-pagination
            background
            destroy-on-close
            :current-page="currentPage"
            :page-sizes="[10, 15, 20, 30, 40]"
            :page-size="pagesize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </el-col>
      </div>
    </el-card>
    <el-drawer v-if="drawer" size="75%" :show-close="false" :with-header="false" :wrapper-closable="false" :visible.sync="drawer" :before-close="handleClose">
      <urmShow :row-id="rowId" />
      <!-- 关闭按钮 -->
      <div class="close" @click="handleClose"><i class="el-icon-close" /></div>
    </el-drawer>
    <div class="call">
      <el-dialog :visible.sync="callDialogVisible" width="30%" :before-close="callHandleClose">
        <div class="box-content">
          <div class="content">
            <div class="title1">您还尚未开通云呼叫服务</div>
            <div class="title2">扫码添加客服</div>
            <div class="title3">快速开通呼叫业务</div>
          </div>
          <div class="code"><img src="../../../../assets/images/outbound/renew.png" alt=""></div>
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
          <div id="animation" class="img"><!--            <img src="../../../../assets/images/outbound/meet.png" alt="">--></div>
          <div class="hu">正在呼叫客户</div>
          <div class="telephone">{{ dialPhone }}</div>
          <div class="company">{{ dialName }}</div>
          <div class="tips">请您留意手机来电接听</div>
        </div>
      </el-dialog>
    </div>
    <el-dialog title="将所选简历设置为" :visible.sync="dialogFormVisible" width="27%">
      <el-form class="common-form" label-width="80px">
        <el-form-item label="审核状态">
          <el-radio-group v-model="setAuditVal">
            <el-radio v-for="item in auditData" :key="item.id" :label="item.id">{{ item.name }}</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="setAuditVal == 2" label="原因"><el-input v-model="setAuditReason" type="textarea" rows="3" /></el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_audit('all')">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog title="将所选简历设置为" :visible.sync="levelVisible" width="25%">
      <el-form class="common-form" label-width="80px">
        <el-form-item label="简历等级">
          <el-radio-group v-model="setLevelVal">
            <el-radio :label="0">普通</el-radio>
            <el-radio :label="1">优质</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="levelVisible = false">取 消</el-button>
        <el-button type="primary" @click="fun_set_level">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog v-if="showUpload" title="导入简历" :visible.sync="showUpload" :close-on-click-modal="false" width="30%">
      <el-form ref="form" label-width="120px" size="small">
        <el-form-item label="上传文件">
          <input
            style="width: 200px"
            class="input-file"
            type="file"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            @change="exportData"
          >
          <span class="smalltip">
            <i class="el-icon-info" />
            <el-button type="text" @click="downTpl">[模板下载]</el-button>
          </span>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="doUpload">确定</el-button>
          <el-button @click="showUpload = false">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog :visible.sync="showIframe"><iframe width="100%" height="600" frameborder="0" scrolling="auto" :src="iframeUrl" /></el-dialog>
  </div>
</template>

<script>
import urmShow from '@/views/user/resume/urm/components/urmShow'
import { resumeAudit, resumeLevel, resumeRefresh, resumeImport } from '@/api/resume'
import { urmList, resumeDel, setContactStatus } from '@/api/resume_urm'
import { parseTime } from '@/utils'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
import XLSX from 'xlsx'
import { classify } from '@/api/company_crm'
import { customLndex, customLndexAdd, outboundCall } from '@/api/outbound'
import { documentConfig, documentCheck } from '@/api/configuration'
import md5 from 'js-md5'

export default {
  name: 'List',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  components: {
    urmShow
  },
  data() {
    return {
      keyword: '',
      key_type: '1',
      menu_icon: 'menu',
      fieldData: [
        { name: 'ID', field: 'id', select: true, is_locking: false, is_lock_display: false, width: 80, is_sortable: false },
        { name: '照片', field: 'photo_img_src', select: true, is_locking: false, is_lock_display: false, width: 80, is_sortable: false },
        { name: '姓名', field: 'fullname', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: false },
        { name: '微信绑定', field: 'is_bind', select: true, is_locking: false, is_lock_display: false, width: 100, is_sortable: false },
        { name: '学历', field: 'education_cn', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        { name: '经验', field: 'experience_cn', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        { name: '简历完整度', field: 'complete_percent', select: true, is_locking: false, is_lock_display: false, width: 170, is_sortable: false },
        { name: '审核状态', field: 'audit', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: false },
        { name: '简历联系方式', field: 'contact_mobile', select: true, is_locking: false, is_lock_display: false, width: 130, is_sortable: false },
        { name: '会员联系方式', field: 'member_mobile', select: true, is_locking: false, is_lock_display: false, width: 130, is_sortable: false },
        { name: '等级', field: 'high_quality', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        { name: '投递数', field: 'delivery_num', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        { name: '被下载', field: 'downloaded', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        { name: '被查看', field: 'viewed', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        { name: '被面邀', field: 'invitation', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        // { 'name': '简历作品', 'field': 'works', 'select': true, 'is_locking': false, 'is_lock_display': false, 'width': 120, 'is_sortable': false },
        { name: '刷新时间', field: 'refreshtime', select: true, is_locking: false, is_lock_display: false, width: 160, is_sortable: 'custom' },
        { name: '登录时间', field: 'last_login_time', select: true, is_locking: false, is_lock_display: false, width: 160, is_sortable: 'custom' },
        { name: '注册时间', field: 'reg_time', select: true, is_locking: false, is_lock_display: false, width: 160, is_sortable: 'custom' },
        { name: '附件', field: 'enclosure_resume', select: true, is_locking: false, is_lock_display: false, width: 90, is_sortable: false },
        { name: '联系状态', field: 'contact_status', select: true, is_locking: false, is_lock_display: false, width: 120, is_sortable: false },
        { name: '跟进次数', field: 'follow_num', select: true, is_locking: false, is_lock_display: false, width: 110, is_sortable: false },
        { name: '最后跟进', field: 'final_follow', select: true, is_locking: false, is_lock_display: false, width: 160, is_sortable: 'custom' },
        { name: '简历备注', field: 'remark', select: true, is_locking: false, is_lock_display: false, width: 180, is_sortable: false }
      ],
      tableData: [],
      loading: false,
      currentPage: 1,
      pagesize: 10,
      total: 0,
      drawer: false,
      visiblepop: false,
      tableUidarr: [],
      dialogFormVisible: false,
      setAuditVal: '',
      options_audit: [],
      tableIdarr: [],
      setAuditReason: '',
      levelVisible: false,
      setLevelVal: '',
      showUpload: false,
      levelSubmitLoading: false,
      auditSubmitLoading: false,
      rowId: 0,
      educationData: [],
      educationScreen: '',
      highQualityData: [{ id: 0, name: '普通简历' }, { id: 1, name: '优质简历' }],
      highQualityScreen: '',
      experienceData: [
        { id: 1, name: '应届生' },
        { id: 2, name: '1年以下' },
        { id: 3, name: '1-2年' },
        { id: 4, name: '2-3年' },
        { id: 5, name: '3-5年' },
        { id: 6, name: '5-10年' },
        { id: 7, name: '10年以上' }
      ],
      experienceScreen: '',
      auditData: [],
      auditScreen: '',
      contactStatusData: [{ id: 1, name: '已电话联系' }, { id: 2, name: '已微信联系' }, { id: 3, name: '未联系' }],
      contactStatusScreen: '',
      deliveryNumData: [{ id: 1, name: '今日投递' }, { id: 3, name: '7日内投递' }, { id: 4, name: '3日内投递' }, { id: 2, name: '30日内投递' }, { id: 5, name: '从未投递过' }],
      deliveryNumScreen: '',
      downloadedData: [{ id: 1, name: '今日下载' }, { id: 4, name: '3日内下载' }, { id: 3, name: '7日内下载' }, { id: 2, name: '30日内下载' }, { id: 5, name: '从未下载过' }],
      downloadedScreen: '',
      viewedData: [{ id: 1, name: '今日被查看' }, { id: 4, name: '3日内被查看' }, { id: 3, name: '7日内被查看' }, { id: 2, name: '30日内被查看' }, { id: 5, name: '从未被查看过' }],
      viewedScreen: '',
      invitationData: [
        { id: 1, name: '今日被面邀' },
        { id: 4, name: '3日内被面邀' },
        { id: 3, name: '7日内被面邀' },
        { id: 2, name: '30日内被面邀' },
        { id: 5, name: '从未被面邀过' }
      ],
      invitationScreen: '',
      worksData: [
        { id: 1, name: '今日发布作品' },
        { id: 4, name: '3日内发布作品' },
        { id: 3, name: '7日内发布作品' },
        { id: 2, name: '30日内发布作品' },
        { id: 5, name: '从未发布作品' }
      ],
      worksScreen: '',
      educationFilter: '',
      experienceFilter: '',
      highQualityFilter: '',
      deliveryNumFilter: '',
      downloadedFilter: '',
      viewedFilter: '',
      invitationFilter: '',
      auditFilter: '',
      worksFilter: '',
      contactStatusFilter: '',
      citycategoryFilter: '',
      jobcategoryFilter: '',
      platformFilter: '',
      sort_type: '',
      sort: '',
      meetDialogVisible: false,
      callDialogVisible: false,
      citycategory: [],
      jobcategory: [],
      platform: [
        { id: 'app', name: 'APP' },
        { id: 'mobile', name: '手机浏览器' },
        { id: 'wechat', name: '微信浏览器' },
        { id: 'miniprogram', name: '微信小程序' },
        { id: 'tiktok', name: '抖音小程序' },
        { id: 'web', name: '电脑浏览器' },
        { id: 'system', name: '系统' }
      ],
      citycategoryScreen: '',
      jobcategoryScreen: '',
      platformScreen: '',
      dialPhone: '',
      dialName: '',
      excelData: [],
      enclosureResumeFilter: '',
      enclosureResumeScreen: '',
      enclosureResumeData: [{ id: '1', name: '有' }, { id: '2', name: '无' }, { id: '0', name: '全部' }],
      showIframe: false,
      iframeUrl: '',
      configInfo: {},
      is_check: 0,
      resumeLink: '',
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
    clueTime() {
      var clue_type = localStorage.getItem('clue_type')
      var clue_resume_id = localStorage.getItem('clue_resume_id')
      if (clue_type != '' && clue_type == 'follow') {
        this.drawer = true
        this.rowId = clue_resume_id
        localStorage.setItem('clue_type', '')
        localStorage.setItem('clue_resume_id', '')
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
      var docHeight = document.documentElement.clientHeight
      this.tabelHeight = docHeight - 316 - 60
    })
  },
  created() {
    this.urmList()
    this.classify()
    this.getFieldData()
    var clue_type = localStorage.getItem('clue_type')
    var clue_resume_id = localStorage.getItem('clue_resume_id')
    if (clue_type != '' && clue_type == 'follow') {
      this.drawer = true
      this.rowId = clue_resume_id
      localStorage.setItem('clue_type', '')
      localStorage.setItem('clue_resume_id', '')
    }
  },
  methods: {
    setContactStatus(type, id) {
      setContactStatus({ type: type, id: id })
        .then(res => {
          if (res.code == 200) {
            this.$message.success(res.message)
            this.urmList()
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(() => {})
    },
    operationResumeDel(type, id) {
      var data = ''
      if (type == 'all') {
        data = { resume_id: id }
      } else {
        data = { resume_id: id }
      }
      this.$confirm('确定将该简历放置回收站？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          resumeDel(data)
            .then(res => {
              if (res.code == 200) {
                this.$message.success(res.message)
                this.urmList()
              } else {
                this.$message.error(res.message)
              }
            })
            .catch(() => {})
        })
        .catch(() => {})
    },
    screenList() {
      this.currentPage = 1
      this.confirm()
    },
    callHandleClose() {
      this.callDialogVisible = false
    },
    meetHandleClose() {
      this.meetDialogVisible = false
    },
    clickDial(phone, name) {
      this.$confirm('是否对【' + name + '】' + phone + ' 发起呼叫？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          outboundCall({ mobile: phone })
            .then(res => {
              if (res.code == 200) {
                this.meetDialogVisible = true
                this.dialPhone = phone
                this.dialName = name
              }
              if (res.code == 4040) {
                this.callDialogVisible = true
              }
            })
            .catch(res => {})
        })
        .catch(() => {})
    },
    getFieldData() {
      customLndex({
        name: 'urm'
      })
        .then(res => {
          if (res.data != '') {
            this.fieldData = JSON.parse(res.data)
          }
        })
        .catch(() => {})
    },
    sortTable({ column, order }) {
      if (order == 'ascending') {
        this.sortType = 'asc'
      } else if (order == 'descending') {
        this.sortType = 'desc'
      } else {
        this.sortType = ''
      }
      this.sort_type = column.property
      this.sort = this.sortType
      this.urmList()
    },
    classify() {
      classify({ type: 'education,resumeAudit,citycategory,jobcategory' })
        .then(res => {
          this.educationData = res.data.education
          this.auditData = res.data.resumeAudit
          this.citycategory = res.data.citycategory
          this.jobcategory = res.data.jobcategory
        })
        .catch(() => {})
    },
    urmList() {
      this.getDocumentCheck()
      this.loading = true
      if (localStorage.getItem('resume_audit') && localStorage.getItem('resume_audit') == '1') {
        this.auditScreen = '0'
        this.auditFilter = { name: '待审核', field: 'audit' }
        localStorage.setItem('resume_audit', '')
      }
      urmList({
        page: this.currentPage,
        pagesize: this.pagesize,
        keyword: this.keyword,
        key_type: this.key_type,
        education: this.educationScreen,
        high_quality: this.highQualityScreen,
        experience: this.experienceScreen,
        audit: this.auditScreen,
        contact_status: this.contactStatusScreen,
        delivery_num: this.deliveryNumScreen,
        downloaded: this.downloadedScreen,
        viewed: this.viewedScreen,
        invitation: this.invitationScreen,
        works: this.worksScreen,
        sort_type: this.sort_type,
        sort: this.sort,
        citycategory: this.citycategoryScreen,
        jobcategory: this.jobcategoryScreen,
        platform: this.platformScreen,
        enclosure_resume: this.enclosureResumeScreen
      })
        .then(res => {
          this.tableData = res.data.items
          this.currentPage = res.data.current_page
          this.total = res.data.total
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    handleSizeChange(val) {
      this.pagesize = val
      this.urmList()
    },
    handleCurrentChange(val) {
      this.currentPage = val
      this.urmList()
    },
    goto(target) {
      this.$router.push(target)
    },
    // 搜索
    funSearchKeyword() {
      this.currentPage = 1
      this.urmList()
    },
    reset(field) {
      this.tabelHeight = 'calc(100vh - 372px)'
      if (field == 'all') {
        this.educationScreen = ''
        this.highQualityScreen = ''
        this.experienceScreen = ''
        this.auditScreen = ''
        this.contactStatusScreen = ''
        this.deliveryNumScreen = ''
        this.downloadedScreen = ''
        this.viewedScreen = ''
        this.invitationScreen = ''
        this.worksScreen = ''
        this.enclosureResumeScreen = ''
      }
      if (field == 'education_cn') {
        this.educationScreen = ''
      }
      if (field == 'high_quality') {
        this.highQualityScreen = ''
      }
      if (field == 'experience_cn') {
        this.experienceScreen = ''
      }
      if (field == 'audit') {
        this.auditScreen = ''
      }
      if (field == 'contact_status') {
        this.contactStatusScreen = ''
      }
      if (field == 'delivery_num') {
        this.deliveryNumScreen = ''
      }
      if (field == 'downloaded') {
        this.downloadedScreen = ''
      }
      if (field == 'viewed') {
        this.viewedScreen = ''
      }
      if (field == 'invitation') {
        this.invitationScreen = ''
      }
      if (field == 'works') {
        this.worksScreen = ''
      }
      if (field == 'enclosure_resume') {
        this.enclosureResumeScreen = ''
      }
      this.confirm()
    },
    locks(field) {
      for (var i = 0; i <= this.fieldData.length - 1; i++) {
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_lock_display == true) {
            this.fieldData[i].is_lock_display = false
          } else {
            this.fieldData[i].is_lock_display = true
          }
        }
      }
    },
    select(field) {
      for (var i = 0; i <= this.fieldData.length - 1; i++) {
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].select == true) {
            this.fieldData[i].select = false
          } else {
            this.fieldData[i].select = true
          }
        }
      }
    },
    see(value) {
      this.drawer = true
      this.rowId = value
    },
    handleClose() {
      this.urmList()
      this.drawer = false
    },
    setFieldClose() {
      this.menu_icon = 'menu'
      this.visiblepop = false
    },
    menu() {
      if (this.menu_icon == 'menu') {
        this.visiblepop = true
        this.menu_icon = 'menu_select'
      } else {
        this.visiblepop = false
        this.menu_icon = 'menu'
      }
    },
    confirm(type) {
      if (type != 'reset'){
        this.tabelHeight = 'calc(100vh - 446px)'
        this.$nextTick(() => {
          this.$refs.multipleTable.doLayout()
        })
      }
      this.currentPage = 1
      if (this.educationScreen != '') {
        for (var i = 0; i <= this.educationData.length - 1; i++) {
          if (this.educationScreen == this.educationData[i].id) {
            this.educationFilter = {
              field: 'education_cn',
              name: this.educationData[i].name
            }
          }
        }
      } else {
        this.educationFilter = ''
      }
      if (this.experienceScreen != '') {
        for (var i = 0; i <= this.experienceData.length - 1; i++) {
          if (this.experienceScreen === this.experienceData[i].id) {
            this.experienceFilter = {
              field: 'experience_cn',
              name: this.experienceData[i].name
            }
          }
        }
      } else {
        this.experienceFilter = ''
      }
      if (this.highQualityScreen !== '') {
        for (var i = 0; i <= this.highQualityData.length - 1; i++) {
          if (this.highQualityScreen === this.highQualityData[i].id) {
            this.highQualityFilter = {
              field: 'high_quality',
              name: this.highQualityData[i].name
            }
          }
        }
      } else {
        this.highQualityFilter = ''
      }
      if (this.deliveryNumScreen != '') {
        for (var i = 0; i <= this.deliveryNumData.length - 1; i++) {
          if (this.deliveryNumScreen == this.deliveryNumData[i].id) {
            this.deliveryNumFilter = {
              field: 'delivery_num',
              name: this.deliveryNumData[i].name
            }
          }
        }
      } else {
        this.deliveryNumFilter = ''
      }
      if (this.downloadedScreen != '') {
        for (var i = 0; i <= this.downloadedData.length - 1; i++) {
          if (this.downloadedScreen == this.downloadedData[i].id) {
            this.downloadedFilter = {
              field: 'downloaded',
              name: this.downloadedData[i].name
            }
          }
        }
      } else {
        this.downloadedFilter = ''
      }
      if (this.viewedScreen != '') {
        for (var i = 0; i <= this.viewedData.length - 1; i++) {
          if (this.viewedScreen == this.viewedData[i].id) {
            this.viewedFilter = {
              field: 'viewed',
              name: this.viewedData[i].name
            }
          }
        }
      } else {
        this.viewedFilter = ''
      }
      if (this.invitationScreen != '') {
        for (var i = 0; i <= this.invitationData.length - 1; i++) {
          if (this.invitationScreen == this.invitationData[i].id) {
            this.invitationFilter = {
              field: 'invitation',
              name: this.invitationData[i].name
            }
          }
        }
      } else {
        this.invitationFilter = ''
      }
      if (this.auditScreen !== '') {
        for (var i = 0; i <= this.auditData.length - 1; i++) {
          if (this.auditScreen === this.auditData[i].id) {
            this.auditFilter = {
              field: 'audit',
              name: this.auditData[i].name
            }
          }
        }
      } else {
        this.auditFilter = ''
      }
      if (this.worksScreen != '') {
        for (var i = 0; i <= this.worksData.length - 1; i++) {
          if (this.worksScreen == this.worksData[i].id) {
            this.worksFilter = {
              field: 'works',
              name: this.worksData[i].name
            }
          }
        }
      } else {
        this.worksFilter = ''
      }
      if (this.contactStatusScreen != '') {
        for (var i = 0; i <= this.contactStatusData.length - 1; i++) {
          if (this.contactStatusScreen == this.contactStatusData[i].id) {
            this.contactStatusFilter = {
              field: 'contact_status',
              name: this.contactStatusData[i].name
            }
          }
        }
      } else {
        this.contactStatusFilter = ''
      }
      if (this.enclosureResumeScreen != '') {
        for (var i = 0; i <= this.enclosureResumeData.length - 1; i++) {
          if (this.enclosureResumeScreen == this.enclosureResumeData[i].id) {
            this.enclosureResumeFilter = {
              field: 'enclosure_resume',
              name: this.enclosureResumeData[i].name
            }
          }
        }
      } else {
        this.enclosureResumeFilter = ''
      }
      this.urmList()
    },
    setFieldConfirm() {
      customLndexAdd({
        name: 'urm',
        value: JSON.stringify(this.fieldData)
      })
        .then(res => {
          if (res.code == 200) {
            this.$message.success(res.message)
          } else {
            this.$message.error(res.message)
          }
          this.visiblepop = false
          this.menu_icon = 'menu'
        })
        .catch(() => {})
    },
    locking(field) {
      for (var i = 0; i <= this.fieldData.length - 1; i++) {
        if (this.fieldData[i].field == field) {
          if (this.fieldData[i].is_locking == true) {
            this.fieldData[i].is_locking = false
            this.fieldData[i].is_lock_display = false
            this.fieldData[i].icon = 'el-icon-lock'
          } else {
            this.fieldData[i].icon = 'el-icon-unlock'
            this.fieldData[i].is_lock_display = false
            this.fieldData[i].is_locking = true
          }
        }
      }
      this.setFieldConfirm()
    },
    handleSelectionChange(val) {
      var tableUidarr = []
      var tableIdarr = []
      val.forEach(row => {
        tableUidarr.push(row.uid)
        tableIdarr.push(row.id)
      })
      this.tableUidarr = tableUidarr
      this.tableIdarr = tableIdarr
    },
    choose() {
      this.tableData.forEach(row => {
        this.$refs.multipleTable.toggleRowSelection(row)
      })
    },
    funRefresh() {
      var that = this
      if (that.tableUidarr.length == 0) {
        that.$message.error('请选择要刷新的简历')
        return false
      }
      const param = {
        uid: that.tableUidarr
      }
      resumeRefresh(param).then(response => {
        that.$message.success(response.message)
      })
    },
    funAuditBatch(type, id) {
      if (type == 'all') {
        if (this.tableIdarr.length == 0) {
          this.$message.error('请选择要审核的简历')
          return false
        }
        this.auditIdarr = this.tableIdarr
      } else {
        this.auditIdarr = id
      }
      this.setAuditVal = 0
      this.setAuditReason = ''
      this.dialogFormVisible = true
    },
    fun_set_audit() {
      if (this.auditSubmitLoading == true) {
        return false
      }
      this.auditSubmitLoading = true
      const params = {
        id: this.auditIdarr,
        audit: this.setAuditVal,
        reason: this.setAuditReason
      }
      resumeAudit(params, 'post')
        .then(response => {
          if (response.code == 200) {
            this.$message.success(response.message)
            this.auditSubmitLoading = false
            this.urmList()
            this.closeDialog()
            this.dialogFormVisible = false
            return true
          } else {
            this.auditSubmitLoading = false
            this.dialogFormVisible = false
            this.$message.error(response.message)
            return false
          }
        })
        .catch(() => {
          this.auditSubmitLoading = false
          this.dialogFormVisible = false
        })
    },
    funLevelBatch() {
      if (this.tableIdarr.length == 0) {
        this.$message.error('请选择要设置的简历')
        return false
      }
      this.setLevelVal = 0
      this.levelIdarr = this.tableIdarr
      this.levelVisible = true
    },
    fun_set_level() {
      if (this.levelSubmitLoading == true) {
        return false
      }
      this.levelSubmitLoading = true
      const params = {
        id: this.levelIdarr,
        level: this.setLevelVal
      }
      resumeLevel(params, 'post')
        .then(response => {
          if (response.code == 200) {
            this.$message.success(response.message)
            this.levelSubmitLoading = false
            this.urmList()
            this.levelVisible = false
            return true
          } else {
            this.levelSubmitLoading = false
            this.levelVisible = false
            this.$message.error(response.message)
            return false
          }
        })
        .catch(() => {
          this.levelSubmitLoading = false
          this.levelVisible = false
        })
    },
    openDialog() {
      this.showUpload = true
    },
    downTpl() {
      location.href = window.global.RequestBaseUrl + apiArr.downloadImportResumeTpl + (window.global.RequestBaseUrl.indexOf('?') == -1 ? '?' : '&') + 'admintoken=' + getToken()
    },
    exportData(event) {
      if (!event.currentTarget.files.length) {
        return
      }
      const that = this
      // 拿取文件对象
      var f = event.currentTarget.files[0]
      // 用FileReader来读取
      var reader = new FileReader()
      // 重写FileReader上的readAsBinaryString方法
      FileReader.prototype.readAsBinaryString = function(f) {
        var binary = ''
        var wb // 读取完成的数据
        var outdata // 你需要的数据
        var reader = new FileReader()
        reader.onload = function() {
          // 读取成Uint8Array，再转换为Unicode编码（Unicode占两个字节）
          var bytes = new Uint8Array(reader.result)
          var length = bytes.byteLength
          for (let i = 0; i < length; i++) {
            binary += String.fromCharCode(bytes[i])
          }
          // 接下来就是xlsx了，具体可看api
          wb = XLSX.read(binary, {
            type: 'binary',
            cellDates: true
          })
          const sheet2JSONOpts = {
            header: 1,
            /** Default value for null/undefined values */
            defval: '' // 给defval赋值为空的字符串
          }
          const outdataAll = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], sheet2JSONOpts)
          outdata = outdataAll.filter(item => item[0] != '')
          outdata.splice(0, 1)
          that.excelData = []
          for (let i = 0; i < outdata.length; i++) {
            const outputs = {
              basic: {},
              intention_list: {},
              education_list: {},
              work_list: {},
              language_list: {},
              certificate_list: {}
            } // 清空接收数据
            // console.log(ws[i])
            const sheetBasic = {
              fullname: outdata[i][0],
              sex: outdata[i][1] === '男' ? 1 : 2,
              birthday: that.dateFormat('YYYY-mm-dd', outdata[i][2]),
              residence: outdata[i][17],
              height: outdata[i][3],
              marriage: outdata[i][5],
              education: outdata[i][7],
              enter_job_time: outdata[i][6],
              householdaddress: outdata[i][4],
              specialty: outdata[i][18],
              addtime: outdata[i][19],
              platform: outdata[i][20],
              contact: {
                mobile: outdata[i][15],
                email: outdata[i][16]
              }
            }
            let sheetIntentionList = []
            if (outdata[i][8]) {
              sheetIntentionList = sheetIntentionList.concat(that.handleCellData(outdata[i][8], 'intention'))
            }
            if (outdata[i][9]) {
              sheetIntentionList = sheetIntentionList.concat(that.handleCellData(outdata[i][9], 'intention'))
            }
            if (outdata[i][10]) {
              sheetIntentionList = sheetIntentionList.concat(that.handleCellData(outdata[i][10], 'intention'))
            }
            const sheetEducationList = that.handleCellData(outdata[i][11], 'education')
            const sheetWorkList = that.handleCellData(outdata[i][12], 'work')
            const sheetLanguageList = that.handleCellData(outdata[i][13], 'language')
            const sheetCertificateList = that.handleCellData(outdata[i][14], 'certificate')
            outputs.basic = sheetBasic
            outputs.intention_list = sheetIntentionList
            outputs.education_list = sheetEducationList
            outputs.work_list = sheetWorkList
            outputs.language_list = sheetLanguageList
            outputs.certificate_list = sheetCertificateList
            that.excelData.push(outputs)
          }
        }
        reader.readAsArrayBuffer(f)
      }
      reader.readAsBinaryString(f)
    },
    doUpload() {
      const that = this
      if (that.excelData.length <= 0) {
        that.$message.error('没有读取到数据，请重新核对、上传')
        return false
      }
      const pagesize = 20
      const totalPage = Math.ceil(that.excelData.length / pagesize)
      that.importLoading = true
      that.showUpload = false
      that.loopImport(1, pagesize, totalPage)
    },
    handleDate(date) {
      const dateArray = date.split('-')
      dateArray[0] = dateArray[0].split('/')[0] + '-' + dateArray[0].split('/')[1]
      dateArray[1] = dateArray[1].split('/')[0] + '-' + dateArray[1].split('/')[1]
      return dateArray
    },
    loopImport(page, pagesize, totalPage) {
      const that = this
      const start = (page - 1) * pagesize
      const end = start + pagesize
      const newarr = that.excelData.slice(start, end)
      that.importText = '正在导入第' + start + '-' + end + '条，请稍候'
      resumeImport(newarr).then(response => {
        if (page >= totalPage) {
          that.importLoading = false
          that.$message({ type: 'success', message: '导入成功' })
          that.fetchData(true)
        } else {
          page++
          that.loopImport(page, pagesize, totalPage)
        }
      })
    },
    dateFormat(fmt, date) {
      let ret
      const opt = {
        'Y+': date.getFullYear().toString(), // 年
        'm+': (date.getMonth() + 1).toString(), // 月
        'd+': date.getDate().toString(), // 日
        'H+': date.getHours().toString(), // 时
        'M+': date.getMinutes().toString(), // 分
        'S+': date.getSeconds().toString() // 秒
        // 有其他格式化字符需求可以继续添加，必须转化成字符串
      }
      for (const k in opt) {
        ret = new RegExp('(' + k + ')').exec(fmt)
        if (ret) {
          fmt = fmt.replace(ret[1], ret[1].length == 1 ? opt[k] : opt[k].padStart(ret[1].length, '0'))
        }
      }
      return fmt
    },
    // 处理数据
    handleCellData(data, type) {
      const that = this
      const returnArray = []
      if (type === 'intention') {
        const valArray = data.split('|')
        returnArray.push({
          nature: valArray[0],
          category: valArray[1],
          district: valArray[2],
          minwage: valArray[3].split('-')[0],
          maxwage: valArray[3].split('-')[1],
          trade: valArray[4]
        })
      } else {
        if (data != '') {
          const listArray = data.split('【#】')
          listArray.forEach(function(val, index, arr) {
            const valArray = val.split('|')
            if (type === 'education') {
              returnArray.push({
                starttime: that.handleDate(valArray[0])[0],
                endtime: that.handleDate(valArray[0])[1],
                school: valArray[1],
                major: valArray[2],
                education: valArray[3]
              })
            }
            if (type === 'work') {
              returnArray.push({
                starttime: that.handleDate(valArray[0])[0],
                endtime: that.handleDate(valArray[0])[1],
                companyname: valArray[1],
                jobname: valArray[2],
                duty: valArray[3]
              })
            }
            if (type === 'language') {
              returnArray.push({
                language: valArray[0],
                level: valArray[1]
              })
            }
            if (type === 'certificate') {
              returnArray.push({
                name: valArray[0],
                obtaintime: valArray[1].split('/')[0] + '-' + valArray[1].split('/')[1]
              })
            }
          })
        }
      }
      return returnArray
    },
    getDocumentConfig() {
      const param = {}
      documentConfig(param, 'get')
        .then(response => {
          this.configInfo = response.data
          const url = this.resumeLink // 要预览文件的访问地址
          const timeStamp = parseInt(Date.now() / 1000)
          const appKey = this.configInfo.appKey
          const appSecret = this.configInfo.appSecret
          const Base64URI = this.$Base64.encode(url)
          const serverName = document.domain
          const request = Base64URI + appKey + timeStamp + appSecret + serverName
          const sign = md5(request)
          this.iframeUrl =
            'https://document.zhitoo.cn/zhitooDocument?url=' +
            Base64URI +
            '&appKey=' +
            appKey +
            '&appSecret=' +
            appSecret +
            '&timeStamp=' +
            timeStamp +
            '&serverName=' +
            serverName +
            '&sign=' +
            sign
          this.showIframe = true
        })
        .catch(() => {})
    },
    getDocumentCheck() {
      documentCheck().then(res => {
        this.is_check = res.data.is_check
      })
    },
    // 查看简历附件
    seeResumeAttachment(row) {
      this.resumeLink = row.enclosure_resume
      if (this.is_check == 1) {
        this.openIframe()
      }
    },
    openIframe() {
      this.getDocumentConfig()
    }
  }
}
</script>

<style scoped lang="scss">
.complete_percent {
  ::v-deep .el-progress__text {
    color: unset;
  }
}
.contact_status {
  display: inline-block;
  margin-right: 10px;
  width: 16px;
  height: 16px;
}
.el-progress-bar__inner {
  position: absolute;
  left: 10px;
  top: 34px;
  height: 16px;
  background-color: #409eff;
  text-align: right;
  border-radius: 100px;
  line-height: 1;
  white-space: nowrap;
  -webkit-transition: width 0.6s ease;
  transition: width 0.6s ease;
  .el-progress-bar__innerText {
    color: #fff;
    font-size: 12px;
    margin: 0 5px;
  }
}
.list-screen {
  margin-left: 10px;
}
.screen_s {
  max-height: 220px;
  overflow: auto;
}
::v-deep .caret-wrapper {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}
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
.meet {
  .box-content {
    .img {
      width: 150px;
      height: 150px;
      text-align: center;
      margin-top: 40px;
      margin: 0 auto;
      background: url('../../../../assets/images/outbound/meet.png') no-repeat center;
    }
    .hu {
      text-align: center;
      margin-top: 30px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #949494;
    }
    .telephone {
      text-align: center;
      font-size: 30px;
      font-family: Microsoft YaHei;
      font-weight: bold;
      color: #333333;
      margin-top: 26px;
    }
    .company {
      text-align: center;
      margin-top: 25px;
      font-size: 15px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #666666;
    }
    .tips {
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
.meet {
  ::v-deep .el-dialog__body {
    padding: 0px 0px 0px 0px;
    border-radius: 5px;
  }
  ::v-deep .el-dialog {
    border-radius: 16px;
  }
}
.call {
  .box-content {
    padding: 20px 30px 35px 30px;
    .advantage {
      .advantage-box {
        display: inline-block;
        width: 170px;
        .title {
          display: inline-block;
          float: left;
        }
        .img {
          display: inline-block;
          float: left;
          margin-right: 8px;
        }
      }
      margin-top: 25px;
    }
    .slogan {
      font-size: 14px;
      font-family: Microsoft YaHei;
      font-weight: 400;
      color: #ff550a;
      margin-top: 24px;
    }
    .code {
      display: inline-block;
      margin-right: 30px;
      float: right;
      width: 117px;
      height: 117px;
      background: #ffffff;
      box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.14);
      border-radius: 8px;
      padding: 10px 10px;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .content {
      display: inline-block;
      float: left;
      .title1 {
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #ff550a;
      }
      .title2 {
        margin-top: 18px;
        font-size: 18px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #555555;
      }
      .title3 {
        width: 133px;
        height: 24px;
        background: #ff6929;
        border-radius: 3px;
        text-align: center;
        line-height: 24px;
        font-size: 14px;
        font-family: Microsoft YaHei;
        font-weight: 400;
        color: #ffffff;
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
  width: 94%;
}

::v-deep .el-popover {
  position: fixed;
}
.list-search {
  z-index: 1;
  position: relative;
}

.filterOperation {
  float: right;
  margin-right: 5px;
  color: #fff;
  background-color: #409eff;
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
  background: url('../../../../assets/images/company/crm/unlock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/unlock1.png') no-repeat center center;
  }
}

.unlock {
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/unlock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/unlock1.png') no-repeat center center;
  }
}

.lock_display {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/lock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/lock1.png') no-repeat center center;
  }
}

.lock {
  display: none;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/lock.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/lock1.png') no-repeat center center;
  }
}

.drop_down {
  display: inline-block;
  float: right;
  width: 20px;
  height: 20px;
  cursor: pointer;
  background: url('../../../../assets/images/company/crm/drop-down.png') no-repeat center center;

  &:hover {
    background: url('../../../../assets/images/company/crm/drop-down1.png') no-repeat center center;
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
  margin: 15px 0px;
  font-size: 14px;
}

.setField {
  top: 20px;
  right: 0;
  position: absolute;
  z-index: 6;
  margin-left: 10px;
}

.screenStyle {
  margin: 10px 0px;
}

.bortton-page {
  margin-top: 20px;
  padding-bottom: 40px;
}

.input-with-select {
  float: left;
  margin-bottom: 20px;
}
.filterCriteria {
  border: 1px dashed #eeeeee;
  font-size: 13px;
  color: #999999;
  margin-top: 15px;
  margin-bottom: 16px;
  padding: 15px 16px 15px 16px;
  .selected {
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
    .name {
      display: inline-block;
    }
    .closes {
      display: inline-block;
      margin-left: 10px;
    }
  }
}
.setField_s {
  top: 0px;
  right: 0;
  position: absolute;
  z-index: 1000;
  margin-left: 10px;
}
#animation {
  animation: pulse 1s 0.2s ease both infinite;
}
@-webkit-keyframes pulse {
  0% {
    -webkit-transform: scale(1);
  }
  50% {
    -webkit-transform: scale(1.1);
  }
  100% {
    -webkit-transform: scale(1);
  }
}
@-moz-keyframes pulse {
  0% {
    -moz-transform: scale(1);
  }
  50% {
    -moz-transform: scale(1.1);
  }
  100% {
    -moz-transform: scale(1);
  }
}
</style>
