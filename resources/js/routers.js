import Vue from 'vue'
import VueRouter from 'vue-router'
import methods from './components/pages/basic/methods'

import usecom from './vuex/usecom'
// admin pages
import home from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'
import createBlog from './admin/pages/createBlog'
import blogs from './admin/pages/blogs'
import editblog from './admin/pages/editBlog'
import notfound from './admin/pages/notfound'

Vue.use(VueRouter)
const routes = [
    {
        path: '/testvuex',
        component: usecom
    },
    {
        path: '/',
        component: home,
        name: 'home'
    },
    {
        path: '/tags',
        component: tags,
        name: 'tags'
    },
    {
        path: '/category',
        component: category,
        name: 'category'
    },
    {
        path: '/createBlog',
        component: createBlog,
        name: 'createBlog'
    },
    {
        path: '/adminusers',
        component: adminusers,
        name: 'adminusers'
    },
    {
        path: '/login',
        component: login,
        name: 'login'
    },
    {
        path: '/role',
        component: role,
        name: 'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name: 'assignRole'
    },
    {
        path: '/blogs',
        component: blogs,
        name: 'blogs'
    },
    {
        path: '/editblog/:id',
        component: editblog,
        name: 'editblog'
    },
    {
        path: 'notfound',
        component: notfound,
        name: 'notfound'
    },






    {
        path: '/method',
        component: methods
    }
]

const router = new VueRouter({
    mode:'history',
    routes
})

export default router;
