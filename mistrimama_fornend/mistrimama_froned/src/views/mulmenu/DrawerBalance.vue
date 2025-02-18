<template>
    <v-dialog v-model="drawer" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="drawer = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>ক্যাশ আউট করুন  </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click="cashOutRequest()">Submit</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-container fluid>
                <v-form lazy-validation @submit.prevent>
                    <v-text-field color="accent" clearable v-model="amount" :error-messages="display_errors.amount" label="Amount *" placeholder="Enter an amount"></v-text-field>
                    <v-select color="accent" :items="itemsMfs" v-model="mfs" :error-messages="display_errors.mfs" label="Select Service *"></v-select>
                    <v-text-field color="accent" clearable v-model="mfsNumber" :error-messages="display_errors.mfs_number" label="MFS Number (Bkash) *" placeholder="Enter MFS No."></v-text-field>
                    <v-text-field color="accent" clearable type="password" v-model="confirmPassword" label="Confirm Password *" placeholder="Please enter your password"></v-text-field>
                </v-form>
            </v-container>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
  </v-dialog>
</template>

<script>
import axios from "../../axios_instance";
export default {
    name: "CashoutRequest",
    props: {
        value: Boolean
    },
    data() {
        return {
            amount: null,
            mfs: null,
            mfsNumber: null,
            confirmPassword: null,
            itemsMfs: ["Bkash"],
            alertMessage: "",
            snackbar: null,
            display_errors: [],
        };
    },

    methods: {
        async cashOutRequest() {
            let loader = this.$loading.show();
            this.display_errors = [];
            try {
                let response = await axios.post("cashout-request", {
                    amount: this.amount,
                    mfs: this.mfs,
                    mfs_number: this.mfsNumber,
                    password: this.confirmPassword
                });
                this.alertMessage = response.data.message;
                this.snackbar = true;
                this.drawer = false ;
                //this.$router.replace('/')
                this.$router.replace("/shokolkaaj");
            } catch (error) {
                if(error.response.data.errors){
                    this.display_errors = error.response.data.errors;
                }
                this.alertMessage = error.response.data.message;
                this.snackbar = true;
            }
            loader.hide();
        }
    },
    computed: {
        drawer: {
            get () {
            return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    }
};
</script>

<style  scoped>
.v-toolbar {
  background-color: #febe00;
}
.v-btn {
  color: var(--secondary);
}
.v-avatar {
  background-color: var(--accent);
}
.card-custom:hover {
  padding: 5px;
  cursor: pointer;
}
.v-icon:hover {
  color: var(--accent) !important;
}
.custom-table-cell {
  cursor: pointer;
}
.custom-table-cell:hover {
  text-decoration: underline;
  color: #febe00;
}

</style>
