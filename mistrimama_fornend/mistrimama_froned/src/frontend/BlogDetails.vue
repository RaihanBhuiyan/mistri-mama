<template>
    <div class="auto-container sidebar-page-container" style="margin-top:30px">
        <div class="blog-heading">
            <h3>{{ article.title }}</h3>
            <ul class="info" style="background-color: transparent !important; padding: 0;">
                <li>{{ article.published_date }},</li>
                <li><router-link :to="'/blog/' + article.id">{{ article.post_by }}</router-link></li>
            </ul>
        </div>
        <div class="content-side" style="margin-bottom:50px">
            <div class="blog-detail">
                <div class="inner-container">
                    <!-- News Block -->
                    <div class="news-block-two">
                        <div class="inner-box">
                            <div class="caption-box" style="margin:0; padding:0;">
                                <div class="inner" style="box-shadow: none; padding: 0;">
                                    <div v-html="article.short_description"></div>
                                    <figure v-if="article.image != null" class="image"><img style="width:100%" :src="article.image"  alt="MM"></figure>
                                    <div v-html="article.content"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="share-option">
                        <span class="title">Share This:</span>
                        <ShareNetwork
                            v-for="network in networks"
                            :network="network.network"
                            :key="network.network"
                            :style="{backgroundColor: network.color}"
                            :url="article.url"
                            :title="article.title"
                            :description="article.content"
                            :quote="article.title"
                            hashtags="Mistrimama">
                            <i :class="network.icon"></i>
                            <span>{{ network.name }}</span>
                        </ShareNetwork>
                    </div>

                    <div class="sidebar-widget latest-news">
                        <div class="sidebar-title"><h3>Recent Post</h3></div>
                        <div class="widget-content">
                            <div class="row">
                                <!-- News Block -->
                                <div class="news-block col-lg-4 col-md-6 col-sm-12" v-for="(article,i) in articles" :key="i">
                                    <div class="inner-box">
                                        <div class="image-box" style="margin-right: 0;">
                                            <figure class="image"><img style="height: 220px;" :src="article.image"  alt="MM"></figure>
                                            <div class="overlay-box">
                                                <router-link :to="{ name: 'BlogDetails', params: { id: article.id }}">
                                                    <i class="fa fa-link"></i>
                                                </router-link>
                                            </div>
                                        </div>
                                        <div class="caption-box" style="margin-top: 0; margin-left: 0; padding: 10px; box-shadow: none;">
                                            <h3 style="font-size: 14px; line-height: 20px;"><router-link :to="{ name: 'BlogDetails', params: { id: article.id }}">{{ article.title }}</router-link></h3>
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
                    </div>

                    <div class="comments-area">
                        <div class="group-title"><h2>Comments ({{article.total_comments}})</h2></div>
                        <div class="comment-box" v-if="article.comments.length > 0" v-for="(comment, commentIndex) in article.comments" :key="comment.id">
                            <div class="comment">
                                <div class="author-thumb"><img :src="comment.author_thumb" :alt="comment.name"></div> 
                                <div class="comment-info">
                                    <div class="name">{{ comment.name }}</div>
                                    <div class="date">{{ comment.comment_on }}</div>
                                </div>
                                <div class="text">{{ comment.message }}</div>
                                <a href="javaScript:;" class="reply-btn" @click="replyComment(comment.name, comment.id, commentIndex)">Reply</a>
                            </div>

                            <div v-if="comment.replies.length > 0" class="comment-box reply-comment" v-for="(reply, replyIndex) in comment.replies" :key="reply.id">
                                <div class="comment">
                                    <div class="author-thumb"><img :src="reply.author_thumb" :alt="reply.name"></div> 
                                    <div class="comment-info">
                                        <div class="name">{{ reply.name }}</div>
                                        <div class="date">{{ reply.reply_on }}</div>
                                    </div>
                                    <div class="text">{{ reply.message }}</div>
                                    <a href="javaScript:;" class="reply-btn" @click="replyComment(reply.name, comment.id, commentIndex)">Reply</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="comment-form">
                        <div class="group-title">
                            <h2>Leave a Comment</h2>
                        </div>
                        <v-form ref="leaveComments" v-model="valid" lazy-validation @submit.prevent="leaveCommentStore">
                            <v-text-field color="accent" v-model="leaveComments.name" :error-messages="display_errors.name" type="text" label="Full Name *" outlined></v-text-field>
                            <v-text-field color="accent" v-model="leaveComments.phone"  prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" label="Phone Number *" outlined></v-text-field>
                            <v-textarea style="margin-top:15px" color="accent" v-model="leaveComments.comments" :error-messages="display_errors.comments" label="Write your comment *" placeholder="Write your comment"></v-textarea>
                            <v-btn color="primary" type="submit">Leave a comment</v-btn>
                        </v-form>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-side" style="width: 330px;">
            <hooper v-if="advertisements.length != 0" style="height:auto; width: 330px;" :settings="hooperSettingAdver">
                <slide v-for="(advertisement,i) in advertisements" :key="i">
                    <a :href="advertisement.url" target="_blank">
                        <img :src="advertisement.image" alt="MM">
                    </a>
                </slide>
            </hooper>
        </div>
        <v-dialog v-model="commentReplyDialog" max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Reply to {{ reply_to }}</span>
                </v-card-title>
                <v-card-text>
                    <v-form ref="commentReply" v-model="valid" lazy-validation @submit.prevent="commentReplyStore">
                        <v-text-field color="accent" v-model="commentReply.name" :error-messages="display_errors.name" type="text" label="Full Name" outlined></v-text-field>
                        <v-text-field color="accent" v-model="commentReply.phone"  prefix="+88" placeholder="01XXXXXXXXX" :error-messages="display_errors.phone" type="number" label="Phone Number" outlined></v-text-field>
                        <v-textarea style="margin-top:15px" color="accent" v-model="commentReply.comments" :error-messages="display_errors.comments" label="Write your comment" placeholder="Write your comment"></v-textarea>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="commentReplyDialog = false">Close</v-btn>
                    <v-btn color="primary" @click="commentReplyStore()">Reply</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
    import {
        required, numeric
    }
    from "vuelidate/lib/validators";
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
                commentReplyDialog: false,
                isDataLoad: false,
                valid: null,
                display_errors: [],
                article: {
                    title: "",
                    url: "",
                },
                articles: [],
                commentReply: {
                    name: null,
                    phone: null,
                    comments: null,
                    comment_id: null,
                },
                instanceReplyIndex: null,
                leaveComments: {
                    name: null,
                    phone: null,
                    comments: null,
                },
                networks: [
                    { network: 'facebook', name: 'Facebook', icon: 'fa fa-facebook', color: '#3b5998' },
                    { network: 'linkedin', name: 'LinkedIn', icon: 'fa fa-linkedin', color: '#007bb5' },
                    { network: 'twitter', name: 'Twitter', icon: 'fa fa-twitter', color: '#1da1f2' },
                ],
                reply_to: "",
                alertMessage: null,
                snackbar: false,
                advertisements: [],
                hooperSettingAdver: {
                    itemsToShow: 1,
                    centerMode: true,
                    wheelControl: false,
                    infiniteScroll: true,
                    autoPlay: true,
                    playSpeed: 10000,
                    transition: 1000
                },
            };
        },
        methods: {
            async getArticleDetails() {
                let id = this.$route.params.id;
                let response = await axios.get('/article/' + id);
                this.article = response.data.data;
            },
            async leaveCommentStore() {
                if (this.$refs.leaveComments.validate()) {
                    this.display_errors = [];
                    let id = this.$route.params.id;
                    
                    try {
                        let response = await axios.post("/leave_comment/" + id + "", this.leaveComments);
                        this.alertMessage = response.data.message;
                        this.article.total_comments = response.data.total_comments;
                        this.article.comments.push(response.data.comment);
                        this.snackbar = true;
                        this.leaveComments = {
                            name: null,
                            phone: null,
                            comments: null,
                        };
                        this.commentReplyDialog = false;
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
            async commentReplyStore() {
                if (this.$refs.commentReply.validate()) {
                    this.display_errors = [];
                    let id = this.$route.params.id;

                    try {
                        let response = await axios.post("/leave_comment/" + id + "", this.commentReply);
                        this.alertMessage = response.data.message;
                        // alert(this.instanceReplyIndex);
                        this.article.comments[this.instanceReplyIndex].replies.push(response.data.comment);
                        this.snackbar = true;
                        this.commentReply = {
                            comment_id: this.comment_id,
                            name: null,
                            phone: null,
                            comments: null,
                        };
                        this.commentReplyDialog = false;
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
            replyComment(name, id, replyIndex)
            {
                this.instanceReplyIndex = replyIndex;
                this.reply_to = name;
                this.commentReplyDialog = true;
                this.commentReply.comment_id = id;
                this.commentReply.comments = '@'+name+' ';
            },
            async getArticles() {
                let articles = await axios.get("/recent/articles");
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
            async getAdvertisement() {
                let advertisements = await axios.get("/advertisement/blog");
                this.advertisements = advertisements.data.data;
            },
        },
        watch: {
            $route(to, from) {
                this.getArticleDetails();
            }
        },
        created() {
            this.getArticleDetails();
            this.getArticles();
            this.getAdvertisement();
        },
        mounted() {
            window.addEventListener('scroll', this.scrolling);
        }
    };
</script>

<style>
.share-option a{
    margin-right: 6px;
    font-size: 14px;
    line-height: 25px;
    color: #fff !important;
    padding: 10px 20px;
    border-radius: 30px;
}
.share-option a span{
    margin-left: 10px;
}
</style>