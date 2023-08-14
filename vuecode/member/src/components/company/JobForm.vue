<template>
    <div>
        <el-form label-width="100px" label-position="right" :model="form" ref="form">
      <el-card>
        <company-title>{{type=='add'?'发布职位':'修改职位'}}</company-title>
        <div class="Tips_text" v-if="type=='add'" >
          <img src="../../assets/images/warning_icon.png"/>
          亲爱的HR，您的帐号可同时发布 {{enable_addjob_num_total}} 个职位，现已发布 {{enable_addjob_num_total-enable_addjob_num}} 个职位。
        </div>
        <div class="form_title">
          基本信息
        </div>
          <el-form-item label="职位名称"
            prop="basic.jobname"
            :rules="[
            {
              required: true,
              message: '请输入职位名称',
              trigger: 'blur'
            },
            {
              max: 60,
              message: '长度在 1 到 30 个字符',
              trigger: 'blur'
            }
          ]"
          >
          <el-input class="name_width" v-model="form.basic.jobname" placeholder="请输入职位名称"></el-input>
          </el-form-item>
          <el-form-item label="职位类别"
            prop="basic.jobcategory_arr"
            :rules="[
            {
              required: true,
              message: '请选择职位类别',
              trigger: 'change'
            }
          ]">
          <el-cascader
            v-if="columnsJobcategory.length>0"
            class="name_width"
            v-model="form.basic.jobcategory_arr"
            :options="columnsJobcategory"
            :show-all-levels="false"
            filterable
          ></el-cascader>
          </el-form-item>

          <div class="form_item">
            <el-form-item label="经验要求">
            <el-select v-model="form.basic.experience">
              <el-option
                v-for="item in columnsExperience"
                :key="item.id"
                :label="item.text"
                :value="item.id"
              >
              </el-option>
            </el-select>
            </el-form-item>
            <el-form-item label="学历要求">
            <el-select v-model="form.basic.education">
              <el-option
                v-for="item in columnsEducation"
                :key="item.id"
                :label="item.text"
                :value="item.id"
              >
              </el-option>
            </el-select>
            </el-form-item>
            <el-form-item
              label="招聘人数"
              v-if="field_rule.basic.amount.is_display == 1"
            >
            <el-input placeholder="若干" v-model.number="form.basic.amount" type="number"></el-input>
            </el-form-item>
          </div>
          <el-form-item
            class="is-required"
            label="薪资待遇"
            prop="basic.minwage"
            :rules="[
              {
                validator: validateWage,
                trigger: 'change'
              }
            ]">
          <el-select
            :disabled="form.basic.negotiable !== false"
            v-model="form.basic.minwage"
            placeholder="请选择"
            class="salary_width"
            @change="handlerMinwageChange"
          >
            <el-option
              v-for="(item, index) in columnsMinWage"
              :key="index"
              :label="item.name"
              :value="item.id"
            ></el-option>
          </el-select>
          -
          <el-select
            :disabled="form.basic.negotiable !== false"
            v-model="form.basic.maxwage"
            placeholder="请选择"
            class="salary_width"
          >
            <el-option
              v-for="(item, index) in options_maxwage"
              :key="index"
              :label="item.name"
              :value="item.id"
            ></el-option>
          </el-select>
           <el-checkbox v-if="field_rule.basic.negotiable.is_display == 1" class="checkbox_mg" v-model="form.basic.negotiable">面议</el-checkbox>
          </el-form-item>
          <el-form-item
            label="工作地区"
            prop="basic.citycategory_arr"
            :rules="[
            {
              required: true,
              message: '请选择工作地区',
              trigger: 'change'
            }
          ]">
          <el-cascader
            v-if="columnsCitycategory.length>0"
            v-model="form.basic.citycategory_arr"
            :options="columnsCitycategory"
            :show-all-levels="false"
          ></el-cascader>
          <el-input class="region_width" v-model="form.basic.address" placeholder="请标注详细地址"></el-input>  <img class="taggingImg" src="../../assets/images/tagging.png" @click="handlerShowMap"/>  <span @click="handlerShowMap">标注</span>
          </el-form-item>

          <el-form-item
            prop="basic.content"
            label="职位描述"
            :rules="[
              {
                required: true,
                message: '请填写职位描述',
                trigger: 'blur'
              }
            ]">
              <el-input class="textField_width" type="textarea" v-model="form.basic.content" :rows="8"></el-input>
          </el-form-item>
      </el-card>
      <el-card>
        <div class="form_title">
          其他信息
        </div>
          <el-form-item
            prop="basic.tag"
            :label="field_rule.basic.tag.field_cn"
            v-if="field_rule.basic.tag.is_display == 1"
            :rules="[
              {
                required: field_rule.basic.tag.is_require == 1?true:false,
                message: '请选择'+field_rule.basic.tag.field_cn,
                trigger: 'change'
              }
            ]"
          >
            <el-select
              class="name_width"
              v-model="form.basic.tag"
              multiple
              filterable
              allow-create
              default-first-option
              :multiple-limit="10"
              placeholder="请选择"
            >
              <el-option
                v-for="(item, index) in columnsTag"
                :key="index"
                :label="item.text"
                :value="item.id"
              >
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item
            prop="basic.department"
            :label="field_rule.basic.department.field_cn"
            v-if="field_rule.basic.department.is_display == 1"
            :rules="[
              {
                required: field_rule.basic.department.is_require == 1?true:false,
                message: '请填写'+field_rule.basic.department.field_cn,
                trigger: 'blur'
              },
              {
                max: 60,
                message: '长度在 1 到 60 个字符',
                trigger: 'blur'
              }
            ]"
          >
            <el-input  class="name_width" v-model="form.basic.department" placeholder="请填写"></el-input>
          </el-form-item>
          <el-form-item
            prop="basic.age"
            :label="field_rule.basic.age.field_cn"
            v-if="field_rule.basic.age.is_display == 1"
            :class="field_rule.basic.age.is_require == 1?'is-required':''"
            :rules="field_rule.basic.age.is_require == 1?[
              {
                validator: validateAge,
                trigger: 'change'
              }
            ]:[]"
          >
                <el-select
                  :disabled="form.basic.age_na !== false"
                  v-model="form.basic.minage"
                  placeholder="请选择"
                  class="salary_width"
                  @change="handlerMinageChange"
                >
                  <el-option
                    v-for="(item, index) in columnsMinAge"
                    :key="index"
                    :label="item.name"
                    :value="item.id"
                  ></el-option>
                </el-select>
              -
                <el-select
                  :disabled="form.basic.age_na !== false"
                  v-model="form.basic.maxage"
                  placeholder="请选择"
                  class="salary_width"
                >
                  <el-option
                    v-for="(item, index) in options_maxage"
                    :key="index"
                    :label="item.name"
                    :value="item.id"
                  ></el-option>
                </el-select>
              <el-checkbox class="checkbox_mg" v-model="form.basic.age_na">不限</el-checkbox>
          </el-form-item>
          <el-form-item
            prop="basic.custom_field_1"
            :label="field_rule.basic.custom_field_1.field_cn"
            v-if="field_rule.basic.custom_field_1.is_display == 1"
            :rules="[
              {
                required: field_rule.basic.custom_field_1.is_require == 1?true:false,
                message: '请填写'+field_rule.basic.custom_field_1.field_cn,
                trigger: 'blur'
              }
            ]"
          >
            <el-input  class="name_width" v-model="form.basic.custom_field_1" placeholder="请填写"></el-input>
          </el-form-item>
          <el-form-item
            prop="basic.custom_field_2"
            :label="field_rule.basic.custom_field_2.field_cn"
            v-if="field_rule.basic.custom_field_2.is_display == 1"
            :rules="[
              {
                required: field_rule.basic.custom_field_2.is_require == 1?true:false,
                message: '请填写'+field_rule.basic.custom_field_2.field_cn,
                trigger: 'blur'
              }
            ]"
          >
            <el-input  class="name_width" v-model="form.basic.custom_field_2" placeholder="请填写"></el-input>
          </el-form-item>
          <el-form-item
            prop="basic.custom_field_3"
            :label="field_rule.basic.custom_field_3.field_cn"
            v-if="field_rule.basic.custom_field_3.is_display == 1"
            :rules="[
              {
                required: field_rule.basic.custom_field_3.is_require == 1?true:false,
                message: '请填写'+field_rule.basic.custom_field_3.field_cn,
                trigger: 'blur'
              }
            ]">
            <el-input  class="name_width" v-model="form.basic.custom_field_3" placeholder="请填写"></el-input>
          </el-form-item>
      </el-card>

      <el-card>
        <div class="form_title">
          联系方式
        </div>
          <el-form-item label="联系方式">
            <el-select class="name_width" v-model="form.contact.use_company_contact">
              <el-option label="使用企业资料联系方式" :value="1"></el-option>
              <el-option label="使用其他联系方式" :value="0"></el-option>
            </el-select>
          </el-form-item>
          <div v-if="form.contact.use_company_contact==0" >
            <el-form-item
              label="联系人"
              prop="contact.contact"
              :rules="[
                {
                  required: true,
                  message: '请填写联系人',
                  trigger: 'blur'
                }
              ]"
            >
              <el-input class="name_width" v-model="form.contact.contact" placeholder="请填写联系人"></el-input>
            </el-form-item>
            <el-form-item
              label="联系手机"
              prop="contact.mobile"
              :rules="[
                {
                  required: true,
                  message: '请填写联系手机',
                  trigger: 'blur'
                }
              ]"
              >
              <el-input class="name_width" v-model="form.contact.mobile" placeholder="请填写手机号" @input="hanlderMobile"></el-input>
            </el-form-item>
            <el-form-item
              prop="contact.telephone"
              label="企业固话"
              v-if="field_rule.contact.telephone.is_display == 1"
              :rules="[
                {
                  required: field_rule.contact.telephone.is_require == 1?true:false,
                  message: '请填写企业固话',
                  trigger: 'blur'
                }
              ]"
            >
              <el-input class="name_width" v-model="form.contact.telephone" placeholder="请输入企业固话(格式:区号+电话号)"></el-input>
            </el-form-item>
            <el-form-item
              prop="contact.weixin"
              label="联系微信"
              v-if="field_rule.contact.weixin.is_display == 1"
              :rules="[
                {
                  required: field_rule.contact.weixin.is_require == 1?true:false,
                  message: '请填写联系微信',
                  trigger: 'blur'
                }
              ]"
            >
              <el-input class="wx_width" v-model="form.contact.weixin" placeholder="请填写微信号" @input="hanlderWeixin"></el-input><el-checkbox @change="handlerSync" v-model="weixin_sync_mobile" class="checkbox_mg">同手机号</el-checkbox>
            </el-form-item>
            <el-form-item
              label="联系邮箱"
              prop="contact.email"
              v-if="field_rule.contact.email.is_display == 1"
              :rules="[
                {
                  required: field_rule.contact.email.is_require == 1?true:false,
                  message: '请填写联系邮箱',
                  trigger: 'blur'
                }
              ]"
            >
              <el-input class="name_width" v-model="form.contact.email" placeholder="请输入邮箱号"></el-input>
            </el-form-item>
            <el-form-item
              label="联系QQ"
              prop="contact.qq"
              v-if="field_rule.contact.qq.is_display == 1"
              :rules="[
                {
                  required: field_rule.contact.qq.is_require == 1?true:false,
                  message: '请填写联系QQ',
                  trigger: 'blur'
                }
              ]"
            >
              <el-input class="name_width" v-model="form.contact.qq" placeholder="请输入QQ号"></el-input>
            </el-form-item>
          </div>

          <el-form-item>
            <el-checkbox v-model="contactHidden">联系方式保密（不想受到骚扰）</el-checkbox>
          </el-form-item>




        <div class="btn_wrapper">
          <el-button type="primary" @click="onSubmit('form')">{{type=='add'?'发布职位':'保存修改'}}</el-button>
        </div>
      </el-card>
      </el-form>
      <el-dialog title="标注详情地址" :visible.sync="showMap" width="800px" height="500px" @opened="handlerMapOpened" @closed="handlerCloseMap">
          <Mapset
            ref="mapset"
            title="地图标注"
            :mapLat="form.basic.map_lat"
            :mapLng="form.basic.map_lng"
            :mapZoom="form.basic.map_zoom"
            :address="form.basic.address"
          ></Mapset>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" @click="handlerCloseMap(true)">保 存</el-button>
          <el-button  @click="handlerCloseMap">取 消</el-button>
        </span>
      </el-dialog>
    </div>
