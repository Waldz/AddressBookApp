/*global require,define*/
define([
    'backbone',
    'dialog/views/modalDialogView',
    'button/views/buttonsView',
    'addressBook/views/contactFormView',
    'addressBook/models/contactModel',
    'text!addressBook/templates/contactListItemTemplate.jst'
],
function (
    Backbone,
    ModalDialogView,
    ButtonsView,
    ContactFormView,
    ContactModel,
    ContactListItemTemplate
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
            },
            'click .contact-delete': function (event) {
                var clickedEl = this.$(event.currentTarget),
                    contactId = clickedEl.attr('data-contact-id');

                this.showContactDeleteForm(contactId);
            }
        },

        /**
         * @param {int} supervisorId
         */
        showContactCreateForm: function (supervisorId) {
            var contact = new ContactModel(),
                contactView = new ContactFormView({
                    model: contact,
                    supervisorId: supervisorId
                }),
                buttonsView = new ButtonsView(),
                dialogView = new ModalDialogView();

            buttonsView.addButton('cancel', 'btn-default', 'Cancel', function () {
                dialogView.close();
            });

            buttonsView.addButton('save', 'btn-primary', 'Save', function () {
                if (contactView.isValid()) {
                    var promise = contact.save().done(function () {
                        dialogView.close();

                        this.renderContact(contact);
                        this.highlightContact(contact.get('id'));
                    }.bind(this));

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
                        var errorMessage = this.getFailureTextFromJson(response) || 'Failed to create contact';
                        contactView.displayMessage(errorMessage, 'danger');
                    }.bind(this));

                    // Handle validation errors
                    contactView.clearErrors();
                    promise.fail(function (response) {
                        if (response.status === 400) {
                            var validationErrors = response.responseJSON;
                            contactView.displayFieldErrors(validationErrors);
                        }
                    }.bind(this));
                }
            }.bind(this));

            dialogView
                .setTitle('Add supervised person')
                .setBodyView(contactView)
                .setFooterView(buttonsView)
                .render()
                .show();
        },

        /**
         * @param {int} contactId
         */
        showContactDeleteForm: function (contactId) {
            var contact = new ContactModel({
                    id: contactId
                }),
                buttonsView = new ButtonsView(),
                dialogView = new ModalDialogView();

            buttonsView.addButton('cancel', 'btn-default', 'Cancel', function () {
                dialogView.close();
            });

            buttonsView.addButton('delete', 'btn-primary', 'Delete', function () {
                var promise = contact.destroy();

                promise.done(function () {
                    dialogView.close();

                    var $contact = this.findContactElement(contactId);
                    this.showMessageAtContact(contactId, 'Contact deleted');
                    $contact.remove();
                }.bind(this));

                // Freeze UI
                buttonsView.disable();
                promise.always(function () {
                    buttonsView.enable();
                });
            }.bind(this));

            dialogView
                .setTitle('Delete contact')
                .setBodyText('Do You realy want to delete?')
                .setFooterView(buttonsView)
                .render()
                .show();
        },

        /**
         * Get error text for JSON response which is known to be bad.
         *
         * @param {Object} response
         * @param {string} [noJsonMessage]
         *
         * @returns {string}
         */
        getFailureTextFromJson: function (response, noJsonMessage) {
            // JSON response marked as failed, because contains no JSON
            if (response.status === 200) {
                return noJsonMessage || 'Server returned unexpected response, please verify system installation.';
            }

            // Otherwise JSON response bring error message
            if (!_.isUndefined(response.responseJSON)) {
                if (!_.isUndefined(response.responseJSON.message)) {
                    return response.responseJSON.message;
                }
            }

            return null;
        },

        /**
         * @param {int} contactId
         * @return jQuery
         *
         * @private
         */
        findContactElement: function(contactId) {
            return this.$('.contact[data-contact-id='+ contactId +']');
        },

        /**
         * @param {ContactModel} contact
         */
        renderContact: function (contact) {
            var template = _.template(ContactListItemTemplate),
                $supervisorContacts;

            if (contact.hasSupervisor()) {
                $supervisorContacts = this
                    .findContactElement(contact.getSupervisor().get('id'))
                    .find('.contact-group')
                    .first();
            } else {
                $supervisorContacts = this.$('.contact-group-root')
            }

            $supervisorContacts.append(template({
                contact: contact
            }));
        },

        /**
         * Blink contact in tree
         *
         * @param {int} contactId
         */
        highlightContact: function (contactId) {
            var $contact = this.findContactElement(contactId);

            $contact
                .addClass('bg-success')
                .css('opacity', 0.5)
                .fadeTo(1500, 1, function whenComplete() {
                    $contact.removeClass('bg-success');
                });
        },

        /**
         * Render message at contact in tree
         *
         * @param {int} contactId
         * @param {string} message
         */
        showMessageAtContact: function (contactId, message) {
            var $contact = this.findContactElement(contactId),
                $message = $('<span>');

            // Show message
            $message
                .html(message)
                .addClass('bg-success')
                .insertAfter($contact);

            // Blink and remove
            $message
                .css('opacity', 0.5)
                .fadeTo(1500, 1, function whenComplete() {
                    $message.remove();
                });
        },

        /**
         * Render view.
         */
        render: function () {

        }
    });
});
