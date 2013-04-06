define(['helpers'], function($h){
    'use strict';

    var $hobj = $h.obj,
        $hac = $h.access;

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

    $hobj.extendProto.call(User,{
        getProp: $hac.getProp,

        getData: function (key) {
            var el = this.getProp(key);
            return $hac.makeCopy(el);
        },

        setData: function (key, value) {
            var el = $hac.serializeKey(key),
                last = el.pop();

            el = (el.length > 0) ? this.getProp(el.join('.')) : this;
            el[last] = value;

            return this;
        }
    });

    return User;
});