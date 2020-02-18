<template>
  <div class="map-row">
    <div class="ifm-input-wrapper">
      <label for="mapRowLeft">Parameter</label>
      <input
        v-if="action && action.id.endsWith('meta')"
        type="text"
        name="mapRowLeft"
        v-model="mapRow.left"
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
        <select name="mapRowType" v-model="mapRow.type" @change="updateOptions">
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
      <div class="ifm-input-wrapper" v-if="mapRow.type">
        <label for="mapRowRight">Value</label>
        <v-select
          name="mapRowRight"
          label="key"
          index="value"
          :options="valueOptions"
          v-model="mapRow.right"
          v-if="'string' !== mapRow.type"
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
  props: ["mapRow", "stepIndex", "mapIndex", "action", "params"],
  data() {
    return {
      valueOptions: [],
      postTypes: ["page", "post", "comment"],
      csvFields: store.state.csvFields
    };
  },
  created() {
    this.getPostTypes();
  },
  computed: {
    stepIds: function() {
      return store.getters.stepIds.slice(
        0,
        this.stepIndex - store.getters.stepIds.length
      );
    }
  },
  methods: {
    async getPostTypes() {
      const response = await WpApi.postTypes();
      this.postTypes = Object.values(response);
    },
    updateOptions() {
      let type = this.mapRow.type;
      delete this.mapRow["right"];
      if ("postType" === type) {
        this.valueOptions = this.postTypes.map(option => {
          return { key: option, value: option };
        });
      }
      if ("csvValue" === type)
        this.valueOptions = this.csvFields.map(option => {
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
