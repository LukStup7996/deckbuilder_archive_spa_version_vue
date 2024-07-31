import { defineStore } from "pinia";
import axios from "axios";
import { getToken, saveToken, removeToken } from "@/utils/jwtHelper";

const apiUrl =
  "http://localhost/deckbuilder_archive_spa_version_vue/api/index.php?action=";

export const useArchiveuserApiStore = defineStore("archiveuserApi", {
  state: () => ({
    userData: null,
    connectSuccess: false,
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
        const response = await axios.post(
          `${apiUrl}loginarchiver&mailadress=${mailAdress}&password=${userPass}`
        );
        if (response.data.status === "success") {
          this.connectSuccess = true;
          this.userData = response.data.user;
          saveToken(response.data.token);
          axios.defaults.headers.common[
            "Authorization"
          ] = `Bearer ${response.data.token}`;
        } else {
          this.connectSuccess = false;
          this.error = response.data.message;
        }
      } catch (error) {
        this.connectSuccess = false;
        this.error = "Login failed.";
      }
    },
    async logoutUser() {
      removeToken();
      this.connectSuccess = false;
      this.userData = null;
      delete axios.defaults.headers.common["Authorization"];
    },
    async getSessionUser() {
      try {
        console.log("Getting session user");
        const token = getToken();
        if (token) {
          axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        }
        const response = await axios.get(`${apiUrl}getarchiver`);
        if (response.data.status === "success") {
          this.connectSuccess = true;
          this.userData = response.data.user;
        } else {
          this.connectSuccess = false;
          this.userData = null;
        }
      } catch (error) {
        console.error("Error getting session user:", error);
        this.connectSuccess = false;
        this.userData = null;
        this.error = "Failed to retrieve session user.";
      }
    },
    async updateUserPassword(newPassword) {
      try {
        const response = await axios.post(
          `${apiUrl}updatearchiverpassword&newpassword=${newPassword}`
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
    async updateUserName(newUserName) {
      try {
        const response = await axios.post(
          `${apiUrl}updatearchivername&newusername=${newUserName}`
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
          `${apiUrl}deletearchiver&password=${password}`
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
