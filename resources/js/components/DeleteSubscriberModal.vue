<template>
  <b-modal id="bv-modal-delete-subscriber" hide-footer>
    <template #modal-title><b>Delete Subscriber</b></template>
    <div>
      <h6 class="font-weight-bold">
        <b>Are you sure you want to delete this subscriber?</b>
      </h6>

      <p v-if="selected_subscriber">
        <b>{{ JSON.parse(selected_subscriber).name }}</b>
      </p>

      <button
        type="button"
        class="btn btn-danger w-100 mt-2"
        @click="deleteSubscriber()">
        Yes, delete
      </button>
      <!-- cancel button -->
      <button
        type="button"
        class="btn btn-secondary w-100 mt-2"
        @click="$bvModal.hide('bv-modal-delete-subscriber')">
        Cancel
      </button>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: ["selected_subscriber"],

  methods: {
    deleteSubscriber() {
      let selected_subscriber = JSON.parse(this.selected_subscriber);

      axios
        .delete(`/subscribers/delete/${selected_subscriber.id}`)
        .then((response) => {
          console.log(response.data);
          this.$emit("update-subscribers");
          this.$bvModal.hide("bv-modal-delete-subscriber");
        })
        .catch(function (err) {
          console.log(err);
          this.loading = false;
        });
    },
  },
};
</script>