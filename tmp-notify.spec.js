const { test, expect } = require('@playwright/test');

test('notification badge and dropdown update after login', async ({ page }) => {
  const browserLogs = [];

  page.on('console', (msg) => {
    const text = msg.text();
    if (text.includes('Notifications endpoint:') || text.includes('Notifications response:') || text.includes('Notifications polling error:')) {
      browserLogs.push(text);
    }
  });

  await page.goto('http://127.0.0.1:8012/login', { waitUntil: 'networkidle' });
  await page.fill('input[name="email"]', 'notifplay@example.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(5000);

  const badge = page.locator('#notificationBadge');
  const count = page.locator('#notificationCount');
  const bell = page.locator('#notificationBell');

  await expect(bell).toBeVisible();
  await expect(badge).toBeVisible();
  await expect(count).not.toHaveText('0');

  await bell.click();
  await page.waitForTimeout(1000);

  const list = page.locator('#notificationList');
  await expect(list).toContainText('Playwright seeded notification');

  console.log('E2E_NOTIFICATION_LOGS_START');
  for (const line of browserLogs) {
    console.log(line);
  }
  console.log('E2E_NOTIFICATION_LOGS_END');
});

