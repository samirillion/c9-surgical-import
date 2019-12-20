<template>
  <div v-if="[] !== data">
      {{ data.steps }}
  </div>
</template>

<script>
import store from "@/store";

export default {
  name: "StepsRaw",
  computed: {
    data: function() {
      // return StepsStore.getSteps();
    }
  },
  watch: {
    data: function() {
      // return StepsStore.getSteps();
    }
  }
};
</script>

<style></style>
