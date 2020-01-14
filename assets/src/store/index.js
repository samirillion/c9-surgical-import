import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const getDefaultState = () => {
  return {
    uploadedFileId: null,
    steps: [
      {
        id: "",
        action: "",
        getMap: [{}],
        setMap: [{}]
      }
    ],
    checkedFields: ["cool"],
    customVars: [{}]
  };
};

const state = getDefaultState();

export default new Vuex.Store({
  state,
  getters: {
    customVars: state => {
      return state.customVars.map(customVar => customVar.name);
    },
    stepIds: state => {
      return state.steps.map(step => step.id);
    }
  },
  mutations: {
    resetState(state) {
      Object.assign(state, getDefaultState());
    },
    setFileId(state, id) {
      state.uploadedFileId = id;
    },
    updateCheckedFields(state, checkedFields) {
      state.checkedFields = checkedFields;
    },
    setStepId(state, index) {
      state.steps[index].id = state.steps[index].action + "_" + index;
    },
    addStep(state, stepLength) {
      state.steps.splice(stepLength + 1, 0, {
        id: "create_post_" + stepLength,
        action: "create_post",
        getMap: [{}],
        setMap: [{}]
      });
    },
    removeStep(state, stepIndex) {
      state.steps.splice(stepIndex, 1);
    },
    addMapRow(state, stepMapArgs) {
      if (stepMapArgs.isGetter) {
        state.steps[stepMapArgs.stepIndex].getMap.splice(
          stepMapArgs.mapLength + 1,
          0,
          {}
        );
      } else {
        state.steps[stepMapArgs.stepIndex].setMap.splice(
          stepMapArgs.mapLength + 1,
          0,
          {}
        );
      }
    },
    deleteMapRow(state, stepMapArgs) {
      if (stepMapArgs.isGetter) {
        state.steps[stepMapArgs.stepIndex].getMap.splice(
          stepMapArgs.mapIndex,
          1
        );
      } else {
        state.steps[stepMapArgs.stepIndex].setMap.splice(
          stepMapArgs.mapIndex,
          1
        );
      }
    },
    updateEntity(stepIndex, verb) {
      state.steps[stepIndex].entity = verb;
    },
    addBaseFields(type, field) {
      state.fields.push(field);
    }
  }
});
