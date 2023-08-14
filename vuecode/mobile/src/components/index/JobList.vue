<template>
  <div>
    <div class="form_split_10"></div>
    <JobOne v-if="plan_id == 1"></JobOne>
    <JobTwo v-if="plan_id == 2"></JobTwo>
    <JobThree v-if="plan_id == 3"></JobThree>
    <JobFour v-if="plan_id == 4"></JobFour>
    <JobFive v-if="plan_id == 5"></JobFive>
    <JobSix v-if="plan_id == 6"></JobSix>
  </div>
</template>

<script>
import JobOne from './components/JobOne'
import JobTwo from './components/JobTwo'
import JobThree from './components/JobThree'
import JobFour from './components/JobFour'
import JobFive from './components/JobFive'
import JobSix from './components/JobSix'
export default {
  name: 'JobList',
  props: ['plan_id'],
  components: {
    JobOne,
    JobTwo,
    JobThree,
    JobFour,
    JobFive,
    JobSix
  },
  created () {},
  methods: {}
}
</script>
