<script setup lang="ts">
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import { api, limparSessao, sessao } from './api'

const router = useRouter()

async function sair() {
  const resposta = await Swal.fire({
    title: 'Deseja sair?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sim, sair',
    cancelButtonText: 'Cancelar',
  })

  if (!resposta.isConfirmed) return

  try {
    await api.post('/logout')
  } finally {
    limparSessao()
    router.push('/login')
  }
}
</script>

<template>
  <nav class="navbar navbar-dark bg-dark sticky-top mb-4 d-print-none">
    <div class="container">
      <RouterLink class="navbar-brand" to="/"><img src="/logo.avif" alt="A4PM" height="40" /></RouterLink>
      <div v-if="sessao.token" class="d-flex align-items-center gap-3">
        <span class="text-light">Olá, {{ sessao.nome }}</span>
        <button class="btn btn-outline-light btn-sm" @click="sair">Sair</button>
      </div>
    </div>
  </nav>

  <main class="container pb-4">
    <RouterView />
  </main>
</template>
