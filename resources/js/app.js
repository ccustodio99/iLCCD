import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

createApp(App)
  .use(router)
  .mount('#app');

document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.getElementById('menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', () => {
      const expanded = sidebar.classList.toggle('active');
      menuToggle.setAttribute('aria-expanded', expanded);
    });
  }

  const toggleFooterBtn = document.getElementById('toggle-footer');
  const footer = document.getElementById('app-footer');
  if (toggleFooterBtn && footer) {
    toggleFooterBtn.addEventListener('click', () => {
      const visible = footer.style.display !== 'none';
      footer.style.display = visible ? 'none' : 'block';
      toggleFooterBtn.textContent = visible ? 'Show Footer' : 'Hide Footer';
    });
  }

  const breadcrumbToggle = document.getElementById('breadcrumb-toggle');
  const breadcrumbPanel = document.querySelector('.sidebar #breadcrumb-panel');
  if (breadcrumbToggle && breadcrumbPanel) {
    breadcrumbToggle.addEventListener('click', () => {
      const visible = breadcrumbPanel.style.display !== 'none';
      breadcrumbPanel.style.display = visible ? 'none' : 'block';
      breadcrumbToggle.setAttribute('aria-expanded', !visible);
    });
  }
});
