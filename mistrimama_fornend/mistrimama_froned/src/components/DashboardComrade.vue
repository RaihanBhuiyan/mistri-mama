<template>
    <v-content>
        <v-toolbar app height="64" dark color="primary">
            <v-toolbar-side-icon @click.stop="secondarySidebar = !secondarySidebar"></v-toolbar-side-icon>
            <v-toolbar-title>{{ this.$route.name }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu bottom left offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn dark icon v-on="on" style="margin-right:10px" to="/comrade-notifications">
                        <v-badge overlap color="red">
                            <template v-slot:badge>
                                <span>{{ notifications.unread_notifications }}</span>
                            </template>
                            <v-icon large color="grey darken-1">notifications_none</v-icon>
                        </v-badge>
                    </v-btn>
                </template>
            </v-menu>
            <v-menu transition="slide-x-transition" offset-y left>
                <template v-slot:activator="{ on }">
                    <img style="border-radius: 100%; border: 1px solid #ddd; height: 40px; width: 40px" v-on="on" :src="(auth_user.photo) ? auth_user.photo : 'https://image.flaticon.com/icons/png/512/236/236832.png'" alt="MM" class="avatar">
                </template>
                <v-card>
                    <v-list>
                        <v-list-tile avatar to="/comrade-profile">
                            <v-list-tile-avatar>
                                <img style="border-radius: 100%; border: 1px solid #ddd; height: 50px; width: 50px" :src="(auth_user.photo) ? auth_user.photo : 'https://image.flaticon.com/icons/png/512/236/236832.png'" alt="MM">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ (auth_user.name) ? auth_user.name : '' }}</v-list-tile-title>
                                <v-list-tile-sub-title>{{ (auth_user.phone) ? auth_user.phone : '' }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                            <v-list-tile-action></v-list-tile-action>
                        </v-list-tile>
                        <v-list-tile>
                            <router-link to="/change-password-comrade">পাসওয়ার্ড পরিবর্তন</router-link>
                        </v-list-tile>
                    </v-list>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" flat @click="logout()"><v-icon>power_settings_new</v-icon>&nbsp;লগ আউট</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-toolbar>
        <v-navigation-drawer v-model="secondarySidebar" temporary fixed dark>
            <!-- <v-toolbar class="transparent">
                <v-list style="text-align: center; background: url('https://img.freepik.com/free-vector/abstract-modern-yellow-background_42581-368.jpg?size=626&ext=jpg'); background-position:center; background-repeat:no-repeat; background-size:cover">
                    <img style="height:50px" src="https://staging.mistrimama.com/backend/public/frontend/logo.png" alt="MM">
                </v-list>
            </v-toolbar> -->

            <v-list color="primary" dark>
                <v-list-tile avatar ripple v-for="item in comradeSidebarItems" :key="item.title" :to="item.link">
                    <v-list-tile-avatar>
                        <v-icon class="amber white--text">{{ item.avatar }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar ripple @click="logout">
                    <v-list-tile-avatar>
                        <v-icon class="amber white--text">power_settings_new</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>লগ আউট</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <router-view></router-view>
    </v-content>
</template>

<script>
    import axios from "../axios_instance.js";
    import { localStorageService } from "../helper.js";
    export default {
        name: "DashboardComrade",
        data: () => ({
            secondarySidebar: null,
            auth_user : localStorageService.getItem("currentUserData"),
            comradeSidebarItems: [{
                title: "চলমান কাজ",
                link: "/comrade-home",
                avatar: "dashboard"
            }, {
                title: "পূর্বের  কাজ",
                link: "/comrade-history",
                avatar: "room_service"
            }],
            notifications: [],
            comradeDetails: []
        }),
        created() {
            this.storeCategorys();
            Echo.channel("orderChannel").listen("AppEventsOrderEvent", res => {
                console.log(res);
            });
        },
        methods: {
            logout() {
                localStorage.removeItem("currentUserData");
                localStorage.removeItem("d_token");
                this.$router.push("/");
            },
            async getComrade() {
                let loader = this.$loading.show();
                try {
                    let response = await axios.get("/comrade-profile");
                    this.comradeDetails = response.data.data;
                } catch (error) {
                    localStorage.removeItem("currentUserData");
                    localStorage.removeItem("d_token");
                    this.$router.push("/");
                }
                loader.hide();
            }, 
            async storeCategorys() {
                var allCategory = await axios.get("/category"); // http://dev.mm/api/category
                localStorageService.setItem("categorys", allCategory.data.data);
            },
            async getNotifications()
            {
                let response = await axios.get("/notifications");
                this.notifications = response.data;
            }
        },
        computed: {
        },
        async mounted() {
        },
        created() {
            this.getComrade();
            Echo.channel("NotificationFrontendEvent").listen("NotificationFrontendEvent", response => {
                this.getNotifications();
            });
            this.getNotifications();
        },
    };
</script>