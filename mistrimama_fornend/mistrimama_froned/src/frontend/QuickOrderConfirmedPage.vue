<template>
    <div class="order-page-fixed-background" style="padding-top: 104px; height: 500px;">
        <v-container style="padding-top:30px; padding-bottom:30px; max-width: 900px;">
            <v-card style="max-width: 480px; margin:0 auto;">
                <v-card-title class="title">Thank you for your order</v-card-title>
                <v-card-title class="heading">We will placed your order quickly.</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="gotoAnotherPage()">Ok</v-btn>
                </v-card-actions>
            </v-card>
        </v-container>
    </div>
</template>
    
<script>
import axios from "../axios_instance.js";
import { localStorageService, Helper } from "../helper.js";
export default {
    data() {
        return {
            thankyouDialog: true,
        };
    },
    created() {
    },
    methods: {
        gotoAnotherPage()
        {
            this.$router.replace("/");
        }
    }
}
</script>