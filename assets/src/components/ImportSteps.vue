<template>
  <details open>
    <summary>
      {{ summary }}
    </summary>
    <div class="step-wrapper">
      <!-- loop through steps here -->
      <div class="ifm-step" v-for="(step, stepIndex) in steps" :key="stepIndex">
        <input type="text" class="regular-text" v-model="step.id" />
        <select v-model="step.verb" v-on:change="setStepId(stepIndex)">
          <option></option>
          <option value="create">create</option>
          <option value="update">update</option>
        </select>
        <select v-model="step.entity" v-on:change="setStepId(stepIndex)">
          <option></option>
          <option value="post">post</option>
          <option value="user">user</option>
          <option value="post_meta">post meta</option>
          <option value="user_meta">user meta</option>
        </select>
        <button
          @click="deleteStep(stepIndex)"
          v-if="steps.length > 1"
          class="button button-primary"
        >
          -
        </button>

        <!-- Add a Post or Meta -->
        <div class="post-params" v-if="step.verb !== '' && step.entity !== ''">
          <ImportMapper :stepIndex="stepIndex" />
        </div>
      </div>
      <button @click="addStep(steps.length)" class="button button-primary">
        +
      </button>
    </div>
  </details>
</template>

<script>
import store from "@/store";
import { userParams, postParams } from "@/utils/Constants";
import ParamMapper from "@/components/ParamMapper.vue";
import MetaMapper from "@/components/MetaMapper.vue";

export default {
  name: "ImportSteps",
  components: {
    ParamMapper,
    MetaMapper
  },
  props: ["checkedFields", "summary"],
  data() {
    return {
      postParams,
      userParams,
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
    onParamUpdate: function(newValue) {
      console.log(newValue);
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
