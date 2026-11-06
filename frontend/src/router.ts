import { createRouter, createWebHistory } from 'vue-router'
import { sessao } from './api'
import LoginView from './views/LoginView.vue'
import CadastroView from './views/CadastroView.vue'
import ReceitasView from './views/ReceitasView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/receitas' },
    { path: '/login', component: LoginView, meta: { publica: true } },
    { path: '/cadastro', component: CadastroView, meta: { publica: true } },
    { path: '/receitas', component: ReceitasView },
  ],
})

router.beforeEach((to) => {
  if (!to.meta.publica && !sessao.token) return '/login'
  if (to.meta.publica && sessao.token) return '/receitas'
})

export default router
