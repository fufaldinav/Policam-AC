(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/app"],{

/***/ "./node_modules/is-buffer/index.js":
/*!*****************************************!*\
  !*** ./node_modules/is-buffer/index.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*!
 * Determine if an object is a Buffer
 *
 * @author   Feross Aboukhadijeh <https://feross.org>
 * @license  MIT
 */

// The _isBuffer check is for Safari 5-7 support, because it's missing
// Object.prototype.constructor. Remove this eventually
module.exports = function (obj) {
  return obj != null && (isBuffer(obj) || isSlowBuffer(obj) || !!obj._isBuffer)
}

function isBuffer (obj) {
  return !!obj.constructor && typeof obj.constructor.isBuffer === 'function' && obj.constructor.isBuffer(obj)
}

// For Node v0.10 support. Remove this eventually.
function isSlowBuffer (obj) {
  return typeof obj.readFloatLE === 'function' && typeof obj.slice === 'function' && isBuffer(obj.slice(0, 0))
}


/***/ }),

/***/ "./node_modules/process/browser.js":
/*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/setimmediate/setImmediate.js":
/*!***************************************************!*\
  !*** ./node_modules/setimmediate/setImmediate.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global, process) {(function (global, undefined) {
    "use strict";

    if (global.setImmediate) {
        return;
    }

    var nextHandle = 1; // Spec says greater than zero
    var tasksByHandle = {};
    var currentlyRunningATask = false;
    var doc = global.document;
    var registerImmediate;

    function setImmediate(callback) {
      // Callback can either be a function or a string
      if (typeof callback !== "function") {
        callback = new Function("" + callback);
      }
      // Copy function arguments
      var args = new Array(arguments.length - 1);
      for (var i = 0; i < args.length; i++) {
          args[i] = arguments[i + 1];
      }
      // Store and register the task
      var task = { callback: callback, args: args };
      tasksByHandle[nextHandle] = task;
      registerImmediate(nextHandle);
      return nextHandle++;
    }

    function clearImmediate(handle) {
        delete tasksByHandle[handle];
    }

    function run(task) {
        var callback = task.callback;
        var args = task.args;
        switch (args.length) {
        case 0:
            callback();
            break;
        case 1:
            callback(args[0]);
            break;
        case 2:
            callback(args[0], args[1]);
            break;
        case 3:
            callback(args[0], args[1], args[2]);
            break;
        default:
            callback.apply(undefined, args);
            break;
        }
    }

    function runIfPresent(handle) {
        // From the spec: "Wait until any invocations of this algorithm started before this one have completed."
        // So if we're currently running a task, we'll need to delay this invocation.
        if (currentlyRunningATask) {
            // Delay by doing a setTimeout. setImmediate was tried instead, but in Firefox 7 it generated a
            // "too much recursion" error.
            setTimeout(runIfPresent, 0, handle);
        } else {
            var task = tasksByHandle[handle];
            if (task) {
                currentlyRunningATask = true;
                try {
                    run(task);
                } finally {
                    clearImmediate(handle);
                    currentlyRunningATask = false;
                }
            }
        }
    }

    function installNextTickImplementation() {
        registerImmediate = function(handle) {
            process.nextTick(function () { runIfPresent(handle); });
        };
    }

    function canUsePostMessage() {
        // The test against `importScripts` prevents this implementation from being installed inside a web worker,
        // where `global.postMessage` means something completely different and can't be used for this purpose.
        if (global.postMessage && !global.importScripts) {
            var postMessageIsAsynchronous = true;
            var oldOnMessage = global.onmessage;
            global.onmessage = function() {
                postMessageIsAsynchronous = false;
            };
            global.postMessage("", "*");
            global.onmessage = oldOnMessage;
            return postMessageIsAsynchronous;
        }
    }

    function installPostMessageImplementation() {
        // Installs an event handler on `global` for the `message` event: see
        // * https://developer.mozilla.org/en/DOM/window.postMessage
        // * http://www.whatwg.org/specs/web-apps/current-work/multipage/comms.html#crossDocumentMessages

        var messagePrefix = "setImmediate$" + Math.random() + "$";
        var onGlobalMessage = function(event) {
            if (event.source === global &&
                typeof event.data === "string" &&
                event.data.indexOf(messagePrefix) === 0) {
                runIfPresent(+event.data.slice(messagePrefix.length));
            }
        };

        if (global.addEventListener) {
            global.addEventListener("message", onGlobalMessage, false);
        } else {
            global.attachEvent("onmessage", onGlobalMessage);
        }

        registerImmediate = function(handle) {
            global.postMessage(messagePrefix + handle, "*");
        };
    }

    function installMessageChannelImplementation() {
        var channel = new MessageChannel();
        channel.port1.onmessage = function(event) {
            var handle = event.data;
            runIfPresent(handle);
        };

        registerImmediate = function(handle) {
            channel.port2.postMessage(handle);
        };
    }

    function installReadyStateChangeImplementation() {
        var html = doc.documentElement;
        registerImmediate = function(handle) {
            // Create a <script> element; its readystatechange event will be fired asynchronously once it is inserted
            // into the document. Do so, thus queuing up the task. Remember to clean up once it's been called.
            var script = doc.createElement("script");
            script.onreadystatechange = function () {
                runIfPresent(handle);
                script.onreadystatechange = null;
                html.removeChild(script);
                script = null;
            };
            html.appendChild(script);
        };
    }

    function installSetTimeoutImplementation() {
        registerImmediate = function(handle) {
            setTimeout(runIfPresent, 0, handle);
        };
    }

    // If supported, we should attach to the prototype of global, since that is where setTimeout et al. live.
    var attachTo = Object.getPrototypeOf && Object.getPrototypeOf(global);
    attachTo = attachTo && attachTo.setTimeout ? attachTo : global;

    // Don't get fooled by e.g. browserify environments.
    if ({}.toString.call(global.process) === "[object process]") {
        // For Node.js before 0.9
        installNextTickImplementation();

    } else if (canUsePostMessage()) {
        // For non-IE10 modern browsers
        installPostMessageImplementation();

    } else if (global.MessageChannel) {
        // For web workers, where supported
        installMessageChannelImplementation();

    } else if (doc && "onreadystatechange" in doc.createElement("script")) {
        // For IE 6–8
        installReadyStateChangeImplementation();

    } else {
        // For older browsers
        installSetTimeoutImplementation();
    }

    attachTo.setImmediate = setImmediate;
    attachTo.clearImmediate = clearImmediate;
}(typeof self === "undefined" ? typeof global === "undefined" ? this : global : self));

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js"), __webpack_require__(/*! ./../process/browser.js */ "./node_modules/process/browser.js")))

