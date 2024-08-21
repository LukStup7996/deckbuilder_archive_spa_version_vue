<!-- eslint-disable vue/return-in-computed-property -->
<script>
import { useDeckArchiveApiStore } from "../stores/deckArchiveApiStore";

export default {
  name: "deck-archive",
  data() {
    return {
      decks: [],
      deckArchiveApi: useDeckArchiveApiStore(),
      filterOptions: [
        { text: "Deck Name", value: "deck-name" },
        { text: "Format", value: "format" },
      ],
      selectedFilter: "deck-name",
      formatOptions: [
        { text: "Standard", value: "Standard" },
        { text: "Modern", value: "Modern" },
        { text: "Pauper", value: "Pauper" },
        { text: "Frontier", value: "Frontier" },
        { text: "Commander", value: "Commander" },
        { text: "Highlander", value: "Highlander" },
        { text: "Legacy", value: "Legacy" },
        { text: "Vintage", value: "Vintage" },
      ],
      selectedFormat: "Standard",
      inputValue: "",
    };
  },
  computed: {
    submitFilterOptions() {
      console.log(this.selectedFormat);
      switch (this.selectedFilter) {
        case "deck-name":
          this.filterByDeckName(this.inputValue);
          break;
        case "format":
          this.filterByFormat(this.selectedFormat);
          break;
        default:
          this.filterAllLists();
          break;
      }
    },
  },
  methods: {
    filterAllLists() {
      this.deckArchiveApi.getAllLists().then(() => {
        this.decks = this.deckArchiveApi.decks;
      });
    },
    filterByDeckName(deckName) {
      this.deckArchiveApi.filterByDeckName(deckName).then(() => {
        this.decks = this.deckArchiveApi.decks;
      });
    },
    filterByFormat(format) {
      console.log(format);
      this.deckArchiveApi.searchByFormat(format).then(() => {
        this.decks = this.deckArchiveApi.decks;
      });
    },
  },
};
</script>

<template>
  <div class="input-group mb-3">
    <div class="input-group-prepend" id="filter-options">
      <button
        class="btn btn-outline-secondary dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        Filter by:
      </button>
      <ul class="dropdown-menu">
        <li v-for="option in filterOptions" :key="option.value">
          <input
            type="radio"
            class="form-check-input"
            :id="option.value"
            name="filter-options"
            v-model="selectedFilter"
            :value="option.value"
          />
          <label class="form-check-label" :for="option.value">{{
            option.text
          }}</label>
        </li>
      </ul>
    </div>
    <p>{{ selectedFilter }}</p>
    <div v-if="selectedFilter === 'deck-name'">
      <input
        type="text"
        class="form-control"
        v-model="inputValue"
        aria-label="Text input with dropdown button"
        id="submit-filter"
      />
      <button
        type="button"
        class="btn btn-primary"
        @click="submitFilterOptions"
      >
        Search
      </button>
    </div>
    <div v-else>
      <div class="input-group-prepend" id="format-options">
        <button
          class="btn btn-outline-secondary dropdown-toggle"
          type="button"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          Format:
        </button>
        <ul class="dropdown-menu">
          <li v-for="format in formatOptions" :key="format.value">
            <input
              type="radio"
              class="form-check-input"
              :id="format.value"
              name="filter-options"
              v-model="selectedFormat"
              :value="format.value"
            />
            <label class="form-check-label" :for="format.value">{{
              format.text
            }}</label>
          </li>
        </ul>
        <button
          type="button"
          class="btn btn-primary"
          @click="submitFilterOptions"
        >
          Search
        </button>
      </div>
    </div>
  </div>
  <div class="container sm-8" name="deck display">
    <div class="card-group" style="width: 72rem">
      <div
        v-for="deck in decks"
        :key="deck.deckId"
        class="card"
        style="width: 18rem"
      >
        <div class="card-body">
          <h5 class="card-title">Deck Name: {{ deck.deckName }}</h5>
          <p class="card-text">Format: {{ deck.format }}</p>
          <p class="card-text">Deck-ID: {{ deck.deckId }}</p>
          <router-link
            :to="{
              name: 'deck-content-display',
              params: {
                deckId: deck.deckId,
                deckName: deck.deckName,
                deckFormat: deck.format,
              },
            }"
          >
            <button class="btn btn-primary">Additional Info</button>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.input-group {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
}

#filter-options {
  flex-shrink: 0;
  margin-left: 10px;
}

#submit-filter {
  flex-grow: 1;
}

input[type="text"] {
  flex-grow: 2;
}

.btn-primary {
  flex-grow: 0;
  white-space: nowrap;
}

#format-options {
  display: flex;
  align-items: center;
  flex-grow: 1;
  gap: 10px;
}

#format-options .dropdown-toggle {
  flex-shrink: 0;
}

.format-display {
  display: inline-block;
  padding: 5px 10px;
  border: 2px solid black;
  border-radius: 5px;
  margin-left: 10px;
  white-space: nowrap;
}

.card-group {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: center;
  align-items: flex-start;
  gap: 20px;
  margin-top: 6px;
  margin-bottom: 20px;
}

.card {
  flex: 1 1 calc(33.333% - 20px);
  margin-bottom: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
}

.card-body {
  padding: 15px;
}

.card-title {
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.card-text {
  margin-bottom: 5px;
}
</style>
