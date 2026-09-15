const { test, expect } = require('@playwright/test');

const requiredEnvironment = [
  'E2E_LOGIN_ID',
  'E2E_PASSWORD',
];

function missingEnvironmentVariables() {
  return requiredEnvironment.filter((name) => !process.env[name]);
}

const missing = missingEnvironmentVariables();
const orderCreationAllowed = process.env.E2E_ALLOW_ORDER_CREATION === 'yes-test-environment-only';
test.skip(
  missing.length > 0 || !orderCreationAllowed,
  missing.length > 0
    ? `環境変数が不足しています: ${missing.join(', ')}`
    : '実注文を1件作成するため、E2E_ALLOW_ORDER_CREATION=yes-test-environment-only が必要です。'
);

async function submitOrderRequest(page, token, nonce) {
  return page.evaluate(async ({ orderToken, orderNonce }) => {
    const deliveryRequestTime = document.querySelector('#delivery-request .change__detail')?.textContent || '';
    const usePoint = document.querySelector('#change-mile_point input#use_point')?.value || '0';
    const selectedAddress = document.querySelector('#change_delivery-address [name="address"]:checked');
    const firstAddress = document.querySelector('#change_delivery-address [name="address"]');
    const deliveryAddress = selectedAddress?.value || firstAddress?.value || '';
    const body = new URLSearchParams({
      action: 'send_order_confirmation_mail',
      delivery_request_time: deliveryRequestTime,
      use_point: usePoint,
      delivery_address: deliveryAddress,
      order_token: orderToken,
      security: orderNonce,
    });

    const ajaxEndpoint = new URL('/cms/wp-admin/admin-ajax.php', window.location.origin);
    const response = await fetch(ajaxEndpoint, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: body.toString(),
    });

    return {
      status: response.status,
      body: await response.json(),
    };
  }, { orderToken: token, orderNonce: nonce });
}

test('有効注文の直後に別画面から送信しても空注文を作成しない', async ({ browser, baseURL }) => {
  expect(new URL(baseURL).hostname).toBe('happyfamily-members.3d-showcase.net');
  const context = await browser.newContext();
  const firstPage = await context.newPage();

  await firstPage.goto('/login/');
  await firstPage.locator('#login_user').fill(process.env.E2E_LOGIN_ID);
  await firstPage.locator('#login_password').fill(process.env.E2E_PASSWORD);
  await firstPage.getByRole('button', { name: 'ログイン' }).click();
  await firstPage.waitForLoadState('domcontentloaded');
  await expect(firstPage).not.toHaveURL(/\/login\/?$/);

  // 既存カートを削除・変更しない。商品がなければ購入可能な商品を1点だけ追加する。
  await firstPage.goto('/cart/');
  const existingCartCount = await firstPage.locator('.article-cart').count();

  if (existingCartCount === 0) {
    await firstPage.goto('/product/');
    const requestedProductCode = process.env.E2E_PRODUCT_CODE;
    if (requestedProductCode) {
      expect(requestedProductCode).toMatch(/^[0-9A-Za-z_-]+$/);
    }
    const purchase = requestedProductCode
      ? firstPage.locator(`.product-purchase[data-product-code="${requestedProductCode}"]`).first()
      : firstPage.locator('.product-purchase').filter({ has: firstPage.locator('.btn-add-cart') }).first();
    await expect(purchase).toBeVisible();
    const selectedProductCode = await purchase.getAttribute('data-product-code');
    expect(selectedProductCode).toBeTruthy();
    await purchase.locator('.btn-up').click();
    const addResponsePromise = firstPage.waitForResponse((response) => (
      response.url().includes('admin-ajax.php') && response.request().postData()?.includes('create_or_update_cart')
    ));
    await purchase.locator('.btn-add-cart').click();
    const addResponse = await addResponsePromise;
    expect(addResponse.ok()).toBeTruthy();
  }

  // 同じセッションで注文確認画面を2つ開き、それぞれ別の一回限りトークンを発行する。
  await firstPage.goto('/cart/confirmation/');
  await expect(firstPage.locator('.product-list article')).not.toHaveCount(0);
  const secondPage = await context.newPage();
  await secondPage.goto('/cart/confirmation/');

  const firstToken = await firstPage.locator('#page-product').getAttribute('data-order-token');
  const firstNonce = await firstPage.locator('#page-product').getAttribute('data-order-nonce');
  const secondToken = await secondPage.locator('#page-product').getAttribute('data-order-token');
  const secondNonce = await secondPage.locator('#page-product').getAttribute('data-order-nonce');
  expect(firstToken).toBeTruthy();
  expect(firstNonce).toBeTruthy();
  expect(secondToken).toBeTruthy();
  expect(secondNonce).toBeTruthy();

  // APIの接続先と拒否応答を、副作用のない無効トークンで先に確認する。
  const rejectedInvalidToken = await submitOrderRequest(firstPage, 'invalid-e2e-token', firstNonce);
  expect(rejectedInvalidToken.status).toBe(409);
  expect(rejectedInvalidToken.body.code).toBe(3);

  // 1回目だけが成立する。
  const accepted = await submitOrderRequest(firstPage, firstToken, firstNonce);
  expect(accepted.status).toBe(200);
  expect(accepted.body.code).toBe(0);

  // 20秒後に発生した実障害と同様に、別画面の有効トークンでも空カートは拒否される。
  const rejectedEmptyOrder = await submitOrderRequest(secondPage, secondToken, secondNonce);
  expect(rejectedEmptyOrder.status).toBe(409);
  expect(rejectedEmptyOrder.body.code).toBe(2);

  // 同じトークンの再送も拒否される。
  const rejectedReplay = await submitOrderRequest(firstPage, firstToken, firstNonce);
  expect(rejectedReplay.status).toBe(409);
  expect(rejectedReplay.body.code).toBe(3);

  await context.close();
});
