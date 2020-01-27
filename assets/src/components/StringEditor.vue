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
import "@/utils/IfmScript";
import { codemirror } from "vue-codemirror";

// theme css
import "codemirror/lib/codemirror.css";

import CodeMirror from "codemirror";
import "@/utils/IfmScript";

/* Example definition of a simple mode that understands a subset of
 * JavaScript:
 */

CodeMirror.defineSimpleMode("simplemode", {
  // The start state contains the rules that are intially used
  start: [
    // The regex matches the token, the token property contains the type
    { regex: /"(?:[^\\]|\\.)*?(?:"|$)/, token: "string" },
    // You can match multiple tokens at once. Note that the captured
    // groups must span the whole string in this case
    {
      regex: /(function)(\s+)([a-z$][\w$]*)/,
      token: ["keyword", null, "variable-2"]
    },
    // Rules are matched in the order in which they appear, so there is
    // no ambiguity between this one and the one above
    {
      regex: /(?:function|var|return|if|for|while|else|do|this)\b/,
      token: "keyword"
    },
    { regex: /true|false|null|undefined/, token: "atom" },
    {
      regex: /0x[a-f\d]+|[-+]?(?:\.\d+|\d+\.?\d*)(?:e[-+]?\d+)?/i,
      token: "number"
    },
    { regex: /\/\/.*/, token: "comment" },
    { regex: /\/(?:[^\\]|\\.)*?\//, token: "variable-3" },
    // A next property will cause the mode to move to a different state
    { regex: /\/\*/, token: "comment", next: "comment" },
    { regex: /[-+\/*=<>!]+/, token: "operator" },
    // indent and dedent properties guide autoindentation
    { regex: /[\{\[\(]/, indent: true },
    { regex: /[\}\]\)]/, dedent: true },
    { regex: /[a-z$][\w$]*/, token: "variable" },
    // You can embed other modes with the mode property. This rule
    // causes all code between << and >> to be highlighted with the XML
    // mode.
    { regex: /<</, token: "meta", mode: { spec: "xml", end: />>/ } }
  ],
  // The multi-line comment state.
  comment: [
    { regex: /.*?\*\//, token: "comment", next: "start" },
    { regex: /.*/, token: "comment" }
  ],
  // The meta property contains global information about the mode. It
  // can contain properties like lineComment, which are supported by
  // all modes, and also directives like dontIndentStates, which are
  // specific to simple modes.
  meta: {
    dontIndentStates: ["comment"],
    lineComment: "//"
  }
});

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
        mode: "simplemode",
        theme: "default",
        lineNumbers: true,
        line: true
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
