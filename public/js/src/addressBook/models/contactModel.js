define(
    ['backbone'],
    function (Backbone) {
        'use strict';

        /**
         * @class ContactModel
         */
        return Backbone.Model.extend({

            idAttribute: 'id',

            defaults: {
                id: null,
                title: null,
                name: null,
                email: null,
                supervisor_id: null
            },

            url: function () {
                if (this.isNew()) {
                    return 'contact-api.php';
                }

                return 'contact-api.php?id=' + this.get('id')
            }

        });
    }
);
