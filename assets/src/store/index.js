import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    uploadedFileId: [],
    steps: [{ id: "", verb: "", entity: "", map: {} }]
  },
  mutations: {
    setFileId(state, id) {
      state.uploadedFileId = id;
    },
    removeStep(state, stepIndex) {
      state.steps.splice(stepIndex, 1);
    },
    addStep(state, stepIndex) {
      state.steps.splice(stepIndex + 1, 0, { id: "", verb: "", entity: "", map: {} });
    },
    updateVerb(stepIndex, verb) {
      state.steps[stepIndex].verb = verb;
    },
    updateEntity(stepIndex, verb) {
      state.steps[stepIndex].entity = verb;
    },
    addBaseFields(type, field) {
      state.fields.push(field);
    }
  }
});
