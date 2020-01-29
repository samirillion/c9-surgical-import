// should match the regex specified in utils/IfmMode.js

export const stringFunctions = [
  {
    name: "whitespace",
    insert: ' " " ',
    description: "drop in some whitespace"
  },
  {
    name: "toLower",
    insert: "toLower(input)",
    description: "make entire input lower case"
  },
  {
    name: "trim",
    insert: "trim(input)",
    description: "remove whitespace from either end of input"
  },
  {
    name: "toUpper",
    insert: "toUpper(input)",
    description: "make entire input uppercase"
  },
  {
    name: "humanize",
    insert: "humanize(input)",
    description:
      "Capitalizes the first word of the input, replaces underscores with spaces, and strips '_id'"
  },
  {
    name: "replace",
    insert: "replace(input, replacePattern, replacer)",
    description: ""
  }
];
