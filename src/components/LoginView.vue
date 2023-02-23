<template>
  <section class="vh-100" style="background-color: #9A616D;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block" style="height: 100%;">
                <img
                  src="images/torreLogin.png"
                  alt="login form" class="img-fluid"
                  style="border-radius: 1rem 0 0 1rem; object-fit: cover; height: 100%;" />
              </div>

              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">
                  <form @submit.prevent="login">
                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <img src="images/logo.png" width="250px" alt="">
                    </div>
                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Inicia sesión con tu cuenta</h5>
                    <div class="form-outline mb-2">
                      <input type="email" v-model="email" placeholder="Email" required id="form2Example17"
                        class="form-control form-control-lg" />
                      <label class="form-label" for="form2Example17">Email</label>
                    </div>
                    <div v-if="errorEmail" class="alert alert-danger">
                      Email incorrecto
                    </div>
                    <div class="form-outline mb-2">
                      <input type="password" v-model="password" placeholder="Contraseña" required id="form2Example27"
                        class="form-control form-control-lg" />
                      <label class="form-label" for="form2Example27">Contraseña</label>
                    </div>
                    <div v-if="errorPassword" class="alert alert-danger">
                      Contraseña incorrecta
                    </div>
                    <div class="pt-1 mb-2">
                      <button type="submit" class="btn btn-primary mt-3">Login</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>



<script>
export default {
  data() {
    return {
      email: "",
      password: "",
      errorEmail: false,
      errorPassword: false,
    };
  },
  methods: {
    async login() {
      this.errorEmail = false;
      this.errorPassword = false;
      const response = await fetch(
        "index.php/?login=" + this.email
      );
      const usuarios = await response.json();
      if (usuarios.length > 0 && usuarios[0].pass === this.password) {
        localStorage.setItem("estoyLogueado", true);
        this.$router.push({ name: "listar" });
      } else {
        this.errorEmail = true;
        this.errorPassword = true;
        localStorage.removeItem("estoyLogueado");
      }
    },
  },
};
</script>