/***/ }),

/***/ "./node_modules/timers-browserify/main.js":
/*!************************************************!*\
  !*** ./node_modules/timers-browserify/main.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var scope = (typeof global !== "undefined" && global) ||
            (typeof self !== "undefined" && self) ||
            window;
var apply = Function.prototype.apply;

// DOM APIs, for completeness

exports.setTimeout = function() {
  return new Timeout(apply.call(setTimeout, scope, arguments), clearTimeout);
};
exports.setInterval = function() {
  return new Timeout(apply.call(setInterval, scope, arguments), clearInterval);
};
exports.clearTimeout =
exports.clearInterval = function(timeout) {
  if (timeout) {
    timeout.close();
  }
};

function Timeout(id, clearFn) {
  this._id = id;
  this._clearFn = clearFn;
}
Timeout.prototype.unref = Timeout.prototype.ref = function() {};
Timeout.prototype.close = function() {
  this._clearFn.call(scope, this._id);
};

// Does not start the time, just sets up the members needed.
exports.enroll = function(item, msecs) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = msecs;
};

exports.unenroll = function(item) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = -1;
};

exports._unrefActive = exports.active = function(item) {
  clearTimeout(item._idleTimeoutId);

  var msecs = item._idleTimeout;
  if (msecs >= 0) {
    item._idleTimeoutId = setTimeout(function onTimeout() {
      if (item._onTimeout)
        item._onTimeout();
    }, msecs);
  }
};

// setimmediate attaches itself to the global object
__webpack_require__(/*! setimmediate */ "./node_modules/setimmediate/setImmediate.js");
// On some exotic environments, it's not clear which object `setimmediate` was
// able to install onto.  Search each possibility in the same order as the
// `setimmediate` library.
exports.setImmediate = (typeof self !== "undefined" && self.setImmediate) ||
                       (typeof global !== "undefined" && global.setImmediate) ||
                       (this && this.setImmediate);
exports.clearImmediate = (typeof self !== "undefined" && self.clearImmediate) ||
                         (typeof global !== "undefined" && global.clearImmediate) ||
                         (this && this.clearImmediate);

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./node_modules/webpack/buildin/module.js":
/*!***********************************!*\
  !*** (webpack)/buildin/module.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function(module) {
	if (!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if (!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ "./resources/js/ac/ac.js":
/*!*******************************!*\
  !*** ./resources/js/ac/ac.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var AcObject = function AcObject(data) {
  _classCallCheck(this, AcObject);

  for (var k in data) {
    this[k] = data[k];
  }
};

var Division =
/*#__PURE__*/
function (_AcObject) {
  _inherits(Division, _AcObject);

  function Division() {
    _classCallCheck(this, Division);

    return _possibleConstructorReturn(this, _getPrototypeOf(Division).apply(this, arguments));
  }

  return Division;
}(AcObject);

var Person =
/*#__PURE__*/
function (_AcObject2) {
  _inherits(Person, _AcObject2);

  function Person() {
    _classCallCheck(this, Person);

    return _possibleConstructorReturn(this, _getPrototypeOf(Person).apply(this, arguments));
  }

  return Person;
}(AcObject);

window.showNewEvent = function (event) {
  $(".events").append("<p class=\"mb-1\"><small>".concat(event, "</small></p>"));
};

window.showPersons = function (div_id) {
  $(".divisions").hide();
  $("#persons-div-".concat(div_id)).show();
};

window.showDivisions = function (div_id) {
  $(".persons").hide();
  $(".divisions").show();
};

