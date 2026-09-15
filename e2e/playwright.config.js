const { defineConfig, devices } = require('@playwright/test');

const baseURL = process.env.E2E_BASE_URL || 'http://happyfamily-members.3d-showcase.net';
const allowedHost = 'happyfamily-members.3d-showcase.net';

if (new URL(baseURL).hostname !== allowedHost) {
  throw new Error(`E2Eはテスト環境 ${allowedHost} 以外では実行できません。`);
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
