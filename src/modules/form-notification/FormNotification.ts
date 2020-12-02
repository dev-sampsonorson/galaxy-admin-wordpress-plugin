import IFormNotificationConfig from "./IFormNotificationConfig";

export enum NotificationStatus {
    Primary = 'alert-primary',
    Secondary = 'alert-secondary',
    Success = 'alert-success',
    Danger = 'alert-danger',
    Warning = 'alert-warning',
    Info = 'alert-info',
    Light = 'alert-light',
    Dark = 'alert-dark',
}

export default class FormNotification {
    private containerEl!: HTMLElement;
    private messageEl!: HTMLElement;

    constructor(private config: IFormNotificationConfig | null) {
        if (config === null) return;

        this.config = this.config as IFormNotificationConfig;

        this.containerEl = document.getElementById(this.config.containerId) as HTMLElement;
        this.messageEl = this.containerEl.querySelector(`.${this.config.messageElClass}`) as HTMLElement;
    }

    show(status: NotificationStatus, message: string): void {
        Object.values(NotificationStatus).forEach((item, index) => {
            this.containerEl.classList.remove(item);
        });

        this.messageEl.innerHTML = message;
        this.containerEl.classList.remove('d-none');
        this.containerEl.classList.add('show', status);
    }

    hide(): void {
        this.containerEl.classList.remove('show');
        this.containerEl.classList.add('d-none');
    }

}