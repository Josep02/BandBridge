import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "output" ]

    connect() {
        this.loadCount();
    }

    loadCount() {
        fetch('/api/count')
            .then(response => response.json())
            .then(data => {
                const count = data.count_number;

                // Oculta o muestra el span del enlace
                const navLinkCountSpan = this.element.querySelector('.nav-link [data-count-target="output"]');
                if (navLinkCountSpan) {
                    if (count === 0) {
                        navLinkCountSpan.classList.add('d-none');
                    } else {
                        navLinkCountSpan.classList.remove('d-none');
                    }
                    navLinkCountSpan.textContent = count;
                }

                // Oculta o muestra el span con el ID notifications
                const notificationsSpan = document.getElementById('notifications');
                if (notificationsSpan) {
                    if (count === 0) {
                        notificationsSpan.style.display = 'none';
                    } else {
                        notificationsSpan.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching count:', error);
            });
    }
}
