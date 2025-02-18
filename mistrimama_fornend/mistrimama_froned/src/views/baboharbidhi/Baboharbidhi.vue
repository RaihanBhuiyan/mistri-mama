<template> 
    <v-container fluid style=""> 
        <v-card color="primary" style="margin-bottom:30px" > 
            <v-expansion-panel class="accordion-box" style="padding:0; box-shadow:none">
                <div class="inner-column baboharbidi"> 
                    <v-expansion-panel class="accordion-box" style="padding:0; box-shadow:none">
                        <v-expansion-panel-content v-for="(faq,i) in baboharbidhi" :key="i">
                        <template v-slot:header>
                            <div class="subheading">{{ faq.title }}</div>
                        </template>
                        <v-card>
                            <iframe v-if="faq.file_name" :src="file_path+faq.file"   
                    width="100%" height="100%" frameborder="0" allowfullscreen="" style="width: 100%;height: 400px;border: none; top:0; left: 0;  z-index: 1;" 
                    ></iframe>
                        </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </div>
            </v-expansion-panel>
        </v-card>
     </v-container>  

    <!-- <section class="faq-section"> 
        <div class="auto-container">
            <div class="accordion-column">
                <div class="inner-column">
                    <div class="sec-title">
                        <span class="float-text">some FAQ’s</span>
                        <h2>Frequality Asked Questions</h2>
                    </div>
                    <v-expansion-panel class="accordion-box" style="padding:0; box-shadow:none">
                        <v-expansion-panel-content v-for="(faq,i) in baboharbidhi" :key="i">
                        <template v-slot:header>
                            <div class="subheading">{{ faq.title }}</div>
                        </template>
                        <v-card>
                            <v-card-text class="grey lighten-3">{{ faq.discription }}</v-card-text>
                        </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </div>
            </div>
        </div>
    </section>  -->
  
        <!--    
            <v-expansion-panel class="accordion-box" style="padding:0; box-shadow:none">
                <v-expansion-panel-content  >
                <template v-slot:header>
                    <div class="subheading">{{ faq.title }}</div>
                </template>
                <v-card>
                    <v-card-text class="grey lighten-3">
                    <iframe v-if="faq.file_name" :src="file_path+faq.file"  

                    width="100%" height="100%" frameborder="0" allowfullscreen="" style="width: 100%;height: 400px;border: none; top:0; left: 0;  z-index: 1;" 
                    ></iframe>
                    </v-card-text>
                </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
             
        </v-card>  -->
   

</template>

<script>
import { mapState } from "vuex";
import axios from "../../axios_instance";  
    if((location.host == 'localhost:8080') || (location.host == '127.0.0.1:8080')){
        var baseURL = "https://staging.mistrimama.com/";
    } else{
        var baseURL =  "https://"+location.host+"/";
    }

export default {
    data() {
        return {
            snackbar: null,
            alertMessage: null,
            baboharbidhi: [],
            file_path: baseURL
        };
    },
    methods: {
        async getBaboharbidhi() {
            let loader = this.$loading.show();
            let response = await axios.get("/baboharbidhi"); 
            this.baboharbidhi = response.data.data;
            loader.hide();
        }
    },
    created() { 
        this.getBaboharbidhi();
    }
};
</script>
<style>
    .baboharbidi .v-expansion-panel__header { 
        color: #fff;
        background-color: #febe00;
    }
     
    .v-expansion-panel {
        display: inline !important;
    }
</style>