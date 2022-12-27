<template>
  <b-modal id="bv-modal-show-contacts" hide-footer>
    <template #modal-title><b>Contacts for {{ JSON.parse(selected_shipper).name }}</b></template>

    <!-- Add contact screen -->
    <div v-if="add_contact">
      <form>
        <div class="form-group pb-2">
          <label>Contact Name</label>
          <input
            v-model="new_contact.name"
            type="text"
            class="form-control mt-2" />
        </div>
        <div class="form-group pb-2">
          <label>Contact Number</label>
          <input
            v-model="new_contact.phone"
            type="text"
            class="form-control my-2" />
        </div>
        <div v-if="saving_contact" class="text-center">
        <b-spinner label="Loading..."></b-spinner>
      </div>
        <button v-else
          type="button"
          class="btn btn-success w-100 mt-2"
          @click="saveContact()">
          Save
        </button>
      </form>
      <div class="text-center mt-2">
        <button
          type="button"
          class="btn btn-danger btn-sm"
          @click="add_contact = false">
          Cancel
        </button>
      </div>
    </div>

    <!-- Show contacts screen -->
    <div v-else>
      <div class="text-center mb-2">
        <button
          type="button"
          class="btn btn-primary btn-sm"
          @click="showAddContact()">
          Add contact
        </button>
      </div>

      <div v-if="contacts.length === 0" class="p-2 text-center">
        No contacts found
      </div>

      <table class="table table-hover mb-0" v-else>
        <thead>
          <tr>
            <th scope="col">Contact Name</th>
            <th scope="col">Contact Number</th>
            <th scope="col">Primary</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(contact, index) in contacts" :key="index">
            <th>{{ contact.name }}</th>
            <td>{{ contact.phone }}</td>
            <td>
              <div v-if="contact.primary === 1">
                <i class="fas fa-check-circle"></i>
              </div>
            </td>
            <td>
              <div v-if="contact.primary === 0">
                <button
                  type="button"
                  class="btn btn-success btn-sm"
                  title="Make primary"
                  @click="makePrimary(`${contact.id}`)">
                  <i class="fas fa-check-circle"></i>
                </button>
                <button
                  v-if="contact.primary === 0"
                  type="button"
                  class="btn btn-danger btn-sm"
                  title="Delete contact"
                  @click="deleteContact(`${contact.id}`)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: ["selected_shipper"],

  data() {
    return {
      contacts: [],
      add_contact: false,
      new_contact: {},
      saving_contact: false,
    };
  },

  methods: {
    showAddContact() {
      this.new_contact = {};
      this.add_contact = true;
    },

    saveContact() {
      let selected_shipper = JSON.parse(this.selected_shipper);

      if (!this.new_contact.name) {
        this.customAlert("Please enter contact name");
      } else if (!this.new_contact.phone) {
        this.customAlert("Please enter contact phone number");
      }

      else {

        this.saving_contact = true;

        axios
          .post("/contacts/new", {
            contact: this.new_contact,
            shipper_id: selected_shipper.id,
          })
          .then((response) => {
            console.log(response.data);
            this.getContacts(selected_shipper.id);
            this.add_contact = false;
            if (response.data.contact.primary === 1) {
              this.$emit("update-shippers");
            }
            this.saving_contact = false;
          })
          .catch(function (err) {
            console.log(err);
            this.loading = false;
            this.saving_contact = false;
          });

      }

    },

    makePrimary(contact_id) {
      let selected_shipper = JSON.parse(this.selected_shipper);

      axios
        .post("/contacts/make-primary", {
          contact_id: contact_id,
          shipper_id: selected_shipper.id,
        })
        .then((response) => {
          console.log(response.data);
          this.getContacts(selected_shipper.id);
          this.$emit("update-shippers");
        })
        .catch(function (err) {
          console.log(err);
          this.loading = false;
        });
    },

    customAlert(text) {
      Swal.fire({
        title: text,
        showConfirmButton: false,
        showDenyButton: false,
        showCancelButton: true,
        focusConfirm: false,
        cancelButtonText: "Ok",
        customClass: {
          title: "popup-title",
        },
      });
    },

    deleteContact(contact_id) {
      let selected_shipper = JSON.parse(this.selected_shipper);

      axios
        .delete(`/contacts/delete/${contact_id}`)
        .then((response) => {
          console.log(response.data);
          this.getContacts(selected_shipper.id);
        })
        .catch(function (err) {
          console.log(err);
          this.loading = false;
        });
    },

    getContacts(shipper_id) {
      axios
        .get(`/contacts/get/${shipper_id}`)
        .then((response) => {
          this.contacts = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
};
</script>
