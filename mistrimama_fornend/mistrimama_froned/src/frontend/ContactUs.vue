<template>
    <div fluid style="margin-top:150px" class="container contact-page-section" id="contactus">
        <div class="row">
            <div class="col-lg-6">
                <div class="sec-title">
                    <span class="float-text">{{ $tc('contact_form',1) }} </span>
                    <h2>{{ $tc('contact_form',0) }}</h2>
                </div>
                <div class="contact-form">
                    <v-form ref="contactUsForm" v-model="valid" lazy-validation @submit.prevent="contactUsStore">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <v-text-field  color="accent" v-model="contactUsForm.name" :error-messages="display_errors.name" type="text" 
                                v-bind:label="$tc('contact_form',2)" outlined></v-text-field>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <v-text-field  color="accent" v-model="contactUsForm.phone"  prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" v-bind:label="$tc('contact_form1',0)" outlined></v-text-field>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <v-text-field  color="accent" v-model="contactUsForm.company" :error-messages="display_errors.company" type="text" v-bind:label="$tc('contact_form1',1)" outlined></v-text-field>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <v-text-field  color="accent" v-model="contactUsForm.email" :error-messages="display_errors.email" type="text" v-bind:label="$tc('contact_form1',2)" outlined></v-text-field>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <v-textarea color="accent" v-model="contactUsForm.message" :error-messages="display_errors.message"v-bind:label="$tc('contact_form2',1)" v-bind:placeholder="$tc('contact_form2',1)" ></v-textarea>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <button :disabled="isTrue ? false : true" class="theme-btn btn-style-three mt-3" type="submit" name="submit-form">{{ $tc('contact_form2',0) }}</button>
                            </div>
                        </div>
                    </v-form>
                </div>
            </div>
            <div class="map-column col-lg-6">
                <div class="inner-column">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.383745283798!2d90.40608961449269!3d23.73369129533243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b9590048c46b%3A0x4f2d99cc0463a1cc!2sMistri%20Mama!5e0!3m2!1sen!2sbd!4v1583748129871!5m2!1sen!2sbd" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
    import {
        required, numeric
    }
    from "vuelidate/lib/validators";
    import axios from "../axios_instance.js";
    import {
        localStorageService
    }
    from "../helper.js";

    export default {
        inject: ["theme"],
        data() {
            return {
                valid: null,
                display_errors: [],
                contactUsForm: {
                    name: null,                   
                    phone: null ,                   
                    company: null,                   
                    email: null,                   
                    message: null,                   
                },
                isTrue: true,
                // nameRules: [
                //     v => !!v || "This field cannot be empty",
                //     v => (v && v.length <= 20) || "Name must be less than 20 characters"
                // ],
                // phoneRules: [
                //     v => !!v || "This field cannot be empty", 
                //     v => v.match(/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/)|| "Invalid mobile number (+8801XXXXXXXXX)",
                // ],
                // emailRules: [
                //     v => /.+@.+/.test(v) || 'E-mail must be valid'
                // ],
                alertMessage: null,
                snackbar: false,
            };
        },
        created() { 
        },
        computed: {
        },
        methods: {
            async contactUsStore() {
                if (this.$refs.contactUsForm.validate()) {
                    this.display_errors = [];
                    this.isTrue = false;
                    try {
                        let response = await axios.post("/contact_us", this.contactUsForm);
                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                        this.contactUsForm = {
                            name: null,                   
                            phone: null ,                   
                            company: null,                   
                            email: null,                   
                            message: null,                   
                        }
                        this.isTrue = true;
                    } catch (error) {
                        if(error.response.data.errors)
                        {
                            this.display_errors = error.response.data.errors;
                        }
                        this.alertMessage = error.response.data.message;
                        this.snackbar = true;
                        this.isTrue = true;
                    }
                }
            },
        }
    };
</script>