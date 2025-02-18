import Vue from "vue";
import Router from "vue-router";

import FrontendLayout from "./frontend/FrontendLayout.vue";
import FrontendHomePage from "./frontend/FrontendHomePage.vue";
import QuickOrderConfirmedPage from "./frontend/QuickOrderConfirmedPage.vue";
import OurServices from "./frontend/OurServices.vue";
import FrontCategoryDetailsPage from "./frontend/FrontCategoryDetailsPage.vue"; 
import AboutUsPage from "./frontend/AboutUsPage.vue";
import Career from "./frontend/Career.vue";
import CommunityGuidelines from "./frontend/CommunityGuidelines.vue";
import ContactUs from "./frontend/ContactUs.vue";
import EarnMoney from "./frontend/EarnMoney.vue";
import Faq from "./frontend/Faq.vue";
import HowItWorks from "./frontend/HowItWorks.vue";
import PrivacyPolicy from "./frontend/PrivacyPolicy.vue";
import TermsConditions from "./frontend/TermsConditions.vue";
import BecomeServiceProvider from "./frontend/BecomeServiceProvider.vue";
import Blog from "./frontend/Blog.vue";
import BlogDetails from "./frontend/BlogDetails.vue";
import Registration from "./frontend/Registration.vue";
import Login from "./frontend/Login.vue";
import ForgotPassword from "./frontend/ForgotPassword.vue";

import FrontendServicePage from "./views/FrontendServicePageNew.vue";
import FrontendOrderConfirmedPage from "./views/FrontendOrderConfirmedPage.vue";

import DashboardForSp from "./components/DashboardForSp.vue";
import DashboardUser from "./components/DashboardUser.vue";
import DashboardComrade from "./components/DashboardComrade.vue";

import Notifications from "./views/Notifications.vue";
import UserProfile from "./views/UserProfile.vue";
import ComradeProfile from "./views/ComradeProfile.vue"; 
import SpProfile from "./views/SpProfile.vue";
import Scheme from  "./views/Scheme.vue" ;

// MENUS: SERVICE PROVIDER
import MulMenu from "./views/mulmenu/MulMenu.vue";
import Shohokari from "./views/shohokari/Shohokari.vue";
import Ownorder from "./views/ownorder/Ownorder.vue";
import Ownservice from "./views/ownservices/Ownservice.vue";
import ShokolKaaj from "./views/shokolkaaj/ShokolKaaj.vue";
import PurberKaajlist from "./views/purberkaajlist/PurberKaajlist.vue";
import ShebaShomuho from "./views/shebashomuho/ShebaShomuho.vue";
import AyerBiboroni from "./views/ayerbiboroni/AyerBiboroni.vue";

// ESP WORK HISTORY
import PurberKaaj from "./views/purberkaaj/PurberKaaj.vue";
//import Opekkhaimankaj from "./views/opekkhaimankaj/opekkhaimankaj.vue";
import OfferDekhun from "./views/offerdekhun/OfferDekhun.vue"; 
import Jiggasha from "./views/jiggasha/Jiggasha.vue";
import userFrequentlyAskedQuestions from "./views/userfaq/userFrequentlyAskedQuestions.vue";
import RechargeKorun from "./views/rechargekorun/RechargeKorun.vue";
import Baboharbidhi from "./views/baboharbidhi/Baboharbidhi.vue";

// MENUS: USER
import UserHome from "./views/userhome/UserHome.vue";
import UserOffers from "./views/useroffers/UserOffers.vue";
import UserOffersType from "./views/useroffers/UserOffersType.vue";
import UserPromo from "./views/userpromo/UserPromo.vue";
import UserRefer from "./views/userrefer/UserRefer.vue";
import UserOrderHistory from "./views/userorderhistory/UserOrderHistory.vue";
import UserCashoutHistory from "./views/usercashouthistory/UserCashoutHistory.vue";

import QuickOrderHistory from "./views/userorderhistory/QuickOrderHistory.vue";
import ViewOrder from "./views/userorderhistory/ViewOrder.vue";
import Services from "./views/services/Service.vue"; 
import pageNotFound from "./views/pageNotFound.vue"; 

// MENUS: COMRADE
import ComradeHome from "./views/comrade/Home.vue";
import ComradeHistory from "./views/comrade/History.vue";

import ChangePassword from "./views/ChangePassword.vue";

import { localStorageService } from "./helper.js";

// frontend  
Vue.use(Router);

