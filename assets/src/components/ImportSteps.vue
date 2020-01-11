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
              <label for="stepAction">Action</label>
              <select
                name="stepAction"
                v-model="step.action"
                @change="setStepId(stepIndex)"
              >
                <option value="create_post">create post</option>
                <option value="update_post">update post</option>
                <option value="create_user">create user</option>
                <option value="update_user">update user</option>
                <option value="taxonomy">add categories, tags, etc</option>
                <option value="post_meta">add post meta</option>
                <option value="user_meta">add user meta</option>
                <option value="acf_data">add acf data</option>
                <option value="get_val">get post or user id</option>
              </select>
            </div>
          </div>
        </div>
        <StepMap
          v-if="
            'taxonomy' === step.action ||
              'update_user' === step.action ||
              'update_post' === step.action ||
              'get_val' === step.action ||
              'post_meta' === step.action ||
              'user_meta' === step.action ||
              'acf_data' === step.action
          "
          :title="'Where'"
          :index="stepIndex"
          :isGetter="true"
        ></StepMap>
        <StepMap
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
