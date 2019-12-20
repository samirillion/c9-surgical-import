<template>
  <div>
    <div class="param-to-field" v-for="(param, index) in dbParams" :key="index">
      <v-select @input="mapParamValue($event, param)" :options="inputFields" :name="param"></v-select>
      <label :for="param">{{ param }}</label>
    </div>
  </div>
</template>
<script>
import StepsStore from "@/admin/components/importer/StepsStore";

export default {
  name: "ParamMapper",
  props: ["dbParams", "inputFields", "step"],
  data() {
    return {
      map: {}
    };
  },
  methods: {
    mapParamValue(inputField, dbParam) {
      if (inputField === null) {
        // shite
        delete this.map[dbParam];
        StepsStore.updateStep(parseInt(this.step), this.map);
      } else {
        this.map[dbParam] = inputField;
        StepsStore.updateStep(parseInt(this.step), this.map);
      }
    }
  }
};
</script>

<style scoped>
.param-to-field {
  margin-top: 5px;
  margin-bottom: 5px;
}
.param-to-field label,
.param-to-field .dropdown {
  display: inline-block;
  min-width: 200px;
}
</style>