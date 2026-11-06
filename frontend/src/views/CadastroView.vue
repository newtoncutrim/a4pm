<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api, entrar, errosCampos, mensagemErro } from '@/api'

const router = useRouter()
const form = ref({ nome: '', login: '', senha: '' })
const erro = ref('')
const erros = ref<Record<string, string[]>>({})

async function enviar() {
  erro.value = ''
  erros.value = {}

  try {
    await api.post('/usuarios', form.value)
    const { data } = await api.post('/login', form.value)
    entrar(data.token, data.usuario.nome ?? data.usuario.login)
    router.push('/receitas')
  } catch (e) {
    erros.value = errosCampos(e)
    if (!Object.keys(erros.value).length) erro.value = mensagemErro(e)
  }
}
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Criar conta</h1>

          <div v-if="erro" class="alert alert-danger py-2">{{ erro }}</div>

          <form novalidate @submit.prevent="enviar">
            <div class="mb-3">
              <label class="form-label" for="nome">Nome <span class="text-danger">*</span></label>
              <input id="nome" v-model="form.nome" class="form-control" :class="{ 'is-invalid': erros.nome }" maxlength="100" />
              <div class="invalid-feedback">{{ erros.nome?.[0] }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="login">Login <span class="text-danger">*</span></label>
              <input id="login" v-model="form.login" class="form-control" :class="{ 'is-invalid': erros.login }" maxlength="100" />
              <div class="invalid-feedback">{{ erros.login?.[0] }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="senha">Senha <span class="text-danger">*</span></label>
              <input id="senha" v-model="form.senha" type="password" class="form-control" :class="{ 'is-invalid': erros.senha }" maxlength="72" />
              <div class="invalid-feedback">{{ erros.senha?.[0] }}</div>
            </div>
            <button class="btn btn-primary w-100">Cadastrar</button>
          </form>

          <p class="text-center mt-3 mb-0">
            Já tem conta? <RouterLink to="/login">Entrar</RouterLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
