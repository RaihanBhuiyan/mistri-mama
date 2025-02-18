<template>
    <v-container fluid style="">
        <v-card elevation="0">
            <v-layout wrap style="text-align: center" v-if="serviceProviderDetails.withdrawable_balance != 0">
                <v-flex md2 sm4 xs6 v-for="category in categories" :key="category.id" :to="'/ownservice/'+category.slug">
                    <router-link :to="{ path: '/ownservice/' + category.slug }" style="display:block; padding: 5px;">
                        <img style="" :src="category.thumb" :alt="category.slug" class="" />
                        
                        <p class="text-center"> 
                            <span v-if="$i18n.locale=='en'">{{category.name}}</span>
                            <span v-else>{{category.name_bn}}</span> 
                        </p>
                    </router-link>
                </v-flex>
            </v-layout>
            <v-card-text v-else class="text-center">কাজ পেতে প্রথমে আপনার অ্যাকাউন্টটি রিচার্জ করুন</v-card-text>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
     import { mapState } from "vuex";
    import axios from "../../axios_instance.js";
    import { localStorageService } from "../../helper";

    export default {
        mounted() { 
            this.getCatgory();
        },
        data() {
            return { 
                categories: [], 
                snackbar: null,
                alertMessage: null,
                auth_user: [], 
                display_errors: [], 
                serviceProviderDetails: {
                    balance: 0,
                    withdrawable_balance: 0
                },
            };
        },
        methods: { 
            async getCatgory() {
                this.categories = localStorageService.getItem("categorys");
                console.log(this.categories);
            },
            async getServiceProviderDetails() {
                let loader = this.$loading.show();
                let response = await axios.get("/service-provider-details");
                this.serviceProviderDetails = response.data.data;
                loader.hide();
            }, 
             
        },
        watch: { 
        },
        computed: { 
        },
        created() { 
            this.getCatgory();
            this.getServiceProviderDetails();
        }
    };
</script>