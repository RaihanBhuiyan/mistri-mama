<template>
    <div class="auto-container sidebar-page-container" style="margin-top:30px">
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
    </div>
</template>
<script>
    import axios from "../axios_instance.js";
    import { localStorageService } from "../helper.js";

    export default {
        data() {
            return {
                articles: [],
                alertMessage: null,
                snackbar: false,
            };
        },
        methods: {
            async getArticles() {
                let articles = await axios.get("/get_articles");
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
        },
        created() {
            this.getArticles();
        },
    };
</script>

<style>
</style>