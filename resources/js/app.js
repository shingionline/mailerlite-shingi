import './bootstrap';

window.Vue = require('vue').default;

// bootstrap vue
import { BootstrapVue } from 'bootstrap-vue'
Vue.use(BootstrapVue)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// sweet alert
import swal from 'sweetalert2';
window.Swal = swal;

Vue.component('subscriber-component', require('./components/SubscriberComponent.vue').default);
Vue.component('Add-subscriber-modal', require('./components/AddSubscriberModal.vue').default);
Vue.component('View-subscriber-modal', require('./components/ViewSubscriberModal.vue').default);
Vue.component('Edit-subscriber-modal', require('./components/EditSubscriberModal.vue').default);
Vue.component('Delete-subscriber-modal', require('./components/DeleteSubscriberModal.vue').default);
Vue.component('Manage-fields-modal', require('./components/ManageFieldsModal.vue').default);


const app = new Vue({
    el: '#app',
});
