<template>
  <b-modal id="bv-modal-view-subscriber" hide-footer>
    <template #modal-title><b>View subscriber</b></template>

  <div v-if="loading_subscriber" class="p-2 text-center">
        <b-spinner label="Loading..."></b-spinner>
  </div>

  <div v-else>

        <table class="table table-hover mb-0">
          <tr>
            <td width="30%"><b>Name</b></td>
            <td width="70%">{{ subscriber.name }}</td>
          </tr>
          <tr>
            <td><b>Email</b></td>
            <td>{{ subscriber.email }}</td>
          </tr>
          <tr>
            <td><b>State</b></td>
            <td>{{ subscriber.state }}</td>
          </tr>
          <tr v-for="(data, index) in subscriber.field_values" :key="index">
            <td><b>{{data.field.title}}</b></td>
            <td>{{data.value}}</td>
          </tr>
        </table>

  </div>

  </b-modal>
</template>

<script>
export default {

  data() {
    return {
      loading_subscriber: false,
      subscriber: {},
    };
  },

  methods: {

    getSubscriber(subscriber_id) {
      this.loading_subscriber = true;
      axios
        .get(`/subscribers/get/${subscriber_id}`)
        .then((response) => {
          this.subscriber = response.data;
          this.loading_subscriber = false;
        })
        .catch(function (error) {
          console.log(error);
          this.loading_subscriber = false;
        });
    },

  },
};
</script>
