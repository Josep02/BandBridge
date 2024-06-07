import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['count'];

    connect() {
        const invitationCountSpan = this.countTarget;
        const apiCountUrl = invitationCountSpan.dataset.apiCount;

        if (apiCountUrl) {
            fetch(apiCountUrl)
                .then(response => response.json())
                .then(data => {
                    invitationCountSpan.textContent = data.count_number;
                })
                .catch(error => {
                    console.error('Error fetching invitation count:', error);
                });
        } else {
            console.error('API count URL not found');
        }
    }
}