window.openEntranceOptions = function (person_id, div_id) {
  var options = "";

  if (div_id === undefined) {
    options += "<div id=\"menu-button-back\" class=\"menu-item\" onclick=\"getDivisions();\">\u041D\u0430\u0437\u0430\u0434</div>"; //TODO перевод
  } else {
    options += "<div id=\"menu-button-back\" class=\"menu-item\" onclick=\"getPersons(".concat(div_id, ");\">\u041D\u0430\u0437\u0430\u0434</div>"); //TODO перевод
  }

  options += "<div id=\"menu-button-forgot\" class=\"menu-item\" onclick=\"sendInfo(1, ".concat(person_id, ")\">\u0417\u0430\u0431\u044B\u043B</div>"); //TODO перевод

  options += "<div id=\"menu-button-lost\" class=\"menu-item\" onclick=\"sendInfo(2, ".concat(person_id, ")\">\u041F\u043E\u0442\u0435\u0440\u044F\u043B</div>"); //TODO перевод

  options += "<div id=\"menu-button-broke\" class=\"menu-item\" onclick=\"sendInfo(3, ".concat(person_id, ")\">\u0421\u043B\u043E\u043C\u0430\u043B</div>"); //TODO перевод

  var menu = document.getElementById("menu");
  menu.innerHTML = options;
};

window.sendInfo = function (type, person_id) {
  var msg;

  switch (type) {
    case 1:
      msg = "\u041D\u0430 \u0441\u0435\u0440\u0432\u0435\u0440 \u0431\u0443\u0434\u0435\u0442 \u043E\u0442\u043F\u0440\u0430\u0432\u043B\u0435\u043D\u043E \u0443\u0432\u0435\u0434\u043E\u043C\u043B\u0435\u043D\u0438\u0435."; //TODO перевод

      if (!confirm(msg)) return;
      break;

    case 2:
      msg = "\u041A\u0430\u0440\u0442\u0430 \u0431\u0443\u0434\u0435\u0442 \u0443\u0434\u0430\u043B\u0435\u043D\u0430, \u0430 \u043D\u0430 \u0441\u0435\u0440\u0432\u0435\u0440 \u0431\u0443\u0434\u0435\u0442 \u043E\u0442\u043F\u0440\u0430\u0432\u043B\u0435\u043D\u043E \u0443\u0432\u0435\u0434\u043E\u043C\u043B\u0435\u043D\u0438\u0435."; //TODO перевод

      if (!confirm(msg)) return;
      break;

    case 3:
      msg = "\u041A\u0430\u0440\u0442\u0430 \u0431\u0443\u0434\u0435\u0442 \u0443\u0434\u0430\u043B\u0435\u043D\u0430, \u0430 \u043D\u0430 \u0441\u0435\u0440\u0432\u0435\u0440 \u0431\u0443\u0434\u0435\u0442 \u043E\u0442\u043F\u0440\u0430\u0432\u043B\u0435\u043D\u043E \u0443\u0432\u0435\u0434\u043E\u043C\u043B\u0435\u043D\u0438\u0435."; //TODO перевод

      if (!confirm(msg)) return;
      break;
  }

  axios.post("util/card_problem", {
    type: type,
    person_id: person_id
  }).then(function (response) {
    if (response.data) {
      alert(response.data);
    } else {
      alert("\u041F\u0443\u0441\u0442\u043E\u0439 \u043E\u0442\u0432\u0435\u0442 \u043E\u0442 \u0441\u0435\u0440\u0432\u0435\u0440\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
}; //сохранить ошибку на сервере


window.sendError = function (message) {
  axios.post("util/save_errors", {
    error: message
  }).catch(function (error) {
    console.log(error);
  });
}; //добавление опций в select


function addOption(elem, value, text) {
  var option = document.createElement("option");
  option.value = value;
  option.text = text;
  elem.add(option);
}

var trans = function trans(key) {
  var replace = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var translation = key.split('.').reduce(function (t, i) {
    return t[i] || null;
  }, window.translations);

  for (var placeholder in replace) {
    translation = translation.replace(":".concat(placeholder), replace[placeholder]);
  }

  return translation;
};

var trans_choice = function trans_choice(key) {
  var count = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
  var replace = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  var translation = key.split('.').reduce(function (t, i) {
    return t[i] || null;
  }, window.translations).split('|');
  translation = count > 1 ? translation[1] : translation[0];

  for (var placeholder in replace) {
    translation = translation.replace(":".concat(placeholder), replace[placeholder]);
  }

  return translation;
};

/***/ }),

/***/ "./resources/js/ac/cards.js":
/*!**********************************!*\
  !*** ./resources/js/ac/cards.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var cards = []; //получим список неизвестных карт (брелоков) из БД

window.getCards = function (id) {
  axios.get("cards/get_list").then(function (response) {
    var data = response.data;

    if (data) {
      var cards_selector = document.getElementById("cards");

      while (cards_selector.length > 0) {
        //удалить все элементы из меню карт
        cards_selector.remove(cards_selector.length - 1);
      }

      if (data.length == 0) {
        //если нет известных карт
        addOption(cards_selector, 0, trans('ac.missing'));
      } else {
        //иначе заполним меню картами
        addOption(cards_selector, 0, trans('ac.not_selected')); //первый пункт

        data.forEach(function (c) {
          addOption(cards_selector, c.id, c.wiegand);
        });
      }

      if (id) {
        //если передавали id, то установим карту как текущую
        cards_selector.value = id;
      }
    } else {
      alert("\u041F\u0443\u0441\u0442\u043E\u0439 \u043E\u0442\u0432\u0435\u0442 \u043E\u0442 \u0441\u0435\u0440\u0432\u0435\u0440\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
}; //получение списка карт (брелоков) от сервера


window.getCardsByPerson = function (person_id) {
  axios.get("cards/get_list/".concat(person_id)).then(function (response) {
    var data = response.data;
    cards = [];
    var person_cards = document.getElementById("person_cards");
    person_cards.innerHTML = "";

    if (data.length > 0) {
      document.getElementById("unknown_cards").hidden = true; //спрячем неизвестные карты

      document.getElementById("cards").disabled = true; //отключим меню неизвеснтых карт

      for (var k in data) {
        //добавим каждую карту в список привязанных
        person_cards.innerHTML += "<div id=\"card".concat(data[k].id, "\">").concat(data[k].wiegand, " <button type=\"button\" onclick=\"delCard(").concat(data[k].id, ");\">\u041E\u0442\u0432\u044F\u0437\u0430\u0442\u044C</button><br /></div>");
      }

      var li = document.getElementById("person".concat(person.id)); //добавим пользователю метку наличия ключей

      var a = li.querySelector(".person");
      a.classList.remove("no-card");
    } else {
      document.getElementById("unknown_cards").hidden = false; //отобразим неизвестные карты

      document.getElementById("cards").disabled = false; //включим меню неизвеснтых карт

      getCards();
    }
  }).catch(function (error) {
    console.log(error);
  });
}; //добавление карты в БД


window.saveCard = function (card_id) {
  axios.post("cards/holder", {
    card_id: card_id,
    person_id: person.id
  }).then(function (response) {
    if (response.data > 0) {
      getCardsByPerson(person.id);
      alert("\u041A\u043B\u044E\u0447 \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0434\u043E\u0431\u0430\u0432\u043B\u0435\u043D"); //TODO перевод
    } else {
      alert("\u041D\u0435\u0438\u0437\u0432\u0435\u0441\u0442\u043D\u0430\u044F \u043E\u0448\u0438\u0431\u043A\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
}; //удаление карты из БД


window.delCard = function (card_id) {
  if (!confirm("\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u0435 \u0443\u0434\u0430\u043B\u0435\u043D\u0438\u0435.")) {
    //TODO перевод
    return;
  }

  axios.post("cards/holder", {
    card_id: card_id,
    person_id: 0
  }).then(function (response) {
    if (response.data > 0) {
      var card = document.getElementById("card".concat(card_id));
      card.remove(); //удалим карту из списка привязанных

      var cardsHtml = document.getElementById("person_cards").innerHTML;
      cardsHtml = cardsHtml.trim ? cardsHtml.trim() : cardsHtml.replace(/^\s+/, "");

      if (cardsHtml == "") {
        //если список привязанных карт пуст, то отобразим и включим меню и запросим неизвеснтые карты
        document.getElementById("unknown_cards").hidden = false;
        document.getElementById("cards").disabled = false;
        getCards();
        var li = document.getElementById("person".concat(person.id)); //удалим у пользователя метку наличия ключей

        var a = li.querySelector(".person");
        a.classList.add("no-card");
      }

      alert("\u041A\u043B\u044E\u0447 \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u043E\u0442\u0432\u044F\u0437\u0430\u043D"); //TODO перевод
    } else {
      alert("\u041D\u0435\u0438\u0437\u0432\u0435\u0441\u0442\u043D\u0430\u044F \u043E\u0448\u0438\u0431\u043A\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
};

/***/ }),

