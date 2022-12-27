<template>
  <div class="container">
    <div class="row py-4">
      <div class="col"><h3>List of Shippers</h3></div>
      <div class="col text-end">
        <button
          type="button"
          class="btn btn-success"
          @click="showModal('add-shipper', null)">
          Add shipper
        </button>
      </div>
    </div>

    <div class="card shadow">
      <div v-if="loading_shippers" class="p-2 text-center">
        <b-spinner label="Loading..."></b-spinner>
      </div>

      <div v-else-if="shippers.length === 0" class="p-2 text-center">
        No shippers found
      </div>

      <table class="table table-hover mb-0" v-else>
        <thead>
          <tr>
            <th scope="col">Company Name</th>
            <th scope="col">Address</th>
            <th scope="col">Primary Contact Name</th>
            <th scope="col">Primary Contact Number</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(shipper, index) in shippers" :key="index">
            <th>{{ shipper.name }}</th>
            <td>{{ shipper.address }}</td>
            <td>
              <div v-if="shipper.contacts[0]">
                {{ shipper.contacts[0].name }}
              </div>
            </td>
            <td>
              <div v-if="shipper.contacts[0]">
                {{ shipper.contacts[0].phone }}
              </div>
            </td>
            <td class="nowrap">
              <button
                type="button"
                class="btn btn-primary"
                title="Show contacts"
                @click="showModal('show-contacts', `${shipper.id}`, `${shipper.name}`)">
                <i class="fas fa-user"></i>
              </button>
              <button
                type="button"
                class="btn btn-success"
                title="Edit shipper"
                @click="showModal('edit-shipper', `${shipper.id}`, `${shipper.name}`)">
                <i class="fas fa-edit"></i>
              </button>
              <button
                type="button"
                class="btn btn-danger"
                title="Delete shipper"
                @click="
                  showModal('delete-shipper',`${shipper.id}`,`${shipper.name}`)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="row text-end my-3">
      <small class="font-italic">Chill-Chain assessment by Shingi Mushipe</small>
    </div>

    <!-- Modal components -->
    <Add-shipper-modal
      @update-shippers="getShippers"
      ref="addShipper"></Add-shipper-modal>

    <Show-contacts-modal
      :selected_shipper="selected_shipper"
      :contacts="contacts"
      ref="showContacts"
      @update-shippers="getShippers"></Show-contacts-modal>

    <Edit-shipper-modal
      :selected_shipper="selected_shipper"
      @update-shippers="getShippers"
      ref="editShipper"></Edit-shipper-modal>

    <Delete-shipper-modal
      :selected_shipper="selected_shipper"
      @update-shippers="getShippers"></Delete-shipper-modal>

  </div>
</template>

<script>
export default {
  data() {
    return {
      shippers: [],
      contacts: [],
      selected_shipper: null,
      loading_shippers: false,
    };
  },

  created() {
    this.getShippers();
  },

  methods: {
    getShippers() {
      this.loading_shippers = true;

      axios
        .get("/shippers/get-all/")
        .then((response) => {
          this.shippers = response.data;
          this.loading_shippers = false;
        })
        .catch(function (error) {
          console.log(error);
          this.loading_shippers = false;
        });
    },

    showModal(name, shipper_id, shipper_name) {

      this.selected_shipper = '{"id":' + shipper_id + ', "name":"' + shipper_name + '"}';

      if (name === "show-contacts") {
        this.$refs.showContacts.getContacts(shipper_id);
      }

      if (name === "add-shipper") {
        this.$refs.addShipper.resetForm();
      }

      if (name === "edit-shipper") {
        this.$refs.editShipper.getInfo(shipper_id);
      }

      this.$bvModal.show(`bv-modal-${name}`);
    },
  },
};
</script>

<style scoped>
h3 { font-family: 'Lexend Deca', sans-serif; font-size: 2rem !important;}
h3, small { color: #fff; }
small { font-style: italic; }
.nowrap { white-space: nowrap; }
</style>
