<template>
  <details open>
    <summary>Import fields (pick some!)</summary>
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
        <option v-for="index in parsedCsv.length - 1" :key="index">{{
          index
        }}</option>
      </select>
      of {{ parseInt(parsedCsv.length) - 1 }} total Entries
    </span>
    <div class="ifm-table-wrapper">
      <table class="csv-table striped">
        <tbody>
          <tr>
            <th>
              <label for="select-all">#</label>
            </th>
            <th
              v-for="(column, columnIndex) in parsedCsv[0]"
              :key="columnIndex"
            >
              <label :for="column">{{ column }} ({{ columnIndex }})</label>
            </th>
          </tr>
          <!-- prettier-ignore-attribute -->
          <tr
            v-for="(example, exampleIndex) in parsedCsv.slice(1, parseInt(exampleEntries) + 1)"
            :key="exampleIndex"
          >
            <td>{{ exampleIndex }}</td>
            <td v-for="(td, tdIndex) in example" :key="tdIndex">
              <div class="cell-content" style="height:100%;width:100%;">
                {{ td.substring(0, exampleEntryLength) }}
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
      csvFields: store.state.csvFields
    };
  },
  mounted() {
    this.importLimit = this.parsedCsv.length - 1;
  },
  computed: {
    importOffset: {
      get: () => parseInt(store.state.importOffset),
      set: value => store.commit("updateOffset", parseInt(value))
    },
    importLimit: {
      get: () => parseInt(store.state.importLimit),
      set: value => store.commit("updateLimit", parseInt(value))
    }
  }
};
</script>
