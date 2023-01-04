<template>
  <b-modal id="bv-modal-edit-subscriber" hide-footer>
    <template #modal-title><b>Edit Subscriber</b></template>
    <div>
      <form>
        <div class="form-group pb-2">
          <label>Email</label>
          <input v-model="subscriber.email" type="text" class="form-control mt-2" />
        </div>
        <div class="form-group pb-2">
          <label>Name</label>
          <input v-model="subscriber.name" type="text" class="form-control mt-2" />
        </div>

        <div class="form-group pb-2">
          <label>State</label>
          <select v-model="subscriber.state" class="form-control my-2">
          <option v-for="(option, index) in options" :value="option" :key="index">
            {{ option }}
          </option>
        </select>
        </div>

          <div v-for="(data, index) in subscriber.field_values" :key="index">
            <div class="form-group pb-2">
              <label>{{data.field.title}}</label>
              <input v-model="custom_fields[index].value" :type="data.field.type" class="form-control mt-2" />
            </div>
          </div>

        <div v-if="saving_subscriber" class="text-center">
        <b-spinner label="Loading..."></b-spinner>
        </div>

        <button v-else
          type="button"
          class="btn btn-success w-100 mt-2"
          @click="editSubscriber()">
          Save
        </button>
      </form>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: ["selected_subscriber"],

  data() {
    return {
      subscriber: {},
      custom_fields: {},
      saving_subscriber: false,
      options: [ 'active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed' ]
    };
  },

  methods: {
    getInfo(subscriber_id) {
      axios
        .get(`/subscribers/get/${subscriber_id}`)
        .then((response) => {
          this.subscriber = response.data;
          this.custom_fields = this.subscriber.field_values;
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    editSubscriber() {
      let selected_subscriber = JSON.parse(this.selected_subscriber);

      this.subscriber.id = selected_subscriber.id;

      if (!this.subscriber.email) {
        this.customAlert("Please enter subscriber email");
      } else if (!this.subscriber.name) {
        this.customAlert("Please enter subscriber name");
      } else if (!this.subscriber.state) {
        this.customAlert("Please enter subscriber state");
      }

      else {

          this.saving_subscriber = true;

          axios
            .post("/subscribers/update", {
              subscriber: this.subscriber,
              custom_fields: this.custom_fields
            })
            .then((response) => {
              console.log(response.data);
              this.$emit("update-subscribers");
              this.$bvModal.hide("bv-modal-edit-subscriber");
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
