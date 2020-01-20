<template>
  <div class="ifm-params">
    <h4>{{ title }}</h4>
    <div
      class="ifm-input-group"
      v-for="(mapRow, mapIndex) in stepMap"
      :key="mapIndex"
    >
      <div class="ifm-input-wrapper">
        <label for="mapRowLeft">Parameter</label>
        <input
          v-if="action.id.endsWith('meta') && false === isGetter"
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
            <option value="customVar">complex variable</option>
          </select>
        </div>
        <div class="ifm-input-wrapper">
          <label for="mapRowRight">Value</label>
          <v-select
            name="mapRowRight"
            :options="valueOptions"
            v-model="mapRow.right"
            v-if="'string' !== mapRow.type"
            @input="updateOptions(mapRow.type)"
            :key="checkedFields.length"
          />
          <input type="text" name="mapRowRight" v-model="mapRow.right" v-else />
        </div>
      </div>
      <button
        @click="deleteMapRow(mapIndex)"
        v-if="stepMap.length > 1"
        class="delete-map-row button-secondary"
      >
        -
      </button>
    </div>
    <button
      @click="addMapRow(stepMap.length)"
      v-if="!isGetter"
      class="button-secondary"
    >
      +
    </button>
  </div>
</template>

<script>
import store from "@/store";
import { getUser, getPost, createUser, createPost } from "@/services/Params";
import { getActions } from "@/services/Actions";
import { WpApi } from "@/services/WpApi";

export default {
  name: "StepMap",
  props: ["index", "isGetter", "title", "actions"],
  data() {
    return {
      step: store.state.steps[this.index],
      getMap: store.state.steps[this.index].getMap,
      valueOptions: [],
      postTypes: ["page", "post", "comment"]
    };
  },
  mounted() {
    this.getPostTypes();
  },
  // need to throw some extra conditionals in here because this map works for setting and getting values
  computed: {
    checkedFields: {
      get: () => store.state.checkedFields,
      set: value => store.commit("updateCheckedFields", value)
    },
    params: function() {
      if (this.isGetter) return this.action.getParams;
      return this.setParams;
    },
    setParams: function() {
      if (Array.isArray(this.action.setParams)) {
        return this.action.setParams.reduce(function(result, item) {
          var key = Object.keys(item)[0]; //first property: a, b, c
          result[key] = item[key];
          return result;
        }, {});
      }
      return this.action.setParams;
    },
    action: function() {
      return this.actions.find(action => this.step.action === action.id);
    },
    setMap: function() {
      return store.state.steps[this.index].setMap;
    },
    stepMap: function() {
      if (this.isGetter) return this.getMap;
      return this.setMap;
    },
    stepIds: function() {
      return store.getters.stepIds.slice(
        0,
        this.index - store.getters.stepIds.length
      );
    }
  },
  methods: {
    async getPostTypes() {
      const response = await WpApi.postTypes();
      this.postTypes = Object.values(response);
    },
    updateOptions(type) {
      if ("postType" === type) this.valueOptions = this.postTypes;
      if ("csvValue" === type) this.valueOptions = this.checkedFields;
      if ("stepId" === type) this.valueOptions = this.stepIds;
      if ("customVar" === type) this.valueOptions = store.getters.customVars;
    },
    addMapRow(mapLength) {
      store.commit("addMapRow", {
        stepIndex: this.index,
        mapLength,
        isGetter: this.isGetter
      });
    },
    deleteMapRow(mapIndex) {
      store.commit("deleteMapRow", {
        stepIndex: this.index,
        mapIndex,
        isGetter: this.isGetter
      });
    }
  }
};
</script>
