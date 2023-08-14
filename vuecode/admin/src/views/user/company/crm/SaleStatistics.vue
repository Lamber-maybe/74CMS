<template>
  <div class="app-container salesStatisticsBox">
    <el-row :gutter="20">
      <el-col :span="16" style="padding-left: 0">
        <div class="sale_box_left">
          <div class="sale_box1">
            <span>销售统计</span>
            <div>
              <el-date-picker v-model="date" type="month" format="yyyy-MM" placeholder="选择月" @change="monthChange"/>
            </div>
          </div>
          <div class="sale_box2">
            <div class="sale_box" @click="jumpPath('/user/company/crm/myClient', '暂无权限查看，请联系超级管理员', 'add_company')">
              <div class="sale_box_desc">
                新增客户
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="统计当前月新增的客户数量"
                  placement="top-start"
                >
                  <i class="el-icon-question"/>
                </el-tooltip>
              </div>
              <div class="sale_box_count">
                <span class="sale_box_count_num">{{ kanbanData.new_customer }}</span>
                <img class="sale_box_count_icon" src="../../../../assets/images/company/crm/SaleStatistics/icon1.png"
                     alt="">
              </div>
            </div>
            <div class="sale_box" @click="jumpPath('/user/company/crm/my','暂无权限查看，请联系超级管理员','add_clue')">
              <div class="sale_box_desc">
                新增线索
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="统计当前月新增的线索数量"
                  placement="top-start"
                >
                  <i class="el-icon-question"/>
                </el-tooltip>
              </div>
              <div class="sale_box_count">
                <span class="sale_box_count_num">{{ kanbanData.new_crm_clue }}</span>
                <img class="sale_box_count_icon" src="../../../../assets/images/company/crm/SaleStatistics/icon2.png"
                     alt="">
              </div>
            </div>
            <div class="sale_box"
                 @click="jumpPath('/user/company/crm/myClient', '暂无权限查看，请联系超级管理员', 'transaction_customer')">
              <div class="sale_box_desc">
                成交客户
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="统计当前月成交的客户数量"
                  placement="top-start"
                >
                  <i class="el-icon-question"/>
                </el-tooltip>
              </div>
              <div class="sale_box_count">
                <span class="sale_box_count_num">{{ kanbanData.transaction_customers }}</span>
                <img class="sale_box_count_icon" src="../../../../assets/images/company/crm/SaleStatistics/icon5.png"
                     alt="">
              </div>
            </div>
            <div class="sale_box" @click="jumpPath('/user/company/crm/myClient', '暂无权限查看，请联系超级管理员', 'follow_customer')">
              <div class="sale_box_desc">
                已跟进客户
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="统计当前月已跟进的客户数量"
                  placement="top-start"
                >
                  <i class="el-icon-question"/>
                </el-tooltip>
              </div>
              <div class="sale_box_count">
                <span class="sale_box_count_num">{{ kanbanData.customer_followed_up }}</span>
                <img class="sale_box_count_icon" src="../../../../assets/images/company/crm/SaleStatistics/icon6.png"
                     alt="">
              </div>
            </div>
          </div>
        </div>
      </el-col>
      <el-col :span="8" style="padding-right: 0">
        <div class="sale_box_right">
          <div class="sale_box1">
            <span>到期统计</span>
          </div>
          <div class="sale_box2">
            <div class="sale_box" @click="jumpPath('/user/company/crm/myClient', '暂无权限查看，请联系超级管理员', 'expiring_soon')">
              <div class="sale_box_desc">
                即将到期客户
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="统计即将到期的客户总数"
                  placement="top-start"
                >
                  <i class="el-icon-question"/>
                </el-tooltip>
              </div>
              <div class="sale_box_count">
                <span class="sale_box_count_num">{{ kanbanData.expiring_soon }}</span>
                <img class="sale_box_count_icon" src="../../../../assets/images/company/crm/SaleStatistics/icon3.png"
                     alt="">
              </div>
            </div>
            <div class="sale_box" @click="jumpPath('/user/company/crm/myClient', '暂无权限查看，请联系超级管理员', 'expire_company')">
              <div class="sale_box_desc">
                到期未续费客户
                <el-tooltip
                  class="item"
                  effect="dark"
                  content="统计到期未续费的客户总数"
                  placement="top-start"
                >
                  <i class="el-icon-question"/>
                </el-tooltip>
              </div>
              <div class="sale_box_count">
                <span class="sale_box_count_num">{{ kanbanData.expire_company }}</span>
                <img class="sale_box_count_icon" src="../../../../assets/images/company/crm/SaleStatistics/icon4.png"
                     alt="">
              </div>
            </div>
          </div>
        </div>
      </el-col>
    </el-row>
    <el-col :span="18">
      <el-row :gutter="20" style="margin-bottom: 20px;">
        <el-col :span="12" class="first_el-col" style="padding-left: 0;">
          <job_refresh height="300px" :data-arr="saleStatictisAllData" :grade-screen-data="gradeScreenData"/>
        </el-col>
        <el-col :span="12" class="two_el-col">
          <company_down height="300px" :data-arr="saleStatictisAllData" :grade-screen-data="gradeScreenData"/>
        </el-col>
        <!-- <el-col :span="6">
        <transaction_customers height="300px" />
      </el-col> -->
      </el-row>
      <el-row :gutter="20">
        <el-col :span="12" class="first_el-col first_el_col_new">
          <com_login height="300px" :data-arr="saleStatictisAllData"/>
        </el-col>
        <el-col :span="12" class="two_el-col">
          <job_apply height="300px" :data-arr="saleStatictisAllData"/>
        </el-col>
        <!-- <el-col :span="6">
        <transaction_customers height="300px" />
      </el-col> -->
      </el-row>
    </el-col>
    <el-col :span="6">
      <el-row :gutter="20" style="margin-bottom: 20px;">
        <el-col :span="24" style="padding-right: 0;">
          <transaction_customers height="250px" :data-arr="saleStatictisAllData"/>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="24" style="padding-right: 0;">
          <new_customers height="250px" :data-arr="saleStatictisAllData"/>
        </el-col>
      </el-row>
    </el-col>
  </div>
