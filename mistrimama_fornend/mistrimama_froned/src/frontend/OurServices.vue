<template>
    <section class="services-section" style="padding-top: 100px">
        <div class="upper-box">
            <div class="auto-container">    
                <div class="sec-title text-center">
                    <span class="float-text"> {{ $tc('expertise',0) }}</span>
                    <h2> {{ $tc('menu',1) }}</h2>
                </div>
            </div>
        </div>
        <div class="auto-container">
            <div class="row" v-if="categories.length != 0">
                <div class="col-md-4" v-for="(category,i) in categories" :key="i">
                    <div class="services-box">
                        <div class="service-block" style="margin-bottom: 30px">
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
                                            <span v-else>{{ category.name_bn }} </span>
                                        </router-link>
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
            </div>
        </div>
    </section>
</template>

<script>
import axios from "../axios_instance.js";
import { localStorageService } from "../helper.js";
export default {
    components: {
    },
    data() {
        return {
            categories: "",
        };
    },
    methods: {
        truncate(input) {
            if (input.length > 5)
                return input.substring(0, 200) + "...";
            else {
                return input;
            }
        }
    },
    created() {
        this.categories = localStorageService.getItem("categorys");
    },
};
</script>