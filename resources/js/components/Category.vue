  <template>
    <Navbar />
    <div class="container mt-4">
      <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h1 class="text-center">Manage Categories</h1>
              <hr />

              <!-- Add/Edit Category Form -->
              <v-form v-model="formValid" @submit.prevent="saveCategory">
                <v-text-field label="Category Name" v-model="category.name"
                  :error-messages="validationErrors.name"></v-text-field>

                <v-btn :loading="processing" color="primary" block type="submit">
                  {{ processing ? "Saving..." : (category.id ? "Update Category" : "Add Category") }}
                </v-btn>
              </v-form>

              <!-- List Categories -->
              <div class="mt-4">
                <h3 v-if="categories.length > 0" class="text-center">Categories List</h3>
                <p v-else class="text-center">No categories found.</p> <!-- Message when there are no categories -->
                <ul class="list-group">
                  <li v-for="cat in categories" :key="cat.id"
                    class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ cat.name }}</span>
                    <div>
                      <v-btn class="mr-5"  small color="warning" @click="editCategory(cat)">
                        Edit
                      </v-btn>
                      <v-btn small color="error" @click="deleteCategory(cat.id)">
                        Delete
                      </v-btn>
                    </div>
                  </li>
                </ul>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
<script>
import { mapActions, mapGetters } from "vuex";
import Navbar from "@/components/layouts/Navbar.vue";

export default {
  name: "ManageCategories",
  components: {
    Navbar,
  },
  data() {
    return {
      category: {
        id: null,
        name: "",
      },
      validationErrors: {},
      processing: false,
      formValid: false
    };
  },
  created() {
    this.fetchCategories();
  },
  computed: {
    ...mapGetters({
      categories: "allCategories",
    }),
  },
  methods: {
    ...mapActions({
      fetchCategories: "fetch",
      addCategory: "add",
      updateCategory: "update",
      removeCategory: "delete",
    }),

    async saveCategory() {
      this.processing = true;
      this.validationErrors = {};
      try {
        if (this.category.id) {
          await this.updateCategory(this.category);
        } else {
          await this.addCategory(this.category);
        }
        this.resetForm();
        this.fetchCategories();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.validationErrors = error.response.data.errors;
        }
      } finally {
        this.processing = false;
      }
    },

    editCategory(cat) {

      this.category = { ...cat };
    },

    async deleteCategory(id) {
      if (confirm("Are you sure you want to delete this category?")) {
        await this.removeCategory(id);
        this.fetchCategories();
      }
    },

    resetForm() {
      this.category = {
        id: null,
        name: "",
      };
    },
  },
};
</script>
