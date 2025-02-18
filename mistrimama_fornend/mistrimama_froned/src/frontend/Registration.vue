<template>
    <v-container fluid style="padding:0; background-color: #fff">
        <v-layout row style="">
            <v-flex md7 hidden-sm-and-down>
                <img style="width:100%; height: 100%;" :src="registrationBackground"  alt="MM" />
            </v-flex>
            <v-flex md5>
                <v-card class="elevation-0 login-registration-form">
                    <v-card-title>
                        <h5 class="text-center">Sign Up</h5>
                    </v-card-title>
                    <v-divider class="m-0"></v-divider>
                    <v-form v-if="otp == false" ref="signupForm" v-model="valid" lazy-validation @submit.prevent="signupPrevent">
                        <v-card-text>
                                <v-text-field color="accent" v-model="signupFormData.name" :error-messages="display_errors.name" type="text" label="Name *" append-icon="person" outlined></v-text-field>
                                <v-text-field color="accent" v-model="signupFormData.phone" prefix="+88" :error-messages="display_errors.phone" type="number" label="Phone Number *" append-icon="call" outlined></v-text-field>
                                <v-text-field color="accent" v-model="signupFormData.email" :error-messages="display_errors.email" type="text" label="Email" append-icon="email" outlined></v-text-field>
                                <v-select color="accent" v-model="signupFormData.area" :items="cluster" :error-messages="display_errors.area" :item-text="'name'" :item-value="'id'" label="Area *" append-icon="keyboard_arrow_down" outlined></v-select>
                                <v-textarea color="accent" v-model="signupFormData.address" :error-messages="display_errors.address" label="Address" placeholder="Write down your address *"></v-textarea>
                                <v-text-field color="accent" v-model="signupFormData.password" :error-messages="display_errors.password"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Password *" outlined></v-text-field>
                                <v-text-field color="accent" v-model="signupFormData.confirmPassword"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Confirm Password" outlined></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" type="submit">SIGN UP</v-btn>
                        </v-card-actions>
                    </v-form>
                    <v-card-text v-if="otp == true">
                        <p>A code has been sent to your phone number. Please enter the code below.</p>
                        <v-text-field color="accent" v-model="otpCode" :error-messages="display_errors.otp_code" type="text" label="Enter Code" append-icon="mobile_screen_share" outlined></v-text-field>
                    </v-card-text>
                    <v-card-actions v-if="otp == true">
                        <v-btn @click="varifyOtp()" color="primary">DONE</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" @click="otp = false">Back</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
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
                otpCode: null,
                otp: false,
                display_errors: [],
                cluster: [],
                signupFormData: {
                    name: null,
                    phone: null ,
                    email: null,
                    area: null,
                    address: null,
                    password: null,
                    confirmPassword: null,
                },
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
                // requiredRules: [
                //     v => !!v || "This field cannot be empty"
                // ],
                // addressRules: [
                //     v => (v && v.length <= 250) || "Address must be less than 250 characters"
                // ],
                passShow: null,
                alertMessage: null,
                snackbar: false,
                registrationBackground: null,
            };
        },
        created() {
            this.loadPageInformations();
            this.getLocations();
        },
        methods: {
            async loadPageInformations(){
                let loader = this.$loading.show();
                let response = await axios.get("/page/registration");
                this.registrationBackground = response.data;
                loader.hide();
            },
            async getLocations()
            {
                let response = await axios.get("division");
                this.cluster = response.data.data[0].cluster;
            },
            async signupPrevent() {
                let loader = this.$loading.show();
                if (this.$refs.signupForm.validate()) {
                    this.display_errors = [];
                    try {
                        let response = await axios.post("/register", {
                            name: this.signupFormData.name,
                            phone: this.signupFormData.phone,
                            email: this.signupFormData.email,
                            area: this.signupFormData.area,
                            address: this.signupFormData.address,
                            password: this.signupFormData.password,
                            password_confirmation: this.signupFormData.confirmPassword
                        });

                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                        this.otp = true;
                        
                    } catch (error) {
                        if(error.response.data.errors)
                        {
                            this.display_errors = error.response.data.errors;
                        }
                        this.alertMessage = error.response.data.message;
                        this.snackbar = true;
                    }
                }
                loader.hide();
            },
            async varifyOtp() {
                let loader = this.$loading.show();
                this.display_errors = [];
                try {
                    let response = await axios.post("/varify-otp", {
                        phone: this.signupFormData.phone,
                        password: this.signupFormData.password,
                        otp_code: this.otpCode
                    });
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    
                    this.$store.commit("setUserInfo", {
                        afterLoginUserData: response.data.user,
                        d_token: response.data.access_token
                    });
                    this.$router.replace(this.$route.query.redirect || "/user");

                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
                loader.hide();
            },
        }
    };
</script>