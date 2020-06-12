/* Example definition of a simple mode that understands a subset of
 * JavaScript:
 */
import CodeMirror from "codemirror";
import "codemirror/addon/mode/simple";

CodeMirror.defineSimpleMode("IfmScript", {
  // The start state contains the rules that are intially used
  start: [
    // The regex matches the token, the token property contains the type
    { regex: /{{([\w|\s|-|\.]+?)}}/, token: "variable" },
    { regex: /"(?:[^\\]|\\.)*?(?:"|$)/, token: "string" },

    {
      regex: /(trim|toLower|toUpper|humanize|replace|htmlEncode|htmlDecode|titleize|formatDate)\(\)?/,
      token: "function",
      push: "args"
    },
    // indent and dedent properties guide autoindentation
    { regex: /[\{\[\(]/, indent: true },
    { regex: /[\}\]\)]/, dedent: true },
    // You can embed other modes with the mode property. This rule
    // causes all code between << and >> to be highlighted with the XML
    // mode.
    { regex: /true|false|null|undefined/, token: "atom", next: "start" },
    // A next property will cause the mode to move to a different state
    { regex: /[-+\/*(==)!]+/, token: "operator", next: "start" }
  ],
  args: [
    { regex: /{{([\w|\s|-|\.]+?)}}/, token: "variable_arg" },
    {
      regex: /"(?:[^\\]|\\.)*?(?:"|$)/,
      token: "string_arg"
    },
    {
      regex: /\)/,
      token: "function",
      pop: true
    },
    {
      regex: /(trim|toLower|toUpper|capitalize|replace)\(\)?/,
      token: "function",
      push: "args"
    }
  ],
  // The multi-line comment state.
  // The meta property contains global information about the mode. It
  // can contain properties like lineComment, which are supported by
  // all modes, and also directives like dontIndentStates, which are
  // specific to simple modes.
  meta: {
    lineComment: "//"
  }
});
