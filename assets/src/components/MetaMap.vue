<template>
  <div class="ifm-params">
    <h4>{{ title }}</h4>
    <div
      class="ifm-input-group"
      v-for="(mapRow, mapIndex) in stepMap"
      :key="mapIndex"
    >
      <div class="ifm-input-wrapper">
        <label for="mapRowLeft">Parameter</label>
        <input type="text" name="mapRowLeft" v-model="mapRow.left" />
      </div>
      <div class="equals">=</div>
      <div class="type-and-value">
        <div class="ifm-input-wrapper">
          <label for="mapRowType">Type</label>
          <select
            name="mapRowType"
            v-model="mapRow.type"
            @change="updateOptions(mapRow.type)"
          >
            <option v-if="'post_type' === mapRow.left" value="postType"
              >post type</option
            >
            <option value="csvValue">csv value</option>
            <option v-if="stepIds.length > 0" value="stepId"
              >previous step id</option
            >
            <option value="string">string</option>
            <option value="customVar">complex variable</option>
          </select>
        </div>
        <div class="ifm-input-wrapper">
          <label for="mapRowRight">Value</label>
          <v-select
            name="mapRowRight"
            @input="updateMap"
            :options="valueOptions"
            :value="mapRow.right"
            v-if="'string' !== mapRow.type"
          />
          <input type="text" name="mapRowRight" v-model="mapRow.right" v-else />
        </div>
      </div>
      <button
        @click="deleteMapRow(mapIndex)"
        v-if="stepMap.length > 1"
        class="delete-map-row button-secondary"
      >
        -
      </button>
    </div>
    <button
      @click="addMapRow(stepMap.length)"
      v-if="!isGetter"
      class="button-secondary"
    >
      +
    </button>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped></style>
