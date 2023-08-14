<template>
  <div class="add_group">
    <div class="g_title">基本信息</div>
    <div class="fill_group">
      <div class="fill_line">
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>姓名</div>
          <div class="i_input">
            <el-input v-model="basic.fullname" placeholder="请填写姓名"></el-input>
          </div>
          <div class="clear"></div>
        </div>
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>性别</div>
          <div class="i_input">
            <div :class="basic.sex === 1 ? 'g_type male active' : 'g_type male'" @click="basic.sex = 1">男</div>
            <div :class="basic.sex === 2 ? 'g_type female active' : 'g_type female'" @click="basic.sex = 2">女</div>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="fill_line">
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>出生日期</div>
          <div class="i_input">
            <el-date-picker
              :style="{'width': '250px'}"
              v-model="basic.birthday"
              type="month"
              format="yyyy-MM"
              value-format="yyyy-MM"
              :picker-options="datePickerRange"
              placeholder="请选择出生日期">
            </el-date-picker>
          </div>
          <div class="clear"></div>
        </div>
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>最高学历</div>
          <div class="i_input">
            <el-select v-model="basic.education" placeholder="请选择最高学历">
              <el-option v-for="(item, index) in optionEducation"
                         :key="index"
                         :label="item.text"
                         :value="item.id"></el-option>
            </el-select>
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="fill_line">
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>开始工作时间</div>
          <div class="i_input">
            <el-date-picker
              :style="{'width': '250px'}"
              v-model="basic.enter_job_time"
              :disabled="isNoJobTime"
              type="month"
              format="yyyy-MM"
              value-format="yyyy-MM"
              :picker-options="datePickerRange"
              placeholder="请选择开始工作时间">
            </el-date-picker>
            <el-checkbox class="for_after" v-model="isNoJobTime">应届生/无经验</el-checkbox>
          </div>
          <div class="clear"></div>
        </div>
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>联系电话</div>
          <div class="i_input">
            <el-input v-model="contact.mobile" placeholder="请填写联系电话"></el-input>
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="fill_line">
        <div class="f_item">
          <div class="i_label">联系微信</div>
          <div class="i_input">
            <el-input v-model="contact.weixin" placeholder="请填写联系微信"></el-input>
            <el-checkbox class="for_after" v-model="checkedEditPhone" @change="syncWxMobile">同手机</el-checkbox>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
    <div class="g_title">求职意向</div>
    <div class="fill_group">
      <div class="fill_line">
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>期望职位</div>
          <div class="i_input">
            <el-cascader
              :style="{'width': '250px'}"
              v-model="modelCategory"
              placeholder="请选择期望职位"
              :options="optionCategory"
              @change="handleValues([...arguments, 'category'])"></el-cascader>
          </div>
          <div class="clear"></div>
        </div>
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>期望薪资</div>
          <div class="i_input">
            <el-select class="w118" v-model="intention.minwage"
                       placeholder="请选择"
                       @change="handlerMinwageChange"
                       >
              <el-option v-for="(item, index) in optionMinWage"
                         :key="index"
                          :label="item.name"
                         :value="item.id"></el-option>
            </el-select>
            -
            <el-select class="w118" v-model="intention.maxwage" placeholder="请选择">
              <el-option v-for="(item, index) in optionMaxWage"
                         :key="index"
                        :label="item.name"
                        :value="item.id"></el-option>
            </el-select>
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="fill_line">
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>期望地区</div>
          <div class="i_input">
            <el-cascader
              :style="{'width': '250px'}"
              v-model="modelDistrict"
              placeholder="请选择期望地区"
              :options="optionDistrict"
              @change="handleValues([...arguments, 'district'])"></el-cascader>
          </div>
          <div class="clear"></div>
        </div>
        <div class="f_item">
          <div class="i_label"><span class="req">* </span>求职状态</div>
          <div class="i_input">
            <el-select v-model="basic.current" placeholder="请选择求职状态">
              <el-option v-for="(item, index) in optionCurrent"
                         :key="index"
                         :label="item.text"
                         :value="item.id"></el-option>
            </el-select>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
    <div class="fill_btn">
      <el-button class="w200" type="primary" @click="handleSubmit">下一步</el-button>
    </div>
  </div>
