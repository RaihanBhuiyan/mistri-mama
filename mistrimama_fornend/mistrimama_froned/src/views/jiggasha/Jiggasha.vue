<template>
    <v-container fluid style="">
        <v-expansion-panel class="accordion-box" style="padding:0; box-shadow:none">
            <v-expansion-panel-content v-for="(faq,i) in jiggasha" :key="i">
            <template v-slot:header>
                <div class="subheading">{{ faq.title }}</div>
            </template>
            <v-card>
                <v-card-text class="grey lighten-3">{{ faq.discription }}</v-card-text>
            </v-card>
            </v-expansion-panel-content>
        </v-expansion-panel>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
import { mapState } from "vuex";
import axios from "../../axios_instance";

export default {
    data() {
        return {
            snackbar: null,
            alertMessage: null,
            jiggasha: []
        };
    },
    methods: {
        async getJiggasha() {
            let loader = this.$loading.show();
            let response = await axios.get("/jiggasha/esp");
            this.jiggasha = response.data;
            loader.hide();
        }
    },
    created() {
        this.getJiggasha();
    }
};
</script>