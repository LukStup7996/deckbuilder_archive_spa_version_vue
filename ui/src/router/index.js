import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

const routes = [
  {
    path: "/",
    name: "cover-page",
    component: HomeView,
  },
  {
    path: "/about",
    name: "about",
    component: () => import("../views/AboutView.vue"),
  },
  {
    path: "/imprint",
    name: "imprint",
    component: () => import("../views/ImprintPage.vue"),
  },
  {
    path: "/card-archive",
    name: "card-archive",
    component: () => import("../views/CardArchive.vue"),
  },
  {
    path: "/single-card-display/:cardId",
    name: "single-card-display",
    component: () => import("../views/DisplaySingleCard.vue"),
    props: true,
  },
  {
    path: "/deck-archive",
    name: "deck-archive",
    component: () => import("../views/DeckArchive.vue"),
  },
  {
    path: "/deck-content-display/:deckId/:deckName/:deckFormat",
    name: "deck-content-display",
    component: () => import("../views/DeckContentDisplay.vue"),
    props: true,
  },
  {
    path: "/account-creation",
    name: "account-creation",
    component: () => import("../views/AccountCreation.vue"),
  },
  {
    path: "/user-login",
    name: "user-login",
    component: () => import("../views/Login.vue"),
  },
  {
    path: "/account-settings",
    name: "account-settings",
    component: () => import("../views/AccountSettings.vue"),
  },
  {
    path: "/deck-creator",
    name: "deck-creator",
    component: () => import("../views/DeckCreator.vue"),
  },
  {
    path: "/sand-box",
    name: "sand-box",
    component: () => import("../views/SandBox.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else if (to.hash) {
      return { el: to.hash, behavior: "smooth" };
    } else {
      return { top: 0 };
    }
  },
});

export default router;
