<template>
  <div class="ifm-params">
    <h4>Set Values</h4>
    <div v-for="(setter, setIndex) in setMap" :key="setIndex">
      <select v-model="setMap.left">
        <option v-for="(param, paramIndex) in params" :key="paramIndex">
          {{ param }}
        </option>
      </select>
      <button
        @click="deleteSetter(setIndex)"
        v-if="setMap > 1"
        class="button button-primary"
      >
        -
      </button>
    </div>
    <button @click="addSetter(setMap.right)" class="button button-primary">
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
      step: store.state.steps[this.index],
      setMap: store.state.steps[this.index].setMap
    };
  },
  computed: {
    params: function() {
      if ("post" === step.entity) {
        return postParams;
      } else if ("user" === step.entity) {
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
    deleteSetter(setIndex) {}
  }
};
</script>
