<template>

</template>

<script>
import store from "@/store";

import { WpApi } from "@/services/WpApi";

export default {
  name: "LoopOptions",
  data() {
    return {
      loopOptions: {
        csv_rows: "csv rows",
        integer: "a set number of times",
        get_value: "the output of a 'get_' action"
      },
      loopOption: store.state.loopOption,
      getIds: []
    };
  },
  created() {
  },
  methods: {
    updateGetActions() {
      let type = this.loopOption.left;
      this.$emit('changeOption', )
    }
  }
};
</script>

<style lang="scss" scoped></style>
