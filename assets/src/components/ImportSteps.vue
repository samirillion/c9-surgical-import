<template>
  <details open>
    <summary>
      {{ summary }}
    </summary>
    <div class="step-wrapper">
      <!-- loop through steps here -->
      <div class="step" v-for="(step, stepIndex) in steps" :key="stepIndex">
        <input type="text" class="step-name" v-model="step.id" />
        <select v-model="step.verb" v-on:change="setStepId(stepIndex)">
          <option value="create">create</option>
          <option value="update">update</option>
          <option value="delete">delete</option>
          <option value="addMeta" v-if="stepIndex !== 0">add meta</option>
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

        <span v-if="step.action !== ''">
          <b>where</b>
        </span>

        <!-- Add a Post or Meta -->
        <div class="post-params" v-if="step.action !== 'addMeta'">
          <ParamMapper
            :actions="getParams(step.action)"
            :inputFields="fields"
            :step="stepIndex"
          />
        </div>
        <div class="add-meta" v-else-if="step.action == 'addMeta'">
          <MetaMapper :inputFields="fields" :step="stepIndex" />
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
import { userParams, postParams, importActions } from "@/utils/Constants";
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
      postParams: postParams,
      userParams: userParams,
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
    getParams(action) {
      if ("newPost" === action) {
        return postParams;
      } else if ("newUser" === action) {
        return userParams;
      }
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
