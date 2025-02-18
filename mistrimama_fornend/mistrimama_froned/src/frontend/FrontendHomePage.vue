<template>
    <div class="frontend_home_page">
        <section id="top" class="banner-section">
            <v-carousel v-if="sliders.length != 0" hide-delimiters interval="10000" height="auto">
                <v-carousel-item v-for="(item,i) in sliders" :key="i" :src="item.url"></v-carousel-item>
            </v-carousel>
            <img v-else class="d-block" style="height:100vh; width:100%;" src="../assets/images/pre-loading-banner.jpg" alt="Loading..." />
            
            <div class="order-form contact-form">
                <v-card dark style="border-radius: 10px; background: rgba(17, 9, 8, 0.9);">
                    <v-card-title class="title" style="padding:16px 16px 8px">{{ $tc('quick_order',0) }}</v-card-title>
                    <v-card-text style="padding:0 16px;">
                        <v-form ref="quickOrderData" v-model="valid" lazy-validation @submit.prevent="palceQuickOrder">
                            <div class="dropdown">
                                <v-text-field height="20px" class="" color="primary" v-model="quickOrderData.request_service" v-on:keyup="findQuickOrder" :error-messages="display_errors.request_service" v-bind:label="$tc('quick_order',1)"></v-text-field>
                                <ul class="dropdown-list" v-show="dropdownList">
                                    <li class="dropdown-item" @click="selectItem(quickorderitem)" v-for="(quickorderitem, i) in quickFilteritems" :key="i">{{ quickorderitem }}</li>
                                </ul>
                            </div>
                            <v-text-field height="20px" color="primary" v-model="quickOrderData.name" :error-messages="display_errors.name" 
                            v-bind:label="$tc('quick_order',2)"></v-text-field>
                            <v-text-field height="20px" color="primary" v-model="quickOrderData.phone" prefix="+88" placeholder="01XXXXXXXXX" type="number" :error-messages="display_errors.phone" v-bind:label="$tc('quick_order1',1)" required></v-text-field>
                            <v-text-field height="20px" color="primary" v-model="quickOrderData.comments"  v-bind:label="$tc('quick_order1',0)" ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions style="padding:0 16px 8px;">
                        <a href="tel:+8809610222111" style="display:block">
                            <img class="" src="../assets/images/call.png" style="max-width: 250px; width:100%;" alt="MM" />
                        </a>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="palceQuickOrder">{{ $tc('menu2',0) }}</v-btn>
                    </v-card-actions>
                </v-card>
            </div>
        </section>
        <!-- End Bnner Section -->

        
        <section class="services-section">
            <div class="upper-box">
                <div class="auto-container">    
                    <div class="sec-title text-lg-center">
                        <span class="float-text">{{ $tc('expertise',0) }}</span>
                        <h2>{{ $tc('menu',1) }}</h2>
                    </div>
                </div>
            </div>
            <!-- <v-carousel v-if="categories.length != 0" hide-delimiters :item="3" interval="10000" height="auto">
                <v-carousel-item v-for="(x,i) in 6" :key="i">
                    <div class="row" v-if="()"></div>
                    <div class="col-md-4">
                        ddddddddddd
                    </div>
                </v-carousel-item>
            </v-carousel> -->
            
            <hooper v-if="categories.length != 0" style="height:auto" :settings="hooperSettingCategory">
                <slide v-for="(category,i) in categories" :key="i">
                    <div class="services-box">
                        <div class="auto-container">
                            <div class="service-block service-block-slider">
                                <div class="inner-box">
                                    <div class="image-box">
                                        <figure class="image">
                                            <router-link :to="{ name: 'FrontCategoryDetailsPage', params: { category: category.slug }}">
                                                <img :src="category.opt_image"  alt="MM" />
                                            </router-link>
                                        </figure>
                                    </div>
                                    <div class="lower-content">
                                        <h3>
                                            <router-link :to="{ name: 'FrontCategoryDetailsPage', params: { category: category.slug }}">
                                            <span v-if="$i18n.locale=='en'">{{ category.name }}</span>
                                            <span v-else>{{ category.name_bn }}</span> </router-link>
                                        </h3>
                                        <div v-if="$i18n.locale=='en'" class="text" v-html="truncate(category.description)"></div> 
                                        <div v-else class="text" v-html="truncate(category.description_bn)"></div> 
                                        <div class="link-box">
                                            <router-link :to="'/services/' + category.slug">                                               
                                                {{ $tc('more',0) }}
                                                <i class="fa fa-long-arrow-right"></i>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </slide>
                <hooper-navigation class="hooper-navigation-backend" slot="hooper-addons"></hooper-navigation>
            </hooper>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="auto-container">
                <div class="row no-gutters">
                    <!-- Image Column -->
                    <div class="image-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="title-box wow fadeInLeft animated" data-wow-delay="1200ms" style="visibility: visible; animation-delay: 1200ms; animation-name: fadeInLeft;">
                                <h2>ABOUT <br> US</h2>
                            </div>
                            <div class="image-box wow fadeInRight animated" data-wow-delay="600ms" style="visibility: visible; animation-delay: 600ms; animation-name: fadeInRight;">
                                <figure class="image"><img src="../assets/images/MistriMamaAboutUs.jpg"  alt="MM"></figure>
                            </div>
                        </div>
                    </div>

                    <!-- Content Column -->
                    <div class="content-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;">
                            <div class="content-box">
                                <div class="title">
                                    <h1> {{ $tc('about_us',0) }}<br />{{ $tc('about_us',1) }}</h1>
                                </div>
                                <div class="text">{{ $tc('about_us',2) }}</div>
                                <div class="link-box">
                                    <router-link class="theme-btn btn-style-one" to="/about-us"> {{ $tc('company',0) }}</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End About Section -->

        <section class="video-section style-two" style="display: none;">
            <div class="outer-box">
                <div class="auto-container">
                    <div class="row">
                        <div class="content-column col-lg-6 col-md-12 col-sm-12">
                            <div class="inner-column fadeInLeft animated">
                                <div class="sec-title" style="margin-top: 70px;">
                                    <span class="float-text">DOWNLOAD</span>
                                    <h2>DOWNLOAD OUR APP</h2>
                                </div>
                                <div class="content-box">
                                    <span class="title">Any Service, Any Time, Anywhere.</span>
                                    <div class="text">Give us your mobile number. You’ll get an SMS with the app download link.</div> 
                                    
                                    <div class="input-group get-app-section" style="">
                                        <input type="text" class="" placeholder="Type your mobile number">
                                        <span class="input-group-btn">
                                            <button class="" type="button">Get the app</button>
                                        </span>
                                    </div>
                                    <div class="link-box">
                                        <a href="">
                                            <img class="" src="" style="max-width: 170px; width:100%;" alt="MM" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Video Column -->
                        <div class="video-column col-lg-6 col-md-12 col-sm-12">
                            <div class="inner-column fadeInRight animated">
                                <div class="video-box">
                                    <figure class="image">
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Project Section -->
        <section class="projects-section">
            <div class="auto-container">
                <div class="sec-title text-lg-right">
                    <span class="float-text">{{ $tc('corporate',0) }} </span>
                    <h2>{{ $tc('b2bpackages',0) }} </h2>
                </div>
            </div>
            <div class="inner-container">
                <!-- <div class="projects-carousel"> -->
                <!-- Project Block sabbir -->
                <hooper v-if="projects.length != 0" style="height:auto" :settings="hooperSettingsProject">
                    <slide v-for="(project,i) in projects" :key="i">
                        <div class="project-block">
                            <div class="image-box">
                                <figure class="image">
                                    <img :src="project.image"  alt="MM" />
                                </figure>
                                <div class="overlay-box">
                                    <h4><a href="/"><strong>
                                        <span v-if="$i18n.locale=='en'">{{ project.title }}</span>
                                        <span v-else>{{ project.title_bn }}</span>
                                        </strong></a></h4>
                                    <div class="btn-box" v-if="$i18n.locale=='en'" >
                                        <p v-for="(summary,i) in project.summary" :key="i" class="text-center text-white" style="padding: 0 15px; font-size: 14px">{{ summary }} 
                                        </p> 
                                    </div>
                                    <div class="btn-box" v-else> 
                                        <p  v-for="(summary,i) in project.summary_bn" :key="i" class="text-center text-white" style="padding: 0 15px; font-size: 14px">{{ summary }} 
                                        </p>
                                    </div>
                                    <router-link class="tag" style="color: #febe00; display: none;" to="">
                                        {{ $tc('more',0) }} 
                                        <i class="fa fa-external-link"></i>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </slide>
                </hooper>
            </div>
        </section>
        <!--End Project Section -->

        <!-- Team Section -->
        <section class="team-section" style="display: none;">
            <div class="auto-container">
                <div class="sec-title text-center">
                    <span class="title">Our Team</span>
                    <h2>Profect Expert</h2>
                </div>

                <div class="row clearfix">
                    <div class="team-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box">
                            <div class="image-box">
                                <div class="image">
                                    <a href="team.html">
                                        <img src="../assets/images/resource/team-1.jpg"  alt="MM" />
                                    </a>
                                </div>
                                <ul class="social-links">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-google-plus"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
                                    </li>
                                </ul>
                                <h3 class="name"><a href="team.html">Scott Grey</a></h3>
                            </div>
                            <span class="designation">Architect</span>
                        </div>
                    </div>

                    <div class="team-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box">
                            <div class="image-box">
                                <div class="image">
                                    <a href="team.html">
                                        <img src="../assets/images/resource/team-2.jpg"  alt="MM" />
                                    </a>
                                </div>
                                <ul class="social-links">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-google-plus"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
                                    </li>
                                </ul>
                                <h3 class="name"><a href="team.html">Russel Reed</a></h3>
                            </div>
                            <span class="designation">Project Manager</span>
                        </div>
                    </div>

                    <div class="team-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box">
                            <div class="image-box">
                                <div class="image">
                                    <a href="team.html">
                                        <img src="../assets/images/resource/team-3.jpg"  alt="MM" />
                                    </a>
                                </div>
                                <ul class="social-links">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-google-plus"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
                                    </li>
                                </ul>
                                <h3 class="name"><a href="team.html">Cheryl Ray</a></h3>
                            </div>
                            <span class="designation">Interior Designer</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Team Section -->

        <!-- Testimonial Section -->
        <section class="testimonial-section">
            <div class="outer-container clearfix">
                <!-- Title Column -->
                <div class="title-column clearfix">
                    <div class="inner-column">
                        <div class="sec-title text-lg-right">
                            <span class="float-text">testimonial</span>
                            <h2>What Client Says</h2>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Column -->
                <div class="testimonial-column clearfix">
                    <div class="inner-column">
                        <div class="testimonial">
                            <!-- Testimonial Block -->
                            <hooper v-if="testimonials.length != 0" style="height:auto" :settings="hooperSettingsTestimonial">
                                <slide v-for="(testimonial,i) in testimonials" :key="i">
                                    <div class="testimonial-block">
                                        <div class="inner-box">
                                            <div class="image-box"><img :src="testimonial.image"  alt="MM"></div>
                                            <div class="text">{{ testimonial.details }}</div>
                                            <div class="info-box">
                                                <h4 class="name">{{ testimonial.name }}</h4>
                                                <span class="designation">{{ testimonial.designation }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </slide>
                                <hooper-pagination slot="hooper-addons" style="bottom: -50px;"></hooper-pagination>
                            </hooper>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Testimonial Section -->

        <!-- News Section -->
        <section class="news-section" v-if="articles.length != 0">
            <div class="auto-container">
                <div class="sec-title">
                    <span class="float-text">Blogs</span>
                    <h2>News &amp; Articals</h2>
                </div>
                <div class="row">
                    <!-- News Block -->
                    <div class="news-block col-lg-4 col-md-6 col-sm-12" v-for="(article,i) in articles" :key="i">
                        <div class="inner-box">
                            <div class="image-box">
                                <figure class="image"><img :src="article.image"  alt="MM"></figure>
                                <div class="overlay-box">
                                    <router-link :to="{ name: 'BlogDetails', params: { id: article.id }}">
                                        <i class="fa fa-link"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="caption-box">
                                <h3><router-link :to="{ name: 'BlogDetails', params: { id: article.id }}">{{ article.title }}</router-link></h3>
                                <ul class="info">
                                    <li>{{ article.published_date }},</li>
                                    <li>{{ article.post_by }}</li>
                                </ul>
                                <ul class="info">
                                    <li>
                                        <router-link :to="{ name: 'BlogDetails', params: { id: article.id }}">
                                        <i class="fa fa-comments-o">&nbsp;</i>{{ article.total_comments }}
                                        </router-link>
                                    </li>
                                    <li style="cursor: pointer" @click="giveLikeOnArticle(i, article.id)"><i :class="(article.is_liked) ? 'fa fa-thumbs-up' : 'fa fa-thumbs-o-up'">&nbsp;</i>{{ article.total_like }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End News Section -->
        
        <section class="contact-page-section" id="contactus">
            <div class="container-fluid">
                <div class="row">
                    <!-- Form Column -->
                    <div class="form-column col-lg-6">
                        <div class="inner-column">
                            <div class="sec-title">
                                <span class="float-text"> {{ $tc('contact_form',1) }}</span>
                                <h2>{{ $tc('contact_form',0) }}</h2>
                            </div>
                            <div class="contact-form">
                                <v-form ref="contactUsForm" v-model="valid" lazy-validation @submit.prevent="contactUsStore">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <v-text-field  color="accent" v-model="contactUsForm.name" :error-messages="display_errors.name" type="text" v-bind:label="$tc('contact_form',2)" outlined></v-text-field>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <v-text-field  color="accent" v-model="contactUsForm.phone"  prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" v-bind:label="$tc('contact_form1',0)" outlined></v-text-field>
                                        </div>

                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <v-text-field  color="accent" v-model="contactUsForm.company" :error-messages="display_errors.company" type="text" v-bind:label="$tc('contact_form1',1)" outlined></v-text-field>
                                        </div>

                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <v-text-field  color="accent" v-model="contactUsForm.email" :error-messages="display_errors.email" type="text" v-bind:label="$tc('contact_form1',2)" outlined></v-text-field>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <v-textarea color="accent" v-model="contactUsForm.message" :error-messages="display_errors.message" v-bind:label="$tc('contact_form2',1)" v-bind:placeholder="$tc('contact_form2',1)"></v-textarea>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <button class="theme-btn btn-style-three mt-3" :disabled="isTrue ? false : true" type="submit" name="submit-form">{{ $tc('contact_form2',0) }}</button>
                                        </div>
                                    </div>
                                </v-form>
                            </div>
                        </div>
                    </div>

                    <div class="map-column col-lg-6">
                        <div class="inner-column">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.383745283798!2d90.40608961449269!3d23.73369129533243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b9590048c46b%3A0x4f2d99cc0463a1cc!2sMistri%20Mama!5e0!3m2!1sen!2sbd!4v1583748129871!5m2!1sen!2sbd" frameborder="0" height="100%" width="100%" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
import jQuery from "jquery";
let $ = jQuery;

import "../assets/js/jquery.js";
//import "../assets/js/jquery.fancybox.js";
//import "../assets/js/appear.js";
//import "../assets/js/mixitup.js";
//import "../assets/js/script.js";
import axios from "../axios_instance.js";
import { localStorageService } from "../helper.js";

import {
    Hooper,
    Slide,
    Progress as HooperProgress,
    Pagination as HooperPagination,
    Navigation as HooperNavigation
}
from "hooper";
import "hooper/dist/hooper.css";

export default {
    components: {
        Hooper,
        Slide,
        HooperProgress,
        HooperPagination,
        HooperNavigation,
    },
    data() {
        return {
            sliders: [],
            categories: "",
            articles: [],
            valid: null,
            display_errors: [],
            auth_user: null,
            contactUsForm: {
                name: null,                   
                phone: null ,                   
                company: null,                   
                email: null,                   
                message: null,                   
            },
            quickorderitems: [],
            dropdownList: false,
            inputValue: true,
            quickOrderData: {
                request_service: "",
                name: "",
                phone: "",
                address: "",
                comments: "",
            },
            isTrue: true,
            // nameRules: [
            //     v => !!v || "This field cannot be empty",
            //     v => (v && v.length <= 20) || "Name must be less than 20 characters"
            // ],
            // phoneRules: [
            //     v => !!v || "This field cannot be empty", 
            //     v => v.match(/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/)|| "Invalid mobile number (+8801XXXXXXXXX)",
            // ],
            // emailRules: [
            //     v => /.+@.+/.test(v) || 'E-mail must be valid'
            // ],
            alertMessage: null,
            snackbar: false,
            hooperSettingCategory: {
                itemsToShow: 3,
                wheelControl: false,
                infiniteScroll: true,
                autoPlay: true,
                playSpeed: 10000,
                transition: 1000,
                centerMode: true,
                breakpoints: {
                    1023: {
                        itemsToShow: 3
                    },
                    767: {
                        itemsToShow: 2
                    },
                    599: {
                        itemsToShow: 2
                    },
                    0: {
                        itemsToShow: 1
                    },
                }
            },
            projects: [],
            hooperSettingsProject: {
                itemsToShow: 4,
                centerMode: false,
                wheelControl: false,
                infiniteScroll: false,
                autoPlay: false,
                playSpeed: 10000,
                transition: 1000,
                breakpoints: {
                    767: {
                        itemsToShow: 4
                    },
                    599: {
                        itemsToShow: 3
                    },
                    0: {
                        itemsToShow: 1
                    },
                }
            },
            testimonials: [],
            hooperSettingsTestimonial: {
                itemsToShow: 1,
                centerMode: true,
                wheelControl: false,
                infiniteScroll: true,
                autoPlay: true,
                playSpeed: 10000,
                transition: 1000
            },
            quickFilteritems: [],
        };
    },
    created() {
        this.getSlider();
        this.storeCategories();
        this.getArticles();
        this.getProjects();
        this.getTestimonials();
        this.quickOrderItems();
        if (localStorage.d_token) {
            this.isLoggedIn = true;
            this.auth_user = localStorageService.getItem("currentUserData");
        } else {
            this.isLoggedIn = false;
        }
    },
    methods: {
        async findQuickOrder(){
            this.dropdownList = true;
            let response = await axios.post("/quickorderitems", {'find': this.quickOrderData.request_service});
            this.quickFilteritems = response.data.data;
        },
        async quickOrderItems() {
            // let quickorderitems = await axios.get("/quickorderitems");
            // this.quickorderitems = quickorderitems.data.data;
        },
        async getSlider() {
            var response = await axios.get("slider");
            this.sliders = response.data.data;
        },
        async storeCategories() {
            // if (!localStorageService.getItem("categorys")) {
            //     let categories = await axios.get("/category");
            //     localStorageService.setItem("categorys", categories.data.data);
            //     this.categories = categories.data.data;
            // } else {
            //     this.categories = localStorageService.getItem("categorys");
            // }
            let categories = await axios.get("/category");
            localStorageService.setItem("categorys", categories.data.data);
            this.categories = categories.data.data;
        },
        async getProjects()
        {
            let projects = await axios.get("/projects");
            this.projects = projects.data;
            console.log('projects', projects);

        },
        async getTestimonials()
        {
            let testimonials = await axios.get("/testimonials");
            this.testimonials = testimonials.data;

        },
        async getArticles() {
            let articles = await axios.get("/articles");
            this.articles = articles.data.data;
        },
        async giveLikeOnArticle(index, id){
            try {
                let response = await axios.get('/article/like/' + id);
                this.articles[index].total_like = response.data;
                this.articles[index].is_liked = true;
            } catch (error) {
            }
        },
        async contactUsStore() {
            if (this.$refs.contactUsForm.validate()) {
                this.display_errors = [];
                this.isTrue = false;
                try {
                    let response = await axios.post("/contact_us", this.contactUsForm);
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.contactUsForm = {
                        name: null,                   
                        phone: null ,                   
                        company: null,                   
                        email: null,                   
                        message: null,                   
                    }
                    this.isTrue = true;
                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                    this.isTrue = true;
                }
            }
        },
        async palceQuickOrder() {
             if (this.$refs.quickOrderData.validate()) {
                this.display_errors = [];
                try {
                    var response = await axios.post("quickorder", {
                        request_service: this.quickOrderData.request_service,
                        name: this.quickOrderData.name,
                        phone: this.quickOrderData.phone,
                        address: this.quickOrderData.address,
                        comments: this.quickOrderData.comments,
                    });
                    this.quicikOrderDialog = false;
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.quickOrderData = {
                        request_service: "",
                        name: "",
                        phone: "",
                        address: "",
                        comments: "",
                    }
                    this.$router.replace("/confirmed-quick-order");
                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
             }
        },
        async getArea() {
            var area = await axios.get("/area");
            localStorageService.setItem("area", area.data.data);
        },
        truncate(input) {
            if (input.length > 5)
                return input.substring(0, 200) + "...";
            else {
                return input;
            }
        },
        selectItem (theItem) {
            this.quickOrderData.request_service = theItem;
            this.quickorderitems = [];
            this.dropdownList = false;
        }
    },
    computed: {
        // quickFilteritems(){
        //     if(this.quickOrderData.request_service.length > 0){
        //         this.dropdownList = true;
        //         return this.quickorderitems.filter((item)=>{
        //             return this.quickOrderData.request_service.toLowerCase().split(' ').every(v => item.toLowerCase().includes(v))
        //         });
        //     }
        //     else
        //     {
        //         this.dropdownList = false;
        //         this.quickOrderItems();
        //     }
        // }
        // categoriesWithPluck() {
        //     return this.categories.map(function(object) { 
        //         return {id : object.id, name : object.name};
        //     });
        // }
    }
};
</script>


<style>
.dropdown{
  position: relative;
}
.dropdown-input, .dropdown-selected{
  width: 100%;
  padding: 10px 16px;
  border: 1px solid transparent;
  background: #edf2f7;
  line-height: 1.5em;
  outline: none;
  border-radius: 8px;
}
.dropdown-input:focus, .dropdown-selected:hover{
  background: #fff;
  border-color: #e2e8f0;
}
.dropdown-input::placeholder{
  opacity: 0.7;
}
.dropdown-selected{
  font-weight: bold;
  cursor: pointer;
}
.dropdown-list{
    position: absolute;
    width: 100%;
    max-height: 300px;
    margin-top: 0px;
    overflow-y: auto;
    background: #ffffff;
    -webkit-box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-radius: 4px;
    z-index: 9999;
    padding: 0;
}
.dropdown-item{
  display: flex;
  width: 100%;
  padding: 11px 16px;
  cursor: pointer;
}
.dropdown-item:hover{
  background: #edf2f7;
}
.dropdown-item-flag{
  max-width: 24px;
  max-height: 18px;
  margin: auto 12px auto 0px;
}
.v-carousel__prev .v-btn__content,
.v-carousel__next .v-btn__content{
    background-color: #febe00;
    border-radius: 100px;
    padding: 10px;
    margin: 0 10px;
}
.v-carousel__prev .v-btn__content .v-icon,
.v-carousel__next .v-btn__content .v-icon{
    font-size: 30px !important;
    color: #000;
}

.get-app-section{
    margin: 15px 0 30px;
}
.get-app-section input[type="text"]{
    border-bottom-left-radius: 8px;
    border-top-left-radius: 8px;
    border: 1.3px solid rgba(0,0,0,.1);
    background-color: #fff;
    font-size: 16px;
    font-weight: 400;
    color: rgba(0,0,0,.5);
    padding: 24px 36px;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    height: calc(1.5em + 1rem + 2px);
    line-height: 1.5;
}
.get-app-section button[type="button"]{
    border-radius: 8px;
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
    font-size: 18px;
    font-weight: 600;
    padding: 12px 35px;
    background-color: #febe00;
    color: #fff;
    cursor: pointer;
    border: 0;
    line-height: 1.5;
}
</style>