</template>

<script>
import job_refresh from './components/job_refresh.vue'
import company_down from './components/company_down.vue'
import com_login from './components/com_login.vue'
import job_apply from './components/job_apply.vue'
import transaction_customers from './components/transaction_customers.vue'
import new_customers from './components/new_customers.vue'
import {salesKanban, companyCrmClueStatistics, lifeCycle} from '@/api/company_crm'
import {checkRoleAuth} from "@/utils/role";

export default {
  components: {
    job_refresh,
    company_down,
    com_login,
    job_apply,
    transaction_customers,
    new_customers
  },
  data() {
    return {
      date: new Date(),
      kanbanData: {},
      saleStatictisAllData: {},
      month: '',
      gradeScreenData: [] // 客户等级
    }
  },
  created() {
    // 生成日期
    this.generateDate(new Date());
    this.initData()
    this.getData()
    this.lifeCycle()
  },
  methods: {
    lifeCycle() {
      lifeCycle()
        .then(res => {
          this.gradeScreenData = res.data
        })
        .catch(() => {
        })
    },
    initData() {
      salesKanban({
        month: this.month
      }).then(res => {
        if (res.code == 200) {
          this.kanbanData = res.data
        } else {
          this.$message.error(res.message)
        }
      })
    },
    getData() {
      companyCrmClueStatistics().then(res => {
        if (res.code == 200) {
          this.saleStatictisAllData = res.data
        } else {
          this.$message.error(res.message)
        }
      })
    },
    monthChange(e) {
      // 生成日期
      this.generateDate(e && e != null ? e : new Date());
      this.initData()
    },
    jumpPath(routePath, message, type) {
      /**
       * 【ID1000677】
       * 【优化】销售看板新增提示语、点击跳转、样式优化
       * cy 2023-06-28
       * 指定月份的筛选
       */
      switch (type) {
        // 新增客户
        case 'add_company' :
        // 新增线索
        case 'add_clue' :
          checkRoleAuth(routePath, message, {'collection_date_custom': this.month})
          break
        // 成交客户
        case 'transaction_customer':
          checkRoleAuth(routePath, message, {'gradeScreen': '7', 'collection_date_custom': this.month})
          break
        // 已跟进客户
        case 'follow_customer':
          checkRoleAuth(routePath, message, {'followupScreen': '8', 'collection_date_custom': this.month})
          break
        // 即将到期客户
        case 'expiring_soon':
          checkRoleAuth(routePath, message, {'setmealDeadline': '1'})
          break
        // 到期未续费客户
        case 'expire_company':
          checkRoleAuth(routePath, message, {'setmealDeadline': '2'})
          break
        default:
          checkRoleAuth(routePath, message)
          break
      }
    },
    // 生成日期
    generateDate(date) {
      let year = date.getFullYear();
      let month = (date.getMonth() + 1);
      month = month < 10 ? '0' + month : month;
      this.month = year + '-' + month;
    }
  }
}
</script>
<style scoped lang="scss">
.app-container {
  padding: 20px;
  background: #f5f7f9;
}

