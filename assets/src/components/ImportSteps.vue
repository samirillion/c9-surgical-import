<template>
  <details open class="ifm-create-steps">
    <summary>
      Create Import Steps
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
          <span class="ifm-step-number"> Step {{ stepIndex + 1 }} </span>
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
          <div class="ifm-entity-verb-wrapper">
            <div class="ifm-input-wrapper">
              <label for="stepVerb">Action</label>
              <select
                name="stepVerb"
                v-model="step.verb"
                @change="setStepId(stepIndex)"
              >
                <option value="create">create</option>
                <option value="update">update</option>
              </select>
            </div>
            <div class="ifm-input-wrapper">
              <label for="stepEntity">Object</label>
              <select
                name="stepEntity"
                v-model="step.entity"
                @change="setStepId(stepIndex)"
              >
                <option value="post">post</option>
                <option value="user">user</option>
                <option value="post_meta">post meta</option>
                <option value="user_meta">user meta</option>
                <option value="acf_data">acf data</option>
              </select>
            </div>
          </div>
        </div>
        <StepMap :index="stepIndex"></StepMap>
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

export default {
  name: "ImportSteps",
  components: {
    StepMap
  },
  props: ["checkedFields"],
  data() {
    return {
      steps: store.state.steps,
      customFields: []
    };
  },
  computed: {
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
