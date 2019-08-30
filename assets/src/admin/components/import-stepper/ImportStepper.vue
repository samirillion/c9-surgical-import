<template>
  <div class="step-wrapper">
    <div class="step" v-for="(step, stepIndex) in steps" :key="stepIndex">
      <h4 class="step-name">Step {{stepPlusOne(stepIndex)}}</h4>

      <select v-model="step.action">
        <option></option>
        <option value="newPost">create a post</option>
        <option value="newUser">create a user</option>
        <option value="addMeta" v-if="stepIndex !== 0">add meta</option>
      </select>
      
      <button @click="addStep(stepIndex)" class="button button-primary" v-if="step.action !== ''">+</button>
      <button @click="deleteStep(stepIndex)" v-if="steps.length > 1" class="button button-primary">-</button>
      
      <span v-if="step.action !== ''">
        <b>then</b>
      </span>

      <!-- Add a Post or Meta -->
      <div class="post-params" v-if="step.action !== 'addMeta'">
        <ParamMapper :dbParams="getParams(step.action)" :inputFields="fields" :step="stepIndex"/>
      </div>
      <div class="add-meta" v-else-if="step.action == 'addMeta'">
        <MetaMapper :inputFields="fields" :step="stepIndex"/>
      </div>
    </div>
  </div>
</template>

<script>
import {
  userParams,
  postParams
} from "@/admin/components/import-stepper/Constants";
import ParamMapper from "@/admin/components/import-stepper/ParamMapper.vue";
import MetaMapper from "@/admin/components/import-stepper/MetaMapper.vue";
import ArgStore from "@/admin/components/import-stepper/ArgStore";

export default {
  name: "ImportStepper",
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
      console.log(newValue)
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
      this.steps.splice(stepIndex + 1, 0, {
        parent: null,
        action: "",
        map: {}
      });
    },
    deleteStep(stepIndex) {
      this.steps.splice(stepIndex, 1);
      ArgStore.removeStep(stepIndex);
    }
  }
};
</script>