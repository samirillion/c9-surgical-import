<template>
  <div class="map-row">
    <div class="ifm-input-wrapper">
      <label for="mapRowLeft">Parameter</label>
      <input
        v-if="action && action.id.endsWith('meta') && false === isGetter"
        type="text"
        name="mapRowLeft"
        v-model="mapRow.left"
        @change="updateOptions(mapRow.type)"
      />
      <select v-else name="mapRowLeft" v-model="mapRow.left">
        <option
          v-for="(paramValue, paramKey) in params"
          :key="paramKey"
          :value="paramKey"
        >
          {{ paramValue }}
        </option>
      </select>
    </div>
    <div class="equals">=</div>
    <div class="type-and-value">
      <div class="ifm-input-wrapper">
        <label for="mapRowType">Type</label>
        <select
          name="mapRowType"
          v-model="mapRow.type"
          @change="updateOptions(mapRow.type)"
        >
          <option v-if="'post_type' === mapRow.left" value="postType"
            >post type</option
          >
          <option value="csvValue">csv value</option>
          <option v-if="stepIds.length > 0" value="stepId"
            >previous step id</option
          >
          <option value="string">string</option>
          <option value="customVar">complex value</option>
        </select>
      </div>
      <div class="ifm-input-wrapper">
        <label for="mapRowRight">Value</label>
        <v-select
          name="mapRowRight"
          label="key"
          index="value"
          :options="valueOptions"
          v-model="mapRow.right"
          v-if="'string' !== mapRow.type"
          @input="updateOptions(mapRow.type)"
          :key="checkedFields.length"
        />
        <input type="text" name="mapRowRight" v-model="mapRow.right" v-else />
      </div>
    </div>
  </div>
</template>

<script>
import store from "@/store";

import { getUser, getPost, createUser, createPost } from "@/services/Params";

import { WpApi } from "@/services/WpApi";

export default {
  name: "StepMapRow",
  props: ["mapRow", "stepIndex", "mapIndex", "action"],
  data() {
    return {
      valueOptions: [],
    };
  },
  computed: {
    params: function() {
      if (this.isGetter) return this.action.getParams;
      return this.action.setParams;
    },
    checkedFields: {
      get: () => store.state.checkedFields,
      set: value => store.commit("updateCheckedFields", value)
    },
    stepIds: function() {
      return store.getters.stepIds.slice(
        0,
        this.stepIndex - store.getters.stepIds.length
      );
    }
  },
  methods: {
    updateOptions(type) {
      if ("postType" === type)
        this.valueOptions = this.postTypes.map(option => {
          return { key: option, value: option };
        });
      if ("csvValue" === type)
        this.valueOptions = this.checkedFields.map(option => {
          return { key: option, value: option };
        });
      if ("stepId" === type)
        this.valueOptions = this.stepIds.map(option => {
          return { key: option, value: option };
        });
      if ("customVar" === type) this.valueOptions = store.getters.customVars;
    }
  }
};
</script>

<style></style>
