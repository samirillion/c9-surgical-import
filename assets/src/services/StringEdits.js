// should match the regex specified in utils/IfmMode.js

export const stringFunctions = [
  {
    name: "+",
    insert: " + ",
    description: "concatenate values"
  },
  {
    name: "whitespace",
    insert: ' " " ',
    description: "drop in some whitespace"
  },
  {
    name: "toLower",
    insert: "toLower(string)",
    description: "make entire string lower case"
  },
  {
    name: "trim",
    insert: "trim(string)",
    description: "remove whitespace from either end of string"
  },
  {
    name: "toUpper",
    insert: "toUpper(string)",
    description: "make entire string uppercase"
  },
  {
    name: "capitalize",
    insert: "capitalize(string)",
    description: "capitalize all "
  },
  {
    name: "replace",
    insert: "trim(string)",
    description: "remove whitespace from either end of string"
  }
];
