<template>
    <v-list>
        <template v-for="(notification, index) in notifications">
            <v-list-tile :key="notification.title" ripple :to="notification.link">

                <v-list-tile-content>
                    <v-list-tile-sub-title class="text--primary">{{ notification.title }}</v-list-tile-sub-title>
                </v-list-tile-content>

                <v-list-tile-action>
                    <v-list-tile-action-text>{{ notification.created_at }}</v-list-tile-action-text>
                </v-list-tile-action>

            </v-list-tile>
            <v-divider v-if="index + 1 < notifications.length" :key="index"></v-divider>
        </template>
    </v-list>
</template>


<script>
    import { localStorageService } from "../helper.js";
    import axios from "../axios_instance";

    export default {
        name: "Notifications",
        data: () => ({
            notifications: [],
        }), 
        watch: {
            
        },
        methods: {
            async getNotifications()
            {
                let response = await axios.get("/notifications");
                this.notifications = response.data.notifications;
            },
        },
        created() {
            this.getNotifications();
        },
    };
</script>