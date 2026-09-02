import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('[data-panorly-sortable]');
  if (!grid) return;

  Sortable.create(grid, {
    animation: 150,
    ghostClass: 'opacity-40',
    onEnd: async () => {
      const order = Array.from(grid.children).map((el) => el.dataset.appId);
      await fetch('/apps/reorder', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ order }),
      });
    },
  });
});
