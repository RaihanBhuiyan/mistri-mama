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
                    <v-flex md5 sm5 xs12 class="outer-bound">
                      <v-layout class="custom-height">
                        <v-flex md6 sm6 xs6 style="text-align: left !important;">
                          <h3 style="font-size: 20px;">ব্যালান্স</h3>
                          <p style="color: grey; font-size: 16px;">উত্তোলনযোগ্য ৳ 2944/-</p>
                        </v-flex>
                        <v-flex md6 sm6 xs6>
                          <h3 style="text-align: right !important; font-size: 30px;">৳ 3487/-</h3>
                        </v-flex>
                      </v-layout>
                      <v-layout>
                        <v-flex md12 sm12 xs12>
                          <v-btn
                            style="width: 100%;"
                            @click="drawerBalance = !drawerBalance;"
                          >ক্যাশ আউট করুন</v-btn>
                        </v-flex>
                      </v-layout>
                    </v-flex>
                    <v-flex md5 sm5 xs12 class="outer-bound">
                      <v-layout class="custom-height">
                        <v-flex style="text-align: left !important;">
                          <h3 style="font-size: 20px;">সহকারীর সংখ্যা</h3>
                          <p style="color: grey; font-size: 16px;">একটিভ - 6 / একটিভ নয় - 2</p>
                        </v-flex>
                        <v-flex>
                          <h3 style="text-align: right !important; font-size: 30px;">13</h3>
                        </v-flex>
                      </v-layout>
                      <v-layout>
                        <v-flex md12 sm12 xs12>
                          <v-btn style="width: 100%;" @click="goto('/shohokari')">সহকারীর বিস্তারিত</v-btn>
                        </v-flex>
                      </v-layout>
                    </v-flex>
                    <v-flex md2 sm2 xs12 class="outer-bound">
                      <h3 style="font-size: 20px; text-align: left">রেটিং</h3>
                      <h3 style="padding-top: 13px; color: var(--accent); font-size: 50px;">4.3</h3>
                    </v-flex>
                    <v-flex md12 sm12 xs12 class="outer-bound">
                      <div class="chart_wrapper pb-50">
                        <bar-chart
                          :dailyData="dataday"
                          :monthData="datamonth"
                          :dailyOrMonthly="dailyOrMonthly"
                          :options="options"
                        ></bar-chart>
                      </div>
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
              <v-flex md6 sm6 xs12>
                <Mulmenu_Lenden/>
              </v-flex>
              <v-flex md6 sm6 xs12>
                <Mulmenu_NotunKaaj/>
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
                  <h3 class="custom-title">আপনার সার্ভিস সমূহ</h3>
                  <v-layout wrap style="text-align: center !important">
                    <v-flex md4 sm4 xs6>
                      <h3 class="services">ইলেকট্রিকাল</h3>
                    </v-flex>
                    <v-flex md4 sm4 xs6>
                      <h3 class="services">প্লাম্বিং</h3>
                    </v-flex>
                    <v-flex md4 sm4 xs6>
                      <h3 class="services">এসি</h3>
                    </v-flex>
                    <v-flex md4 sm4 xs6>
                      <h3 class="services">জেনারেটার</h3>
                    </v-flex>
                    <v-flex md4 sm4 xs6>
                      <h3 class="services">আই টি</h3>
                    </v-flex>
                    <v-flex md4 sm4 xs6>
                      <h3 class="services">সিসিটিভি</h3>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
            </v-layout>
          </div>
        </v-flex>
        <!-- <v-flex lg1 md1 hidden-sm-and-down></v-flex> -->
      </v-layout>
      <DrawerBalance :drawer="drawerBalance" @clicked="closeDrawer"/>
      <v-snackbar
        v-model="snackbar"
        :bottom="'bottom'"
        :right="'right'"
        :timeout="6000"
        :vertical="'vertical'"
        style="text-align: left !important"
      >
        {{ alertMessage }}
        <v-btn color="primaryButton" flat @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import BarChart from "../../chart.js";
import Mulmenu_Lenden from "../mulmenu/Mulmenu_Lenden";
import Mulmenu_NotunKaaj from "../mulmenu/Mulmenu_NotunKaaj";
import DrawerBalance from "../mulmenu/DrawerBalance";

export default {
  components: {
    Mulmenu_Lenden,
    Mulmenu_NotunKaaj,
    DrawerBalance,
    BarChart
  },
  data() {
    return {
      dataLoaded: true,
      pageCount: 1,
      dataLoaded: false,
      drawerBalance: null,
      pagination: null,
      snackbar: null,
      alertMessage: null,
      ownerSearch: null,
      headersLenden: [
        {
          text: "তারিখ",
          align: "left",
          // sortable: false,
          value: "name"
        },
        { text: "বিস্তারিত", value: "calories" },
        { text: "TXN ID", value: "fat" },
        { text: "পরিমান", value: "carbs" }
      ],
      desserts: [],
      sideData: [],
      items: [
        { title: "Home", icon: "dashboard" },
        { title: "About", icon: "question_answer" }
      ],
      dailyOrMonthly: "daily",
      dataday: {
        labels: [
          "Saturday",
          "Sunday",
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday"
        ],
        datasets: [
          {
            label: "Number of Transection",
            backgroundColor: "#febe00",
            data: [6, 44, 82, 13, 23, 57, 59]
          },
          {
            label: "Volume of Transected Money",
            backgroundColor: "#222222",
            data: [54, 6, 82, 85, 25, 56, 79]
          },
          {
            label: "Number of Users",
            backgroundColor: "#ed1c24",
            data: [15, 32, 39, 37, 35, 18, 7]
          }
        ]
      },
      datamonth: {
        labels: [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "May",
          "Jun",
          "Jul",
          "Aug",
          "Sept",
          "Oct",
          "Nov",
          "Dec"
        ],
        datasets: [
          {
            label: "Number of Transection",
            backgroundColor: "#f87979",
            data: [28, 78, 1, 17, 96, 75, 47, 42, 2, 7, 44, 13]
          },
          {
            label: "Volume of Transected Money",
            backgroundColor: "#41B883",
            data: [42, 27, 63, 61, 56, 57, 50, 87, 35, 53, 32, 90]
          },
          {
            label: "Number of Users",
            backgroundColor: "#35495E",
            data: [37, 99, 13, 15, 83, 86, 56, 14, 50, 64, 35, 52]
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
                fontFamily: "'Montserrat', sans-serif"
              }
            }
          ],
          xAxes: [
            {
              ticks: {
                beginAtZero: true,
                fontFamily: "'Montserrat', sans-serif"
              }
            }
          ]
        },
        legend: {
          labels: {
            fontFamily: "'Montserrat', sans-serif"
          }
        }
      }
    };
  },
  methods: {
    closeDrawer: function(value) {
      this.drawerBalance = false;
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
  padding: 8px;
  margin: 15px;
}
.services {
  border: 1px solid #febe00;
  font-size: 16px !important;
  padding: 10px;
}
</style>
