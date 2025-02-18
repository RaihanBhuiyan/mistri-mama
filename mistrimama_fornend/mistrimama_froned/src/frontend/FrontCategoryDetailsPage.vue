<template>
    <section class="project-details-section" :style="(category.feature_image == null) ? 'margin-top: 100px' : ''">
        <div class="project-detail" v-if="category.feature_image != null">
            <figure class="image">
                <img style="width: 100%; margin: 0 auto; display: block;" :src="category.feature_image"  alt="MM">
            </figure>
            <div class="auto-container">
                <div class="service-detail">
                    <div class="inner-box">
                        <div class="row no-gutters" style="margin-bottom:15px">
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <h2 style="margin-top:10px">{{ category.feature_bit.name }}</h2>
                                <h4>৳ {{ category.feature_bit.price }}</h4>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 text-right">
                                <v-btn dark color="primary" @click="quicikOrderFunction( category.feature_bit.name+' '+category.feature_bit.service_name+' to '+category.feature_bit.categgory_name)">Order Now</v-btn>
                                <v-btn dark color="secondary" @click.stop href="tel: +8809610222111">
                                    <v-icon dark>call</v-icon>
                                </v-btn>
                            </div>
                        </div>
                        <p>Service brief</p>
                        <div class="text" v-html="category.feature_bit.brief">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr v-if="category.feature_image != null" style="margin-top: 50px;">
        <div class="project-detail">
            <div class="auto-container">
                <!--Lower Content-->
                <div class="lower-content">
                    <div class="content-column">
                        <div class="inner-column">
                            <!-- <div class="sec-title">
                                <span class="float-text">Our Services</span>
                                <h2>Services</h2>
                            </div> -->
                            <!-- <p v-if="$i18n.locale=='en'" class="text-center">{{ category.name }} en </p>
                            <p v-else class="text-center">{{ category.name_bn }} bn</p>
                             -->
                            <h2>
                                <span v-if="$i18n.locale=='en'">{{ category.name }}</span>
                                <span v-else>{{ category.name_bn }}</span>
                            </h2>
                            <p v-if="$i18n.locale=='en'" v-html="category.description"></p>
                            <p velse v-html="category.description_bn"></p> 

                            <h4>{{ $tc('unique_benefits',0) }}</h4>
                            <div  v-if="$i18n.locale=='en'" class="list-style-one" v-html="category.benifits"></div>
                            <div v-else class="list-style-one" v-html="category.benifits_bn"></div>
                            <div style="margin-bottom:30px;" v-for="services in category.services " :key="services.id">
                                <h5>
                                    <span v-if="$i18n.locale=='en'">{{ services.name }}</span>
                                    <span v-else>{{ services.name_bn }} </span>
                                </h5>
                                <div style="overflow:auto">
                                    <span style="background-color: rgb(254, 190, 0); display: inline-block; padding: 5px 15px; border-radius: 10px; margin: 4px 4px 4px 0; cursor: pointer;" v-for="bit in services.serviceBits" :key="bit.id" @click="quicikOrderFunction( bit.name+' '+services.name+' to '+category.name)">
                                        <a href="javaScript:;" style="display:block; color:#fff">
                                        <span v-if="$i18n.locale=='en'">{{ bit.name }} </span>
                                        <span v-else>{{ bit.name_bn }} </span>
                                </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <v-dialog v-model="quicikOrderDialog" max-width="600px">
            <v-card dark style="border-radius: 10px; background: rgba(17, 9, 8, 0.9);">
                <v-card-title>
                    <span class="headline">Quick Order </span>
                </v-card-title>
                <v-card-text>
                    <v-form ref="quickOrderData" v-model="valid" lazy-validation @submit.prevent="palceQuickOrder">
                        <v-layout wrap>
                            <v-flex xs12>
                                <!-- <v-text-field v-model="quickOrderData.request_service" :error-messages="display_errors.request_service" label="Your Service"></v-text-field> -->
                                <v-combobox :allow-overflow="true"  v-model="quickOrderData.request_service" :error-messages="display_errors.request_service" :items="detailsquickorderitems" label="Search for a service"></v-combobox>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field v-model="quickOrderData.name" :error-messages="display_errors.name" color="primary" label="Full Name" ></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field v-model="quickOrderData.phone" prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" label="Phone Number" required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field v-model="quickOrderData.comments" label="Comments/Notes" ></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="quicikOrderDialog = false">Close</v-btn>
                    <v-btn color="primary" @click="palceQuickOrder">Order</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </section>
</template>
<script>
    import axios from "../axios_instance.js";
    import { localStorageService } from "../helper.js";

    export default {
        components: {
        },

        data() {
            return {
                detailsquickorderitems : [],
                valid: null,
                isLoggedIn: false, 
                categories: "",
                isDataLoad: false, 
                category: "",
                services: "",
                quicikOrderDialog: false,
                display_errors: [],
                alertMessage: null,
                snackbar: false,
                quickOrderData: {
                    request_service: "",
                    name: "",
                    phone: "",
                    address: "",
                    comments: "",
                },
            };
        },
        watch: {
            $route(to, from) {
                this.getService();
            }
        },
        methods: {
            async getService() {
                let category = this.$route.params.category;
                if(category != 'about-us')
                {
                    let response = await axios.get('/category/service/' + category);
                    this.category = response.data.data;
                    this.services = this.category.services;
                }
            },
            async quickOrderItems() {
                let quickorderitems = await axios.get("/quickorderitems");
                this.detailsquickorderitems = quickorderitems.data.data;
                 
            },
            quicikOrderFunction(item)
            {
                this.quicikOrderDialog = true; 
                this.quickOrderData.request_service = item
            },
            async palceQuickOrder() {
                if (this.$refs.quickOrderData.validate()) {
                    this.display_errors = [];
                    try {
                        var response = await axios.post("quickorder", {
                            request_service: this.quickOrderData.request_service,
                            name: this.quickOrderData.name,
                            phone: this.quickOrderData.phone,
                            address: this.quickOrderData.address,
                            comments: this.quickOrderData.comments,
                        });
                        this.quicikOrderDialog = false;
                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                        this.quickOrderData = {
                            request_service: "",
                            name: "",
                            phone: "",
                            address: "",
                            comments: "",
                        }
                        this.$router.replace("/confirmed-quick-order");
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
        },

        created() {
            this.getService();
            this.quickOrderItems();
            this.categories = localStorageService.getItem("categories");
        }
    };
</script>

<style scoped>
.v-chip .v-chip__content{
    cursor: pointer;
}
</style>