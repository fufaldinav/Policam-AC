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

window.AcClass = function (data) {
  if (data === undefined) {
    return;
  }

  var d = document;
  var selectedPerson = null;
  var selectedDivision = null;
  var form = d.forms.namedItem("form-person");
  var menu = d.getElementById("ac-menu-left");
  var buttonSave = d.getElementById("ac-button-save");
  var buttonDelete = d.getElementById("ac-button-delete");
  this.divisions = [];
  this.persons = [];

  if (data['divisions'] !== undefined) {
    for (var k in data['divisions']) {
      this.divisions[k] = new Division(data['divisions'][k]);
    }
  }

  if (data['persons'] !== undefined) {
    for (var _k in data['persons']) {
      this.persons[_k] = new Person(data['persons'][_k]);
    }
  }

  this.alert = function (text, type) {
    if (type === undefined) {
      type = "info";
    }

    var alert = d.createElement("div");
    alert.classList.add("alert", "alert-".concat(type), "alert-dismissible", "fade", "show", "ac-alert");
    alert.role = "alert";
    alert.textContent = text;
    d.body.appendChild(alert);

    function close() {
      $(alert).alert("close");
    }

    setTimeout(close, 5000);
  };

  this.listGroupDivisions = function (element) {
    selectedDivision = null;

    if (element !== undefined) {
      var division_id = _getIdFromElement(element);

      delete this.divisions[division_id].deletePerson(0);
      delete this.persons[0];
    }

    _disableForm();

    var list = d.createElement("div");
    list.id = "ac-list-divisions";
    list.classList.add("list-group", "list-group-flush");

    for (var _k2 in this.divisions) {
      var div = this.divisions[_k2];
      var count = div.persons().length;
      var button = d.createElement("button");
      button.type = "button";
      button.classList.add("list-group-item", "list-group-item-action", "d-flex", "justify-content-between", "align-items-center");
      button.id = "ac-button-division-".concat(_k2);

      button.onclick = function () {
        window.Ac.listGroupPersons(this);
      };

      button.textContent = div.name;

      if (count === 0) {
        button.disabled = true;
      }

      var badge = d.createElement("span");
      badge.classList.add("badge", "badge-primary", "badge-pill");
      badge.textContent = count;
      button.appendChild(badge);
      list.appendChild(button);
    }

    menu.innerHTML = "";
    menu.appendChild(list);
  };

  this.listGroupPersons = function (element) {
    var division_id = _getIdFromElement(element);

    selectedDivision = Ac.divisions[division_id];
    var list = d.createElement("div");
    list.id = "ac-list-persons";
    list.classList.add("list-group", "list-group-flush");
    var buttonBack = d.createElement("button");
    buttonBack.type = "button";
    buttonBack.classList.add("list-group-item", "list-group-item-info");
    buttonBack.id = "ac-button-back-".concat(division_id);

    buttonBack.onclick = function () {
      window.Ac.listGroupDivisions(this);
    };

    buttonBack.textContent = "Back";
    list.appendChild(buttonBack);
    var buttonAdd = buttonBack.cloneNode(true);
    buttonAdd.classList.remove("list-group-item-info");
    buttonAdd.classList.add("list-group-item-success");
    buttonAdd.id = "ac-button-add-".concat(division_id);

    buttonAdd.onclick = function () {
      window.Ac.newPersonInForm(this);
    };

    buttonAdd.textContent = "Add";
    list.appendChild(buttonAdd);
    var persons = this.divisions[division_id].persons();
    var _iteratorNormalCompletion = true;
    var _didIteratorError = false;
    var _iteratorError = undefined;

    try {
      for (var _iterator = persons[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
        var id = _step.value;
        var person = this.persons[id];
        var button = buttonBack.cloneNode(true);
        button.classList.remove("list-group-item-info");
        button.classList.add("list-group-item-action");
        button.id = "ac-button-person-".concat(id);

        button.onclick = function () {
          window.Ac.showPersonInForm(this);
        };

        button.textContent = "".concat(person.f, " ").concat(person.i);
        list.appendChild(button);
      }
    } catch (err) {
      _didIteratorError = true;
      _iteratorError = err;
    } finally {
      try {
        if (!_iteratorNormalCompletion && _iterator.return != null) {
          _iterator.return();
        }
      } finally {
        if (_didIteratorError) {
          throw _iteratorError;
        }
      }
    }

    menu.innerHTML = "";
    menu.appendChild(list);
  };

  this.showPersonInForm = function (element) {
    _enableForm();

    buttonSave.onclick = function () {
      window.Ac.updatePerson();
    };

    buttonSave.textContent = "Update";
    buttonDelete.classList.remove("btn-secondary");
    buttonDelete.classList.add("btn-danger");

    buttonDelete.onclick = function () {
      window.Ac.deletePerson();
    };

    buttonDelete.textContent = "Delete";

    var person_id = _getIdFromElement(element);

    selectedPerson = this.persons[person_id];

    for (var i = 0; i < form.length; i++) {
      if (selectedPerson.hasOwnProperty(form[i].id)) {
        form[i].value = selectedPerson[form[i].id];
      }
    }
  };

  this.newPersonInForm = function (element) {
    _enableForm();

    buttonSave.onclick = function () {
      window.Ac.savePerson();
    };

    buttonSave.textContent = "Save";
    buttonDelete.classList.remove("btn-danger");
    buttonDelete.classList.add("btn-secondary");

    buttonDelete.onclick = function () {
      window.Ac.clearPerson();
    };

    buttonDelete.textContent = "Cancel";

    var division_id = _getIdFromElement(element);

    this.divisions[division_id].persons().push(0);
    selectedPerson = this.persons[0] = new Person();
    selectedPerson.divisions().push(parseInt(division_id));
  };

  this.savePerson = function () {
    var _this = this;

    for (var i = 0; i < form.length; i++) {
      if (selectedPerson.hasOwnProperty(form[i].id)) {
        selectedPerson[form[i].id] = form[i].value;
      }
    }

    selectedPerson.save().then(function (data) {
      delete _this.persons[0];
      selectedDivision.deletePerson(0);
      var person = _this.persons[data.id] = new Person(data);

      for (var _k3 in data.divisions) {
        var div = data.divisions[_k3];

        _this.divisions[div.id].addPerson(person.id);
      }

      var list = d.getElementById("ac-list-persons");
      var button = d.createElement("button");
      button.type = "button";
      button.classList.add("list-group-item", "list-group-item-action");
      button.id = "ac-button-person-".concat(person.id);

      button.onclick = function () {
        window.Ac.showPersonInForm(this);
      };

      button.textContent = "".concat(person.f, " ").concat(person.i);
      list.appendChild(button);

      _this.alert("".concat(person.f, " ").concat(person.i, " \u0441\u043E\u0445\u0440\u0430\u043D\u0435\u043D \u0443\u0441\u043F\u0435\u0448\u043D\u043E"), "success");
    });
  };

  this.clearPerson = function () {
    _disableForm();

    this.alert("\u0424\u043E\u0440\u043C\u0430 \u043E\u0447\u0438\u0449\u0435\u043D\u0430");
  };

  this.updatePerson = function () {
    var _this2 = this;

    for (var i = 0; i < form.length; i++) {
      if (selectedPerson.hasOwnProperty(form[i].id)) {
        selectedPerson[form[i].id] = form[i].value;
      }
    }

    selectedPerson.update().then(function (person) {
      for (var _k4 in person.divisions) {
        var div = person.divisions[_k4];

        _this2.divisions[div.id].addPerson(person.id);
      }

      var button = d.getElementById("ac-button-person-".concat(person.id));
      button.textContent = "".concat(person.f, " ").concat(person.i);

      _this2.alert("".concat(person.f, " ").concat(person.i, " \u043E\u0431\u043D\u043E\u0432\u043B\u0435\u043D \u0443\u0441\u043F\u0435\u0448\u043D\u043E"), "success");
    });
  };

  this.deletePerson = function () {
    var _this3 = this;

    selectedPerson.delete().then(function (id) {
      var person = _this3.persons[id];
      delete _this3.persons[id];
      selectedDivision.deletePerson(id);
      var button = d.getElementById("ac-button-person-".concat(id));
      button.parentElement.removeChild(button);

      _disableForm();

      _this3.alert("".concat(person.f, " ").concat(person.i, " \u0443\u0434\u0430\u043B\u0435\u043D \u0443\u0441\u043F\u0435\u0448\u043D\u043E"));
    });
  };

  var _getIdFromElement = function (element) {
    return element.id.split("-").pop();
  }.bind(this);

  var _enableForm = function () {
    for (var i = 0; i < form.length; i++) {
      form[i].value = null;

      if (form[i].disabled && form[i].id !== "id") {
        form[i].disabled = false;
      }
    }
  }.bind(this);

  var _disableForm = function () {
    for (var i = 0; i < form.length; i++) {
      form[i].value = null;

      if (!form[i].disabled) {
        form[i].disabled = true;
      }
    }

    selectedPerson = null;
  }.bind(this);
};

var Ac = window.Ac = new AcClass(window.AcData);
delete window.AcData;

if (window.location.pathname === "/cp/persons") {
  Ac.listGroupDivisions();
}

window.showNewEvent = function (event) {
  $(".events").append("<p class=\"mb-1\"><small>".concat(event, "</small></p>"));
};

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

window.Card = function (data) {
  this.id = 0;
  this.wiegand = null;
  this.last_conn = null;
  this.controller_id = null;
  this.person_id = 0;

  for (var k in data) {
    if (this.hasOwnProperty(k)) {
      this[k] = data[k];
    }
  }
}; //получим список неизвестных карт (брелоков) из БД


window.getCards = function (id) {
  axios.get("/cards/get_list").then(function (response) {
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
  axios.get("/cards/get_list/".concat(person_id)).then(function (response) {
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
  axios.post("/cards/holder", {
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

  axios.post("/cards/holder", {
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

axios.get("/controllers/get_list").then(function (response) {
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

window.Division = function (data) {
  var persons = [];
  this.id = 0;
  this.name = null;
  this.organization_id = null;
  this.type = 1;

  for (var k in data) {
    if (this.hasOwnProperty(k)) {
      this[k] = data[k];
    }

    if (k === 'persons') {
      persons = data[k];
    }
  }

  this.persons = function (id) {
    if (id !== undefined) {
      var i = persons.indexOf(id);
      return persons[i];
    }

    return persons;
  };

  this.addPerson = function (id) {
    if (persons.indexOf(id) === -1) {
      persons.push(id);
    }
  };

  this.deletePerson = function (id) {
    var index = persons.indexOf(id);
    persons.splice(index, 1);
  };
};

/***/ }),

/***/ "./resources/js/ac/persons.js":
/*!************************************!*\
  !*** ./resources/js/ac/persons.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.Person = function (data) {
  var cards = [];
  var divisions = [];
  var photos = [];
  this.id = 0;
  this.f = null;
  this.i = null;
  this.o = null;
  this.type = 1;
  this.birthday = null;
  this.address = null;
  this.phone = null;

  for (var k in data) {
    if (this.hasOwnProperty(k)) {
      this[k] = data[k];
    }

    if (k === 'cards') {
      for (var l in data[k]) {
        cards[l] = new Card(data[k][l]);
      }
    }

    if (k === 'divisions') {
      divisions = data[k];
    }

    if (k === 'photos') {
      for (var _l in data[k]) {
        photos[_l] = new Photo(data[k][_l]);
      }
    }
  }

  this.cards = function (id) {
    if (id !== undefined) {
      var i = cards.indexOf(id);
      return cards[i];
    }

    return cards;
  };

  this.divisions = function (id) {
    if (id !== undefined) {
      var i = divisions.indexOf(id);
      return divisions[i];
    }

    return divisions;
  };

  this.photos = function (id) {
    if (id !== undefined) {
      var i = photos.indexOf(id);
      return photos[i];
    }

    return photos;
  };

  this.save = function () {
    return axios.post("/api/persons", {
      person: this,
      divisions: this.divisions(),
      cards: this.cards(),
      photos: this.photos()
    }).then(function (response) {
      return response.data;
    }).catch(function (error) {
      Ac.alert(error, "danger");
      return null;
    });
  };

  this.update = function () {
    return axios.put("/api/persons/".concat(this.id), {
      person: this,
      divisions: this.divisions(),
      cards: this.cards(),
      photos: this.photos()
    }).then(function (response) {
      for (var _k in response.data) {
        if (this.hasOwnProperty(_k)) {
          this[_k] = data[_k];
        }
      }

      return response.data;
    }).catch(function (error) {
      Ac.alert(error, "danger");
      return null;
    });
  };

  this.delete = function () {
    return axios.delete("/api/persons/".concat(this.id)).then(function (response) {
      return response.data;
    }).catch(function (error) {
      Ac.alert(error, "danger");
      return null;
    });
  };
};

/***/ }),

/***/ "./resources/js/ac/photos.js":
/*!***********************************!*\
  !*** ./resources/js/ac/photos.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.Photo = function (data) {
  this.id = 0;
  this.hash = null;
  this.person_id = 0;

  for (var k in data) {
    if (this.hasOwnProperty(k)) {
      this[k] = data[k];
    }
  }
}; //загрузка фото


window.handleFiles = function (files) {
  var formData = new FormData();
  formData.append("file", files[0]);
  axios({
    method: "post",
    url: "/photos/save",
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

  axios.post("/photos/delete", {
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
  apiKey: Object({"MIX_APP_URL":"http://192.168.1.8","MIX_PUSHER_APP_CLUSTER":"eu","MIX_PUSHER_APP_KEY":"4b0f5261202dcbf80bd4","MIX_WS_PORT":"6002","NODE_ENV":"development"}).MIX_FCM_API_KEY,
  authDomain: Object({"MIX_APP_URL":"http://192.168.1.8","MIX_PUSHER_APP_CLUSTER":"eu","MIX_PUSHER_APP_KEY":"4b0f5261202dcbf80bd4","MIX_WS_PORT":"6002","NODE_ENV":"development"}).MIX_FCM_AUTH_DOMAIN,
  databaseURL: Object({"MIX_APP_URL":"http://192.168.1.8","MIX_PUSHER_APP_CLUSTER":"eu","MIX_PUSHER_APP_KEY":"4b0f5261202dcbf80bd4","MIX_WS_PORT":"6002","NODE_ENV":"development"}).MIX_FCM_DB_URL,
  projectId: Object({"MIX_APP_URL":"http://192.168.1.8","MIX_PUSHER_APP_CLUSTER":"eu","MIX_PUSHER_APP_KEY":"4b0f5261202dcbf80bd4","MIX_WS_PORT":"6002","NODE_ENV":"development"}).MIX_FCM_PROJECT_ID,
  storageBucket: Object({"MIX_APP_URL":"http://192.168.1.8","MIX_PUSHER_APP_CLUSTER":"eu","MIX_PUSHER_APP_KEY":"4b0f5261202dcbf80bd4","MIX_WS_PORT":"6002","NODE_ENV":"development"}).MIX_FCM_STORAGE_BUCKET,
  messagingSenderId: Object({"MIX_APP_URL":"http://192.168.1.8","MIX_PUSHER_APP_CLUSTER":"eu","MIX_PUSHER_APP_KEY":"4b0f5261202dcbf80bd4","MIX_WS_PORT":"6002","NODE_ENV":"development"}).MIX_FCM_SENDER_ID
};
firebase.initializeApp(config); // пользователь уже разрешил получение уведомлений
// подписываем на уведомления если ещё не подписали

if (Notification.permission === "granted") {
  subscribe();
}

window.subscribe = function () {
  var messaging = firebase.messaging();
  messaging.usePublicVapidKey(Object({"MIX_APP_URL":"http://192.168.1.8","MIX_PUSHER_APP_CLUSTER":"eu","MIX_PUSHER_APP_KEY":"4b0f5261202dcbf80bd4","MIX_WS_PORT":"6002","NODE_ENV":"development"}).MIX_FCM_PUBLIC_VAPID_KEY); // запрашиваем разрешение на получение уведомлений

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

    axios.post("/users/token", {
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

__webpack_require__(/*! ./ac/cards */ "./resources/js/ac/cards.js");

__webpack_require__(/*! ./ac/controllers */ "./resources/js/ac/controllers.js");

__webpack_require__(/*! ./ac/divisions */ "./resources/js/ac/divisions.js");

__webpack_require__(/*! ./ac/persons */ "./resources/js/ac/persons.js");

__webpack_require__(/*! ./ac/photos */ "./resources/js/ac/photos.js");

__webpack_require__(/*! ./ac/push */ "./resources/js/ac/push.js");

__webpack_require__(/*! ./ac/ac */ "./resources/js/ac/ac.js");

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
  authEndpoint: '/broadcasting/auth',
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