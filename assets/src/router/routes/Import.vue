<template>
  <div class="import">
    <FileUploader @uploaded="onUpload" />

    <details v-if="parsedCsv.length > 1" open>
      <summary>Import fields (pick some!)</summary>
      <div class="table-wrapper">
        <table class="csv-table striped">
          <tbody>
            <tr>
              <th>
                <label for="select-all">Select all</label>
                <input
                  type="checkbox"
                  name="select-all"
                  @change="toggleSelect"
                  v-model="allSelected"
                />
              </th>
              <th
                v-for="(column, columnIndex) in parsedCsv[0]"
                :key="columnIndex"
              >
                <input
                  type="checkbox"
                  :name="column"
                  :value="column"
                  v-model="checkedFields"
                />
                <label :for="column">{{ column }} ({{ columnIndex }})</label>
              </th>
            </tr>
            <tr
              v-for="(example, exampleIndex) in parsedCsv.slice(1, parseInt(exampleEntries) + 1)"
              :key="exampleIndex"
            >
              <td></td>
              <td v-for="(td, tdIndex) in example" :key="tdIndex">
                <div class="cell-content" style="height:100%;width:100%;">
                  {{ td.substring(0, exampleEntryLength) }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <span class="preview-details" v-if="parsedCsv.length > 0">
        Previewing the first
        <input
          type="number"
          name="entryLength"
          min="10"
          class="entry-length"
          v-model="exampleEntryLength"
        />
        characters of
        <select v-model="exampleEntries">
          <option v-for="index in parsedCsv.length" :key="index">{{
            index
          }}</option>
        </select>
        of {{ parsedCsv.length }} total Entries
      </span>
    </details>
    <div v-if="checkedFields.length > 0">
      <hr />
      <ImportSteps
        :checkedFields="checkedFields"
        :summary="'Create Your Steps'"
      />
      <StepsRaw :open="true" :summary="'View Steps as Json'" />
    </div>
    <hr />
    <div class="row">
      <button class="button button-primary">Validate Input</button>
      <button class="button button-primary" @click="runImport">
        Run Import
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

import { WpApi } from "@/services/WpApi";
import { CsvToArray } from "@/utils/CsvToArray";
import FileUploader from "@/components/FileUploader.vue";
import ImportSteps from "@/components/ImportSteps.vue";
import StepsRaw from "@/components/StepsRaw.vue";

import store from "@/store";

export default {
  name: "Import",
  components: {
    FileUploader,
    ImportSteps,
    StepsRaw
  },
  data() {
    return {
      allSelected: false,
      file: [],
      uploadId: null,
      uploadObject: {},
      rawCsv: {},
      parsedCsv: [],
      checkedFields: [],
      exampleEntries: 1,
      exampleEntryLength: 25
    };
  },
  computed: {
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
    toggleSelect() {
      if (!this.allSelected) {
        this.checkedFields = [];
      } else {
        this.checkedFields = this.parsedCsv[0];
      }
    },
    async onUpload(uploadId) {
      this.uploadId = uploadId;
      // StepsStore.setFileId(uploadId);
      const uploadObject = await this.getObjectFromId(uploadId);
      this.downloadFromUrl(this.uploadObject.guid.rendered);
      this.checkedFields = [];
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
