<template>
    <v-container style="">
        <v-card elevation="0" max-width="450" class="mx-auto">
            <v-form ref="changePaaswordForm" v-model="valid" lazy-validation @submit.prevent="changePaaswordPrevent">
                <v-card-title class="white--text" style="background-color: #febe00;">
                    <h5>পাসওয়ার্ড পরিবর্তন</h5>
                </v-card-title>
                <v-text-field color="accent" v-model="changePaaswordFormData.present_password" :rules="requiredRules" :error-messages="display_errors.present_password"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="বর্তমান পাসওয়ার্ড *" outlined></v-text-field>
                <v-text-field color="accent" v-model="changePaaswordFormData.password" :rules="requiredRules" :error-messages="display_errors.password"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="নতুন পাসওয়ার্ড *" outlined></v-text-field>
                <v-text-field color="accent" v-model="changePaaswordFormData.confirmPassword" :rules="requiredRules"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="কনফার্ম পাসওয়ার্ড *" outlined></v-text-field>
                <p color="secondary" v-if="changePaaswordFormData.password != changePaaswordFormData.confirmPassword">Password did not match!</p>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" type="submit">পরিবর্তন করুন</v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
import { mapState } from "vuex";
import axios from "../axios_instance.js";
import { localStorageService } from "../helper.js";
export default {
    name: "UserProfile",
    data() {
        return {
            valid: null,
            display_errors: [],
            requiredRules: [
                v => !!v || "This field cannot be empty"
            ],
            passShow: null,
            changePaaswordFormData: {
                present_password : null,
                password: null,
                confirmPassword: null,
            },
            snackbar: null,
            alertMessage: null,
        };
    },
    methods: {
      
        async changePaaswordPrevent() {
            if (this.$refs.changePaaswordForm.validate()) {
                try {
                    var response = await axios.post('/change-password', {
                        present_password :  this.changePaaswordFormData.present_password,
                        password: this.changePaaswordFormData.password,
                        password_confirmation: this.changePaaswordFormData.confirmPassword
                    });
                    if(response.data.status == 1){
                        this.alertMessage = response.data.message;
                        this.snackbar = true; 
                    }else{
                        this.alertMessage = response.data.message;
                        this.snackbar = true; 
                    }
                
                } catch (error) {
                    console.log(error);
                    this.display_errors = error.response.data.errors;
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            }
        },
    },
    watch: {
        //
    },
    computed: {
        //
    },
};
</script>
