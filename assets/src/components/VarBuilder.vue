<template>
  <details class="ifm-var-builder" open>
    <summary>
      Complex Values
    </summary>
    <div
      class="ifm-custom-var"
      v-for="(customVar, index) in customVars"
      :key="index"
    >
      <div class="ifm-custom-var-wrapper">
        <div class="custom-var-header">
          <div class="var-type-wrapper">
            <label for="varType">Type</label>
            <select name="varType">
              <option></option>
              <option value="string">String</option>
              <option value="regex">Regex Pattern</option>
            </select>
          </div>
          <div class="var-name-wrapper">
            <label for="varName">Name</label>
            <input type="text" name="varName" />
          </div>
          <button
            class="button button-primary"
            v-if="customVars.length > 1"
            @click="removeVar(index)"
          >
            -
          </button>
        </div>
        <div class="var-value">
          <label class="var-input-label" for="varValue">Value</label>
          <input type="text" name="varValue" />
        </div>
        <!-- <button @click="varType">+</button> -->
        <!-- <div
          v-for="(subVar, subVarIndex) in customVars[index]"
          :key="subVarIndex"
        >
          {{ subVar.type }}
          <input type="text" />
          <button @click="removeSubVar">-</button>
        </div> -->
      </div>
      <button
        class="button button-primary add-custom-value"
        @click="addVar(index)"
      >
        + Add Custom Value
      </button>
    </div>
  </details>
</template>

<script>
export default {
  name: "VarBuilder",
  data() {
    return {
      nextSubVar: "field",
      customVars: [[]]
    };
  },
  methods: {
    addVar(index) {
      this.customVars.splice(parseInt(index) + 1, 0, [[]]);
    },
    removeVar(index) {
      this.customVars.splice(parseInt(index), 1);
    },
    varType(event) {
      console.log(event);
    },
    removeSubVar(event) {
      console.log(event);
    }
  }
};
</script>
