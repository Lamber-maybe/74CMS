<template>
  <div class="my_resume">
    <div class="saveResumeDetail">
      <div class="btn resetUpload" @click="resetResumeDetail">
        <span class="text1">解析结果不满意</span>
        <span class="text2">重新上传</span>
      </div>
      <div class="btn sureUpload" @click="saveResumeDetail">创建在线简历</div>
    </div>
    <div class="r_left" v-if="infoBoolean">
      <!--基本信息-->
      <div class="b1" ref="basic" id="basicGroup">
        <div class="l_title">
          <div class="req">*</div>
          <div class="t_name">基本信息</div>
          <div
            class="tobeimproved"
            v-if="basic.fullname == '' || basic.sex == '' || basic.age == '' || basic.education == '' || basic.experience == '' || contact.mobile == ''"
          ></div>
          <div class="clear"></div>
        </div>
        <!-- <div class="a_scan" v-if="!editBasic" @mouseover="startScan(1)">
          微信扫码上传
          <div class="codeImg">
            <img :src="scanQrcode" alt="" />
            <p>
              微信扫描二维码
              <br />
              快速上传手机相册图片
            </p>
            <em>&#9670;</em>
            <span>&#9670;</span>
          </div>
        </div> -->
        <div class="con" v-if="!editBasic">
          <div class="ava_box">
            <el-upload class="avatar-uploader" action="#" :show-file-list="false" :http-request="handlerUpload" :before-upload="beforeUpload">
              <img class="img" v-if="basic.photo_img_src" :src="basic.photo_img_src" alt="" />
              <div class="up" v-if="!basic.photo_img_src"></div>
            </el-upload>
          </div>
          <div class="name">
            <div class="n_t">
              <router-link to="">{{ basic.fullname }}</router-link>
            </div>
            <div class="clear"></div>
          </div>
          <div class="ed" @click="editBasic = true">编辑</div>
          <div class="ot">
            <div class="ot_item">{{ basic.sex_cn ? basic.sex_cn : '性别' }}</div>
            <div class="ot_item">{{ basic.age ? basic.age : '年龄' }}</div>
            <div class="ot_item">{{ basic.education_cn ? basic.education_cn : '学历' }}</div>
            <div class="ot_item">{{ basic.experience_cn ? basic.experience_cn : '经验' }}</div>
            <div class="clear"></div>
          </div>
          <div class="ct">
            <div class="ct_item phone">{{ contact.mobile || '手机号未填写' }}</div>
            <div class="ct_item wx">{{ contact.weixin || '微信未填写' }}</div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="fill_group for_bw" v-if="editBasic">
          <div class="fill_bw">
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  姓名
                </div>
                <div class="i_input"><el-input v-model="basic.fullname" placeholder="请填写姓名"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  性别
                </div>
                <div class="i_input">
                  <div :class="basic.sex === 1 ? 'g_type male active' : 'g_type male'" @click="sexClick(1)">男</div>
                  <div :class="basic.sex === 2 ? 'g_type female active' : 'g_type female'" @click="sexClick(2)">女</div>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  出生日期
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="basic.birthday"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="DateRanges"
                    :default-value="defaultValue"
                    placeholder="请选择出生日期"
                  ></el-date-picker>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  最高学历
                </div>
                <div class="i_input">
                  <el-select :style="{ width: '196px' }" v-model="basic.education" placeholder="请选择最高学历">
                    <el-option v-for="(item, index) in optionEducation" :key="index" :label="item.text" :value="item.id"></el-option>
                  </el-select>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  开始工作时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="basic.enter_job_time"
                    :disabled="isNoJobTime"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择开始工作时间"
                  ></el-date-picker>
                  <el-checkbox :style="{ 'font-size': '12px' }" class="for_after" v-model="isNoJobTime">应届生/无经验</el-checkbox>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  联系电话
                </div>
                <div class="i_input"><el-input v-model="contact.mobile" placeholder="请填写联系电话"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">* </span> -->
                  微信
                </div>
                <div class="i_input">
                  <el-input v-model="contact.weixin" placeholder="请填写微信"></el-input>
                  <el-checkbox :style="{ 'font-size': '12px' }" class="for_after" v-model="checkedEditPhone" @change="syncWxMobile">同手机</el-checkbox>
                </div>
                <div class="clear"></div>
              </div>
            </div>
          </div>
          <div class="fill_bgr">
            <div class="bgr_title">完善信息，获得更多求职机会</div>
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">*</span> -->
                  现居住地
                </div>
                <div class="i_input"><el-input v-model="basic.residence" placeholder="请填写现居住地"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">*</span> -->
                  婚姻状况
                </div>
                <div class="i_input">
                  <el-select :style="{ width: '196px' }" v-model="basic.marriage" placeholder="请选择婚姻状况">
                    <el-option v-for="(item, index) in optionMarriage" :key="index" :label="item.text" :value="item.id"></el-option>
                  </el-select>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">*</span> -->
                  专业
                </div>
                <div class="i_input">
                  <el-cascader
                    ref="intMajor"
                    :style="{ width: '196px' }"
                    v-model="basicMajor"
                    placeholder="请选择专业"
                    :options="optionMajor"
                    filterable
                    @change="handleValues([...arguments, 'major', 'basic'])"
                  ></el-cascader>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">* </span> -->
                  身高
                </div>
                <div class="i_input"><el-input v-model="basic.height" placeholder="请填写身高"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">*</span> -->
                  籍贯
                </div>
                <div class="i_input"><el-input v-model="basic.householdaddress" placeholder="请填写籍贯"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">* </span> -->
                  邮箱
                </div>
                <div class="i_input"><el-input v-model="contact.email" placeholder="请填写邮箱"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <!-- <span class="req">* </span> -->
                  QQ
                </div>
                <div class="i_input"><el-input v-model="contact.qq" placeholder="请填写QQ"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_btn">
              <el-button type="primary" @click="saveBasic">保存</el-button>
              <el-button @click="editBasic = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--求职意向-->
        <div :class="intentionList.length == 0 && !editIntention ? 'b2 noHaveData' : 'b2'" ref="intention" id="intentionGroup">
          <div class="l_title">
            <div class="req">*</div>
            <div class="t_name">求职意向</div>
            <div class="tobeimproved" v-if="intentionList.length == 0"></div>
            <div class="add" @click="editItemFun('', 'intention')">添加一条</div>
            <div class="clear"></div>
          </div>
          <!-- <div class="cur" v-if="!editCurrent">
            {{ basic.current_text }}
            <div class="ed" @click="editItemFun('', 'current')">编辑</div>
          </div> -->
          <div class="fill_group fill_al">
            <div class="fill_bm">
              <div class="fill_line">
                <div class="f_item f_item_top">
                  <div class="i_label">
                    <span class="req">*</span>
                    求职状态
                  </div>
                  <div class="i_input">
                    <el-select class="w250" v-model="basic.current" placeholder="请选择求职状态" @change="changeCurrent">
                      <el-option v-for="(item, index) in optionCurrent" :key="index" :label="item.text" :value="item.id"></el-option>
                    </el-select>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="intentionWarn" v-if="intentionList.length == 0 && !editIntention">
                  我们将根据您的求职意向为你推荐、筛选匹配的职位，以提升您的求职体验，请添加您的求职意向。
                </div>
              </div>
              <!-- <div class="fill_btn" v-if="intentionList.length == 0">
                <el-button type="primary" @click="saveCurrent">保存</el-button>
                <el-button @click="editCurrent = false">取消</el-button>
              </div> -->
            </div>
          </div>
          <div v-if="!editIntention">
            <div class="bit bit_hand" v-for="(item, index) in intentionList" :key="index">
              <div class="ed" @click="editItemFun(item, 'intention')">编辑</div>
              <div class="del" @click="delItemFun('resume_intention_delete', item.id, intentionList)">删除</div>
              <div class="j_name">{{ item.category_text }}</div>
              <div class="j_line">
                <div class="l_i substring">期望薪资：{{ item.wage_text }}</div>
                <div class="l_i substring">期望地区：{{ item.district_text }}</div>
              </div>
              <div class="j_line">
                <div class="l_i substring">工作性质：{{ item.nature_text }}</div>
                <div class="l_i substring">期望行业：{{ item.trade_text || '未填写' }}</div>
              </div>
            </div>
          </div>
          <div class="fill_group fill_al" v-if="editIntention">
            <div class="fill_bm">
              <div class="fill_line">
                <div class="f_item">
                  <div class="i_label">
                    <span class="req">*</span>
                    工作性质
                  </div>
                  <div class="i_input">
                    <el-select v-model="intentionItem.nature" placeholder="请选择工作性质" @change="changeNature">
                      <el-option v-for="(item, index) in optionNature" :key="index" :label="item.text" :value="item.id"></el-option>
                    </el-select>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="f_item">
                  <div class="i_label">
                    <span class="req">*</span>
                    期望职位
                  </div>
                  <div class="i_input">
                    <el-cascader
                      ref="intCategory"
                      v-model="intentionItemCategory"
                      placeholder="请选择期望职位"
                      :options="optionCategory"
                      filterable
                      @change="handleValues([...arguments, 'category', 'intention'])"
                    ></el-cascader>
                  </div>
                  <div class="clear"></div>
                </div>
              </div>
              <div class="fill_line">
                <div class="f_item">
                  <div class="i_label">
                    <span class="req">*</span>
                    工作地区
                  </div>
                  <div class="i_input">
                    <el-cascader
                      ref="intDistrict"
                      v-model="intentionItemDistrict"
                      placeholder="请选择期望地区"
                      :options="optionDistrict"
                      :props="{ checkStrictly: true }"
                      filterable
                      @change="handleValues([...arguments, 'district', 'intention'])"
                    ></el-cascader>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="f_item">
                  <div class="i_label">
                    <span class="req">*</span>
                    期望薪资
                  </div>
                  <div class="i_input">
                    <el-select class="w118" placeholder="请选择" v-model="intentionItem.minwage" @change="changeMinWage(intentionItem.minwage)">
                      <el-option v-for="(item, index) in optionMinWage" :key="index" :label="`${item}元/月`" :value="item"></el-option>
                    </el-select>
                    -
                    <el-select class="w118" v-model="intentionItem.maxwage" placeholder="请选择" @change="changeMaxWage">
                      <el-option v-for="(item, index) in optionMaxWage" :key="index" :label="`${item}元/月`" :value="item"></el-option>
                    </el-select>
                  </div>
                  <div class="clear"></div>
                </div>
              </div>
              <div class="fill_line">
                <div class="f_item">
                  <div class="i_label">
                    <span class="req">*</span>
                    行业
                  </div>
                  <div class="i_input">
                    <el-select v-model="intentionItem.trade" placeholder="请选择行业" @change="changeTrade">
                      <el-option v-for="(item, index) in optionTrade" :key="index" :label="item.text" :value="item.id"></el-option>
                    </el-select>
                  </div>
                  <div class="clear"></div>
                </div>
              </div>
              <div class="fill_btn">
                <!--              重复提交修改 zch 2022/9/16-->
                <el-button type="primary" @click="saveIntention">保存</el-button>
                <el-button @click="editIntention = false">取消</el-button>
              </div>
            </div>
          </div>
        </div>
        <!--特长标签-->
        <div :class="selectedTags.length == 0 && !editTags ? 'b3 noHaveData' : 'b3'" ref="tag" id="tagGroup">
          <div class="l_title">
            <div class="t_name">特长标签</div>
            <div class="ed" @click="editItemFun('', 'tags')">编辑</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="selectedTags.length == 0 && !editTags">特长标签是你客观展示自己的方式，快来填写吧！</div>
          </div>
          <div class="tg" v-if="!editTags">
            <div class="gi active" v-for="(item, index) in selectedTags" :key="index">{{ item.text }}</div>
            <div class="clear"></div>
          </div>
          <div class="fill_group fill_al" v-if="editTags">
            <div class="tg">
              <div :class="item.active ? 'gi active' : 'gi'" v-for="(item, index) in loopTags" :key="index" @click="tagClick(item)">{{ item.text }}</div>
              <div class="clear"></div>
            </div>
            <div class="tg_add">
              <el-input class="w156" v-model="customTagText" :disabled="selectedTags.length >= 10" placeholder="请输入自定义标签"></el-input>
              <el-button type="info" plain :disabled="selectedTags.length >= 10" @click="addCusTag">添加</el-button>
            </div>
            <div class="fill_btn">
              <el-button type="primary" @click="saveTags">保存</el-button>
              <el-button @click="editTags = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--自我描述-->
        <div :class="basic.specialty == '' && !editSpecialty ? 'b4 noHaveData' : 'b4'" ref="specialty" id="specialtyGroup">
          <div class="l_title">
            <div class="t_name">自我描述</div>
            <div class="ed" @click="editItemFun('', 'specialty')">编辑</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="basic.specialty == '' && !editSpecialty">总结优势，突出亮点，您的自我描述我们将突出展示给企业。</div>
          </div>
          <!-- <div class="l_add" v-if="!basic.specialty && !editSpecialty">
            <div class="la_title" @click="editSpecialty = !editSpecialty">添加一条</div>
            <div class="la_des">自我描述是你客观认识自己的基石，快来填写吧！</div>
          </div> -->
          <div class="b_con" v-if="!editSpecialty">{{ basic.specialty }}</div>
          <div class="fill_group" v-if="editSpecialty">
            <el-input class="area_box" type="textarea" :rows="7" placeholder="请输入内容" v-model="basic.specialty"></el-input>
            <div class="fill_btn">
              <el-button type="primary" @click="saveSpecialty">保存</el-button>
              <el-button @click="editSpecialty = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--教育经历-->
        <div :class="educationList.length == 0 && !editEducation ? 'b5 noHaveData' : 'b5'" ref="education" id="educationGroup">
          <div class="l_title">
            <div class="req">*</div>
            <div class="t_name">教育经历</div>
            <div class="add" @click="editItemFun('', 'education')">添加一条</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="educationList.length == 0 && !editEducation">教育经历是你学历和专业能力的最佳体现，立即添加提升求职竞争力！</div>
          </div>
          <div v-if="!editEducation">
            <div class="bit bit_hand listItem" v-for="(item, index) in educationList" :key="index">
              <div class="ed" @click="editItemFun(item, 'education')">编辑</div>
              <div class="del" @click="delItemFun('resume_education_delete', item.id, educationList)">删除</div>
              <div class="e_time">{{ item.starttime }} - {{ item.todate ? '至今' : item.endtime }}</div>
              <div class="e_t1">{{ item.school }}</div>
              <div class="e_t2">{{ item.education_text }}{{ item.major ? `，${item.major}` : '' }}</div>
              <div v-if="item.incomplete" class="incompleteWarn">信息不完整，请您补充完善</div>
            </div>
          </div>
          <div class="fill_group fill_al" v-if="editEducation">
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  学校名称
                </div>
                <div class="i_input"><el-input v-model="educationItem.school" placeholder="请填写就读学校名称"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  专业名称
                </div>
                <div class="i_input"><el-input v-model="educationItem.major" placeholder="请填写就读专业"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  入学时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="educationItem.starttime"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择入学时间"
                  ></el-date-picker>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  毕业时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="educationItem.endtime"
                    :disabled="educationItem.todate"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择毕业时间"
                  ></el-date-picker>
                  <el-checkbox class="for_after" v-model="educationItem.todate">至今</el-checkbox>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  取得学历
                </div>
                <div class="i_input">
                  <el-select :style="{ width: '196px' }" v-model="educationItem.education" placeholder="请选择取得学历">
                    <el-option v-for="(item, index) in optionEducation" :key="index" :label="item.text" :value="item.id"></el-option>
                  </el-select>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_btn">
              <!--            重复提交修改 zch 2022/9/16-->
              <el-button type="primary" @click="saveEducation">保存</el-button>
              <el-button @click="editEducation = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--工作经历-->
        <div :class="workList.length == 0 && !editWork ? 'b5 noHaveData' : 'b5'" ref="work" id="workGroup">
          <div class="l_title">
            <div class="req">*</div>
            <div class="t_name">工作经历</div>
            <div class="add" @click="editItemFun('', 'work')">添加一条</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="workList.length == 0 && !editWork">展示您的工作经验、工作能力，这也是HR判断您是否符合岗位要求的重要依据。</div>
          </div>
          <!-- <div class="l_add" v-if="workList.length == 0 && !editWork" @click="editItemFun('', 'work')">
            <div class="la_title">添加一条</div>
            <div class="la_des">工作经历是你丰富阅历的最佳呈现，丰富的工作经历更易获得HR的青睐！</div>
          </div> -->
          <div v-if="!editWork">
            <div class="bit bit_hand itemList" v-for="(item, index) in workList" :key="index">
              <div class="ed" @click="editItemFun(item, 'work')">编辑</div>
              <div class="del" @click="delItemFun('resume_work_delete', item.id, workList)">删除</div>
              <div class="e_time">{{ item.starttime }} - {{ item.todate ? '至今' : item.endtime }}</div>
              <div class="e_t1">{{ item.companyname }}</div>
              <div class="e_t2">{{ item.jobname }}</div>
              <div class="e_t3" v-if="item.duty!=''">{{ item.duty }}</div>
              <div v-if="item.incomplete" class="incompleteWarn">信息不完整，请您补充完善</div>
            </div>
          </div>
          <div class="fill_group fill_al" v-if="editWork">
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  公司名称
                </div>
                <div class="i_input"><el-input v-model="workItem.companyname" placeholder="请输入公司名称"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  职位名称
                </div>
                <div class="i_input"><el-input v-model="workItem.jobname" placeholder="请输入职位名称"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  入职时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="workItem.starttime"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择入职时间"
                  ></el-date-picker>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  离职时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="workItem.endtime"
                    :disabled="workItem.todate"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择离职时间"
                  ></el-date-picker>
                  <el-checkbox class="for_after" v-model="workItem.todate">至今</el-checkbox>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item al">
                <div class="i_label">
                  <span class="req">*</span>
                  工作职责
                </div>
                <div class="i_input"><el-input class="w618 h190" type="textarea" v-model="workItem.duty" placeholder="请简要描述您此段工作期间的工作职责"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_btn">
              <!--            重复提交修改 zch 2022/9/16-->
              <el-button type="primary" @click="saveWork">保存</el-button>
              <el-button @click="editWork = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--培训经历-->
        <div :class="trainList.length == 0 && !editTrain ? 'b5 noHaveData' : 'b5'" ref="train" id="trainGroup">
          <div class="l_title">
            <div class="t_name">培训经历</div>
            <div class="add" @click="editItemFun('', 'train')">添加一条</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="trainList.length == 0 && !editTrain">培训经历是你勇于上进的最好的体现，快来说说令您难忘的学习经历吧！</div>
          </div>
          <!-- <div class="l_add" v-if="!trainList.length && !editTrain" @click="editItemFun('', 'train')">
            <div class="la_title">添加一条</div>
            <div class="la_des">培训经历是你勇于上进的最好的体现，快来说说令您难忘的学习经历吧！</div>
          </div> -->
          <div v-if="!editTrain">
            <div class="bit bit_hand itemList" v-for="(item, index) in trainList" :key="index">
              <div class="ed" @click="editItemFun(item, 'train')">编辑</div>
              <div class="del" @click="delItemFun('resume_train_delete', item.id, trainList)">删除</div>
              <div class="e_time">{{ item.starttime }} - {{ item.todate ? '至今' : item.endtime }}</div>
              <div class="e_t1">{{ item.agency }}</div>
              <div class="e_t2">{{ item.course }}</div>
              <div class="e_t3">{{ item.description }}</div>
              <div v-if="item.incomplete" class="incompleteWarn">信息不完整，请您补充完善</div>
            </div>
          </div>
          <div class="fill_group fill_al" v-if="editTrain">
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  培训机构
                </div>
                <div class="i_input"><el-input v-model="trainItem.agency" placeholder="请填写培训机构"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  培训课程
                </div>
                <div class="i_input"><el-input v-model="trainItem.course" placeholder="请填写培训课程"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  开始时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="trainItem.starttime"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择开始时间"
                  ></el-date-picker>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  结束时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="trainItem.endtime"
                    :disabled="trainItem.todate"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择结束时间"
                  ></el-date-picker>
                  <el-checkbox class="for_after" v-model="trainItem.todate">至今</el-checkbox>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item al">
                <div class="i_label">
                  <span class="req">*</span>
                  培训内容
                </div>
                <div class="i_input"><el-input class="w618 h190" type="textarea" v-model="trainItem.description" placeholder="请填写培训内容"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_btn">
              <!--            重复提交修改 zch 2022/9/16-->
              <el-button type="primary" @click="saveTrain">保存</el-button>
              <el-button @click="editTrain = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--项目经历-->
        <div :class="projectList.length == 0 && !editProject ? 'b5 noHaveData' : 'b5'" ref="project" id="projectGroup">
          <div class="l_title">
            <div class="t_name">项目经历</div>
            <div class="add" @click="editItemFun('', 'project')">添加一条</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="projectList.length == 0 && !editProject">项目经历让你的简历超越99%的竞争者，快来说说令您难忘的项目经历吧！</div>
          </div>
          <!-- <div class="l_add" v-if="!projectList.length && !editProject" @click="editItemFun('', 'project')">
            <div class="la_title">添加一条</div>
            <div class="la_des">项目经历让你的简历超越99%的竞争者，快来说说令您难忘的项目经历吧！</div>
          </div> -->
          <div v-if="!editProject">
            <div class="bit bit_hand itemList" v-for="(item, index) in projectList" :key="index">
              <div class="ed" @click="editItemFun(item, 'project')">编辑</div>
              <div class="del" @click="delItemFun('resume_project_delete', item.id, projectList)">删除</div>
              <div class="e_time">{{ item.starttime }} - {{ item.todate ? '至今' : item.endtime }}</div>
              <div class="e_t1">{{ item.projectname }}</div>
              <div class="e_t2">{{ item.role }}</div>
              <div class="e_t3">{{ item.description }}</div>
              <div v-if="item.incomplete" class="incompleteWarn">信息不完整，请您补充完善</div>
            </div>
          </div>
          <div class="fill_group fill_al" v-if="editProject">
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  项目名称
                </div>
                <div class="i_input"><el-input v-model="projectItem.projectname" placeholder="请填写项目名称"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  担任角色
                </div>
                <div class="i_input"><el-input v-model="projectItem.role" placeholder="请填写担任角色"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  开始时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="projectItem.starttime"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择开始时间"
                  ></el-date-picker>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  结束时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="projectItem.endtime"
                    :disabled="projectItem.todate"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择结束时间"
                  ></el-date-picker>
                  <el-checkbox class="for_after" v-model="projectItem.todate">至今</el-checkbox>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_line">
              <div class="f_item al">
                <div class="i_label">
                  <span class="req">*</span>
                  项目描述
                </div>
                <div class="i_input"><el-input class="w618 h190" type="textarea" v-model="projectItem.description" placeholder="请填写项目描述"></el-input></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_btn">
              <!--            重复提交修改 zch 2022/9/16-->
              <el-button type="primary" @click="saveProject">保存</el-button>
              <el-button @click="editProject = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--获得证书-->
        <div :class="certificateList.length == 0 && !editCertificate ? 'b6 noHaveData' : 'b6'" ref="certificate" id="certificateGroup">
          <div class="l_title">
            <div class="t_name">获得证书</div>
            <div class="add" @click="editItemFun('', 'certificate')">添加一条</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="certificateList.length == 0 && !editCertificate">证书是您驰骋职场的敲门砖。 您有哪些证书呢？</div>
          </div>
          <!-- <div class="l_add" v-if="!certificateList.length && !editCertificate" @click="editItemFun('', 'certificate')">
            <div class="la_title">添加一条</div>
            <div class="la_des">证书是您驰骋职场的敲门砖。 您有哪些证书呢？</div>
          </div> -->
          <div v-if="!editCertificate">
            <div class="bit bit_hand itemList" v-for="(item, index) in certificateList" :key="index">
              <div class="ed" @click="editItemFun(item, 'certificate')">编辑</div>
              <div class="del" @click="delItemFun('resume_certificate_delete', item.id, certificateList)">删除</div>
              <div class="bit">
                <div class="b_i">
                  {{ item.name }}
                  <span class="date">{{ item.obtaintime }}</span>
                </div>
              </div>
              <div v-if="item.incomplete" class="incompleteWarn">信息不完整，请您补充完善</div>
            </div>
          </div>
          <div class="fill_group fill_al" v-if="editCertificate">
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  证书名称
                </div>
                <div class="i_input"><el-input :style="{ width: '250px' }" v-model="certificateItem.name" placeholder="请填写证书名称"></el-input></div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  获得时间
                </div>
                <div class="i_input">
                  <el-date-picker
                    v-model="certificateItem.obtaintime"
                    type="month"
                    format="yyyy-MM"
                    value-format="yyyy-MM"
                    :picker-options="datePickerRange"
                    placeholder="请选择获得时间"
                  ></el-date-picker>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_btn">
              <!--            重复提交修改 zch 2022/9/16-->
              <el-button type="primary" @click="saveCertificate">保存</el-button>
              <el-button @click="editCertificate = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--语言能力-->
        <div :class="languageList.length == 0 && !editLanguage ? 'b6 noHaveData' : 'b6'" ref="language" id="languageGroup">
          <div class="l_title">
            <div class="t_name">语言能力</div>
            <div class="add" @click="editItemFun('', 'language')">添加一条</div>
            <div class="clear"></div>
            <div class="intentionWarn" v-if="languageList.length == 0 && !editLanguage">语言能力是提升求职竞争力的法宝，千万别谦虚啊！</div>
          </div>
          <!-- <div class="l_add" v-if="languageList.length == 0 && !editLanguage" @click="editItemFun('', 'language')">
            <div class="la_title">添加一条</div>
            <div class="la_des">语言能力是提升求职竞争力的法宝，千万别谦虚啊！</div>
          </div> -->
          <div v-if="!editLanguage">
            <div class="bit bit_hand itemList" v-for="(item, index) in languageList" :key="index">
              <div class="ed" @click="editItemFun(item, 'language')">编辑</div>
              <div class="del" @click="delItemFun('resume_language_delete', item.id, languageList)">删除</div>
              <div class="bit">
                <div class="b_i">
                  {{ item.language_text }}
                  <span class="date">{{ item.level_text }}</span>
                </div>
              </div>
              <div v-if="item.incomplete" class="incompleteWarn">信息不完整，请您补充完善</div>
            </div>
          </div>
          <div class="fill_group fill_al" v-if="editLanguage">
            <div class="fill_line">
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  语种
                </div>
                <div class="i_input">
                  <el-select v-model="languageItem.language" placeholder="请选择语种" @visible-change="choiceLanguage">
                    <el-option v-for="(item, index) in optionLanguage" :key="index" :label="item.text" :value="item.id"></el-option>
                  </el-select>
                </div>
                <div class="clear"></div>
              </div>
              <div class="f_item">
                <div class="i_label">
                  <span class="req">*</span>
                  熟悉程度
                </div>
                <div class="i_input">
                  <el-select v-model="languageItem.level" placeholder="请选择熟悉程度">
                    <el-option v-for="(item, index) in optionLanguageLevel" :key="index" :label="item.text" :value="item.id"></el-option>
                  </el-select>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="fill_btn">
              <!--            重复提交修改 zch 2022/9/16-->
              <el-button type="primary" @click="saveLanguage">保存</el-button>
              <el-button @click="editLanguage = false">取消</el-button>
            </div>
          </div>
        </div>
        <!--我的作品-->
        <div :class="imgList.length == 0 ? 'b7 noHaveData' : 'b7'" ref="img" id="imgGroup">
          <div class="l_title">
            <div class="t_name">我的作品</div>
            <div class="add">
              <el-upload class="avatar-uploader" action="#" v-if="imgList.length < 6" :show-file-list="false" :http-request="handlerUploadImg" :before-upload="beforeUpload">
                添加一条
              </el-upload>
            </div>
            <!-- <div class="t_tip">最多上传6张，每张最大800KB,支持jpg/gif/bmp/png格式</div> -->
            <div class="clear"></div>
            <div class="intentionWarn">最多上传6张，每张最大800KB,支持jpg/gif/bmp/png格式</div>
          </div>
          <div class="i_box">
            <div class="i_t" v-for="(item, index) in imgList" :key="index">
              <img :src="item.img_src" :alt="item.title" />
              <div class="i_del" @click="delItemFun('resume_img_delete', item.id, imgList)"></div>
            </div>
            <!-- <el-upload class="avatar-uploader" action="#" v-if="imgList.length < 6" :show-file-list="false" :http-request="handlerUploadImg" :before-upload="beforeUpload">
              <div class="i_t up"></div>
            </el-upload> -->
            <div class="clear"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index';
