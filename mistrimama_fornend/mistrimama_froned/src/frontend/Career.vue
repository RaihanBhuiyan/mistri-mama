<template>
    <div class="auto-container" style="margin-top:100px">
        <section class="specialize-section-two alternate">
            <div class="service-detail">
                <div class="inner-box">
                    <div class="text text-center">
                        <strong>{{ $tc('career',0) }} </strong>
                    </div>
                </div>
            </div>
        </section>
        <hr>
        <section class="specialize-section-two alternate">
            <div class="title-column">
                <div class="inner-column text-center">
                    <div class="sec-title">
                        <h2>{{ $tc('career',1) }} </h2>
                    </div>
                    <div class="text-box">
                        <p class="text-center">{{ $tc('career',2) }} </p>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="help-box-two">
                    <div class="inner">
                        <h2>0</h2>
                        <div class="text">{{ $tc('career1',0) }} </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="help-box-two">
                    <div class="inner">
                        <h2>0</h2>
                        <div class="text">{{ $tc('career1',1) }} </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="help-box-two">
                    <div class="inner">
                        <h2>0</h2>
                        <div class="text">{{ $tc('career1',2) }} </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="help-box-two">
                    <div class="inner">
                        <h2>0</h2>
                        <div class="text">{{ $tc('career2',0) }} </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="help-box-two">
                    <div class="inner">
                        <h2>0</h2>
                        <div class="text">{{ $tc('career2',1) }}  </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="help-box-two">
                    <div class="inner">
                        <h2>0</h2>
                        <div class="text">{{ $tc('career2',2) }}  </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="specialize-section-two alternate">
            <div class="title-column">
                <div class="inner-column text-center">
                    <div class="sec-title">
                        <h2>{{ $tc('career3',0) }} </h2>
                    </div>
                    <v-form ref="careerForm" v-model="valid" lazy-validation @submit.prevent="careerStore">
                        <v-card elevation="0" style="background-color: transparent">
                            <v-card-text>
                                <v-layout row wrap>
                                    <v-flex md6>
                                        <v-text-field  color="accent" v-model="careerForm.name" :error-messages="display_errors.name" type="text" v-bind:label="$tc('career_form',0)" outlined></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field color="accent" prefix="+88" placeholder="01XXXXXXXXX" v-model="careerForm.phone_number" :error-messages="display_errors.phone_number" type="number" v-bind:label="$tc('career_form',1)" outlined></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex md6>
                                        <v-text-field color="accent" v-model="careerForm.email" :error-messages="display_errors.email" type="text" v-bind:label="$tc('career_form',2)" outlined></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field color="accent" v-model="careerForm.year_of_experience" :error-messages="display_errors.year_of_experience" type="text" v-bind:label="$tc('career_form1',0)" outlined></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex md4>
                                        <v-text-field color="accent" v-model="careerForm.position" :error-messages="display_errors.position" type="text" v-bind:label="$tc('career_form1',1)" outlined></v-text-field>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-text-field color="accent" v-model="careerForm.salary_expectation" :error-messages="display_errors.salary_expectation" type="number" v-bind:label="$tc('career_form1',2)" outlined></v-text-field>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-text-field color="accent" v-model="careerForm.link" :error-messages="display_errors.link" type="text" v-bind:label="$tc('career_form2',0)" outlined></v-text-field>
                                    </v-flex>
                                </v-layout>

                                <v-text-field color="accent" v-bind:label="$tc('career_form2',1)" @click='onButtonClick' :error-messages="display_errors.file" v-model='buttonText' prepend-icon='attach_file' outlined></v-text-field>
                                <input ref="uploader" class="d-none" type="file" accept=".doc,.docx,.pdf" @change="onFileChanged">

                                <v-textarea style="margin-top:15px" color="accent" v-model="careerForm.comments" :error-messages="display_errors.comments" v-bind:label="$tc('career_form3',0)" v-bind:placeholder="$tc('career_form3',1)" ></v-textarea>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="primary" type="submit">{{ $tc('career_form3',2) }}</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </div>
            </div>
        </section>
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
                otpCode: null,
                otp: false,
                page: "",
                pageBanner: "",
                display_errors: [],
                cluster: [],
                defaultButtonText: '',
                selectedFile: null,
                isSelecting: false,
                careerForm: {
                    name: "",
                    phone_number: "",
                    email: "",
                    year_of_experience: "",
                    salary_expectation: "",
                    link: "",
                    comments: "",
                    file: "",
                    position: ""
                   
                },
                // nameRules: [
                //     v => !!v || "This field cannot be empty",
                //     v => (v && v.length <= 20) || "Name must be less than 20 characters"
                // ],
                //  phoneRules: [
                //     v => !!v || "This field cannot be empty", 
                //     v => v.match(/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/)|| "Invalid mobile number (+8801XXXXXXXXX)",
                   
                // ],
                // emailRules: [
                //     v => /.+@.+/.test(v) || 'E-mail must be valid'
                // ],
                
                passShow: null,
                alertMessage: null,
                snackbar: false,
            };
        },
        created() { 
        },
        computed: {
            buttonText() {
                return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
            }
        },
        methods: {
            onButtonClick() {
                this.isSelecting = true
                window.addEventListener('focus', () => {
                    this.isSelecting = false
                }, { once: true })

                this.$refs.uploader.click()
            },
            onFileChanged(e) {
                this.selectedFile = e.target.files[0];
                this.careerForm.file = this.selectedFile;
            },
            async careerStore() {
                if (this.$refs.careerForm.validate()) {
                    this.display_errors = [];
                    try {
                        const formData = new FormData();
                        formData.append('file', this.selectedFile);
                        formData.append('name', this.careerForm.name);
                        formData.append('phone_number', this.careerForm.phone_number);
                        formData.append('email', this.careerForm.email);
                        formData.append('year_of_experience', this.careerForm.year_of_experience);
                        formData.append('salary_expectation', this.careerForm.salary_expectation);
                        formData.append('link', this.careerForm.link);
                        formData.append('comments', this.careerForm.comments);
                        formData.append('position', this.careerForm.position);

                        let response = await axios.post("/careers", formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }); 
                        
                        this.careerForm = {
                            name: "",
                            phone_number: "",
                            email: "",
                            year_of_experience: "",
                            salary_expectation: "",
                            link: "",
                            comments: "",
                            file: "",
                            position: ""
                        
                        },
                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                    } catch (error) {
                        console.log(error);
                        if(error.response.data.errors)
                        {
                            this.display_errors = error.response.data.errors;
                        }
                        this.alertMessage = error.response.data.message;
                        this.snackbar = true;
                    }
                }
            },
           
         
        }
    };
</script>