import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    uploadedFileId: [],
    steps: []
  },
  getters: {
    steps: state => {
      return state.steps;
    }
  },
  mutations: {
    setFileId(state, id) {
      state.uploadedFileId = id;
    },
    removeStep(state, stepIndex) {
      state.steps.splice(stepIndex, 1);
    },
    addStep(state, stepIndex) {
      state.steps.splice(stepIndex + 1, 0, {
        parent: null,
        action: "",
        map: {}
      });
    },
    updateStep(stepIndex, stepContent) {
      state.steps.splice(stepIndex, 1, stepContent);
    },
    addBaseFields(type, field) {
      state.fields.push(field);
    }
  }
});
