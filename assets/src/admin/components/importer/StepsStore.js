export default {
  data: {
    uploadedFileId: [],
    steps: []
  },
  getSteps() {
    return this.data;
  },
  setFileId(id) {
    this.data.uploadedFileId = id;
  },
  removeStep(stepIndex) {
    this.data.steps.splice(stepIndex, 1);
  },
  addStep(stepIndex) {
    this.data.steps.splice(stepIndex + 1, 0, {
      parent: null,
      action: "",
      map: {}
    });
  },
  updateStep(stepIndex, stepContent) {
    this.data.steps.splice(stepIndex, 1, stepContent);
  },
  addBaseFields(type, field) {
    this.data.fields.push(field);
  }
};
