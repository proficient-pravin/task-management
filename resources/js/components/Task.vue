<template>
  <Navbar />
  <div class="container mt-4">
    <div class="row">
      <div class="">
        <div class="card shadow-sm">
          <div class="card-body">
            <h1 class="text-center">Manage Tasks</h1>
            <hr />
            <!-- Add/Edit Task Form -->
            <v-form @submit.prevent="saveTask">
              <div class="row">
                <div class="col-md-4">
                  <v-text-field
                    label="Task Title"
                    v-model="task.title"
                    :error-messages="validationErrors.title"
                    required
                  ></v-text-field>
                </div>
                <div class="col-md-4">
                  <v-text-field
                    label="Description"
                    v-model="task.description"
                    :error-messages="validationErrors.description"
                    required
                  ></v-text-field>
                </div>
                <div class="col-md-4">
                  <v-text-field
                    label="Due Date"
                    v-model="task.due_date"
                    type="date"
                    :error-messages="validationErrors.due_date"
                    required
                  ></v-text-field>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <v-select
                    label="Category"
                    v-model="task.category_id"
                    :items="categories"
                    item-title="name"
                    item-value="id"
                    :error-messages="validationErrors.category_id"
                    required
                  ></v-select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <v-btn :loading="processing" color="primary" block type="submit">
                    {{ processing ? "Saving..." : (task.id ? "Update Task" : "Add Task") }}
                  </v-btn>
                </div>
              </div>
            </v-form>

            <!-- List Tasks -->
            <div class="mt-4">
              <h3 v-if="tasks.length > 0" class="text-center">Tasks List</h3>
              <p v-else class="text-center">No tasks found.</p>

              <div v-if="tasks.length">
                <!-- Task Headers -->
                <div class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                  <span class="column-title">Title</span>
                  <span class="column-description">Description</span>
                  <span class="column-category">Category</span>
                  <span class="column-due-date">Due Date</span>
                  <span class="column-status">Status</span>
                  <span class="column-actions text-right">Actions</span>
                </div>

                <!-- Task List -->
                <ul class="list-group">
                  <li
                    v-for="task in tasks"
                    :key="task.id"
                    class="list-group-item d-flex justify-content-between align-items-center"
                  >
                    <span class="column-title">{{ task.title }}</span>
                    <span class="column-description">{{ task.description }}</span>
                    <span class="column-category">{{ task?.category?.name }}</span>
                    <span class="column-due-date">{{ task.due_date }}</span>
                    <span class="column-status">
                      <v-btn
                        :color="task.is_completed ? 'success' : 'warning'"
                        small
                        @click="toggleTaskStatus(task)"
                      >
                        {{ task.is_completed ? 'Complete' : 'Incomplete' }}
                      </v-btn>
                    </span>

                    <div class="column-actions text-right">
                      <v-btn class="mr-2" small color="warning" @click="editTask(task)">
                        Edit
                      </v-btn>
                      <v-btn small color="error" @click="deleteTask(task.id)">
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
  </div>
</template>

<style scoped>
/* Set specific widths for columns to maintain alignment */
.column-title {
  width: 15%;
  text-align: left;
}

.column-description {
  width: 25%;
  text-align: left;
}

.column-category {
  width: 15%;
  text-align: left;
}

.column-due-date {
  width: 15%;
  text-align: left;
}

.column-status {
  width: 10%;
  text-align: left;
}

.column-actions {
  width: 20%;
  text-align: right;
}

</style>

<script>
import { mapActions, mapGetters } from "vuex";
import Navbar from "@/components/layouts/Navbar.vue";

export default {
  name: "ManageTasks",
  components: {
    Navbar,
  },
  data() {
    return {
      task: {
        id: null,
        title: "",
        description: "",
        due_date: "",
        category_id: null,
        is_completed: false,
      },
      validationErrors: {},
      processing: false,
    };
  },
  created() {
    this.fetchTasks(); 
  },
  computed: {
    ...mapGetters({
      tasks: "allTasks", 
      categories: "allCategories", 
    }),
  },
  methods: {
    ...mapActions({
      fetchTasks: "fetchTasks",
      fetchCategories: "fetchCategories",
      addTask: "addTask",
      updateTask: "updateTask",
      removeTask: "deleteTask",
    }),

    async saveTask() {
      this.processing = true;
      this.validationErrors = {};
      try {
        if (this.task.id) {
          await this.updateTask(this.task);
        } else {
          await this.addTask(this.task);
        }
        this.resetForm();
        this.fetchTasks();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.validationErrors = error.response.data.errors;
        }
      } finally {
        this.processing = false;
      }
    },

    editTask(task) {
      this.task = { ...task };
    },

    async toggleTaskStatus(task) {
      try {
        task.is_completed = task.is_completed ? 0 : 1;
        await this.updateTask(task); 
      } catch (error) {
        console.error("Error updating task status:", error);
      }
    },

    async deleteTask(id) {
      if (confirm("Are you sure you want to delete this task?")) {
        await this.removeTask(id);
        this.fetchTasks();
      }
    },

    resetForm() {
      this.task = {
        id: null,
        title: "",
        description: "",
        due_date: "",
        category_id: null,
        is_completed: false,
      };
    },
  },
};
</script>
