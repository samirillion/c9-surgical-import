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
              class="button button-primary"
            >
              -
            </button>
          </span>
        </summary>
        <ImportStep :step="step" :stepIndex="stepIndex"></ImportStep>
        <button @click="addStep(stepIndex)" class="button button-primary wide">
          + Add Step
        </button>
      </details>
    </div>
  </details>
</template>

<script>
import store from "@/store";

import ImportStep from "@/components/stepper/ImportStep.vue";

export default {
  name: "ImportSteps",
  components: {
    ImportStep
  },
  data() {
    return {
      steps: store.state.steps,
      actions: []
    };
  },
  methods: {
    addStep(stepIndex) {
      store.commit("addStep", stepIndex);
    },
    deleteStep(stepIndex) {
      store.commit("removeStep", stepIndex);
    }
  }
};
</script>
