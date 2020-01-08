<template>
  <div class="ifm-params">
    <h4>Set Values</h4>
    <div
      class="ifm-input-group"
      v-for="(setter, setIndex) in setMap"
      :key="setIndex"
    >
      <div class="ifm-input-wrapper">
        <label for="setterLeft">Parameter</label>
        <select name="setterLeft" v-model="setter.left">
          <option v-for="(param, paramIndex) in params" :key="paramIndex">
            {{ param }}
          </option>
        </select>
      </div>
      <div class="ifm-input-wrapper">
        <label for="setterType">Type</label>
        <select name="setterType" v-model="setter.type">
          <option value="csvValue">CSV Value</option>
          <option value="stepId">Previous Step ID</option>
          <option value="string">String</option>
          <option value="customVar">Custom Variable</option>
        </select>
      </div>
      <div class="ifm-input-wrapper">
        <label for="setterRight">Value</label>
        <v-select
          name="setterRight"
          :options="valueOptions"
          v-model="setter.right"
        />
      </div>
      <button @click="deleteSetter(setIndex)" v-if="setMap.length > 1">
        -
      </button>
    </div>
    <button @click="addSetter(setMap.length)">
      +
    </button>
  </div>
</template>

<script>
import store from "@/store";
import { userParams, postParams } from "@/utils/Constants";

export default {
  name: "ImportMapper",
  props: ["index"],
  data() {
    return {
      valueType: null,
      store,
      step: store.state.steps[this.index],
      setMap: store.state.steps[this.index].setMap,
      getMap: store.state.steps[this.index].getMap
    };
  },
  computed: {
    valueOptions: function() {
      if("csvValue" === this.setter.type) {
        return store.state.checkedFields
      } else if ("stepId" === this.setter.type) {
        return store.getters.customVars
      } else if ("")
    }
    params: function() {
      if ("post" === this.step.entity) {
        return postParams;
      } else if ("user" === this.step.entity) {
        return userParams;
      }
    }
  },
  methods: {
    addSetter(setMapLength) {
      store.commit("addSetter", {
        stepIndex: this.index,
        mapIndex: setMapLength
      });
    },
    deleteSetter(setIndex) {
      store.commit("deleteSetter", {
        stepIndex: this.index,
        mapIndex: setIndex
      });
    },
    addGetter(getMapLength) {
      store.commit("addGetter", {
        stepIndex: this.index,
        mapIndex: getMapLength
      });
    },
    deleteGetter(getIndex) {
      store.commit("deleteGetter", {
        stepIndex: this.index,
        mapIndex: getIndex
      });
    }
  }
};
</script>
