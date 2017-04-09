// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
  "use strict";

  CodeMirror.defineMode("crystal", function(config) {
    function wordRegExp(words, end) {
      return new RegExp((end ? "" : "^") + "(?:" + words.join("|") + ")" + (end ? "$" : "\\b"));
    }

    function chain(tokenize, stream, state) {
      state.tokenize.push(tokenize);
      return tokenize(stream, state);
    }

    var operators = /^(?:[-+/%|&^]|\*\*?|[<>]{2})/;
    var conditionalOperators = /^(?:[=!]~|===|<=>|[<>=!]=?|[|&]{2}|~)/;
    var indexingOperators = /^(?:\[\][?=]?)/;
    var anotherOperators = /^(?:\.(?:\.{2})?|->|[?:])/;
    var idents = /^[a-z_\u009F-\uFFFF][a-zA-Z0-9_\u009F-\uFFFF]*/;
    var types = /^[A-Z_\u009F-\uFFFF][a-zA-Z0-9_\u009F-\uFFFF]*/;
    var keywords = wordRegExp([
      "abstract", "alias", "as", "asm", "begin", "break", "case", "class", "def", "do",
      "else", "elsif", "end", "ensure", "enum", "extend", "for", "fun", "if", "ifdef",
      "include", "instance_siz