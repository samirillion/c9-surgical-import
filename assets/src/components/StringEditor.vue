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
    </div>
    <codemirror
      ref="myCm"
      :value="customVar.code"
      :options="cmOptions"
      @ready="onCmReady"
      @focus="onCmFocus"
      @input="onCmCodeChange"
    ></codemirror>
    <div
      class="preview-var-wrapper"
      v-show="customVar.code && customVar.code.length"
    >
      <hr />
      <button class="button-secondary" @click="previewCustomVar">
        Preview
      </button>
      <span>for line #</span>
      <input type="number" v-model="previewRecordIndex" class="entry-length" />
      <div
        class="var-preview"
        v-show="customVarPreview && customVarPreview.length"
      >
        <button @click="customVarPreview = null">
          x
        </button>
        {{ customVarPreview }}
      </div>
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
      ref: this.index + 1,
      fieldsToggled: false,
      stringFunctions,
      customVarPreview: null,
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
  methods: {
    async previewCustomVar() {
      this.customVarPreview = await WpApi.previewCustomVar()
        .param("upload_id", store.state.uploadedFileId)
        .param("record_index", Number(this.previewRecordIndex) - 1)
        .param("var_code", this.customVar.code);
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
  },
  computed: {
    customVar() {
      return store.state.customVars[this.index];
    },
    checkedFields: {
      get() {
        return store.state.checkedFields;
      }
    },
    codemirror() {
      return this.$refs.myCm.codemirror;
    }
  }
};
</script>
