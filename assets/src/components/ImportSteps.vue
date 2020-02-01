<template>
  <details open class="ifm-create-steps">
    <summary>
      Create Import Steps (runs on each CSV row)
    </summary>
    <div class="ifm-steps-wrapper">
      <!-- loop through steps here -->
      <details
        open
        class="ifm-step"
        v-for="(step, stepIndex) in steps"
        :key="stepIndex"
      >
        <summary>
          <span class="ifm-step-number">Step {{ stepIndex + 1 }}</span>
          <span class="ifm-summary-right">
            <span class="ifm-step-name" v-show="step.action">
              {{ step.id }}
            </span>
            <button
              @click="deleteStep(stepIndex)"
              v-if="steps.length > 1"
              class="button buttonp-primary"
            >
              -
            </button>
          </span>
        </summary>
        <div class="ifm-define-action">
          <h4>Define Action</h4>
          <div class="ifm-action-wrapper">
            <div class="ifm-input-wrapper">
              <v-select
                :options="actions"
                index="id"
                label="displayName"
                v-model="step.action"
                @input="presets(stepIndex, step.action)"
              ></v-select>
            </div>
          </div>
        </div>
        <StepMap
          v-if="getterActions.includes(step.action)"
          :title="'Where'"
          :index="stepIndex"
          :isGetter="true"
          :actions="actions"
        ></StepMap>
        <StepMap
          v-if="setterActions.includes(step.action)"
          :title="'Set Values'"
          :index="stepIndex"
          :isGetter="false"
          :actions="actions"
        ></StepMap>
        <button @click="addStep(stepIndex)" class="button button-primary wide">
          + Add Step
        </button>
      </details>
    </div>
  </details>
</template>

<script>
import store from "@/store";
import StepMap from "@/components/StepMap.vue";
import { getActions } from "@/services/Actions";
import { getActionByName } from "@/services/Helpers";

export default {
  name: "ImportSteps",
  components: {
    StepMap
  },
  data() {
    return {
      steps: store.state.steps,
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
    }
  },
  methods: {
    async getActions() {
      const actions = await getActions();
      this.actions = actions;
    },
    presets: function(index, actionName) {
      let action = getActionByName(actionName, this.actions);
      if (undefined !== action) {
        store.commit("stepPresets", {
          index,
          getParams: action.getParams,
          setParams: action.setParams
        });
      }
    },
    stepPlusOne: function(index) {
      return index + 1;
    },
    addStep(stepIndex) {
      store.commit("addStep", stepIndex);
    },
    deleteStep(stepIndex) {
      store.commit("removeStep", stepIndex);
    }
  }
};
</script>