</template>

<script>
  import http from '@/utils/http'
  import api from '@/api'

  export default {
    name: 'AddResumeStep1',
    data () {
      return {
        datePickerRange: this.dateRange(),
        basic: {
          fullname: '',
          sex: 1,
          birthday: '',
          education: '',
          enter_job_time: '',
          current: '',
        },
        contact: {
          mobile: '',
          weixin: '',
        },
        intention: {
          category1: '',
          category2: '',
          category3: '',
          minwage: '',
          maxwage: '',
          district1: '',
          district2: '',
          district3: '',
        },
        isNoJobTime: false,
        checkedEditPhone: false,
        optionEducation: [],
        optionCurrent: [],
        optionMaxWage:[],
        optionCategory: [],
        optionDistrict: [],
        modelCategory: [],
        modelDistrict: []
      }
    },
    computed: {
      optionMinWage () {
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
      }
    },
    mounted () {
      // 学历分类
      this.$store.dispatch('getClassify', 'education').then(() => {
        this.optionEducation = JSON.parse(JSON.stringify(this.$store.state.classifyEdu))
      })
      // 手机号
      this.contact.mobile = this.$store.state.userMobile
      // 求职状态分类
      this.$store.dispatch('getClassify', 'current').then(() => {
        this.optionCurrent = JSON.parse(JSON.stringify(this.$store.state.classifyCurrent))
      })
      // 薪资分类
      this.$store.dispatch('getClassifyWage')
      // 职位分类
      this.$store.dispatch('getClassify', 'jobcategory').then(() => {
        this.optionCategory = this.$store.state.classifyJobCategory
      })
      // 地区分类
      this.$store.dispatch('getClassify', 'citycategory').then(() => {
        this.optionDistrict = this.$store.state.classifyCityCategory
      })
    },
    watch: {
      'contact.weixin': function () {
        this.$nextTick(function () {
          this.checkedEditPhone = this.contact.mobile === this.contact.weixin
        })
      }
    },
    methods: {
      dateRange(){
        return {
          disabledDate(time){
              return time.getTime() > new Date().getTime();
          }
        }
      },
      // 微信号同手机号
      syncWxMobile () {
        if (this.checkedEditPhone) {
          this.contact.weixin = this.contact.mobile
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
        this.optionMaxWage = []
        let startindex = wage_data.indexOf(minwage) + 1
        if (minwage >= this.intention.maxwage) {
          this.intention.maxwage = wage_data[startindex]
        }
        for (let index = startindex; index < wage_data.length; index++) {
          let tmp_json = {
            id: wage_data[index],
            name: wage_data[index] + '元/月',
          }
          this.optionMaxWage.push(tmp_json)
        }
      },
      // /**
      //  * 生成薪资分类
      //  * @param type
      //  * @param minValue
      //  * @returns {[]}
      //  */
      // makeWageOption (type, minValue) {
      //   let wageArray = []
      //   if (minValue <= this.$store.state.maxWage) {
      //     let a = minValue
      //     let b = this.$store.state.maxWage
      //     if (type === 'min') {
      //       this.optionMinWage = [...Array(((b - a) / 500))].map((e, i) => a + i * 500)
      //     } else if (type === 'max') {
      //       this.optionMaxWage = [...Array(((b - a) / 500) + 1)].map((e, i) => a + i * 500)
      //     }
      //   }
      //   return wageArray
      // },
      // // 薪资级联
      // changeMinWage (value) {
      //   this.makeWageOption('max', value + 500)
      // },
      // 选择职位和地区分类
      handleValues (value) {
        this.makeUpValue(JSON.parse(JSON.stringify(value[0]))).forEach((item, index) => {
          this.intention[`${value[1]}${(index + 1)}`] = item
        })
      },
      // 选择地区或职位分类之后处理选中值
      makeUpValue (value) {
        return [...Array(3)].map((e, i) => value[i] > 0 ? value[i] : 0)
      },
      // 提交前验证
      handleSubmit () {
        if (!this.basic.fullname) {
          this.$message({ message: '请填写姓名', type: 'warning' })
          return false
        }
        if (!this.basic.sex) {
          this.$message({ message: '请选择性别', type: 'warning' })
          return false
        }
        if (!this.basic.birthday) {
          this.$message({ message: '请选择出生日期', type: 'warning' })
          return false
        }
        if (!this.basic.education) {
          this.$message({ message: '请选择学历', type: 'warning' })
          return false
        }
        if (!this.isNoJobTime) {
          if (!this.basic.enter_job_time) {
            this.$message({ message: '请选择开始工作时间', type: 'warning' })
            return false
          }
        }
        if (!this.contact.mobile) {
          this.$message({ message: '请填写联系电话', type: 'warning' })
          return false
        }
        if (!this.intention.category1) {
          this.$message({ message: '请选择期望职位', type: 'warning' })
          return false
        }
        if (!this.intention.minwage || !this.intention.maxwage) {
          this.$message({ message: '请选择薪资', type: 'warning' })
          return false
        }
        if (!this.intention.district1) {
          this.$message({ message: '请选择期望地区', type: 'warning' })
          return false
        }
        if (!this.basic.current) {
          this.$message({ message: '请选择求职状态', type: 'warning' })
          return false
        }
        http.post(api.reg_resume_form_step1, {
          'basic': {
            'fullname': this.basic.fullname,
            'sex': this.basic.sex,
            'birthday': this.basic.birthday,
            'education': this.basic.education,
            'enter_job_time': this.basic.enter_job_time,
            'current': this.basic.current,
          },
          'contact': {
            'mobile': this.contact.mobile,
            'weixin': this.contact.weixin,
          },
          'intention': {
            'category1': this.intention.category1,
            'category2': this.intention.category2,
            'category3': this.intention.category3,
            'minwage': this.intention.minwage,
            'maxwage': this.intention.maxwage,
            'district1': this.intention.district1,
            'district2': this.intention.district2,
            'district3': this.intention.district3,
          },
        }).then(res => {
          if (parseInt(res.code) === 200) {
            this.$message({ message: res.message, type: 'success' })
            this.$router.push({ path: `/personal/resume/add_resume_step2/${this.isNoJobTime ? 0 : 1}` })
          }
        }).catch(() => {
        })
      },
    },
  }
</script>

<style lang="scss" scoped>
  .add_group {
    .el-input, .el-select {
      width: 250px;
    }

    .el-select {
      &.w118 {
        width: 118px;
      }
    }

    width: 1200px;
    background-color: #fff;
    padding: 0 50px;
    margin-top: 15px;
    position: relative;

    .g_title {
      font-size: 18px;
      color: #333;
      font-weight: 700;
      padding: 33px 0 12px;
      border-bottom: 1px solid #f3f3f3;
    }

    .fill_group {
      padding-top: 8px;

      .fill_line {
        display: flex;
        margin-top: 22px;

        .f_item {
          flex: 1;

          .i_label {
            float: left;
            padding: 11px 10px 11px 0;
            text-align: right;
            width: 142px;

            .req {
              color: #ff0000
            }
          }

          .i_input {
            float: left;
            position: relative;

            .g_type {
              float: left;
              width: 76px;
              color: #999;
              padding: 11px 0 11px 39px;
              margin-right: 15px;
              border-radius: 40px;
              cursor: pointer;

              &.male {
                background: #f8f8f8 url("../../assets/images/member/4.png") 16px center no-repeat;
                background-size: 13px;
              }

              &.female {
                background: #f8f8f8 url("../../assets/images/member/6.png") 16px center no-repeat;
                background-size: 13px;
              }

              &.active {
                color: #1787fb;

                &.male {
                  background: #ebf5ff url("../../assets/images/member/5.png") 16px center no-repeat;
                  background-size: 13px;
                }

                &.female {
                  background: #ebf5ff url("../../assets/images/member/7.png") 16px center no-repeat;
                  background-size: 13px;
                }
              }
            }

            .for_after {
              margin-left: 15px;
            }
          }
        }
      }
    }

    .fill_btn {
      padding: 70px 0 90px;
      text-align: center;
    }
  }
</style>
