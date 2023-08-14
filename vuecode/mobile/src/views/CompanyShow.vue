<template>
  <div>
    <CompanyOne v-if="detail_id == 'def'"></CompanyOne>
    <CompanyTwo v-if="detail_id == 'tpl2'"></CompanyTwo>
  </div>
</template>

<script>
import CompanyOne from "../components/company/CompanyOne";
import CompanyTwo from "../components/company/CompanyTwo";
export default {
  name: "companyShow",
  data() {
    return {
      detail_id: 'def',
    };
  },
  components: { CompanyOne, CompanyTwo },
  created() {
    this.detail_id = this.$store.state.config.mobile_company_show_tpl
  },
  methods: {},
};
</script>
