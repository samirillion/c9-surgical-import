<template>
  <div class="ifm-params">
    <div class="ifm-define-action">
      <h4>Define Action</h4>
      <div class="ifm-action-wrapper">
        <div class="ifm-input-wrapper">
          <v-select
            :options="actions"
            index="id"
            label="displayName"
            v-model="step.action"
            @input="setPresets(stepIndex, step.action)"
          ></v-select>
        </div>
      </div>
    </div>
    <div class="getters" v-show="action && action.getParams">
      <h4>Get By</h4>
      <div
        class="ifm-input-group"
        v-for="(mapRow, mapIndex) in getMap"
        :key="mapIndex"
      >
        <StepMapRow
          :mapRow="mapRow"
          :stepIndex="stepIndex"
          :mapIndex="mapIndex"
          :action="action"
          :params="action.getParams"
        ></StepMapRow>
      </div>
    </div>
    <div v-show="action && action.setParams" class="setters">
      <h4>Set Values</h4>
      <div
        class="ifm-input-group"
        v-for="(mapRow, mapIndex) in setMap"
        :key="mapIndex"
      >
        <StepMapRow
          :mapRow="mapRow"
          :stepIndex="stepIndex"
          :mapIndex="mapIndex"
          :action="action"
          :params="action.setParams"
        ></StepMapRow>
        <button
          @click="deleteMapRow(mapIndex)"
          v-show="setMap.length > 1"
          class="delete-map-row button-secondary"
        >
          -
        </button>
      </div>
      <button
        v-show="'' !== step.action"
        @click="addMapRow(setMap.length)"
        class="button-secondary"
      >
        +
      </button>
    </div>
  </div>
</template>

<script>
import store from "@/store";

import { getActionByName } from "@/services/Helpers";
import { getActions } from "@/services/Actions";
import { WpApi } from "@/services/WpApi";

import StepMapRow from "@/components/stepper/StepMapRow.vue";

export default {
  name: "ImportStep",
  props: ["step", "stepIndex"],
  components: {
    StepMapRow
  },
  data() {
    return {
      actions: []
    };
  },
  created() {
    this.getActions();
  },
  computed: {
    getMap: function() {
      return store.state.steps[this.stepIndex].getMap;
    },
    setMap: function() {
      return store.state.steps[this.stepIndex].setMap;
    },
    action: function() {
      return this.actions.find(action => this.step.action === action.id);
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
    }
  },
  methods: {
    async getActions() {
      const actions = await getActions();
      this.actions = actions;
    },
    setPresets(index, actionName) {
      let action = getActionByName(actionName, this.actions);
      if (undefined !== action) {
        store.commit("stepPresets", {
          index,
          getParams: action.getParams,
          setParams: action.setParams
        });
      }
    },
    addMapRow(mapLength) {
      store.commit("addMapRow", {
        stepIndex: this.stepIndex,
        mapLength,
        isGetter: false
      });
    },
    deleteMapRow(mapIndex) {
      store.commit("deleteMapRow", {
        stepIndex: this.stepIndex,
        mapIndex,
        isGetter: false
      });
    }
  }
};
</script>
