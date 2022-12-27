<template>
  <b-modal id="bv-modal-edit-shipper" hide-footer>
    <template #modal-title><b>Edit Shipper</b></template>
    <div>
      <form>
        <div class="form-group pb-2">
          <label>Company Name</label>
          <input v-model="shipper.name" type="text" class="form-control mt-2" />
        </div>
        <div class="form-group pb-2">
          <label>Address</label>
          <input
            v-model="shipper.address"
            type="text"
            class="form-control my-2" />
        </div>
        <div v-if="saving_shipper" class="text-center">
        <b-spinner label="Loading..."></b-spinner>
      </div>
        <button v-else
          type="button"
          class="btn btn-success w-100 mt-2"
          @click="editShipper()">
          Save
        </button>
      </form>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: ["selected_shipper"],

  data() {
    return {
      shipper: {},
      saving_shipper: false,
    };
  },

  methods: {
    getInfo(shipper_id) {
      axios
        .get(`/shippers/get/${shipper_id}`)
        .then((response) => {
          this.shipper = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    editShipper() {
      let selected_shipper = JSON.parse(this.selected_shipper);

      this.shipper.id = selected_shipper.id;

      if (!this.shipper.name) {
        this.customAlert("Please enter company name");
      } else if (!this.shipper.address) {
        this.customAlert("Please enter company address");
      }

      else {

          this.saving_shipper = true;

          axios
            .post("/shippers/update", {
              shipper: this.shipper,
            })
            .then((response) => {
              console.log(response.data);
              this.$emit("update-shippers");
              this.$bvModal.hide("bv-modal-edit-shipper");
              this.saving_shipper = false;
            })
            .catch(function (err) {
              console.log(err);
              this.loading = false;
              this.saving_shipper = false;
            });

      }
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

  },
};
</script>
