export default {
    data: {
        uploadedFileId: [],
        steps: [],
    },
    getData() {
        return this.data
    },
    setFileId(id) {
        this.data.uploadedFileId = id
    },
    removeStep(stepIndex) {
        this.data.steps.splice(stepIndex, 1)
    },
    addStep(step) {
        this.data.steps.push(step);
    },
    updateStep(stepIndex, stepContent) {
        this.data.steps.splice(stepIndex, 1, stepContent);
        console.log(this.data)
    },
    addBaseFields(type, field) {
        this.data.fields.push(field);
    }
}