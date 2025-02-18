import { localStorageService } from "../helper.js";
import axios from "../axios_instance.js";

export default {
  state: {
    // = data
    userInformation: []
  },
  getters: {
    // = computed
    getCurrentUserData: state => {
      return state.userInformation;
    }
  },
  actions: {
    // = methods : make api call : use methods used in mutations from actions to update the state : dnt update state from here, always use mutations
    getCurrentUserData({ commit }) {
      commit("setUserInfo");
    },
    async userRegistration({ commit }, formData) {
      try {
        let response = await axios.post("/client", formData, {
          headers: {
            "Content-Type": "application/json"
          }
        });
        console.log("RESPONSE: ", response);
        return { status: "success", data: response.data };
      } catch (err) {
        return { status: "failure", data: err };
      }
    },
    async changePassword({ commit }, payload) {
      try {
        let response = await axios.patch("", {
          oldPassword: payload.oldPassword,
          newPassword: payload.newPassword
        });
        return { status: "success", data: response.data };
      } catch (err) {
        return { status: "failure", data: err };
      }
    }
  },
  mutations: {
    // = methods used for updating states
    setUserInfo(state, payload) {
      state.userInformation = payload.afterLoginUserData;
      localStorageService.setItem(
        "currentUserData",
        payload.afterLoginUserData
      );
      localStorageService.setItem("d_token", payload.d_token);
      // console.log(localStorageService.getItem('currentUserData'));
    },
    setUserInfoFromLocalstorage(state) {
      state.userInformation = localStorageService.getItem("currentUserData");
      // alert(JSON.stringify(state));
    }
  }
};
