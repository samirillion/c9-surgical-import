import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const stepScheme = {
  id: "",
  verb: "",
  entity: "",
  getMap: [{}],
  setMap: [{}]
}

export default new Vuex.Store({
  state: {
    uploadedFileId: null,
    steps: [stepScheme],
    checkedFields: []
  },
  mutations: {
    updateCheckedFields(state, checkedFields) {
      state.checkedFields = checkedFields;
    },
    setFileId(state, id) {
      state.uploadedFileId = id;
    },
    setStepId(state, index) {
      state.steps[index].id =
        state.steps[index].verb + "_" + state.steps[index].entity + "_" + index;
    },
    removeStep(state, stepIndex) {
      state.steps.splice(stepIndex, 1);
    },
    addStep(state, stepLength) {
      state.steps.splice(stepLength + 1, 0, stepScheme);
    },
    updateEntity(stepIndex, verb) {
      state.steps[stepIndex].entity = verb;
    },
    addBaseFields(type, field) {
      state.fields.push(field);
    }
  }
});