/***/ "./resources/js/ac/controllers.js":
/*!****************************************!*\
  !*** ./resources/js/ac/controllers.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

axios.get('controllers/get_list').then(function (response) {
  for (var k in response.data) {
    Echo.private("controller-events.".concat(response.data[k].id)).listen('EventReceived', function (e) {
      if (!events.includes(e.event)) {
        return;
      }

      if (e.event == 16 || e.event == 17) {
        setPersonInfo(e.card_id);
      } else if (e.event == 2 || e.event == 3) {
        if (!document.getElementById("cards").disabled) {
          //если меню неизвестных карт активно
          var o = confirm("\u0412\u0432\u0435\u0434\u0435\u043D \u043D\u0435\u0438\u0437\u0432\u0435\u0441\u0442\u043D\u044B\u0439 \u043A\u043B\u044E\u0447. \u0412\u044B\u0431\u0440\u0430\u0442\u044C \u0435\u0433\u043E \u0432 \u043A\u0430\u0447\u0435\u0441\u0442\u0432\u0435 \u043D\u043E\u0432\u043E\u0433\u043E \u043A\u043B\u044E\u0447\u0430 \u043F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044F?"); //TODO перевод

          if (o) {
            getCards(e.card_id);
          }
        } else if (document.getElementById("unknown_cards").hidden) {
          var _o = confirm("\u0412\u0432\u0435\u0434\u0435\u043D \u043D\u0435\u0438\u0437\u0432\u0435\u0441\u0442\u043D\u044B\u0439 \u043A\u043B\u044E\u0447. \u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C \u0435\u0433\u043E \u0442\u0435\u043A\u0443\u0449\u0435\u043C\u0443 \u043F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044E?"); //TODO перевод


          if (_o) {
            saveCard(e.card_id);
          }
        }
      }
    }).listen('ControllerConnected', function (e) {
      SetControllerStatus(e.controller_id);
    });
  }
}).catch(function (error) {
  console.log(error);
});

window.SetControllerStatus = function (controller_id) {
  showNewEvent("\u041A\u043E\u043D\u0442\u0440\u043E\u043B\u043B\u0435\u0440 <mark>ID: ".concat(controller_id, "</mark> \u0432\u044B\u0448\u0435\u043B \u043D\u0430 \u0441\u0432\u044F\u0437\u044C."));
};

/***/ }),

