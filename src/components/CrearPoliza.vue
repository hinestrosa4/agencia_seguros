<template>
    <div class="container">
        <div class="card">
            <div class="card-header">Agregar Nueva Poliza</div>
            <div class="card-body">
                <form v-on:submit.prevent="agregarpoliza" class="row g-3 needs-validation" novalidate>

                    <div class="col-md-4">
                        <label for="cliente" class="form-label">Cliente</label>
                        <select id="cliente" class="form-select" v-model="poliza.id_cliente" required
                            v-bind:class="{ 'is-invalid': errorMessages.cliente, 'is-valid': !errorMessages.cliente && poliza.id_cliente }">
                            <option value="" disabled selected>Seleccione un cliente</option>
                            <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                {{ cliente.apellidos }}, {{ cliente.nombre }}
                            </option>
                        </select>
                        <div v-if="errorMessages.cliente" class="invalid-feedback">{{ errorMessages.cliente }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Numero de poliza</label>
                        <input type="text" class="form-control" id="validationCustom02" name="numeroPoliza"
                            v-model="poliza.numeroPoliza" required
                            v-bind:class="{ 'is-invalid': errorMessages.numeroPoliza, 'is-valid': !errorMessages.numeroPoliza && poliza.numeroPoliza }" />
                        <span v-if="errorMessages.numeroPoliza" class="text-danger">{{ errorMessages.numeroPoliza }}</span>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Importe</label>
                        <input type="number" class="form-control" id="validationCustom02" name="importe"
                            v-model="poliza.importe" required
                            v-bind:class="{ 'is-invalid': errorMessages.importe, 'is-valid': !errorMessages.importe && poliza.importe }" />
                        <div v-if="errorMessages.importe" class="invalid-feedback">{{ errorMessages.importe }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="validationCustom02" name="fecha" v-model="poliza.fecha"
                            required
                            v-bind:class="{ 'is-invalid': errorMessages.fecha, 'is-valid': !errorMessages.fecha && poliza.fecha }" />
                        <div v-if="errorMessages.fecha" class="invalid-feedback">{{ errorMessages.fecha }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="estado" class="form-label">Estado de la póliza</label>
                        <select id="estado" name="estado" class="form-select" v-model="poliza.estado" required
                            v-bind:class="{ 'is-invalid': errorMessages.estado, 'is-valid': !errorMessages.estado && poliza.estado }">
                            <option value="cobrada">Cobrada</option>
                            <option value="liquidada">Liquidada</option>
                            <option value="pre-anulada">Pre-anulada</option>
                            <option value="a_cuenta">A cuenta</option>
                            <option value="anulada">Anulada</option>
                        </select>
                        <div v-if="errorMessages.estado" class="invalid-feedback">{{ errorMessages.estado }}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones" class="form-control" name="observaciones"
                            v-model="poliza.observaciones" required
                            v-bind:class="{ 'is-invalid': errorMessages.observaciones, 'is-valid': !errorMessages.observaciones && poliza.observaciones }"></textarea>
                        <div v-if="errorMessages.observaciones" class="invalid-feedback">{{ errorMessages.observaciones }}
                        </div>
                    </div>

                    <div class="col-12" role="group" aria-label="Button group name">
                        <button type="submit" class="btn btn-success">Crear poliza</button>&nbsp;
                        <router-link :to="{ name: 'listarPolizas' }"  class="btn btn-secondary">Atrás</router-link>
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
</style>

<script>
export default {
    data() {
        return {
            poliza: {
                id_cliente: "",
                numeroPoliza: "",
                importe: "",
                fecha: "",
                estado: "",
                observaciones: "",
                estadoPoliza: "alta",
            },
            clientes: [],
            errorMessages: {
                cliente: "",
                numeroPoliza: "",
                importe: "",
                fecha: "",
                estado: "",
                observaciones: "",
            }
        };
    },
    created() {
        this.getClientes();
    },
    methods: {
        getClientes() {
            fetch("index.php/?consultar")
                .then(response => response.json())
                .then(data => {
                    this.clientes = data;
                })
                .catch(error => console.error(error));
        },
        async agregarpoliza() {
            if (this.validateForm()) {
                console.log(this.poliza);

                const respuesta = await fetch("index.php/?insertarPoliza=1", {
                    method: "POST",
                    body: JSON.stringify(this.poliza),
                    headers: {
                        "Content-Type": "application/json"
                    }
                });

                const datosRespuesta = await respuesta.json();
                console.log(datosRespuesta);
                this.$router.push({ name: "listarPolizas" });
            }
        },

        validateForm() {
            let isValid = true;

            if (!this.poliza.id_cliente) {
                this.errorMessages.cliente = "El cliente es requerido.";
                isValid = false;
            } else {
                this.errorMessages.cliente = "";
            }

            if (!this.poliza.numeroPoliza) {
                this.errorMessages.numeroPoliza = "El numero de poliza es requerido.";
                isValid = false;
            } else {
                this.errorMessages.numeroPoliza = "";
            }

            if (!this.poliza.importe) {
                this.errorMessages.importe = "El importe es requerido.";
                isValid = false;
            } else {
                this.errorMessages.importe = "";
            }

            if (!this.poliza.fecha) {
                this.errorMessages.fecha = "La fecha es requerida.";
                isValid = false;
            } else {
                this.errorMessages.fecha = "";
            }

            if (!this.poliza.estado) {
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