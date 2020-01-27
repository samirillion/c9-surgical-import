<template>
  <!-- bidirectional data binding（双向数据绑定） -->
  <!-- <codemirror v-model="code" :options="cmOptions"></codemirror> -->
  <div class="string-editor-wrapper">
    <codemirror
      ref="myCm"
      :value="code"
      :options="cmOptions"
      @ready="onCmReady"
      @focus="onCmFocus"
      @input="onCmCodeChange"
    >
    </codemirror>
  </div>
</template>
<script>
// language js
import { codemirror } from "vue-codemirror";

// theme css
import "codemirror/lib/codemirror.css";

import "@/utils/IfmMode";

export default {
  components: {
    codemirror
  },
  data() {
    return {
      code: "",
      cmOptions: {
        // codemirror options
        tabSize: 4,
        mode: "IfmScript",
        theme: "default",
        lineNumbers: true,
        line: true,
        // more codemirror options, 更多 codemirror 的高级配置...
      }
    };
  },
  methods: {
    onCmReady(cm) {
      console.log("the editor is readied!", cm);
    },
    onCmFocus(cm) {
      console.log("the editor is focus!", cm);
    },
    onCmCodeChange(newCode) {
      console.log("this is new code", newCode);
      this.code = newCode;
    }
  },
  computed: {
    codemirror() {
      return this.$refs.myCm.codemirror;
    }
  },
  mounted() {
    console.log("this is current codemirror object", this.codemirror);
    // you can use this.codemirror to do something...
  }
};
</script>
