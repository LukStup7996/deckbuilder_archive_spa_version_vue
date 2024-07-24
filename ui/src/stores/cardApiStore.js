import { defineStore } from "pinia";
import axios from "axios";

const apiUrl =
  "http://localhost/deckbuilder_archive_spa_version_vue/api/index.php?action=";
export const useCardApiStore = defineStore("cardApi", {
  state: () => ({
    cards: [],
    card: [],
    connectSuccess: false,
  }),
  actions: {
    async getAllCards() {
      try {
        const response = await axios.get(apiUrl + "displayallcards");
        this.$state.cards = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.cards = [];
        this.error =
          "There has been some form of cennection issue with the database or the api.";
        console.log(error);
      }
    },
    async fetchCardDetails(cardId) {
      try {
        this.$state.card = [];
        const response = await axios.get(
          apiUrl + "filterbyid&cardid=" + cardId
        );
        this.$state.card = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.card = [];
        this.error = "We apologize we couldn't find this particular card.";
        console.log(error);
      }
    },
    async getCardsByName(cardName) {
      try {
        const response = await axios.get(
          apiUrl + "filterbyname&cardname=" + cardName
        );
        this.$state.cards = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.cards = [];
        this.error = "We apologize we couldn't find any cards by that name.";
        console.log(error);
      }
    },
    async getCardsBySuper(superType) {
      try {
        const response = await axios.get(
          apiUrl + "filterbysuper&supertype=" + superType
        );
        this.$state.cards = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.cards = [];
        this.error =
          "We apologize we couldn't find any cards with that particular super type.";
        console.log(error);
      }
    },
    async getCardsByType(cardType) {
      try {
        const response = await axios.get(
          apiUrl + "filterbytype&cardtype=" + cardType
        );
        this.$state.cards = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.cards = [];
        this.error =
          "We apologize we couldn't find any cards by with this card type.";
        console.log(error);
      }
    },
    async getCardsBySub(subType) {
      try {
        const response = await axios.get(
          apiUrl + "filterbysub&subtype=" + subType
        );
        this.$state.cards = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.cards = [];
        this.error =
          "We apologize we couldn't find any cards with that specific sub type.";
        console.log(error);
      }
    },
    async getByValue(manaValue) {
      try {
        const response = await axios.get(
          apiUrl + "filterbyvalue&manavalue=" + manaValue
        );
        this.$state.cards = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.cards = [];
        this.error =
          "We apologize we couldn't find any cards with that mana value.";
        console.log(error);
      }
    },
    async getByCost(cmc) {
      try {
        const response = await axios.get(apiUrl + "filterbycost&cmc=" + cmc);
        this.$state.cards = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.cards = [];
        this.error =
          "We apologize we couldn't find any spells with these casting costs.";
        console.log(error);
      }
    },
  },
});
