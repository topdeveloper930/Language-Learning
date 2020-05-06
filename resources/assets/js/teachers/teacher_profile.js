require("../bootstrap");
require("../modules/KeepTokenAlive");
require("../modules/tabsNavigation");

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

const activeStatuses = ["Active", "Inactive"],
    defaultConstraints = { presence: {allowEmpty: false}, length: { maximum: 150 }},
    app = new Vue({
        el: "#root",
        components: { SocialButtons, simpleModal, ProfileImage, fileUploadModal },
        mixins: [ errorInputMixin, validatorTranslationsMixin ],
        data() {
            return {
                validate: validate,
                profileImage: initImage,
                specializedCourses: {},
                serializeForm: new SerializeForm().toJson,
                toaster: new toaster({}),
                formsData: {
                    email_form: {},
                    paypal_form: {},
                    security_form: {},
                    profile_form: {},
                    practice_form: {},
                    personal_form: {}
                },
                constraints: {
                    email_form: {
                        email: {
                            presence: {allowEmpty: false},
                            email: true,
                            length: { maximum: 150 }
                        },
                        email_confirmation: {
                            presence: {allowEmpty: false},
                            equality: {
                                attribute: "email",
                                message: Shared.__('confirm_email')
                            }
                        }
                    },
                    paypal_form: {
                        paymentEmail: {
                            email: value => !!value
                        },
                        paymentEmail_confirmation: {
                            equality: {
                                attribute: "paymentEmail"
                            }
                        },
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
                        languagesSpoken: { length: { maximum: 500 }},
                        agesTaught: {
                            presence: {allowEmpty: false},
                            type: {
                                type: 'array',
                                message: Shared.__('wrong_type')
                            }
                        },
                        coursesTaught: {
                            type: {
                                type: 'array',
                                message: Shared.__('wrong_type')
                            }
                        }
                    },
                    personal_form: {
                        title: {
                            presence: {allowEmpty: false},
                            inclusion: {
                                within: ['Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Prof.']
                            }
                        },
                        firstName: defaultConstraints,
                        lastName: defaultConstraints,
                        timezone: defaultConstraints,
                        skype: defaultConstraints,
                        phone: value => value ? {length: { minimum: 10, maximum: 16 }} : false,
                        newStudents: {
                            presence: {allowEmpty: false},
                            inclusion: { within: ['0', '1'] }
                        },
                        countryOrigin: defaultConstraints,
                        stateOrigin: defaultConstraints,
                        cityOrigin: defaultConstraints,
                        street1Residence: { length: { maximum: 150 }},
                        street2Residence: { length: { maximum: 150 }},
                        countryResidence: defaultConstraints,
                        stateResidence: defaultConstraints,
                        cityResidence: defaultConstraints,
                        zipResidence: { presence: {allowEmpty: false}, length: { maximum: 100 }},
                    },
                    practice_form: {
                        teacherIntroduction: {length: { maximum: 65535, minimum: 150 }},
                        startedTeaching: {presence: {allowEmpty: false}},
                        education: {length: { maximum: 2000 }},
                        teachingStyle: {
                            presence: {allowEmpty: false},
                            length: { maximum: 100 }
                        },
                        workExperience: {length: { maximum: 65535 }},
                        hobbies: {length: { maximum: 1000 }},
                        favoriteWebsite: {length: { maximum: 255 }},
                        favoriteMovie: {length: { maximum: 255 }},
                        favoriteFood: {length: { maximum: 128 }},
                        countriesVisited: {length: { maximum: 500 }},
                        bucketList: {length: { maximum: 500 }}
                    },
                },
                errors: {},
                spin: null
            }
        },
        computed: {
            fullname() {
                return (this.formsData.personal_form.title + ' ' + this.formsData.personal_form.firstName + ' ' + this.formsData.personal_form.lastName).trim();
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

                Object.keys( self.formsData[id] ).map(k => self.formsData[id][k] = (self.sh.isString(self.formsData[id][k])) ? self.formsData[id][k].trim() : self.formsData[id][k]);

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
                        .finally( self.resetForms );
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

            resetForms() {
                this.spin = null;
                this.formsData.security_form = {};
                this.formsData.email_form.email_confirmation = '';
                this.formsData.paypal_form.paymentEmail_confirmation = '';
            },

            setNewImage (src) {
                this.profileImage = src;

                document.querySelector( '.avatar > img' ).src = src;
            },

            countriesTypeahead () {
                const self = this,
                    countries = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        prefetch: '/pub/location/country?plucked=1'
                    });

                // passing in `null` for the `options` arguments will result in the default options being used
                $('#countryResidence, #countryOrigin').typeahead( null, {
                    name: 'countries',
                    source: countries
                })
                    .on('typeahead:selected', function(evt, item) {
                        const countryField = evt.target.id,
                            stateField = countryField.replace('country', 'state');
                        self.formsData.personal_form[ countryField ] = item;
                        self.formsData.personal_form[ stateField ] = '';
                        $('#' + stateField).typeahead('destroy');
                        self.regionsTypeahead(stateField, item);
                    });
            },

            regionsTypeahead: function (field, country) {
                const self = this,
                    regions = [];

                regions[ country ] = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        prefetch: '/pub/location/region?plucked=1&country=' + country
                    });

                // passing in `null` for the `options` arguments will result in the default options being used
                $('#' + field).typeahead( null, {
                    name: 'regions' + country,
                    source: regions[ country ]
                })
                    .on('typeahead:selected', function(evt, item) {
                        self.formsData.personal_form[ field ] = item;
                    });
            },
            getSpecializedCourses() {
                const self = this,
                    promises = [],
                    retval = [];

                for( let i = 0; i < languagesTaught.length; i++ )
                    promises[i] = window.axios.get( '/ajax/' + languagesTaught[i] + '/courses' );

                Promise.all( promises )
                    .then( res => {
                        self.specializedCourses = {};
                        res.map( (r, i) => {
                            self.specializedCourses[languagesTaught[i]] = r.data.map( obj => obj.courseType )
                        });
                    });
            }
        },
        created() {
            const self = this;
            self.formInit();

            self.formsData.profile_form.agesTaught = agesTaught;
            self.formsData.profile_form.coursesTaught = coursesTaught;
        },
        mounted() {
            this.getSpecializedCourses();

            this.countriesTypeahead();

            if( this.formsData.personal_form.countryOrigin )
                this.regionsTypeahead( 'stateOrigin', this.formsData.personal_form.countryOrigin );

            if( this.formsData.personal_form.countryResidence )
                this.regionsTypeahead( 'stateResidence', this.formsData.personal_form.countryResidence );
        }
});