import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
window.Alpine = Alpine;
Alpine.start();
document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('[data-panorly-sortable]');
  if (!grid) return;
  Sortable.create(grid, {
    animation: 170,
    disabled: !grid.dataset.editing,
    ghostClass: 'opacity-30',
    onEnd: async () => {
      const order = Array.from(grid.querySelectorAll('[data-app-id]')).map((el) => Number(el.dataset.appId));
      await fetch('/apps/reorder', { method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body:JSON.stringify({order}) });
    }
  });
});
