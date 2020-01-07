<template>
  <div class="ifm-params">
    <h4>Set Values</h4>
    <div
      class="ifm-map-setters"
      v-for="(setter, setIndex) in setMap"
      :key="setIndex"
    >
      <select v-model="setter.left">
        <option></option>
        <option v-for="(param, paramIndex) in params" :key="paramIndex">
          {{ param }}
        </option>
      </select>
      <v-select :options="store.state.checkedFields" v-model="setter.right" />

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
      setMap: store.state.steps[this.index].setMap
    };
  },
  computed: {
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
    }
  }
};
</script>
