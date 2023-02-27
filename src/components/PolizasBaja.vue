<template>
    <div class="contact">
      <h2 class="text-center">Listado de Polizas de Baja</h2>
  
      <!-- <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filtroCollapse"
        aria-expanded="false" aria-controls="filtroCollapse">
        Filtrar Polizas
      </button> -->
  
      <!-- <router-link :to="{ name: 'FiltradoPolizas' }"  class="btn btn-primary">Filtrar Polizas de Baja</router-link>
  
      <div id="filtroCollapse" class="collapse">
        <div class="d-flex justify-content-center">
          <div class="col-md-4">
            <label for="filtro" class="form-label">Filtrado de Polizas</label>
            <select class="form-select" v-model="filtroEstado">
              <option value="">Todos</option>
              <option value="cobrada">Cobrada</option>
              <option value="a cuenta">A cuenta</option>
              <option value="liquidada">Liquidada</option>
              <option value="anulada">Anulada</option>
              <option value="pre-anulada">Pre-anulada</option>
            </select>
          </div>
        </div>
      </div> -->
  
      <br>
      <div class="card">
        <div class="card-header">Lista Polizas de Baja</div>
        <div class="card-body d-flex justify-content-center">
          <table id="tabla" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Numero Poliza</th>
                <th>Importe</th>
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
                <td>
                  {{ poliza.id }}
                </td>
                <td><router-link style="text-decoration: none; color: black;"
                    :to="{ name: 'detalles', query: { cliente: poliza.id_cliente } }">{{ poliza.id_cliente }}</router-link>
                </td>
                <td>{{ poliza.numero_poliza }}</td>
                <td>{{ poliza.importe_recibo }}</td>
                <td>{{ new Date(poliza.fecha).toLocaleDateString() }}</td>
                <td>
                  <span v-if="poliza.estado === 'liquidada'">Liquidada</span>
                  <span v-if="poliza.estado === 'anulada'">Anulada</span>
                  <span v-if="poliza.estado === 'a_cuenta'">A cuenta</span>
                  <span v-if="poliza.estado === 'cobrada'">Cobrada</span>
                  <span v-if="poliza.estado === 'pre-anulada'">Pre-anulada</span>
                </td>
  
                <td>{{ poliza.observaciones }}</td>
                <td>
                  <!-- <button class="btn btn-danger" v-on:click="borrarPoliza(poliza.id)">
                    🗑️
                  </button> -->
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                    @click="almacenarPoliza(poliza)">
                    ✔️
                  </button>
                  <router-link :to="{ name: 'editarPoliza', query: { id: poliza.id } }"
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
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación dar de Alta</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <h5>¿Estás seguro que quieres dar de alta la poliza <b>{{ selecPoliza.id }}</b>?</h5>
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
              <button class="btn btn-success" data-bs-dismiss="modal" v-on:click="altaPoliza(selecPoliza.id)">
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
        selecPoliza: [],
        polizas: [],
        // filtroEstado: "",
      };
    },
  
    created: function () {
      this.consultarPolizasBaja();
    },
    // computed: {
    //   polizasFiltradas() {
    //     if (this.filtroEstado === '') {
    //       return this.polizas;
    //     } else {
    //       return this.polizas.filter((poliza) => poliza.estado === this.filtroEstado);
    //     }
    //   }
    // },
  
    methods: {
      almacenarPoliza(poliza) {
        this.selecPoliza = poliza
      },
      consultarPolizasBaja() {
        fetch("index.php/?listaPolizasBaja")
          .then((resp) => resp.json())
          .then((datosResp) => {
            console.log(datosResp);
            this.polizas = [];
            if (typeof datosResp[0].success === "undefined") {
              //si no es undefined..
              this.polizas = datosResp;
            }
            this.$nextTick(function () {
              $("#tabla").DataTable();
            });
          })
          .catch(console.log);
      },
      altaPoliza(id) {
        fetch("index.php/?altaPoliza=" + id)
          .then((resp) => resp.json())
          .then((datosResp) => {
            console.log(datosResp);
            this.consultarPolizasBaja();
          })
          .catch(console.log);
      },
    },
  };
  </script>