// import {
//   handlerHttpError
// } from '@/utils/error'
import http from '@/utils/http';
import api from '@/api';

export default {
  name: 'Resume',
  filters: {
    timeFilter(timestamp) {
      return timestamp ? parseTime(timestamp, '{y}-{m}-{d}') : '';
    }
  },
  data() {
    return {
      DateRanges: this.birthdayDateRange(),
      datePickerRange: this.dateRange(),
      defaultValue: this.defaultValueFun(),
      basic: {},
      contact: {},
      intention: {},
      education: {},
      work: {},
      isRoll: false,
      resumePublic: true,
      editBasic: false,
      editCurrent: false,
      editIntention: false,
      editTags: false,
      editSpecialty: false,
      editEducation: false,
      editWork: false,
      editExperience: false,
      editTrain: false,
      editProject: false,
      editCertificate: false,
      editLanguage: false,
      isNoJobTime: false,
      checkedEditPhone: false,
      optionTrade: [],
      optionEducation: [],
      optionCurrent: [],
      optionMinWage: [],
      optionMaxWage: [],
      optionCategory: [],
      optionDistrict: [],
      optionMarriage: [],
      optionMajor: [],
      optionNature: [],
      optionLanguage: [],
      optionLanguageLevel: [],
      tagList: [],
      loopTags: [],
      selectedTags: [],
      customTagText: '',
      intentionList: [],
      intentionItem: {},
      intentionItemCategory: [],
      intentionItemDistrict: [],
      basicMajor: [],
      educationList: [],
      educationItem: {},
      workList: [],
      workItem: {},
      trainList: [],
      trainItem: {},
      projectList: [],
      projectItem: {},
      certificateList: [],
      certificateItem: {},
      languageList: [],
      languageOldList: [],
      languageItem: {},
      imgList: [],
      rightMenu: [],
      moduleList: [],
      scanQrcode: '',
      scanQrcodeImg: '',
      timer: '',
      timerImg: '',
      preview_url: '',
      issubmit: false, // 重复提交修改 zch 2022/9/16
      infoBoolean: false,
      optionArr: 'add'
    };
  },
  created() {
    this.initInfo();
  },
  mounted() {
    if (!this.$store.state.LoginOrNot) {
      document.body.scrollHeight;
      return false;
    }
    window.addEventListener('scroll', this.getScrollTop);
    // 学历分类
    this.$store.dispatch('getClassify', 'education').then(() => {
      this.optionEducation = JSON.parse(JSON.stringify(this.$store.state.classifyEdu));
    });
    // 手机号
    this.contact.mobile = this.$store.state.userMobile;
    // 求职状态分类
    this.$store.dispatch('getClassify', 'current').then(() => {
      this.optionCurrent = JSON.parse(JSON.stringify(this.$store.state.classifyCurrent));
    });
    // 薪资分类
    this.makeWageOption('min', 500);
    this.makeWageOption('max', 1000);
    // 职位分类
    this.$store.dispatch('getClassify', 'jobcategory').then(() => {
      this.optionCategory = this.$store.state.classifyJobCategory;
    });
    // 地区分类
    this.$store.dispatch('getClassify', 'citycategory').then(() => {
      this.optionDistrict = this.$store.state.classifyCityCategory;
    });
    // 婚姻状况分类
    this.$store.dispatch('getClassify', 'marriage').then(() => {
      this.optionMarriage = JSON.parse(JSON.stringify(this.$store.state.classifyMarriage));
    });
    // 专业分类
    this.$store.dispatch('getClassify', 'major').then(() => {
      this.optionMajor = JSON.parse(JSON.stringify(this.$store.state.classifyMajor));
    });
    // 工作性质分类
    this.$store.dispatch('getClassify', 'resumeNature').then(() => {
      this.optionNature = JSON.parse(JSON.stringify(this.$store.state.classifyResumeNature));
    });
    // 行业分类
    this.$store.dispatch('getClassify', 'trade').then(() => {
      this.optionTrade = JSON.parse(JSON.stringify(this.$store.state.classifyTrade));
    });
    // 标签分类
    this.$store.dispatch('getClassify', 'resumeTag').then(() => {
      this.tagList = JSON.parse(JSON.stringify(this.$store.state.classifyResumeTag));
    });
    // 语种分类
    this.$store.dispatch('getClassify', 'language').then(() => {
      this.optionLanguage = JSON.parse(JSON.stringify(this.$store.state.classifyLanguage));
    });
    // 语种熟悉程度分类
    this.$store.dispatch('getClassify', 'languageLevel').then(() => {
      this.optionLanguageLevel = JSON.parse(JSON.stringify(this.$store.state.classifyLanguageLevel));
    });
    let locationUrl = this.$store.state.config.mobile_domain + 'scan_upload?type=resume_photo&access=' + this.$store.state.userToken;
    locationUrl = encodeURIComponent(locationUrl);
    let locationUrlImg = this.$store.state.config.mobile_domain + 'scan_upload?type=resume_img&access=' + this.$store.state.userToken;
    locationUrlImg = encodeURIComponent(locationUrlImg);
    this.scanQrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + locationUrl;
    this.scanQrcodeImg = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + locationUrlImg;
    var url = location.href;
    if (url.split('#')[1]) {
      this.$nextTick(() => {
        location.href = url;
      });
    }
  },
  beforeDestroy() {
    clearInterval(this.timer);
    clearInterval(this.timerImg);
  },
  watch: {
    // 'contact.weixin': function() {
    //   this.$nextTick(function() {
    //     this.checkedEditPhone = this.contact.mobile === this.contact.weixin
    //   })
    // },
    // 'imgList': function() {
    //   this.$nextTick(function() {})
    // }
  },
  computed: {},
  methods: {
    sexClick(val) {
      this.basic.sex = val;
      this.basic.sex_text = val == 1 ? '男' : '女';
    },
    dateRange() {
      return {
        disabledDate(time) {
          return time.getTime() > new Date().getTime();
        }
      };
    },
    //PC端创建简历日期选择往前推到16岁 zdq
    birthdayDateRange() {
      return {
        disabledDate(time) {
          var myDate = new Date();
          return time.getTime() > new Date(new Date(myDate.getFullYear(), 11).getTime() - 16 * 365 * 24 * 3600 * 1000);
        }
      };
    },
    //PC端创建简历日期选择往前推到16岁 zdq
    defaultValueFun() {
      const nowDate = new Date();
      const nowYear = nowDate.getFullYear();
      const minBirthdayYear = nowYear - 16;
      return new Date(minBirthdayYear, 11);
    },
    handlerPreview() {
      window.open(this.preview_url);
    },
    startScan(type) {
      if (type === 1) {
        // 头像
        clearInterval(this.timer);
        this.timer = setInterval(this.scanUploadResult, 5000);
      } else {
        // 作品
        clearInterval(this.timerImg);
        this.timer = setInterval(this.scanUploadImgResult, 5000);
      }
    },
    // 扫描上传简历作品
    scanUploadImgResult() {
      http
        .post(api.scan_upload_result, {
          type: 'resume_img'
        })
        .then(res => {
          if (res.data !== 0) {
            this.imgList = res.data;
          }
        })
        .catch(() => {});
    },
    // 扫描上传简历头像
    scanUploadResult() {
      http
        .post(api.scan_upload_result, {
          type: 'resume_photo'
        })
        .then(res => {
          if (res.data !== 0) {
            this.basic.photo_img = res.data.file_id;
            this.basic.photo_img_src = res.data.file_url;
          }
        })
        .catch(() => {});
    },
    // 上传头像之前验证格式及大小
    beforeUpload(file) {
      let fileRaw = file;
      let fileTypeArr = (fileRaw.type || '').split('/');
      let fileType = fileTypeArr[1];
      let typeArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif'];
      if (-1 === typeArr.indexOf(fileType)) {
        this.$message.error('文件类型不支持');
        return false;
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024;
        size = size.toFixed(1);
        this.$message.error('文件大小超出限制，最大' + size + 'mb');
        return false;
      }
    },
    // 上传作品
    handlerUploadImg(file) {
      http
        .postFormData(api.uploadFile, {
          file: file.file
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            let obj = {
              file_id: res.data.file_id,
              img_src: res.data.file_url
            };
            this.imgList.push(obj);
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    // 上传头像
    handlerUpload(file) {
      http
        .postFormData(api.uploadFile, {
          file: file.file
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.basic.photo_img = res.data.file_id;
            this.basic.photo_img_src = res.data.file_url;
            this.$forceUpdate()
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    //重构数据
    doResetArray(type, array) {
      if (type == 'intention_list') {
        for (let i in array) {
          array[i].incomplete = false;
          if (array[i].nature == '' || array[i].category1 == '' || array[i].district1 == '' || array[i].trade == '' || array[i].minwage == '' || array[i].maxwage == '') {
            array[i].incomplete = true;
          }
        }
        this.intentionList = array;
      } else if (type == 'resume_work') {
        for (let i in array) {
          array[i].todate = array[i].todate ? 1 : 0;
          array[i].incomplete = false;
          if (array[i].companyname == '' || array[i].starttime == '' || (array[i].todate == '' && array[i].endtime == '') || array[i].jobname == '' || array[i].duty == '') {
            array[i].incomplete = true;
          }
        }
        this.workList = array;
      } else if (type == 'resume_education') {
        for (let i in array) {
          array[i].todate = array[i].todate ? 1 : 0;
          array[i].incomplete = false;
          if (array[i].school == '' || array[i].starttime == '' || (array[i].todate == '' && array[i].endtime == '') || array[i].education == '') {
            array[i].incomplete = true;
          }
          if (array[i].education == 0) {
            array[i].education = '';
          }
        }
        this.educationList = array;
      } else if (type == 'project_list') {
        for (let i in array) {
          array[i].todate = array[i].todate ? 1 : 0;
          array[i].incomplete = false;
          if (array[i].description == '' || array[i].starttime == '' || (array[i].todate == '' && array[i].endtime == '') || array[i].role == '' || array[i].projectname == '') {
            array[i].incomplete = true;
          }
        }
        this.projectList = array;
      } else if (type == 'training_list') {
        for (let i in array) {
          array[i].todate = array[i].todate ? 1 : 0;
          array[i].incomplete = false;
          if (array[i].agency == '' || array[i].starttime == '' || (array[i].todate == '' && array[i].endtime == '') || array[i].course == '' || array[i].description == '') {
            array[i].incomplete = true;
          }
        }
        this.trainList = array;
      } else if (type == 'certificate_list') {
        for (let i in array) {
          array[i].incomplete = false;
          if (array[i].name == '' || array[i].obtaintime == '') {
            array[i].incomplete = true;
          }
        }
        this.certificateList = array;
      } else if (type == 'languageList') {
        for (let i in array) {
          array[i].incomplete = false;
          if (array[i].language_cn == '' || array[i].level_cn == '') {
            array[i].incomplete = true;
          }
        }
        this.languageList = array;
      }
    },
    // 获取简历信息
    initInfo() {
      let infoData = JSON.parse(localStorage.getItem('resumeDetail'));
      //更新基本资料
      this.preview_url = infoData.preview_url;
      this.basic = infoData.basic;
      this.basic.education = infoData.basic.education == 0 ? '' : infoData.basic.education;
      this.basic.specialty = infoData.basic.specialty ? infoData.basic.specialty : '';
      this.basicMajor = [this.basic.major1, this.basic.major2];
      this.basic.current = this.basic.current ? this.basic.current : '';
      this.basic.current_text = this.basic.current_text ? this.basic.current_text : '';
      this.enclosure_id = infoData.enclosure_id ? infoData.enclosure_id : 0;
      if (this.basic.current == '') {
        this.editCurrent = true;
      }
      if (this.basic.enter_job_time) {
        this.basic.enter_job_time = this.dateFormat(this.basic.enter_job_time);
      } else {
        this.isNoJobTime = true;
      }
      if (this.basic.tag) {
        this.cusTags();
      }
      this.contact = infoData.contact ? infoData.contact : [];
      this.checkedEditPhone = this.contact.mobile === this.contact.weixin;
      //重构求职意向数据
      if (infoData.intention_list && infoData.intention_list.length > 0) {
        this.doResetArray('intention_list', infoData.intention_list);
      }
      //重构工作经历数据
      if (infoData.resume_work && infoData.resume_work.length > 0) {
        this.doResetArray('resume_work', infoData.resume_work);
      }
      (this.intentionList = infoData.intention_list ? infoData.intention_list : []),
        (this.workList = infoData.resume_work ? infoData.resume_work : []),
        (this.workList = this.workList.map(item => {
          return {
            id: item.id,
            companyname: item.companyname,
            starttime: this.dateFormat(item.starttime),
            endtime: this.dateFormat(item.endtime),
            todate: item.todate === 1,
            jobname: item.jobname,
            duty: item.duty,
            incomplete: item.incomplete
          };
        }));

      this.educationList = infoData.resume_education ? infoData.resume_education : [];
      //重构教育经历数据
      if (this.educationList && this.educationList.length > 0) {
        this.doResetArray('resume_education', infoData.resume_education);
      }
      this.educationList = this.educationList.map(item => {
        return {
          id: item.id,
          education: item.education,
          education_text: item.education_cn,
          starttime: this.dateFormat(item.starttime),
          endtime: this.dateFormat(item.endtime),
          todate: item.todate === 1,
          major: item.major,
          school: item.school,
          incomplete: item.incomplete
        };
      });
      this.projectList = infoData.project_list ? infoData.project_list : [];
      //重构项目经历数据
      if (this.projectList && this.projectList.length > 0) {
        this.doResetArray('project_list', this.projectList);
      }
      this.projectList = this.projectList.map(item => {
        return {
          id: item.id,
          description: item.description,
          starttime: this.dateFormat(item.starttime),
          endtime: this.dateFormat(item.endtime),
          todate: item.todate === 1,
          role: item.role,
          projectname: item.projectname,
          incomplete: item.incomplete
        };
      });
      this.trainList = infoData.training_list ? infoData.training_list : [];
      //重构培训经历数据
      if (this.trainList && this.trainList.length > 0) {
        this.doResetArray('training_list', this.trainList);
      }
      this.trainList = this.trainList.map(item => {
        return {
          id: item.id,
          agency: item.agency,
          starttime: this.dateFormat(item.starttime),
          endtime: this.dateFormat(item.endtime),
          todate: item.todate === 1,
          course: item.course,
          description: item.description,
          incomplete: item.incomplete
        };
      });

      this.certificateList = infoData.certificate_list ? infoData.certificate_list : [];
      //重构证书数据
      if (this.certificateList && this.certificateList.length > 0) {
        this.doResetArray('certificate_list', this.certificateList);
      }
      this.certificateList = this.certificateList.map(item => {
        return {
          id: item.id,
          name: item.name,
          obtaintime: this.dateFormat(item.obtaintime),
          incomplete: item.incomplete
        };
      });

      if (infoData.languageList.level == 0 && infoData.languageList.language == 0) {
        this.languageList = [];
      } else {
        let languageListArr = [];
        this.languageList = languageListArr.push(infoData.languageList);
      }
      //重构语言数据
      if (this.languageList && this.languageList.length > 0) {
        this.doResetArray('languageList', this.languageList);
      }
      this.imgList = infoData.img_list ? infoData.img_list : [];
      // 作品
      // if (this.$store.state.resume.img_list.length) {
      //   this.imgList = this.$store.state.resume.img_list;
      // }
      this.infoBoolean = true;
    },
    // 选择当前求职状态
    changeCurrent(value) {
      this.basic.current_text = this.optionCurrent.filter(item => item.id === value)[0].text;
      this.$forceUpdate();
    },
    // 选择工作性质
    changeNature(value) {
      this.intention.nature_text = this.optionNature.filter(item => item.id === value)[0].text;
    },
    // 选择期望行业
    changeTrade(value) {
      this.basic.trade_text = this.optionTrade.filter(item => item.id === value)[0].text;
    },
    // 选择标签
    tagClick(data) {
      // 判断选中特长标签的数量
      if (!data.active && this.selectedTags.length >= 10) {
        this.$message({
          message: '您最多可以选择10个特长标签',
          type: 'warning'
        });
        return false;
      }
      console.log(this.loopTags)
      this.loopTags = this.loopTags.map(function(item) {
        let isSelect = false;
        if (data.id) {
          isSelect = parseInt(item.id) === parseInt(data.id) ? !item.active : item.active;
        } else {
          isSelect = item.text === data.text ? !item.active : item.active;
        }
        return {
          id: item.id,
          text: item.text,
          active: isSelect
        };
      });
      this.syncSelectedTag();
    },
    // 特长标签数据重构
    cusTags() {
      this.loopTags = this.tagList;
      let interfaceTags = this.basic.tag;
      this.loopTags = this.loopTags.map(function(item) {
        return {
          id: item.id,
          text: item.text,
          active: false
        };
      });
      if (interfaceTags.length) {
        this.loopTags = this.loopTags.map(item => {
          let thisIndex = interfaceTags.findIndex(val => parseInt(item.id) === parseInt(val));
          return {
            id: item.id,
            text: item.text,
            active: thisIndex > -1
          };
        });
        // 恢复自定义标签
        interfaceTags.forEach(item => {
          // 自定义标签无id
          if (!parseInt(item)) {
            this.loopTags.push({
              id: '',
              text: item,
              active: true
            });
          }
        });
      }
      this.syncSelectedTag();
    },
    // 添加自定义标签
    addCusTag() {
      if (this.selectedTags.length >= 10) {
        this.$message({
          message: '您最多可以选择10个特长标签',
          type: 'warning'
        });
        return false;
      } else {
        if (!this.customTagText) {
          this.$message({
            message: '请输入自定义标签',
            type: 'warning'
          });
        } else {
          this.loopTags.push({
            id: '',
            text: this.customTagText,
            active: true
          });
          this.syncSelectedTag();
          this.customTagText = '';
        }
      }
    },
    // 同步已选中标签
    syncSelectedTag() {
      this.selectedTags = this.loopTags.filter(item => item.active);
    },
    // 编辑信息公用方法
    editItemFun(item, editName) {
      if (item == '') {
        this.optionArr = 'add';
      } else {
        this.optionArr = 'edit';
      }
      this[`${editName}Item`] = item ? item : {};
      /**
       * 【bug】简历求职意向最多应设置三条
       *  zch 2022.9.20
       *  【新增】
       */
      if (editName === 'intention' && !item && this.intentionList.length >= 3) {
        this.$message({
          message: '求职意向最多可填写三条',
          type: 'warning'
        });
        return false;
      }
      if (editName === 'intention' && item) {
        this.intentionItemCategory = [item.category1, item.category2, item.category3];
        this.intentionItemDistrict = [];
        if (item.district1 > 0) {
          this.intentionItemDistrict.push(item.district1);
        }
        if (item.district2 > 0) {
          this.intentionItemDistrict.push(item.district2);
        }
        if (item.district3 > 0) {
          this.intentionItemDistrict.push(item.district3);
        }
        // this.intentionItemDistrict = [item.district1, item.district2, item.district3]
        if (item.trade === 0) {
          item.trade = '';
        }
      }
      this.editBasic = false;
      this.editCurrent = false;
      this.editIntention = false;
      this.editTags = false;
      this.editSpecialty = false;
      this.editEducation = false;
      this.editWork = false;
      this.editTrain = false;
      this.editProject = false;
      this.editCertificate = false;
      this.editLanguage = false;
      let editUpName = editName.toLowerCase().replace(/( |^)[a-z]/g, L => L.toUpperCase());
      this[`edit${editUpName}`] = true;
    },
    // 保存基本资料
    saveBasic() {
      if (!this.basic.fullname) {
        this.$message({
          message: '请填写姓名',
          type: 'warning'
        });
        return false;
      }
      if (!this.basic.sex) {
        this.$message({
          message: '请选择性别',
          type: 'warning'
        });
        return false;
      }
      if (!this.basic.birthday) {
        this.$message({
          message: '请选择出生日期',
          type: 'warning'
        });
        return false;
      }
      if (!this.isNoJobTime) {
        if (!this.basic.enter_job_time) {
          this.$message({
            message: '请选择开始工作时间',
            type: 'warning'
          });
          return false;
        }
      }
      if (!this.basic.education) {
        this.$message({
          message: '请选择学历',
          type: 'warning'
        });
        return false;
      }
      if (!this.contact.mobile) {
        this.$message({
          message: '请填写联系电话',
          type: 'warning'
        });
        return false;
      }
      this.basic.major1 = this.basicMajor[0];
      this.basic.major2 = this.basicMajor[1];
      this.basic.education_cn = this.optionEducation.filter(item => item.id === this.basic.education)[0].text;
      this.basic.sex_cn = this.basic.sex === 1 ? '男' : '女';
      let birYear = this.basic.birthday.split('-')[0];
      let nowYear = this.dateFormat(Date.parse(new Date()) / 1000).split('-')[0];
      this.basic.age = parseInt(nowYear) - parseInt(birYear);
      if (this.isNoJobTime) {
        this.basic.enter_job_time = '';
        this.basic.experience_cn = '无经验';
      } else {
        let jobYear = this.basic.enter_job_time.split('-')[0];
        let jobMonth = this.basic.enter_job_time.split('-')[1];
        let nowMonth = this.dateFormat(Date.parse(new Date()) / 1000).split('-')[1];
        let yearMinus = parseInt(nowYear) - parseInt(jobYear);
        if (yearMinus === 0) {
          let monthMinus = parseInt(nowMonth) - parseInt(jobMonth);
          if (monthMinus === 0) {
            this.basic.experience_cn = '无经验';
          } else {
            this.basic.experience_cn = `${monthMinus}月`;
          }
        } else {
          this.basic.experience_cn = `${yearMinus}年`;
        }
      }
      this.editBasic = false;
      this.basic = {
        fullname: this.basic.fullname,
        sex: this.basic.sex,
        sex_cn: this.basic.sex_cn,
        birthday: this.basic.birthday,
        education: this.basic.education,
        education_cn: this.basic.education_cn,
        enter_job_time: this.basic.enter_job_time,
        experience_cn: this.basic.experience_cn,
        photo_img: this.basic.photo_img,
        residence: this.basic.residence,
        marriage: this.basic.marriage,
        major1: this.basic.major1,
        major2: this.basic.major2,
        height: this.basic.height,
        householdaddress: this.basic.householdaddress
      };
      this.contact = {
        mobile: this.contact.mobile ? this.contact.mobile : '',
        weixin: this.contact.weixin ? this.contact.weixin : '',
        email: this.contact.email ? this.contact.email : '',
        qq: this.contact.qq ? this.contact.qq : ''
      };
    },
    // 保存当前求职状态
    saveCurrent() {
      if (!this.basic.current) {
        this.$message({
          message: '请选择求职意向',
          type: 'warning'
        });
        return false;
      }
      this.editCurrent = false;
    },
    // 保存求职意向
    saveIntention() {
      if (!this.intentionItem.nature) {
        this.$message({
          message: '请选择工作性质',
          type: 'warning'
        });
        return false;
      }
      if (!this.intentionItem.category1) {
        this.$message({
          message: '请选择期望职位',
          type: 'warning'
        });
        return false;
      }
      if (!this.intentionItem.district1) {
        this.$message({
          message: '请选择期望地区',
          type: 'warning'
        });
        return false;
      }
      if (!this.intentionItem.minwage || !this.intentionItem.maxwage) {
        this.$message({
          message: '请选择薪资',
          type: 'warning'
        });
        return false;
      }
      this.intentionItem.nature_text =
        this.optionNature.filter(item => item.id === this.intentionItem.nature)[0] !== undefined
          ? this.optionNature.filter(item => item.id === this.intentionItem.nature)[0].text
          : '';
      this.intentionItem.trade_text =
        this.optionTrade.filter(item => item.id === this.intentionItem.trade)[0] !== undefined ? this.optionTrade.filter(item => item.id === this.intentionItem.trade)[0].text : '';
      this.intentionItem.district1 = this.intentionItemDistrict[0];
      this.intentionItem.district2 = this.intentionItemDistrict[1];
      this.intentionItem.district3 = this.intentionItemDistrict[2];
      this.intentionItem.category1 = this.intentionItemCategory[0];
      this.intentionItem.category2 = this.intentionItemCategory[1];
      this.intentionItem.category3 = this.intentionItemCategory[2];
      this.intentionItem.wage_text = this.handleWageUnit(this.intentionItem.minwage, this.intentionItem.maxwage);

      let intentionObj = {
        id: this.intentionList.length,
        district1: this.intentionItem.district1,
        district2: this.intentionItem.district2,
        district3: this.intentionItem.district3,
        category1: this.intentionItem.category1,
        category2: this.intentionItem.category2,
        category3: this.intentionItem.category3,
        minwage: this.intentionItem.minwage,
        maxwage: this.intentionItem.maxwage,
        nature: this.intentionItem.nature,
        trade: this.intentionItem.trade
      };
      intentionObj.nature_text = this.intentionItem.nature_text;
      intentionObj.trade_text = this.intentionItem.trade_text;
      intentionObj.wage_text = this.intentionItem.wage_text;
      intentionObj.district_text = this.intentionItem.district_text;
      intentionObj.category_text = this.intentionItem.category_text;
      if (this.optionArr == 'add') {
        this.intentionList.push(intentionObj);
      }
      this.editIntention = false;
      this.intentionItem = {};
      this.intentionItemCategory = [];
      this.intentionItemDistrict = [];
      this.intentionItem.district_text = '';
      this.intentionItem.category_text = '';
    },
    // 处理薪资显示
    handleWageUnit(minWage, maxWage, negotiable = 0) {
      let wageText = '';
      let wageUnit = parseInt(this.$store.state.config.wage_unit); // 薪资显示类型
      if (negotiable === 0) {
        if (wageUnit === 1) {
          minWage = minWage % 1000 === 0 ? `${minWage / 1000}K` : `${(minWage / 1000).toFixed(1)}K`;
          maxWage = maxWage ? (maxWage % 1000 === 0 ? `${maxWage / 1000}K` : `${(maxWage / 1000).toFixed(1)}K`) : 0;
        } else if (wageUnit === 2) {
          if (minWage >= 10000) {
            if (minWage % 10000 === 0) {
              minWage = `${minWage / 10000}万`;
            } else {
              minWage = (minWage / 10000).toFixed(1);
              minWage = minWage.indexOf('.') > -1 ? minWage.replace('.', '万') : `${minWage}万`;
            }
          } else {
            if (minWage % 1000 === 0) {
              minWage = `${minWage / 1000}千`;
            } else {
              minWage = (minWage / 1000).toFixed(1);
              minWage = minWage.indexOf('.') > -1 ? minWage.replace('.', '千') : `${minWage}千`;
            }
          }
          if (maxWage >= 10000) {
            if (maxWage % 10000 === 0) {
              maxWage = `${maxWage / 10000}万`;
            } else {
              maxWage = (maxWage / 10000).toFixed(1);
              maxWage = maxWage.indexOf('.') > -1 ? maxWage.replace('.', '万') : `${maxWage}万`;
            }
          } else if (maxWage) {
            if (maxWage % 1000 === 0) {
              maxWage = `${maxWage / 1000}千`;
            } else {
              maxWage = (maxWage / 1000).toFixed(1);
              maxWage = maxWage.indexOf('.') > -1 ? maxWage.replace('.', '千') : `${maxWage}千`;
            }
          } else {
            maxWage = 0;
          }
        }
        if (maxWage === 0) {
          wageText = '面议';
        } else {
          if (minWage === maxWage) {
            wageText = minWage;
          } else {
            wageText = `${minWage}~${maxWage}`;
          }
        }
      } else {
        wageText = '面议';
      }
      return wageText;
    },
    // 保存特长标签
    saveTags() {
      this.editTags = false;
    },
    // 保存自我描述
    saveSpecialty() {
      if (!this.basic.specialty) {
        this.$message({
          message: '请填写自我描述',
          type: 'warning'
        });
        return false;
      }
      this.editSpecialty = false;
    },
    // 保存教育经历
    saveEducation() {
      if (!this.educationItem.school) {
        this.$message({
          message: '请填写学校名称',
          type: 'warning'
        });
        return false;
      }
      if (!this.educationItem.major) {
        this.$message({
          message: '请填写专业名称',
          type: 'warning'
        });
        return false;
      }
      if (!this.educationItem.starttime) {
        this.$message({
          message: '请选择入学时间',
          type: 'warning'
        });
        return false;
      }
      if (!this.educationItem.todate) {
        if (!this.educationItem.endtime) {
          this.$message({
            message: '请选择毕业时间',
            type: 'warning'
          });
          return false;
        }
      }
      if (!this.educationItem.education) {
        this.$message({
          message: '请选择学历',
          type: 'warning'
        });
        return false;
      }
      this.educationItem.education_text = this.optionEducation.filter(item => item.id === this.educationItem.education)[0].text;
      let edutionObj = {
        id: this.educationItem.id,
        school: this.educationItem.school,
        major: this.educationItem.major,
        major_text: this.educationItem.major_text,
        education: this.educationItem.education,
        education_text: this.educationItem.education_text,
        starttime: this.educationItem.starttime,
        endtime: (this.educationItem.endtime = this.educationItem.todate ? '' : this.educationItem.endtime),
        todate: this.educationItem.todate === true ? 1 : 0
      };
      this.educationItem = {};
      if (this.optionArr == 'add') {
        this.educationList.push(edutionObj);
      }
      this.doResetArray('resume_education', this.educationList);
      this.editEducation = false;
    },
    // 保存工作经历
    saveWork() {
      if (!this.workItem.companyname) {
        this.$message({
          message: '请填写公司名称',
          type: 'warning'
        });
        return false;
      }
      if (!this.workItem.jobname) {
        this.$message({
          message: '请填写职位名称',
          type: 'warning'
        });
        return false;
      }
      if (!this.workItem.starttime) {
        this.$message({
          message: '请选择入职时间',
          type: 'warning'
        });
        return false;
      }
      if (!this.workItem.todate) {
        if (!this.workItem.endtime) {
          this.$message({
            message: '请选择离职时间',
            type: 'warning'
          });
          return false;
        }
      }
      if (!this.workItem.duty) {
        this.$message({
          message: '请填写工作职责',
          type: 'warning'
        });
        return false;
      }
      let workObj = {
        id: this.workItem.id,
        companyname: this.workItem.companyname,
        jobname: this.workItem.jobname,
        duty: this.workItem.duty,
        starttime: this.workItem.starttime,
        endtime: (this.workItem.endtime = this.workItem.todate ? '' : this.workItem.endtime),
        todate: this.workItem.todate === true ? 1 : 0
      };
      if (this.optionArr == 'add') {
        this.workList.push(workObj);
      }
      this.doResetArray('resume_work', this.workList);
      this.workItem = {};
      this.editWork = false;
    },
    // 保存培训经历
    saveTrain() {
      if (!this.trainItem.agency) {
        this.$message({
          message: '请填写培训机构',
          type: 'warning'
        });
        return false;
      }
      if (!this.trainItem.course) {
        this.$message({
          message: '请填写培训课程',
          type: 'warning'
        });
        return false;
      }
      if (!this.trainItem.starttime) {
        this.$message({
          message: '请选择开始时间',
          type: 'warning'
        });
        return false;
      }
      if (!this.trainItem.todate) {
        if (!this.trainItem.endtime) {
          this.$message({
            message: '请选择结束时间',
            type: 'warning'
          });
          return false;
        }
      }
      if (!this.trainItem.description) {
        this.$message({
          message: '请填写培训内容',
          type: 'warning'
        });
        return false;
      }
      // 重复提交修改 zch 2022/9/16
      let trainObj = {
        id: this.trainItem.id,
        agency: this.trainItem.agency,
        course: this.trainItem.course,
        description: this.trainItem.description,
        starttime: this.trainItem.starttime,
        endtime: (this.trainItem.endtime = this.trainItem.todate ? '' : this.trainItem.endtime),
        todate: this.trainItem.todate === true ? 1 : 0
      };
      if (this.optionArr == 'add') {
        this.trainList.push(trainObj);
      }
      this.doResetArray('training_list', this.trainList);
      this.trainItem = {};
      this.editTrain = false;
    },
    // 保存项目经历
    saveProject() {
      if (!this.projectItem.projectname) {
        this.$message({
          message: '请填写项目名称',
          type: 'warning'
        });
        return false;
      }
      if (!this.projectItem.role) {
        this.$message({
          message: '请填写担任角色',
          type: 'warning'
        });
        return false;
      }
      if (!this.projectItem.starttime) {
        this.$message({
          message: '请选择开始时间',
          type: 'warning'
        });
        return false;
      }
      if (!this.projectItem.todate) {
        if (!this.projectItem.endtime) {
          this.$message({
            message: '请选择结束时间',
            type: 'warning'
          });
          return false;
        }
      }
      if (!this.projectItem.description) {
        this.$message({
          message: '请填写项目描述',
          type: 'warning'
        });
        return false;
      }
      let projectObj = {
        id: this.projectItem.id,
        projectname: this.projectItem.projectname,
        role: this.projectItem.role,
        description: this.projectItem.description,
        starttime: this.projectItem.starttime,
        endtime: (this.projectItem.endtime = this.projectItem.todate ? '' : this.projectItem.endtime),
        todate: this.projectItem.todate === true ? 1 : 0
      };
      if (this.optionArr == 'add') {
        this.projectList.push(projectObj);
      }
      this.doResetArray('project_list', this.projectList);
      this.projectItem = {};
      this.editProject = false;
    },
    // 保存获得证书
    saveCertificate() {
      if (!this.certificateItem.name) {
        this.$message({
          message: '请填写证书名称',
          type: 'warning'
        });
        return false;
      }
      if (!this.certificateItem.obtaintime) {
        this.$message({
          message: '请选择获得时间',
          type: 'warning'
        });
        return false;
      }
      let certificateObj = {
        id: this.certificateItem.id,
        name: this.certificateItem.name,
        obtaintime: this.certificateItem.obtaintime
      };
      if (this.optionArr == 'add') {
        this.certificateList.push(certificateObj);
      }
      this.doResetArray('certificate_list', this.certificateList);
      this.certificateItem = {};
      this.editCertificate = false;
    },
    // 选择语种
    choiceLanguage(visible) {
      if (visible) {
        this.languageOldList = JSON.parse(JSON.stringify(this.languageList));
      }
    },
    // 保存语言能力
    saveLanguage() {
      if (!this.languageItem.language) {
        this.$message({
          message: '请选择语种',
          type: 'warning'
        });
        return false;
      }
      if (this.languageList.some(item => item.language === this.languageItem.language && item.id !== this.languageItem.id)) {
        this.$message({
          message: '已有该语种',
          type: 'warning'
        });
        return false;
      }
      if (!this.languageItem.level) {
        this.$message({
          message: '请选择熟悉程度',
          type: 'warning'
        });
        return false;
      }
      this.languageItem.language_text = this.optionLanguage.filter(item => item.id === this.languageItem.language)[0].text;
      this.languageItem.level_text = this.optionLanguageLevel.filter(item => item.id === this.languageItem.level)[0].text;

      let languageObj = {
        id: this.languageItem.id,
        language: this.languageItem.language,
        language_text: this.languageItem.language_text,
        level: this.languageItem.level,
        level_text: this.languageItem.level_text
      };
      if (this.optionArr == 'add') {
        this.languageList.push(languageObj);
      }
      this.doResetArray('languageList', this.languageList);
      this.languageItem = {};
      this.languageItem.language_text = '';
      this.languageItem.level_text = '';
      this.editLanguage = false;
    },
    // 删除信息公用方法
    delItemFun(url, id, list) {
      this.$confirm('此操作将永久删除该条信息, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          list.splice(list.findIndex(item => item.id === id), 1);
        })
        .catch(err => {
          console.log(err);
        });
    },
    // 格式化日期
    dateFormat(time) {
      return time ? parseTime(time, '{y}-{m}') : '';
    },
    // 监听页面滚动
    getScrollTop() {
      let scrollTop = 0;
      if (document.documentElement && document.documentElement.scrollTop) {
        scrollTop = document.documentElement.scrollTop;
      } else if (document.body) {
        scrollTop = document.body.scrollTop;
      }
      this.isRoll = scrollTop >= 114;
    },
    // 微信号同手机号
    syncWxMobile() {
      if (this.checkedEditPhone) {
        this.contact.weixin = this.contact.mobile;
      }
    },
    /**
     * 生成薪资分类
     * @param type
     * @param minValue
     * @returns {[]}
     */
    makeWageOption(type, minValue) {
      let max;
      if (type === 'min') {
        this.optionMinWage = [];
        max = this.$store.state.maxWage - 5000;
      } else {
        this.optionMaxWage = [];
        max = this.$store.state.maxWage;
      }

      let wageArray = [];
      let current = minValue;
      let unit1 = 500;
      let unit2 = 5000;
      while (current <= max) {
        if (type === 'min') {
          this.optionMinWage.push(current);
        } else {
          this.optionMaxWage.push(current);
        }
        if (current < 15000) {
          current += unit1;
        } else {
          current += unit2;
        }
      }
      if (type === 'max') {
        this.intentionItem.maxwage = this.optionMaxWage[0];
      }
      return wageArray;
    },
    // 薪资级联
    changeMinWage(minWage) {
      this.makeWageOption('max', minWage >= 15000 ? minWage + 5000 : minWage + 500);
    },
    changeMaxWage() {
      this.$forceUpdate();
    },
    // 选择职位和地区分类
    handleValues(value) {
      this.makeUpValue(2, JSON.parse(JSON.stringify(value[0]))).forEach((item, index) => {
        if (value[2] === 'basic') {
          this.basic[`${value[1]}${index + 1}`] = item;
        } else if (value[2] === 'intention') {
          this.intentionItem[`${value[1]}${index + 1}`] = item;
        }
      });
      this.handleLabels(value[1]);
    },
    // 选择地区或职位分类之后处理选中值
    makeUpValue(length, value) {
      return [...Array(length)].map((e, i) => (value[i] > 0 ? value[i] : 0));
    },
    // 处理选中节点
    handleLabels(type) {
      if (type === 'district') {
        setTimeout(() => {
          this.intentionItem.district_text = this.$refs['intDistrict'].inputValue;
        }, 300);
      } else if (type === 'category') {
        setTimeout(() => {
          let categoryLabel = this.$refs['intCategory'].inputValue.split(' / ');
          this.intentionItem.category_text = categoryLabel[categoryLabel.length - 1];
        }, 300);
      } else if (type === 'major') {
        setTimeout(() => {
          let majorLabel = this.$refs['intMajor'].inputValue.split(' / ');
          this.basic.major_text = majorLabel[majorLabel.length - 1];
        }, 300);
      }
    },
    //创建在线简历
    saveResumeDetail() {
      if (this.basic.fullname == '') {
        this.$message.error('请完善基本信息中的姓名！');
        return;
      }
      if (this.basic.sex == '') {
        this.$message.error('请完善基本信息中的性别！');
        return;
      }
      if (this.basic.age == '') {
        this.$message.error('请完善基本信息中的年龄！');
        return;
      }
      if (this.basic.mobile == '') {
        this.$message.error('请完善基本信息中的手机号！');
        return;
      }
      if (this.basic.education == '') {
        this.$message.error('请完善基本信息中的最高学历！');
        return;
      }
      if (this.basic.experience == '') {
        this.$message.error('请完善基本信息中的开始工作时间！');
        return;
      }
      if (!this.basic.current) {
        this.$message.error('请完善求职状态！');
        return;
      }
      //特长标签数据重构
      let tagSelectLists = [];
      for (let i = 0; i < this.selectedTags.length; i++) {
        tagSelectLists.push(this.selectedTags[i].text);
      }
      this.basic.tag = tagSelectLists
      if (this.intentionList.length < 1) {
        this.$message.error('请完善求职意向！');
        return;
      }
      if (this.intentionList.length > 0) {
        for (let i in this.intentionList) {
          if (this.intentionList[i].incomplete) {
            let j = i + 1;
            this.$message.error('请完善第' + j + '条求职意向信息！');
            return;
          }
        }
      }
      if (this.educationList.length > 0) {
        for (let i in this.educationList) {
          this.educationList[i].todate = this.workList[i].todate ? 1 : 0;
          if (this.educationList[i].incomplete) {
            let j = i + 1;
            this.$message.error('请完善第' + j + '条教育经历！');
            return;
          }
        }
      }
      if (this.workList.length > 0) {
        for (let i in this.workList) {
          this.workList[i].todate = this.workList[i].todate ? 1 : 0;
          if (this.workList[i].incomplete) {
            let j = i + 1;
            this.$message.error('请完善第' + j + '条工作经历！');
            return;
          }
        }
      }
      if (this.trainList.length > 0) {
        for (let i in this.trainList) {
          this.trainList[i].todate = this.trainList[i].todate ? 1 : 0;
          if (this.trainList[i].incomplete) {
            let j = i + 1;
            this.$message.error('请完善第' + j + '条培训经历！');
            return;
          }
        }
      }
      if (this.projectList.length > 0) {
        for (let i in this.projectList) {
          this.projectList[i].todate = this.projectList[i].todate ? 1 : 0;
          if (this.projectList[i].incomplete) {
            let j = i + 1;
            this.$message.error('请完善第' + j + '条项目经历！');
            return;
          }
        }
      }
      if (this.certificateList.length > 0) {
        for (let i in this.certificateList) {
          if (this.certificateList[i].incomplete) {
            let j = i + 1;
            this.$message.error('请完善第' + j + '条证书信息！');
            return;
          }
        }
      }
      if (this.languageList.length > 0) {
        for (let i in this.languageList) {
          if (this.languageList[i].incomplete) {
            let j = i + 1;
            this.$message.error('请完善第' + j + '条证书信息！');
            return;
          }
        }
      }
      http
        .post(api.importResumeCreate, {
          basic: this.basic,
          contact: this.contact,
          intention_list: this.intentionList,
          education_list: this.educationList,
          work_list: this.workList,
          training_list: this.trainList,
          project_list: this.projectList,
          certificate_list: this.certificateList,
          language_list: this.languageList,
          img_list: this.imgList,
          enclosure_id: this.enclosure_id,
        })
        .then(res => {
          if (res.code == 200) {
            this.$message.success(res.message);
            localStorage.setItem('resumeDetail','')
            this.$router.push('/personal/resume');
          }
        })
        .catch(() => {});
    },
    resetResumeDetail() {
      this.$router.go(-1);
    }
  }
};
</script>

<style lang="scss" scoped>
.my_resume {
  width: 1200px;
  margin-top: 15px;

  .saveResumeDetail {
    width: 100%;
    position: fixed;
    bottom: 0;
    left: 0;
    height: 100px;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;

    .btn {
      width: 166px;
      height: 45px;
      text-align: center;
      background: #fff;
      color: #409eff;
      font-size: 12px;
      border-radius: 4px;
      cursor: pointer;

      &.sureUpload {
        background: #409eff;
        color: #f3f3f3;
        font-size: 16px;
        line-height: 45px;
        margin-left: 50px;
      }

      .text1 {
        display: block;
        width: 100%;
        text-align: center;
        margin-top: 6px;
      }

      .text2 {
        display: block;
        width: 100%;
        text-align: center;
        font-size: 16px;
      }
    }
  }

  .r_left {
    // float: left;
    background-color: #fff;
    width: 100%;

    .l_title {
      position: relative;
      margin-bottom: 20px;

      .t_name {
        float: left;
        font-size: 22px;
        color: #333;
      }

      .t_tip {
        float: left;
        font-size: 12px;
        color: #c9c9c9;
        height: 28px;
        line-height: 28px;
        margin-left: 12px;
      }

      .req {
        float: left;
        color: #ff4a3c;
        font-size: 16px;
        border-radius: 3px;
        padding: 0 5px;
      }

      .tobeimproved {
        float: left;
        width: 50px;
        height: 19px;
        background: url('../../assets/images/personal/resume/tobeimproved.png') left center no-repeat;
        background-size: 100% 100%;
        margin: 5px 0 0 5px;
      }

      .add {
        position: absolute;
        right: 0;
        top: 5px;
        color: #1787fb;
        font-size: 14px;
        padding-left: 22px;
        background: url('../../assets/images/personal/resume/5.png') 0 center no-repeat;
        cursor: pointer;
      }

      .ed {
        position: absolute;
        right: 0;
        top: 5px;
        font-size: 14px;
        color: #666;
        padding-left: 21px;
        cursor: pointer;
        background: url('../../assets/images/personal/resume/6.png') 0 center no-repeat;
        background-size: 14px;
      }

      .t_scan {
        float: right;
        font-size: 12px;
        color: #02bb29;
        height: 28px;
        line-height: 28px;
        padding-left: 23px;
        position: relative;
        background: url('../../assets/images/personal/resume/14.png') 0 center no-repeat;
        cursor: pointer;

        &:hover {
          .codeImg {
            display: block;
          }
        }

        .codeImg {
          background-color: #fff;
          width: 180px;
          height: 240px;
          border: 1px solid #eeeeee;
          position: absolute;
          right: -200px;
          top: -120px;
          text-align: center;
          font-size: 12px;
          z-index: 10;
          display: none;

          p {
            line-height: 18px;
          }

          img {
            width: 150px;
            height: 150px;
            margin: 19px 0 10px 0;
          }

          em,
          span {
            font-style: normal;
            font-size: 30px;
            position: absolute;
            left: -10px;
            top: 108px;
            color: #efefef;
          }

          span {
            left: -8px;
            color: white;
          }
        }
      }
    }

    .l_add {
      width: 100%;
      height: 100px;
      text-align: center;
      background-color: #fafafa;
      cursor: pointer;
      padding-top: 23px;

      .la_title {
        font-size: 18px;
        color: #1787fb;
        width: 105px;
        margin: 0 auto 12px;
        padding-left: 30px;
        background: url('../../assets/images/personal/resume/7.png') 0 center no-repeat;
        background-size: 20px;
      }

      .la_des {
        font-size: 12px;
        color: #999;
      }
    }

    .bit_hand {
      position: relative;

      .ed {
        position: absolute;
        right: 77px;
        top: 2px;
        color: #666;
        padding-left: 21px;
        cursor: pointer;
        display: none;
        background: url('../../assets/images/personal/resume/6.png') 0 center no-repeat;
        background-size: 14px;
      }

      .del {
        position: absolute;
        right: 0;
        top: 2px;
        color: #666;
        padding-left: 21px;
        cursor: pointer;
        display: none;
        background: url('../../assets/images/personal/resume/8.png') 0 center no-repeat;
      }

      &:hover {
        .ed,
        .del {
          display: block;
        }
      }
    }

    .b1 {
      padding: 37px 30px 40px 35px;
      position: relative;

      .b_time {
        position: absolute;
        right: 30px;
        top: 44px;
        font-size: 12px;
      }

      .a_scan {
        font-size: 12px;
        color: #1787fb;
        position: absolute;
        left: 40px;
        bottom: 10px;
        padding-left: 18px;
        cursor: pointer;
        background: url('../../assets/images/personal/resume/15.png') 0 center no-repeat;
        background-size: 14px;

        &:hover {
          .codeImg {
            display: block;
          }
        }

        .codeImg {
          background-color: #fff;
          width: 180px;
          height: 240px;
          border: 1px solid #eeeeee;
          position: absolute;
          right: -200px;
          top: -120px;
          text-align: center;
          font-size: 12px;
          z-index: 3;
          display: none;

          p {
            line-height: 18px;
          }

          img {
            width: 150px;
            height: 150px;
            margin: 19px 0 10px 0;
          }

          em,
          span {
            font-style: normal;
            font-size: 30px;
            position: absolute;
            left: -10px;
            top: 108px;
            color: #efefef;
          }

          span {
            left: -8px;
            color: white;
          }
        }
      }

      .con {
        position: relative;
        padding-left: 130px;

        .ava_box {
          position: absolute;
          left: 0;
          top: 3px;
          width: 100px;
          height: 100px;
          border-radius: 100%;
          z-index: 1;

          .img {
            width: 100px;
            height: 100px;
            border-radius: 100%;
          }

          .up {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
            height: 100px;
            border-radius: 100%;
            z-index: 2;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.4) url('../../assets/images/personal/resume/4.png') center center no-repeat;
          }
        }

        .name {
          margin-bottom: 18px;

          .n_t {
            float: left;
            font-size: 24px;
            color: #333;

            a {
              font-size: 24px;
              color: #333;
            }
          }
        }

        .ed {
          position: absolute;
          right: 0;
          top: 7px;
          color: #1787fb;
          padding-left: 21px;
          cursor: pointer;
          background: url('../../assets/images/personal/resume/1.png') 0 center no-repeat;
          background-size: 14px;
        }

        .ot {
          margin-bottom: 16px;

          .ot_item {
            position: relative;
            float: left;
            color: #333;

            &:not(:first-child) {
              padding-left: 20px;

              &::before {
                content: '';
                position: absolute;
                left: 10px;
                top: 4px;
                width: 1px;
                height: 10px;
                border-left: 1px solid #e2e2e2;
              }
            }
          }
        }

        .ct {
          .ct_item {
            float: left;
            padding-left: 25px;
            font-size: 12px;
            margin-right: 17px;
            position: relative;
            color: #999;
            height: 16px;
            line-height: 16px;

            &.phone {
              &::after {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                width: 16px;
                height: 16px;
                border-radius: 100%;
                background: #5c9aef url('../../assets/images/personal/resume/2.png') center center no-repeat;
                background-size: 10px;
              }
            }

            &.wx {
              &::after {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                width: 16px;
                height: 16px;
                border-radius: 100%;
                background: #18c24c url('../../assets/images/personal/resume/3.png') center center no-repeat;
                background-size: 11px;
              }
            }
          }
        }
      }
    }

    .b2 {
      padding: 37px 0 39px 0;
      &.noHaveData {
        background: #fffcf9;
        border: 1px dashed #ffebd2;
        padding: 20px;
        border-radius: 6px;
        margin-top: 37px;
        .fill_group {
          background: #fffcf9;
          padding-top: 0;
          .fill_line {
            padding-left: 0;
            ::v-deep .el-input__inner {
              background: #fffcf9;
            }
            .intentionWarn {
              font-size: 14px;
              color: #999;
              box-sizing: border-box;
              padding-left: 14px;
              margin-top: 10px;
            }
            .f_item_top {
              margin-bottom: 0;
              .i_label {
                width: 90px;
              }
            }
          }
        }
      }

      .cur {
        position: relative;
        font-size: 14px;
        color: #333;
        margin-bottom: 33px;

        .ed {
          position: absolute;
          right: 0;
          top: 0;
          font-size: 14px;
          color: #666;
          padding-left: 21px;
          cursor: pointer;
          background: url('../../assets/images/personal/resume/6.png') 0 center no-repeat;
          background-size: 14px;
          display: none;
        }

        &:hover {
          .ed {
            display: block;
          }
        }
      }

      .bit {
        .j_name {
          font-size: 16px;
          color: #333;
          margin-bottom: 20px;
        }

        .j_line {
          display: flex;

          .l_i {
            flex: 1;
          }

          &:not(:last-child) {
            margin-bottom: 10px;
          }
        }

        &:not(:last-child) {
          margin-bottom: 30px;
        }
      }
    }

    .b3 {
      padding: 37px 0 15px 0;
      border-top: 1px solid #f3f3f3;
      &.noHaveData {
        background: #fffcf9;
        border: 1px dashed #ffebd2;
        padding: 20px;
        border-radius: 6px;
        margin-top: 37px;
        .t_name {
          padding-left: 14px;
        }
        .intentionWarn {
          font-size: 14px;
          color: #999;
          box-sizing: border-box;
          padding-left: 14px;
          margin-top: 10px;
        }
      }

      .fill_group {
        padding: 0 15px;

        .gi {
          cursor: pointer;
        }
      }

      .tg {
        margin-bottom: 10px;

        .gi {
          float: left;
          margin: 0 10px 10px 0;
          font-size: 14px;
          color: #666;
          border: 1px solid #f3f3f3;
          border-radius: 3px;
          padding: 6px 13px;
          background-color: #fff;

          &.active,
          &:hover {
            background-color: #fdf6ec;
            border: 1px solid #faecd8;
            color: #e6a75d;
          }
        }
      }

      .tg_add {
        position: relative;
        width: 226px;
        height: 40px;
        border-radius: 3px;

        .el-input__inner {
          border-radius: 4px 0 0 4px;
          border-right: 0;
        }

        .el-button {
          border-radius: 0 4px 4px 0;
        }
      }
    }

    .b4 {
      padding: 37px 0 40px 0;
      border-top: 1px solid #f3f3f3;
      &.noHaveData {
        background: #fffcf9;
        border: 1px dashed #ffebd2;
        padding: 20px;
        border-radius: 6px;
        margin-top: 37px;
        .t_name {
          padding-left: 14px;
        }
        .intentionWarn {
          font-size: 14px;
          color: #999;
          box-sizing: border-box;
          padding-left: 14px;
          margin-top: 10px;
        }
      }

      .b_con {
        color: #666;
        word-break: break-all;
        line-height: 1.7;
      }
    }

    .b5 {
      padding: 37px 0 40px 0;
      border-top: 1px solid #f3f3f3;
      &.noHaveData {
        background: #fffcf9;
        border: 1px dashed #ffebd2;
        padding: 20px;
        border-radius: 6px;
        margin-top: 37px;
        .intentionWarn {
          font-size: 14px;
          color: #999;
          box-sizing: border-box;
          margin-top: 10px;
        }
      }

      .bit {
        padding-left: 158px;
        &.itemList {
          position: relative;
        }

        .incompleteWarn {
          width: 193px;
          height: 30px;
          line-height: 30px;
          background: #fff8f7 url('../../assets/images/personal/resume/warn.png') 10px center no-repeat;
          background-size: 15px 15px;
          border: 1px solid #ff8a81;
          border-radius: 15px;
          color: #ff4a3c;
          font-size: 12px;
          position: absolute;
          bottom: -33px;
          left: 159px;
          box-sizing: border-box;
          padding-left: 33px;
        }

        &:not(:last-child) {
          margin-bottom: 35px;
        }

        .e_time {
          position: absolute;
          left: 0;
          top: 1.5px;
          font-size: 14px;
          color: #999;
        }

        &::before {
          content: '';
          position: absolute;
          left: 132px;
          top: 7px;
          width: 8px;
          height: 8px;
          border-radius: 100%;
          background-color: #f3f3f3;
        }

        &::after {
          content: '';
          position: absolute;
          left: 135px;
          top: 20px;
          width: 1px;
          height: 90%;
          border-left: 1px solid #f3f3f3;
        }

        .e_t1 {
          font-size: 16px;
          color: #333;
          margin-bottom: 15px;
        }

        .e_t2 {
          font-size: 14px;
          color: #333;
        }

        .e_t3 {
          font-size: 12px;
          color: #999;
          line-height: 1.8;
          word-break: break-all;
          padding-top: 13px;
        }
      }
    }

    .b6 {
      padding: 37px 0 30px 0;
      border-top: 1px solid #f3f3f3;
      &.noHaveData {
        background: #fffcf9;
        border: 1px dashed #ffebd2;
        padding: 20px;
        border-radius: 6px;
        margin-top: 37px;
        .intentionWarn {
          font-size: 14px;
          color: #999;
          box-sizing: border-box;
          margin-top: 10px;
        }
      }

      .bit {
        &:not(:last-child) {
          margin-bottom: 15px;
        }

        &.itemList {
          position: relative;
        }

        .incompleteWarn {
          width: 193px;
          height: 30px;
          line-height: 30px;
          background: #fff8f7 url('../../assets/images/personal/resume/warn.png') 10px center no-repeat;
          background-size: 15px 15px;
          border: 1px solid #ff8a81;
          border-radius: 15px;
          color: #ff4a3c;
          font-size: 12px;
          position: absolute;
          bottom: -33px;
          left: 159px;
          box-sizing: border-box;
          padding-left: 33px;
        }

        .b_i {
          font-size: 14px;
          color: #55a3ff;
          border: 1px solid #d9ecff;
          width: fit-content;
          padding: 6px 15px;
          background-color: #ecf5ff;
          border-radius: 4px;

          .date {
            margin-left: 25px;
            position: relative;

            &::before {
              content: '';
              position: absolute;
              left: -15px;
              top: 50%;
              width: 1px;
              height: 15px;
              border-left: 1px solid #c6deff;
              transform: translate(0, -50%);
            }
          }
        }

        .ed,
        .del {
          top: 7px;
        }
      }
    }

    .b7 {
      padding: 37px 0 30px 0;
      border-top: 1px solid #f3f3f3;
      &.noHaveData {
        background: #fffcf9;
        border: 1px dashed #ffebd2;
        padding: 20px;
        border-radius: 6px;
        margin-top: 37px;
        .intentionWarn {
          font-size: 14px;
          color: #999;
          box-sizing: border-box;
          margin-top: 10px;
        }
      }

      .i_box {
        .i_t {
          position: relative;
          float: left;
          width: 110px;
          height: 110px;
          margin: 0 20px 0 0;

          img {
            width: 110px;
            height: 110px;
            border: 0;
          }

          .i_del {
            position: absolute;
            right: -9px;
            top: -9px;
            width: 22px;
            height: 22px;
            background: rgba(0, 0, 0, 0.3);
            cursor: pointer;
            border-radius: 100%;

            &::before {
              content: '';
              position: absolute;
              left: 5px;
              top: 10px;
              width: 12px;
              height: 1px;
              border-top: 1px solid #fff;
              transform: rotate(45deg);
            }

            &::after {
              content: '';
              position: absolute;
              left: 10px;
              top: 5px;
              width: 1px;
              height: 12px;
              border-left: 1px solid #fff;
              transform: rotate(45deg);
            }
          }

          &.up {
            border: 1px solid #e2e2e2;
            background: #fff url('../../assets/images/personal/resume/9.png') center center no-repeat;
            cursor: pointer;
          }
        }
      }
    }

    .fill_group {
      background-color: #fbfbfb;
      padding-top: 8px;

      .fill_bw {
        background-color: #fff;
        padding-bottom: 8px;
      }

      .fill_bgr {
        background-color: #fbfbfb;
        padding-top: 18px;
      }

      .fill_bm {
        margin-bottom: 15px;
      }

      .area_box {
        padding: 40px 30px 0 30px;
      }

      &.fill_al {
        padding-top: 25px;
      }

      .bgr_title {
        position: relative;
        font-size: 12px;
        color: #999;
        text-align: center;
        width: 195px;
        margin: 0 auto 20px;

        &::before {
          content: '';
          position: absolute;
          left: -230px;
          top: 7px;
          width: 230px;
          height: 1px;
          border-top: 1px solid #f3f3f3;
        }

        &::after {
          content: '';
          position: absolute;
          right: -230px;
          top: 7px;
          width: 230px;
          height: 1px;
          border-top: 1px solid #f3f3f3;
        }
      }

      &.for_bw {
        background-color: #fff;
      }

      .fill_line {
        padding-left: 100px;

        .f_item {
          display: inline-block;
          width: 50%;
          margin-bottom: 22px;

          &.al {
            width: 100%;
          }

          .i_label {
            float: left;
            padding: 11px 10px 11px 0;
            text-align: right;
            width: 110px;

            .req {
              color: #ff0000;
            }
          }

          .i_input {
            .input_radio {
              padding-top: 11px;
            }

            float: left;
            position: relative;

            .el-input {
              width: 196px;
            }

            .el-checkbox__label {
              font-size: 12px;
            }

            .g_type {
              float: left;
              width: 76px;
              color: #999;
              padding: 11px 0 11px 39px;
              margin-right: 15px;
              border-radius: 40px;
              cursor: pointer;

              &.male {
                background: #f8f8f8 url('../../assets/images/member/4.png') 16px center no-repeat;
                background-size: 13px;
              }

              &.female {
                background: #f8f8f8 url('../../assets/images/member/6.png') 16px center no-repeat;
                background-size: 13px;
              }

              &.active {
                color: #1787fb;

                &.male {
                  background: #ebf5ff url('../../assets/images/member/5.png') 16px center no-repeat;
                  background-size: 13px;
                }

                &.female {
                  background: #ebf5ff url('../../assets/images/member/7.png') 16px center no-repeat;
                  background-size: 13px;
                }
              }
            }

            .for_after {
              margin-left: 10px;
            }
          }
        }
      }

      .fill_btn {
        padding: 18px 0 45px;
        text-align: center;

        .el-button {
          width: 115px;
        }
      }
    }
  }

  .r_right {
    position: fixed;
    top: 129px;
    left: 50%;
    width: 280px;
    z-index: 9;
    margin-left: 320px;

    &.roll {
      top: 0;
    }

    .t1 {
      background-color: #fff;
      padding: 26px 30px 25px 30px;
      border-bottom: 1px solid #f3f3f3;

      .p_num {
        font-size: 14px;
        color: #333;
        margin-bottom: 18px;
      }

      .blue {
        color: #1787fb;
      }

      .p_per {
      }
    }

    .t2 {
      background-color: #fff;
      padding: 20px 20px 0 20px;

      .s_item {
        position: relative;
        width: 100%;
        height: 34px;
        line-height: 34px;
        background-color: #fff;
        font-size: 14px;
        color: #666;
        padding: 0 10px;
        display: block;

        &:not(:first-child) {
          // margin-top: 5px;
        }

        .r_txt {
          position: absolute;
          right: 10px;
          top: 0;
        }

        .r_finish {
          position: absolute;
          right: 10px;
          top: 9px;
          width: 16px;
          height: 16px;
          background: url('../../assets/images/personal/resume/10.png') 0 0 no-repeat;
        }

        &.scr,
        &:hover {
          background-color: #f6fafe;
          color: #0a79eb;
        }

        &:hover {
          text-decoration: none;
        }

        .el-switch {
          position: absolute;
          right: 10px;
          top: 7px;
        }
      }

      .s_btn_g {
        display: flex;
        justify-content: space-between;

        &.btn1 {
          padding: 16px 0;
        }

        &.btn2 {
          padding: 0 0 40px;
        }

        .s_btn {
          flex: 1;
          height: 40px;
          padding-left: 94px;
          line-height: 40px;
          color: #fff;
          font-size: 14px;
          border-radius: 4px;
          cursor: pointer;

          &.view {
            background: #e6a23c;

            &:hover {
              background: #d89838;
            }
          }

          &.index {
            background: #1787fb;

            &:hover {
              background: #1681ef;
            }
          }
        }
      }
    }

    .t3 {
      margin-top: 10px;
      background: url('../../assets/images/personal/resume/13.png') 0 0 no-repeat;
      width: 100%;
      height: 190px;
      cursor: pointer;
    }
  }
}
</style>
