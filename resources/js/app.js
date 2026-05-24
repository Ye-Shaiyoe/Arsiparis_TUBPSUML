import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

if (!window.__alpineStarted) {
    window.__alpineStarted = true;
    Alpine.start();
}
