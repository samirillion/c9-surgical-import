<template>
  <div class="ifm-input-wrapper ifm-value-wrapper" v-if="loopOption.left">
    <!-- If you have uploaded a csv, and want to just loop through any or all of the rows -->
    <div
      v-if="parsedCsv.length > 1 && 'csv_rows' === loopOption.left"
      class="ifm-input-wrapper"
    >
      <label for="loopOffset">Loop Offset:</label>
      <input
        type="number"
        name="loopOffset"
        min="1"
        :max="parsedCsv.length - 1"
        class="import-range"
        v-model="loopOption.right.offset"
      />
      <label for="loopLimit">Loop Limit:</label>
      <input
        type="number"
        name="loopLimit"
        min="1"
        :max="parsedCsv.length"
        class="import-range"
        v-model="loopOption.right.limit"
      />
    </div>
    <!-- Loop through an exact range -->
    <div v-else-if="'integer' === loopOption.left" class="ifm-input-wrapper">
      <label for="importLimit">Import Limit</label>
      <input
        type="number"
        min="1"
        name="importLimit"
        class="import-limit"
        v-model="loopOption.right"
      />
    </div>
    <!-- Loop through the output of a get query -->
    <div v-else-if="'get_value' === loopOption.left" class="ifm-input-wrapper">
      <label for="importGetValue">Value</label>
      <v-select
        name="importGetValue"
        label="key"
        index="value"
        :options="getIds"
        v-model="loopOption.right"
        item-value="text"
      />
    </div>
  </div>
</template>

<script>
import store from "@/store";

export default {
  name: "LoopParams",
  props: ["parsedCsv", "loopOptionLeft"],
  data() {
    return {
      loopOption: store.state.loopOption,
      getIds: store.state.getIds
    };
  },
  created() {
    store.state.loopOption.right.limit = this.parsedCsv.length;
  },
  watch: {
    getIds: function(newVal, oldVal) {
      this.getIds = store.stage.getIds;
    },
    parsedCsv: function(newVal, oldVal) {
      store.state.loopOption.right.limit = newVal.length;
    },
    loopOptionLeft: function(newVal, oldVal) {
      store.state.loopOption.right = newVal;
      if ("csv_rows" === newVal) {
        store.state.loopOption.right = {};
        store.state.loopOption.right.offset = 1;
        store.state.loopOption.right.limit = this.parsedCsv.length;
      }
      if ("get_value" === newVal) {
        this.getIds = store.state.steps
          .filter(option => {
            return option.id.startsWith("get_");
          })
          .map(option => {
            return { key: option.id, value: option.id };
          });
        store.state.loopOption.right = this.getIds[0];
      }
      if ("integer" === newVal) {
        store.state.loopOption.right = 0;
      }
    }
  }
};
</script>

<style lang="scss" scoped></style>
