import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container', 'template'];

    connect() {
        this.index = 0;
        this.addInstrument();
    }

    addInstrument(event) {
        const container = this.containerTarget;
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'instrument-row', 'mb-3');
        newRow.innerHTML = this.templateTarget.innerHTML.replace(/__index__/g, this.index);
        container.appendChild(newRow);
        this.index++;
    }
}