.salesStatisticsBox {
  overflow: hidden;
}

// .salesStatisticsBox .el-col {
//   padding: 0 !important;
// }
::v-deep .salesStatisticsBox .el-table::before {
  background: transparent;
}

.salesStatisticsBox .first_el_col {
  padding-left: 0px !important;
}

.salesStatisticsBox .two_el_col {
  // padding: 0 20px !important;
}

.salesStatisticsBox .el-row {
  margin-left: 0 !important;
  margin-right: 0 !important;
}

.sale_box_left {
  background: #fff;
  margin-bottom: 20px;
  box-sizing: border-box;
  padding: 15px 30px 28px 30px;

  .sale_box1 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    color: #333;
    font-size: 18px;
    font-weight: bold;

    .el-date-editor.el-input {
      width: 120px;
    }

    .el-input__icon {
      line-height: 30px;
    }

    .el-input__inner {
      height: 30px;
      line-height: 30px;
    }
  }

  .sale_box2 {
    display: flex;
    justify-content: space-between;
    align-items: center;

    .sale_box {
      width: calc((100% - 70px) / 4);
      border-radius: 5px;
      box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.1);
      padding: 16px 14px 0 24px;
      cursor: pointer;

      &:hover {
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
        transition: all .1s;

        .sale_box_count_icon {
          transform: scale(1.2);
          transition: all .1s;
        }
      }

      .sale_box_desc {
        color: #79869e;
        font-size: 14px;
      }

      .sale_box_count {
        display: flex;
        justify-content: space-between;
        align-items: center;

        .sale_box_count_num {
          font-size: 28px;
          color: #2b2b2b;
        }

        .sale_box_count_icon {
        }
      }
    }
  }
}

.sale_box_right {
  background: #fff;
  margin-bottom: 20px;
  box-sizing: border-box;
  padding: 15px 30px 28px 30px;

  .sale_box1 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #333;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 23px;
    padding-top: 12px;

    .el-date-editor.el-input {
      width: 120px;
    }

    .el-input__icon {
      line-height: 30px;
    }

    .el-input__inner {
      height: 30px;
      line-height: 30px;
    }
  }

  .sale_box2 {
    display: flex;
    justify-content: space-between;
    align-items: center;

    .sale_box {
      width: calc((100% - 22px) / 2);
      border-radius: 5px;
      box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.1);
      padding: 16px 24px 0;
      cursor: pointer;

      &:hover {
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
        transition: all .1s;

        .sale_box_count_icon {
          transform: scale(1.2);
          transition: all .1s;
        }
      }

      .sale_box_desc {
        color: #79869e;
        font-size: 14px;
      }

      .sale_box_count {
        display: flex;
        justify-content: space-between;
        align-items: center;

        .sale_box_count_num {
          font-size: 28px;
          color: #2b2b2b;
        }

        .sale_box_count_icon {
        }
      }
    }
  }
}

.first_el_col_new {
  padding-left: 0 !important;
}
</style>
