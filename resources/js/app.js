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

Vue.component('shipping-component', require('./components/ShippingComponent.vue').default);
Vue.component('Add-shipper-modal', require('./components/AddShipperModal.vue').default);
Vue.component('Show-contacts-modal', require('./components/ShowContactsModal.vue').default);
Vue.component('Edit-shipper-modal', require('./components/EditShipperModal.vue').default);
Vue.component('Delete-shipper-modal', require('./components/DeleteShipperModal.vue').default);

const app = new Vue({
    el: '#app',
});
