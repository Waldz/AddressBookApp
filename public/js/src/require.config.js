/*global require,define*/

// Configure requireJS
require.config({
    baseUrl: '/js/src',
    paths: {
        'require': '../vendor/requirejs/require',
        'text': '../vendor/text/text',
        'underscore': '../vendor/underscore/underscore-min',
        'underscore-string': '../vendor/underscore.string/underscore.string.min',
        'jquery': '../vendor/jquery/jquery.min',
        'backbone': '../vendor/backbone/backbone-min',
        'backbone-stickit': '../vendor/backbone.stickit/backbone.stickit',
        'bootstrap': '../vendor/bootstrap/js/bootstrap.min'
    },
    shim: {
        backbone: ['underscore', 'jquery'],
        bootstrap: {
            deps: ['jquery'],
            exports: '$'
        }
    }
});

// Bootstrap is needed for menu
require(
    ['bootstrap'],
    function(bs) {}
);
