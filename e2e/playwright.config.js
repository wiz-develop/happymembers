const { defineConfig, devices } = require('@playwright/test');

const baseURL = process.env.E2E_BASE_URL || 'http://happyfamily-members.3d-showcase.net';
const testHost = 'happyfamily-members.3d-showcase.net';
const productionHost = 'www.happyfamily.co.jp';
const targetHost = new URL(baseURL).hostname;
const productionExecutionAllowed = process.env.E2E_ALLOW_PRODUCTION_ORDER === 'yes-production-order';

if (targetHost !== testHost && targetHost !== productionHost) {
  throw new Error(`E2Eの実行対象として許可されていないホストです: ${targetHost}`);
}

if (targetHost === productionHost && !productionExecutionAllowed) {
  throw new Error('本番E2Eには E2E_ALLOW_PRODUCTION_ORDER=yes-production-order が必要です。');
}

const device = process.env.E2E_DEVICE === 'desktop'
  ? devices['Desktop Chrome']
  : devices['Pixel 7'];

module.exports = defineConfig({
  testDir: './tests',
  fullyParallel: false,
  workers: 1,
  timeout: 120000,
  reporter: 'list',
  outputDir: './test-results',
  use: {
    ...device,
    baseURL,
    screenshot: 'off',
    trace: 'off',
    video: 'off',
  },
});
