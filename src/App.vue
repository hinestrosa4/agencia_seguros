<template>
  <div id="app">
    <nav v-if="loginSuccess && $route.path !== '/'">
      <img src="images/logo.png" width="300px" alt=""> <br><br>
      <router-link to="/listar">Listar Clientes</router-link> |
      <router-link to="/listarBaja">Listar Clientes Baja</router-link> |
      <router-link to="/listarPolizas">Listar Polizas</router-link> |
      <router-link to="/listarPolizasBaja">Listar Polizas Baja</router-link> |
      <router-link to="/crear">Crear Cliente</router-link> |
      <router-link to="/crearPoliza">Crear Poliza</router-link> |
      <a @click="logout"><img src="images/logout.png" width="30px" alt=""></a>
    </nav>
    <router-view v-if="!loginSuccess" />
    <div v-if="loginSuccess">
      <router-view />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loginSuccess: localStorage.getItem("estoyLogueado") === "true",
    };
  },
  methods: {
    logout() {
      localStorage.removeItem("estoyLogueado");
      this.loginSuccess = false;
      this.$router.push("/");
    },
    login() {
      localStorage.setItem("estoyLogueado", "true");
      this.loginSuccess = true;
      this.$router.push("/listar");
    }
  },
  watch: {
    "$route.path": function () {
      this.loginSuccess = localStorage.getItem("estoyLogueado") === "true";
    }
  }
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #fc3737;
}
</style>
