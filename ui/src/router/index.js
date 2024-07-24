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
    component: () => import("../views/ImprintPage.vue"), // Verwende das importierte ImprintPage
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
