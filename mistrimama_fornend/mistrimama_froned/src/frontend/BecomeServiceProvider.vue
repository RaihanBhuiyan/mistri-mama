<template>
    <div class="contact-page-section" id="contactus" style="margin-top:100px">
        <div class="auto-container">
            <section class="video-section style-two">
                <div class="outer-box">
                    <div class="row">
                        <div class="content-column col-lg-6 col-md-12 col-sm-12">
                            <div class="inner-column">
                                <div class="sec-title">
                                    <span class="float-text">{{ $tc('bservice_provider',0) }} </span>
                                    <h2>{{ $tc('bservice_provider',1) }} </h2>
                                </div>
                                <span class="title">{{ $tc('bservice_provider',2) }} </span>
                                <div class="text text-justify">{{ $tc('bservice_provider1',0) }} </div> 
                            </div>
                            <div class="inner-column">
                                <div class="text text-justify">{{ $tc('bservice_provider1',1) }} </div> 
                            </div>
                            <div class="inner-column">
                                <span class="title">{{ $tc('bservice_provider1',2) }} </span>
                                <div class="text text-justify">{{ $tc('bservice_provider2',0) }} </div> 
                            </div>
                            <div class="inner-column">
                                <div class="text text-justify"><strong>{{ $tc('bservice_provider2',1) }}</strong></div> 
                            </div>
                        </div>

                        <!-- Video Column -->
                        <div class="form-column order-last col-lg-6 col-md-12 col-sm-12">
                            <div class="inner-column">
                                <div class="discount-form">
                                    <v-form ref="becomeServicceProvider" v-model="valid" lazy-validation @submit.prevent="contactUsStore">
                                        <v-text-field color="accent" v-model="becomeServicceProvider.name" :error-messages="display_errors.name" type="text" v-bind:label="$tc('earn_money8',0)" outlined></v-text-field>
                                        <v-text-field color="accent" v-model="becomeServicceProvider.phone"  prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" v-bind:label="$tc('earn_money8',1)" outlined></v-text-field>
                                        <v-text-field color="accent" v-model="becomeServicceProvider.email" :error-messages="display_errors.email" type="text" v-bind:label="$tc('earn_money9',2)" outlined></v-text-field>
                                        <v-select color="accent" v-model="becomeServicceProvider.area" :items="cluster" :error-messages="display_errors.area" :item-text="'name'" :item-value="'id'" v-bind:label="$tc('earn_money8',2)" append-icon="keyboard_arrow_down" outlined></v-select>
                                        <v-select color="accent" multiple v-model="becomeServicceProvider.service_categoris" :items="categories" :error-messages="display_errors.service_categoris" :item-text="'name'" :item-value="'name'" v-bind:label="$tc('bservice_provider_from',0)" append-icon="keyboard_arrow_down" outlined></v-select>
                                        <v-text-field color="accent" v-model="becomeServicceProvider.other_service" :error-messages="display_errors.other_service" type="text" v-bind:label="$tc('bservice_provider_from',1)" outlined></v-text-field>
                                        <v-btn color="primary" type="submit">{{ $tc('contact_form2',0) }} </v-btn>
                                    </v-form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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
                categories: [],
                valid: null,
                display_errors: [],
                cluster: [],
                becomeServicceProvider: {
                    name: null,
                    phone: null,
                    email: null,
                    area: null,
                    service_categoris: [],
                    other_service: null,
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
            async contactUsStore() {
                if (this.$refs.becomeServicceProvider.validate()) {
                    this.display_errors = [];
                    try {
                        let response = await axios.post("/become_service_provider", this.becomeServicceProvider);
                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                        this.becomeServicceProvider = {
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