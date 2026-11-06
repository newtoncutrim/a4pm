import axios from 'axios'
import { reactive } from 'vue'

export interface Categoria {
  id: number
  nome: string
}

export interface Receita {
  id: number
  id_categorias: number
  nome: string
  tempo_preparo_minutos: number | null
  porcoes: number | null
  modo_preparo: string
  ingredientes: string | null
  categoria: Categoria | null
}

export interface Pagina<T> {
  data: T[]
  current_page: number
  last_page: number
  total: number
}

export function mensagemErro(erro: unknown) {
  if (!axios.isAxiosError(erro)) return 'Não foi possível concluir. Tente novamente.'
  if (!erro.response) return 'Sem conexão com o servidor. Tente novamente.'

  return erro.response.data?.message ?? 'Não foi possível concluir. Tente novamente.'
}

export const sessao = reactive({
  token: localStorage.getItem('token') ?? '',
  nome: localStorage.getItem('nome') ?? '',
})

export function entrar(token: string, nome: string) {
  sessao.token = token
  sessao.nome = nome
  localStorage.setItem('token', token)
  localStorage.setItem('nome', nome)
}

export function limparSessao() {
  sessao.token = ''
  sessao.nome = ''
  localStorage.removeItem('token')
  localStorage.removeItem('nome')
}

export const api = axios.create({
  baseURL: '/api',
  headers: { Accept: 'application/json' },
})

api.interceptors.request.use((config) => {
  if (sessao.token) config.headers.Authorization = `Bearer ${sessao.token}`
  return config
})

api.interceptors.response.use(
  (resposta) => resposta,
  (erro) => {
    if (erro.response?.status === 401 && sessao.token) {
      limparSessao()
      location.href = '/login'
    }

    return Promise.reject(erro)
  },
)
