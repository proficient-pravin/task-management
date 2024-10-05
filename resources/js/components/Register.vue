<template>
    <v-app>
      <v-main>
        <v-container fluid fill-height>
          <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
              <v-card>
                <v-card-title class="text-center">Register</v-card-title>
                <v-card-text>
                  <v-form v-model="formValid">

                    <v-text-field
                      label="Name"
                      v-model="user.name"
                      :error-messages="validationErrors.name"
                      required
                    ></v-text-field>
  
                    <v-text-field
                      label="Email"
                      v-model="user.email"
                      :error-messages="validationErrors.email"
                      required
                    ></v-text-field>
  
                    <v-text-field
                      label="Password"
                      v-model="user.password"
                      type="password"
                      :error-messages="validationErrors.password"
                      required
                    ></v-text-field>
  
                    <v-text-field
                      label="Confirm Password"
                      v-model="user.password_confirmation"
                      type="password"
                      :error-messages="validationErrors.password_confirmation"
                      required
                    ></v-text-field>
  
                    <v-btn color="primary" block :loading="processing" @click="register">
                      {{ processing ? "Please wait" : "Register" }}
                    </v-btn>

                    <v-row justify="center" class="mt-2">
                      <span>Already have an account? </span>
                      <router-link to="{ name: 'login' }" style="text-decoration: none">
                        Login Now!
                      </router-link>
                    </v-row>

                  </v-form>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-main>
    </v-app>
  </template>
  
  <script>
  import { mapActions } from 'vuex';
  import axios from 'axios';
  
  export default {
    name: 'register',
    data() {
      return {
        user: {
          name: "",
          email: "",
          password: "",
          password_confirmation: ""
        },
        validationErrors: {},
        processing: false
      };
    },
    created() {
      console.log("created");
    },
    methods: {
      ...mapActions({
        signIn: 'auth/login'
      }),
      async register() {
        this.processing = true;
        await axios.get('/sanctum/csrf-cookie');
        await axios.post('/register', this.user).then(response => {
          this.validationErrors = {};
          this.signIn();
        }).catch(({ response }) => {
          if (response.status === 422) {
            this.validationErrors = response.data.errors;
            this.$toast.open({ message: response.data.message, type: 'error', position: "top-right" });
          } else {
            this.validationErrors = {};
            this.$toast.open({ message: response.data.message, type: 'error', position: "top-right"});
          }
        }).finally(() => {
          this.processing = false;
        });
      }
    }
  }
  </script>
  