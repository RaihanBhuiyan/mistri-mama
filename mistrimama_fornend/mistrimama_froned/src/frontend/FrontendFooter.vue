<template>
    <!-- Main Footer -->
    <footer class="main-footer">
        <v-container fluid>
            <!--Widgets Section-->
            <div class="widgets-section">
                <div class="row"> 
                    <div class="footer-column col-lg-3 col-md-6">
                        <div class="footer-widget links-widget">
                            <h2 class="widget-title">{{ $tc('footer',0) }}</h2>
                            <!--Footer Column-->
                            <div class="widget-content"> 
                                <ul>
                                    <li style="color:#FFF; font-size: 14px;"><i style="color:#FBD232;" class="fa fa-map-marker">&nbsp;</i>
                                    {{ $tc('address',1) }}</li>
                                    <li style="color:#FFF; font-size: 14px;"><i style="color:#FBD232;" class="fa fa-map-marker">&nbsp;</i>
                                    {{ $tc('address',0) }}</li>
                                    <li style="color:#FFF; font-size: 14px;"><i style="color:#FBD232;" class="fa fa-phone">&nbsp;</i><a style="color:#FFF;" href="tel:+8809610222111"> {{ $tc('phone',0) }}</a></li>
                                    <li style="color:#FFF; font-size: 14px;"><i style="color:#FBD232;" class="fa fa-envelope">&nbsp;</i><a href="mailto:info@mistrimama.com"> info@mistrimama.com</a></li>
                                    <li style="color:#FFF; font-size: 14px;"><i style="color:#FBD232;" class="fa fa-clock-o">&nbsp;</i>
                                    {{ $tc('Office_time') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--Footer Column-->
                    <div class="footer-column col-lg-1 col-md-1">&nbsp;</div>
                    <div class="footer-column col-lg-2 col-md-6">
                        <div class="footer-widget links-widget">
                            <h2 class="widget-title">{{ $tc('footer',1) }}</h2>
                            <!--Footer Column-->
                            <div class="widget-content"> 
                                <ul class="list" v-if="categories">
                                    <li v-for="category in categories" :key="category.id">
                                        <router-link :to="'/services/' + category.slug"> 
                                            <span v-if="$i18n.locale=='en'">{{ category.name }}</span>
                                            <span v-else>{{ category.name_bn }}</span></router-link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="footer-column col-lg-1 col-md-1">&nbsp;</div>
                    <div class="footer-column col-lg-2 col-md-6">
                        <div class="footer-widget links-widget">
                            <h2 class="widget-title">{{ $tc('footer',2) }}</h2>
                            <div class="widget-content">
                                <ul class="list">
                                    <li><router-link to="/about-us">{{ $tc('company',0) }}</router-link></li>
                                    <li><router-link to="/career">{{ $tc('company',1) }}</router-link></li>
                                    <li><router-link to="/community-guidelines">{{ $tc('company',2) }}</router-link></li>
                                    <li><router-link to="/terms-conditions">{{ $tc('company1',0) }}</router-link></li>
                                    <li><router-link to="/privacy-policy"> {{ $tc('company1',1) }}</router-link></li>
                                    <li><router-link to="/contact-us">{{ $tc('company1',2) }}</router-link></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--Footer Column-->
                    <div class="footer-column col-lg-1 col-md-1">&nbsp;</div>
                    <div class="footer-column col-lg-2 col-md-6">
                        <div class="footer-widget links-widget">
                            <h2 class="widget-title">{{ $tc('footer1',0) }}</h2>
                            <div class="widget-content">
                                <ul class="list">
                                    <li><router-link to="/blog">{{ $tc('discover',0) }} </router-link></li>
                                    <li><router-link to="/how-it-works">{{ $tc('discover',1) }} </router-link></li>
                                    <li><router-link to="/earn-money">{{ $tc('discover',2) }} </router-link></li>
                                    <li><router-link to="/faq">{{ $tc('discover1',0) }} </router-link></li>
                                    <li><a href="javaScript:;" @click="downloadCorporateServices()">{{ $tc('discover1',1) }}</a></li>
                                    <li><router-link to="/become-service-provider">{{ $tc('discover1',2) }}</router-link></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widgets-section">
                <img src="../assets/images/SSL-Commerz-Pay.png" alt="SSLCOMMERZ">
            </div>
        </v-container> 
        <!--Footer Bottom-->
        <div class="footer-bottom">
            <div class="auto-container">
                <div class="inner-container clearfix">
                    <div class="social-links">
                        <ul class="social-icon-two clearfix">
                            <li><a href="https://www.facebook.com/MistriMama"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://www.linkedin.com/company/mistrimama"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="https://twitter.com/MistriMama"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://www.youtube.com/channel/UCoRKp2bHCT6V6cKYCRmokhQ"><i class="fa fa-youtube"></i></a></li>
                            <li><a href="https://www.instagram.com/mistrimama"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                    
                    <div class="copyright-text">
                        <p>© {{ new Date().getFullYear() }} Copyright: <a href="https://mistrimama.com/">Mistri Mama.</a></p>
                    </div>
                </div>
            </div>
        </div>
        <a href="javaScript:;" class="scroll-to-top scroll-to-target" data-target="html">
            <span class="fa fa-arrow-circle-o-up"></span>
        </a> 
    </footer>
    <!-- End Main Footer -->

</template>

<script>
import axios from "../axios_instance.js";
import { localStorageService } from "../helper.js";
export default {
    props: {
        categories: Array
    },
    data() {
        return {
        };
    },
    methods: {
        downloadCorporateServices(){
            axios({
                url: '/download-corporate-services',
                method: 'GET',
                responseType: 'blob',
            }).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'corporate-services.pdf');
                    document.body.appendChild(fileLink);

                    fileLink.click();
            });
        },
    }
};
</script>
<style scoped>
ul{
    padding-left: 3px;
}
ul li{
    margin-bottom: 10px;
}
.main-footer .widgets-section{
    padding: 0;
}
.main-footer .footer-widget{
    margin-bottom: 0;
}
</style>