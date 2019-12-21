<template>
  <details v-if="[] !== steps" open>
    <Summary>{{ summary }}</Summary>
    {{ steps }}
  </details>
</template>

<script>
import store from "@/store";

export default {
  name: "StepsRaw",
  props: ["summary"],
  computed: {
    steps: function() {
      return store.state.steps;
    }
  }
};
</script>

<style></style>
