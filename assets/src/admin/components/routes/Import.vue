<template>
  <div class="import">
    <FileUploader @uploaded="onUpload"/>

    <details v-if="parsedCsv.length > 1">
      <summary>Import fields</summary>
      <div class="table-wrapper">
        <table class="csv-table">
          <tbody>
            <tr>
              <th>
                <label for="select-all">Select all</label>
                <input
                  type="checkbox"
                  name="select-all"
                  @change="toggleSelect"
                  v-model="allSelected"
                >
              </th>
              <th v-for="(column, columnIndex) in parsedCsv[0]" :key="columnIndex">
                <input type="checkbox" :name="column" :value="column" v-model="checkedFields">
                <label :for="column">{{ column }} ({{ columnIndex }})</label>
              </th>
            </tr>
            <tr
              v-for="(example, exampleIndex) in parsedCsv.slice(1, parseInt(exampleEntries)+ 1)"
              :key="exampleIndex"
            >
              <td></td>
              <td v-for="(td, tdIndex) in example" :key="tdIndex">
                <div
                  class="cell-content"
                  style="height:100%;width:100%;"
                >{{ td.substring(0,exampleEntryLength) }}</div>
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
        > characters
        <select v-model="exampleEntries">
          <option v-for="index in parsedCsv.length" :key="index">{{index}}</option>
        </select>
        of {{ parsedCsv.length }} Entries
      </span>
    </details>

    <details class="custom-var-wrapper" v-if="checkedFields.length > 0">
      <summary>Build Custom Variables</summary>
      <VarBuilder/>
    </details>

    <details class="fields-wrapper" v-if="checkedFields.length > 0">
      <summary>Variables ({{ checkedFields.length }})</summary>
      <div class="import-fields-wrapper">
        <ul class="import-fields">
          <li v-for="(field, index) in checkedFields" :key="index">
            {{field}}
            <!-- <select  -->
          </li>
        </ul>
      </div>
    </details>

    <hr>

    <!-- v-if="checkedFields.length  -->
    <details open>
      <summary>Create Your Steps</summary>

      <ImportStepper :checkedFields="checkedFields"/>
    </details>
    <hr style="clear:both;">
    <div class="row">
      <button class="button button-primary">Validate Input</button>
      <button class="button button-primary" @click="importPosts">Run Import</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

import { WpApi } from "@/shared/services/WpApi";
import { CsvToArray } from "@/shared/utils/CsvToArray";
import FileUploader from "@/shared/components/FileUploader.vue";
import ImportStepper from "@/admin/components/import-stepper/ImportStepper.vue";
import VarBuilder from "@/admin/components/import-stepper/VarBuilder.vue";

import ArgStore from "@/admin/components/import-stepper/ArgStore";

export default {
  name: "Import",
  components: {
    FileUploader,
    ImportStepper,
    VarBuilder
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
  methods: {
    async importPosts() {
      const response = await WpApi.importPosts()
        .param("import_maps", ArgStore.getData())
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
      ArgStore.setFileId(uploadId);
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
