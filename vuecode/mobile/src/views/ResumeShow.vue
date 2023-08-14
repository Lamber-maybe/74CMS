<template>
  <div>
    <ResumeOne v-if="detail_id == 'def'"></ResumeOne>
    <ResumeTwo v-if="detail_id == 'tpl2'"></ResumeTwo>
  </div>
</template>

<script>
import ResumeOne from "../components/resume/ResumeOne";
import ResumeTwo from "../components/resume/ResumeTwo";
export default {
  name: "resumeShow",
  data() {
    return {
      detail_id: 'def',
    };
    
  },
  components: { ResumeOne, ResumeTwo },
  created() {
    this.detail_id = this.$store.state.config.mobile_resume_show_tpl
  },
  methods: {
  },
};
</script>
