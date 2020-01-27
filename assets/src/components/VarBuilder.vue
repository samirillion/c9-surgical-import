<template>
  <details class="ifm-var-builder" open>
    <summary>
      Complex Values
    </summary>
    <details
      class="ifm-custom-var"
      v-for="(customVar, varIndex) in customVars"
      :key="varIndex"
      open
    >
      <summary class="ifm-custom-var-wrapper" open>
        <div class="content-left">
          {{ customVar.id ? customVar.id : "Unnamed Value" }}
        </div>
        <button
          class="button button-primary"
          v-if="customVars.length > 1"
          @click="removeVar(varIndex)"
        >
          -
        </button>
        <button
          class="button button-primary add-custom-value"
          @click="addVar(varIndex)"
        >
          +
        </button>
      </summary>
      <div class="custom-var-header">
        <div class="ifm-params ifm-name">
          <label for="varName">Name</label>
          <input
            type="text"
            name="varName"
            class="var-name"
            v-model="customVar.id"
          />
        </div>
      </div>
      <StringEditor :id="'custom_var_' + varIndex"></StringEditor>
    </details>
    <StepsRaw :open="true" :summary="'View Steps as Json'" />
  </details>
</template>

<script>
import StepsRaw from "@/components/StepsRaw.vue";
import StringEditor from "@/components/StringEditor.vue";

import store from "@/store";

export default {
  name: "VarBuilder",
  components: {
    StepsRaw,
    StringEditor
  },
  data() {
    return {
      customVars: store.state.customVars,
      valueOptions: []
    };
  },
  watch: {
    customVars: function(vars) {
      store.state.customVars = vars;
    }
  },
  methods: {
    updateOptions(type) {
      if ("csvValue" === type) {
        this.valueOptions = store.state.checkedFields;
      } else {
        this.valueOptions = [];
      }
    },
    addVar(index) {
      this.customVars.splice(index + 1, 0, {
        id: "custom_var_" + (index + 1),
        script: ""
      });
    },
    removeVar(index) {
      this.customVars.splice(parseInt(index), 1);
    }
  }
};
</script>
