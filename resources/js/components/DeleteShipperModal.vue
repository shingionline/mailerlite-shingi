<template>
  <b-modal id="bv-modal-delete-shipper" hide-footer>
    <template #modal-title><b>Delete Shipper</b></template>
    <div>
      <h6 class="font-weight-bold">
        <b>Are you sure you want to delete this shipper?</b>
      </h6>

      <p v-if="selected_shipper">
        <b>{{ JSON.parse(selected_shipper).name }}</b>
      </p>

      <button
        type="button"
        class="btn btn-danger w-100 mt-2"
        @click="deleteShipper()">
        Yes, delete
      </button>
      <!-- cancel button -->
      <button
        type="button"
        class="btn btn-secondary w-100 mt-2"
        @click="$bvModal.hide('bv-modal-delete-shipper')">
        Cancel
      </button>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: ["selected_shipper"],

  methods: {
    deleteShipper() {
      let selected_shipper = JSON.parse(this.selected_shipper);

      axios
        .delete(`/shippers/delete/${selected_shipper.id}`)
        .then((response) => {
          console.log(response.data);
          this.$emit("update-shippers");
          this.$bvModal.hide("bv-modal-delete-shipper");
        })
        .catch(function (err) {
          console.log(err);
          this.loading = false;
        });
    },
  },
};
</script>