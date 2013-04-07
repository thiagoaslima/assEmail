define(['jquery'], function($){
    'use strict';

    var helpers = {

        obj: {
            extend: function(obj) {
                for (var prop in obj) {
                    this[prop] = obj[prop];
                }
            },

            extendProto: function(obj) {
                for (var prop in obj) {
                    this.prototype[prop] = obj[prop];
                }
            }
        },

        access: {
            getProp: (function() {
                var self = this;

                return function(key) {
                    var el, i, len;

                    key = self.serializeKey(key);
                    el = this[key[0]];
                    
                    for (i = 1, len = key.length; i<len; i++) {
                        el = el[key[i]];
                    }

                    return el;
                }
            }),

            makeCopy: function(el) {
                var resp;

                if (typeof el === 'string' || typeof el === 'number') {
                    resp = el;
                } else if ($.isArray(el)) {
                    resp = $.extend(true, [], el);
                } else if (typeof el === 'object' &&  el !== null) {
                    resp = $.extend(true, {}, el);
                }

                return resp;
            },

            serializeKey: function(key) {
                key = key.replace(/\[/g, '.').replace(/\]/g, '');
                return String.prototype.split.call(key, '.');
            }
        }
    };

    return helpers;
});