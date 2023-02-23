import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import 'bootstrap/dist/css/bootstrap.css'
import 'datatables.net-bs5/css/dataTables.bootstrap5.css'
import 'datatables.net-bs5/js/dataTables.bootstrap5.js'
import 'datatables.net/js/jquery.dataTables.js'
import LoginView from './components/LoginView.vue'
import ListarClientes from './components/ListarClientes.vue'
import ListarPolizas from './components/ListarPolizas.vue'
import CrearCliente from './components/CrearCliente.vue'
import DetallesCliente from './components/DetallesCliente.vue'
import CrearPoliza from './components/CrearPoliza.vue'
import EditarPoliza from './components/EditarPoliza.vue'
import EditarCliente from './components/EditarCliente.vue'

Vue.use(VueRouter)

Vue.config.productionTip = false

const router = new VueRouter({
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      meta: { guest: true }
    },
    {
      path: '/listar',
      name: 'listar',
      component: ListarClientes,
      meta: { requiresAuth: true }
    },
    {
      path: '/listarPolizas',
      name: 'listarPolizas',
      component: ListarPolizas,
      meta: { requiresAuth: true }
    },
    {
      path: '/detalles',
      name: 'detalles',
      component: DetallesCliente,
      meta: { requiresAuth: true }
    },
    {
      path: '/crear',
      name: 'crear',
      component: CrearCliente,
      meta: { requiresAuth: true }
    },
    {
      path: '/crearPoliza',
      name: 'crearPoliza',
      component: CrearPoliza,
      meta: { requiresAuth: true }
    },
    {
      path: '/editarPoliza',
      name: 'editarPoliza',
      component: EditarPoliza,
      meta: { requiresAuth: true }
    },
    {
      path: '/editarCliente',
      name: 'editarCliente',
      component: EditarCliente,
      meta: { requiresAuth: true }
    },
  ]
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => !record.meta.guest)) { // Modifica la condición para que permita el acceso a rutas con meta campo 'guest'
    if (!localStorage.getItem('estoyLogueado')) {
      next({
        path: '/',
        query: { redirect: to.fullPath }
      })
    } else {
      next()
    }
  } else {
    next()
  }
})

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
