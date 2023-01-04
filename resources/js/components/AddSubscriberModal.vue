<template>
  <b-modal id="bv-modal-add-subscriber" hide-footer>
    <template #modal-title><b>Add Subscriber</b></template>
    <div>
      <form>
        <div class="form-group pb-2">
          <label>Email</label>
          <input
            v-model="new_subscriber.email"
            type="text"
            class="form-control mt-2" />
        </div>
        <div class="form-group pb-2">
          <label>Name</label>
          <input
            v-model="new_subscriber.name"
            type="text"
            class="form-control my-2"
          />
        </div>

        <div v-for="(data, index) in fields" :key="index">
          <div class="form-group pb-2">
            <label>{{ data.title }}</label>
            <input
              v-model="custom_fields[data.title]"
              :type="data.type"
              class="form-control mt-2" />
          </div>
        </div>

        <div v-if="saving_subscriber" class="text-center">
          <b-spinner label="Loading..."></b-spinner>
        </div>
        <button
          v-else
          type="button"
          class="btn btn-success w-100 mt-2"
          @click="saveSubscriber()">
          Save
        </button>
      </form>
    </div>
  </b-modal>
</template>

<script>
export default {
  data() {
    return {
      new_subscriber: {},
      custom_fields: {},
      saving_subscriber: false,
      fields: [],
    };
  },

  methods: {
    resetForm() {
      this.new_subscriber = {};
      this.custom_fields = {};
    },

    getFields() {
      axios
        .get("/fields/get")
        .then((response) => {
          this.fields = response.data;
        })
        .catch(function (err) {
          console.log(err);
        });
    },

    saveSubscriber() {
      if (!this.new_subscriber.email) {
        this.customAlert("Please enter subscriber email");
      } else if (!this.new_subscriber.name) {
        this.customAlert("Please enter subscriber name");
      } else {
        this.saving_subscriber = true;

        axios
          .post("/subscribers/new", {
            subscriber: this.new_subscriber,
            custom_fields: this.custom_fields,
          })
          .then((response) => {
            console.log(response.data);

            if (response.data.success) {
              this.$bvModal.hide("bv-modal-add-subscriber");
              this.$emit("update-subscribers");
            } else {
              this.customAlert(response.data.message);
            }

            this.saving_subscriber = false;
          })
          .catch(function (err) {
            console.log(err);
            this.loading = false;
            this.saving_subscriber = false;
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
