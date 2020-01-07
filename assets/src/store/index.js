import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    uploadedFileId: null,
    steps: [
      {
        id: "",
        verb: "",
        entity: "",
        getMap: [],
        setMap: []
      }
    ],
    checkedFields: []
  },
  mutations: {
    addSetter(state, setMapObj) {
      state.steps[setMapObj.stepIndex].setMap.splice(
        setMapObj.setMapLength + 1,
        0,
        {}
      );
    },
    deleteSetter(state, setMapObj) {
      state.steps[setMapObj.stepIndex].setMap.splice(setMapObj.mapIndex, 1);
    },
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
      state.steps.splice(stepLength + 1, 0, {
        id: "",
        verb: "",
        entity: "",
        getMap: [{}],
        setMap: [{}]
      });
    },
    updateEntity(stepIndex, verb) {
      state.steps[stepIndex].entity = verb;
    },
    addBaseFields(type, field) {
      state.fields.push(field);
    }
  }
});