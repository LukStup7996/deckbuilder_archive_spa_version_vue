import { defineStore } from "pinia";
import axios from "axios";

const apiUrl =
  "http://localhost/deckbuilder_archive_spa_version_vue/api/index.php?action=";

export const useArchiveuserApiStore = defineStore("archiveuserApi", {
  state: () => ({
    userData: [],
    userName: "",
    userId: "",
    userMail: "",
    connectSuccess: false,
    loginStatus: false,
    error: null,
  }),
  actions: {
    async createUser(mailAdress, archiveName, userPass, confirm) {
      try {
        await axios.post(
          `${apiUrl}createarchiver&mailadress=${mailAdress}&username=${archiveName}&password=${userPass}&confirm=${confirm}`
        );
        await this.loginUser(mailAdress, userPass);
      } catch (error) {
        this.error = "User creation failed.";
      }
    },
    async loginUser(mailAdress, userPass) {
      try {
        const response = await axios.get(
          `${apiUrl}loginarchiver&mailadress=${mailAdress}&password=${userPass}`
        );
        if (response.data) {
          this.$state.connectSuccess = true;
          this.$state.loginStatus = true;
          this.$state.userName = response.data.user_name;
          console.log(this.userName);
          this.$state.userId = response.data.user_id;
          console.log(this.userId);
          this.$state.userMail = response.data.mail_adress;
          console.log(this.userMail);
        } else {
          this.$state.connectSuccess = false;
          this.$state.loginStatus = false;
          this.$state.userName = "";
          this.$state.userId = "";
          this.$state.userMail = "";
          this.$state.error = response.data.message;
        }
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.loginStatus = false;
        this.$state.error = "Login failed.";
      }
    },
    async logoutUser() {
      this.$state.connectSuccess = false;
      this.$state.loginStatus = false;
      this.$state.userName = "";
      this.$state.userId = "";
      this.$state.userMail = "";
      delete axios.defaults.headers.common["Authorization"];
    },
    async updateUserPassword(password, newPassword) {
      try {
        const response = await axios.post(
          `${apiUrl}updatearchiverpassword&mailadress=${this.userMail}&password=${password}&newpassword=${newPassword}`
        );
        if (response.data.status === "success") {
          this.error = null;
        } else {
          this.error = response.data.message;
        }
      } catch (error) {
        this.error = "Password update failed.";
      }
    },
    async updateUserName(password, newUserName) {
      try {
        const response = await axios.post(
          `${apiUrl}updatearchivername&mailadress=${this.userMail}&password=${password}newusername=${newUserName}`
        );
        if (response.data.status === "success") {
          this.userData.user_name = newUserName;
          this.error = null;
        } else {
          this.error = response.data.message;
        }
      } catch (error) {
        this.error = "Username update failed.";
      }
    },
    async deleteArchiver(password) {
      try {
        const response = await axios.post(
          `${apiUrl}deletearchiver&mailadress=${this.userMail}&password=${password}`
        );
        if (response.data.status === "success") {
          this.logoutUser();
        } else {
          this.error = "Cannot delete archiveuser.";
        }
      } catch (error) {
        this.error = "Deletion failed.";
      }
    },
  },
});
