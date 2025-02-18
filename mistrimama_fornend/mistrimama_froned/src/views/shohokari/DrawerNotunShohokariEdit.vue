<template>
    <v-dialog v-model="drawer" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="drawer = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>সহকারী পরিবর্তন</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click="updateComrade(dialogData.user_id)">Update</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-container grid-list-xl fluid>
                <v-form lazy-validation @submit.prevent>
                    <v-layout grid-list-xl wrap>
                        <v-flex md6>
                            <v-text-field color="accent" v-model="dialogData.name" label="সম্পূর্ণ নাম *" placeholder="Radif Chowdhury" :error-messages="display_errors.name"></v-text-field>
                        </v-flex>
                        <v-flex md6>
                            <v-text-field color="accent" clearable v-model="dialogData.phone" prefix="+88" label="ফোন *" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex md4>
                            <v-text-field color="accent" clearable v-model="dialogData.email" label="ই-মেইল" placeholder="youremail@gmail.com" :error-messages="display_errors.email"></v-text-field>
                        </v-flex>
                        <v-flex md4>
                            <v-text-field color="accent" clearable v-model="dialogData.alt_phone" prefix="+88" label="অতিরিক্ত ফোন *" placeholder="01XXXXXXXXX" :error-messages="display_errors.alt_phone"></v-text-field>
                        </v-flex>
                        <v-flex md4>
                            <v-text-field color="accent" clearable type="password" v-model="dialogData.password" :error-messages="display_errors.password" label="পাসওয়ার্ড" placeholder="Enter your comrade password"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-flex md12>
                        <v-select color="accent" item-text="name" item-value="id" v-model="dialogData.services" :items="dialogitemsService" label="ক্যাটাগরি *" :menu-props="{ maxHeight: '400' }" multiple persistent-hint></v-select>
                    </v-flex>
                    <v-flex md12>
                        <v-text-field color="accent" clearable v-model="dialogData.nid_no" label="এন.আই.ডি নং # *" placeholder="Type your nid no" :error-messages="display_errors.nid_no"></v-text-field>
                    </v-flex>
                    <v-layout class="text-center">
                        <v-flex md4>
                            <p class="">ছবি</p>
                            <input @change="onFileChange('personalImage', $event)" type="file" label="File input" style="overflow: hidden;" />
                            <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                                <img v-if="dialogData.photo_url == ''" class="rect-image" src="https://image.flaticon.com/icons/png/512/236/236832.png" alt="user_image" />
                                <img v-if="dialogData.photo_url != ''" class="rect-image" :src="dialogData.photo_url" alt="nid_front" />
                            </v-avatar>
                        </v-flex>
                        <v-flex md4>
                            <p class="">এন.আই.ডি সামনের ছবি</p>
                            <input type="file" label="File input" @change="onFileChange('nidImageFront', $event)" style="overflow: hidden;" />
                            <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                                <img v-if="dialogData.nid_front_url == ''" class="rect-image" src="https://image.flaticon.com/icons/png/512/236/236832.png" alt="user_image" />
                                <img v-if="dialogData.nid_front_url != ''" class="rect-image" :src="dialogData.nid_front_url" alt="nid_front" />
                            </v-avatar>
                        </v-flex>
                        <v-flex md4>
                            <p class="">এন.আই.ডি পিছনের ছবি</p>
                            <input type="file" label="File input" @change="onFileChange('nidImageBack', $event)" style="overflow: hidden;" />
                            <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                                <img v-if="dialogData.nid_back_url == ''" class="rect-image" src="https://image.flaticon.com/icons/png/512/236/236832.png" alt="user_image" />
                                <img v-if="dialogData.nid_back_url != ''" class="rect-image" :src="dialogData.nid_back_url" alt="nid_front" />
                            </v-avatar>
                        </v-flex>
                    </v-layout>
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
    import { localStorageService } from "../../helper.js";
    import axios from "../../axios_instance.js";

    export default {
        name: "UpdateComrade",
        props: {
            dialogData: Array,
            dialogitemsService: Array,
            value: Boolean
        },
        data() {
            return {
                length: 0,
                personalImage: null,
                nidImageFront: null,
                nidImageBack: null,
                alertMessage: null,
                snackbar: false,
                display_errors: [],
                itemsService: []
            };
        },
        methods: {
            onFileChange(item, e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;
                this.createImage(item, files[0]);
            },
            createImage(item, file) {
                var image = new Image();
                var reader = new FileReader();

                reader.onload = e => {
                    switch (item) {
                        case "personalImage":
                            this.dialogData.photo_url = e.target.result;
                            this.personalImage = e.target.result;
                            break;
                        case "nidImageFront":
                            this.dialogData.nid_front_url = e.target.result;
                            this.nidImageFront = e.target.result;
                            break;
                        case "nidImageBack":
                            this.dialogData.nid_back_url = e.target.result;
                            this.nidImageBack = e.target.result;
                            break;
                        default:
                            break;
                    }
                };
                reader.readAsDataURL(file);
            },
            async updateComrade(id) {
                try {
                    let response = await axios.put('/comrade/'+id, {
                        name: this.dialogData.name,
                        phone: this.dialogData.phone,
                        alt_phone: this.dialogData.alt_phone,
                        email: this.dialogData.email,
                        password: this.dialogData.password,
                        nid_no: this.dialogData.nid_no,
                        photo: this.personalImage,
                        nid_front: this.nidImageFront,
                        nid_back: this.nidImageBack,
                        services: this.dialogData.services
                    });
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                } catch (error) {
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            },
            reset() {
                this.$refs.form.reset();
            },
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
    .img-display {
        padding: 15px;
    }
</style>