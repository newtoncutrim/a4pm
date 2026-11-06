import { expect, test } from '@playwright/test'

test('não entra com senha errada', async ({ page }) => {
  await page.goto('/login')
  await page.getByLabel('Login').fill('naoexiste')
  await page.getByLabel('Senha').fill('errada')
  await page.getByRole('button', { name: 'Entrar' }).click()

  await expect(page.locator('.alert-danger')).toBeVisible()
})
