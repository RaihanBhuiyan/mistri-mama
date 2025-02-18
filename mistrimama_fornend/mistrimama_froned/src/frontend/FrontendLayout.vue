<template>
    <div class="page-wrappers">
        <frontend-header></frontend-header>
        <router-view />
        <frontend-footer :categories="categories"></frontend-footer>
    </div>
</template>

<script>
import FrontendHeader from "./FrontendHeader";
import FrontendFooter from "./FrontendFooter";
import axios from "../axios_instance.js";
import { localStorageService } from "../helper.js";

export default {
    name: "FrontendLandingPage",
    components: {
        'frontend-header': FrontendHeader,
        'frontend-footer': FrontendFooter,
    },
    data() {
        return {
            categories: "",
        };
    },

    created() {
        this.storeCategories();
    },

    methods: {
        async storeCategories() {
            this.categories = localStorageService.getItem("categorys");
        },
    }
};
</script>
