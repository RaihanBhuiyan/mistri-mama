<template>
    <v-container grid-list-md fluid style="">
        <v-layout v-if="offers.length != 0" row wrap style="margin-left: -5px; margin-right: -5px;">
            <v-flex md4 sm12 xs12 v-for="offer in offers" :key="offer.id">
                <v-card style="margin-bottom:15px" color="primary">
                    <v-img :src="offer.offer_image" aspect-ratio="1.9"></v-img>
                    <v-card-title>
                        <h5><v-icon>tag</v-icon>{{ offer.title }}</h5>
                    </v-card-title>
                    <v-card-text class="headline font-weight-bold truncat">{{ offer.description }}</v-card-text>
                    <v-card-text>Expire Date {{ offer.expire_date }}</v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
        <div v-else>
            <v-card color="error">
                <v-card-text class="headline text-center">Currently no offer is running</v-card-text>
            </v-card>
        </div>
    </v-container>
</template>
<script>
import axios from "../../axios_instance.js";
export default {
    name: "SPOffers",
    data() {
        return {
            offers: []
        };
    },
    created() {
        this.getOffers();
    },
    methods: {
        async getOffers()
        {
            let response = await axios.get("offers");
            this.offers = response.data;
        },
        toggle (index) {

        }
    }
};
</script>
<style type="text/css">
.truncat{
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    display: -webkit-box;
    height: 86px;
    overflow: hidden;
}
</style>