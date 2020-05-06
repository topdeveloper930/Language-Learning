require('../bootstrap');
require('../modules/tabsNavigation');
const validate = require("validate.js");

import slideOutMenu from "../modules/slideOutMenu";
import simpleModal from "../components/simpleModal";
import fileUploadModal from "../components/fileUploadModal";
import SocialButtons from "../components/SocialButtons";
import SerializeForm from "../modules/SerializeForm";
import toaster from "../modules/toaster";
import ProfileImage from "../components/ProfileImage";
import errorInputMixin from "../mixins/errorInputMixin";
import validatorTranslationsMixin from "../mixins/validatorTranslationsMixin";

const activeStatuses = ['Active', 'Inactive'],
    defaultConstraints = { presence: {allowEmpty: false}, length: { maximum: 150 }},
    app = new Vue({
        el: '#root',
        components: { SocialButtons, simpleModal, ProfileImage, fileUploadModal },
        mixins: [ errorInputMixin, validatorTranslationsMixin ],
        data() {
            return {
                validate: validate,
                serializeForm: new SerializeForm().toJson,
                toaster: new toaster({}),
                formsData: {
                    settings_form: {},
                    security_form: {},
                    profile_form: {}
                },
                constraints: {
                    settings_form: {
                        mailingList: {
                            inclusion: {
                                within: activeStatuses
                            }
                        },
                        classLogMessages: {
                            inclusion: {
                                within: activeStatuses
                            }
                        },
                        classReminder: {
                            inclusion: {
                                within: activeStatuses
                            }
                        }
                    },
                    security_form: {
                        current_password: {
                            presence: {allowEmpty: false}
                        },
                        password: {
                            presence: {allowEmpty: false},
                            length: { minimum: 8 }
                        },
                        password_confirmation: {
                            presence: {allowEmpty: false},
                            equality: {
                                attribute: "password",
                                message: Shared.__('confirm_password')
                            }
                        }
                    },
                    profile_form: {
                        title: {
                            presence: {allowEmpty: false},
                            inclusion: {
                                within: ['Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Prof.']
                            }
                        },
                        firstName: defaultConstraints,
                        lastName: defaultConstraints,
                        timezone: defaultConstraints,
                        dateOfBirth: {},
                        information: {length: { maximum: 400 }},
                        skype: defaultConstraints,
                        phone: value => value ? {length: { minimum: 10, maximum: 16 }} : false,
                        paypalEmail: {
                            email: value => !!value
                        },
                        paypalEmail_confirmation: {
                            equality: {
                                attribute: "paypalEmail"
                            }
                        },
                        country: defaultConstraints,
                        state: defaultConstraints,
                        city: defaultConstraints
                    }
                },
                errors: {},
                spin: null,
                profileImage: initImage
            }
        },
        computed: {
            fullname: function () {
                return (this.formsData.profile_form.title + ' ' + this.formsData.profile_form.firstName + ' ' + this.formsData.profile_form.lastName).trim();
            }
        },
        methods: {
            formInit: function(){
                for( let i of Object.keys( this.formsData ) )
                    this.formsData[i] = this.serializeForm(i);
            },

            submitImage: function( img ) {
                const self = this,
                    formData = new FormData();

                formData.append( 'file', img );

                window.axios.post( ajax_url + '/photo',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(
                    r => {
                        if( r.data.success ) {
                            self.setNewImage( r.data.success );
                            self.toaster.success( self.sh.__( 'image_updated' ));
                            Shared.$emit( 'file_upload_close' );
                        }
                        else {
                            self.toaster.caution( self.sh.__( 'image_not_updated' ) );
                        }
                    },
                    err => {
                        if ( 422 === err.response.status ) {
                            self.toaster.caution(self.sh.__('failure_validation'));
                            Shared.$emit('file_upload_error', err.response.data.file);
                        }
                        else {
                            self.toaster.caution( self.sh.__('failure_photo' ) );
                        }
                    }
                );
            },
            submitForm: function (id) {
                const self = this,
                    action = id.replace('_form', '');

                Object.keys( self.formsData[id] ).map(k => self.formsData[id][k] = self.formsData[id][k].trim());

                self.setErrors( self.validate( self.formsData[id], self.constraints[id] ) || {} );

                if( self.sh.empty( self.errors ) ) {
                    self.spin = id;
                    window.axios
                        .put( ajax_url + '/' + action, self.formsData[id] )
                        .then(
                            response => {
                                if( response.data.success )
                                    self.toaster.success( self.sh.__('success_' + action) );
                                else
                                    self.toaster.caution( self.sh.__('failure_' + action) );
                            },
                            err => {
                                self.setErrors( err.response.data );

                                switch ( err.response.status ) {
                                    case 422:
                                        self.toaster.caution( self.sh.__('failure_validation') );
                                        break;
                                    default:
                                        self.toaster.caution( self.sh.__('failure_' + action) );
                                }
                            }
                        )
                        .finally(() => {
                            self.spin = null;
                            self.formsData.security_form = {};
                        });
                }
                else {
                    setTimeout(
                        () => document.querySelector('.field-invalid-label').parentElement.scrollIntoView(),
                        200
                    );
                }
            },

            outputErrors: function (id) {
                return this.errors[id].join('<br>');
            },

            setNewImage: function (src) {
                this.profileImage = src;

                document.querySelector( '.avatar > img' ).src = src;
            },

            countriesTypeahead: function () {
                const self = this,
                    countries = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        prefetch: '/pub/location/country?plucked=1'
                    });

                // passing in `null` for the `options` arguments will result in the default options being used
                $('#country').typeahead( null, {
                    name: 'countries',
                    source: countries
                })
                    .on('typeahead:selected', function(evt, item) {
                        self.formsData.profile_form.country = item;
                        self.formsData.profile_form.state = '';
                        $('#state').typeahead('destroy');
                        self.regionsTypeahead();
                    });
            },

            regionsTypeahead: function () {
                const self = this,
                    regions = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        prefetch: '/pub/location/region?plucked=1&country=' + self.formsData.profile_form.country
                    });

                // passing in `null` for the `options` arguments will result in the default options being used
                $('#state').typeahead( null, {
                    name: 'regions',
                    source: regions
                })
                    .on('typeahead:selected', function(evt, item) {
                        self.formsData.profile_form.state = item;
                    });
            }
        },
        created() {
            const self = this;
            self.formInit();
        },
        mounted() {
            this.countriesTypeahead();

            if( this.formsData.profile_form.country )
                this.regionsTypeahead();
        }
    });