/***/ "./resources/js/ac/divisions.js":
/*!**************************************!*\
  !*** ./resources/js/ac/divisions.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var division = {
  'name': null,
  'organization_id': null
};
var divisions = [];

window.openDivision = function () {};

window.getDivisions = function () {
  axios.get("divisions/get_list").then(function (response) {
    var data = response.data;

    if (data.length > 0) {
      var _divisions = "";
      data.forEach(function (div) {
        _divisions += "<div id=\"div".concat(div.id, "\" class=\"menu-item\" onclick=\"getPersons(").concat(div.id, ");\">").concat(div.name, "</div>");
      });
      var menu = document.getElementById("menu");
      menu.innerHTML = _divisions;
    } else {
      alert("\u041F\u0443\u0441\u0442\u043E\u0439 \u043E\u0442\u0432\u0435\u0442 \u043E\u0442 \u0441\u0435\u0440\u0432\u0435\u0440\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  }).then(function () {// always executed
  });
}; //сохранить в базу


window.saveDivision = function (org_id) {
  var number = document.getElementById("number").value;
  var letter = document.getElementById("letter").value;

  if (!number || !letter) {
    alert("\u0412\u0432\u0435\u0434\u0435\u043D\u044B \u043D\u0435 \u0432\u0441\u0435 \u0434\u0430\u043D\u043D\u044B\u0435"); //TODO перевод

    return;
  }

  window.div.name = "".concat(number, " \"").concat(letter, "\"");
  window.div.organization_id = org_id;
  axios.post("divisions/save", {
    div: JSON.stringify(window.div)
  }).then(function (response) {
    alert("\u041A\u043B\u0430\u0441\u0441 ".concat(response.data.name, " \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0441\u043E\u0445\u0440\u0430\u043D\u0435\u043D")); //TODO перевод

    location.reload();
  }).catch(function (error) {
    console.log(error);
  });
}; //удалить из базы


window.deleteDivision = function (div_id) {
  if (!confirm("\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u0435 \u0443\u0434\u0430\u043B\u0435\u043D\u0438\u0435.")) {
    //TODO перевод
    return;
  }

  axios.post("divisions/delete", {
    'div_id': div_id
  }).then(function (response) {
    if (response.data > 0) {
      alert("\u0423\u0441\u043F\u0435\u0448\u043D\u043E\u0435 \u0443\u0434\u0430\u043B\u0435\u043D\u0438\u0435"); //TODO перевод

      location.reload();
    } else {
      alert("\u041D\u0435\u0438\u0437\u0432\u0435\u0441\u0442\u043D\u0430\u044F \u043E\u0448\u0438\u0431\u043A\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
};

window.setDiv = function (id) {
  var index = window.divs.indexOf(id);

  if (index === -1) {
    window.divs.push(id);
    document.getElementById("div".concat(id)).classList.add("checked");
  } else {
    window.divs.splice(index, 1);
    document.getElementById("div".concat(id)).classList.remove("checked");
  }
};

/***/ }),

