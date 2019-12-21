<template>
  <div class="param-to-field">
    <div v-if="'update' === state.verb">
      <h4>where</h4>
      <div
        class="where-mapper"
        v-for="(where, whereIndex) in step.map.where"
        :key="whereIndex"
      >
        <select v-model="step.map.where">
          <option v-for="(param, paramIndex) in params" :key="paramIndex">
            {{ param }}
          </option>
        </select>
        <button
          @click="deleteWhere(stepIndex)"
          v-if="step.map.where.length > 1"
          class="button button-primary"
        >
          -
        </button>
      </div>
      <button
        @click="addWhere(step.map.where.length)"
        class="button button-primary"
      >
        +
      </button>
    </div>
  </div>
</template>

<script>
import store from "@/store";
import { userParams, postParams } from "@/utils/Constants";

export default {
  name: "ImportMapper",
  props: ["stepIndex"],
  data() {
    return {
      step: store.state.steps[this.stepIndex]
    };
  },
  computed: {
    params: function() {
      if ("post" === step.entity) {
        return postParams;
      } else if ("user" === step.entity) {
        return userParams;
      }
    }
  },
  methods: {
    getParams(action) {
      if ("newPost" === action) {
        return postParams;
      } else if ("newUser" === action) {
        return userParams;
      }
    }
  }
};
</script>
