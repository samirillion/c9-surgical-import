<template>
  <details open>
    <summary>Import fields (pick some!)</summary>
    <div class="ifm-table-wrapper">
      <table class="csv-table striped">
        <tbody>
          <tr>
            <th>
              <input
                type="checkbox"
                name="select-all"
                @change="toggleSelect"
                v-model="allSelected"
              />
              <label for="select-all">Select all</label>
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
                @change="updateCheckedFields"
              />
              <label :for="column">{{ column }} ({{ columnIndex }})</label>
            </th>
          </tr>
          <!-- prettier-ignore-attribute -->
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
</template>

<script>
import store from "@/store";

export default {
  props: {
    parsedCsv: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      allSelected: false,
      exampleEntries: 1,
      exampleEntryLength: 25,
      checkedFields: store.state.checkedFields
    };
  },
  methods: {
    updateCheckedFields() {
      store.commit("updateCheckedFields", this.checkedFields);
    },
    toggleSelect() {
      if (!this.allSelected) {
        this.checkedFields = [];
        store.commit("updateCheckedFields", this.checkedFields);
      } else {
        this.checkedFields = this.parsedCsv[0];
        store.commit("updateCheckedFields", this.checkedFields);
      }
    }
  }
};
</script>
