/**
 * Renders the modal dialog without requiring to define the html in server-side template.
 */
define(
[
    'bootstrap',
    'underscore',
    'backbone',
    'text!dialog/templates/modalDialogTemplate.jst'
],
function (bs, _, Backbone, Template) {
    'use strict';

    var _container;

    function container() {
        if (!_container) {
            _container = bs('<modals>');
            bs('body').prepend(_container);
        }
        return _container;
    }

    /**
     * @class ModalDialogView
     */
    return Backbone.View.extend({

        className: 'modal fade',

        attributes: {
            'tabindex': '-1',
            'role': 'dialog',
            'aria-hidden': 'true',
            'data-backdrop': 'true',
            'data-keyboard': 'true'
        },

        initialize: function () {
            this.title = null;
            this.bodyCallback = function () {};
            this.footerCallback = function () {};
            this.closedCallbacks = [];

            this.$el.modal({'show': false});
            this.$el.on('hidden.bs.modal', function () {
                this.$el.detach();
                _.each(this.closedCallbacks, function (closedCallback) {
                    closedCallback();
                })
            }.bind(this));
        },

        /**
         * Set dialog title.
         *
         * @param {string} title Dialog title.
         * @returns {*}
         */
        setTitle: function (title) {
            this.title = title;

            return this;
        },

       /**
         * Set function to be called when dialog is closed by clicking outside of it.
         *
         * @param callback
         *
         */
        addClosedCallback: function (callback) {
            this.closedCallbacks = callback;
            return this;
        },

        /**
         * For example:
         *   dialog.setBodyFunction(function($body) { $body.html('Test'); });
         *
         * @param fn Callback function.
         * @returns {*}
         */
        setBodyCallback: function (fn) {
            this.bodyCallback = fn;

            return this;
        },

        /**
         * For example:
         *   dialog.setBodyText('Hello');
         *
         * @param fn Callback function.
         * @returns {*}
         */
        setBodyText: function (html) {
            this.setBodyCallback(function drawBody($body) {
                $body.html(html);
            });

            return this;
        },

        /**
         * For example:
         *   dialog.setBodyView(new Backbone.View());
         *
         * @param {Backbone.View} view Dialog main view
         * @returns {*}
         */
        setBodyView: function (view) {
            this.setBodyCallback(function drawBody($body) {
                view.setElement($body).render();
            });
            this.addClosedCallback(function () {
                view.remove()
            }.bind(this));

            return this;
        },

        /**
         * For example:
         *   dialog.setFooterFunction(function($footer) { $footer.html('Buttons'); });
         *
         * @param fn Callback function.
         * @returns {*}
         */
        setFooterCallback: function (fn) {
            this.footerCallback = fn;

            return this;
        },

        /**
         * For example:
         *   dialog.setFooterView(new ButtonsView());
         *
         * @param {Backbone.View} view Dialog footer view
         * @returns {*}
         */
        setFooterView: function (view) {
            this.setFooterCallback(function ($footer) {
                view.setElement($footer).render();
            });
            this.addClosedCallback(function () {
                view.remove()
            });

            return this;
        },

        /**
         * For example:
         *   dialog.setFooterText('Hello');
         *
         * @param fn Callback function.
         * @returns {*}
         */
        setFooterText: function (html) {
            this.setFooterCallback(function drawBody($body) {
                $body.html(html);
            });

            return this;
        },

        /**
         * Show this dialog.
         *
         * @returns {*}
         */
        show: function () {
            this.$el.modal('show');

            return this;
        },

        /**
         * Close this dialog.
         *
         * @returns {*}
         */
        close: function () {
            this.$el.modal('hide');

            return this;
        },

        render: function () {
            this.renderDialogSkeleton()
                .renderDialogBody()
                .renderDialogFooter();

            return this;
        },

        renderDialogSkeleton: function () {
            var template = _.template(Template),
                $modalBody = this.$('.modal-body:first');

            // Keep the same dom element for inner views.
            if ($modalBody.length > 0) {
                $modalBody.detach();
            }

            this.$el.html(template({
                title: this.title
            }));
            container().append(this.$el);

            return this;
        },

        renderDialogBody: function () {
            var $modalBody = this.$('.modal-body:first');

            // Clear existing contents from modal.
            if ($modalBody.length > 0) {
                this.$('.modal-body:first').replaceWith($modalBody);
            } else {
                $modalBody = this.$('.modal-body:first');
            }
            this.bodyCallback($modalBody);

            return this;
        },

        /**
         * Render buttons and other footer stuff
         */
        renderDialogFooter: function () {
            var $modalFooter = this.$('.modal-footer:first');

            // Clear existing contents from modal.
            if ($modalFooter.length > 0) {
                this.$('.modal-footer:first').replaceWith($modalFooter);
            } else {
                $modalFooter = this.$('.modal-footer:first');
            }
            this.footerCallback($modalFooter);

            return this;
        }

    });
});
