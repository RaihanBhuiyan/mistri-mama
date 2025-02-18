<template>
    <section class="faq-section"> 
        <div class="auto-container">
            <div class="accordion-column">
                <div class="inner-column">
                    <div class="sec-title">
                        <span class="float-text">some FAQ’s</span>
                        <h2>Frequality Asked Questions</h2>
                    </div>
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
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import { mapState } from "vuex";
import axios from "../axios_instance";

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
            let response = await axios.get("/jiggasha/common");
            this.jiggasha = response.data;
            loader.hide();
        }
    },
    created() {
        this.getJiggasha();
    }
};
</script>