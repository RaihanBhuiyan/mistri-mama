<template>
    <v-container fluid style="padding:0; background-color: #fff">
        <v-layout row style="">
            <v-flex md7 hidden-sm-and-down>
                <img style="width:100%; height: 100%;" :src="loginBackground"  alt="MM" />
            </v-flex>
            <v-flex md5>
                <v-card class="elevation-0 login-registration-form">
                    <v-card-title>
                        <h5 class="text-center">Login</h5>
                    </v-card-title>
                    <v-divider class="m-0"></v-divider>
                    <v-form ref="loginForm" v-model="valid" lazy-validation @submit.prevent="loginPrevent">
                        <v-card-text>
                            <v-text-field color="accent" v-model="loginFormData.phone" :error-messages="display_errors.phone" type="number" label="Phone Number *" append-icon="call" outlined></v-text-field>
                            <v-text-field color="accent" v-model="loginFormData.password" :error-messages="display_errors.password" :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Password *" outlined></v-text-field>
                            <v-checkbox v-model="rememberMe" label="Remember Me"></v-checkbox>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn flat small class="text-truncate" to="forgot-password">Forgot password?</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn large color="primary" type="submit">Login</v-btn>
                        </v-card-actions>
                    </v-form>

                    <div style="padding: 8px;">
                        <GoogleLogin :params="{client_id: '812804009292-o7c58kc2li9jgnk22k5vb26jl0uu5pa3.apps.googleusercontent.com'}" :onSuccess="onSuccess" class="google-login-btn" style="">
                            <strong>
                                <img class="" src="../assets/images/200px-Google__G__Logo.png" style="width: 20px; height: 20px" alt="MM" />
                            </strong>
                            <span>Login with Google</span>
                        </GoogleLogin>
                        <facebook-login class="button" style="padding: 5px 0; width: 48%; margin-left: 5px; display: inline-block;"
                            appId="692720754720898"
                            loginLabel="Login with Facebook"
                            @login="getFacebookUserData">
                        </facebook-login>
                    </div>
                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-if="guestRegistrationModal" v-model="guestRegistrationModal" max-width="480">
            <v-form ref="guestRegistrationForm" v-model="valid" lazy-validation @submit.prevent="guestRegistrationPrevent">
                <v-card>
                    <v-card-title class="white--text" style="background-color: #febe00;">Enter You Phone No</v-card-title>
                    <v-card-text>
                        <v-text-field color="accent" v-model="guestRegistrationFormData.phone" :error-messages="display_errors.phone_no" label="Phone No"></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" @click="guestRegistrationModal = false">Close</v-btn>
                        <v-btn color="primary" type="submit">Next</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <v-dialog v-if="guestRegistrationOtpModal" v-model="guestRegistrationOtpModal" max-width="480">
            <v-form ref="guestRegistrationVerifyOtpForm" v-model="valid" lazy-validation @submit.prevent="guestRegistrationVerifyOtpPrevent">
                <v-card>
                    <v-card-title class="white--text" style="background-color: #febe00;">Enter OTP code</v-card-title>
                    <v-card-text>
                        <v-text-field color="accent" v-model="guestRegistrationFormData.otp_code" :error-messages="display_errors.otp_code" label="OTP Code"></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" @click="guestRegistrationOtpModal = false">Close</v-btn>
                        <v-btn color="primary" type="submit">Registration</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
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
    import {
        localStorageService
    }
    from "../helper.js";
    
    import GoogleLogin from 'vue-google-login';
    import facebookLogin from 'facebook-login-vuejs';

    export default {
        components: {
            GoogleLogin,
            facebookLogin
        },
        data() {
            return {
                isConnected: false,
                valid: null,
                display_errors: [],
                loginFormData: {
                    phone: null,
                    password: null,
                },
                guestRegistrationModal: false,
                guestRegistrationOtpModal: false,
                guestRegistrationFormData: {
                    phone: null,
                    name: null,
                    email: null,
                    otp_code: null,
                },
                rememberMe: false,
                passShow: null,
                alertMessage: null,
                snackbar: false,
                isLoggedIn: false,
                loginBackground: null,
            };
        },
        created() {
            this.loadPageInformations();
            if (localStorage.d_token) {
                this.isLoggedIn = true;
                this.auth_user = localStorageService.getItem("currentUserData");
            } else {
                this.isLoggedIn = false;
            }
        },
        methods: {
            async loadPageInformations(){
                let loader = this.$loading.show();
                let response = await axios.get("/page/login");
                this.loginBackground = response.data;
                loader.hide();
            },
            async loginPrevent() {
                let loader = this.$loading.show();
                if (this.$refs.loginForm.validate()) {
                    this.display_errors = [];
                    try {
                        let response = await axios.post("/login", {
                            phone: this.loginFormData.phone,
                            password: this.loginFormData.password
                        });
                        this.$store.commit("setUserInfo", {
                            afterLoginUserData: response.data.user,
                            d_token: response.data.access_token
                        });
                        let usertype = localStorageService.getItem("currentUserData").type;
                        if(usertype == "client")
                        {
                            this.$router.replace(this.$route.query.redirect || "/user");
                        } 
                        else if(usertype == "esp")
                        {
                            this.$router.replace(this.$route.query.redirect || "/shokolkaaj");
                        }
                        else if(usertype == "comrade")
                        {
                            this.$router.replace(this.$route.query.redirect || "/comrade-home");
                        }
                        else if(usertype == "fsp")
                        {
                                this.$router.replace(this.$route.query.redirect || "/shokolkaaj");
                        }
                        else
                        {
                            localStorage.removeItem("currentUserData");
                            localStorage.removeItem("d_token");
                            this.alertMessage = "Authentication failed !";
                            this.snackbar = true;
                        }
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
            onSuccess(googleUser) {
                var profile = {
                    id: googleUser.getBasicProfile().getId(), 
                    email: googleUser.getBasicProfile().getEmail(),
                    name: googleUser.getBasicProfile().getName()
                };
                this.checkSocialLogin(profile)
            },
            onFailure() {
                alert("Social Login Failed");
            },
            onCurrentUser() {
                alert("Current User");
            },
            async checkSocialLogin(credentials)
            {
                try {
                    let response = await axios.post("/check_social_login", {
                        email: credentials.email,
                        name: credentials.name,
                        app_id: credentials.id,
                    });

                    if(response.data == false)
                    {
                        this.guestRegistrationFormData.name = credentials.name;
                        this.guestRegistrationFormData.email = credentials.email;
                        this.guestRegistrationModal = true;
                    }
                    else
                    {
                        this.$store.commit("setUserInfo", {
                            afterLoginUserData: response.data.user,
                            d_token: response.data.access_token
                        });
                        this.$router.replace(this.$route.query.redirect || "/user");
                    }
                } catch (error) {
                }
            },

            // Facebook
            getFacebookUserData() {
                FB.login((response)=>{
                    if (response.status === 'connected') {
                        FB.api('/me', 'GET', { fields: 'id,name,email,picture' },
                            user => {
                                this.checkSocialLogin({email: user.email, name: user.name, app_id: user.id})
                            }
                        )
                    }
                    else
                    {
                        console.log("Login failed!");
                    }
                });
            },
            async guestRegistrationPrevent() {
                let loader = this.$loading.show();
                if (this.$refs.guestRegistrationForm.validate()) {
                    this.display_errors = [];
                    try {
                        var response = await axios.post("/send_otp", this.guestRegistrationFormData);
                        console.log(response);
                        this.guestRegistrationModal = false;
                        this.guestRegistrationOtpModal = true;
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
            async guestRegistrationVerifyOtpPrevent() {
                let loader = this.$loading.show();
                if (this.$refs.guestRegistrationVerifyOtpForm.validate()) {
                    this.display_errors = [];
                    try {
                        var response_otp = await axios.post("/varify-password-otp", this.guestRegistrationFormData);
                        if (response_otp.status == 200) {
                            this.guestRegistrationOtpModal = false;

                            var response = await axios.post("/social_authentication", this.guestRegistrationFormData);
                            if (response.status == 200) {
                                this.$store.commit("setUserInfo", {
                                    afterLoginUserData: response.data.user,
                                    d_token: response.data.access_token
                                });
                                this.$router.replace(this.$route.query.redirect || "/user");
                            }
                            else
                            {
                                this.alertMessage = response.data.message;
                                this.snackbar = true;
                                this.guestRegistrationModal = false;
                                this.guestRegistrationOtpModal = false;
                            }
                        }
                        else
                        {
                            this.alertMessage = response_otp.data.message;
                            this.snackbar = true;
                            this.guestRegistrationModal = true;
                            this.guestRegistrationOtpModal = false;
                        }

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
            redirectLoggedUser(usertype)
            {
                
            }
        }
    };
</script>

<style type="text/css">
.google-login-btn{
    display: inline-block;
    width: 48%;
    text-align: left;
    border-radius: 4px;
    font-size: 16px;
    border: 1px solid #4286F5;
    font-weight: 500;
    background-color: #4286F5;
    color: #fff;
    margin-right: 5px;
}
.google-login-btn strong{
    padding: 9px 11px;
    color: #333;
    display: inline-block;
    border-radius: 4px;
    margin: 1px;
    background-color: #fff;
}
.google-login-btn span{
    padding: 0px 11px;
    display: inline-block;
}

.container.button button{
    width: 100%;
    text-align: left;
    border-radius: 4px;
    display: block;
    vertical-align: middle;
    font-weight: 500;
    font-size: 16px;
    padding-left: 54px;
    color: #fff;
    line-height: 46px;
}
.container.button button .spinner{
    height: 30px;
    top: 8px;
}
.container.button button img{
    top: 7px;
}
</style>