/***/ "./resources/js/ac/persons.js":
/*!************************************!*\
  !*** ./resources/js/ac/persons.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var person = {
  'f': null,
  'i': null,
  'o': null,
  'birthday': null,
  'address': null,
  'phone': null
};
var persons = [];

window.setPersonInfo = function (card_id) {
  axios.get("persons/get_by_card/".concat(card_id)).then(function (response) {
    if (response.data) {
      var data = response.data;

      var _loop = function _loop(k) {
        if (k === "divs") {
          data.person[k].forEach(function (div) {
            document.getElementById(k).innerHTML = div.name; //TODO списком
          });
        } else {
          var elem = document.getElementById(k);

          if (elem == null) {
            return "continue";
          }

          elem.value = data.person[k];
        }
      };

      for (var k in data.person) {
        var _ret = _loop(k);

        if (_ret === "continue") continue;
      }

      var photo_id = 0;
      var photo = document.getElementById("photo_bg");

      if (data.photos.length > 0) {
        photo_id = data.photos[0].id;
      }

      photo.style.backgroundImage = 'url(/img/ac/s/' + photo_id + '.jpg)';
    } else {
      console.log("\u041F\u0443\u0441\u0442\u043E\u0439 \u043E\u0442\u0432\u0435\u0442 \u043E\u0442 \u0441\u0435\u0440\u0432\u0435\u0440\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  }).then(function () {// always executed
  });
};

window.getPersons = function (div_id) {
  axios.get("persons/get_list/".concat(div_id)).then(function (response) {
    var data = response.data;
    var persons = "<div id=\"menu-button-back\" class=\"menu-item\" onclick=\"getDivisions();\">\u041D\u0430\u0437\u0430\u0434</div>"; //TODO перевод

    if (data.length > 0) {
      data.forEach(function (person) {
        persons += "<div id=\"person".concat(person.id, "\" class=\"menu-item\" onclick=\"openEntranceOptions(").concat(person.id, ", ").concat(div_id, ");\">").concat(person.f, " ").concat(person.i, "</div>");
      });
    }

    document.getElementById("menu").innerHTML = persons;
  }).catch(function (error) {
    console.log(error);
  });
};

window.savePersonInfo = function () {
  var checkValidity = true;

  for (var k in person) {
    var _elem = document.getElementById(k);

    if (_elem.required && _elem.value === "") {
      _elem.classList.add("no-data");

      checkValidity = false;
    }

    if (_elem.value) {
      person[k] = _elem.value;
    } else {
      person[k] = null;
    }
  }

  var elem = document.getElementById("cards");

  if (elem.value > 0) {
    cards.push(elem.value);
  }

  if (!checkValidity) {
    alert("\u0412\u0432\u0435\u0434\u0435\u043D\u044B \u043D\u0435 \u0432\u0441\u0435 \u0434\u0430\u043D\u043D\u044B\u0435"); //TODO перевод
  } else {
    axios.post("persons/save", {
      cards: JSON.stringify(cards),
      divs: JSON.stringify(divs),
      person: JSON.stringify(person),
      photos: JSON.stringify(photos)
    }).then(function (response) {
      var person_id = response.data;

      for (var _k in person) {
        person[_k] = null;
      }

      cards = [];
      alert("\u041F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044C \u2116".concat(person_id, " \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0441\u043E\u0445\u0440\u0430\u043D\u0435\u043D")); //TODO перевод

      clearPersonInfo();
    }).catch(function (error) {
      console.log(error);
    });
  }
};

window.clearPersonInfo = function () {
  for (var k in person) {
    document.getElementById(k).value = null;
  }

  photos = [];
  document.getElementById("cards").value = 0;
  document.getElementById("photo_bg").style.backgroundImage = 'url(/img/ac/s/0.jpg)';
  document.getElementById("photo_del").hidden = true;

  document.getElementById("photo_del").onclick = function () {
    return false;
  };
}; //обновление информации пользователя в БД


window.updatePersonInfo = function () {
  var checkValidity = true;

  for (var k in person) {
    var _elem2 = document.getElementById(k);

    if (_elem2.required && _elem2.value === "") {
      _elem2.classList.add("no-data");

      checkValidity = false;
    }

    if (_elem2.value) {
      person[k] = _elem2.value;
    } else {
      person[k] = null;
    }
  }

  var elem = document.getElementById("cards");

  if (elem.value > 0) {
    cards.push(elem.value);
  }

  if (!checkValidity) {
    alert("\u0412\u0432\u0435\u0434\u0435\u043D\u044B \u043D\u0435 \u0432\u0441\u0435 \u0434\u0430\u043D\u043D\u044B\u0435"); //TODO перевод
  } else {
    axios.post("persons/save", {
      cards: JSON.stringify(cards),
      divs: JSON.stringify(divs),
      person: JSON.stringify(person),
      photos: JSON.stringify(photos)
    }).then(function (response) {
      if (response.data > 0) {
        alert("\u041F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044C \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0441\u043E\u0445\u0440\u0430\u043D\u0435\u043D"); //TODO перевод
      } else {
        alert("\u041D\u0435 \u0441\u043E\u0445\u0440\u0430\u043D\u0435\u043D\u043E \u0438\u043B\u0438 \u0434\u0430\u043D\u043D\u044B\u0435 \u0441\u043E\u0432\u043F\u0430\u043B\u0438"); //TODO перевод
      }

      getCardsByPerson(person.id);
    }).catch(function (error) {
      console.log(error);
    });
  }
}; //удаление пользователя из БД


window.deletePerson = function () {
  if (!confirm("\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u0435 \u0443\u0434\u0430\u043B\u0435\u043D\u0438\u0435.")) {
    //TODO перевод
    return;
  }

  axios.post("persons/delete", {
    person_id: person.id
  }).then(function (response) {
    if (response.data > 0) {
      var currentElement = document.getElementById("person".concat(person.id));
      var parentElement = currentElement.parentElement; //родитель этого элемента

      currentElement.remove(); //удаляем элемент

      var lastElement = parentElement.lastElementChild;

      if (lastElement !== null) {
        lastElement.classList.add("tree-is-last"); //устанавливаем последний элемент в ветке
      }

      for (var k in person) {
        var elem = document.getElementById(k);
        elem.value = null;
        elem.readOnly = true;
        person[k] = null;
      }

      photos = [];
      document.getElementById("photo_bg").style.backgroundImage = 'url(/img/ac/s/0.jpg)';
      document.getElementById("photo").hidden = true;

      document.getElementById("photo").onchange = function () {
        return false;
      };

      document.getElementById("photo_del").onclick = function () {
        return false;
      };

      document.getElementById("photo_del").hidden = true;
      cards = [];
      document.getElementById("cards").value = 0;
      document.getElementById("person_cards").innerHTML = ""; //очистка списка привязанных карт

      document.getElementById("unknown_cards").hidden = false; //отобразим меню с неизвестными картами

      document.getElementById("cards").disabled = true; //но запретим редактирование

      window.divs = [];

      document.getElementById("save").onclick = function () {
        return false;
      };

      document.getElementById("delete").onclick = function () {
        return false;
      };

      alert("\u041F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044C \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0443\u0434\u0430\u043B\u0435\u043D"); //TODO перевод
    } else {
      alert("\u041F\u0443\u0441\u0442\u043E\u0439 \u043E\u0442\u0432\u0435\u0442 \u043E\u0442 \u0441\u0435\u0440\u0432\u0435\u0440\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
}; //получение данных пользователя из БД


window.getPersonInfo = function (person_id) {
  axios.get("persons/get/".concat(person_id)).then(function (response) {
    var data = response.data;

    if (data) {
      for (var k in data.person) {
        var elem = document.getElementById(k);

        if (elem == null) {
          continue;
        }

        person[k] = data.person[k];
        elem.value = data.person[k];
        elem.readOnly = false;
      }

      var photo_id = 0;
      window.photos = [];
      document.getElementById("photo").value = null;

      if (data.photos.length === 0) {
        document.getElementById("photo").hidden = false;
        document.getElementById("photo_del").hidden = true;

        document.getElementById("photo_del").onclick = function () {
          return false;
        };
      } else {
        photo_id = data.photos[0].id;
        photos.unshift(photo_id);
        document.getElementById("photo").hidden = true;
        document.getElementById("photo_del").hidden = false;
        document.getElementById("photo_del").onclick = deletePhoto;
      }

      document.getElementById("photo_bg").style.backgroundImage = 'url(/img/ac/s/' + photo_id + '.jpg)';

      document.getElementById("photo").onchange = function () {
        handleFiles(this.files);
      };

      window.divs = [];

      for (var _k2 in data.divs) {
        divs.push(data.divs[_k2].id);
      }

      document.getElementById("save").onclick = updatePersonInfo;
      document.getElementById("delete").onclick = deletePerson;
    } else {
      alert("\u041F\u0443\u0441\u0442\u043E\u0439 \u043E\u0442\u0432\u0435\u0442 \u043E\u0442 \u0441\u0435\u0440\u0432\u0435\u0440\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
};

/***/ }),

