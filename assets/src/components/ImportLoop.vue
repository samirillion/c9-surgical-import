<template>
  <details closed class="ifm-create-steps">
    <summary>
      Import Loop (choose conditions)
    </summary>
    <div class="ifm-loop-wrapper">
      <div class="ifm-input-wrapper">
        <label for="importLoopLeft">Loop Through</label>
        <select
          name="importLoopLeft"
          v-model="importLoop.left"
          @change="updateGetActions"
        >
          <option
            v-for="(paramValue, paramKey) in loopOptions"
            :key="paramKey"
            :value="paramKey"
          >
            {{ paramValue }}
          </option>
        </select>
      </div>
      <div class="ifm-input-wrapper ifm-value-wrapper" v-if="importLoop.left">
        <!-- If you have uploaded a csv, and want to just loop through any or all of the rows -->
        <div
          v-if="parsedCsv.length > 1 && 'csv_rows' === importLoop.left"
          class="ifm-input-wrapper"
        >
          <label for="loopOffset">Loop Offset:</label>
          <input
            type="number"
            name="loopOffset"
            min="0"
            :max="parsedCsv.length - 1"
            class="import-range"
            v-model="importLoop.right.offset"
          />
          <label for="loopLimit">Loop Limit:</label>
          <input
            type="number"
            name="loopLimit"
            min="0"
            :max="parsedCsv.length"
            class="import-range"
            v-model="importLoop.right.limit"
          />
        </div>
        <!-- Loop through an exact range -->
        <div
          v-else-if="'integer' === importLoop.left"
          class="ifm-input-wrapper"
        >
          <label for="importLimit">ImportLimit</label>
          <input
            type="number"
            name="importLimit"
            class="import-limit"
            v-model="importLoop.right"
          />
        </div>
        <!-- Loop through the output of a get query -->
        <div
          v-else-if="'get_value' === importLoop.left"
          class="ifm-input-wrapper"
        >
          <label for="importGetValue">Value</label>
          <v-select
            name="importGetValue"
            label="key"
            index="value"
            :options="getIds"
            v-model="importLoop.right"
          />
        </div>
      </div>
    </div>
  </details>
</template>

<script>
import store from "@/store";

import { WpApi } from "@/services/WpApi";

export default {
  name: "ImportLoop",
  props: ["parsedCsv"],
  data() {
    return {
      loopOptions: {
        csv_rows: "csv rows",
        integer: "a set number of times",
        get_value: "the output of a 'get_' action"
      },
      importLoop: store.state.importLoop,
      getIds: []
    };
  },
  mounted() {
    this.getIds = store.state.steps
      .filter(option => {
        return option.id.startsWith("get_");
      })
      .map(option => {
        return { key: option, value: option };
      });
    this.importLoop.right.limit = this.parsedCsv.length;
    store.state.importLoop.right.limit = this.parsedCsv.length;
  },
  methods: {
    updateGetActions() {
      let type = this.importLoop.left;
      delete this.importLoop["right"];
      if ("get_value" === type) {
        this.getIds = store.state.steps
          .filter(option => {
            return option.id.startsWith("get_");
          })
          .map(option => {
            return { key: option, value: option };
          });
      }
      if ("csv_rows" === type) {
        store.state.importLoop.right = {};
        store.state.importLoop.right.offset = 1;
        store.state.importLoop.right.limit = this.parsedCsv.length;
      }
    }
  }
};
</script>

<style lang="scss" scoped></style>
