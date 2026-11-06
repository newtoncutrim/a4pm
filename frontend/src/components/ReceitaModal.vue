<script setup lang="ts">
import { ref } from 'vue'
import { api, errosCampos, mensagemErro, type Categoria, type Receita } from '@/api'

const { receita, categorias } = defineProps<{ receita?: Receita; categorias: Categoria[] }>()
const emit = defineEmits<{ fechar: []; salvo: [] }>()

const form = ref({
  nome: receita?.nome ?? '',
  id_categorias: receita?.id_categorias ?? '',
  tempo_preparo_minutos: receita?.tempo_preparo_minutos ?? null,
  porcoes: receita?.porcoes ?? null,
  ingredientes: receita?.ingredientes ?? '',
  modo_preparo: receita?.modo_preparo ?? '',
})
const erro = ref('')
const erros = ref<Record<string, string[]>>({})
const salvando = ref(false)

async function salvar() {
  erro.value = ''
  erros.value = {}
  salvando.value = true

  try {
    if (receita) {
      await api.put(`/receitas/${receita.id}`, form.value)
    } else {
      await api.post('/receitas', form.value)
    }
    emit('salvo')
  } catch (e) {
    erros.value = errosCampos(e)
    if (!Object.keys(erros.value).length) erro.value = mensagemErro(e)
  } finally {
    salvando.value = false
  }
}
</script>

<template>
  <div class="modal d-block" tabindex="-1" @click.self="emit('fechar')">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <form class="modal-content" novalidate @submit.prevent="salvar">
        <div class="modal-header">
          <h5 class="modal-title">{{ receita ? 'Editar receita' : 'Nova receita' }}</h5>
          <button type="button" class="btn-close" @click="emit('fechar')"></button>
        </div>

        <div class="modal-body">
          <div v-if="erro" class="alert alert-danger py-2">{{ erro }}</div>

          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label" for="nome">Nome <span class="text-danger">*</span></label>
              <input id="nome" v-model="form.nome" class="form-control" :class="{ 'is-invalid': erros.nome }" maxlength="45" />
              <div class="invalid-feedback">{{ erros.nome?.[0] }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="categoria">Categoria <span class="text-danger">*</span></label>
              <select id="categoria" v-model="form.id_categorias" class="form-select" :class="{ 'is-invalid': erros.id_categorias }">
                <option value="" disabled>Selecione</option>
                <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                  {{ categoria.nome }}
                </option>
              </select>
              <div class="invalid-feedback">{{ erros.id_categorias?.[0] }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="tempo">Tempo de preparo (minutos)</label>
              <input id="tempo" v-model.number="form.tempo_preparo_minutos" type="number" min="1" class="form-control" :class="{ 'is-invalid': erros.tempo_preparo_minutos }" />
              <div class="invalid-feedback">{{ erros.tempo_preparo_minutos?.[0] }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="porcoes">Porções</label>
              <input id="porcoes" v-model.number="form.porcoes" type="number" min="1" class="form-control" :class="{ 'is-invalid': erros.porcoes }" />
              <div class="invalid-feedback">{{ erros.porcoes?.[0] }}</div>
            </div>
            <div class="col-12">
              <label class="form-label" for="ingredientes">Ingredientes</label>
              <textarea id="ingredientes" v-model="form.ingredientes" class="form-control" :class="{ 'is-invalid': erros.ingredientes }" rows="3" maxlength="1500"></textarea>
              <div class="invalid-feedback">{{ erros.ingredientes?.[0] }}</div>
              <div class="form-text text-end">{{ form.ingredientes.length }}/1500</div>
            </div>
            <div class="col-12">
              <label class="form-label" for="modo_preparo">Modo de preparo <span class="text-danger">*</span></label>
              <textarea id="modo_preparo" v-model="form.modo_preparo" class="form-control" :class="{ 'is-invalid': erros.modo_preparo }" rows="4" maxlength="1500"></textarea>
              <div class="invalid-feedback">{{ erros.modo_preparo?.[0] }}</div>
              <div class="form-text text-end">{{ form.modo_preparo.length }}/1500</div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="emit('fechar')">Cancelar</button>
          <button class="btn btn-primary" :disabled="salvando">{{ salvando ? 'Salvando...' : 'Salvar' }}</button>
        </div>
      </form>
    </div>
  </div>
  <div class="modal-backdrop show"></div>
</template>