/***/ "./resources/js/ac/photos.js":
/*!***********************************!*\
  !*** ./resources/js/ac/photos.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var photos = []; //загрузка фото

window.handleFiles = function (files) {
  var formData = new FormData();
  formData.append("file", files[0]);
  axios({
    method: "post",
    url: "photos/save",
    data: formData,
    config: {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    }
  }).then(function (response) {
    var data = response.data;

    if (data) {
      if (data.error === "") {
        document.getElementById("photo_bg").style.backgroundImage = 'url(/img/ac/s/' + data.id + '.jpg)';
        photos.unshift(data.id);
        document.getElementById("photo").hidden = true;
        document.getElementById("photo_del").hidden = false;
        document.getElementById("photo_del").onclick = deletePhoto;
      } else {
        document.getElementById("photo").value = null;
        alert(data.error);
      }
    } else {
      alert("\u041D\u0435\u0438\u0437\u0432\u0435\u0441\u0442\u043D\u0430\u044F \u043E\u0448\u0438\u0431\u043A\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
}; //удаление фото


window.deletePhoto = function () {
  if (!confirm("\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u0435 \u0443\u0434\u0430\u043B\u0435\u043D\u0438\u0435.")) {
    //TODO перевод
    return;
  }

  axios.post("photos/delete", {
    photo_id: photos.shift()
  }).then(function (response) {
    if (response.data) {
      document.getElementById("photo_bg").style.backgroundImage = 'url(/img/ac/s/0.jpg)';
      document.getElementById("photo_del").hidden = true;

      document.getElementById("photo_del").onclick = function () {
        return false;
      };

      document.getElementById("photo").hidden = false;
      document.getElementById("photo").value = null;
    } else {
      alert("\u041D\u0435\u0438\u0437\u0432\u0435\u0441\u0442\u043D\u0430\u044F \u043E\u0448\u0438\u0431\u043A\u0430"); //TODO перевод
    }
  }).catch(function (error) {
    console.log(error);
  });
};

/***/ }),

