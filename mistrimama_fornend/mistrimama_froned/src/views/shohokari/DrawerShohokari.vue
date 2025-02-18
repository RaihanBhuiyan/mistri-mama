<template>
    <v-dialog v-model="drawer" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="drawer = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>বিস্তারিত</v-toolbar-title>
            </v-toolbar>
            <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                <img v-if="dialogData.photo_url == ''" class="rect-image" src="https://image.flaticon.com/icons/png/512/236/236832.png" alt="user_image" />
                <img v-if="dialogData.photo_url != ''" class="rect-image" :src="dialogData.photo_url" alt="user_image" />
            </v-avatar>
            <v-list>
                <v-list-tile>
                    <v-list-tile-content>
                        <v-list-tile-sub-title>নাম:</v-list-tile-sub-title>
                        <v-list-tile-title>{{dialogData.name}}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile>
                    <v-list-tile-content>
                        <v-list-tile-sub-title>ফোন নম্বর:</v-list-tile-sub-title>
                        <v-list-tile-title>{{dialogData.phone}}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile>
                    <v-list-tile-content>
                        <v-list-tile-sub-title>অতিরিক্ত ফোন নম্বর:</v-list-tile-sub-title>
                        <v-list-tile-title>{{dialogData.alt_phone}}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile>
                    <v-list-tile-content>
                        <v-list-tile-sub-title>ই-মেইল:</v-list-tile-sub-title>
                        <v-list-tile-title>{{dialogData.email}}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile>
                    <v-list-tile-content>
                        <v-list-tile-sub-title>এন.আই.ডি নং:</v-list-tile-sub-title>
                        <v-list-tile-title>{{dialogData.nid_no}}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile>
                    <v-list-tile-content>
                        <v-list-tile-sub-title>ঠিকানা</v-list-tile-sub-title>
                        <v-list-tile-title>{{dialogData.address}}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
            <v-layout class="text-center">
                <v-flex md6>
                    <p class="">এন.আই.ডি সামনের ছবি</p>
                    <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                        <img v-if="dialogData.nid_front_url == ''" class="rect-image" src="https://image.flaticon.com/icons/png/512/236/236832.png" alt="user_image" />
                        <img v-if="dialogData.nid_front_url != ''" class="rect-image" :src="dialogData.nid_front_url" alt="nid_front" />
                    </v-avatar>
                </v-flex>
                <v-flex md6>
                    <p class="">এন.আই.ডি পিছনের ছবি</p>
                    <v-avatar :size="170" color="grey lighten-4" style="margin: 15px auto; display:block">
                        <img v-if="dialogData.nid_back_url == ''" class="rect-image" src="https://image.flaticon.com/icons/png/512/236/236832.png" alt="user_image" />
                        <img v-if="dialogData.nid_back_url != ''" class="rect-image" :src="dialogData.nid_back_url" alt="nid_front" />
                    </v-avatar>
                </v-flex>
            </v-layout>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Shohokari",
        props: {
            dialogData: Array,
            value: Boolean
        },
        data() {
            return {
                length: 0,
            };
        },
        computed: {
            drawer: {
                get() {
                    return this.value
                },
                set(value) {
                    this.$emit('input', value)
                }
            }
        },
        data() {
            return {
                desserts: []
            };
        },
    };
</script>