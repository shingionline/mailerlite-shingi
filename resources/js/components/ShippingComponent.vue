<template>
  <div class="container">
    <div class="row py-4">
      <div class="col"><h3>List of Subscribers</h3></div>
      <div class="col text-end">
        <button
          type="button"
          class="btn btn-success"
          @click="showModal('add-subscriber', null)">
          Add subscriber
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
            <th scope="col">Company Name</th>
            <th scope="col">Address</th>
            <th scope="col">Primary Contact Name</th>
            <th scope="col">Primary Contact Number</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(subscriber, index) in subscribers" :key="index">
            <th>{{ subscriber.name }}</th>
            <td>{{ subscriber.address }}</td>
            <td>
              <div v-if="subscriber.contacts[0]">
                {{ subscriber.contacts[0].name }}
              </div>
            </td>
            <td>
              <div v-if="subscriber.contacts[0]">
                {{ subscriber.contacts[0].phone }}
              </div>
            </td>
            <td class="nowrap">
              <button
                type="button"
                class="btn btn-primary"
                title="Show contacts"
                @click="showModal('show-contacts', `${subscriber.id}`, `${subscriber.name}`)">
                <i class="fas fa-user"></i>
              </button>
              <button
                type="button"
                class="btn btn-success"
                title="Edit subscriber"
                @click="showModal('edit-subscriber', `${subscriber.id}`, `${subscriber.name}`)">
                <i class="fas fa-edit"></i>
              </button>
              <button
                type="button"
                class="btn btn-danger"
                title="Delete subscriber"
                @click="
                  showModal('delete-subscriber',`${subscriber.id}`,`${subscriber.name}`)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="row text-end my-3">
      <small class="font-italic">Mailerlite assessment by Shingi Mushipe</small>
    </div>

    <!-- Modal components -->
    <Add-subscriber-modal
      @update-subscribers="getSubscribers"
      ref="addSubscriber"></Add-subscriber-modal>

    <Show-contacts-modal
      :selected_subscriber="selected_subscriber"
      :contacts="contacts"
      ref="showContacts"
      @update-subscribers="getSubscribers"></Show-contacts-modal>

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

      this.selected_subscriber = '{"id":' + subscriber_id + ', "name":"' + subscriber_name + '"}';

      if (name === "show-contacts") {
        this.$refs.showContacts.getContacts(subscriber_id);
      }

      if (name === "add-subscriber") {
        this.$refs.addSubscriber.resetForm();
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
h3 { font-family: 'Lexend Deca', sans-serif; font-size: 2rem !important;}
h3, small { color: #fff; }
small { font-style: italic; }
.nowrap { white-space: nowrap; }
</style>
