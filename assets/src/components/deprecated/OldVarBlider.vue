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
      </summary>
      <div class="custom-var-header">
        <div class="ifm-params">
          <label for="varName">Name</label>
          <input
            type="text"
            name="varName"
            class="var-name"
            v-model="customVar.id"
          />
        </div>
      </div>
      <h4>Segments (to concatenate)</h4>
      <div class="map-wrapper">
        <div
          class="ifm-params"
          v-for="(varSegment, segmentIndex) in customVar.map"
          :key="segmentIndex"
        >
          <div class="ifm-input-wrapper">
            <label for="mapRowType">Type</label>
            <select
              name="mapRowType"
              v-model="varSegment.type"
              @change="updateOptions(varSegment.type)"
            >
              <option value="csvValue">csv value</option>
              <option value="string">string</option>
            </select>
          </div>
          <div class="ifm-input-wrapper">
            <label for="mapRowRight">Value</label>
            <v-select
              name="mapRowRight"
              :options="valueOptions"
              v-model="varSegment.value"
              v-if="'string' !== varSegment.type"
            />
            <input
              type="text"
              name="mapRowRight"
              v-model="varSegment.value"
              v-else
            />
          </div>
          <button
            @click="deleteVarSegment(varIndex, segmentIndex)"
            v-if="customVar.map.length > 1"
            class="delete-map-row button-secondary"
          >
            -
          </button>
          <button
            @click="addVarSegment(varIndex, segmentIndex)"
            class="button-secondary"
          >
            +
          </button>
        </div>
      </div>
      <button
        class="button button-primary add-custom-value"
        @click="addVar(varIndex)"
      >
        + Add Custom Value
      </button>
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
        id: "",
        map: [{}]
      });
    },
    removeVar(index) {
      this.customVars.splice(parseInt(index), 1);
    },
    addVarSegment(varIndex, segmentIndex) {
      console.log(this.customVars[varIndex]);
      this.customVars[varIndex].map.splice(segmentIndex + 1, 0, {});
    },
    deleteVarSegment(varIndex, segmentIndex) {
      this.customVars[varIndex].map.splice(segmentIndex, 1);
    }
  }
};
</script>
