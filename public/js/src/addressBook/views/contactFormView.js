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

        modelBindings: {
            '#title': defaultObserver('title'),
            '#name': defaultObserver('name'),
            '#email': defaultObserver('email')
        },

        supervisorBindings: {
            '#supervisorName': defaultObserver('name')
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
            this.supervisorModel = new ContactModel({
                id: args.supervisorId
            });

            this.syncSupervisorToModel();
        },

        /**
         * Sync supervisor model data contact model
         */
        syncSupervisorToModel: function () {
            this.model.setSupervisor(this.supervisorModel);

            this.listenTo(this.supervisorModel, 'change', function () {
                this.model.setSupervisor(this.supervisorModel);
            }.bind(this));
        },

        /**
         * Fetch assigned supervisor structure to contact
         */
        fetchSupervisor: function () {
            var promise = this.supervisorModel.fetch();

            promise.done(function () {
                this.model.setSupervisor(this.supervisorModel);
            }.bind(this));

            // Freeze UI
            this.disable();
            promise.always(function () {
                this.enable();
            }.bind(this));
        },

        /**
         * Render view.
         */
        render: function () {
            var template = _.template(Template);

            this.$el.html(template({
                model: this.model
            }));

            this.stickit(this.model, this.modelBindings);
            this.stickit(this.supervisorModel, this.supervisorBindings);

            this.fetchSupervisor();
        },

        clearMessage: function () {
            this.$('#messagePlaceholder')
                .addClass('hide')
                .html('');
        },

        displayMessage: function (message, level) {
            var level = level || 'info';

            this.$('#messagePlaceholder')
                .removeClass()
                .addClass('alert alert-' + level)
                .html(message);
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
