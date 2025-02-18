<template>
    <v-container fluid style="padding:0; background-color: #fff">
        <v-layout row style="">
            <v-flex md8 hidden-sm-and-down>
                <img style="width:100%; height: 100%;" src="forgotPasswordBackground"  alt="MM" />
            </v-flex>
            <v-flex md4>
                <v-card class="elevation-0 login-registration-form">
                    <v-card-title>
                        <h5 class="text-center">Forgot password</h5>
                    </v-card-title>
                    <v-divider class="m-0"></v-divider>
                    <v-form v-if="forgotPasswordForm" ref="forgotPasswordForm" v-model="valid" lazy-validation @submit.prevent="forgotPasswordPrevent">
                        <v-card-text>
                            <v-text-field color="accent" v-model="forgotPasswordData.phone" :error-messages="display_errors.phone" type="number" label="Insert your phone number *" append-icon="call" outlined></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" type="submit">Submit</v-btn>
                        </v-card-actions>
                    </v-form>
                    <v-card-text v-if="varifyPasswordOtpForm">
                        <p>A code has been sent to your phone number. Please enter the code below.</p>
                        <v-text-field color="accent" v-model="otpCode" :error-messages="display_errors.otp_code" type="number" label="Enter Code *" append-icon="mobile_screen_share" outlined></v-text-field>
                        <v-btn @click="varifyPasswordOtp()" color="primary">DONE</v-btn>
                        <v-btn @click="optReSend()" color="secondary">Resend</v-btn>
                    </v-card-text>
                    <v-form v-if="resetPaaswordForm" ref="resetPaaswordForm" v-model="valid" lazy-validation @submit.prevent="resetPaaswordPrevent">
                        <v-card-text>
                            <v-text-field color="accent" v-model="resetPaaswordFormData.password" :error-messages="display_errors.password"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Password *" outlined></v-text-field>
                            <v-text-field color="accent" v-model="resetPaaswordFormData.confirmPassword" :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Confirm Password *" outlined></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" type="submit">Reset Password</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>                
            </v-flex>
        </v-layout>
        <v-dialog v-model="confirmationDialog" max-width="290">
            <v-card>
                <v-card-title class="headline">Alert !</v-card-title>
                <v-card-text>Your password has been changed. Thanks</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="confirmationDialogClose()">Ok</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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

    export default {
        inject: ["theme"],
        data() {
            return {
                confirmationDialog: false,
                valid: null,
                display_errors: [],
                forgotPasswordForm: true,
                varifyPasswordOtpForm: false,
                resetPaaswordForm: false,
                otpCode: null,
                // phoneRules: [
                //     v => !!v || "This field cannot be empty",
                //     v => (v && v.length == 11) || "Invalid mobile number"
                // ],
                // requiredRules: [
                //     v => !!v || "This field cannot be empty"
                // ],
                forgotPasswordData: {
                    phone: null,
                },
                passShow: null,
                resetPaaswordFormData: {
                    password: null,
                    confirmPassword: null,
                },
                alertMessage: null,
                snackbar: false,
                loginBackground: null,
            };
        },
        created() {
            this.loadPageInformations();
        },
        methods: {
            async forgotPasswordPrevent() {
                let loader = this.$loading.show();
                if (this.$refs.forgotPasswordForm.validate()) {
                    this.display_errors = [];
                    try {
                        let response = await axios.post("/forgot-password", {
                            phone: this.forgotPasswordData.phone,
                        });

                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                        this.varifyPasswordOtpForm = true;
                        this.forgotPasswordForm = false;
                        
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
            async varifyPasswordOtp() {
                let loader = this.$loading.show();
                this.display_errors = [];
                try {
                    let response = await axios.post("/varify-password-otp", {
                        phone: this.forgotPasswordData.phone,
                        otp_code: this.otpCode
                    });

                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.varifyPasswordOtpForm = false;
                    this.resetPaaswordForm = true;

                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.snackbar = true;
                    this.alertMessage = error.response.data.message;
                }
                loader.hide();
            },
            async optReSend()
            {
                let loader = this.$loading.show();
                this.display_errors = [];
                try {
                    let response = await axios.post("/forgot-password", {
                        phone: this.forgotPasswordData.phone,
                    });

                    this.alertMessage = response.data.message;
                    this.snackbar = true;

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
            async resetPaaswordPrevent() {
                let loader = this.$loading.show();
                if (this.$refs.resetPaaswordForm.validate()) {
                    this.display_errors = [];
                    try {
                        let response = await axios.post("/reset-password", {
                            phone: this.forgotPasswordData.phone,
                            password: this.resetPaaswordFormData.password,
                            password_confirmation: this.resetPaaswordFormData.confirmPassword
                        });

                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                        this.confirmationDialog = true;

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
            confirmationDialogClose(){
                this.$router.replace(this.$route.query.redirect || "/user");
            },
            async loadPageInformations(){
                let loader = this.$loading.show();
                let response = await axios.get("/page/forgot-password");
                this.loginBackground = response.data;
                loader.hide();
            },
        }
    };
</script>