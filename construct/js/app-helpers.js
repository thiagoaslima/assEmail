(function(G, doc, $, undefined) {
    'use strict';

    $.fn.removeFromArray = function(from, to) {
        // Array Remove - By John Resig (MIT Licensed)
        var rest = this.slice((to || from) + 1 || this.length);
        this.length = from < 0 ? this.length + from : from;
        return this.push.apply(this, rest);
    };

    var helpers = {
        contactsQuery: function(classes) {
            var cl = (classes.split(" ")[0] === classes) ? classes : helpers.getContactClass(classes.split(" ")),
                data = helpers.getClassNumber(cl);
                G.console.log('query:', data.cl, '-', data.ind);
            return {
                cl: data.cl,
                ind: data.ind
            };
        },

        defField: function(str) {
            return ".field" + str.substring(0,1).toUpperCase() + str.substring(1);
        },

        getContactClass: function(array) {
            var i, len = array.length, resp;
            for (i=0; i<len; i++) {
                if ($.trim(array[i]).indexOf('contacts') === 0) resp = $.trim(array[i]);
            }
            return resp;
        },
        getClassNumber: function(str) {
            var pos = str.search(/[0-9]/);

            return {
                cl: str.substring(0, pos),
                ind: str.substring(pos)
            };
        },

        prepareTemplate: function() {
            var tmp = $('#tmp-cNumber').html(),
                i = $('.remove').length;
            return tmp.replace(/contacts/g, 'contacts'+i);
        }
    };

}(this, this.document, this.jQuery));