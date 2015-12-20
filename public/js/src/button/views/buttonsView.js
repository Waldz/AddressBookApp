/**
 * Renders the any kind of buttons.
 */
define(
[
    'bootstrap',
    'underscore',
    'backbone',
    'text!button/templates/defaultButtonsTemplate.jst'
],
function (bs, _, Backbone, DefaultButtonsTemplate) {
    'use strict';

    /**
     * @class ButtonsView
     * @extends {Backbone.View}
     */
    return Backbone.View.extend({

        events: {
            'click .button-item': function (event) {
                if (this.allDisabled) {
                    return;
                }

                var button = this.findButtonById(event.target.id);
                if (button.enabled && button.visible) {
                    if (bs.isFunction(button.actionCallback)) {
                        button.actionCallback();
                    }
                }
            }
        },

        /**
         * Initialize the view.
         */
        initialize: function () {
            _.defaults(this, {
                template: DefaultButtonsTemplate,
                buttons: [],
                allDisabled: false
            });
        },

        /**
         * Changes template of view
         *
         * @param {String} template
         * @returns {*}
         */
        setTemplate: function (template) {
            this.template = template;
            return this;
        },

        /**
         * Add an action button to this view.
         *
         * @param {string} id Button id.
         * @param {string} cssClass Button cssClass.
         * @param {string} title Button title.
         * @param {*} actionCallback Button click function.
         * @returns {*}
         */
        addButton: function (id, cssClass, title, actionCallback) {
            this.buttons[this.buttons.length] = {
                id: id,
                title: title,
                cssClass: cssClass,
                titleAttribute: undefined,
                actionCallback: actionCallback,
                enabled: true,
                visible: true
            };
            if (null !== this.view) {
                this.render();
            }
            return this;
        },

        /**
         * Retrieves button.
         *
         * @param {string} id Button id.
         * @returns {jQuery}
         */
        getButton: function (id) {
            return this.$('#' + id);
        },

        /**
         * Return true if button exists.
         *
         * @param {string} id Button id.
         * @returns {boolean}
         */
        hasButton: function (id) {
            var button = this.findButtonById(id);

            return !_.isUndefined(button) && !_.isNull(button);
        },

        /**
         * A private helper function to iterate over the buttons and get button index in array.
         *
         * @param {string} buttonId Button id.
         * @returns {Object}
         *
         * @private
         */
        findButtonById: function (buttonId) {
            return _.find(this.buttons, function (button) {
                return button.id ==buttonId;
            });
        },

        /**
         * Check if button is visible.
         *
         * @param {string} id Button id.
         * @returns {boolean}
         */
        isVisibleButton: function (id) {
            return this.findButtonById(id).visible === true;
        },

        /**
         * Hide button.
         *
         * @param {string} id Button id.
         * @returns {*}
         */
        hideButton: function (id) {
            this.findButtonById(id).visible = false;
            if (null !== this.view) {
                this.render();
            }
            return this;
        },

        /**
         * Show button.
         *
         * @param {string} id Button id.
         * @returns {*}
         */
        showButton: function (id) {
            this.findButtonById(id).visible = true;
            if (null !== this.view) {
                this.render();
            }
            return this;
        },

        /**
         * Show/hide button by flag.
         *
         * @param {string} id Button id.
         * @param {boolean} visible
         *
         * @returns {*}
         */
        toggleButtonVisible: function (id, visible) {
            if (visible) {
                this.showButton(id)
            } else {
                this.hideButton(id)
            }
            return this;
        },

        /**
         * Check if button is enabled.
         *
         * @param {string} id Button id.
         * @returns {boolean}
         */
        isEnabledButton: function (id) {
            return this.findButtonById(id).enabled === true;
        },

        /**
         * Enable button.
         *
         * @param {string} id Button id.
         * @returns {*}
         */
        enableButton: function (id) {
            this.findButtonById(id).enabled = true;
            if (null !== this.view) {
                this.render();
            }
            return this;
        },

        /**
         * Disable button.
         *
         * @param {string} id Button id.
         * @returns {*}
         */
        disableButton: function (id) {
            this.findButtonById(id).enabled = false;
            if (null !== this.view) {
                this.render();
            }
            return this;
        },

        /**
         * Enable/disable button by flag.
         *
         * @param {string} id Button id.
         * @param {boolean} enabled
         *
         * @returns {*}
         */
        toggleButtonEnable: function (id, enabled) {
            if (enabled) {
                this.enableButton(id)
            } else {
                this.disableButton(id)
            }
            return this;
        },

        /**
         * Disable this view.
         */
        disable: function () {
            this.allDisabled = true;
            if (null !== this.view) {
                this.render();
            }
            return this;
        },

        /**
         * Enable this view.
         */
        enable: function () {
            this.allDisabled = false;
            if (null !== this.view) {
                this.render();
            }
            return this;
        },

        /**
         * Render buttons.
         *
         * @returns {*}
         */
        render: function () {
            var template = _.template(this.template);

            this.$el.html(template({
                allDisabled: this.allDisabled,
                buttons: this.buttons
            }));

            return this;
        }

    });
});
