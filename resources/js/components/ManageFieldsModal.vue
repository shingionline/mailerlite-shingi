<template>
  <b-modal id="bv-modal-manage-fields" hide-footer>
    <template #modal-title><b>Manage Fields</b></template>

    <div v-if="add_field">
      <form>
        <div class="form-group pb-2">
          <label>Title</label>
          <input
            v-model="new_field.title"
            type="text"
            class="form-control mt-2" />
        </div>
        <div class="form-group pb-2">
          <label>Type</label>
          <select v-model="new_field.type" class="form-control my-2">
            <option
              v-for="(option, index) in options"
              :value="option"
              :key="index">
              {{ option }}
            </option>
          </select>
        </div>
        <div v-if="saving_field" class="text-center">
          <b-spinner label="Loading..."></b-spinner>
        </div>
        <button
          v-else
          type="button"
          class="btn btn-success w-100 mt-2"
          @click="saveField()">
          Save
        </button>
      </form>

      <div class="text-center mt-2">
        <button
          type="button"
          class="btn btn-danger btn-sm"
          @click="add_field = false">
          Cancel
        </button>
      </div>
    </div>

    <div v-else>
      <div class="text-center mb-2">
        <button
          type="button"
          class="btn btn-primary btn-sm"
          @click="showAddField()">
          Add new field
        </button>
      </div>

      <div v-if="fields.length === 0" class="p-2 text-center">
        No fields found
      </div>

      <table class="table table-hover mb-0" v-else>
        <thead>
          <tr>
            <th scope="col">Title</th>
            <th scope="col">Type</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(field, index) in fields" :key="index">
            <td>{{ field.title }}</td>
            <td>
              <span :class="'badge badge-pill'">{{ field.type }}</span>
            </td>
            <td>
              <button
                type="button"
                class="btn btn-danger btn-sm"
                title="Delete field"
                @click="deleteField(`${field.id}`)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </b-modal>
</template>

<script>
export default {
  data() {
    return {
      fields: [],
      add_field: false,
      new_field: {},
      saving_field: false,
      options: ["date", "number", "string", "boolean"],
    };
  },

  methods: {
    showAddField() {
      this.new_field = {};
      this.add_field = true;
    },

    getFields() {
      axios
        .get("/fields/get")
        .then((response) => {
          this.fields = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    showAddContact() {
      this.new_field = {};
      this.add_contact = true;
    },

    saveField() {
      if (!this.new_field.title) {
        this.customAlert("Please enter field title");
      } else if (!this.new_field.type) {
        this.customAlert("Please select field type");
      } else {
        this.saving_field = true;

        axios
          .post("/fields/new", { field: this.new_field })
          .then((response) => {
            console.log(response.data);
            this.getFields();
            this.add_field = false;
            this.saving_field = false;
          })
          .catch(function (err) {
            console.log(err);
            this.loading = false;
            this.saving_field = false;
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

    deleteField(field_id) {
      axios
        .delete(`/fields/delete/${field_id}`)
        .then((response) => {
          console.log(response.data);
          this.getFields();
        })
        .catch(function (err) {
          console.log(err);
          this.loading = false;
        });
    },
  },
};
</script>

<style scoped>
.badge {
  font-weight: normal;
  text-transform: uppercase;
  background-color: rgb(0, 0, 0);
  letter-spacing: 0.1em;
  font-size: 0.75em;
}
</style>
