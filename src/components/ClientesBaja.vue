<template>
    <div class="contact">
      <h2>Listado de Clientes de baja</h2>
  
      <div class="card">
        <div class="card-header">Lista Clientes de baja</div>
        <div class="card-body d-flex justify-content-center">
          <table id="tabla" class="table table-bordered table-hover">
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
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="cliente in clientes" :key="cliente.id">
                <td>{{ cliente.id }}</td>
                <td>{{ cliente.nombre }}</td>
                <td>{{ cliente.apellidos }}</td>
                <td>{{ cliente.telefono }}</td>
                <td>{{ cliente.provincia_nombre }}</td>
                <td>{{ cliente.localidad }}</td>
                <td>{{ cliente.cp }}</td>
                <td>{{ cliente.entidad }}</td>
                <td>
                  <!-- <button class="btn btn-danger" v-on:click="borrarCliente(cliente.id)">
                    🗑️
                  </button> -->
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                    @click="almacenarCliente(cliente)">
                    ✔️
                  </button>
                  <router-link :to="{ name: 'detalles', query: { cliente: cliente.id } }"
                    class="btn btn-primary">👀</router-link>
                  <router-link :to="{ name: 'editarCliente', query: { id: cliente.id } }"
                    class="btn btn-warning">📝</router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- Modal -->
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de dar de Alta</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <h5>¿Estás seguro que quieres dar de alta a este cliente <b>{{ selecCliente.id }}</b>?</h5>
              <div class="modal-body">
                  <b>Nombre:</b> {{ selecCliente.nombre }}<br>
                  <b>Apellidos:</b> {{ selecCliente.apellidos }}<br>
                  <b>Teléfono:</b> {{ selecCliente.telefono }}<br>
                  <b>Localidad:</b> {{ selecCliente.localidad }}<br>
                  <b>Código Postal:</b> {{ selecCliente.cp }}<br>
                  <b>Entidad:</b> {{ selecCliente.entidad === 'empresa' ? 'Empresa' : 'Particular' }}
              </div>
  
  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atrás</button>
              <button class="btn btn-success" data-bs-dismiss="modal" v-on:click="altaCliente(selecCliente.id)">
                Dar de Alta
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
  import "bootstrap/dist/css/bootstrap.min.css";
  import "bootstrap/dist/js/bootstrap.bundle.min.js";
  export default {
    data() {
      return {
        selecCliente: [],
        clientes: [],
      };
    },
    created: function () {
      this.consultarClientes();
    },
    methods: {
      almacenarCliente(cliente) {
        this.selecCliente = cliente
      },
      //http://localhost/empleados/
      consultarClientes() {
        fetch("index.php/?consultarBaja")
          .then((resp) => resp.json())
          .then((datosResp) => {
            console.log(datosResp);
            this.clientes = [];
            if (typeof datosResp[0].success === "undefined") {
              //si no es undefined..
              this.clientes = datosResp;
            }
            this.$nextTick(function () {
              $("#tabla").DataTable();
            });
          })
          .catch(console.log);
      },
      altaCliente(id) {
          fetch("index.php/?alta=" + id)
            .then((resp) => resp.json())
            .then((datosResp) => {
              console.log(datosResp);
              this.consultarClientes();
            })
            .catch(console.log);
      },
      verDetalles(cliente) {
        this.$router.push({ name: 'detalles', params: { cliente } });
      }
    },
  };
  </script>
  