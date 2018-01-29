/*
        __           __                                ___       _ __        __
   ____/ /___ ______/ /_  _________  ____ ___     ____/ (_)___ _(_) /_____ _/ /
  / __  / __ `/ ___/ __ \/ ___/ __ \/ __ `__ \   / __  / / __ `/ / __/ __ `/ /
 / /_/ / /_/ / /__/ / / / /__/ /_/ / / / / / /  / /_/ / / /_/ / / /_/ /_/ / /
 \__,_/\__,_/\___/_/ /_/\___/\____/_/ /_/ /_/   \__,_/_/\__, /_/\__/\__,_/_/
                                                       /____/
 copyright @ 2016, dachcom digital

 */
var formBuilder = (function() {

    'use strict';

    var self = {

        init: function() {

            self.startSystem();

        },

        startSystem: function() {

            this.loadForms();

        },

        loadForms: function() {

            /*

             // Use those Events in your Project!

             $('form.ajax-form').on('formbuilder.success', function(ev, message, redirect, $form) {
             console.log(messages);
             }).on('formbuilder.error', function(ev, message, $form) {
             console.log(messages);
             }).on('formbuilder.error-field', function(ev, data, $form) {
             console.log(messages);
             });

             */

            $('form.formbuilder.ajax-form').on('submit', function(ev) {

                var $form = $(this),
                    $btns = $form.find('.btn');

                ev.preventDefault();

                $btns.attr('disabled', 'disabled');

                // convert to a formData object
                var formData = new FormData();
                var data = $form.serializeArray();
                data.forEach(function(date) {
                    formData.append(date.name, date.value);
                });

                $form.find('input[type=file]').each(function(key, htmlElement) {
                    var fileField = $(this);
                    var fileFieldName = fileField.attr('name');
                    for (var i = 0; i < htmlElement.files.length; i++) {
                        formData.append(fileFieldName, htmlElement.files[i]);
                    }
                });
                $('.popover-on').popover('destroy').removeClass('.popover-on');

                $.ajax({

                    url: '/plugin/Formbuilder/ajax/parse',

                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request

                    success: function(response) {

                        $btns.attr('disabled', false);

                        $form.find('.help-block').remove();
                        $form.find('.form-group').removeClass('has-error');

                        if (response.success === false) {

                            if (response.validationData !== false) {

                                $.each(response.validationData, function(fieldId, messages) {

                                    var $fields = $form.find('.element-' + fieldId),
                                        $field = $fields.first(),
                                        $formGroup = null,
                                        $spanEl = null;

                                    if ($field.length > 0) {

                                        $formGroup = $field.closest('.form-group');

                                        $.each(messages, function(validationType, message) {

                                            $formGroup.addClass('has-error');
                                            $formGroup.find('span.help-block').remove();

                                            //its a multiple field
                                            //$spanEl = $('<p/>', {'class': 'help-block', 'text': message});

                                            $field.addClass('popover-on');
                                            $field.popover({
                                                //title: 'Twitter Bootstrap Popover',
                                                placement: 'top',
                                                container: 'form',
                                                trigger: 'focus',
                                                content: message,
                                                animation: false
                                            });
                                            $field.popover('show');


                                            if ($fields.length > 1) {
                                                $field.closest('label').before($spanEl);
                                            } else {
                                                $field.before($spanEl);
                                            }

                                        });

                                        $form.trigger('formbuilder.error-field', [{
                                            'field': $field,
                                            'messages': messages
                                        }, $form]);

                                    }

                                });

                            } else {

                                $form.trigger('formbuilder.error', [response.message, $form]);
                            }

                        } else {

                            $form.trigger('formbuilder.success', [response.message, response.redirect, $form]);
                            $form.find('input[type=text], textarea').val('');

                            if (typeof grecaptcha === 'object' && $form.find('.g-recaptcha:first').length > 0) {
                                grecaptcha.reset();
                            }

                        }

                    }

                });

            });

        }

    };

    // API
    return {

        init: self.init

    };

})();

$(function() {
    'use strict';
    formBuilder.init();
});
