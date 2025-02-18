<template>
    <v-card elevation="0" v-if="orders.length != 0">
        <v-card-actions>
            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
        </v-card-actions>
        <v-data-table :headers="ordersHeaders" :items="orders" :search="search" class="elevation-0">
            <template v-slot:items="props">
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{ props.item.order_no }}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{ props.item.category_name }}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{ props.item.name }}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{ props.item.area }}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{ props.item.address }}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{props.item.date}}/{{props.item.time}}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{props.item.comrade_name}}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{props.item.comrade_phone}}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{props.item.state = 1 ? 'সহকারি এলোকেটেড' : props.item.state = 2 ? 'কাজ চলছে' : props.item.state = 3 ? ' পেমেন্টের জন্য অপেক্ষামান' : '' }}</td>
                <td class="text-xs-left" style="cursor: pointer" @click="openDrawer(props.item)">{{ props.item.carbs }}</td>
                <td class="text-xs-right" style="cursor: pointer">
                    <v-menu bottom left>
                        <template v-slot:activator="{ on }">
                            <v-icon v-on="on">more_vert</v-icon>
                        </template>
                        <v-list>
                            <v-list-tile @click="openDrawer(props.item)">
                                <v-list-tile-title>বিস্তারিত</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click="openDrawer(props.item, 'paymentshongroho')">
                                <v-list-tile-title>পেমেন্ট সংগ্রহ</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click="openDrawer(props.item, 'shohokariporiborton')">
                                <v-list-tile-title>সহকারী পরিবর্তন</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click="openDrawer(props.item, 'notunservicejog')">
                                <v-list-tile-title>নতুন সার্ভিস যোগ</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                </td>
            </template>
        </v-data-table>
        <v-dialog v-model="drawerBistarito" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="drawerBistarito = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>বিস্তারিত</v-toolbar-title>
                </v-toolbar>
                <v-layout>
                    <v-flex md6>
                        <v-list subheader three-line>
                            <v-subheader>কাস্টমার এর তথ্য</v-subheader>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-list-tile-title>নাম {{ dialogObject.name }}</v-list-tile-title>
                                    <v-list-tile-sub-title>ফোন {{ dialogObject.phone }}</v-list-tile-sub-title>
                                    <v-list-tile-sub-title>এলাকা {{ dialogObject.area }}, {{ dialogObject.address }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                    <v-flex md6>
                        <v-list subheader three-line>
                            <v-subheader>সহকারী এর তথ্য</v-subheader>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-list-tile-title>নাম {{ dialogObject.comrade_name }}</v-list-tile-title>
                                    <v-list-tile-sub-title>ফোন {{ dialogObject.comrade_phone }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                </v-layout>
                <v-data-table :headers="headerBistarito" :items="dialogObject.items" hide-actions>
                    <template v-slot:items="props">
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_bit_name }}</td>
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.price }}</td>
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.quantity }}</td>
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.total_price }}</td>
                        <td class="text-xs-right" style="cursor: pointer">{{ (props.item.status == 1) ? 'শেষ হয়েছে' : 'এখনো বাকি আছে' }}</td>
                    </template>
                </v-data-table>
            </v-card>
        </v-dialog>
        <v-dialog v-model="drawerShohokari" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="drawerShohokari = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>সহকারী পরিবর্তন</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click="drawerShohokari = false">Save</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-list subheader two-line>
                    <v-subheader>কাস্টমার এর তথ্য</v-subheader>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>নাম {{ dialogObject.name }}</v-list-tile-title>
                            <v-list-tile-sub-title>ফোন {{ dialogObject.phone }}</v-list-tile-sub-title>
                            <v-list-tile-sub-title>এলাকা {{ dialogObject.area }}, {{ dialogObject.address }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider style="margin:0"></v-divider>
                <v-list subheader two-line>
                    <v-subheader>সহকারী নির্বাচন করুন</v-subheader>
                    <v-radio-group :mandatory="true" v-model="service">
                        <template v-for="comrade in itemsService">
                            <v-list-tile @click="checkedComrade(comrade)">
                                <v-list-tile-action>
                                    <v-radio :value="comrade" :key="comrade"></v-radio>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ comrade }}</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>
                    </v-radio-group>
                </v-list>
            </v-card>
        </v-dialog>
    </v-card>
    
    <v-card elevation="0" v-else> 
        <v-card-text style="text-align:center">You have no order.</v-card-text>
    </v-card>
</template>

<script>
import axios from "../../axios_instance";
export default {
  props: {
    role: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      orders: [],
      drawerBistarito: null,
      drawerShohokari: null,
      desserts: [],
      search: null,
      service: null,
      itemsService: ["Sami", "Mamun", "Shoibal"],
      dialogObject: [],
      ordersHeaders: [
        { text: "অর্ডার নং", align: "left", sortable: false, value: "order_no"},
        { text: "সার্ভিস", value: "category_name" },
        { text: "অর্ডারকারী", value: "name" },
        { text: "এরিয়া", value: "area" },
        { text: "ঠিকানা", value: "address" },
        { text: "তারিখ/সময়", value: "data/time" },
        { text: "সহকারী", value: "comrade_name" },
        { text: "সহকারী ফোন", value: "comrade_phone" },
        { text: "অবস্থা", value: "state" },
        { text: "অ্যাকশন", value: "action" }
      ],
      headerBistarito: [
        { text: "সার্ভিস", align: "left", sortable: false, value: "name" },
        { text: "সার্ভিস মূল্য", value: "calories" },
        { text: "পরিমাণ", value: "fat" },
        { text: "মোট মূল্য", value: "carbs" },
        { text: "অ্যাকশন", value: "iron", align: "center" }
      ]
    };
  },
  methods: {
    checkedComrade(comrade){
        this.drawerComradeData.selectedComrade = comrade.id;
    },
    openDrawer: function(itemObject, drawerName) {
      this.dialogObject = itemObject;
      switch (drawerName) {
        case "paymentshongroho":
          this.drawerShohokari = !this.drawerShohokari;
          break;
        case "shohokariporiborton":
          this.drawerShohokari = !this.drawerShohokari;
          break;
        case "notunservicejog":
          this.drawerShohokari = !this.drawerShohokari;
          break;
        default:
          this.drawerBistarito = !this.drawerBistarito;
          break;
      }
    },
    async getOrders() {
      let response = await axios.get("phone-order");
      this.orders = response.data.data;
    }
  },
  created() {
    this.getOrders();
  }
};
</script>

<style  scoped>
</style>
