<template>
    <div class="container">
        <div class="card">
            <div class="card-header">Agregar Nuevo Cliente</div>
            <div class="card-body">
                <form v-on:submit.prevent="agregarCliente" class="row g-3 needs-validation" novalidate>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="validationCustom02" name="nombre"
                            v-model="cliente.nombre" required
                            v-bind:class="{ 'is-invalid': errorMessages.nombre, 'is-valid': !errorMessages.nombre && cliente.nombre }" />
                        <span v-if="errorMessages.nombre" class="text-danger">{{ errorMessages.nombre }}</span>

                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="validationCustom02" name="apellidos"
                            v-model="cliente.apellidos" required
                            v-bind:class="{ 'is-invalid': errorMessages.apellidos, 'is-valid': !errorMessages.apellidos && cliente.apellidos }" />
                        <div v-if="errorMessages.apellidos" class="invalid-feedback">{{ errorMessages.apellidos }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="validationCustom02" name="telefono"
                            v-model="cliente.telefono" required
                            v-bind:class="{ 'is-invalid': errorMessages.telefono, 'is-valid': !errorMessages.telefono && cliente.telefono }" />
                        <div v-if="errorMessages.telefono" class="invalid-feedback">{{ errorMessages.telefono }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Provincia</label>
                        <select class="form-select" v-model="cliente.provincia" required
                            v-bind:class="{ 'is-invalid': errorMessages.provincia, 'is-valid': !errorMessages.provincia && cliente.provincia }">
                            <option value="" disabled selected>Selecciona una provincia</option>
                            <option v-for="provincia in provincias" :key="provincia.id" :value="provincia.id">
                                {{ provincia.provincia }}
                            </option>
                        </select>
                        <div v-if="errorMessages.provincia" class="invalid-feedback">{{ errorMessages.provincia }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Localidad</label>
                        <select class="form-select" v-model="cliente.localidad" required
                            v-bind:class="{ 'is-invalid': errorMessages.localidad, 'is-valid': !errorMessages.localidad && cliente.localidad }">
                            <option value="" disabled selected>Selecciona una localidad</option>
                            <option v-for="localidad in localidades" :key="localidad" :value="localidad">
                                {{ localidad }}
                            </option>

                        </select>
                        <div v-if="errorMessages.localidad" class="invalid-feedback">{{ errorMessages.localidad }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Codigo Postal</label>
                        <input type="text" class="form-control" id="validationCustom02" name="cp" v-model="cliente.cp"
                            required
                            v-bind:class="{ 'is-invalid': errorMessages.cp, 'is-valid': !errorMessages.cp && cliente.cp }" />
                        <div v-if="errorMessages.cp" class="invalid-feedback">{{ errorMessages.cp }}</div>
                    </div>

                    <!-- <div class="col-md-4">
                        <label for="validationCustom02">Entidad</label>
                        <select id="entityType" name="entityType" class="form-select" v-model="cliente.entidad" required
                            v-bind:class="{ 'is-invalid': errorMessages.entidad, 'is-valid': !errorMessages.entidad && cliente.entidad }">
                            <option value="" disabled selected>Selecciona una opción</option>
                            <option value="particular">Particular</option>
                            <option value="empresa">Empresa</option>
                        </select>
                        <div v-if="errorMessages.entidad" class="invalid-feedback">
                            {{ errorMessages.entidad }}
                        </div>
                    </div> -->

                    <div class="col-md-4">
                        <label for="validationCustom02">Entidad</label>
                        <div>
                            <label>
                                <input type="radio" id="particular" name="entityType" v-model="cliente.entidad"
                                    value="particular" required
                                    v-bind:class="{ 'is-invalid': errorMessages.entidad, 'is-valid': !errorMessages.entidad && cliente.entidad }">
                                Particular
                            </label>
                        </div>
                        <div>
                            <label>
                                <input type="radio" id="empresa" name="entityType" v-model="cliente.entidad" value="empresa"
                                    required
                                    v-bind:class="{ 'is-invalid': errorMessages.entidad, 'is-valid': !errorMessages.entidad && cliente.entidad }">
                                Empresa
                            </label>
                        </div>
                        <div v-if="errorMessages.entidad" class="invalid-feedback">
                            {{ errorMessages.entidad }}
                        </div>
                    </div>


                    <div class="col-12" role="group" aria-label="Button group name">
                        <button type="submit" class="btn btn-success">Crear cliente</button>&nbsp;
                        <router-link :to="{ name: 'listar' }"  class="btn btn-secondary">Atrás</router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
.is-valid {
    border-color: green;
}

.is-invalid {
    border-color: red;
}

.invalid-feedback {
  display: block;
  color: red;
}

</style>

<script>
export default {
    data() {
        return {
            cliente: {
                nombre: "",
                apellidos: "",
                telefono: "",
                localidad: "",
                cp: "",
                provincia: "",
                entidad: "",
                estado: "alta",
            },
            provincias: [],
            clienteLocalidades: [],
            localidades: [],
            filteredLocalidades: [],
            errorMessages: {
                nombre: "",
                provincia: "",
                localidad: "",
            }
        };
    },
    created() {
        this.getProvincias();
    },
    watch: {
        'cliente.provincia': function () {
            this.getLocalidades();

        }
    },
    methods: {
        getLocalidades() {
            if (this.cliente.provincia) {
                fetch(`index.php/?consultar_localidades&provincia=${encodeURIComponent(this.cliente.provincia)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Eliminar duplicados
                        this.localidades = [...new Set(data.map(item => item.municipio))];
                        console.log(this.localidades)
                    })
                    .catch(error => console.error(error));
            } else {
                this.localidades = [];
            }
        },

        getProvincias() {
            fetch("index.php/?consultar_provincias")
                .then(response => response.json())
                .then(data => {
                    this.provincias = data;
                })
                .catch(error => console.error(error));
        },
        async agregarCliente() {
            if (this.validateForm()) {
                this.validateForm()
                console.log(this.cliente);

                const respuesta = await fetch("index.php/?insertar=1", {
                    method: "POST",
                    body: JSON.stringify(this.cliente),
                    headers: {
                        "Content-Type": "application/json"
                    }
                });

                const datosRespuesta = await respuesta.json();
                console.log(datosRespuesta);
                this.$router.push({ name: "listar" });
            }
        },
        validateForm() {
            let isValid = true;

            if (!this.cliente.nombre) {
                this.errorMessages.nombre = "El nombre es requerido.";
                isValid = false;
            } else {
                this.errorMessages.nombre = "";
            }

            if (!this.cliente.apellidos) {
                this.errorMessages.apellidos = "Los apellidos son requeridos.";
                isValid = false;
            } else {
                this.errorMessages.apellidos = "";
            }

            if (!this.cliente.telefono) {
                this.errorMessages.telefono = "El telefono es requerido.";
                isValid = false;
            } else {
                this.errorMessages.telefono = "";
            }

            if (!this.cliente.localidad) {
                this.errorMessages.localidad = "La localidad es requerida.";
                isValid = false;
            } else {
                this.errorMessages.localidad = "";
            }

            if (!this.cliente.cp) {
                this.errorMessages.cp = "El codigo postal es requerido.";
                isValid = false;
            } else {
                this.errorMessages.cp = "";
            }

            if (!this.cliente.provincia) {
                this.errorMessages.provincia = "La provincia es requerida.";
                isValid = false;
            } else {
                this.errorMessages.provincia = "";
            }

            if (!this.cliente.entidad) {
                this.errorMessages.entidad = "La entidad es requerida.";
                isValid = false;
            } else {
                this.errorMessages.entidad = "";
            }

            if (!this.cliente.estado) {
                this.errorMessages.estado = "El estado es requerido.";
                isValid = false;
            } else {
                this.errorMessages.estado = "";
            }

            return isValid;
        },
    }
};
</script>