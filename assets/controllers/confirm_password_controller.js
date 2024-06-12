import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["password", "confirmPassword"];

    connect() {
        console.log("ConfirmPasswordController connected");
        this.confirmPasswordTarget.addEventListener('input', () => this.confirm());
        this.passwordTarget.addEventListener('input', () => this.confirm());
    }

    confirm() {
        const password = this.passwordTarget.value;
        const confirmPassword = this.confirmPasswordTarget.value;

        console.log(`Password: ${password}, Confirm Password: ${confirmPassword}`);

        this.passwordTarget.classList.remove('border-success', 'border-danger', 'border-2');
        this.confirmPasswordTarget.classList.remove('border-success', 'border-danger', 'border-2');

        if (password && confirmPassword) {
            if (password === confirmPassword) {
                this.confirmPasswordTarget.classList.add('border-success');
                this.passwordTarget.classList.add('border-success');
                this.confirmPasswordTarget.classList.add('border-2');
                this.passwordTarget.classList.add('border-2');
            } else {
                this.confirmPasswordTarget.classList.add('border-danger');
                this.passwordTarget.classList.add('border-danger');
                this.confirmPasswordTarget.classList.add('border-2');
                this.passwordTarget.classList.add('border-2');
            }
        }
    }
}
