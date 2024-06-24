/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import VueRouter from "vue-router";
import axios from "axios";
import "./bootstrap";
import HomeView from "./components/HomeView.vue";
import EditPointView from "./components/EditPointView.vue";
import NewPointView from "./components/NewPointView.vue";

window.Vue = require("vue").default;
Vue.use(VueRouter);
Vue.prototype.$http = axios;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component(
  "relative-points",
  require("./components/RelativePoints.vue").default
);

Vue.component(
  "points-table",
  require("./components/PointsTable.vue").default
)
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
  mode: "history",
  routes: [
    { path: "/", component: HomeView },
    { path: "/point/:id", name: "EditPoint", component: EditPointView, props: true },
    { path: "/newPoint", name: "NewPoint", component: NewPointView },
  ],
});

const app = new Vue({
  el: "#app",
  router,
});
