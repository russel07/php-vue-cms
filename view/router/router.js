import Home from '../components/home.js';

const routes = [
    { path: '/', name:'default', component: Home }
];

const router = new VueRouter({
    routes
});

export default router;
