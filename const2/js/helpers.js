require(['jquery'], function($){

    helpers = {

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
            getProp: function(key) {
                var el, i, len;

                key = this.serializeKey(key);

                for (i = 0, len = key.length; i<len; i++) {
                    el = this[key[i]];
                }

                return el;
            },

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
                return Array.prototype.split.call(key, '.');
            }
        }
    };
});