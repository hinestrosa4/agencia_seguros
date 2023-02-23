<template>
    <div class="container">
        <h1>Detalles del Cliente</h1>
        <div class="row">

            <table id="tabla" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Telefono</th>
                        <th>Provincia</th>
                        <th>Localidad</th>
                        <th>Codigo Postal</th>
                        <th>Entidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ cliente.id }}</td>
                        <td>{{ cliente.nombre }}</td>
                        <td>{{ cliente.apellidos }}</td>
                        <td>{{ cliente.telefono }}</td>
                        <td>{{ cliente.provincia_nombre }}</td>
                        <td>{{ cliente.localidad }}</td>
                        <td>{{ cliente.cp }}</td>
                        <td>{{ cliente.entidad }}</td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table id="tablaPol" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Numero de Póliza</th>
                        <th>Importe Recibido</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="poliza in polizas" :key="poliza.id" :class="{
                        'bg-warning': poliza.estado === 'liquidada',
                        'bg-danger': poliza.estado === 'anulada',
                        'bg-info': poliza.estado === 'a_cuenta',
                        'bg-success': poliza.estado === 'cobrada',
                        'bg-primary': poliza.estado === 'pre-anulada',
                    }">
                        <td>{{ poliza.id }}</td>
                        <td>{{ poliza.numero_poliza }}</td>
                        <td>{{ poliza.importe_recibo }}</td>
                        <td v-if="poliza.fecha">{{ new Date(poliza.fecha).toLocaleDateString() }}</td>
                        <td v-else></td>
                        <td>
                            <span v-if="poliza.estado === 'liquidada'">Liquidada</span>
                            <span v-if="poliza.estado === 'anulada'">Anulada</span>
                            <span v-if="poliza.estado === 'a_cuenta'">A cuenta</span>
                            <span v-if="poliza.estado === 'cobrada'">Cobrada</span>
                            <span v-if="poliza.estado === 'pre-anulada'">Pre-anulada</span>
                        </td>
                        <td>{{ poliza.observaciones }}</td>
                        <td v-if="poliza.fecha">
                            <!-- <button class="btn btn-danger" v-on:click="borrarPoliza(poliza.id)">
                  🗑️
                </button> -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop" @click="almacenarPoliza(poliza)">
                                🗑️
                            </button>
                            <router-link :to="{ name: 'editarPoliza', query: { id: poliza.id } }"
                                class="btn btn-warning">📝</router-link>
                        </td>
                        <td v-else></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <router-link :to="{ name: 'listar' }" class="btn btn-secondary">Atrás</router-link>



        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de borrado</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>¿Estás seguro que quieres borrar la poliza <b>{{ selecPoliza.id }}</b>?</h5>
                        <div class="modal-body">
                            <b>Cliente:</b> {{ selecPoliza.id_cliente }}<br>
                            <b>Numero poliza:</b> {{ selecPoliza.numero_poliza }}<br>
                            <b>Importe:</b> {{ selecPoliza.importe_recibo }}<br>
                            <b>Fecha:</b> {{ selecPoliza.fecha }}<br>
                            <b>Estado:</b> {{ selecPoliza.estado }}<br>
                            <b>Observaciones:</b> {{ selecPoliza.observaciones }}<br>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atrás</button>
                        <button class="btn btn-danger" data-bs-dismiss="modal" v-on:click="borrarPoliza(selecPoliza.id)">
                            Borrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
import $ from "jquery";
import "datatables.net";
export default {
    data() {
        return {
            cliente: [],
            selecPoliza: [],
            polizas: [],
        }
    },
    created() {
        this.listarClientes(),
            this.consultarPolizas()
    },
    methods: {
        almacenarPoliza(poliza) {
            this.selecPoliza = poliza
        },
        listarClientes() {
            this.cliente = this.$route.query.cliente;
            fetch("index.php/?consultarCliente=" + this.cliente)
                .then((response) => response.json())
                .then((data) => {
                    this.cliente = data[0];
                })
                .catch(console.log);
        },

        consultarPolizas() {
            this.cliente = this.$route.query.cliente;
            fetch("index.php/?consultarPolizas=" + this.cliente)
                .then((response) => response.json())
                .then((data) => {
                    this.polizas = data;
                    this.$nextTick(function () {
                        $("#tablaPol").DataTable();
                    });
                })
                .catch(console.log);
        },
        borrarPoliza(id) {
            fetch("index.php/?borrarPoliza=" + id)
                .then((resp) => resp.json())
                .then((datosResp) => {
                    console.log(datosResp);
                    this.consultarPolizas();
                })
                .catch(console.log);
        },
    },
};
</script>
