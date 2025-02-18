<template>
    <v-container fluid style="">
        <v-layout row wrap>
            <v-flex xs12 sm12 md6>
                <div v-if="personalImage" md6> 
                    <div v-if="uploadImageOption">
                        <ImageCropper :src="personalImage" @clicked="croppedImage" />
                        <v-card elevation="0">
                            <v-card-actions>
                                <v-btn color="success" @click="uploadImageOption = false; personalImage = personalImageTemp"><v-icon>check</v-icon></v-btn>
                                <v-btn color="accent" @click="uploadImageOption = false"><v-icon>close</v-icon></v-btn>
                            </v-card-actions>
                        </v-card>
                    </div>
                    <div v-if="!uploadImageOption">
                        <v-avatar :size="200" color="grey lighten-4"> 
                            <img :src="personalImage" alt="avatar">
                        </v-avatar>
                        <v-card elevation="0">
                            <v-card-actions>
                                <v-btn color="primary" @click="uploadImageOption = true"><v-icon>crop</v-icon></v-btn>
                                <v-btn color="secondary" @click="personalImage = null;"><v-icon>delete</v-icon></v-btn>
                                <v-btn color="success" @click="imageChange"><v-icon>check</v-icon></v-btn>
                            </v-card-actions>
                        </v-card>
                    </div>
                </div>
                <div v-if="!personalImage" md6>
                    <v-avatar :size="200" color="grey lighten-4">
                        <img :src="auth_user.photo" alt="avatar">
                    </v-avatar>
                    <v-card elevation="0">
                        <v-card-actions>
                            <input @change="onFileChange('personalImage', $event)" type="file" label="Upload Profile Photo" />
                        </v-card-actions>
                    </v-card>
                </div>
            </v-flex>
            <v-flex xs12 sm12 md6>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-card elevation="0">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <h5>প্রোফাইল বিবরণ</h5>
                        </v-card-title>
                        <v-text-field v-model="profileForm.name" :error-messages="display_errors.name" label="Name *"></v-text-field>
                        <v-text-field v-model="profileForm.email" :error-messages="display_errors.email" label="E-mail *"></v-text-field>
                        <v-text-field disabled v-model="profileForm.phone" :error-messages="display_errors.phone" label="Mobile Number *"></v-text-field>
                        <v-text-field v-model="profileForm.alt_phone" :error-messages="display_errors.alt_phone" label="Alt Mobile Number"></v-text-field>
                        <v-text-field v-model="profileForm.nid_no" :error-messages="display_errors.nid_no"  label="NID NO *"></v-text-field>
                        <v-textarea rows="2" v-model="profileForm.address" :error-messages="display_errors.address" label="Address" placeholder="Write down your address"></v-textarea>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="updateProfile()">পরিবর্তন করুন</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-flex>
        </v-layout>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
import { mapState } from "vuex";
import ImageCropper from "../components/ImageCropper";
import { localStorageService } from "../helper.js";
import axios from "../axios_instance.js";

export default {
    name: "UserProfile",
    components: {
        ImageCropper,
    },
    data() {
        return {
            uploadImageOption: false,
            auth_user : localStorageService.getItem("currentUserData"),
            personalImage: null,
            personalImageTemp: null,
            profileForm: {
                name: localStorageService.getItem("currentUserData").name,
                address: localStorageService.getItem("currentUserData").address,
                email: localStorageService.getItem("currentUserData").email,
                phone: localStorageService.getItem("currentUserData").phone,
                alt_phone: localStorageService.getItem("currentUserData").alt_phone,
                nid_no: localStorageService.getItem("currentUserData").nid_no,
                photo : localStorageService.getItem("currentUserData").client_type,
            },
            valid: true,
            display_errors: [],
            // nameRules: [(v) => !!v || "Name is required", (v) => (v && v.length <= 20) || "Name must be less than 20 characters"],
            // mobileNumberRules: [(v) => !!v || "Mobile number is required", (v) => (v && v.length == 11) || "Invalid mobile number"],
            // addressRules: [(v) => !!v || "Address is required", (v) => (v && v.length <= 100) || "Address must be less than 50 characters"],
            // emailRules: [(v) => !!v || "E-mail is required", (v) => /.+@.+\..+/.test(v) || "E-mail must be valid"],
            snackbar: false,
            alertMessage: null,
        };
    },
    created(){
        console.log(localStorageService.getItem("currentUserData").photo);
    },
    methods: {
        async updateProfile() {
            if (this.$refs.form.validate()) {
                try {
                    var response = await axios.post('/profile-update/comrade', this.profileForm);
                    var userData = localStorageService.getItem("currentUserData");
                    userData.name = this.profileForm.name;
                    userData.phone = this.profileForm.phone;
                    userData.alt_phone = this.profileForm.alt_phone;
                    userData.address = this.profileForm.address;
                    userData.email = this.profileForm.email;
                    userData.nid_no = this.profileForm.nid_no;  
                    localStorageService.setItem("currentUserData", userData);
                    
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
            }
        },
        onFileChange(item, e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.createImage(item, files[0]);
        },
        createImage(item, file) {
            var image = new Image();
            var reader = new FileReader();
            reader.onload = (e) => {
                this.personalImage = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        croppedImage: function (val) {
            this.personalImageTemp = val;
        },

        async imageChange() {
            try {
                var response = await axios.post('/profile-image-change/comrade', {
                    photo: this.personalImage,
                });

                var userData = localStorageService.getItem("currentUserData");
                userData.photo = this.personalImage;
                localStorageService.setItem("currentUserData", userData);
                this.auth_user.photo = this.personalImage;
                this.personalImage = null;

                this.alertMessage = response.data.message;
                this.snackbar = true;
            } catch (error) {
                this.alertMessage = error.response.data.message;
                this.snackbar = true;
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
