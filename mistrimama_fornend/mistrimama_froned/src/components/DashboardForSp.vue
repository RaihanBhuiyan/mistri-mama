
<template>
    <v-content>
        <v-toolbar app height="64" dark color="primary">
            <v-toolbar-side-icon @click.stop="secondarySidebar = !secondarySidebar"></v-toolbar-side-icon>
            <v-toolbar-title>{{ this.$route.name }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                <!-- <span class="" style="background-color: red; border: 1px solid blue;">
                    <select v-model="$i18n.locale"> 
                      <option v-for="(lang, i) in langs" :key="`lang${i}`" :value="lang">{{ lang }}</option>
                    </select> 
                </span> -->  
                <v-btn flat to="/mulmenu"> মূল মেনু</v-btn>
                <v-btn flat to="/shokolkaaj">সকল কাজ</v-btn>
                <v-btn v-if="auth_user.type == 'esp'" flat to="/shohokari">সহকারী</v-btn>
                <v-btn flat to="/shebashomuho">সেবা সমূহ</v-btn>
                <v-btn flat to="/ayerbiboroni">আয়ের বিবরণী</v-btn>
            </v-toolbar-items>
            <v-menu transition="slide-x-transition" offset-y left>
                <template v-slot:activator="{ on }">
                    <v-btn dark icon v-on="on" @click="marklAsReadNotifications()" style="margin-right:10px" to="/sp-notifications">
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
                        <v-list-tile avatar to="/spprofile">
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
                            <router-link to="/change-password-sp">পাসওয়ার্ড পরিবর্তন</router-link>
                        </v-list-tile>
                    </v-list>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" flat @click="logout()"><v-icon>power_settings_new</v-icon>&nbsp;লগ আউট</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
            <v-menu bottom left offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn dark icon v-on="on" class="hidden-md-and-up">
                        <v-icon>more_vert</v-icon>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-tile to="/mulmenu">
                        <v-list-tile-title>মূল মেনু</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile to="/shokolkaaj">
                        <v-list-tile-title>সকল কাজ</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile v-if="auth_user.type == 'esp'" to="/shohokari">
                        <v-list-tile-title>সহকারী</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile to="/shebashomuho">
                        <v-list-tile-title>সেবা সমূহ</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile to="/ayerbiboroni">
                        <v-list-tile-title>আয়ের বিবরণী</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar>
        <v-navigation-drawer v-model="secondarySidebar" temporary fixed dark>
            <!-- <v-toolbar class="transparent">
                <v-list style="text-align: center; background: url('https://img.freepik.com/free-vector/abstract-modern-yellow-background_42581-368.jpg?size=626&ext=jpg'); background-position:center; background-repeat:no-repeat; background-size:cover">
                    <img style="height:50px" src="https://staging.mistrimama.com/backend/public/frontend/logo.png" alt="MM">
                </v-list>
            </v-toolbar> -->

            <v-list color="primary" dark>
                <v-list-tile avatar ripple v-for="item in serviceProviderSidebarItems" :key="item.title" :to="item.link">
                    <v-list-tile-avatar>
                        <v-icon class="amber white--text">{{ item.avatar }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar ripple @click.stop href="tel: +8809610222111">
                    <v-list-tile-avatar>
                        <v-icon class="amber white--text">phone</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>কাস্টমার কেয়ার</v-list-tile-title>
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
        <v-container fluid style="padding-top: 10px; padding-bottom: 0;" v-if="(serviceProviderDetails.balance <= serviceProviderDetails.withdrawable_limit)">
            <v-alert dismissible :value="true" color="error">আপনার বর্তমান একাউন্ট ব্যালেন্স ৫০০ টাকার নিচে আছে। নতুন কাজের অর্ডার পেতে রিচার্জ করুন। </v-alert>
        </v-container>
        <router-view></router-view>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-content>
</template>

<script>
    import { localStorageService } from "../helper.js";
    import Footer from "../components/Footer";
    import axios from "../axios_instance";
    // this.$i18n.locale='bn';
    /// console.log('dfsdfsdfdfdfds',`$i18n`.loc);
    // import VueI18n from 'vue-i18n'
    // const i18n = new VueI18n({
    //   locale: 'bn', 
    // });

    export default {
        name: "Dashboard",
        components: {
            Footer
        },
        data() {
        return { 
            langs: ['bn','en'], 
            alertMessage: null,
            snackbar: false,
            auth_user : localStorageService.getItem("currentUserData"),
            primarySidebar: null,
            secondarySidebar: null, 
            userInfo: [],
            serviceProviderSidebarItems: [{
                title: "পূর্বের কাজ",
                link: "/purberkaaj",
                avatar: "work"
            },{
                title: "নতুন অর্ডার",
                link: "/ownorder",
                avatar: "local_offer"
            },{
                title: "অফার দেখুন",
                link: "/offerdekhun",
                avatar: "local_offer"
            },{
                title: "রিচার্জ করুন",
                link: "/rechargekorun",
                avatar: "flash_on"
            },{
                title: "জিজ্ঞাসা",
                link: "/jiggasha",
                avatar: "question_answer"
            },{
                title: "ব্যবহারবিধি",
                link: "/baboharbidhi",
                avatar: "build"
            }],
            serviceProviderDetails: {
                balance: 0,
                withdrawable_balance: 0
            },
            notifications: [], 
            }
        },  
        watch: {
            
        },
        methods: {
            logout() {
                localStorage.removeItem("currentUserData");
                localStorage.removeItem("d_token");
                this.$router.push("/");
            },
            async getServiceProviderDetails() {
                let loader = this.$loading.show();
                try {
                    let response = await axios.get("/service-provider-details");
                    this.serviceProviderDetails = response.data.data;
                } catch (error) {
                    this.logout();
                }
                loader.hide();
            },
            async getNotifications()
            {
                let response = await axios.get("/notifications");
                this.notifications = response.data;
                // for(i = 0; i <= this.notifications.length; i++){
                //     alert();
                //     this.alertMessage.push(this.notifications[i].title);
                // }
            },
            async marklAsReadNotifications()
            {
                let response = await axios.get("/mark_as_read");
                this.notifications.unread_notifications = response.data;
            }
        },
        created() {
            this.getServiceProviderDetails();
            Echo.channel("NotificationFrontendEvent").listen("NotificationFrontendEvent", response => {
                this.getNotifications();
            });
            this.getNotifications();
            this.$i18n.locale = "bn"; 
        },
    };
</script>
<style  scoped>
.v-navigation-drawer a:hover{
    color: #febe00 !important;
}
</style>