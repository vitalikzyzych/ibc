(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('element/locale/en', ['module', 'exports'], factory);
  } else if (typeof exports !== "undefined") {
    factory(module, exports);
  } else {
    var mod = {
      exports: {}
    };
    factory(mod, mod.exports);
    global.ELEMENT.lang = global.ELEMENT.lang || {};
    global.ELEMENT.lang.en = mod.exports;
  }
})(this, function (module, exports) {
  'use strict';

  exports.__esModule = true;
  exports.default = window.EventsSchedule.locale_element_ui;
  module.exports = exports['default'];
});
