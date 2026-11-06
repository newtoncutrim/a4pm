<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api, entrar, mensagemErro } from '@/api'

const router = useRouter()
const form = ref({ nome: '', login: '', senha: '' })
const erro = ref('')

async function enviar() {
  erro.value = ''

  try {
    await api.post('/usuarios', form.value)
    const { data } = await api.post('/login', form.value)
    entrar(data.token, data.usuario.nome ?? data.usuario.login)
    router.push('/receitas')
  } catch (e) {
    erro.value = mensagemErro(e)
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

          <form @submit.prevent="enviar">
            <div class="mb-3">
              <label class="form-label" for="nome">Nome</label>
              <input id="nome" v-model="form.nome" class="form-control" maxlength="100" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="login">Login <span class="text-danger">*</span></label>
              <input id="login" v-model="form.login" class="form-control" maxlength="100" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="senha">Senha <span class="text-danger">*</span></label>
              <input id="senha" v-model="form.senha" type="password" class="form-control" minlength="3" maxlength="72" required />
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
