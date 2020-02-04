<template>
  <div class="import">
    <FileUploader @uploaded="onUpload" />
    <CsvPreview v-if="parsedCsv.length > 1" :parsedCsv="parsedCsv" />
    <div v-if="checkedFields && checkedFields.length > 0">
      <hr />
      <div class="ifm-steps-and-vars">
        <ImportSteps />
        <div class="submit-wrapper">
          <button class="button button-secondary">Validate Input</button>
          <button class="button button-primary" @click="runImport">
            Run Import
          </button>
        </div>
      </div>
      <VarBuilder />
    </div>
  </div>
</template>

<script>
import store from "@/store";

import axios from "axios";
import { WpApi } from "@/services/WpApi";
import { CsvToArray } from "@/utils/CsvToArray";

import FileUploader from "@/components/FileUploader.vue";
import VarBuilder from "@/components/VarBuilder.vue";
import CsvPreview from "@/components/CsvPreview.vue";
import ImportSteps from "@/components/stepper/ImportSteps.vue";

export default {
  name: "Import",
  components: {
    FileUploader,
    ImportSteps,
    VarBuilder,
    CsvPreview
  },
  data() {
    return {
      file: [],
      uploadObject: {},
      rawCsv: {},
      parsedCsv: []
    };
  },
  computed: {
    checkedFields: {
      get: () => store.state.checkedFields,
      set: value => store.commit("updateCheckedFields", value)
    }
  },
  methods: {
    async runImport() {
      const response = await WpApi.runImport()
        .param("upload_object", this.uploadObject)
        .param("import_steps", store.getters.jsonSteps)
        .param("import_vars", store.getters.jsonVars);
    },
    async onUpload(uploadId) {
      store.commit("setFileId", uploadId);
      // StepsStore.setFileId(uploadId);
      const uploadObject = await this.getObjectFromId(uploadId);
      this.downloadFromUrl(this.uploadObject.guid.rendered);
      this.checkedFields = [];
      store.commit("updateCheckedFields", this.checkedFields);
      this.allSelected = false;
    },
    async getObjectFromId(fileId) {
      const response = await WpApi.media()
        .id(fileId)
        .get();
      this.uploadObject = response;
    },
    async downloadFromUrl(url) {
      axios.get(url).then(response => {
        this.rawCsv = response;
        this.parsedCsv = CsvToArray(response.data);
        store.state.csvLength = this.parsedCsv.length;
      });
    }
  }
};
</script>
