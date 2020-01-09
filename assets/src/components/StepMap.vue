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
        <select name="mapRowLeft" v-model="mapRow.left">
          <option v-for="(param, paramIndex) in params" :key="paramIndex">
            {{ param }}
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
              >Post Type</option
            >
            <option value="csvValue">CSV Value</option>
            <option value="stepId">Previous Step ID</option>
            <option value="string">String</option>
            <option value="customVar">Custom Variable</option>
          </select>
        </div>
        <div class="ifm-input-wrapper">
          <label for="mapRowRight">Value</label>
          <v-select
            name="mapRowRight"
            :options="valueOptions"
            v-model="mapRow.right"
            v-if="'string' !== mapRow.type"
          />
          <input type="text" name="mapRowRight" v-else />
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
import { WpApi } from "@/services/WpApi";

export default {
  name: "StepMap",
  props: ["index", "isGetter", "title"],
  data() {
    return {
      step: store.state.steps[this.index],
      setMap: store.state.steps[this.index].setMap,
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
    params: function() {
      if (this.isGetter) return this.getParams;
      return this.setParams;
    },
    stepMap: function() {
      if (this.isGetter) return this.getMap;
      return this.setMap;
    },
    getParams: function() {
      if ("post" === this.step.entity) return getPost;
      if ("user" === this.step.entity) return getUser;
    },
    setParams: function() {
      if ("post" === this.step.entity) return createPost;
      if ("user" === this.step.entity) return createUser;
      return [];
    }
  },
  methods: {
    async getPostTypes() {
      const response = await WpApi.postTypes();
      console.log(response);
      this.postTypes = Object.values(response);
    },
    updateOptions(type) {
      if ("postType" === type) this.valueOptions = this.postTypes;
      if ("csvValue" === type) this.valueOptions = store.state.checkedFields;
      if ("stepId" === type) this.valueOptions = store.getters.stepIds;
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
