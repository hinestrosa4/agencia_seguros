<template>
    <div class="container">
        <div class="card">
            <div class="card-header">Editar Poliza</div>
            <div class="card-body">
                <form v-on:submit.prevent="actualizarPoliza" class="row g-3 needs-validation" novalidate>

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
                        <input type="text" class="form-control" id="validationCustom02" name="numero_poliza"
                            v-model="poliza.numero_poliza" required
                            v-bind:class="{ 'is-invalid': errorMessages.numero_poliza, 'is-valid': !errorMessages.numero_poliza && poliza.numero_poliza }" />
                        <span v-if="errorMessages.numero_poliza" class="text-danger">{{ errorMessages.numero_poliza }}</span>
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">importe_recibo</label>
                        <input type="number" class="form-control" id="validationCustom02" name="importe_recibo"
                            v-model="poliza.importe_recibo" required
                            v-bind:class="{ 'is-invalid': errorMessages.importe_recibo, 'is-valid': !errorMessages.importe_recibo && poliza.importe_recibo }" />
                        <div v-if="errorMessages.importe_recibo" class="invalid-feedback">{{ errorMessages.importe_recibo }}</div>
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
                        <button type="submit" class="btn btn-success">Editar poliza</button>&nbsp;
                        <router-link :to="{ name: 'listarPolizas' }"  class="btn btn-secondary">Atrás</router-link>

                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style></style>
<script>
export default {
    data() {
        return {
            poliza: {
                id:"",
                id_cliente: "",
                numero_poliza: "",
                importe_recibo: "",
                fecha: "",
                estado: "",
                observaciones: "",
                estadoPoliza: "alta",
            },
            clientes: [],
            errorMessages: {
                cliente: "",
                numero_poliza: "",
                importe_recibo: "",
                fecha: "",
                estado: "",
                observaciones: "",
            }
        };
    },
    created() {
        this.detallesPoliza()
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
        detallesPoliza() {
            let polizaID = this.$route.query.id;
            fetch("index.php/?consultarPoliza=" + polizaID)
                .then((response) => response.json())
                .then((data) => {
                    console.log(data[0]);
                    this.poliza = data[0];
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async actualizarPoliza() {
            const polizaActualizada = {
                id:this.poliza.id,
                id_cliente: this.poliza.id_cliente,
                numero_poliza: this.poliza.numero_poliza,
                importe_recibo: this.poliza.importe_recibo,
                fecha: this.poliza.fecha,
                estado: this.poliza.estado,
                observaciones: this.poliza.observaciones,
                estadoPoliza: this.poliza.estadoPoliza,
            };
            console.log(polizaActualizada);
            await fetch(
                "index.php/?actualizar_poliza=" + this.$route.params.id,
                {
                    method: "POST",
                    body: JSON.stringify(polizaActualizada),
                }
            )
                .then((respuesta) => respuesta.json())
                .then((datosRespuesta) => {
                    console.log(datosRespuesta);
                    this.$router.push({ name: "listarPolizas" });
                });
        },
    }
};
</script>