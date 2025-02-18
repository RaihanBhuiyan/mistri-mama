<template>
    <v-container fluid style="">
        <v-layout row wrap>
            <v-flex xs12 sm12 md6>
                <div class="profile_photo_section">
                    <div v-if="uploadProfilePhotoOption">
                        <div v-if="uploadProfilePhotoCrop">
                            <ImageCropper :src="uploadProfilePhotoOption" @clicked="croppedProfilePhoto" />
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="success" @click="uploadProfilePhotoCrop = false; uploadProfilePhotoOption = profilePhotoTemp"><v-icon>check</v-icon></v-btn>
                                    <v-btn color="accent" @click="uploadProfilePhotoCrop = false"><v-icon>close</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                        <div v-if="!uploadProfilePhotoCrop">
                            <v-avatar :size="150" color="grey lighten-4">
                                <img :src="uploadProfilePhotoOption" alt="avatar">
                            </v-avatar>
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="primary" @click="uploadProfilePhotoCrop = true"><v-icon>crop</v-icon></v-btn>
                                    <v-btn color="secondary" @click="uploadProfilePhotoOption = null;"><v-icon>delete</v-icon></v-btn>
                                    <v-btn color="success" @click="profilePhotoChange"><v-icon>check</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                    </div>
                    <div v-if="!uploadProfilePhotoOption">
                        <v-avatar :size="150" color="grey lighten-4">
                            <label for="UploadProfilePhoto" style="height: 150px; width: 150px;">
                                <img :src="auth_user.photo" alt="avatar">
                                <span style="right: 0px; top: 12px;"><i class="material-icons">photo_camera</i></span>
                            </label>
                            <input style="display: none;" id="UploadProfilePhoto" @change="onPhotoFileChange('profilePhoto', $event)" type="file" label="Upload Profile Photo" />
                        </v-avatar>
                    </div>
                </div>
            </v-flex>
            <v-flex xs12 sm12 md6>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-card elevation="0">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <h5>প্রোফাইল বিবরণ</h5>
                        </v-card-title>
                        <v-text-field v-model="profileForm.name" :error-messages="display_errors.name" label="Name *" required></v-text-field>
                        <v-text-field v-model="profileForm.email" :error-messages="display_errors.email" label="E-mail" required></v-text-field>
                        <v-text-field disabled v-model="profileForm.phone" :error-messages="display_errors.phone" label="Mobile Number" required></v-text-field>
                        <v-select :items="itemsMfs" v-model="profileForm.mfs_type" :error-messages="display_errors.mfs_type" label="Select MFS"></v-select>
                        <v-text-field v-model="profileForm.mfs_no" :error-messages="display_errors.mfs_no" label="MFS Number (Bkash) *"></v-text-field>
                        <v-textarea rows="2" v-model="profileForm.address" :error-messages="display_errors.address" label="Address" placeholder="Write down your address" required></v-textarea>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="updateProfile()">পরিবর্তন করুন</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-flex>
        </v-layout>
        <v-layout row wrap style="margin-top: 30px;">
            <v-flex xs12 sm6 md6>
                <div class="nid_front_photo_section" style="padding-right: 15px; margin-bottom: 30px;">
                    <h5 class="text-capitalize"><strong>এন.আই.ডি সামনের ছবি</strong> ({{ documentUploadStatus[serviceProviderDetails.media.nid_front.status] }})</h5>
                    <div v-if="uploadnidFrontPhotoOption">
                        <div v-if="uploadnidFrontPhotoCrop">
                            <ImageCropper :src="uploadnidFrontPhotoOption" @clicked="croppednidFrontPhoto" />
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="success" @click="uploadnidFrontPhotoCrop = false; uploadnidFrontPhotoOption = nidFrontPhotoTemp"><v-icon>check</v-icon></v-btn>
                                    <v-btn color="accent" @click="uploadnidFrontPhotoCrop = false"><v-icon>close</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                        <div v-if="!uploadnidFrontPhotoCrop">
                            <img :src="uploadnidFrontPhotoOption" alt="avatar">
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="primary" @click="uploadnidFrontPhotoCrop = true"><v-icon>crop</v-icon></v-btn>
                                    <v-btn color="secondary" @click="uploadnidFrontPhotoOption = null;"><v-icon>delete</v-icon></v-btn>
                                    <v-btn color="success" @click="serviceProviderDocumentUpload('nidFrontPhoto')"><v-icon>check</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                    </div>
                    <div v-if="!uploadnidFrontPhotoOption">
                        <label class="v-card v-sheet theme--light" for="UploadnidFrontPhoto" style="margin-bottom: 0; display: block; cursor: pointer; min-height: 150px; text-align: center;">
                            <img :src="serviceProviderDetails.media.nid_front.photo_url" alt="avatar">
                            <span style="position: absolute; right: 12px; top: 12px;"><i class="material-icons">photo_camera</i></span>
                        </label>
                        <input style="display: none;" id="UploadnidFrontPhoto" @change="onPhotoFileChange('nidFrontPhoto', $event)" type="file" label="Upload NID Front Photo" />
                    </div>
                </div>
            </v-flex>
            <v-flex xs12 sm6 md6>
                <div class="nid_back_photo_section" style="padding-left: 15px; margin-bottom: 30px;">
                    <h5 class="text-capitalize"><strong>এন.আই.ডি পিছনের ছবি</strong> ({{ documentUploadStatus[serviceProviderDetails.media.nid_back.status] }})</h5>
                    <div v-if="uploadnidBackPhotoOption">
                        <div v-if="uploadnidBackPhotoCrop">
                            <ImageCropper :src="uploadnidBackPhotoOption" @clicked="croppednidBackPhoto" />
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="success" @click="uploadnidBackPhotoCrop = false; uploadnidBackPhotoOption = nidBackPhotoTemp"><v-icon>check</v-icon></v-btn>
                                    <v-btn color="accent" @click="uploadnidBackPhotoCrop = false"><v-icon>close</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                        <div v-if="!uploadnidBackPhotoCrop">
                            <img :src="uploadnidBackPhotoOption" alt="avatar">
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="primary" @click="uploadnidBackPhotoCrop = true"><v-icon>crop</v-icon></v-btn>
                                    <v-btn color="secondary" @click="uploadnidBackPhotoOption = null;"><v-icon>delete</v-icon></v-btn>
                                    <v-btn color="success" @click="serviceProviderDocumentUpload('nidBackPhoto')"><v-icon>check</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                    </div>
                    <div v-if="!uploadnidBackPhotoOption">
                        <label class="v-card v-sheet theme--light" for="UploadnidBackPhoto" style="margin-bottom: 0; display: block; cursor: pointer; min-height: 150px; text-align: center;">
                            <img :src="serviceProviderDetails.media.nid_back.photo_url" alt="avatar">
                            <span style="position: absolute; right: 12px; top: 12px;"><i class="material-icons">photo_camera</i></span>
                        </label>
                        <input style="display: none;" id="UploadnidBackPhoto" @change="onPhotoFileChange('nidBackPhoto', $event)" type="file" label="Upload NID Back Photo" />
                    </div>
                </div>
            </v-flex>
            <v-flex xs12 sm6 md6>
                <div class="tin_certificate_photo_section" style="padding-right: 15px; margin-bottom: 30px;">
                    <h5 class="text-capitalize"><strong>টিন সার্টিফিকেট</strong> ({{ documentUploadStatus[serviceProviderDetails.media.tin_image.status] }})</h5>
                    <div v-if="uploadTinCertificatePhotoOption">
                        <div v-if="uploadTinCertificatePhotoCrop">
                            <ImageCropper :src="uploadTinCertificatePhotoOption" @clicked="croppedTinCertificatePhoto" />
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="success" @click="uploadTinCertificatePhotoCrop = false; uploadTinCertificatePhotoOption = tinCertificatePhotoTemp"><v-icon>check</v-icon></v-btn>
                                    <v-btn color="accent" @click="uploadTinCertificatePhotoCrop = false"><v-icon>close</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                        <div v-if="!uploadTinCertificatePhotoCrop">
                            <img :src="uploadTinCertificatePhotoOption" alt="avatar">
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="primary" @click="uploadTinCertificatePhotoCrop = true"><v-icon>crop</v-icon></v-btn>
                                    <v-btn color="secondary" @click="uploadTinCertificatePhotoOption = null;"><v-icon>delete</v-icon></v-btn>
                                    <v-btn color="success" @click="serviceProviderDocumentUpload('tinCertificatePhoto')"><v-icon>check</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                    </div>
                    <div v-if="!uploadTinCertificatePhotoOption">
                        <label class="v-card v-sheet theme--light" for="UploadTinCertificatePhoto" style="margin-bottom: 0; display: block; cursor: pointer; min-height: 150px; text-align: center;">
                            <img :src="serviceProviderDetails.media.tin_image.photo_url" alt="avatar">
                            <span style="position: absolute; right: 12px; top: 12px;"><i class="material-icons">photo_camera</i></span>
                        </label>
                        <input style="display: none;" id="UploadTinCertificatePhoto" @change="onPhotoFileChange('tinCertificatePhoto', $event)" type="file" label="Upload Trade License Photo" />
                    </div>
                </div>
            </v-flex>
            <v-flex xs12 sm6 md6>
                <div class="trade_license_photo_section" style="padding-left: 15px; margin-bottom: 30px;">
                    <h5 class="text-capitalize"><strong>ট্রেড লাইসেন্স</strong> ({{ documentUploadStatus[serviceProviderDetails.media.trade_lic_image.status] }})</h5>
                    <div v-if="uploadTradeLicesePhotoOption">
                        <div v-if="uploadTradeLicesePhotoCrop">
                            <ImageCropper :src="uploadTradeLicesePhotoOption" @clicked="croppedTradeLicesePhotoPhoto" />
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="success" @click="uploadTradeLicesePhotoCrop = false; uploadTradeLicesePhotoOption = tradeLicesePhotoTemp"><v-icon>check</v-icon></v-btn>
                                    <v-btn color="accent" @click="uploadTradeLicesePhotoCrop = false"><v-icon>close</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                        <div v-if="!uploadTradeLicesePhotoCrop">
                            <img :src="uploadTradeLicesePhotoOption" alt="avatar">
                            <v-card elevation="0">
                                <v-card-actions>
                                    <v-btn color="primary" @click="uploadTradeLicesePhotoCrop = true"><v-icon>crop</v-icon></v-btn>
                                    <v-btn color="secondary" @click="uploadTradeLicesePhotoOption = null;"><v-icon>delete</v-icon></v-btn>
                                    <v-btn color="success" @click="serviceProviderDocumentUpload('tradeLicesePhoto')"><v-icon>check</v-icon></v-btn>
                                </v-card-actions>
                            </v-card>
                        </div>
                    </div>
                    <div v-if="!uploadTradeLicesePhotoOption">
                        <label class="v-card v-sheet theme--light" for="UploadTradeLicesePhoto" style="margin-bottom: 0; display: block; cursor: pointer; min-height: 150px; text-align: center;">
                            <img :src="serviceProviderDetails.media.trade_lic_image.photo_url" alt="avatar">
                            <span style="position: absolute; right: 12px; top: 12px;"><i class="material-icons">photo_camera</i></span>
                        </label>
                        <input style="display: none;" id="UploadTradeLicesePhoto" @change="onPhotoFileChange('tradeLicesePhoto', $event)" type="file" label="Upload Trade License Photo" />
                    </div>
                </div>
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
        name: "ServiceProviderProfile",
        components: {
            ImageCropper
        },
        data() {
            return {
                auth_user : localStorageService.getItem("currentUserData"),
                uploadProfilePhotoCrop: false,
                uploadnidFrontPhotoCrop: false,
                uploadnidBackPhotoCrop: false,
                uploadTradeLicesePhotoCrop: false,
                uploadTinCertificatePhotoCrop: false,
                uploadProfilePhotoOption: null,
                uploadnidFrontPhotoOption: null,
                uploadnidBackPhotoOption: null,
                uploadTradeLicesePhotoOption: null,
                uploadTinCertificatePhotoOption: null,
                profilePhotoTemp: null,
                nidFrontPhotoTemp: null,
                nidBackPhotoTemp: null,
                tradeLicesePhotoTemp: null,
                tinCertificatePhotoTemp: null,
                otherDocuments: null,
                otherDocumentsTemp: null,
                itemsMfs: ["Bkash"],
                serviceProviderDetails: {
                    balance: 0,
                    withdrawable_balance: 0
                },
                profileForm: {
                    name: localStorageService.getItem("currentUserData").name,
                    address: localStorageService.getItem("currentUserData").address,
                    email: localStorageService.getItem("currentUserData").email,
                    phone: localStorageService.getItem("currentUserData").phone,
                    mfs_type: localStorageService.getItem("currentUserData").mfs_type,
                    mfs_no: localStorageService.getItem("currentUserData").mfs_no ,
                },
                documentUploadStatus: {
                    "pending": "অপেক্ষমান রয়েছে",
                    "approve": "অনুমোদিত হয়েছে ",
                    "Not Upload": "আপলোড করা হয়নি"
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
        methods: {
            async updateProfile() {
                if (this.$refs.form.validate()) {
                    this.display_errors = [];
                    try {
                        var response = await axios.post('/profile-update/sp', this.profileForm);
                        var userData = localStorageService.getItem("currentUserData");
                        userData.name = this.profileForm.name;
                        userData.phone = this.profileForm.phone;
                        userData.address = this.profileForm.address;
                        userData.email = this.profileForm.email;
                        userData.mfs_type = this.profileForm.mfs_type;
                        userData.mfs_no = this.profileForm.mfs_no;
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
            onPhotoFileChange(item, e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;
                this.createPhoto(item, files[0]);
            },
            createPhoto(item, file) {
                var image = new Image();
                var reader = new FileReader();

                if (item == 'profilePhoto') {
                    reader.onload = e => {
                        this.uploadProfilePhotoOption = e.target.result;
                    };
                }
                if (item == 'nidFrontPhoto') {
                    reader.onload = e => {
                        this.uploadnidFrontPhotoOption = e.target.result;
                    };
                }
                if (item == 'nidBackPhoto') {
                    reader.onload = e => {
                        this.uploadnidBackPhotoOption = e.target.result;
                    };
                }
                if (item == 'tradeLicesePhoto') {
                    reader.onload = e => {
                        this.uploadTradeLicesePhotoOption = e.target.result;
                    };
                }
                if (item == 'tinCertificatePhoto') {
                    reader.onload = e => {
                        this.uploadTinCertificatePhotoOption = e.target.result;
                    };
                }
                reader.readAsDataURL(file);
            },
            croppedProfilePhoto: function(val) {
                this.profilePhotoTemp = val;
            },
            croppednidFrontPhoto: function(val) {
                this.nidFrontPhotoTemp = val;
            },
            croppednidBackPhoto: function(val) {
                this.nidBackPhotoTemp = val;
            },
            croppedTinCertificatePhoto: function(val) {
                this.tinCertificatePhotoTemp = val;
            },
            croppedTradeLicesePhotoPhoto: function(val) {
                this.tradeLicesePhotoTemp = val;
            },
            async profilePhotoChange() {
                try {
                    let response = await axios.post('/profile-change/sp', {
                        photo: this.uploadProfilePhotoOption,
                    });

                    var userData = localStorageService.getItem("currentUserData");
                    userData.photo = this.uploadProfilePhotoOption;
                    localStorageService.setItem("currentUserData", userData);
                    this.auth_user.photo = this.uploadProfilePhotoOption;
                    this.uploadProfilePhotoOption = null;

                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                } catch (error) {
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            },
            async serviceProviderDocumentUpload(item) {
                if (item == 'nidFrontPhoto') {
                    var photo = this.uploadnidFrontPhotoOption;
                }
                if (item == 'nidBackPhoto') {
                    var photo = this.uploadnidBackPhotoOption;
                }
                if (item == 'tradeLicesePhoto') {
                    var photo = this.uploadTradeLicesePhotoOption;
                }
                if (item == 'tinCertificatePhoto') {
                    var photo = this.uploadTinCertificatePhotoOption;
                }

                try {
                    let response = await axios.post('/service-provider-document-upload', {
                        photo: photo,
                        type: item,
                    });
                    if (item == 'nidFrontPhoto') {
                        this.uploadnidFrontPhotoOption = null;
                    }
                    if (item == 'nidBackPhoto') {
                        this.uploadnidBackPhotoOption = null;
                    }
                    if (item == 'tradeLicesePhoto') {
                        this.uploadTradeLicesePhotoOption = null;
                    }
                    if (item == 'tinCertificatePhoto') {
                        this.uploadTinCertificatePhotoOption = null;
                    }
                    this.getServiceProviderDetails();
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                } catch (error) {
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            },
            async getServiceProviderDetails() {
                let loader = this.$loading.show();
                let response = await axios.get("/service-provider-details");
                this.serviceProviderDetails = response.data.data;
                console.log(this.serviceProviderDetails);
                loader.hide();
            }, 
        },
        watch: {
        },
        computed: {
        },
        created() {
            this.getServiceProviderDetails();
            console.log(localStorageService.getItem("currentUserData").photo);
        }
    };
</script>