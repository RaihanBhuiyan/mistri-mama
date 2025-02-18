<template>
<v-app class="main-app">
    <router-view></router-view>
</v-app>
</template>

<script>
//import { localStorageService } from "./helper.js";
export default {
    name: "App",
    data() {
        return {
            
        };
    },
    created() {
        if(navigator.onLine == false)
        {
          console.log("There is no internet connection");
        }else{
            console.log("There internet connectioned ");
        }
    },  
};
</script>
<style scoped>
.v-list--three-line .v-list__tile {
    height: auto;
}
</style>