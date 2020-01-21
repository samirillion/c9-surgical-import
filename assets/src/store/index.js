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
    checkedFields: [""],
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
    },
    steps: state => {
      return JSON.stringify(state.steps);
    }
  },
  mutations: {
    presets: (state, obj) => {
      let index = obj.index;
      let setMap = [{}];
      let getMap = [{}];

      if (obj.setParams) {
        let setters = Object.keys(obj.setParams).filter(key =>
          /(required|recommended)/.test(obj.setParams[key])
        );
        if (setters.length > 0) {
          setMap = [];
          setters.forEach(param => {
            setMap.push({ left: param });
          });
        }
      }

      // populate get params
      if (obj.getParams) {
        let getters = Object.keys(obj.getParams).filter(
          key => obj.getParams[key].indexOf("required") !== -1
        );
        if (getters.length > 0) {
          getMap = [];
          getters.forEach(param => {
            getMap.push({ left: param });
          });
        }
      }

      state.steps[index].setMap = setMap;
      state.steps[index].getMap = getMap;
      state.steps[index].id = state.steps[index].action + "_" + index;
    },
    clearMap(state, index) {
      state.steps[index].setMap = {};
      state.steps[index].getMap = {};
    },
    populateSetMap(state) {},
    resetState(state) {
      Object.assign(state, getDefaultState());
    },
    setFileId(state, id) {
      state.uploadedFileId = id;
    },
    updateCheckedFields(state, checkedFields) {
      state.checkedFields = checkedFields;
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
