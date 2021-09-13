import Home from '../components/home.js';
import Login from '../components/login.js';
import Admin from '../components/admin.js';
import Create from '../components/create-page.js';
import EditPage from '../components/edit-page.js';

const routes = [
    { path: '/login', name:'Login', component: Login },
    { path: '/home', name:'Home', component: Home },
    { path: '/admin', name:'Admin', component: Admin },
    { path: '/admin/create-page', name:'CreatePage', component: Create },
    { path: '/admin/view/:pageId', name:'ViewPage', component: Home },
    { path: '/admin/edit/:pageId', name:'EditPage', component: EditPage },
    { path: '/', name:'default', component: Home }
];

const router = new VueRouter({
    routes
});

export default router;
