<template>
   <div class="auto-container" style="margin-top:100px">
        <section class="specialize-section-two alternate">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="title-column">
                        <div class="inner-column">
                            <div class="sec-title">
                                <h2>{{ $tc('earn_money',0) }}  </h2>
                            </div>
                            <div class="text-box">
                                <p class="text-justify">{{ $tc('earn_money',1) }}   </p>
                                <p class="text-justify">{{ $tc('earn_money',2) }}  </p>
                            </div>
                        </div>
                    </div>
                    <div class="title-column">
                        <div class="inner-column">
                            <div class="sec-title">
                                <h2>{{ $tc('earn_money1',0) }}  </h2>
                            </div>
                            <div class="text-box">
                                <ul style="margin: 0; padding: 0">
                                    <li>{{ $tc('earn_money1',1) }} </li>
                                    <li>{{ $tc('earn_money1',2) }} </li>
                                    <li>{{ $tc('earn_money2',0) }} </li>
                                    <li>{{ $tc('earn_money2',1) }} </li>
                                    <li>{{ $tc('earn_money2',2) }} </li>
                                    <li>{{ $tc('earn_money3',1) }} </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="title-column">
                        <div class="inner-column">
                            <div class="sec-title">
                                <h2>{{ $tc('earn_money3',2) }} </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="inner-column">
                        <div class="discount-form">
                            <v-form ref="signUpwithMFSnumber" v-model="valid" lazy-validation @submit.prevent="signUpwithMFSnumberStore">
                                <v-text-field color="accent" v-model="signUpwithMFSnumber.name" :error-messages="display_errors.name" type="text" v-bind:label="$tc('earn_money8',0)" outlined></v-text-field>
                                <v-text-field color="accent" v-model="signUpwithMFSnumber.phone"  prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" v-bind:label="$tc('earn_money8',1)" outlined></v-text-field>
                                <v-text-field color="accent" v-model="signUpwithMFSnumber.email" :error-messages="display_errors.email" type="text" v-bind:label="$tc('earn_money9',2)" outlined></v-text-field>
                                <v-select color="accent" v-model="signUpwithMFSnumber.area" :items="cluster" :error-messages="display_errors.area" :item-text="'name'" :item-value="'id'" v-bind:label="$tc('earn_money8',2)" append-icon="keyboard_arrow_down" outlined></v-select>
                                <v-select color="accent" :items="itemsMfs" v-model="signUpwithMFSnumber.mfs_type" :error-messages="display_errors.mfs" v-bind:label="$tc('earn_money9',0)"></v-select>
                                <v-text-field color="accent" v-model="signUpwithMFSnumber.mfs_no"  prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" v-bind:label="$tc('earn_money9',1)"  outlined></v-text-field>
                                <v-btn color="primary" type="submit">{{ $tc('contact_form2',0) }}</v-btn>
                            </v-form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="services-section">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="services-box">
                        <div class="service-block" style="padding:0">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image">
                                        <img style="width: 100%" class="img-responsive" src="https://mistrimama.com/backend/public/frontend/image/refer_img/01.jpg" alt="MM">
                                    </figure>
                                </div>
                                <div class="lower-content">
                                    <h3> {{ $tc('earn_money4',0) }} </h3>
                                    <div class="text">{{ $tc('earn_money4',1) }} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="services-box">
                        <div class="service-block" style="padding:0">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image">
                                        <img style="width: 100%" class="img-responsive" src="https://mistrimama.com/backend/public/frontend/image/refer_img/02.jpg" alt="MM">
                                    </figure>
                                </div>
                                <div class="lower-content">
                                    <h3>{{ $tc('earn_money4',2) }} </h3>
                                    <div class="text">{{ $tc('earn_money5',0) }} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="services-box">
                        <div class="service-block" style="padding:0">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image">
                                        <img style="width: 100%" class="img-responsive" src="https://mistrimama.com/backend/public/frontend/image/refer_img/03.jpg" alt="MM">
                                    </figure>
                                </div>
                                <div class="lower-content">
                                    <h3>{{ $tc('earn_money5',1) }} </h3>
                                    <div class="text">{{ $tc('earn_money5',2) }} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="services-box">
                        <div class="service-block" style="padding:0">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image">
                                        <img style="width: 100%" class="img-responsive" src="https://mistrimama.com/backend/public/frontend/image/refer_img/04.jpg" alt="MM">
                                    </figure>
                                </div>
                                <div class="lower-content">
                                    <h3>{{ $tc('earn_money6',0) }} </h3>
                                    <div class="text">{{ $tc('earn_money6',1) }} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="specialize-section-two alternate" style="padding-left: 15px; padding-right: 15px;">
            <div class="title-column">
                <div class="inner-column">
                    <div class="sec-title">
                        <h2>{{ $tc('earn_money6',2) }} </h2>
                    </div>
                    <div class="text-box">
                        <p class="text-justify">{{ $tc('earn_money7',0) }}  </p>
                    </div>
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
                categories: [],
                valid: null,
                display_errors: [],
                cluster: [],
                itemsMfs: ["Bkash"],
                signUpwithMFSnumber: {
                    name: null,
                    phone: null,
                    email: null,
                    area: null,
                    mfs_type: "Bkash",
                    mfs_no: null,
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
                alertMessage: null,
                snackbar: false,
            };
        },
        created() { 
            this.getLocations();
            this.storeCategories();
        },
        computed: {
        },
        methods: {
            async getLocations()
            {
                let response = await axios.get("division");
                this.cluster = response.data.data[0].cluster;
            },
            async storeCategories() {
                if (!localStorageService.getItem("categorys")) {
                    let categories = await axios.get("/category");
                    localStorageService.setItem("categorys", categories.data.data);
                    this.categories = categories.data.data;
                } else {
                    this.categories = localStorageService.getItem("categorys");
                }
            },
            async signUpwithMFSnumberStore() {
                if (this.$refs.signUpwithMFSnumber.validate()) {
                    this.display_errors = [];
                    try {
                        let response = await axios.post("/sign-up-with-mfs-number", this.signUpwithMFSnumber);
                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                        this.signUpwithMFSnumber = {
                            name: null,
                            phone: null,
                            email: null,
                            area: null,
                            service_categoris: [],
                            other_service: null,
                        };
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
        }
    };
</script>