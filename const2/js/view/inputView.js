define([
    'jquery',
    'helpers',
    'model/user',
    'ctrl/inputCtrl'
    ], function($, $h, User){
    'use strict';

    var $hobj = $h.obj,
        $hac = $h.access;

    function Form($dom) {
        this.$form = $dom;
    };

    $hobj.extendProto.call(FormField, {
        attachListeners: function(elem, trigger, func) {
            $(this.$form).on(trigger, elem, func);
        }
    });

});