<template>
  <!-- bidirectional data binding（双向数据绑定） -->
  <!-- <codemirror v-model="code" :options="cmOptions"></codemirror> -->
  <div class="string-editor-wrapper">
    <div class="string-editor-header">
      <div class="csv-value-dropdown" v-show="checkedFields.length > 0">
        <button class="button-secondary" @click="toggleFields">
          Csv Value
        </button>
        <table class="form-table" v-show="fieldsToggled">
          <tr valign="top" v-for="(field, index) in checkedFields" :key="index">
            <td scope="row" @click="insert('{{' + field + '}}')">
              {{ field }}
            </td>
          </tr>
        </table>
      </div>
      <button
        class="button-secondary"
        v-for="(stringFunc, funcIndex) in stringFunctions"
        :key="funcIndex"
        @click="insert(stringFunc.insert)"
      >
        {{ stringFunc.name }}
      </button>
    </div>
    <codemirror
      :ref="variable.id"
      :value="variable.code"
      :options="cmOptions"
      @ready="onCmReady"
      @focus="onCmFocus"
      @input="onCmCodeChange"
    >
    </codemirror>
  </div>
</template>
<script>
import store from "@/store";

import { codemirror } from "vue-codemirror";
import "codemirror/lib/codemirror.css";
//custom codemirror language mode
import "@/utils/IfmMode";

import { stringFunctions } from "@/services/StringEdits";

export default {
  components: {
    codemirror
  },
  props: {
    index: {
      type: Number
    }
  },
  data() {
    return {
      fieldsToggled: false,
      stringFunctions,
      variable: store.state.customVars[this.index],
      cmOptions: {
        // codemirror options
        tabSize: 4,
        mode: "IfmScript",
        theme: "default",
        lineNumbers: true,
        line: true
        // more codemirror options, 更多 codemirror 的高级配置...
      }
    };
  },
  computed: {
    checkedFields: {
      get() {
        return store.state.checkedFields;
      }
    },
    codemirror() {
      let ref = this.variable.id;
      console.log(this.$refs);
      return this.$refs[ref].codemirror;
    }
  },
  methods: {
    insert(value) {
      this.codemirror.replaceSelection(value);
      this.codemirror.focus();
      this.fieldsToggled = false;
    },
    toggleFields() {
      this.fieldsToggled = !this.fieldsToggled;
    },
    onCmReady(cm) {
      console.log("the editor is readied!", cm);
    },
    onCmFocus(cm) {
      console.log("the editor is focus!", cm);
    },
    onCmCodeChange(newCode) {
      console.log("this is new code", newCode);
      store.state.customVars[this.index].code = newCode;
    }
  }
};
</script>
