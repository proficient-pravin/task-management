// store/tasks.js
import axios from 'axios';

const state = {
  tasks: [], // Array to hold all tasks
  loading: false, // To manage loading states (optional)
  error: null, // To hold error messages (optional)
};

const getters = {
  allTasks: (state) => state.tasks, // Get all tasks
  isLoading: (state) => state.loading, // Check if loading
  errorMessage: (state) => state.error, // Get error message
};

const actions = {
  async fetchTasks({ commit }) {
    commit('SET_LOADING', true); // Start loading
    try {
      const response = await axios.get('/api/tasks'); // Adjust the endpoint as needed
      commit('setTasks', response?.data?.data); // Adjust the response mapping as needed
    } catch (error) {
      commit('SET_ERROR', error.message); // Set error message
    } finally {
      commit('SET_LOADING', false); // End loading
    }
  },
  async addTask({ dispatch }, task) {
    await axios.post('/api/tasks', task); // Adjust the endpoint as needed
    dispatch('fetchTasks'); // Re-fetch tasks after adding
  },
  async updateTask({ dispatch }, task) {
    await axios.put(`/api/tasks/${task.id}`, task); // Adjust the endpoint as needed
    dispatch('fetch'); // Re-fetch tasks after updating
  },
  async deleteTask({ dispatch }, id) {
    await axios.delete(`/api/tasks/${id}`); // Adjust the endpoint as needed
    dispatch('fetch'); // Re-fetch tasks after deletion
  },
  async toggleCompletion({ dispatch }, taskId) {
    await axios.patch(`/api/tasks/${taskId}/toggle`); // Adjust the endpoint as needed
    dispatch('fetch'); // Re-fetch tasks after toggling completion
  },
};

const mutations = {
  setTasks: (state, tasks) => (state.tasks = tasks), // Set tasks in state
  SET_LOADING: (state, loading) => (state.loading = loading), // Set loading state
  SET_ERROR: (state, error) => (state.error = error), // Set error message
};

export default {
  state,
  getters,
  actions,
  mutations,
};