/***/ "./resources/js/ac/push.js":
/*!*********************************!*\
  !*** ./resources/js/ac/push.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// Initialize Firebase
var config = {
  apiKey: "AIzaSyDI_-AwpqcTclSXCyXgYJzvaTNC-dky9iY",
  authDomain: "policam-ac.firebaseapp.com",
  databaseURL: "https://policam-ac.firebaseio.com",
  projectId: "policam-ac",
  storageBucket: "policam-ac.appspot.com",
  messagingSenderId: "1005476478589"
};
firebase.initializeApp(config); // пользователь уже разрешил получение уведомлений
// подписываем на уведомления если ещё не подписали

if (Notification.permission === "granted") {
  subscribe();
}

window.subscribe = function () {
  var messaging = firebase.messaging();
  messaging.usePublicVapidKey("BPKQjI8lJAE9pymLNyKm5fsJSsu-7vXlPZivaRvR52lxGWgsxF2TN5s_iaIKQ1LWNZPh0S8arKNOXfq9nAAB3Yg"); // запрашиваем разрешение на получение уведомлений

  messaging.requestPermission().then(function () {
    // получаем ID устройства
    messaging.getToken().then(function (currentToken) {
      console.log("\u0422\u043E\u043A\u0435\u043D \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u043F\u043E\u043B\u0443\u0447\u0435\u043D"); //TODO перевод

      if (currentToken) {
        sendTokenToServer(currentToken);
      } else {
        console.warn("\u041D\u0435 \u0443\u0434\u0430\u043B\u043E\u0441\u044C \u043F\u043E\u043B\u0443\u0447\u0438\u0442\u044C \u0442\u043E\u043A\u0435\u043D."); //TODO перевод

        setTokenSentToServer(false);
      }
    }).catch(function (err) {
      console.warn("\u041F\u0440\u0438 \u043F\u043E\u043B\u0443\u0447\u0435\u043D\u0438\u0438 \u0442\u043E\u043A\u0435\u043D\u0430 \u043F\u0440\u043E\u0438\u0437\u043E\u0448\u043B\u0430 \u043E\u0448\u0438\u0431\u043A\u0430.", err); //TODO перевод

      setTokenSentToServer(false);
    });
  }).catch(function (err) {
    console.warn("\u041D\u0435 \u0443\u0434\u0430\u043B\u043E\u0441\u044C \u043F\u043E\u043B\u0443\u0447\u0438\u0442\u044C \u0440\u0430\u0437\u0440\u0435\u0448\u0435\u043D\u0438\u0435 \u043D\u0430 \u043F\u043E\u043A\u0430\u0437 \u0443\u0432\u0435\u0434\u043E\u043C\u043B\u0435\u043D\u0438\u0439.", err); //TODO перевод
  });
}; // отправка ID на сервер


function sendTokenToServer(currentToken) {
  if (!isTokenSentToServer(currentToken)) {
    console.log("\u041E\u0442\u043F\u0440\u0430\u0432\u043A\u0430 \u0442\u043E\u043A\u0435\u043D\u0430 \u043D\u0430 \u0441\u0435\u0440\u0432\u0435\u0440..."); //TODO перевод

    axios.post("users/token", {
      token: currentToken
    }).then(function (response) {
      console.log(response.data);
    }).catch(function (error) {
      console.log(error);
    });
    setTokenSentToServer(currentToken);
  } else {
    console.log("\u0422\u043E\u043A\u0435\u043D \u0443\u0436\u0435 \u043E\u0442\u043F\u0440\u0430\u0432\u043B\u0435\u043D \u043D\u0430 \u0441\u0435\u0440\u0432\u0435\u0440."); //TODO перевод
  }
} // используем localStorage для отметки того,
// что пользователь уже подписался на уведомления


function isTokenSentToServer(currentToken) {
  return window.localStorage.getItem("sentFirebaseMessagingToken") == currentToken;
}

function setTokenSentToServer(currentToken) {
  window.localStorage.setItem("sentFirebaseMessagingToken", currentToken ? currentToken : "");
}

/***/ }),

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
__webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");

window.Vue = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// const app = new Vue({
//     el: '#app'
// });

__webpack_require__(/*! ./ac/ac */ "./resources/js/ac/ac.js");

__webpack_require__(/*! ./ac/cards */ "./resources/js/ac/cards.js");

__webpack_require__(/*! ./ac/controllers */ "./resources/js/ac/controllers.js");

__webpack_require__(/*! ./ac/divisions */ "./resources/js/ac/divisions.js");

__webpack_require__(/*! ./ac/persons */ "./resources/js/ac/persons.js");

__webpack_require__(/*! ./ac/photos */ "./resources/js/ac/photos.js");

__webpack_require__(/*! ./ac/push */ "./resources/js/ac/push.js");

/***/ }),

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var laravel_echo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! laravel-echo */ "./node_modules/laravel-echo/dist/echo.js");
window._ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.Popper = __webpack_require__(/*! popper.js */ "./node_modules/popper.js/dist/esm/popper.js").default;
  window.$ = window.jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

  __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.js");
} catch (e) {}
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */


window.axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

var token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */



window.Pusher = __webpack_require__(/*! pusher-js */ "./node_modules/pusher-js/dist/web/pusher.js");
window.Echo = new laravel_echo__WEBPACK_IMPORTED_MODULE_0__["default"]({
  authEndpoint: 'broadcasting/auth',
  broadcaster: 'pusher',
  key: "4b0f5261202dcbf80bd4",
  wsHost: window.location.hostname,
  wsPort: "6002",
  wssPort: "6002",
  disableStats: true
});

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! C:\policam.ru\resources\js\app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! C:\policam.ru\resources\sass\app.scss */"./resources/sass/app.scss");


/***/ })

},[[0,"/js/manifest","/js/vendor"]]]);