<template>
  <div>
    <div class="custom-container">
      <v-layout row wrap style="margin-bottom: 55px;">
        <!-- <v-flex lg1 md1 hidden-sm-and-down></v-flex> -->
        <v-flex lg12 md12 sm12 xs12>
          <div
            v-if="dataLoaded"
            style="text-align: left; margin-top: 30px; margin-bottom: 20px; font-size: 40px"
          >
            <v-layout wrap align-center class="custom-gap">
              <v-flex md12 sm12 xs12 style="text-align: center !important;">
                <v-card>
                  <v-layout wrap>
                    <v-flex md4 sm4 xs12 class="outer-bound">
                      <h3 style="font-size: 20px;">চলতি কাজের সংখ্যা</h3>
                      <h3 class="header-font">17</h3>
                    </v-flex>
                    <v-flex md4 sm4 xs12 class="outer-bound">
                      <h3 style="font-size: 20px;">অপেক্ষামান কাজের সংখ্যা</h3>
                      <h3 class="header-font">3</h3>
                    </v-flex>
                    <v-flex md4 sm4 xs12 class="outer-bound">
                      <h3 style="font-size: 20px;">মোট কাজ</h3>
                      <h3 class="header-font">20</h3>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
            </v-layout>
            <v-layout
              wrap
              align-center
              class="elevation-2 custom-gap"
              style="background-color: white;"
            >
              <v-flex md12 sm12 xs12>
                <v-card class="v-card-padding" flat>
                  <v-tabs icons-and-text centered grow v-model="tab">
                    <v-tabs-slider color="accent"></v-tabs-slider>
                    <v-tab class="classic-tabs">
                      ফোন অর্ডার
                      <v-icon>local_phone</v-icon>
                    </v-tab>
                    <v-tab class="classic-tabs">
                      চলতি কাজ
                      <v-icon>av_timer</v-icon>
                    </v-tab>
                    <v-tabs-items v-model="tab">
                      <v-tab-item v-for="item in tabValue" :key="item">
                        <div v-if="item == 1">
                          <ShokolKaaj_PhoneOrder />
                        </div>
                        <div v-if="item == 2">
                          <ShokolKaajCholtiKaaj />
                        </div>
                      </v-tab-item>
                    </v-tabs-items>
                  </v-tabs>
                </v-card>
              </v-flex>
            </v-layout>
          </div>
        </v-flex>
        <!-- <v-flex lg1 md1 hidden-sm-and-down></v-flex> -->
      </v-layout>
      <v-navigation-drawer v-model="drawer" absolute temporary right style="position: fixed">
        <v-list class="pa-3" style="background-color: #febe00 !important">
          <h2 style="text-align: center; color: var(--secondary)">DETAILS</h2>
        </v-list>
        <v-list style="background-color: white !important;" class="pt-0" dense>
          <v-divider></v-divider>
          <div style="text-align: center; margin-top: 40px;">
            <v-avatar :size="170" color="grey lighten-4" style="margin-bottom: 15px;">
              <img
                class="rect-image"
                src="https://image.flaticon.com/icons/png/512/236/236832.png"
                alt="user_image"
              />
            </v-avatar>
            <br />
          </div>
          <div style="margin-top: 20px; padding: 20px;">
            <v-list-tile style="margin-bottom: 15px;">
              <v-list-tile-content>
                <label style="color: var(--accent)">Dessert Name</label>
                <v-list-tile-title style="font-size: 17px;">{{sideData.name}}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile style="margin-bottom: 15px;">
              <v-list-tile-content>
                <label style="color: var(--accent)">Calories</label>
                <v-list-tile-title style="font-size: 17px;">{{sideData.calories}}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile style="margin-bottom: 15px;">
              <v-list-tile-content>
                <label style="color: var(--accent)">Fat</label>
                <v-list-tile-title style="font-size: 17px;">{{sideData.fat}}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <div style="text-align: center; margin-top: 20px;">
              <v-btn
                @click.stop="drawer = !drawer"
                color="primary"
                style="min-width: 100px !important; margin: 5px;"
              >DONE</v-btn>
              <v-btn
                @click.stop="drawer = !drawer"
                style="min-width: 100px !important; margin: 5px;"
              >CANCEL</v-btn>
            </div>
          </div>
        </v-list>
        <div v-if="actionButtonVisibleInSidePanel" style="text-align: center">
          <v-layout>
            <v-flex md12>
              <div>
                <v-btn
                  @click.stop="drawer = !drawer"
                  color="primary"
                  style="min-width: 100px !important;"
                >CANCEL</v-btn>
              </div>
            </v-flex>
          </v-layout>
        </div>
      </v-navigation-drawer>
      <v-snackbar
        v-model="snackbar"
        :bottom="'bottom'"
        :right="'right'"
        :timeout="6000"
        :vertical="'vertical'"
        style="text-align: left !important"
      >
        {{ alertMessage }}
        <v-btn color="primary" flat @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import ShokolKaajCholtiKaaj from "../shokolkaaj/ShokolKaajCholtiKaaj";
import ShokolKaajPhoneOrder from "../shokolkaaj/ShokolKaajPhoneOrder";

