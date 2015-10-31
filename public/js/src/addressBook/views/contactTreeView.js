/*global require,define*/
define([
    'backbone',
    'dialog/views/modalDialogView',
    'button/views/buttonsView',
    'addressBook/views/contactFormView',
    'addressBook/models/contactModel'
],
function (
    Backbone,
    ModalDialogView,
    ButtonsView,
    ContactFormView,
    ContactModel
) {
    'use strict';

    return Backbone.View.extend({

        /**
         * @class ContactTreeView
         * @extends {Backbone.View}
         */
        initialize : function (args) {
            Backbone.View.prototype.initialize.apply(this, arguments);
        },

        events: {
            'click .contact-new': function (event) {
                var clickedEl = this.$(event.currentTarget),
                    supervisorId = clickedEl.attr('data-contact-id');

                this.showContactCreateForm(supervisorId);
            }
        },

        /**
         * @param {int} supervisorId
         */
        showContactCreateForm: function (supervisorId) {
            var contact = new ContactModel({
                    supervisor_id: supervisorId
                }),
                contactView = new ContactFormView({
                    model: contact
                }),
                buttonsView = new ButtonsView(),
                dialogView = new ModalDialogView();

            buttonsView.addButton('cancel', 'btn-default', 'Cancel', function () {
                dialogView.close();
            });

            buttonsView.addButton('save', 'btn-primary', 'Save', function () {
                if (contactView.isValid()) {
                    var promise = contact.save().done(function () {
                        contactView.displayMessage('Contact created');
                        dialogView.close();
                    });

                    // Freeze UI
                    contactView.disable();
                    promise.always(function () {
                        contactView.enable();
                    });

                    buttonsView.disable();
                    promise.always(function () {
                        buttonsView.enable();
                    });

                    // Handle fatal errors
                    contactView.clearMessage();
                    promise.fail(function (response) {
                        var errorMessage = response.responseJSON.message || 'Failed to save contact';
                        contactView.displayMessage(errorMessage);
                    });

                    // Handle validation errors
                    contactView.clearErrors();
                    promise.fail(function (response) {
                        if (response.status === 400) {
                            var validationErrors = response.responseJSON;
                            contactView.displayFieldErrors(validationErrors);
                        }
                    });
                }
            });

            dialogView
                .setTitle('Add supervised person')
                .setBodyView(contactView)
                .setFooterView(buttonsView)
                .render()
                .show();
        },

        /**
         * Render view.
         */
        render: function () {

        }
    });
});
