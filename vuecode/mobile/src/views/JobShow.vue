<template>
  <div>
    <JobOne v-if="detail_id == 'def'"></JobOne>
    <JobTwo v-if="detail_id == 'tpl2'"></JobTwo>
  </div>
</template>

<script>
import JobOne from "../components/job/JobOne";
import JobTwo from "../components/job/JobTwo";
export default {
  name: "JobShow",
  data() {
    return {
      detail_id: 'def',
    };
  },
  components: { JobOne, JobTwo },
  created() {
    this.detail_id = this.$store.state.config.mobile_job_show_tpl
  },
  methods: {},
};
</script>
