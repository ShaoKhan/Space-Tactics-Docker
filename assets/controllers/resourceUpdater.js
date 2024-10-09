import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = [ "resource" ]

  connect() {
    console.log('connect');
    this.updateResources().then(r => console.log('Resources updated'));
    this.startPolling();
  }

  startPolling() {
    setInterval(() => {
      console.log('startPolling');
      this.updateResources().then(r => console.log('Resources updated'));
    }, 1000);
  }

  async updateResources() {
    try {
      colsole.log('updateResources');
      const response = await fetch('/get_resources');
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      this.resourceTargets.forEach((element) => {
        const resourceName = element.dataset.resourceName;
        element.textContent = data[resourceName];
      });
    } catch (error) {
      console.error('Error fetching resources:', error);
    }
  }
}
