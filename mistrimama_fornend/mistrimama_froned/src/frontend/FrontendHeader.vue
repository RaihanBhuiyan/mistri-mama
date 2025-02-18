<template>
    <header class="main-header header-style-one">
        <div class="auto-container">
            <div class="header-lower" style="border-radius: 0px 0px 17px 17px;">
                <div class="main-box clearfix">
                    <div class="logo-box">
                        <div class="logo"><a href="/">
                            <img style="height: 84px;" src="https://mistrimama.com/backend/public/frontend/logo.png" alt="MM" title=""></a>
                        </div>
                    </div>
                    
                    <!-- <p>{{ $t("welcome_message") }}</p>
                    <p>{{ $t("data[0].name") }}</p> -->
                    <div class="nav-outer clearfix">
                        <!-- Main Menu -->
                        <nav class="main-menu navbar-expand-md">

                            <div class="navbar-header">
                                <!-- Toggle Button -->      
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="material-icons icons">menu</span>
                                </button>
                            </div>
                            
                            <div class="collapse navbar-collapse clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                   <!-- <li class="" style="background-color: red; border: 1px solid blue;">
                                        <select v-model="$i18n.locale"> 
                                          <option v-for="(lang, i) in langs" :key="`lang${i}`" :value="lang">{{ lang }}</option>
                                        </select> 
                                    </li>  --> 
                                    <li class="">
                                        <router-link to="/">
                                            <span class="non-btn">{{ $tc('menu',0) }}</span>
                                        </router-link>
                                    </li>
                                    <li class="">
                                        <router-link to="/our-services">
                                            <span class="non-btn">{{ $tc('menu',1) }}</span>
                                        </router-link>
                                    </li>
                                    <li class="">
                                        <router-link to="/about-us">
                                            <span class="non-btn">{{ $tc('menu',2) }}</span>
                                        </router-link>
                                    </li>
                                    <li class="">
                                        <router-link to="/order/electrical">
                                            <span class="non-btn">{{ $tc('menu2',0) }}</span>
                                        </router-link>
                                    </li>
                                    <li class="">
                                        <router-link to="/contact-us">
                                            <span class="non-btn">{{ $tc('menu2',1) }} </span>
                                        </router-link>
                                    </li>
                                    <li class="">
                                        <router-link to="/login" v-if="isLoggedIn == false">
                                            <v-chip color="primary" text-color="white" style="cursor:pointer">
                                                <v-avatar style="padding-left: 10px !important;">
                                                    <v-icon>person</v-icon>
                                                </v-avatar>
                                                <div class="text-truncate" style="width: 40px">{{ $tc('menu2',2) }}</div>
                                            </v-chip>
                                        </router-link>
                                        <router-link to="/user" v-if="isLoggedIn == true && auth_user.type == 'client'">
                                            <v-chip v-if="" color="primary" text-color="white" style="cursor:pointer">
                                                <v-avatar style="padding-left: 10px !important;">
                                                    <v-icon>person</v-icon>
                                                </v-avatar>
                                                <div class="text-truncate" style="width: 75px">{{ auth_user.name }}</div>
                                            </v-chip>
                                        </router-link>
                                        <router-link to="/mulmenu" v-if="isLoggedIn == true && auth_user.type == 'esp'">
                                            <v-chip color="primary" text-color="white" style="cursor:pointer">
                                                <v-avatar style="padding-left: 10px !important;">
                                                    <v-icon>person</v-icon>
                                                </v-avatar>
                                                <div class="text-truncate" style="width: 75px">{{ auth_user.name }}</div>
                                            </v-chip>
                                        </router-link>
                                        <router-link to="/mulmenu" v-if="isLoggedIn == true && auth_user.type == 'fsp'">
                                            <v-chip color="primary" text-color="white" style="cursor:pointer">
                                                <v-avatar style="padding-left: 10px !important;">
                                                    <v-icon>person</v-icon>
                                                </v-avatar>
                                                <div class="text-truncate" style="width: 75px">{{ auth_user.name }}</div>
                                            </v-chip>
                                        </router-link>
                                        <router-link to="/comrade-home" v-if="isLoggedIn == true && auth_user.type == 'comrade'">
                                            <v-chip color="primary" text-color="white" style="cursor:pointer">
                                                <v-avatar style="padding-left: 10px !important;">
                                                    <v-icon>person</v-icon>
                                                </v-avatar>
                                                <div class="text-truncate" style="width: 75px">{{ auth_user.name }}</div>
                                            </v-chip>
                                        </router-link>
                                    </li>
                                    <li class="" v-if="isLoggedIn == true">
                                        <a @click="logout()">
                                            <v-chip color="primary" text-color="white" style="cursor:pointer">
                                                <v-avatar style="padding-left: 10px !important;">
                                                    <v-icon>power_settings_new</v-icon>
                                                </v-avatar>
                                                <div class="text-truncate" style="width: 53px">{{ $tc('menu3',1) }}</div>
                                            </v-chip>
                                        </a>
                                    </li>
                                    <li class="" v-if="isLoggedIn == false">
                                        <router-link to="/registration">
                                            <v-chip color="primary" text-color="white" style="cursor:pointer">
                                                <v-avatar style="padding-left: 10px !important;">
                                                    <v-icon>person</v-icon>
                                                </v-avatar>
                                                <div class="text-truncate" style="width: 55px">{{ $tc('menu3',0) }}</div>
                                            </v-chip>
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                        <!-- Main Menu End-->
                    </div>
                </div>
            </div>
        </div>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </header>
</template>

<script>
import jQuery from "jquery";
let $ = jQuery;

import "../assets/js/jquery.js";
import {required, numeric } from "vuelidate/lib/validators";
import axios from "../axios_instance";
import { localStorageService, Helper } from "../helper.js";
import langs from "../lang.js"; 
export default {
    data() {
        return {
            valid: null,
            isLoggedIn: false,
            auth_user: [],
            alertMessage: null,
            snackbar: false,
            langs: ['en', 'bn'],
            // phoneRules: [
            //     v => !!v || "This field cannot be empty", 
            //     v => v.match(/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/)|| "Invalid mobile number (+8801XXXXXXXXX)",
            // ],
        };
    },
    created() {
        if (localStorage.d_token != undefined) {
            this.isLoggedIn = true;
            this.auth_user = localStorageService.getItem("currentUserData");
            if(this.auth_user == null)
            {
                localStorage.removeItem("currentUserData");
                localStorage.removeItem("d_token");
                this.isLoggedIn = false;
            }
        } else {
            this.isLoggedIn = false;
        }
    },
    methods: {
        logout() {
            localStorage.removeItem("currentUserData");
            localStorage.removeItem("d_token");
            this.isLoggedIn = false;
        },
    },
    computed: {
    },
    watch: {
        '$route' () {
            const element = document.querySelector("#navbarSupportedContent");
            let isShown = element.classList.contains("show");
            if(isShown){
                element.classList.remove("show");
            }
        }
    }
};


</script>