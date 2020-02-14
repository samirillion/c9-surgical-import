// should match the regex specified in utils/IfmMode.js

export const stringFunctions = [
  {
    name: "text",
    insert: '"text goes here"',
    description: "drop in some text"
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
    insert: "replace(csv value, search, replacement)",
    description: "Replaces all occurrences of search in input by replacement"
  },
  {
    name: "htmlEncode",
    insert: "htmlEncode(input)",
    description:
      "Convert all applicable characters to HTML entities. An alias of htmlentities. "
  },
  {
    name: "htmlDecode",
    insert: "htmlDecode(input)",
    description: "Convert all HTML entities to their applicable characters."
  },
  {
    name: "titleize",
    insert: "titleize(input)",
    description:
      "Returns a trimmed string with the first letter of each word capitalized. Ignores connecting words"
  },
  {
    name: "formatDate",
    insert: "formatDate(input)",
    description: "Parses the date the best it knows how"
  }
];
