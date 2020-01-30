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
        :title="stringFunc.description"
      >
        {{ stringFunc.name }}
      </button>
      <button
        class="button-secondary"
        @click="previewCustomVar"
      >
        Preview Var
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
    <div class="ifm-input-wrapper">
      <input type="number" v-model="previewRecordIndex" class="entry-length">
      {{ customVarPreview }}
    </div>
  </div>
</template>
<script>
import store from "@/store";

import { codemirror } from "vue-codemirror";
import "codemirror/lib/codemirror.css";
//custom codemirror language mode
import "@/utils/IfmMode";

import { stringFunctions } from "@/services/StringEdits";

import { WpApi } from "@/services/WpApi";

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
      customVarPreview: null,
      variable: store.state.customVars[this.index],
      previewRecordIndex: 1,
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
    async previewCustomVar() {
      this.customVarPreview = await WpApi.previewCustomVar()
        .param("upload_id", store.state.uploadedFileId)
        .param("record_index", this.previewRecordIndex + 1)
        .param("var_code", this.variable.code);
    },
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
