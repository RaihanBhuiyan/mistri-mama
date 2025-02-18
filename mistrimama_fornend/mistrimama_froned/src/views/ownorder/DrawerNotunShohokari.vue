<template>
    <v-dialog v-model="drawer" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="drawer = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>নতুন সহকারী যোগ</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click="addComrade">Save</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-container grid-list-xl fluid>
                <v-form lazy-validation @submit.prevent>
                    <v-layout grid-list-xl wrap>
                        <v-flex md6>
                            <v-text-field color="accent" v-model="fullName" label="সম্পূর্ণ নাম" placeholder="Radif Chowdhury" :error-messages="display_errors.name"></v-text-field>
                        </v-flex>
                        <v-flex md6>
                            <v-text-field color="accent" clearable v-model="phoneNumber" prefix="+88" label="ফোন" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex md6>
                            <v-text-field color="accent" clearable v-model="email"  autocomplete="off" label="ই-মেইল" placeholder="youremail@gmail.com" :error-messages="display_errors.email"></v-text-field>
                        </v-flex>
                        <v-flex md6>
                            <v-text-field color="accent" clearable type="password"  autocomplete="off" v-model="password" label="পাসওয়ার্ড" placeholder="Enter your comrade password"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex md6>
                            <v-select color="accent" item-text="name" item-value="id" :items="dialogitemsService" v-model="category" label="ক্যাটাগরি" :menu-props="{ maxHeight: '400' }" multiple persistent-hint></v-select>
                        </v-flex>
                        <v-flex md6>
                            <v-text-field color="accent" clearable v-model="nidNumber" label="এন.আই.ডি নং #" placeholder="Type your nid no" :error-messages="display_errors.nid_no"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex md4>
                            <p class="">ছবি</p>
                            <input @change="onFileChange('personalImage', $event)" type="file" label="File input" />
                            <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                                <img v-if="personalImage" class="rect-image" :src="personalImage" alt="nid_front" />
                            </v-avatar>
                        </v-flex>
                        <v-flex md4>
                            <p class="">এন.আই.ডি সামনের ছবি</p>
                            <input type="file" label="File input" @change="onFileChange('nidImageFront', $event)" />
                            <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                                <img v-if="nidImageFront" class="rect-image" :src="nidImageFront" alt="nid_front" />
                            </v-avatar>
                        </v-flex>
                        <v-flex md4>
                            <p class="">এন.আই.ডি পিছনের ছবি</p>
                            <input type="file" label="File input" @change="onFileChange('nidImageBack', $event)" />
                            <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                                <img v-if="nidImageBack" class="rect-image" :src="nidImageBack" alt="nid_front" />
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
        name: "SaveComrade",
        props: {
            dialogitemsService: Array,
            value: Boolean
        },
        data() {
            return {
                length: 0,
                personalImage: null,
                nidImageFront: null,
                nidImageBack: null,
                fullName: null,
                phoneNumber: null,
                email: null,
                password: null,
                nidNumber: null,
                category: null,
                alertMessage: null,
                snackbar: false,
                display_errors: [],
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
                                this.personalImage = e.target.result;
                                break;
                            case "nidImageFront":
                                this.nidImageFront = e.target.result;
                                break;
                            case "nidImageBack":
                                this.nidImageBack = e.target.result;
                                break;
                            default:
                                break;
                        }
                    };
                    reader.readAsDataURL(file);
                },
                async addComrade() {
                    let loader = this.$loading.show();
                     try {
                      
                        let response = await axios.post('/comrade', {
                            name: this.fullName,
                            phone: this.phoneNumber,
                            email: this.email,
                            password: this.password,
                            address: "--",
                            nid_no: this.nidNumber,
                            photo: this.personalImage,
                            nid_front: this.nidImageFront,
                            nid_back: this.nidImageBack,
                            services: this.category
                        }).then(res => {
                            loader.hide();
                            console.log(res); 
                            this.alertMessage = 'Comrade create successfully'; 
                            this.$toasted.show('Comrade create successfully')
                            this.drawer = false;   
                            this.snackbar = true; 
                            //this.$refs.form.reset();
                            //this.$router.push("/shohokari"); 
                        });
                        // this.alertMessage = response.data.message;
                        // this.snackbar = true;
                      
                    } catch (error) {
                        {
                            this.display_errors = error.response.data.errors;
                        }
                        loader.hide();
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