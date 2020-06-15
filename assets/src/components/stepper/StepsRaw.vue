<template>
  <details class="raw-json" v-if="[] !== steps">
    <Summary>{{ summary }}</Summary>
    <h4 style="margin:20px;">Custom Vars</h4>
    <pre>{{ vars }}</pre>
    <h4 style="margin:20px;">Steps</h4>
    <pre>{{ steps }}</pre>
    <h4 style="margin:20px;">Import Loop</h4>
    <pre>{{ loop }}</pre>
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
    },
    vars: function() {
      return store.state.customVars;
    },
    loop: function() {
      return store.state.loopOption;
    }
  }
};
</script>

<style></style>
