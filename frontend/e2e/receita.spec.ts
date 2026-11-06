import { expect, test } from '@playwright/test'

test('cadastra, faz login e adiciona uma receita', async ({ page }) => {
  const login = `maria${Date.now()}`

  await page.goto('/cadastro')
  await page.getByLabel('Nome').fill('Maria')
  await page.getByLabel('Login').fill(login)
  await page.getByLabel('Senha').fill('123')
  await page.getByRole('button', { name: 'Cadastrar' }).click()
  await page.getByRole('button', { name: 'Sair' }).click()
  await page.getByRole('button', { name: 'Sim, sair' }).click()

  await page.getByLabel('Login').fill(login)
  await page.getByLabel('Senha').fill('123')
  await page.getByRole('button', { name: 'Entrar' }).click()

  await page.getByRole('button', { name: 'Nova receita' }).click()
  await page.getByLabel('Nome').fill('Bolo de cenoura')
  await page.getByLabel('Categoria').selectOption('Bolos e tortas doces')
  await page.getByLabel('Modo de preparo').fill('Bater tudo e assar')
  await page.getByRole('button', { name: 'Salvar' }).click()

  await expect(page.getByText('Bolo de cenoura')).toBeVisible()
})
