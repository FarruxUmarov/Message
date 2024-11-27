// router.js
import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const routes = [
    { path: '/user/:id', component: UserComponent },
];

const router = new Router({
    routes
});

export default router;
