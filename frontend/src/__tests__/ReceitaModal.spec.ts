import { describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import ReceitaModal from '@/components/ReceitaModal.vue'
import { api } from '@/api'

vi.mock('@/api', () => ({
  api: { post: vi.fn(), put: vi.fn() },
  mensagemErro: () => 'O campo nome é obrigatório.',
}))

const categorias = [{ id: 1, nome: 'Bolos e tortas doces' }]

describe('ReceitaModal', () => {
  it('preenche o formulário quando é edição', () => {
    const receita = {
      id: 7,
      id_categorias: 1,
      nome: 'Bolo de cenoura',
      tempo_preparo_minutos: 40,
      porcoes: 8,
      modo_preparo: 'Bater tudo e assar',
      ingredientes: null,
      categoria: categorias[0]!,
    }

    const wrapper = mount(ReceitaModal, { props: { receita, categorias } })

    expect(wrapper.find('h5').text()).toBe('Editar receita')
    expect(wrapper.find<HTMLInputElement>('#nome').element.value).toBe('Bolo de cenoura')
    expect(wrapper.find<HTMLInputElement>('#tempo').element.value).toBe('40')
  })

  it('envia a nova receita e avisa que salvou', async () => {
    vi.mocked(api.post).mockResolvedValue({})
    const wrapper = mount(ReceitaModal, { props: { categorias } })

    await wrapper.find('#nome').setValue('Pudim')
    await wrapper.find('#categoria').setValue(1)
    await wrapper.find('#modo_preparo').setValue('Assar em banho-maria')
    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(api.post).toHaveBeenCalledWith('/receitas', expect.objectContaining({ nome: 'Pudim', id_categorias: 1 }))
    expect(wrapper.emitted('salvo')).toHaveLength(1)
  })

  it('mostra o erro da api e não fecha', async () => {
    vi.mocked(api.post).mockRejectedValue(new Error())
    const wrapper = mount(ReceitaModal, { props: { categorias } })

    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(wrapper.find('.alert-danger').text()).toBe('O campo nome é obrigatório.')
    expect(wrapper.emitted('salvo')).toBeUndefined()
  })
})
