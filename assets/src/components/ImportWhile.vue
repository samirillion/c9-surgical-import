<template>
  <div>
    Cool
    <span class="preview-details" v-if="parsedCsv.length > 0">
      Start with record:
      <input
        type="number"
        name="importOffset"
        min="0"
        :max="parsedCsv.length - 1"
        class="import-offset"
        v-model="importOffset"
      />
      End with record:
      <input
        type="number"
        name="importLimit"
        :min="importOffset"
        :max="parsedCsv.length - 1"
        class="import-limit"
        v-model="importLimit"
      />
    </span>
  </div>
</template>

<script>
export default {
  props: {
    parsedCsv: {
      type: Array,
      default: []
    },
  },
};
</script>

<style lang="scss" scoped></style>
