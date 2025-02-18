import { localStorageService } from "./helper.js";

// if((location.host == 'localhost:8080') || (location.host == '127.0.0.1:8080')){
//   var urrentUrl = "https://staging.mistrimama.com/backend/api/";
// }else{
//   var urrentUrl = "http://mistrimama.com/backend/api/";
// } 
  if((location.host == 'localhost:8080') || (location.host == '127.0.0.1:8080')){
    //var urrentUrl = "https://staging.mistrimama.com/backend/api/";
    var urrentUrl =  "http://127.0.0.1:8000/api";
  } else {
    var urrentUrl = "https://"+location.host+"/backend/api/";
  }

const axios = require("axios");
const defaultOptions = { 
  baseURL: urrentUrl, 
  headers: {
    "Content-Type": "application/json"
  }
};
let axiosInstance = axios.create(defaultOptions);
axiosInstance.interceptors.request.use(function(config) {
  let token = localStorageService.getItem("d_token");
  config.headers.Authorization = token ? `Bearer ${token}` : "";
  return config;
});

export default axiosInstance;
