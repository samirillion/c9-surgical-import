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
    <div
      class="ifm-input-group"
      v-for="(mapRow, mapIndex) in setMap"
      :key="mapIndex"
    >
      <StepMapRow
        :mapRow="mapRow"
        :stepIndex="stepIndex"
        :mapIndex="mapIndex"
        :action="step.action"
      ></StepMapRow>
      <button
        @click="deleteMapRow(mapIndex)"
        v-if="setMap.length > 1"
        class="delete-map-row button-secondary"
      >
        -
      </button>
    </div>
    <button @click="addMapRow(setMap.length)" class="button-secondary">
      +
    </button>
  </div>
</template>

<script>
import store from "@/store";

import { getActionByName } from "@/services/Helpers";
import { getActions } from "@/services/Actions";
import { WpApi } from "@/services/WpApi";

import StepMapRow from "@/components/stepper/StepMapRow.vue";

export default {
  name: "StepMap",
  props: ["step", "stepIndex"],
  components: {
    StepMapRow
  },
  data() {
    return {
      getMap: store.state.steps[this.stepIndex].getMap,
      setMap: store.state.steps[this.stepIndex].setMap,
      actions: []
    };
  },
  mounted() {
    this.getActions();
  },
  computed: {
    getterActions: function() {
      return this.actions
        .filter(action => "getParams" in action)
        .map(action => action.id);
    },
    setterActions: function() {
      return this.actions
        .filter(action => "setParams" in action)
        .map(action => action.id);
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
