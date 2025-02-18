<template>
    <v-container fluid>
        <v-card elevation="1">
            <v-card-title style="background-color: #febe00;">
                <h5><v-icon>group</v-icon> সহকারীদের লিস্ট</h5>
            </v-card-title>
            <v-card-actions>
                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialogNotunShohokari = true"><v-icon class="custom-icon">person_add</v-icon> </v-btn>
            </v-card-actions>
            <v-data-table :headers="comradesHeaders" :items="comrades" :search="search" v-if="comrades.length != 0">
                <template v-slot:items="props">
                    <td>
                        <v-avatar>
                            <img :src="props.item.photo_url" alt="Picture" />
                        </v-avatar>
                    </td>
                    <td @click="opendialogShohokari(props.item)">{{ props.item.name }}</td>
                    <td @click="opendialogShohokari(props.item)">{{ props.item.phone }}</td>
                    <td @click="opendialogShohokari(props.item)">{{ props.item.email }}</td>
                    <td @click="opendialogShohokari(props.item)">{{ props.item.nid_no }}</td>
                    <td @click="opendialogShohokari(props.item)">{{ props.item.approve == 1 ? 'Yes' : 'No' }}</td>
                    <td @click="opendialogShohokari(props.item)">{{ props.item.status == 1 ? 'Active' : 'Not Active' }}</td>
                    <td class="text-xs-center">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" @click="opendialogShohokariEdit(props.item)" class="icon-hover">edit</v-icon>
                            </template>
                            <span>এডিট</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" @click="shohokariDelete(props.item.user_id)" class="icon-hover" color="accent">not_interested</v-icon>
                            </template>
                            <span>বাতিল</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
            <dialog-notun-shohokari v-model="dialogNotunShohokari" :dialogitemsService="itemsService"></dialog-notun-shohokari>
            <dialog-shohokari v-model="dialogShohokari" :dialogData="dialogShohokariData"></dialog-shohokari>
            <dialog-shohokari-edit v-model="dialogShohokariEdit" :dialogData="dialogShohokariData" :dialogitemsService="itemsService"></dialog-shohokari-edit>

            <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
                {{ alertMessage }}
                <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
            </v-snackbar>
        </v-card>
    </v-container>
</template>

<script>
    import {
        mapState
    }
    from "vuex";
    import DrawerNotunShohokari from "../shohokari/DrawerNotunShohokari";
    import DrawerNotunShohokariEdit from "../shohokari/DrawerNotunShohokariEdit";
    import DrawerShohokari from "../shohokari/DrawerShohokari";
    import axios from "../../axios_instance.js";
    import {
        async
    }
    from "q";

    export default {
        components: {
            'dialog-notun-shohokari': DrawerNotunShohokari,
            'dialog-shohokari-edit': DrawerNotunShohokariEdit,
            'dialog-shohokari': DrawerShohokari
        },
        data() {
            return {
                comrades: [],
                dialogNotunShohokari: false,
                dialogShohokari: false,
                dialogShohokariEdit: false,
                snackbar: null,
                alertMessage: null,
                search: null,
                comradesHeaders: [{
                    text: "ছবি",
                    align: "left",
                    value: "photo_url"
                }, {
                    text: "নাম",
                    value: "name"
                }, {
                    text: "ফোন নম্বর",
                    value: "phone"
                }, {
                    text: "ই-মেইল",
                    value: "email"
                }, {
                    text: "এন.আই.ডি নং",
                    value: "nid_no"
                }, {
                    text: "অনুমদিত",
                    value: "approve"
                }, {
                    text: "স্টেটাস",
                    value: "protein"
                }, {
                    text: "অ্যাকশন",
                    value: "status",
                    align: "center"
                }],
                drawer: null,
                dialogShohokariData: [],
                itemsService: []
            };
        },
        methods: {
            opendialogShohokari: function(itemObject) {
                this.dialogShohokari = true;
                this.dialogShohokariData = itemObject;
            },
            opendialogShohokariEdit: function(itemObject) {
                this.dialogShohokariEdit = true;
                this.dialogShohokariData = itemObject;
            },
            async shohokariDelete(id) {
                try {
                    let response = await axios.delete('/comrade/'+id);
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.getAllComrades();
                } catch (error) {
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            },
            async getAllComrades() {
                let loader = this.$loading.show();
                let response = await axios.get('/sp/comrades');
                this.comrades = response.data.data;
                loader.hide();
            },
            async storeCategorys() {
                let response = await axios.get("/category");
                this.itemsService = response.data.data;
            }
        },
        watch: {},
        computed: {},
        created() {
            this.getAllComrades();
            this.storeCategorys();
        }
    };
</script>