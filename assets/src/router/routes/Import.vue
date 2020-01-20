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
import axios from "axios";

import { WpApi } from "@/services/WpApi";
import { CsvToArray } from "@/utils/CsvToArray";
import FileUploader from "@/components/FileUploader.vue";
import ImportSteps from "@/components/ImportSteps.vue";
import VarBuilder from "@/components/VarBuilder.vue";
import CsvPreview from "@/components/CsvPreview.vue";

import store from "@/store";

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
    },
    exampleStepper: function() {
      return parsedCsv.slice(1, parseInt(exampleEntries) + 1);
    }
  },
  methods: {
    async runImport() {
      const response = await WpApi.runImport().param(
        "import_maps"
        // StepsStore.getSteps()
      );
      console.log(response);
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
      });
    }
  }
};
</script>
