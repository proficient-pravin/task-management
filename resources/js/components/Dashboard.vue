<template>
  <Navbar />
  <div>    
    <main class="mt-3">
      <v-container fluid fill-height>
        <v-row align="center" justify="center">
          <p class="mb-0">
            You are logged in as <b>{{ user.email }}</b>
          </p>
        </v-row>
      </v-container>
    </main>
  </div>
</template>

<script>
import Navbar from "@/components/layouts/Navbar.vue";
export default {
  name: "dashboard",
  components: {
    Navbar, 
  },
  data() {
    return {
      user: this.$store.state.auth.user,
    };
  },
};
</script>
