<template>
  <div class="import">
    <div class="ifm-input-wrapper">
      <label for="loopOptionLeft">Loop Through</label>
      <select name="loopOptionLeft" v-model="loopOption.left">
        <option
          v-for="(paramValue, paramKey) in loopOptions"
          :key="paramKey"
          :value="paramKey"
        >
          {{ paramValue }}
        </option>
      </select>
    </div>

    <LoopParams :loopOptionLeft="loopOption.left" :parsedCsv="parsedCsv" />

    <FileUploader @uploaded="onUpload" v-if="'csv_rows' === loopOption.left" />
    <div v-if="parsedCsv.length > 1 || 'csv_rows' !== loopOption.left">
      <CsvPreview
        v-if="parsedCsv.length > 1 && 'csv_rows' === loopOption.left"
        :parsedCsv="parsedCsv"
      />
      <hr />
      <div class="ifm-steps-and-vars">
        <!-- Where all the steps go -->
        <ImportSteps />

        <!-- Define conditions for running import -->
        <div class="submit-wrapper">
          <!-- <button class="button button-secondary" @click="validateInput">
            Validate Input
          </button> -->
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
        <template v-slot:body>
          <details open>
            <summary>Progress : {{ importState }} </summary>
            <div class="modal-details-body">
              <div
                v-for="(record, recordIndex) in progress"
                :key="recordIndex"
                class="record-out"
              >
                {{
                  Math.round(
                    (parseInt(recordIndex) / parseInt(importLimit)) * 100
                  )
                }}% Record: {{ parseInt(recordIndex) }}/{{
                  parseInt(importLimit)
                }}
                <div
                  v-for="(step, stepIndex) in record"
                  v-bind:class="{ progress: true, failed: !step.success }"
                  :key="stepIndex"
                >
                  <b>Step id:</b> {{ step.id }} <b>Success:</b>
                  {{ step.success ? "true" : "false" }}
                  <div
                    v-show="step.get"
                    v-for="(value, param) in step.get"
                    :key="param"
                  >
                    <b>Get Parameter:</b> {{ param }} <b>Value:</b> {{ value }}
                  </div>
                  <div
                    v-show="step.set"
                    v-for="(value, param) in step.set"
                    :key="param"
                  >
                    <b>Set Parameter:</b> {{ param }} <b>Value:</b> {{ value }}
                  </div>
                </div>
              </div>
            </div>
          </details>
        </template>
        <template v-slot:footer>
          <button class="button-primary" @click="closeModal">
            x
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
import LoopParams from "@/components/LoopParams.vue";

export default {
  name: "Import",
  components: {
    FileUploader,
    ImportSteps,
    VarBuilder,
    CsvPreview,
    ProgressModal,
    LoopParams
  },
  data() {
    return {
      loopOptions: {
        csv_rows: "csv rows",
        integer: "a set number of times",
        get_value: "the output of a 'get_' action"
      },
      loopOption: store.state.loopOption,
      uploadFile: 1,
      file: [],
      uploadObject: {},
      rawCsv: {},
      parsedCsv: [],
      inputValid: true,
      progress: null,
      importComplete: false,
      requestContent: "",
      showModal: false,
      err: false
    };
  },
  computed: {
    importLimit() {
      return store.state.importLimit;
    },
    csvFields: {
      get: () => store.state.csvFields,
      set: value => store.commit("updateCsvFields", value)
    },
    importState() {
      if (this.importComplete) {
        return "Complete";
      } else if (this.err) {
        return this.err;
      }
      return "Pending";
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
      this.importComplete = false;
      this.err = false;
      this.getProgress();
      this.runImport();
    },
    timeout(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    },
    async getProgress() {
      while (false === this.importComplete && false === this.err) {
        let state = await WpApi.getProgress().auth();
        this.parseProgress(state);
        await this.timeout(200);
      }
    },
    parseProgress(state) {
      state = JSON.parse(state);

      if (state.err) {
        this.err = state.err;
      }
      if (false === this.importComplete) {
        this.progress = state.progress;
      }
    },
    async runImport() {
      try {
        let request = await WpApi.auth()
          .runImport()
          .param("upload_object", this.uploadObject)
          .param("import_steps", store.getters.jsonSteps)
          .param("import_vars", store.getters.jsonVars)
          .param("offset", store.state.importOffset)
          .param("limit", store.state.importLimit);
        this.parseProgress(request);
        this.importComplete = true;
      } catch (err) {
        console.log(err.rawResponse);
        this.err = JSON.parse(err);
      }
    },
    async onUpload(uploadId) {
      store.commit("setFileId", uploadId);
      const uploadObject = await this.getObjectFromId(uploadId);
      this.downloadFromUrl(this.uploadObject.guid.rendered);
      this.csvFields = [];
      store.commit("updateCsvFields", this.csvFields);
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
        store.commit("updateCsvFields", this.parsedCsv[0]);
        store.state.csvLength = this.parsedCsv.length;
      });
    }
  }
};
</script>