export default new Router({
    mode: "history",
    linkExactActiveClass: 'is-active',
    scrollBehavior() {
        return {x: 0, y: 0}
    },
    routes: [
        {
            path: "/admin",
            component: DashboardForSp,
            beforeEnter: requireAuthSP,
            children: [
                {
                    path: "/mulmenu",
                    name: "মূল মেনু",
                    component: MulMenu,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/shohokari",
                    name: "সহকারী সমূহ",
                    component: Shohokari,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/ownorder",
                    name: "নতুন অর্ডার (নিজস্ব)",
                    component: Ownorder,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/ownservice/:category",
                    name: "নিজস্ব অর্ডার",
                    component: Ownservice,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/shokolkaaj",
                    name: "সকল কাজ",
                    component: ShokolKaaj,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/scheme",
                    name: "Scheme",
                    component: Scheme,
                    beforeEnter: requireAuthSP
                },
                
                {
                    path: "/shebashomuho",
                    name: "সেবা সমূহ",
                    component: ShebaShomuho,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/ayerbiboroni",
                    name: "আয়ের বিবরণী",
                    component: AyerBiboroni,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/purberkaaj",
                    name: "পূর্বের কাজ",
                    component: PurberKaaj,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/purberkaajlist/:type",
                    name: "পূর্বের কাজ সমূহ",
                    component: PurberKaajlist,
                    beforeEnter: requireAuthSP
                }, 
                {
                    path: "/offerdekhun",
                    name: "অফার দেখুন",
                    component: OfferDekhun,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/jiggasha",
                    name: "জিজ্ঞাসা",
                    component: Jiggasha,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/rechargekorun",
                    name: "রিচার্জ করুন",
                    component: RechargeKorun,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/baboharbidhi",
                    name: "ব্যবহারবিধি",
                    component: Baboharbidhi,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/spprofile",
                    name: "প্রোফাইল",
                    component: SpProfile,
                    beforeEnter: requireAuthSP
                },
                {
                    path: "/change-password-sp",
                    name: "পাসওয়ার্ড পরিবর্তন",
                    component: ChangePassword,
                    beforeEnter: requireAuth
                },
                {
                    path: "/sp-notifications",
                    name: "নোটিফিকেশন",
                    component: Notifications,
                    beforeEnter: requireAuth
                },
            ]
        },
        {
            path: "/comrade",
            component: DashboardComrade,
            beforeEnter: requireAuth,
            children: [
                {
                    path: "/comrade-home",
                    name: "আপনার অর্ডার সমূহ",
                    component: ComradeHome,
                    beforeEnter: requireAuth
                },
                {
                    path: "/comrade-history",
                    name: "অর্ডার সমূহের বিবরণী",
                    component: ComradeHistory,
                    beforeEnter: requireAuth
                },
                {
                    path: "/comrade-profile",
                    name: "প্রোফাইল",
                    component: ComradeProfile
                },
                {
                    path: "/change-password-comrade",
                    name: "পাসওয়ার্ড পরিবর্তন",
                    component: ChangePassword,
                    beforeEnter: requireAuth
                },
                {
                    path: "/comrade-notifications",
                    name: "নোটিফিকেশন",
                    component: Notifications,
                    beforeEnter: requireAuth
                },
            ]
        },
        {
            path: "/user",
            component: DashboardUser,
            beforeEnter: requireAuth,
            children: [
                {
                    path: "/",
                    name: "DASHBOARD",
                    component: UserHome,
                    beforeEnter: requireAuth
                },
                {
                    path: "/user/:offer",
                    name: "DASHBOARD", 
                    component: UserHome,
                    beforeEnter: requireAuth
                },
                {
                    path: "/offers",
                    name: "OFFERS",
                    component: UserOffers,
                    beforeEnter: requireAuth
                },
                {
                    path: "/offers-type",
                    name: "OFFERS-TYPE",
                    component: UserOffersType,
                    beforeEnter: requireAuth
                },
                {
                    path: "/promo",
                    name: "PROMO CODE",
                    component: UserPromo,
                    beforeEnter: requireAuth
                },
                {
                    path: "/refer",
                    name: "REFER",
                    component: UserRefer,
                    beforeEnter: requireAuth
                },
                {
                    path: "/order-history",
                    name: "ORDER HISTORY",
                    component: UserOrderHistory,
                    beforeEnter: requireAuth
                },
                {
                    path: "/user-cashout-history",
                    name: "CASHOUT HISTORY",
                    component: UserCashoutHistory,
                    beforeEnter: requireAuth
                },
                {
                    path: "/profile",
                    name: "PROFILE",
                    component: UserProfile
                },
                {
                    path: "/service/:category",
                    name: "Services",
                    component: Services
                },
                {
                    path: "/service/:category/:offer",
                    name: "Services",
                    component: Services
                },
                {
                    path: "/view-order",
                    name: "VIEW ORDER",
                    component: ViewOrder
                },
                {
                    path: "/change-password-client",
                    name: "change-password-client",
                    component: ChangePassword,
                    beforeEnter: requireAuth
                },
                {
                    path: "/quick-order-history",
                    name: "quick-order-history",
                    component: QuickOrderHistory,
                    beforeEnter: requireAuth
                },
                {
                    path: "/user-notifications",
                    name: "user-notifications",
                    component: Notifications,
                    beforeEnter: requireAuth
                },
                {
                    path: "/user-frequently-asked-questions",
                    name: "Frequently Asked Questions",
                    component: userFrequentlyAskedQuestions,
                    beforeEnter: requireAuth
                },
            ]
        },
        {
            path: "/",
            component: FrontendLayout,
            children: [
                {
                    path: "/",
                    name: "FrontendHomePage",
                    component: FrontendHomePage
                },
                {
                    path: "/our-services",
                    name: "OurServices",
                    component: OurServices
                },
                {
                    path: "/about-us",
                    name: "About Us",
                    component: AboutUsPage
                },
                {
                    path: "/career",
                    name: "Career",
                    component: Career
                },
                {
                    path: "/community-guidelines",
                    name: "Community Guidelines",
                    component: CommunityGuidelines
                },
                {
                    path: "/terms-conditions",
                    name: "Terms and Conditions",
                    component: TermsConditions
                },
                {
                    path: "/privacy-policy",
                    name: "Privacy and Policy",
                    component: PrivacyPolicy
                },
                {
                    path: "/how-it-works",
                    name: "How It Works",
                    component: HowItWorks
                },
                {
                    path: "/earn-money",
                    name: "Earn Money",
                    component: EarnMoney
                },
                {
                    path: "/faq",
                    name: "Faq",
                    component: Faq
                },
                {
                    path: "/contact-us",
                    name: "ContactUs",
                    component: ContactUs
                },
                {
                    path: "/registration",
                    name: "Registration",
                    component: Registration
                },
                {
                    path: "/login",
                    name: "Login",
                    component: Login
                },
                {
                    path: "/forgot-password",
                    name: "forgot-password",
                    component: ForgotPassword
                },
                {
                    path: "/order/:category",
                    name: "Order",
                    component: FrontendServicePage,
                },
                {
                    path: "/order/:category/order_confirmed",
                    name: "Order_Confirmed",
                    component: FrontendOrderConfirmedPage
                },
                {
                    path: "/order/refer/:refer_key/:category",
                    name: "OrderService",
                    component: FrontendServicePage
                },
                {
                    path: "/become-service-provider",
                    name: "Become Service Provider",
                    component: BecomeServiceProvider
                },
                {
                    path: "/services/:category",
                    name: "FrontCategoryDetailsPage",
                    component: FrontCategoryDetailsPage
                },
                {
                    path: "/blog",
                    name: "Blog",
                    component: Blog
                },
                {
                    path: "/blog/:id",
                    name: "BlogDetails",
                    component: BlogDetails
                },
                {
                    path: "/confirmed-quick-order",
                    name: "Quick Order Confirmed",
                    component: QuickOrderConfirmedPage
                },
            ]
        },
        {
            path: '*',
            name: "404 Page Not Found !",
            component: pageNotFound
        }
    ]
});

function requireAuth(to, from, next) {
    if (localStorage.d_token) {
        next();
    }
    else
    {
        localStorage.removeItem("currentUserData");
        localStorage.removeItem("d_token");
        window.location.href = "/login";
    }
}

function requireAuthSP(to, from, next) {
    if (localStorage.d_token) {
        let usertype = localStorageService.getItem('currentUserData').type;
        if (usertype == 'esp' || usertype == 'fsp') {
            next();
        }
        // let usertype = localStorageService.getItem('currentUserData').type;
        // if (usertype == 'esp' || usertype == 'fsp') {
        //   next();
        // } else {
        //   window.location.href = "/login/user";
        // }
    }
    else
    {
        localStorage.removeItem("currentUserData");
        localStorage.removeItem("d_token");
        window.location.href = "/";
    }
}
