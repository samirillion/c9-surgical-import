<template>
  <div class="step-wrapper">
    <!-- loop through steps here -->
    <div class="step" v-for="(step, stepIndex) in steps" :key="stepIndex">
      <h4 class="step-name">
        {{ step.verb }} {{ step.entity }} {{ stepPlusOne(stepIndex) }}
      </h4>

      <select v-model="step.action">
        <option></option>
        <option value="newPost">create a post</option>
        <option value="newUser">create a user</option>
        <option value="addMeta" v-if="stepIndex !== 0">add meta</option>
      </select>
      <select v-model="step.verb">
        <option value="create">create</option>
        <option value="update">update</option>
        <option value="delete">delete</option>
        <option value="addMeta" v-if="stepIndex !== 0">add meta</option>
      </select>
      <select v-model="step.entity">
        <option></option>
        <option value="post">post</option>
        <option value="user">user</option>
        <option value="postMeta">post meta</option>
        <option value="userMeta">user meta</option>
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
</template>

<script>
import store from "@/admin/store";
import {
  userParams,
  postParams,
  importActions
} from "@/admin/components/importer/Constants";
import ParamMapper from "@/admin/components/importer/ParamMapper.vue";
import MetaMapper from "@/admin/components/importer/MetaMapper.vue";
import StepsStore from "@/admin/components/importer/StepsStore";

export default {
  name: "ImportSteps",
  components: {
    ParamMapper,
    MetaMapper
  },
  props: ["checkedFields"],
  data() {
    return {
      postParams: postParams,
      userParams: userParams,
      steps: [
        {
          action: "",
          map: {}
        }
      ],
      customFields: []
    };
  },
  computed: {
    fields: function() {
      return this.checkedFields.concat(this.customFields);
    }
  },
  methods: {
    onParamUpdate: function(newValue) {
      console.log(newValue);
    },
    stepPlusOne: function(index) {
      return index + 1;
    },
    getParams(action) {
      if (action == "newPost") {
        return postParams;
      } else if (action == "newUser") {
        return userParams;
      }
    },
    addStep(stepIndex) {
      StepsStore.addStep(stepIndex);
      this.steps.splice(stepIndex + 1, 0, {
        parent: null,
        action: "",
        map: {}
      });
    },
    deleteStep(stepIndex) {
      this.steps.splice(stepIndex, 1);
      StepsStore.removeStep(stepIndex);
    }
  }
};
</script>
