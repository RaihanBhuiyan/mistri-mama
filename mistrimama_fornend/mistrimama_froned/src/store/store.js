import Vue from "vue";
import Vuex from "vuex";
import user_module from "../modules/user_module.js";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    userModule: user_module
  }
});
