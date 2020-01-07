<template>
  <details class="ifm-var-builder">
    <summary>
      Custom Right Hand Variables
    </summary>
    <div
      class="ifm-custom-var"
      v-for="(customVar, index) in customVars"
      :key="index"
    >
      <label for="varName">Variable Name</label>
      <input type="text" name="varName" />
      <label for="addSubVar">Add a:</label>
      <select name="addSubVar">
        <option></option>
        <option value="field">Field</option>
        <option value="string">String</option>
        <option value="string">Regex</option>
      </select>
      <button @click="addSubVar">+</button>
      <br />
      {{ customVar[0] }}
      <div
        v-for="(subVar, subVarIndex) in customVars[index]"
        :key="subVarIndex"
      >
        {{ subVar.type }}
        <br />
        <input type="text" />
        <button @click="removeSubVar">-</button>
      </div>
      <br height="0" />
      <button class="button button-primary" @click="addVar(index)">+</button>
      <button
        class="button button-primary"
        v-if="customVars.length > 1"
        @click="removeVar(index)"
      >
        -
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
    addSubVar(event) {
      console.log(event);
    },
    removeSubVar(event) {
      console.log(event);
    }
  }
};
</script>
