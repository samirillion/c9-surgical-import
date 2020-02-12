<template>
  <div class="import">
    <FileUploader @uploaded="onUpload" />
    <CsvPreview v-if="parsedCsv.length > 1" :parsedCsv="parsedCsv" />
    <div v-if="checkedFields && checkedFields.length > 0">
      <hr />
      <div class="ifm-steps-and-vars">
        <ImportSteps />
        <div class="submit-wrapper">
          <button class="button button-secondary" @click="validateInput">
            Validate Input
          </button>
          <button
            class="button button-primary"
            @click="handleImport"
            :disabled="!inputValid"
          >
            Run Import
          </button>
        </div>
      </div>
      <VarBuilder />
      <ProgressModal v-if="showModal" @close="showModal = false">
        <template v-slot:header>
          <h1>Progress</h1>
        </template>
        <template v-slot:body>
          <div>{{ progress }}</div>
        </template>
        <template v-slot:footer>
          <button class="button-primary" @click="closeModal">
            X
          </button>
        </template>
      </ProgressModal>
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
import ProgressModal from "@/components/ProgressModal.vue";

export default {
  name: "Import",
  components: {
    FileUploader,
    ImportSteps,
    VarBuilder,
    CsvPreview,
    ProgressModal
  },
  data() {
    return {
      file: [],
      uploadObject: {},
      rawCsv: {},
      parsedCsv: [],
      inputValid: true,
      progress: "",
      importComplete: false,
      showModal: false
    };
  },
  computed: {
    checkedFields: {
      get: () => store.state.checkedFields,
      set: value => store.commit("updateCheckedFields", value)
    }
  },
  methods: {
    closeModal() {
      this.showModal = false;
      this.progress = "";
    },
    validateInput() {
      this.inputValid = true;
    },
    async handleImport() {
      this.showModal = true;
      this.getProgress();
      this.runImport();
    },
    timeout(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    },
    async getProgress() {
      await this.timeout(500);
      while (false === this.importComplete) {
        let state = await WpApi.getProgress().auth();
        this.progress = state;
      }
    },
    async runImport() {
      try {
        let request = await WpApi.auth()
          .runImport()
          .param("upload_object", this.uploadObject)
          .param("import_steps", store.getters.jsonSteps)
          .param("import_vars", store.getters.jsonVars);
        if (true == request) {
          this.progress = "Complete!";
        } else {
          this.progress = request;
        }
        this.importComplete = true;
      } catch (err) {
        this.progress = err;
        this.importComplete = true;
      }
    },
    async onUpload(uploadId) {
      store.commit("setFileId", uploadId);
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