export default {
  components: {
    ShokolKaajCholtiKaaj,
    ShokolKaajPhoneOrder
  },
  data() {
    return {
      dataLoaded: true,
      pageCount: 1,
      dataLoaded: false,
      drawer: null,
      pagination: null,
      snackbar: null,
      alertMessage: null,
      ownerSearch: null,
      tabValue: [1, 2],
      headers: [
        {
          text: "অর্ডার",
          align: "left",
          sortable: false,
          value: "name"
        },
        { text: "সার্ভিস", value: "calories" },
        { text: "অর্ডারকারী", value: "fat" },
        { text: "সহকারী", value: "carbs" },
        { text: "বিস্তারিত", value: "protein" },
        { text: "অবস্থা", value: "iron" }
      ],
      desserts: [],
      drawer: null,
      sideData: [],
      items: [
        { title: "Home", icon: "dashboard" },
        { title: "About", icon: "question_answer" }
      ]
    };
  },
  methods: {
    openDrawer: function(itemObject) {
      this.drawer = !this.drawer;
      this.sideData = itemObject;
    }
    // tableAction: function(data, option, isButton) {
    //   this.selectedImage = data.photo;
    //   this.rowData = data;
    //   // this.displayImage = process.env.VUE_APP_IMAGE_API_URL + data.distributor.image;
    //   this.actionButtonVisibleInSidePanel = isButton;
    //   if (option == "ownerDetails") {
    //     this.sidePanelTitle = "TENANT DETAILS";
    //     this.sideData = [
    //       { label: "Name", value: data.fullName },
    //       { label: "Address", value: data.homeAddress },
    //       { label: "Contact", value: data.phoneNumber }
    //     ];
    //   } else {
    //     this.sidePanelTitle = "OWNER DETAILS";
    //     this.sideData = [
    //       { label: "ID", value: data.id },
    //       { label: "Name", value: data.name },
    //       { label: "Address", value: data.address },
    //       { label: "Contact", value: data.contact },
    //       { label: "Region", value: data.region },
    //       { label: "City", value: data.city }
    //     ];
    //   }
    // },
    // async previous() {
    //   this.dataLoaded = false;
    //   let api =
    //     this.ownerListSelected != "Home Owner"
    //       ? "getHouseownerDataByPage"
    //       : "getHouseownerDataByPageOwnerOnly";
    //   let respo = await this.$store.dispatch(api, {
    //     currentPage: this.pageCount - 1
    //   });
    //   if (respo.status == "failure") this.errorAlerts(respo.data);
    //   this.pageCount = this.pageCount - 1;
    //   this.$router.push({ path: "/tenants", query: { page: this.pageCount } });
    //   this.arrangeData(this.dataList[this.pageCount - 1]);
    // },
    // async next() {
    //   this.dataLoaded = false;
    //   let numPage = parseInt(this.pageCount) + 1;
    //   let api =
    //     this.ownerListSelected != "Home Owner"
    //       ? "getHouseownerDataByPage"
    //       : "getHouseownerDataByPageOwnerOnly";
    //   let respo = await this.$store.dispatch(api, {
    //     currentPage: parseInt(numPage)
    //   });
    //   if (respo.status == "failure") this.errorAlerts(respo.data);
    //   this.pageCount = numPage;
    //   this.$router.push({ path: "/tenants", query: { page: numPage } });
    //   this.arrangeData(this.dataList[this.pageCount - 1]);
    // },
    // arrangeData(response) {
    //   this.tableData.data = response;
    //   this.dataLoaded = true;
    // }
  },
  watch: {
    // ownerListSelected: async function() {
    //   this.dataLoaded = false;
    //   let response;
    //   this.pageCount = 1;
    //   let api =
    //     this.ownerListSelected != "Home Owner"
    //       ? "getHouseownerDataByPage"
    //       : "getHouseownerDataByPageOwnerOnly";
    //   await this.$store.dispatch("emptyListHouseowner");
    //   response = await this.$store.dispatch(api, {
    //     currentPage: this.pageCount
    //   });
    //   if (response.status == "failure") this.errorAlerts(response.data);
    //   this.$router.push({ path: "/tenants", query: { page: this.pageCount } });
    //   this.arrangeData(this.dataList[this.pageCount - 1]);
    // }
  },
  computed: {
    // ...mapState({
    //   dataList: state => state.houseownerModule.houseownerInformation,
    //   currentPageState: state => state.houseownerModule.currentPageHouseowner,
    //   totalPage: state => state.houseownerModule.totalPageHouseowner
    // })
  },
  async mounted() {
    this.desserts = [
      {
        name: "Frozen Yogurt",
        calories: 159,
        fat: 6.0,
        carbs: 24,
        protein: 4.0,
        iron: "1%"
      },
      {
        name: "Frozen Yogurt",
        calories: 159,
        fat: 6.0,
        carbs: 24,
        protein: 4.0,
        iron: "1%"
      },
      {
        name: "Frozen Yogurt",
        calories: 159,
        fat: 6.0,
        carbs: 24,
        protein: 4.0,
        iron: "1%"
      },
      {
        name: "Ice cream sandwich",
        calories: 237,
        fat: 9.0,
        carbs: 37,
        protein: 4.3,
        iron: "1%"
      },
      {
        name: "Eclair",
        calories: 262,
        fat: 16.0,
        carbs: 23,
        protein: 6.0,
        iron: "7%"
      },
      {
        name: "Cupcake",
        calories: 305,
        fat: 3.7,
        carbs: 67,
        protein: 4.3,
        iron: "8%"
      },
      {
        name: "Gingerbread",
        calories: 356,
        fat: 16.0,
        carbs: 49,
        protein: 3.9,
        iron: "16%"
      }
    ];
    this.dataLoaded = true;
  }
};
</script>
<style  scoped>
a {
  cursor: pointer;
  color: var(--dark);
}
a:hover {
  text-decoration: underline;
  color: #febe00;
}
.custom-height {
  height: 75px;
}
.v-card-padding {
  padding: 10px;
  margin: 15px;
}
.services {
  border: 1px solid #febe00;
  font-size: 16px !important;
  padding: 10px;
}
.header-font {
  font-size: 30px;
}
</style>
