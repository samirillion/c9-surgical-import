<template>
  <div class="meta">
    <div class="single-meta" v-for="(object, index) in meta" :key="index">
      <div class="key-row">
        <b>key:</b>
        <input type="text" class="meta-key-input" :value="object.key" @input="updateKey($event, index)">
        <button @click="spliceMeta(step, index)" v-if="meta.length > 1">-</button>
      </div>
      <div class="value-row">
        <b>value:</b>
        <v-select
          @input="updateValue($event, index)"
          :options="inputFields"
          :value="object.value"
          style="display:inline-block;"
        ></v-select>
        <button @click="addMeta(step, index)">+</button>
      </div>
    </div>
  </div>
</template>

<script>
import StepsStore from "@/admin/components/importer/StepsStore";

export default {
  name: "MetaMapper",
  props: ["inputFields", "step"],
  data() {
    return {
      meta: [{ key: "", value: "" }]
    };
  },
  methods: {
    updateKey(keyEvent, index) {
      this.meta[index].key = keyEvent.target.value;
      StepsStore.updateStep(parseInt(this.step), this.meta)
    },
    updateValue(valueEvent, index) {
      this.meta[index].value = valueEvent;
      StepsStore.updateStep(parseInt(this.step), this.meta)
    },
    addMeta(step, index) {
      this.meta.splice(index + 1, 0, { key: "", value: "" });
    },
    spliceMeta(index) {
      this.meta.splice(index, 1);
      StepsStore.updateStep(parseInt(this.step), this.meta)
    }
  }
};
</script>