require(['helpers'], function($h){
    'use strict';

    function User() {
        this.name = '';
        this.job = '';
        this.email = '';
        this.contacts = [["tel",""]];
        this.listCont = {
            tel: [],
            cel: [],
            fax: []
        };
    }

    $h.obj.extendProto.call(User,{
        getData: function (key) {
            var $ac = $h.access,
                el = $ac.getProp(key);
            return $ac.makeCopy(el);
        },

        setData: function (key, value) {
            var $ac = $h.access,
                el = $ac.serializeKey(key),
                last = el.pop();

            el = (el.length > 0) ? $ac.getProp(el.join('.')) : this;
            el[last] = value;

            return this;
        }
    });

});