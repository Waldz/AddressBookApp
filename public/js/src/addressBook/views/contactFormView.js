/*global require,define*/
define([
    'backbone',
    'backbone-stickit',
    'underscore',
    'underscore-string',
    'addressBook/models/contactModel',
    'text!addressBook/templates/contactFormTemplate.jst'
],
function (
    Backbone,
    Stickit,
    _,
    String,
    ContactModel,
    Template
) {
    'use strict';

    function defaultObserver(attribute) {
        return {
            observe: attribute,
            onSet: function (value) {
                return String(value).trim().value();
            }
        };
    };

    return Backbone.View.extend({

        bindings: {
            '#title': defaultObserver('title'),
            '#name': defaultObserver('name'),
            '#email': defaultObserver('email'),
            '#supervisorName': defaultObserver('supervisorName')
        },

        errorBindings: {
            'title': '#title',
            'name': '#name',
            'email': '#email',
            'supervisorName': '#supervisorName'
        },

        /**
         * @class ContactFormView
         * @extends {Backbone.View}
         */
        initialize : function (args) {
            Backbone.View.prototype.initialize.apply(this, arguments);

            this.model = args.model || new ContactModel();
        },

        /**
         * Render view.
         */
        render: function () {
            var template = _.template(Template);

            this.$el.html(template({
                model: this.model
            }));
            this.stickit();
        },

        clearMessage: function () {
            this.$('#messagePlaceholder').html('');
        },

        displayMessage: function (message) {
            this.$('#messagePlaceholder').html(message);
        },

        clearErrors: function () {
            this.$('.form-group').removeClass('has-error');
            this.$('.form-group .help-block').html('');
        },

        /**
         * @param {Array} errors
         */
        displayFieldErrors: function (errors) {
            this.clearErrors();

            _.each(errors, function (message, field) {
                this.displayFieldError(field, message)
            }.bind(this));
        },

        /**
         * @param {string} field
         * @param {string} message
         */
        displayFieldError: function (field, message) {
            if (this.errorBindings.hasOwnProperty(field)) {
                var $field = this.$(this.errorBindings[field]),
                    $group = $field.closest('.form-group');

                $group.addClass('has-error');
                $group.find('.help-block').html(message);
            }
        },

        isValid: function() {
            return true;
        },

        /**
         * Disable view.
         */
        disable: function () {
            this.$('fieldset').prop('disabled', true);
            return this;
        },

        /**
         * Enable view.
         */
        enable: function () {
            this.$('fieldset').prop('disabled', false);
            return this;
        }

    });
});
