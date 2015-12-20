define(
    ['backbone', 'underscore'],
    function (Backbone, _) {
        'use strict';

        /**
         * @class ContactModel
         */
        var ContactModel = Backbone.Model.extend({

            idAttribute: 'id',

            defaults: {
                id: null,
                title: null,
                name: null,
                email: null,
                supervisor: null,
                supervised_contacts: []
            },

            url: function () {
                if (this.isNew()) {
                    return 'contact-api.php';
                }

                return 'contact-api.php?id=' + this.get('id')
            },

            /**
             * @returns {boolean}
             */
            hasSupervisor: function () {
                var supervisor = this.get('supervisor');
                return !_.isUndefined(supervisor) && !_.isNull(supervisor);

            },

            /**
             * @returns {ContactModel}
             */
            getSupervisor: function () {
                if (this.hasSupervisor()) {
                    return new ContactModel(this.get('supervisor'));
                }

                return null;
            },

            /**
             * @param {ContactModel} supervisor
             * @returns {ContactModel}
             */
            setSupervisor: function (supervisor) {
                this.set('supervisor', supervisor ? supervisor.toJSON() : null);

                return null;
            },

            /**
             * @returns {boolean}
             */
            hasSupervicedContacts: function () {
                var contacts = this.get('supervised_contacts');

                return _.isArray(contacts) && _.size(contacts) > 0;

            },

            /**
             * @returns {ContactModel[]}
             */
            getSupervicedContacts: function () {
                return _.map(
                    this.get('supervised_contacts'),
                    function (contact) {
                        return new ContactModel(contact);
                    }
                );
            }

        });

        return ContactModel;
    }
);
