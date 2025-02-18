import Vue from "vue";
import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css';
import App from "./App.vue";
import router from "./router";
import store from "./store/store.js";
import Toasted from "vue-toasted";
import Loading from 'vue-loading-overlay'; 
import 'vue-loading-overlay/dist/vue-loading.css';
import 'vuetify/dist/vuetify.min.css';
import Lang from "./lang.js";
Vue.config.productionTip=false;
Vue.use(require('vue-moment'));
Vue.use(Toasted, {
    duration: 2500
});

Vue.config.productionTip = false

import VueI18n from 'vue-i18n'
Vue.use(VueI18n)

import Echo from 'laravel-echo';
window.Pusher=require("pusher-js");
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '1a4651c42720c5fe40da',
    cluster: 'ap2',
    forceTLS: true,
    // wsHost: 'https://staging.mistrimama.com/backend/',
    // wsPort: 6001,
    // disableStats: true,
    auth: {
        headers: {
            Authorization: "Bearer " + localStorage.d_token
        }
    }
});

import VueSocialSharing from 'vue-social-sharing';
Vue.use(VueSocialSharing);

// function e2btransform (input) {
//     var numbers = {0: '০', 1: '১', 2: '২', 3: '৩', 4: '৪', 5: '৫', 6: '৬', 7: '৭', 8: '৮', 9:'৯' }; 
//     var output = [];
//     for (var i = 0; i < input.length; ++i) {
//         if (numbers.hasOwnProperty(input[i])) {
//           output.push(numbers[input[i]]);
//         } else {
//           output.push(input[i]);
//         }
//     }
//     return output.join('');
// }
// Vue.use(e2btransform)

Vue.mixin({
    methods: {
        e2btransform: function (input) {
            var numbers = {0: '০', 1: '১', 2: '২', 3: '৩', 4: '৪', 5: '৫', 6: '৬', 7: '৭', 8: '৮', 9:'৯' }; 
            var output = [];
            for (var i = 0; i < input.length; ++i) {
                if (numbers.hasOwnProperty(input[i])) {
                  output.push(numbers[input[i]]);
                } else {
                  output.push(input[i]);
                }
            }
            return output.join('');
        }
    }
});

// import VueAnalytics from 'vue-analytics'

// Vue.use(VueAnalytics, {
//   id: 'UA-152891154-1'
// })

Vue.config.productionTip=false;
Vue.use(Loading, {
    color: '#ec6523 ',
    loader: 'spinner',
    width: 64,
    height: 64,
    backgroundColor: '#ffffff',
    opacity: 0.5,
    zIndex: 999,
}, { 
})

Vue.use(Vuetify, {
    theme: {
        options: {
            customProperties: true
        },
        primary: "#febe00",
        secondary: "#6a6c6d",
        accent: "#03a9f4",
        error: "#f44336",
        warning: "#ffc107",
        info: "#ffeb3b",
        success: "#4caf50"
    }
});
 
Vue.mixin( {
    methods: {
        localSetItem: function(key, data) {
            localStorage.setItem(key, JSON.stringify(data));
        },
        localGetItem: function (key) {
            let data=localStorage.getItem(key) || null;
            return JSON.parse(data);
        },
        baseUrl: function () {  
            if((location.host == 'localhost:8080') || (location.host == '127.0.0.1:8080')){
                //return "http://"+location.host+"/v4/api";
                //return "https://staging.mistrimama.com/backend/api/";
                var urrentUrl =  "http://127.0.0.1:8000/api";
            }else{
                return "https://"+location.host+"/backend/api/";
            } 
        },
        currentUrl: function () {
            return location.toString();
        },
        currentHost: function () {
            return  location.host 
        },
        errorAlerts(error) {
            if (error.response) {
                return error.response.data.message;
            }
            else {
                return error.message;
            }
        },
        goto(route) {
            this.$router.replace(this.$route.query.redirect || route);
        }
    }
});
 
const i18n = new VueI18n({
  locale: 'en', // set locale
  messages: Lang, // set locale messages
})
///console.log('fffffffffff====================',i18n.locale  i18n.locale = 'en');
new Vue( {
    router, store, i18n, render: h=> h(App)
}

).$mount("#app");