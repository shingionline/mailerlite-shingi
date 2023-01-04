<template>
  <div class="container">
    <div class="row py-4">
      <div class="col"><h3>List of Subscribers</h3></div>
      <div class="col text-end">
        <button
          type="button"
          class="btn btn-success me-2"
          @click="showModal('add-subscriber', null)">
          Add subscriber
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="showModal('manage-fields', null)">
          Manage fields
        </button>
      </div>
    </div>

    <div class="card shadow">
      <div v-if="loading_subscribers" class="p-2 text-center">
        <b-spinner label="Loading..."></b-spinner>
      </div>

      <div v-else-if="subscribers.length === 0" class="p-2 text-center">
        No subscribers found
      </div>

      <table class="table table-hover mb-0" v-else>
        <thead>
          <tr>
            <th scope="col">Email</th>
            <th scope="col">Name</th>
            <th scope="col">State</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(subscriber, index) in subscribers" :key="index">
            <td>{{ subscriber.email }}</td>
            <td>{{ subscriber.name }}</td>
            <td>
              <span :class="'badge badge-pill ' + subscriber.state">{{
                subscriber.state
              }}</span>
            </td>

            <td class="nowrap">
              <button
                type="button"
                class="btn btn-primary"
                title="View subscriber"
                @click="showModal('view-subscriber',`${subscriber.id}`,`${subscriber.name}`)">
                <i class="fas fa-eye"></i>
              </button>
              <button
                type="button"
                class="btn btn-success"
                title="Edit subscriber"
                @click="showModal('edit-subscriber',`${subscriber.id}`,`${subscriber.name}`)">
                <i class="fas fa-edit"></i>
              </button>
              <button
                type="button"
                class="btn btn-danger"
                title="Delete subscriber"
                @click="showModal('delete-subscriber',`${subscriber.id}`,`${subscriber.name}`)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="row text-end my-3">
      <small class="font-italic">MailerLite assessment by Shingi Mushipe</small>
    </div>

    <!-- Modal components -->
    <Add-subscriber-modal
      @update-subscribers="getSubscribers"
      ref="addSubscriber"></Add-subscriber-modal>

    <View-subscriber-modal
      ref="viewSubscriber"
      @update-subscribers="getSubscribers"></View-subscriber-modal>

    <Manage-fields-modal ref="manageFields"></Manage-fields-modal>

    <Edit-subscriber-modal
      :selected_subscriber="selected_subscriber"
      @update-subscribers="getSubscribers"
      ref="editSubscriber"></Edit-subscriber-modal>

    <Delete-subscriber-modal
      :selected_subscriber="selected_subscriber"
      @update-subscribers="getSubscribers"></Delete-subscriber-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      subscribers: [],
      contacts: [],
      selected_subscriber: null,
      loading_subscribers: false,
    };
  },

  created() {
    this.getSubscribers();
  },

  methods: {
    getSubscribers() {
      this.loading_subscribers = true;

      axios
        .get("/subscribers/get-all/")
        .then((response) => {
          this.subscribers = response.data;
          this.loading_subscribers = false;
        })
        .catch(function (error) {
          console.log(error);
          this.loading_subscribers = false;
        });
    },

    showModal(name, subscriber_id, subscriber_name) {
      this.selected_subscriber =
        '{"id":' + subscriber_id + ', "name":"' + subscriber_name + '"}';

      if (name === "view-subscriber") {
        this.$refs.viewSubscriber.getSubscriber(subscriber_id);
      }

      if (name === "manage-fields") {
        this.$refs.manageFields.getFields();
      }

      if (name === "add-subscriber") {
        this.$refs.addSubscriber.resetForm();
        this.$refs.addSubscriber.getFields();
      }

      if (name === "edit-subscriber") {
        this.$refs.editSubscriber.getInfo(subscriber_id);
      }

      this.$bvModal.show(`bv-modal-${name}`);
    },
  },
};
</script>

<style scoped>
h3 {
  font-family: "Lexend Deca", sans-serif;
  font-size: 2rem !important;
}
h3,
small {
  color: #fff;
}
small {
  font-style: italic;
}
.nowrap {
  white-space: nowrap;
}
.badge {
  background-color: #fff;
  font-weight: normal;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-size: 0.75em;
}
.active {
  background-color: rgb(26, 90, 30);
}
.unsubscribed {
  background-color: rgb(124, 33, 129);
}
.junk {
  background-color: rgb(0, 0, 0);
}
.bounced {
  background-color: rgb(154, 37, 37);
}
.unconfirmed {
  background-color: rgb(80, 80, 80);
}
</style>
