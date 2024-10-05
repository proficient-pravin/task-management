// store/categories.js
import axios from 'axios';

const state = {
  categories: [],
};

const getters = {
  allCategories: (state) => state.categories,
};

const actions = {
  async fetch({ commit }) {
    const response = await axios.get('/api/categories'); // Adjust the endpoint as needed    
    commit('setCategories', response?.data?.data); // Make sure to map the response correctly
  },
  async add({ dispatch }, category) {
    await axios.post('/api/categories', category); // Adjust the endpoint as needed
    dispatch('fetch'); // Re-fetch categories after adding
  },
  async update({ dispatch }, category) {
    await axios.put(`/api/categories/${category.id}`, category); // Adjust the endpoint as needed
    dispatch('fetch'); // Re-fetch categories after updating
  },
  async delete({ dispatch }, id) {
    await axios.delete(`/api/categories/${id}`); // Adjust the endpoint as needed
    dispatch('fetch'); // Re-fetch categories after deletion
  },
};

const mutations = {
  setCategories: (state, categories) => (state.categories = categories),
};

export default {
  state,
  getters,
  actions,
  mutations,
};
