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
            <span class="ifm-step-name">
              {{ step.id }}
            </span>
            <button
              @click="deleteStep(stepIndex)"
              v-if="steps.length > 1"
              class="button button-primary"
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
                @input="setStepId(stepIndex)"
              ></v-select>
            </div>
          </div>
        </div>
        <StepMap
          v-if="getterActions.includes(step.action)"
          :title="'Where'"
          :index="stepIndex"
          :isGetter="true"
        ></StepMap>
        <StepMap
          v-if="setterActions.includes(step.action)"
          :title="'Set Values'"
          :index="stepIndex"
          :isGetter="false"
        ></StepMap>
        <button
          @click="addStep(steps.length)"
          class="button button-primary wide"
        >
          + Add Step
        </button>
      </details>
    </div>
  </details>
</template>

<script>
import store from "@/store";
import StepMap from "@/components/StepMap.vue";
import { actions } from "@/services/Actions";

export default {
  name: "ImportSteps",
  components: {
    StepMap
  },
  props: ["checkedFields"],
  data() {
    return {
      steps: store.state.steps,
      customFields: [],
      actions
    };
  },
  computed: {
    getterActions: function() {
      return actions
        .filter(action => "getParams" in action)
        .map(action => action.id);
    },
    setterActions: function() {
      return actions
        .filter(action => "setParams" in action)
        .map(action => action.id);
    },
    fields: function() {
      return this.checkedFields.concat(this.customFields);
    }
  },
  methods: {
    setStepId: function(index) {
      store.commit("setStepId", index);
    },
    stepPlusOne: function(index) {
      return index + 1;
    },
    addStep(stepLength) {
      store.commit("addStep", stepLength);
    },
    deleteStep(stepIndex) {
      store.commit("removeStep", stepIndex);
    }
  }
};
</script>
