requirejs.config({
    shim: {
        'Modernizr': {
            //These script dependencies should be loaded first
            deps: [],
            //Once loaded, use the global as the module value.
            exports: 'Modernizr'
        }
    },
     //To get timely, correct error triggers in IE, force a define/shim exports check.
    // enforceDefine: true,
    paths: {
        Modernizr: "libs/modernizr.custom.37295"
    }
});

require([
    'jquery',
    'Modernizr',
    'model/user'
    ], function($, Mod, User){
    'user strict';

    var user = new User();
});