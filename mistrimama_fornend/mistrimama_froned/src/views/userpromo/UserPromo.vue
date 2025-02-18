<template>
    <v-container fluid style="">
        <v-card elevation="1" max-width="640" class="mx-auto" style="margin-bottom:15px">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>code</v-icon> SAVE PROMO CODE TO GET DISCOUNTS</h5>
            </v-card-title>
            <v-form ref="savePromoCodeForm" v-model="valid" lazy-validation @submit.prevent="savePromoCodePrevent">
                <v-card-text>
                    <v-text-field color="accent" v-model="savePromoCodeFormData.promoCode" :error-messages="display_errors.promo_code" type="text" label="Enter Promo Code here ..."></v-text-field>
                </v-card-text>
                <v-btn type="submit" color="primary">Submit</v-btn>
            </v-form>
        </v-card>
        <v-card elevation="1" max-width="640" class="mx-auto">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>code</v-icon> AVAILABLE PROMOCODE</h5>
            </v-card-title>
            <v-card-text v-if="promocodes.length != 0">
                <v-tooltip bottom v-for="promocode in promocodes" :key="promocode.id" v-if="promocode.is_expired == false">
                    <template v-slot:activator="{ on }">
                        <v-chip v-on="on" label color="red" text-color="white">
                            <v-avatar class="primary" title="Uses Number">{{ parseInt(promocode.promo_code.be_used) - parseInt(promocode.have_used) }}</v-avatar>
                            <strong>{{ promocode.code }}</strong>
                        </v-chip>
                    </template>
                    <span>{{ promocode.promo_code.details }}</span>
                </v-tooltip>
            </v-card-text>
            <v-card-text v-else>NOT AVAILABLE PROMOCODE</v-card-text>
        </v-card>
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
    name: "UserPromo",
    data() {
        return {
            valid: null,
            display_errors: [],
            requiredRules: [
                v => !!v || "This field cannot be empty"
            ],
            savePromoCodeFormData: {
                promoCode: null,
            },
            promocodes: [],
            snackbar: null,
            alertMessage: null,
        };
    },
    created() {
        this.availablePromoCodes();
    },
    methods: {
        async savePromoCodePrevent() {
            let loader = this.$loading.show();
            if (this.$refs.savePromoCodeForm.validate()) {
                this.display_errors = [];
                try {
                    var response = await axios.post('/user-save-promo-codes', {
                        promo_code: this.savePromoCodeFormData.promoCode,
                    });
                    
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.availablePromoCodes();

                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            }
            loader.hide();
        },
        async availablePromoCodes()
        {
            var response = await axios.get('/user-available-promo-codes');
            this.promocodes = response.data;
        }
    },
    watch: {},
    computed: {}
};
</script>