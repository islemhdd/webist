import { createRouter, createWebHashHistory } from 'vue-router'

// Routes now point to a single safe component to allow removal of the
// legacy view files without breaking the router imports.
const safeComponent = () => import('@/components/ExampleComponent.vue')

const routes = [
  { meta: { title: 'Home' }, path: '/', name: 'style', component: safeComponent },
  { meta: { title: 'Dashboard' }, path: '/dashboard', name: 'dashboard', component: safeComponent },
  { meta: { title: 'Tables' }, path: '/tables', name: 'tables', component: safeComponent },
  { meta: { title: 'Forms' }, path: '/forms', name: 'forms', component: safeComponent },
  { meta: { title: 'Profile' }, path: '/profile', name: 'profile', component: safeComponent },
  { meta: { title: 'Ui' }, path: '/ui', name: 'ui', component: safeComponent },
  { meta: { title: 'Responsive layout' }, path: '/responsive', name: 'responsive', component: safeComponent },
  { meta: { title: 'Login' }, path: '/login', name: 'login', component: safeComponent },
  { meta: { title: 'Error' }, path: '/error', name: 'error', component: safeComponent },
]

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 }
  },
})

export default router
