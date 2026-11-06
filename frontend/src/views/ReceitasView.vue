<script setup lang="ts">
import { onMounted, ref } from 'vue'
import Swal from 'sweetalert2'
import { api, mensagemErro, type Categoria, type Pagina, type Receita } from '@/api'
import ReceitaModal from '@/components/ReceitaModal.vue'

const categorias = ref<Categoria[]>([])
const pagina = ref<Pagina<Receita>>()
const filtros = ref({ nome: '', id_categorias: '' })
const receitaAberta = ref<Receita>()
const formAberto = ref(false)
const receitaEditada = ref<Receita>()

function avisarSucesso(texto: string) {
  Swal.fire({ title: texto, icon: 'success', timer: 1500, showConfirmButton: false })
}

function avisarErro(e: unknown) {
  Swal.fire({ title: 'Erro', text: mensagemErro(e), icon: 'error' })
}

async function carregar(numero = 1) {
  try {
    const { data } = await api.get('/receitas', { params: { ...filtros.value, page: numero } })
    pagina.value = data
  } catch (e) {
    avisarErro(e)
  }
}

async function excluir(receita: Receita) {
  const resposta = await Swal.fire({
    title: 'Tem certeza?',
    text: `A receita "${receita.nome}" será excluída.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sim, excluir',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#dc3545',
  })

  if (!resposta.isConfirmed) return

  try {
    await api.delete(`/receitas/${receita.id}`)
    avisarSucesso('Receita excluída com sucesso!')
    carregar(pagina.value?.current_page)
  } catch (e) {
    avisarErro(e)
  }
}

function abrirForm(receita?: Receita) {
  receitaEditada.value = receita
  formAberto.value = true
}

function salvo() {
  formAberto.value = false
  avisarSucesso(receitaEditada.value ? 'Receita atualizada com sucesso!' : 'Receita cadastrada com sucesso!')
  carregar(pagina.value?.current_page)
}

function imprimir() {
  window.print()
}

onMounted(async () => {
  try {
    const { data } = await api.get('/categorias')
    categorias.value = data
  } catch (e) {
    avisarErro(e)
  }

  carregar()
})
</script>

<template>
  <div class="d-print-none">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">Minhas receitas</h1>
      <button class="btn btn-primary" @click="abrirForm()">Nova receita</button>
    </div>

    <form class="row g-2 mb-3" @submit.prevent="carregar()">
      <div class="col-md-5">
        <input v-model="filtros.nome" class="form-control" placeholder="Pesquisar pelo nome" />
      </div>
      <div class="col-md-4">
        <select v-model="filtros.id_categorias" class="form-select">
          <option value="">Todas as categorias</option>
          <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
            {{ categoria.nome }}
          </option>
        </select>
      </div>
      <div class="col-md-3 d-grid">
        <button class="btn btn-outline-secondary">Pesquisar</button>
      </div>
    </form>

    <template v-if="pagina">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Categoria</th>
              <th>Tempo</th>
              <th>Porções</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="receita in pagina.data" :key="receita.id">
              <td>{{ receita.nome }}</td>
              <td>{{ receita.categoria?.nome ?? '-' }}</td>
              <td>{{ receita.tempo_preparo_minutos ? `${receita.tempo_preparo_minutos} min` : '-' }}</td>
              <td>{{ receita.porcoes ?? '-' }}</td>
              <td class="text-end text-nowrap">
                <button class="btn btn-sm btn-outline-secondary" @click="receitaAberta = receita">Ver</button>
                <button class="btn btn-sm btn-outline-primary ms-1" @click="abrirForm(receita)">Editar</button>
                <button class="btn btn-sm btn-outline-danger ms-1" @click="excluir(receita)">Excluir</button>
              </td>
            </tr>
            <tr v-if="!pagina.data.length">
              <td colspan="5" class="text-center text-muted py-4">Nenhuma receita encontrada.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav v-if="pagina.last_page > 1">
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: pagina.current_page === 1 }">
            <button class="page-link" @click="carregar(pagina.current_page - 1)">Anterior</button>
          </li>
          <li
            v-for="numero in pagina.last_page"
            :key="numero"
            class="page-item"
            :class="{ active: numero === pagina.current_page }"
          >
            <button class="page-link" @click="carregar(numero)">{{ numero }}</button>
          </li>
          <li class="page-item" :class="{ disabled: pagina.current_page === pagina.last_page }">
            <button class="page-link" @click="carregar(pagina.current_page + 1)">Próxima</button>
          </li>
        </ul>
      </nav>
    </template>
  </div>

  <template v-if="receitaAberta">
    <div class="modal d-block" tabindex="-1" @click.self="receitaAberta = undefined">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-break">{{ receitaAberta.nome }}</h5>
            <button type="button" class="btn-close d-print-none" @click="receitaAberta = undefined"></button>
          </div>
          <div class="modal-body">
            <p v-if="receitaAberta.categoria">
              <span class="badge text-bg-secondary">{{ receitaAberta.categoria.nome }}</span>
            </p>
            <p v-if="receitaAberta.tempo_preparo_minutos" class="mb-1">
              <strong>Tempo de preparo:</strong> {{ receitaAberta.tempo_preparo_minutos }} minutos
            </p>
            <p v-if="receitaAberta.porcoes"><strong>Porções:</strong> {{ receitaAberta.porcoes }}</p>

            <template v-if="receitaAberta.ingredientes">
              <h6>Ingredientes</h6>
              <p class="text-break" style="white-space: pre-line">{{ receitaAberta.ingredientes }}</p>
            </template>

            <h6>Modo de preparo</h6>
            <p class="mb-0 text-break" style="white-space: pre-line">{{ receitaAberta.modo_preparo }}</p>
          </div>
          <div class="modal-footer d-print-none">
            <button class="btn btn-secondary" @click="receitaAberta = undefined">Fechar</button>
            <button class="btn btn-primary" @click="imprimir">Imprimir</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop show d-print-none"></div>
  </template>

  <ReceitaModal
    v-if="formAberto"
    :receita="receitaEditada"
    :categorias="categorias"
    @fechar="formAberto = false"
    @salvo="salvo"
  />
</template>

<style scoped>
@media print {
  .modal {
    position: static;
    height: auto;
    overflow: visible;
  }

  .modal-content {
    border: 0;
  }
}
</style>
