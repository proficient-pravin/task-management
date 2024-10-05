<template>
  
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <router-link :to="{ name: 'dashboard' }" class="nav-link"
                  >Home
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'tasks' }" class="nav-link"
                  >Tasks
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'categories' }" class="nav-link"
                  >Category
                </router-link>
              </li>
            </ul>
            <div class="d-flex">
              <ul class="navbar-nav">
                <li class="nav-item dropdown">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdownMenuLink"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    style="color: #ffffff8c"
                  >
                    {{ user.name }}
                  </a>
                  <div
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="navbarDropdownMenuLink"
                  >
                    <a
                      class="dropdown-item"
                      href="javascript:void(0)"
                      @click="logout"
                      >Logout</a
                    >
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "Navbar",
  data() {
    return {
      user: this.$store.state.auth.user,
    };
  },
  methods: {
    ...mapActions({
      signOut: "auth/logout",
    }),
    async logout() {
      await axios.post("/logout").then(({ data }) => {
        this.signOut();
        this.$router.push({ name: "login" });
      });
    },
  },
};
</script>