</template>

<script>
import Mapset from '@/components/company/Mapset'
  export default {
    name: 'JobForm',
    components: {
      Mapset
    },
    props: ['enable_addjob_num','enable_addjob_num_total', 'type'],
    mounted () {
      this.$store.dispatch('getClassify', 'jobcategory')
      this.$store.dispatch('getClassify', 'education')
      this.$store.dispatch('getClassify', 'citycategory')
      this.$store.dispatch('getClassify', 'experience')
      this.$store.dispatch('getClassify', 'jobTag')
      this.$store.dispatch('getClassifyWage')
      this.$store.dispatch('getClassifyAge')
    },
    computed: {
      columnsCitycategory () {
        return this.$store.state.classifyCityCategory
      },
      columnsJobcategory () {
        return this.$store.state.classifyJobCategory
      },
      columnsEducation () {
        let arr = [{id:0,text:'不限'}]
        return arr.concat(this.$store.state.classifyEdu)
      },
      columnsExperience () {
        let arr = [{id:0,text:'不限'}]
        return arr.concat(this.$store.state.classifyExperience)
      },
      columnsTag () {
        return this.$store.state.classifyJobTag
      },
      columnsMinWage () {
        let returnArr = []
        let arr = [...this.$store.state.classifyWage]
        if(arr.length>0){
          arr.pop()
        }
        for (let index = 0; index < arr.length; index++) {
          let tmp_json = {
            id: arr[index],
            name: arr[index] + '元/月'
          }
          returnArr.push(tmp_json)
        }
        return returnArr
      },
      columnsMinAge () {
        let returnArr = []
        let arr = [...this.$store.state.classifyAge]
        if(arr.length>0){
          arr.pop()
        }
        for (let index = 0; index < arr.length; index++) {
          let tmp_json = {
            id: arr[index],
            name: arr[index] + '岁'
          }
          returnArr.push(tmp_json)
        }
        return returnArr
      },
    },
    data () {
      return {
        options_maxwage:[],
        options_maxage:[],
        showMap: false,
        field_rule: {
          basic: {
            department: {},
            age: {},
            tag: {},
            amount: {},
            negotiable: {},
            custom_field_1: {},
            custom_field_2: {},
            custom_field_3: {}
          },
          contact: {
            weixin: {},
            telephone: {},
            qq: {},
            email: {}
          }
        },
        form:{
          basic: {
            id: 0,
            jobname: '',
            jobcategory_arr:[],
            category1: 0,
            category2: 0,
            category3: 0,
            experience: 0,
            education: 0,
            minwage: '',
            maxwage: '',
            negotiable: false,
            amount: '',
            citycategory_arr:[],
            district1: 0,
            district2: 0,
            district3: 0,
            content: '',
            tag: [],
            department: '',
            minage: '',
            maxage: '',
            age_na: false,
            map_lat: 0,
            map_lng: 0,
            map_zoom: 0,
            address: '',
            custom_field_1: '',
            custom_field_2: '',
            custom_field_3: ''
          },
          contact: {
            use_company_contact: 1,
            is_display: true,
            contact: '',
            mobile: '',
            weixin: '',
            telephone: '',
            email: '',
            qq: ''
          }
        },
        contactHidden: false,
        weixin_sync_mobile: false,
      }
    },
    methods:{
      validateWage(rule, value, callback){
        if(this.form.basic.negotiable===true){
          callback();
        }else if(!this.form.basic.minwage || !this.form.basic.maxwage){
          callback(new Error("请选择薪资待遇"));
        }else{
          callback();
        }
      },
      validateAge(rule, value, callback) {
        if(this.form.basic.age_na===true){
          callback();
        }else if(!this.form.basic.minage || !this.form.basic.maxage){
          callback(new Error("请选择年龄要求"));
        }else{
          callback();
        }
      },
      handlerMinwageChange(e) {
        this.handleMaxwageChange(e)
      },
      handleMaxwageChange(minwage) {
        let wage_data = this.$store.state.classifyWage
        if (!minwage) {
          return false
        }
        this.options_maxwage = []
        let startindex = wage_data.indexOf(minwage) + 1
        if (minwage >= this.form.basic.maxwage) {
          this.form.basic.maxwage = wage_data[startindex]
        }
        for (let index = startindex; index < wage_data.length; index++) {
          let tmp_json = {
            id: wage_data[index],
            name: wage_data[index] + '元/月',
          }
          this.options_maxwage.push(tmp_json)
        }
      },
      handlerMinageChange(e) {
        this.handleMaxageChange(e)
      },
      handleMaxageChange(minage) {
        let age_data = this.$store.state.classifyAge
        if (!minage) {
          return false
        }
        this.options_maxage = []
        let startindex = age_data.indexOf(minage) + 1
        if (minage >= this.form.basic.maxage) {
          this.form.basic.maxage = age_data[startindex]
        }
        for (let index = startindex; index < age_data.length; index++) {
          let tmp_json = {
            id: age_data[index],
            name: age_data[index] + '岁',
          }
          this.options_maxage.push(tmp_json)
        }
      },
      onSubmit (formName) {
        this.$refs[formName].validate(valid => {
        if (valid) {
          this.form.basic.age_na = this.form.basic.age_na === true ? 1 : 0
          this.form.basic.negotiable = this.form.basic.negotiable === true ? 1 : 0
          this.form.contact.is_display = this.contactHidden === true ? 0 : 1
          let tmp_jobcategory_arr = this.form.basic.jobcategory_arr
          this.form.basic.category1 = tmp_jobcategory_arr[0]
          this.form.basic.category2 =
            tmp_jobcategory_arr[1] !== undefined ? tmp_jobcategory_arr[1] : 0
          this.form.basic.category3 =
            tmp_jobcategory_arr[2] !== undefined ? tmp_jobcategory_arr[2] : 0
          let tmp_citycategory_arr = this.form.basic.citycategory_arr
          this.form.basic.district1 = tmp_citycategory_arr[0]
          this.form.basic.district2 =
            tmp_citycategory_arr[1] !== undefined ? tmp_citycategory_arr[1] : 0
          this.form.basic.district3 =
            tmp_citycategory_arr[2] !== undefined ? tmp_citycategory_arr[2] : 0

          this.$emit('submit', {
            basic: this.form.basic,
            contact: this.form.contact
          })
        } else {
          return false;
        }
      });

      },
      hanlderMobile (val) {
        if (this.weixin_sync_mobile === true) {
          this.form.contact.weixin = val
        }
      },
      hanlderWeixin () {
        if (this.form.contact.weixin !== this.form.contact.mobile) {
          this.weixin_sync_mobile = false
        } else {
          this.weixin_sync_mobile = true
        }
      },
      handlerSync () {
        if (this.weixin_sync_mobile === true) {
          this.form.contact.weixin = this.form.contact.mobile
        }
      },
      handlerShowMap () {
        this.showMap = true
      },
      handlerMapOpened(){
        this.$refs.mapset.initCB()
      },
      handlerCloseMap(setData){
        this.showMap = false
        if(setData===true){
          this.form.basic.map_lat = this.$refs.mapset.mapData.lat
          this.form.basic.map_lng = this.$refs.mapset.mapData.lng
          this.form.basic.map_zoom = this.$refs.mapset.mapData.zoom
          this.form.basic.address = this.$refs.mapset.mapData.address
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
	.Tips_text{
		padding: 16px 0;
		font-size: 11px;
		color: #846d39;
		background: #fefce8;
		margin-bottom: 20px;
	}
	.Tips_text img{
		vertical-align: middle;
		margin: 0  10px 0  20px;
	}
	.form_title{
		padding: 20px 0;
		font-size: 16px;
	}
	.el-form{
		margin: 15px 0 0 16px;
	}
	.form_item{
		width: 820px;
		display: flex;
	}
	.form_item .el-form-item .el-select,
	.form_item .el-form-item .el-input{
		width: 170px;
	}
	.name_width{
		width:430px;
	}
	.salary_width{
		width: 180px;
	}
	.region_width{
		width: 430px;
		margin-left: 20px;
	}
	.textField_width{
		width: 960px;
	}
	.el-card{
		margin-bottom: 10px;
	}
	.form_item_con{
		display: flex;
	}
	.form_item_con>div{
		border: 1px solid #ccc;
		padding: 0 10px 0 0;
		margin-right: 10px;
	}
	.form_item_con>div:nth-of-type(2){
		margin-left:10px;
	}
	.wx_width{
		width: 350px;
	}
	.checkbox_mg{
		margin: 0 6px 0 12px;
	}
	.taggingImg{
		vertical-align: middle;
		cursor: pointer;
	}
	.welfare_con{
		display: flex;
		flex-wrap:wrap
	}
	.welfare_con>div>div{
		padding: 0px 12px;
		background: #fafafa;
		font-size:12px ;
		color: #666666;
		margin-right: 20px;
		margin-bottom: 20px;
	}
	.input_box .el-input{
		width: 133px;
	}
	.input_box .el-button{
		width:77px ;
		background: #fafafa;
	}
	.welfare_con .active_bg{
		background:#eef9ff ;
		color: #1787fb;
	}

.el-form-item__content > span {
  cursor: pointer;
}
.el-form{
  margin:0;
}

</style>
